<?php

namespace App\domain;

Class Message
{
    protected $message;// string
    protected $sender;// user
    protected $timeDate;// Time object

    public function __construct($user, $message)
    {
        $this->message = $message;
        $this->sender = $user;
        $this->timeDate = date("h:i m/d/Y");
    }

    public function GetMessage()
    {
        return $this->message;
    }

    public function GetSender()
    {
        return $this->sender;// Returns a User Object
    }

    public function GetTimeDate()
    {
        return $this->timeDate;
    }
}