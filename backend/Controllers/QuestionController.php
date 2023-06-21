<?php


class QuestionController
{
    private $requestMethod;
    private $request;
    private $questionDAO;
    public function __construct($requestMethod, $request)
    {
        $this->requestMethod = $requestMethod;
        $this->request = $request;
        $this->questionDAO = new QuestionDAO();
    }

    public function processRequest(): void
    {
        switch ($this->requestMethod) {
            case 'GET':
                //questions/{id}
                if(isset($this->request[0])){
                    $response = $this->getQuestionById($this->request[0]);
                }
                //questions
                else {
                    $response = $this->getAllQuestions();
                }
                break;
            case 'POST':
                //questions
                    $response = $this->addQuestion();
                break;
            case 'PUT':
                //questions
                if(isset($this->request[0])) {
                    $response = $this->updateQuestion($this->request[0]);
                } else {
                    $response = ErrorHandler::notFoundResponse();
                }
                break;
            case 'DELETE':
                //questions
                if(isset($this->request[0])){
                    $response = $this->deleteQuestion($this->request[0]);
                }
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

    private function getAllQuestions()
    {
        $result = $this->questionDAO->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';


        $response['body'] = json_encode($result);
        return $response;
    }

    private function getQuestionById($id)
    {
        $result = $this->questionDAO->findById($id);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $response['body'] = json_encode($result);

        return $response;
    }

    private function addQuestion()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (!$this->validateQuestion($input)) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $id_picture = null;
        $id_equation = null;

        if(isset($input['id_picture'])){
            $id_picture = $input['id_picture'];
        }

        if(isset($input['id_equation'])){
            $id_equation = $input['id_equation'];
        }

        if($id_picture == null && $id_equation == null){
            return ErrorHandler::unprocessableEntityResponse();
        }

        $question = new Question($input['question_text'], $input['difficulty'], $input['points'], $id_picture, $id_equation);

        $this->questionDAO->create($question);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['content_type_header'] = 'Content-Type: application/json';

        $response['body'] = json_encode(array(
            'message' => 'Question added successfully'
        ));
        return $response;
    }

    private function deleteQuestion(mixed $id)
    {
        $result = $this->questionDAO->findById($id);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $this->questionDAO->delete($id);

        $response['body'] = json_encode(array(
            'message' => 'Question deleted successfully'
        ));

        return $response;
    }

    private function updateQuestion(mixed $id)
    {
        $result = $this->questionDAO->findById($id);
        if (!$result) {
            return ErrorHandler::notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (!$this->validateQuestion($input)) {
            return ErrorHandler::unprocessableEntityResponse();
        }

        $id_picture = null;
        $id_equation = null;

        if(isset($input['id_picture'])){
            $id_picture = $input['id_picture'];
        }

        if(isset($input['id_equation'])){
            $id_equation = $input['id_equation'];
        }

        if($id_picture == null && $id_equation == null){
            return ErrorHandler::unprocessableEntityResponse();
        }

        $question = new Question($input['question_text'], $input['difficulty'], $input['points'], $id_picture, $id_equation, $id);

        $this->questionDAO->update($question);

        $response['body'] = json_encode(array(
            'message' => 'Question updated successfully'
        ));

        return $response;
    }

    private function validateQuestion(array $input)
    {
        if (!isset($input['question_text'])) {
            return false;
        }
        if (!isset($input['difficulty'])) {
            return false;
        }
        if (!isset($input['points'])) {
            return false;
        }

        return true;
    }

}
