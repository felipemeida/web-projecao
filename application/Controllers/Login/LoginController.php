<?php

namespace Agencia\Close\Controllers\Login;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\User\EmailUser;
use Agencia\Close\Helpers\User\Identification;
use Agencia\Close\Models\Log\RegisterLog;
use Agencia\Close\Models\User\User;
use Agencia\Close\Services\Login\Logon;
use Agencia\Close\Services\Oauth\FacebookAuth;
use Agencia\Close\Services\Oauth\FacebookAuthCallback;

class LoginController extends Controller
{
    public array $getFacebookCallback = [];


    public function index(array $params)
    {
        $this->setParams($params);
        //e-mail e senha
        if (!isset($this->params['company'])) {
            $this->params['company'] = 0;
        }

        $logon = new Logon();
        if ($logon->loginByEmail($this->params['email'], $this->params['password'], $this->params['company'])) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function google(array $params)
    {
        $this->setParams($params);
        $this->createUser($this->params['userName'], $this->params['userEmail'], ['google_id' => $this->params['userID']], $this->params['id-company']);

        $logon = new Logon();
        if ($logon->loginByOnlyEmail($this->params['userEmail'], $this->params['slug-company'])) {
            echo '1';
        } else {
            echo 'error';
        }
    }

    public function facebook(array $params)
    {
        $this->setParams($params);
        $facebookAuth = new FacebookAuth();
        $facebookAuth->begin();
        echo $facebookAuth->getUrl();
    }

    public function facebookCallback(array $params)
    {
        $this->setParams($params);

        $facebookAuth = new FacebookAuthCallback();
        $facebookAuth->begin($this->getFacebookCallback);

        if ($facebookAuth->getEmail() !== '') {

            $this->createUser($facebookAuth->getUser()['name'], $facebookAuth->getEmail(), ['face_id' => $facebookAuth->getUser()['id']], $_SESSION['id-company']);

            $logon = new Logon();
            $logon->loginByOnlyEmail($facebookAuth->getEmail());
            echo '<script language="JavaScript">window.opener.location.reload();window.close();</script>';
        } else {
            echo 'error';
        }
    }

    public function logout(array $params)
    {
        $this->setParams($params);

        session_destroy();
        setcookie("CookieLoginEmail", "", time() - 3600);
        setcookie("CookieLoginHash", "", time() - 3600);

        $this->router->redirect("home", [
            "slug-company" => $this->params['slug-company']
        ]);
    }

    private function createUser(string $name, string $email, array $arrayIdentification, $company): void
    {
        $identification = new Identification();
        $identification->setIdentification($email);
        $identification->setType('email');

        if (!EmailUser::verifyIfEmailExist($identification)) {
            $user = new User();
            $createdId = $user->saveUserByOauth($name, $email, $arrayIdentification);

            $registerLog = new RegisterLog();
            $registerLog->save($createdId, $company);
        }
    }
}