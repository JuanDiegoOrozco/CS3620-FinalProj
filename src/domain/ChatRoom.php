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
        $this->name = $nameGiven;
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

        foreach ($this->users as $key=>$user)
        {
            if(strcmp($usersGiven->GetEmail(),$user->GetEmail()) == 0)
            {
                unset($this->users[$key]);
                $usersTemp = array_values($this->users);
                $this->users = $usersTemp;// Re-index array
                return true;
            }
        }
        return false;
    }

    public function AddMessage($mess)
    {
        $this->messages[] = $mess;
    }

    public function GetMessages()
    {
        return $this->messages;
    }

    public function RemoveAllMessages()
    {
        unset($this->messages);
        $this->messages = array();
    }
}