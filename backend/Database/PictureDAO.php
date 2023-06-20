<?php

class PictureDAO
{
    private $conn;

    public function __construct(){
        $this->conn = Database::getConnection();
    }

    public function getNrPictures(){
        try{
            $stmt = $this->conn->prepare("SELECT COUNT('c') FROM pictures");
            $stmt->execute();

            return $stmt->fetchColumn();
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function create(Picture $picture){
        try{
            $stmt = $this->conn->prepare("INSERT INTO pictures (text, download_link) VALUES (:text, :download_link)");
            $stmt->bindValue(":text", $picture->getText());
            $stmt->bindValue(":download_link", $picture->getDownloadLink());
            $stmt->execute();

        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }



}