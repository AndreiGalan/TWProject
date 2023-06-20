<?php

spl_autoload_register('autoloader');
function autoloader(string $name) {

    if (file_exists('../TWProject/backend/Controllers/'.$name.'.php')){
        require_once '../TWProject/backend/Controllers/'.$name.'.php';
    }
    else if (file_exists('../TWProject/backend/Models/'.$name.'.php')){
        require_once '../TWProject/backend/Models/'.$name.'.php';
    }
}

require_once ($_SERVER['DOCUMENT_ROOT'] . '/TWProject/backend/vendor/autoload.php');


$openapi = \OpenApi\Generator::scan($_SERVER['DOCUMENT_ROOT'], '/TWProject/backend/Controllers');
header('Content-Type: application/json');
// save the file
file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/TWProject/backend/documentation-swagger/swagger.json', $openapi->toJson());