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
            $stmt->bindValue(":text", $picture->getText(), PDO::PARAM_STR);
            $stmt->bindValue(":path_in_dropbox", $picture->getPathInDropbox(), PDO::PARAM_STR);
            $stmt->bindValue(":download_link", $picture->getDownloadLink(), PDO::PARAM_STR);
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
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
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
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
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
            $stmt->bindValue(":text", $picture->getText(), PDO::PARAM_STR);
            $stmt->bindValue(":id", $picture->getId(), PDO::PARAM_INT);

            $stmt->execute();
        }catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function findAll(){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pictures");
            $stmt->execute();

            // set the resulting array to associative
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findById(mixed $id)
    {
        try {
            $statement = $this->conn->prepare("SELECT * FROM pictures WHERE id = :id");
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

}