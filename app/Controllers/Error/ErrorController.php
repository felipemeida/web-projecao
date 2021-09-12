<?php

namespace Felmework\Controllers\Error;

use Felmework\Controllers\Controller;

class ErrorController extends Controller
{
    public function show($params)
    {
        $this->params = $params;
        if(!isset($this->params['message'])){
            $this->params['message'] = 'Empresa não encontrada!';
        }
        $this->render('pages/error/404.twig', [ 'message' => $this->params['message']]);
    }
}