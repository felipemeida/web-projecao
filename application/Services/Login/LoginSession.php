<?php

namespace Agencia\Close\Services\Login;

class LoginSession
{
    public function loginUser(array $login)
    {
        $_SESSION = [
            'perfil_id' => $login['id'],
            'perfil_empresa' => $login['empresa'],
            'perfil_tipo' => $login['tipo'],
            'perfil_slug' => $login['slug'],
            'perfil_nome' => $login['nome'],
            'perfil_email' => $login['email'],
            'perfil_imagem' => $login['imagem'],
            'perfil_avatar' => $login['avatar']
        ];
    }

    public function userIsLogged(): bool
    {
        if (isset($_SESSION['perfil_id'])){
            return true;
        }
        return false;
    }
}