<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController
{

    private $requestMethod;

    private $request;
    private $secret_Key  = '%aaSWvtJ98os_b<IQ_c$j<_A%bo_[xgct+j$d6LJ}^<pYhf+53k^-R;Xs<l%5dF';
    private $domainName = "https://127.0.0.1";
    private $userDAO;

    /**
     * @param $requestMethod
     * @param $request
     */
    public function __construct($requestMethod, $request)
    {
        $this->requestMethod = $requestMethod;
        $this->request = $request;
        $this->userDAO = new UserDAO();
    }

    public function processRequest() {

        switch ($this->requestMethod) {
            case 'POST':
                if(isset($this->request[1]) && $this->request[1] == 'register')
                    $response = $this->register();
                else if(isset($this->request[1]) && $this->request[1] == 'login')
                    $response = $this->login();
                else
                    $response = ErrorHandler::notFoundResponse();
                break;
            default:
                $response = ErrorHandler::notFoundResponse();
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
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!isset($input['email']) || !isset($input['password'])) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $user = $this->userDAO->findByEmail($input['email']);
        if (!$user) {
            return ErrorHandler::notFoundResponse();
        }
        // verify if the password is correct
        if (!$this->userDAO->verifyPassword($input['email'], $input['password'])) {
            $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
            $response['content_type_header'] = 'Content-Type: application/json';
            $response['body'] = json_encode(array("message" => "Wrong password"));
            return $response;
        }

        return $this->createJWT($input['email']);
    }

    private function register(): array{

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateUser($input)){
            return ErrorHandler::unprocessableEntityResponse();
        }


        //check if username already exists
        $userExists = $this->userDAO->findByUsername($input['username']);
        if ($userExists) {
            return ErrorHandler::entityAlreadyExists('user', 'username');
        }

        //check if mail already exists
        $userExists = $this->userDAO->findByEmail($input['email']);
        if ($userExists){
            return ErrorHandler::entityAlreadyExists('user', 'email');
        }

        $user = new User($input['firstName'], $input['lastName'], $input['username'],
            $input['password'], $input['gender'], $input['email'], null, null, null);


        $this->userDAO->create($user);
        $response['body'] = json_encode(array("Result"=>"User Created"));
        return $response;
    }

    private function validateUser(array $input): bool
    {
        if(!isset($input['firstName']) || !isset($input['lastName']) ||  !isset($input['username']) ||
            !isset($input['password']) || !isset($input['gender']) || !isset($input['email']))
        {
            return false;
        }
        return true;
    }

    private function createJWT($email) {
        $secret_Key = $this -> secret_Key;
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+60 minutes')->getTimestamp();
        $domainName = $this -> domainName;

        $request_data = [
            'iat'  => $date->getTimestamp(),         // ! Issued at: time when the token was generated
            'iss'  => $domainName,                   // ! Issuer
            'nbf'  => $date->getTimestamp(),         // ! Not before
            'exp'  => $expire_at,                    // ! Expire
            'userName' => $email,                 // User.php name
        ];

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode(array("token" =>  JWT::encode(
            $request_data,
            $secret_Key,
            'HS512'
        )));


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


}