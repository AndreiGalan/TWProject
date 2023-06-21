<?php

class Answer implements JsonSerializable
{
    private $id;
    private $question_id;
    private $answer_text;
    private $is_correct;


    public function __construct($question_id, $answer_text, $is_correct ,$id = null)
    {
        $this->question_id = $question_id;
        $this->answer_text = $answer_text;
        $this->is_correct = $is_correct;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * @param mixed $question_id
     */
    public function setQuestionId($question_id): void
    {
        $this->question_id = $question_id;
    }

    /**
     * @return mixed
     */
    public function getAnswerText(){
        return $this->answer_text;
    }

    /**
     * @param mixed $answer_text
     */
    public function setAnswerText($answer_text): void
    {
        $this->answer_text = $answer_text;
    }

    /**
     * @return mixed
     */
    public function getIsCorrect()
    {
        return $this->is_correct;
    }

    /**
     * @param mixed $is_correct
     */
    public function setIsCorrect($is_correct): void
    {
        $this->is_correct = $is_correct;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'question_id' => $this->question_id,
            'answer_text' => $this->answer_text,
            'is_correct' => $this->is_correct
        ];
    }

    //to string
    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }
}