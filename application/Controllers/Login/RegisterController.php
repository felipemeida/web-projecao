<?php

namespace Agencia\Close\Controllers\Login;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\User\EmailUser;
use Agencia\Close\Helpers\User\UserIdentification;
use Agencia\Close\Models\Log\RegisterLog;
use Agencia\Close\Models\User\User;
use Agencia\Close\Services\Login\Logon;

class RegisterController extends Controller
{
    public function create(array $params)
    {
        $this->setParams($params);

        $userIdentification = new UserIdentification();
        $identification = $userIdentification->processIdentification($this->params['user_identification']);

        if(EmailUser::verifyIfEmailExist($identification)){
            echo '2';
            return;
        }

        $user = new User();
        $idUser = $user->saveUser($this->params['user_name'], $identification, $this->params['user_sector'], $this->params['user_password']);

        if ($idUser) {
            $registerLog = new RegisterLog();
            $registerLog->save($idUser, $this->params['company']);

            $logon = new Logon();
            $logon->loginByEmail($identification->getIdentification(), $this->params['user_password'], $this->params['company']);
            echo '0';
        } else {
            echo '1';
        }
    }
}