<?php


require_once ($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

include_once "Controllers/UserController.php";
include_once "Controllers/AuthController.php";

include_once "Database/UserDAO.php";

include_once "Models/User.php";
include_once "Dispatcher/Dispatcher.php";



    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    $request = [];
    for($i = 3; $i < count($uri); $i++){
        $request[] = $uri[$i];
    }

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    Dispatcher::dispatch($requestMethod, $request);