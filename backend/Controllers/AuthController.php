<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @OA\Info(title="My First API", version="0.1")
 */

class AuthController
{

    private $requestMethod;

    /**
     * @var array
     */
    private $request;
    /**
     * @var string
     */
    private $secret_Key  = '%aaSWvtJ98os_b<IQ_c$j<_A%bo_[xgct+j$d6LJ}^<pYhf+53k^-R;Xs<l%5dF';
    /**
     * @var string
     */
    private $domainName = "https://127.0.0.1";
    /**
     * @var UserDAO
     */
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

    /**
     * @return array
     */
    public function processRequest() {

        switch ($this->requestMethod) {
            case 'POST':
                if(isset($this->request[1]) && $this->request[1] == 'register')
                    $response = $this->register();
                else if(isset($this->request[1]) && $this->request[1] == 'login')
                    $response = $this->login();
                else if(isset($this->request[1]) && $this->request[1] == 'reset-password')
                    $response = $this->resetPassword();
                else if(isset($this->request[1]) && $this->request[1] == 'enter-code')
                    $response = $this->validateResetCode();
                else if(isset($this->request[1]) && $this->request[1] == 'change-password')
                    $response = $this->changeNewPassword();
                else if(isset($this->request[1]) && $this->request[1] == 'send-email')
                    $response = $this->sendEmailFromContact();
                else if(isset($this->request[1]) && $this->request[1] == 'verify-email')
                    $response = $this->verifyEmail();
                else
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

        // verify if the email is verified
        if(!$this->userDAO->getVerified($input['email'])) {
            $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
            $response['content_type_header'] = 'Content-Type: application/json';
            $response['body'] = json_encode(array("message" => "Email not verified"));
            return $response;
        }

        return $this->createJWT($input['email'], $user['id']);
    }


    private function register(): array{

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateUser($input)){
            return ErrorHandler::unprocessableEntityResponse();
        }

        $email = $input['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
            $input['password'], $input['gender'], $input['email'], null, null, null, null, null);


        $this->userDAO->create($user);

        $this->sendValidationEmail($input['email']);

        $response['body'] = json_encode(array("Result"=>"User Created"));
        return $response;
    }

    private function sendValidationEmail($email) {
        $emailOriginal = $email;
        $subject = "Inregistration confirmation";
        // hash the email
        $email = password_hash($email, PASSWORD_DEFAULT);
        $message = "Welcome! Before you start playing FruitsOnTheWeb you need to confirm your email adress. Open this link please: http://localhost/TWProject/frontend/html/Verified.html?email=" . urlencode($email);

        $headers = "From: noreply@example.com" . "\r\n" .
            "Reply-To: noreply@example.com" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();


        // Trimite emailul de validare
        mail($emailOriginal, $subject, $message, $headers);
    }

