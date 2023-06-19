<?php

class UserController {
    private $requestMethod;
    private $request;
    private $userDAO;

    public function __construct($requestMethod, $request)
    {
        $this->requestMethod = $requestMethod;
        $this->request = $request;
        $this->userDAO = new UserDAO();
    }

    public function processRequest(): void
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($this->request[0]) && $this->request[0] != 'ranking') {
                    $response = $this->getUser($this->request[0]);
                }
                else if(isset($this->request[0]) && $this->request[0] == 'ranking') {
                    $response = $this->getRanking();
                }
                else {
                    if(!isset($_GET['email']) && !isset($_GET['username'])){
                        $response = $this->getAllUsers();
                        break;
                    } else if(isset($_GET['email'])){
                        $response = $this->getUserByEmail($_GET['email']);
                        break;
                    } else if(isset($_GET['username'])){
                        $response = $this->getUserByUsername($_GET['username']);
                        break;
                    }
                }
                break;
            case 'POST':
                if(isset($this->request[0]) && $this->request[0] == 'send-email'){
                    $response = $this->sendEmailFromContact();
                    break;
                }
                break;
            case 'PUT':
                $response = $this->updateUserFromRequest($this->request[0]);
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->request[0]);
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

    private function getUser($id): array
    {
        $result = $this->userDAO->find($id);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getUserByEmail($email):array{
        $result = $this->userDAO->findByEmail($email);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getUserByUsername($username):array{
        $result = $this->userDAO->findByUsername($username);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getAllUsers(): array{
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $result = $this->userDAO->findAll();
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getRanking(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $result = $this->userDAO->findFirstTenByRanking();
        $response['body'] = json_encode($result);
        return $response;
    }

    private function updateUserFromRequest(mixed $id): array
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $result = $this->userDAO->find($id);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if($this->updatePassword($input)){
            $this->userDAO->updatePassword($input['password'], $id);
            $response['body'] = json_encode(array("Result"=>"User password Updated"));
        }
        else if($this->updatePoints($input)){
            $this->userDAO->updatePoints($input['points'], $id);

            $response['body'] = json_encode(array("Result"=>"User points Updated"));
        } else {
            $user = new User($input['firstName'], $input['lastName'], $input['username']
                , null,  null, $input['email'], null,null, null, null, $id);

            $this->userDAO->update($user);

            $response['body'] = json_encode(array("Result"=>"User personal info Updated"));
        }

        return $response;
    }


    private function deleteUser($id): array
    {
        $result = $this->userDAO->find($id);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }
        $this->userDAO->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode(array("Result"=>"User Deleted"));

        return $response;
    }

    private function updatePassword(array $input): bool
    {
        if (!isset($input['password'])) {
            return false;
        }
        return true;
    }

    private function updatePoints(array $input): bool
    {
        if (!isset($input['points'])) {
            return false;
        }
        return true;
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


}