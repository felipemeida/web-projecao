<?php

namespace Felmework\Controllers\Home;

use Felmework\Controllers\Controller;

class HomeController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->render('pages/home/home.twig', []);
    }
}