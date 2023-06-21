<?php

class Equation implements JsonSerializable
{
    private $id;
    private $equation_text;


    public function __construct($equation_text, $id = null)
    {
        $this->equation_text = $equation_text;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEquationText()
    {
        return $this->equation_text;
    }

    /**
     * @param mixed $equation_text
     */
    public function setEquationText($equation_text): void
    {
        $this->equation_text = $equation_text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'equation_text' => $this->equation_text,
        ];
    }

    public function __toString(): string
    {
        return "Equation: " . $this->id . " , " . $this->equation_text;
    }
}