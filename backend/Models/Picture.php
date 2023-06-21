<?php

class Picture implements JsonSerializable
{
    private $id;
    private $text;
    private $pathInDropbox;
    private $downloadLink;


    public function __construct($text, $downloadLink,$pathInDropbox, $id = null)
    {
        $this->text = $text;
        $this->downloadLink = $downloadLink;
        $this->pathInDropbox = $pathInDropbox;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getDownloadLink()
    {
        return $this->downloadLink;
    }

    /**
     * @param mixed $downloadLink
     */
    public function setDownloadLink($downloadLink): void
    {
        $this->downloadLink = $downloadLink;
    }

    /**
     * @return mixed
     */
    public function getPathInDropbox()
    {
        return $this->pathInDropbox;
    }

    /**
     * @param mixed $pathInDropbox
     */
    public function setPathInDropbox($pathInDropbox): void
    {
        $this->pathInDropbox = $pathInDropbox;
    }

    public function getId()
    {
        return $this->id;
    }


    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'downloadLink' => $this->downloadLink,
            'pathInDropbox' => $this->pathInDropbox,
        ];
    }

    public function __toString(): string
    {
        return "Picture: " . $this->id . " , " . $this->text . " , " . $this->downloadLink . " , " . $this->pathInDropbox;
    }
}