<?php
class Dispatcher{
    public static function dispatch($requestMethod, $request) : void{
        $authController = new AuthController($requestMethod, $request);

        switch($request[0]){
            case 'users':
                $jwt = $authController-> checkJWTExistance();
                $id = $authController -> validateJWT($jwt);

                // delete the first element of the array
                array_shift($request);

                $controller = new UserController($requestMethod, $request, $id);
                $controller->processRequest();
                break;

            case 'auth':

                $authController->processRequest();
                break;

            case 'pictures':

                $jwt = $authController-> checkJWTExistance();
                $id = $authController -> validateJWT($jwt);

                array_shift($request);

                $controller = new PictureController($requestMethod, $request,$id);
                $controller->processRequest();
                break;

            case 'equations':

                $jwt = $authController-> checkJWTExistance();
                $authController -> validateJWT($jwt);

                array_shift($request);

                $controller = new EquationController($requestMethod, $request);
                $controller->processRequest();
                break;

            case 'questions':
                $jwt = $authController-> checkJWTExistance();
                $authController -> validateJWT($jwt);

                array_shift($request);

                $controller = new QuestionController($requestMethod, $request);
                $controller->processRequest();
                break;

            case 'answers':
                $jwt = $authController-> checkJWTExistance();
                $authController -> validateJWT($jwt);

                array_shift($request);

                $controller = new AnswerController($requestMethod, $request);
                $controller->processRequest();
                break;

            default:
                header("HTTP/1.1 404 Not Found");
                exit();
        }
    }
}