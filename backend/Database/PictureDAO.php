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
            $stmt = $this->conn->prepare("INSERT INTO pictures (text,path_in_dropbox,download_link) VALUES (:text,:path_in_dropbox ,:download_link)");
            $stmt->bindValue(":text", $picture->getText());
            $stmt->bindValue(":path_in_dropbox", $picture->getPathInDropbox());
            $stmt->bindValue(":download_link", $picture->getDownloadLink());
            $stmt->execute();

        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }
    public function find($id)
    {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM pictures WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            $row = $stmt->fetch();

            if($row){
                return new Picture($row['text'], $row['download_link'], $row['path_in_dropbox'], $row['id']);
            }
            else{
                return null;
            }
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function delete($id)
    {
        try{
            $stmt = $this->conn->prepare("DELETE FROM pictures WHERE id = :id");
            $stmt->bindValue(":id", $id);
            $stmt->execute();
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function updateText(Picture $picture)
    {

        try{
            $stmt = $this->conn->prepare("UPDATE pictures SET text = :text WHERE id = :id");
            $stmt->bindValue(":text", $picture->getText());
            $stmt->bindValue(":id", $picture->getId());

            $stmt->execute();
        }catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

}