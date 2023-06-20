<?php

class DropboxUploader
{
    private const TOKEN_DROPBOX = 'sl.BgufDAoACzw8jtj1wd_QVjLmzU5aMAmEXqnveYFJ0_xgwDECXwZQxtjR6XlXS2R-l2txaYxdzQzfgGDLvqQsBO8Z-Kn87YkiJKdloCTqvmICEzUHCm-vsI8QuoEib_R4fCRF6hDcX4TR';

    public function __construct()
    {
    }

    /**
     * @param $filePath
     * @return ?string - the path of the file in dropbox, or null if the upload was unsuccessful
     */
    public function uploadFile($filePath) : ?string {

        $fp = fopen($filePath, 'rb');
        $size = filesize($filePath);

        $questionDAO = new PictureDAO();
        $fileNameInDropbox = $questionDAO->getNrPictures() . '.jpg';

        $headers = array('Authorization: Bearer ' . self::TOKEN_DROPBOX,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: {"path":"/pictures/'. $fileNameInDropbox . '", "mode":"add"}');

        $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, $size);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        $httpResponse = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        fclose($fp);
        if($httpResponse != 200){
            return null;
        }

        $response = json_decode($response, true);
        return $response['path_display'];
    }
    public function getDownloadLink ($pathFileInDropbox) : string{

        $parameters = array('path' => $pathFileInDropbox);

        $headers = array('Authorization: Bearer ' . self::TOKEN_DROPBOX,
            'Content-Type: application/json');

        $curlOptions = array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($parameters),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => true
        );

        $ch = curl_init('https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings');

        curl_setopt_array($ch, $curlOptions);

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);
        return $this->getDirectDownloadLink($response['url']);

    }

    private function getDirectDownloadLink($dropboxLink) : string {
        // Replace "www.dropbox.com" with "dl.dropboxusercontent.com"
        $modifiedLink = str_replace("www.dropbox.com", "dl.dropboxusercontent.com", $dropboxLink);

        // Append "?dl=1" to indicate direct download
        $directDownloadLink = substr_replace($modifiedLink , '1', -1, 1);

        return $directDownloadLink;
    }


}