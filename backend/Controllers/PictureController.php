<?php

class PictureController
{
    private $request;
    private $requestMethod;
    private $pictureDAO;
    private $idUserWhoRequested;

    public function __construct($requestMethod, $request, $id)
    {
        $this->requestMethod = $requestMethod;
        $this->pictureDAO = new PictureDAO();
        $this->request = $request;
        $this->idUserWhoRequested = $id;
    }

    public function processRequest(){
        switch ( $this->requestMethod ){
            case 'GET' :
                //pictures
                if(isset($this->request[0])){
                    $response = $this->getPictureById($this->request[0]);
                } else {
                    $response = $this->getAllPictures();
                }
                break;
            case 'POST' :
                //pictures/create
                if(isset($this->request[0]) && $this->request[0] == 'create'){
                    if($this->idUserWhoRequested == 34){
                        $response = $this->addPicture();
                    }
                    else{
                        $response = ErrorHandler::unauthorizedResponse();
                    }
                }
                break;

            case 'DELETE' :
                //pictures/delete
                if(isset($this->request[0]) && $this->request[0] == 'delete'){
                    if($this->idUserWhoRequested == 34){
                        $response = $this->deletePicture();
                    }
                    else{
                        $response = ErrorHandler::unauthorizedResponse();
                    }
                }
            break;

            case 'PUT' :
                //pictures/update
                if(isset($this->request[0]) && $this->request[0] == 'update'){
                    if($this->idUserWhoRequested == 34){
                        $response = $this->updatePicture();
                    }
                    else{
                        $response = ErrorHandler::unauthorizedResponse();
                    }
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

    private function addPicture()
    {
        //the body of the request will have the following format:
        // {
        //     "text": "Something informative about the picture",
        //     "filePath" : "local/path/of/the/file"
        // }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $dropboxUploader = new DropboxCommand();
        $pathInDropbox = $dropboxUploader->uploadFile($input['filePath']);

        if($pathInDropbox == null){
            return ErrorHandler::badRequestResponse("Error uploading file to Dropbox");
        }

        $picture = new Picture($input['text'],
            $dropboxUploader->getDownloadLink($pathInDropbox),
            $pathInDropbox
        );

        $this->pictureDAO->create($picture);

        $response['body'] = json_encode(array(
            'message' => 'Picture added successfully'
        ));

        return $response;

    }

    private function deletePicture()
    {
        //the body of the request will have the following format:
        // {
        //     "id": "id of the picture",
        // }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $picture = $this->pictureDAO->find($input['id']);
        if(!$picture){
            return ErrorHandler::notFoundResponse();
        }

        $dropboxUploader = new DropboxCommand();

        if(!$dropboxUploader->deleteFile($picture->getPathInDropbox())){//the file was not deleted
            return ErrorHandler::badRequestResponse("Error deleting file from Dropbox");
        }

        $this->pictureDAO->delete($picture->getId());

        $response['body'] = json_encode(array(
            'message' => 'Picture deleted successfully'
        ));
        return $response;

    }

    private function updatePicture()
    {
        //the body of the request will have the following format:
        // {
        //     "id": "id of the picture",
        //     "text": "Something informative about the picture"
        // }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $picture = $this->pictureDAO->find($input['id']);
        if(!$picture){
            return ErrorHandler::notFoundResponse();
        }

        $picture->setText($input['text']);

        $this->pictureDAO->updateText($picture);

        $response['body'] = json_encode(array(
            'message' => 'Picture updated successfully'
        ));

        return $response;

    }

    private function getAllPictures()
    {
        $pictures = $this->pictureDAO->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';

        if(!$pictures){
            return ErrorHandler::notFoundResponse();
        }

        $response['body'] = json_encode($pictures);

        return $response;
    }

    private function getPictureById(mixed $int)
    {
        $picture = $this->pictureDAO->findById($int);
        if(!$picture){
            return ErrorHandler::notFoundResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['content_type_header'] = 'Content-Type: application/json';


        $response['body'] = json_encode($picture);

        return $response;
    }


}