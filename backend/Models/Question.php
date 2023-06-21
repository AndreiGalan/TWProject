<?php

class Question implements JsonSerializable
{
    private $id;
    private $question_text;
    private $difficulty;
    private $points;
    private $id_picture;
    private $id_equation;

    public function __construct($question_text, $difficulty, $points, $id_picture, $id_equation, $id = null)
    {
        $this->question_text = $question_text;
        $this->difficulty = $difficulty;
        $this->points = $points;
        $this->id_picture = $id_picture;
        $this->id_equation = $id_equation;
        $this->id = $id;
    }

    /**
     * @return mixed|null
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @param mixed|null $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getQuestionText()
    {
        return $this->question_text;
    }

    /**
     * @param mixed $question_text
     */
    public function setQuestionText($question_text): void
    {
        $this->question_text = $question_text;
    }

    /**
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @param mixed $difficulty
     */
    public function setDifficulty($difficulty): void
    {
        $this->difficulty = $difficulty;
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
    public function getIdPicture()
    {
        return $this->id_picture;
    }

    /**
     * @param mixed $id_picture
     */
    public function setIdPicture($id_picture): void
    {
        $this->id_picture = $id_picture;
    }

    /**
     * @return mixed
     */
    public function getIdEquation()
    {
        return $this->id_equation;
    }

    /**
     * @param mixed $id_equation
     */
    public function setIdEquation($id_equation): void
    {
        $this->id_equation = $id_equation;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'difficulty' => $this->difficulty,
            'points' => $this->points,
            'id_picture' => $this->id_picture,
            'id_equation' => $this->id_equation,
        ];
    }

    public function __toString(): string
    {
        return "Question: " . $this->id . " , " . $this->question_text . " , " . $this->difficulty . " , " . $this->points . " , " . $this->id_picture . " , " . $this->id_equation;
    }
}