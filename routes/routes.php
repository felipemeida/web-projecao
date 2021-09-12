<?php

use CoffeeCode\Router\Router;

$router = new Router(DOMAIN);

// PAGE HOME
$router->namespace("Felmework\Controllers\Region");
$router->get("/", "RegionController:index");

$router->get("/regiao", "RegionController:index");
$router->post("/regiao", "RegionController:store");
$router->get("/regiao/cadastrar", "RegionController:create");
$router->post("/regiao/editar/{id}", "RegionController:update");
$router->get("/regiao/editar/{id}", "RegionController:change");
$router->post("/regiao/deletar/{id}", "RegionController:delete");

// ERROR PAGE

$router->group("error")->namespace("Felmework\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    var_dump($router->error());
   //$router->redirect("/error/{$router->error()}");
}