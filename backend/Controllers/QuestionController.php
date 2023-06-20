<?php

class QuestionController
{
    private $request;
    private $requestMethod;
    private $pictureDAO;

    public function __construct($requestMethod, $request)
    {
        $this->requestMethod = $requestMethod;
        $this->pictureDAO = new PictureDAO();
        $this->request = $request;
    }

    public function processRequest(){
        switch ( $this->requestMethod ){
            case 'POST' :
                //questions/addPicture
                if(isset($this->request[0]) && $this->request[0] == 'addPicture'){
                    echo "add question endpoint";
                    $response = $this->addPicture();
                    break;
                }

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

        $dropboxUploader = new DropboxUploader();
        $pathInDropbox = $dropboxUploader->uploadFile($input['filePath']);

        if($pathInDropbox == null){
            return ErrorHandler::badRequestResponse("Error uploading file to Dropbox");
        }

        $picture = new Picture($input['text'],
            $dropboxUploader->getDownloadLink($pathInDropbox)
        );

        $this->pictureDAO->create($picture);

        $response['body'] = json_encode(array("message" => "Picture added successfully"));

        return $response;

    }

}