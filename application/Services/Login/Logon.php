<?php

namespace Agencia\Close\Services\Login;

use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Log\LoginLog;
use Agencia\Close\Models\Login;
use Agencia\Close\Services\Login\LoginSession;
use Agencia\Close\Services\Login\LoginCookie;

class Logon
{
    public function loginByEmail($email, $password, $company): bool
    {
        $login = new Login();
        $result = $login->getUserByEmailAndPassword($email, $password);
        if ($result->getResult()) {
            $this->actionsAfterFoundUser($result, $company);
            return true;
        } else {
            return false;
        }
    }

    public function loginByOnlyEmail(string $email, string $company = ''): bool
    {
        $login = new Login();
        $result = $login->getUserByEmail($email);
        if ($result->getResult()) {
            $this->actionsAfterFoundUser($result, $company);
            return true;
        } else {
            return false;
        }
    }

    public function loginByCookie(): bool
    {
        if (isset($_COOKIE['CookieLoginEmail'], $_COOKIE['CookieLoginHash'])) {
            $login = new Login();
            $result = $login->getUserByEmailAndCookie($_COOKIE['CookieLoginEmail'], $_COOKIE['CookieLoginHash']);
            if ($result->getResult()) {
                $this->actionAfterFoundUser($result->getResult()[0]);
                return true;
            }
        }
        return false;
    }

    private function actionAfterFoundUser($login)
    {
        $this->saveInSession($login);
    }

    private function loginCookie($idUser, $email)
    {
        $loginCookie = new LoginCookie();
        $loginCookie->createCookie($idUser, $email);
    }

    private function saveInSession(array $login)
    {
        $loginSession = new LoginSession();
        $loginSession->loginUser($login);
    }

    private function saveLog($id, $idCompany)
    {
        $loginLog = new LoginLog();
        $loginLog->save($id, $idCompany);
    }

    private function actionsAfterFoundUser(Read $result, $company): void
    {
        $loginResult = $result->getResult()[0];
        $this->loginCookie($loginResult['id'], $loginResult['email']);
        $this->saveLog($loginResult['id'], $company);
        $this->actionAfterFoundUser($loginResult);
    }
}