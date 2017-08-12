<?php

namespace App\domain;

class ChatRoom
{
    protected $users;// array
    protected $name;// string
    protected $subject;// string
    protected $messages;// array

    public function __construct()
    {
    }

    public function GetName()
    {
        return $this->name;
    }

    public function SetName($nameGiven)
    {
        $this->name = name;
    }

    public function GetSubject()
    {
        return $this->subject;
    }

    public function SetSubject($subjectGiven)
    {
        $this->subject = $subjectGiven;
    }

    public function Join($usersGiven)
    {
        $this->users[] = $usersGiven;
    }

    public function GetMembers()
    {
        return $this->users;
    }

    public function Leave($usersGiven)
    {
        foreach ($this->users as $user)
        {
            if($usersGiven->GetEmail() === $user->GetEmail())
            {
                unset($user);
                return true;
            }
        }
        return false;
    }
}