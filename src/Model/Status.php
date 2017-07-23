<?php

namespace Model;

use \DateTime;

class Status
{
    private $id;
    private $user;
    private $content;
    private $date;

    public function __construct($user, $content, DateTime $date, $id = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->content = $content;
        $this->date = $date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getDate()
    {
        return $this->date;
    }
}
