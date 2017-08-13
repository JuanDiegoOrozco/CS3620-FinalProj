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
        $var = 0;

        if(count($this->users) == 1){
            //$this->users = $this->users + array(null);
            if($usersGiven->GetEmail() === $this->users[0]->GetEmail())
            {
                unset($this->users[0]);
                return true;
            }
            else
            {
                return false;
            }
        }

        foreach ($this->users as $user)
        {
            if(strcmp($usersGiven->GetEmail(),$user->GetEmail()) !== 0)
            {
                unset($this->users[$var]);
                return true;
            }
            $var ++;
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
}