    private function resetPassword() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        // Verify if the user exists in the database
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!isset($input['email'])) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $user = $this->userDAO->findByEmail($input['email']);

        if (!$user) {
            return ErrorHandler::notFoundResponse();
        }

        if(!$this->userDAO->getVerified($input['email'])) {
            $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
            $response['content_type_header'] = 'Content-Type: application/json';
            $response['body'] = json_encode(array("message" => "Email not verified"));
            return $response;
        }

        // Generate a unique reset code and save it in the database
        $resetCode = $this->generateResetCode(); // Funcție pentru generarea unui cod unic
        $this->userDAO->addResetCode($user['email'], $resetCode);

        // Trimite codul de resetare pe adresa de e-mail a utilizatorului
        $this->sendResetCodeByEmail($input['email'], $resetCode); // Funcție pentru trimiterea e-mail-ului

        $response['body'] = json_encode(array("message" => "Reset code sent",
            "email" => $input['email'])
        );

        return $response;
    }

    private function generateResetCode() {
        $reset_code = rand(1000, 9999);

        //check if reset code already exists in DB
        while($this->userDAO->findByResetCode($reset_code)){
            $reset_code = rand(1000, 9999);
        }

        return $reset_code;
    }

    private function sendResetCodeByEmail($email, $resetCode) {
        $subject = "Reset Password";
        $message = "Hello from FruitsOnTheWeb! \nYour reset code is:" . $resetCode;
        $headers = "From: noreply@example.com" . "\r\n" .
            "Reply-To: noreply@example.com" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        mail($email, $subject, $message, $headers);
    }


    private function validateResetCode() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!isset($input['email']) || !isset($input['resetCode'])) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $user = $this->userDAO->findByEmail($input['email']);
        if (!$user) {
            return ErrorHandler::notFoundResponse();
        }

        $resetCode = $this->userDAO->getResetCode($input['email']);
        if ($resetCode != $input['resetCode']) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $response['body'] = json_encode(array(
                "code" => $resetCode,
        ));


        return $response;
    }

    private function verifyEmail() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $email = $input['email'];

        $email = urldecode($email);
        //verify if hash email is equal to the one in the database
        if (!isset($input['email'])) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $user = $this->userDAO->findByHashEmail($email);
        if (!$user) {
            return ErrorHandler::notFoundResponse();
        }

        $this->userDAO->updateVerified($user['email']);

        $response['body'] = json_encode(array(
                "message" => "Email verified",
        ));

        return $response;
    }

    private function changeNewPassword() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

//        $user = $this->validateResetCode();
//        if (!$user) {
//            return ErrorHandler::unprocessableEntityResponse();
//        }

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!isset($input['password'])) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $restCodeFromDB = $this->userDAO->getResetCode($input['email']);

        if($restCodeFromDB == null){
            return ErrorHandler::unprocessableEntityResponse();
        }

        if($restCodeFromDB != $input['code']){
            return ErrorHandler::unprocessableEntityResponse();
        }

        if($this->userDAO->changePassword($input['email'], $input['password']) == false) {

            return ErrorHandler::entityAlreadyExists("user", "password");
        }

        $response['body'] = json_encode(array("message" => "Password changed"));

        $this->userDAO->addResetCode($input['email'], null);

        return $response;
    }

    private function sendEmailFromContact() {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!isset($input['email']) || !isset($input['message']) || !isset($input['name'])) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $name = $input['name'];
        $email = $input['email'];
        $message = $input['message'];

        $transport = new Swift_SmtpTransport('smtp.gmail.com', 587);
        $transport->setUsername('fruitsonthewebcontact@gmail.com');
        $transport->setPassword('fmwyukyvhwjurvea');
        $transport->setEncryption('tls');

        $mailer = new Swift_Mailer($transport);

        // Creați mesajul
        $messageObject = new Swift_Message();
        $messageObject->setSubject('New message from ' . $name);
        $messageObject->setTo('fruitsonthewebcontact@gmail.com', 'Andrei Galan');
        $messageObject->setFrom([$email => $name]);
        $messageObject->setReplyTo([$email => $name]);
        $messageObject->setBody($message);

        $result = $mailer->send($messageObject);

        if ($result) {
            echo 'Mesajul a fost trimis cu succes.';
            $response['body'] = json_encode(array("message" => "Mesajul a fost trimis cu succes."));
        } else {
            echo 'A apărut o eroare la trimiterea mesajului.';
            return ErrorHandler::unprocessableEntityResponse();
        }

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

    private function createJWT($email, $id) {
        $secret_Key = $this -> secret_Key;
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+60 minutes')->getTimestamp();
        $domainName = $this -> domainName;

        $request_data = [
            'iat'  => $date->getTimestamp(),         // ! Issued at: time when the token was generated
            'iss'  => $domainName,                   // ! Issuer
            'nbf'  => $date->getTimestamp(),         // ! Not before
            'exp'  => $expire_at,
            'id' => $id
        ];

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode(array("token" =>  JWT::encode(
            $request_data,
            $secret_Key,
            'HS512'
            ))
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

        return $token->id;
    }


}