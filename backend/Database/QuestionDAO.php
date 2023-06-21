<?php

include_once "Database.php";

class QuestionDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function create($question): void
    {
        try{
            $question_text = $question->getQuestionText();
            $difficulty = $question->getDifficulty();
            $points = $question->getPoints();
            $id_picture = $question->getIdPicture();
            $id_equation = $question->getIdEquation();

            $sql = "INSERT INTO questions (question_text, difficulty, points, id_picture, id_equation) VALUES (:question_text, :difficulty, :points, :id_picture, :id_equation)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":question_text", $question_text, PDO::PARAM_STR);
            $stmt->bindParam(":difficulty", $difficulty, PDO::PARAM_INT);
            $stmt->bindParam(":points", $points, PDO::PARAM_INT);
            $stmt->bindParam(":id_picture", $id_picture, PDO::PARAM_INT);
            $stmt->bindParam(":id_equation", $id_equation, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findAll(){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM questions");
            $stmt->execute();

            // set the resulting array to associative
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findById($id)
    {
        try {
            $statement = $this->conn->prepare("SELECT * FROM questions WHERE id = :id");
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $statement = $this->conn->prepare("DELETE FROM questions WHERE id = :id");
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function update($question): void
    {
        try{
            $id = $question->getId();
            $question_text = $question->getQuestionText();
            $difficulty = $question->getDifficulty();
            $points = $question->getPoints();
            $id_picture = $question->getIdPicture();
            $id_equation = $question->getIdEquation();

            $sql = "UPDATE questions SET question_text = :question_text, difficulty = :difficulty, 
                     points = :points, id_picture = :id_picture, id_equation = :id_equation WHERE id = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":question_text", $question_text, PDO::PARAM_STR);
            $stmt->bindParam(":difficulty", $difficulty, PDO::PARAM_INT);
            $stmt->bindParam(":points", $points, PDO::PARAM_INT);
            $stmt->bindParam(":id_picture", $id_picture, PDO::PARAM_INT);
            $stmt->bindParam(":id_equation", $id_equation, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function getQuestionsByDifficulty($difficulty)
    {
        try {
            $statement = $this->conn->prepare("SELECT TOP 10 * FROM questions WHERE difficulty = :difficulty ORDER BY NEWID();");
            $statement->bindParam(':difficulty', $difficulty, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }
}
