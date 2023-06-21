<?php

class ErrorHandler
{
    public static function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode(array("Result"=>"Not Found"));
        return $response;
    }

    public static function unprocessableEntityResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    public static function entityNotFound ($entity) : array
    {
        $response['status_code_header'] = 'HTTP/1.1 444 No Response';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode([
            'error' => 'Entity ' . "$entity " . ' not found'
        ]);
        return $response;
    }

    public static function entityAlreadyExists ($entity, $message) : array
    {
        $response['status_code_header'] = 'HTTP/1.1 409 Conflict';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode([
            'error' => 'Entity ' . "$entity " . 'already exists',
            'message' => $message
        ]);
        return $response;
    }

    public static function badRequestResponse($message)
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode([
            'error' => $message
        ]);
        return $response;
    }

}