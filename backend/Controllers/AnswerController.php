<?php

class AnswerController
{
    private $request;
    private $requestMethod;
    private $answerDAO;

    public function __construct($requestMethod, $request)
    {
        $this->requestMethod = $requestMethod;
        $this->answerDAO = new AnswerDAO();
        $this->request = $request;
    }

    public function processRequest(){
        switch ( $this->requestMethod ){
            case 'GET' :
                //answers/question/{id}
                if(isset($this->request[0]) && $this->request[0] == 'question' && isset($this->request[1])){
                    $response = $this->getAnswersByQuestionId($this->request[1]);
                }
                //answers/{id}
                else if(isset($this->request[0]) && $this->request[0] != 'question'){
                    $response = $this->getAnswerById($this->request[0]);
                }
                //answers
                else{
                    $response = $this->getAllAnswers();
                }
                break;

            case 'DELETE' :
                //answers/{id}
                if(isset($this->request[0])){
                    $response = $this->deleteAnswer($this->request[0]);
                }
                break;

            case 'PUT' :
                //answers/{id}
                if(isset($this->request[0])) {
                    $response = $this->updateAnswer($this->request[0]);
                }
                break;

            case 'POST' :
                //answers
                $response = $this->addAnswer();
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

    private function addAnswer()
    {
        //the body of the request will have the following format:
        // {
        //     "text": "Something informative about the picture",
        //     "filePath" : "local/path/of/the/file"
        // }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $answer = new Answer($input['question_id'], $input['answer_text'], $input['is_correct']);

        $this->answerDAO->create($answer);

        $response['body'] = json_encode(array(
            'message' => 'Answer added successfully'
        ));

        return $response;

    }

    private function deleteAnswer($id)
    {
        //the body of the request will have the following format:
        // {
        //     "id": "id of the picture",
        // }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $answer = $this->answerDAO->find($id);

        if(!$answer){
            return ErrorHandler::notFoundResponse();
        }

        $this->answerDAO->delete($id);

        $response['body'] = json_encode(array(
            'message' => 'Answer deleted successfully'
        ));

        return $response;

    }

    private function updateAnswer($id)
    {
        //the body of the request will have the following format:
        // {
        //     "id": "id of the picture",
        //     "text": "Something informative about the picture"
        // }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $answer = $this->answerDAO->find($id);

        if(!$answer){
            return ErrorHandler::notFoundResponse();
        }

        $answer->setQuestionId($input['question_id']);

        $answer->setAnswerText($input['answer_text']);

        $answer->setIsCorrect($input['is_correct']);

        $this->answerDAO->update($answer);

        $response['body'] = json_encode(array(
            'message' => 'Answer updated successfully'
        ));

        return $response;
    }

    private function getAllAnswers()
    {
        $result = $this->answerDAO->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $response['content_type_header'] = 'Content-Type: application/json';

        $response['body'] = json_encode($result);

        return $response;
    }

    private function getAnswerById($id)
    {
        $result = $this->answerDAO->findById($id);

        if(!$result){
            return ErrorHandler::notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $response['content_type_header'] = 'Content-Type: application/json';

        $response['body'] = json_encode($result);

        return $response;
    }

    private function getAnswersByQuestionId($id)
    {
        $result = $this->answerDAO->findAllAnswersByQuestionId($id);

        if(!$result){
            return ErrorHandler::notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $response['content_type_header'] = 'Content-Type: application/json';

        $response['body'] = json_encode($result);

        return $response;
    }

}