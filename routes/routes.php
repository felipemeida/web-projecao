<?php

use CoffeeCode\Router\Router;

$router = new Router(DOMAIN);

// PAGE HOME
$router->namespace("Agencia\Close\Controllers\Home");
$router->get("/", "HomeController:index");

// ERROR PAGE

$router->group("error")->namespace("Agencia\Close\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    var_dump($router->error());
   //$router->redirect("/error/{$router->error()}");
}