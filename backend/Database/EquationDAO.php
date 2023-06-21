<?php

class EquationDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function create(Equation $equation){
        try{
            $stmt = $this->conn->prepare("INSERT INTO equations (equation_text) VALUES (:equation_text)");
            $stmt->bindValue(":equation_text", $equation->getEquationText(), PDO::PARAM_STR);
            $stmt->execute();

        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function find($id)
    {
        try{
            $stmt = $this->conn->prepare("SELECT * FROM equations WHERE id = :id");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch();

            if($row){
                return new Equation($row['equation_text'], $row['id']);
            }
            else{
                return null;
            }
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function findById($id)
    {
        try {
            $statement = $this->conn->prepare("SELECT * FROM equations WHERE id = :id");
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
            $stmt = $this->conn->prepare("DELETE FROM equations WHERE id = :id");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function update(Equation $equation)
    {

        try {
            $stmt = $this->conn->prepare("UPDATE equations SET equation_text = :equation_text WHERE id = :id");
            $stmt->bindValue(":equation_text", $equation->getEquationText(), PDO::PARAM_STR);
            $stmt->bindValue(":id", $equation->getId(), PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

    }

    public function getAll()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM equations");
            $stmt->execute();

            // set the resulting array to associative
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }
}