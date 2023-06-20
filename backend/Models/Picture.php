<?php

class Picture
{
    private $id;
    private $text;
    private $downloadLink;


    public function __construct($text, $downloadLink, $id = null)
    {
        $this->text = $text;
        $this->downloadLink = $downloadLink;
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



}