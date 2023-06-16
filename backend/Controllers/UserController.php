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
                if (isset($this->request[0])) {
                    $response = $this->getUser($this->request[0]);
                } else {
                    $response = $this->getAllUsers();
                }
                break;
            case 'POST':
//                $response = $this->createUserFromRequest();
                break;
            case 'PUT':
                $response = $this->updateUserFromRequest($this->request[0]);
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->request[0]);
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

    private function getUser($id): array
    {
        $result = $this->userDAO->find($id);
        if (!$result) {
            return $this->notFoundResponse();
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

    private function updateUserFromRequest(mixed $id): array
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $result = $this->userDAO->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if($this->updatePassword($input)){
            $this->userDAO->updatePassword($input['password'], $id);
            $response['body'] = json_encode(array("Result"=>"User password Updated"));
        }
        else if($this->updatePoints($input)){
            echo "update points: ".$input['points'];

            $this->userDAO->updatePoints($input['points'], $id);

            $response['body'] = json_encode(array("Result"=>"User points Updated"));
        } else {
            $user = new User($input['firstName'], $input['lastName'], $input['username']
                , null, $input['gender'], $input['email'], null, null, $id);

            $this->userDAO->update($user);

            $response['body'] = json_encode(array("Result"=>"User personal info Updated"));
        }

        return $response;
    }


    private function deleteUser($id): array
    {
        $result = $this->userDAO->find($id);
        if (!$result) {
            return $this->notFoundResponse();
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

    private function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['content_type_header'] = 'Content-Type: application/json';
        $response['body'] = json_encode(array("Result"=>"Not Found"));
        return $response;
    }
    private function unprocessableEntityResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }
}