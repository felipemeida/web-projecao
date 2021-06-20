<?php

namespace Agencia\Close\Models;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Helpers\User\Identification;
use Agencia\Close\Helpers\User\UserIdentification;

class Login extends Model
{
    public function getUserByEmailAndPassword($email, $password): Read
    {
        $password = sha1($password);
        $identification = $this->processIdentification($email);
        $this->read = new Read();
        $this->read->FullRead("SELECT * FROM usuarios WHERE ( email = :email AND senha = :password ) OR ( telefone = :email AND senha = :password )", "email={$identification->getIdentification()}&password={$password}");
        return $this->read;
    }

    public function getUserByEmail($email): Read
    {
        $this->read = new Read();
        $this->read->FullRead("SELECT * FROM usuarios WHERE email = :email", "email={$email}");
        return $this->read;
    }

    private function processIdentification(string $email): Identification
    {
        $userIdentification = new UserIdentification();
        return $userIdentification->processIdentification($email);
    }

    public function getUserByEmailAndCookie($email, $cookie): Read
    {
        $this->read = new Read();
        $this->read->FullRead("SELECT * FROM usuarios WHERE email = :email AND cookie_key = :cookie", "email={$email}&cookie={$cookie}");
        return $this->read;
    }
}