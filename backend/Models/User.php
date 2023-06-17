<?php
class User implements JsonSerializable
{
    private $id;
    private $firstName;

    private $lastName;

    private $username;

    private $gender;

    private $email;

    private $password;

    private $points;

    private $ranking;

    private $created_at;

    private $reset_code;

    public function __construct( $firstName, $lastName, $username, $password,
                                $gender, $email, $points, $ranking , $created_at, $reset_code, $id = null) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->gender = $gender;
        $this->email = $email;
        $this->password = $password;

        $this->points = $points;
        $this->ranking = $ranking;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->reset_code = $reset_code;
    }

    public function getId()
    {
        return $this->id;
    }

    // setter for id
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points): void
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * @param mixed $ranking
     */
    public function setRanking($ranking): void
    {
        $this->ranking = $ranking;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getResetCode()
    {
        return $this->reset_code;
    }

    /**
     * @param mixed $reset_code
     */
    public function setResetCode($reset_code): void
    {
        $this->reset_code = $reset_code;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'username' => $this->username,
            'gender' => $this->gender,
            'email' => $this->email,
            'password' => $this->password,
            'points' => $this->points,
            'ranking' => $this->ranking,
            'created_at' => $this->created_at,
            'reset_code' => $this->reset_code
        ];
    }

    public function __toString(): string
    {
        return "User: " . $this->id . " " . $this->firstName . " " . $this->lastName .
            " " . $this->username . " " . $this->gender . " " . $this->email . " " .
            $this->password . " " . $this->points . " " . $this->ranking . " " . $this->created_at . " " . $this->reset_code;
    }
}