<?php


require_once ($_SERVER['DOCUMENT_ROOT'] . '/TWProject/backend/vendor/autoload.php');

include_once "Controllers/UserController.php";
include_once "Controllers/AuthController.php";
include_once "Controllers/ErrorHandler.php";
include_once "Controllers/PictureController.php";
include_once "Controllers/EquationController.php";
include_once "Controllers/QuestionController.php";
include_once "Controllers/AnswerController.php";

include_once "Service/DropboxCommand.php";
include_once "Service/TokenManager.php";

include_once "Database/UserDAO.php";
include_once "Database/PictureDAO.php";
include_once "Database/EquationDAO.php";
include_once "Database/QuestionDAO.php";
include_once "Database/AnswerDAO.php";

include_once "Models/User.php";
include_once "Models/Picture.php";
include_once "Models/Equation.php";
include_once "Models/Question.php";
include_once "Models/Answer.php";

include_once "Dispatcher/Dispatcher.php";



    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    $request = [];
    for($i = 3; $i < count($uri); $i++){
        $request[] = $uri[$i];
    }

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod === 'OPTIONS') {
        // Răspunde la cererea OPTIONS cu un cod de stare HTTP 200 OK
        header('HTTP/1.1 200 OK');
        exit();
    }
    Dispatcher::dispatch($requestMethod, $request);