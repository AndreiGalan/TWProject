<?php

class AnswerDAO
{
    private $conn;

    public function __construct(){
        $this->conn = Database::getConnection();
    }


    public function create(Answer $answer){
        try{
            $stmt = $this->conn->prepare("INSERT INTO answers (question_id,answer_text,is_correct) VALUES (:question_id,:answer_text ,:is_correct)");
            $stmt->bindValue(":question_id", $answer->getQuestionId(), PDO::PARAM_INT);
            $stmt->bindValue(":answer_text", $answer->getAnswerText(), PDO::PARAM_STR);
            $stmt->bindValue(":is_correct", $answer->getIsCorrect(), PDO::PARAM_INT);
            $stmt->execute();

        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }
    public function find($id)
    {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM answers WHERE id = :id");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch();


            if($row){
                return new Answer($row['question_id'], $row['answer_text'], $row['is_correct'], $row['id']);
            }
            else{
                return null;
            }
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function findAllAnswersByQuestionId($questionId)
    {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM answers WHERE question_id = :question_id");
            $stmt->bindValue(":question_id", $questionId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function findById($id)
    {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM answers WHERE id = :id");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();


            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function findAll()
    {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM answers");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function delete($id)
    {
        try{
            $stmt = $this->conn->prepare("DELETE FROM answers WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function update(Answer $answer)
    {

        try{
            $stmt = $this->conn->prepare("UPDATE answers SET question_id = :question_id, answer_text = :answer_text, is_correct = :is_correct WHERE id = :id");
            $stmt->bindValue(":question_id", $answer->getQuestionId(), PDO::PARAM_INT);
            $stmt->bindValue(":answer_text", $answer->getAnswerText(), PDO::PARAM_STR);
            $stmt->bindValue(":is_correct", $answer->getIsCorrect(), PDO::PARAM_INT);
            $stmt->bindValue(":id", $answer->getId(), PDO::PARAM_INT);

            $stmt->execute();
        }catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

}