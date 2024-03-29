<?php

include_once "Database.php";

class UserDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }
    public function create($user): void
    {
        try
        {
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $username = $user->getUsername();
            $password = $user->getPassword();
            $email = $user->getEmail();
            $gender = $user->getGender();
            $created_at = $user->getCreatedAt();
            $reset_code = $user->getResetCode();

            $cryptPassword = password_hash($password, PASSWORD_DEFAULT);

            // number of users in the database
            $ranking = $this->conn->query("SELECT COUNT(*) FROM users")->fetchColumn() + 1;

            //put the today date
            $created_at = date("Y-m-d H:i:s");

            $verified = 0;


//            $decryptedPassword = password_verify($password, $cryptPassword);

            $statement = $this->conn->prepare("INSERT INTO users (first_name, last_name, username, password, email, gender, ranking, created_at, reset_code, verified)
                        VALUES (:firstName, :lastName, :username, :password, :email, :gender, :ranking, :created_at, :reset_code, :verified)");
            $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $statement->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':password', $cryptPassword, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
            $statement->bindParam(':ranking', $ranking, PDO::PARAM_INT);
            $statement->bindParam(':created_at', $created_at, PDO::PARAM_STR);
            $statement->bindParam(':reset_code', $reset_code, PDO::PARAM_STR);
            $statement->bindParam(':verified', $verified, PDO::PARAM_INT);

            $statement->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findAll(){
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users");
            $stmt->execute();

            // set the resulting array to associative
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findAllEmails(){
        try {
            $stmt = $this->conn->prepare("SELECT email FROM users");
            $stmt->execute();

            // set the resulting array to associative
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findFirstTenByRanking(){
        try {
            //select rank, points, username from users order by rank asc limit 10;
            $stmt = $this->conn->prepare("SELECT TOP 10 ranking, username, points, created_at FROM users ORDER BY ranking ASC, created_at ASC ");
            $stmt->execute();

            // set the resulting array to associative
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function find($id)
    {
        try {
            $statement = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findByUsername($username){
        try{
            $statement = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findByEmail($email){
        try{
            $statement = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function findByHashEmail($hashEmail){
        try{
            $emails = $this->findAllEmails();

            foreach ($emails as $email){
                if (password_verify($email['email'], $hashEmail)){
                    return $this->findByEmail($email['email']);
                }
            }
        }
        catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function verifyPassword($email, $password){
        try {
            $statement = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->execute();

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            $hashedPassword = $user['password'];

            return password_verify($password, $hashedPassword);

        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function update($user): void
    {
        try
        {
            $id = $user->getId();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $username = $user->getUsername();
            $email = $user->getEmail();
            echo "id: $id <br>";
            echo "first name: $firstName <br>";
            echo "last name: $lastName <br>";
            echo "username: $username <br>";
            echo "email: $email <br>";

            $statement = $this->conn->prepare("UPDATE users SET first_name = :firstName, last_name = :lastName,
                 username = :username, email = :email WHERE id = :id");

            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $statement->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);

            $statement->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function updatePassword($password, $id): void
    {
        try
        {
            $cryptPassword = password_hash($password, PASSWORD_DEFAULT);

            $statement = $this->conn->prepare("UPDATE users SET password = :password WHERE id = :id");

            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':password', $cryptPassword, PDO::PARAM_STR);

            $statement->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function updatePoints($points, $id): void
    {
        try
        {
            $statement = $this->conn->prepare("UPDATE users SET points = points + :points WHERE id = :id");

            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':points', $points, PDO::PARAM_INT);

            $statement->execute();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

        // Actualizarea ranking-ului
        $this->updateRanking();
    }

    private function updateRanking(): void
    {
        try
        {
            $query = "SELECT id, points FROM users ORDER BY points DESC";
            $statement = $this->conn->query($query);
            $players = $statement->fetchAll(PDO::FETCH_ASSOC);

            $ranking = 1;
            foreach ($players as $player) {
                $playerId = $player['id'];

                // Actualizarea ranking-ului pentru fiecare jucător
                $query = "UPDATE users SET ranking = :ranking WHERE id = :id";
                $statement = $this->conn->prepare($query);
                $statement->bindParam(':ranking', $ranking, PDO::PARAM_INT);
                $statement->bindParam(':id', $playerId, PDO::PARAM_INT);
                $statement->execute();

                $ranking++;
            }

            RSS::updateRSS();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function updateVerified($email): void
    {
        try
        {
            $statement = $this->conn->prepare("UPDATE users SET verified = 1 WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);

            $statement->execute();
        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }


    public function delete(mixed $id)
    {
        try
        {
            $statement = $this->conn->prepare("DELETE FROM users WHERE id = :id");
            $statement->bindParam(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            $this->updateRanking();
        } catch (PDOException $e) {
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }


    }

    public function addResetCode($email, $code): void
    {
        try
        {
            $statement = $this->conn->prepare("UPDATE users SET reset_code = :code WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':code', $code, PDO::PARAM_STR);

            $statement->execute();
        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function getResetCode($email): string
    {
        try
        {
            $statement = $this->conn->prepare("SELECT reset_code FROM users WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            return $result['reset_code'];
        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function getVerified($email): int
    {
        try
        {
            $statement = $this->conn->prepare("SELECT verified FROM users WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);

            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            return $result['verified'];
        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
    }

    public function changePassword($email, $password): bool
    {
        try
        {
            $statement = $this->conn->prepare("SELECT password FROM users WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);

            $statement->execute();

            if(password_verify($password, $statement->fetch(PDO::FETCH_ASSOC)['password'])){
                return false;
            }

            $cryptPassword = password_hash($password, PASSWORD_DEFAULT);


            $statement = $this->conn->prepare("UPDATE users SET password = :password WHERE email = :email");
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':password', $cryptPassword, PDO::PARAM_STR);

            $statement->execute();

        } catch (PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }
        return true;
    }

    public function findByResetCode($code): bool{
        try{
            $statement = $this->conn->prepare("SELECT * FROM users WHERE reset_code = :code");
            $statement->bindParam(':code', $code, PDO::PARAM_STR);

            $statement->execute();

            if($statement->rowCount() == 0){
                return false;
            }
        }
        catch(PDOException $e){
            trigger_error("Error in " . __METHOD__ . ": " . $e->getMessage(), E_USER_ERROR);
        }

        return true;
    }
}