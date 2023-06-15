<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController
{

    private $requestMethod;
    private $secret_Key  = '%aaSWvtJ98os_b<IQ_c$j<_A%bo_[xgct+j$d6LJ}^<pYhf+53k^-R;Xs<l%5dF';
    private $domainName = "https://127.0.0.1";

    private $userDAO;

    /**
     * @param $requestMethod
     */
    public function __construct($requestMethod)
    {
        $this->requestMethod = $requestMethod;
        $this->userDAO = new UserDAO();
    }
    
    public function processRequest() {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->login();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        header($response['content_type_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function login() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        // verify if the user exists in the database
        $user = $this->userDAO->findByUsername($_POST['username']);
        if (!$user) {
            return $this->notFoundResponse();
        }
        // verify if the password is correct
        if (!$this->userDAO->verifyPassword($_POST['username'], $_POST['password'])) {
            $response['header'] = 'HTTP/1.1 401 Unauthorized';
            $response['body'] = json_encode(array("message" => "Wrong password"));
            return $response;
        }

        return $this->createJWT($user['username']);
    }

    private function createJWT($username) {
        $secret_Key = $this -> secret_Key;
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+60 minutes')->getTimestamp();
        $domainName = $this -> domainName;

        $request_data = [
            'iat'  => $date->getTimestamp(),         // ! Issued at: time when the token was generated
            'iss'  => $domainName,                   // ! Issuer
            'nbf'  => $date->getTimestamp(),         // ! Not before
            'exp'  => $expire_at,                    // ! Expire
            'userName' => $username,                 // User.php name
        ];

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = JWT::encode(
            $request_data,
            $secret_Key,
            'HS512'
        );

        return $response;
    }

    function checkJWTExistance () {
        // Check JWT
        if (! preg_match('/Bearer\s(\S+)/', $this -> getAuthorizationHeader(), $matches)) {
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
        }
        return $matches[1];
    }

    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function validateJWT( $jwt ) {
        $secret_Key = $this -> secret_Key;

        try {
            $token = JWT::decode($jwt, new Key($secret_Key, 'HS512'));
        } catch (Exception) {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }
        $now = new DateTimeImmutable();
        $domainName = $this -> domainName;

        if ($token->iss !== $domainName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }
    }

    private function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode(array("Result"=>"Not Found"));
        return $response;
    }

}