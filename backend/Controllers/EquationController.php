<?php

class EquationController
{
    private $request;
    private $requestMethod;
    private $equationDAO;

    public function __construct($requestMethod, $request)
    {
        $this->requestMethod = $requestMethod;
        $this->equationDAO = new EquationDAO();
        $this->request = $request;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET' :
                //equations/{id}
                if(isset($this->request[0])){
                    $response = $this->getEquationById($this->request[0]);
                } else {
                    $response = $this->getAllEquations();
                }

                break;
            case 'POST' :
                //equations
                    $response = $this->addEquation();
                break;

            case 'DELETE' :
                //equations
                    if(isset($this->request[0])) {
                        $response = $this->deleteEquation($this->request[0]);
                    } else {
                        $response = ErrorHandler::notFoundResponse();
                    }
                break;

            case 'PUT' :
                //equations
                if(isset($this->request[0])) {
                    $response = $this->updateEquation($this->request[0]);
                } else {
                    $response = ErrorHandler::notFoundResponse();
                }
                break;

            default:
                $response = ErrorHandler::notFoundResponse();
                break;

        }

        header($response['status_code_header']);
        header($response['content_type_header']);

        if($response['body']){
            echo $response['body'];
        }
    }

    private function addEquation()
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if(!isset($input['equation_text']))
            return ErrorHandler::unprocessableEntityResponse();

        $equation = new Equation($input['equation_text']);

        $this->equationDAO->create($equation);

        $response['body'] = json_encode(array(
            'message' => 'Equation added successfully'
        ));

        return $response;
    }

    private function deleteEquation(mixed $id)
    {
        $result = $this->equationDAO->findById($id);
        if(!$result)
            return ErrorHandler::notFoundResponse();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $this->equationDAO->delete($id);

        $response['body'] = json_encode(array(
            'message' => 'Equation deleted successfully'
        ));

        return $response;
    }

    private function updateEquation(mixed $id)
    {
        $result = $this->equationDAO->findById($id);
        if(!$result)
            return ErrorHandler::notFoundResponse();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if(!isset($input['equation_text']))
            return ErrorHandler::unprocessableEntityResponse();

        $equation = new Equation($input['equation_text'], $id);

        $this->equationDAO->update($equation);

        $response['body'] = json_encode(array(
            'message' => 'Equation updated successfully'
        ));

        return $response;
    }

    private function getAllEquations()
    {
        $equations = $this->equationDAO->getAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $response['body'] = json_encode($equations);

        return $response;
    }

    private function getEquationById(mixed $id)
    {
        $result = $this->equationDAO->findById($id);
        if(!$result)
            return ErrorHandler::notFoundResponse();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $response['body'] = json_encode($result);

        return $response;
    }


}
