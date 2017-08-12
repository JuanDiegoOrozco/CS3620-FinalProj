<?php

namespace App\domain;
class User
{
    protected $id;// TODO
    protected $alias;
    protected $email;
    public function __construct() {}

    public function SetAlias($aliasGiven)
    {
        $this->alias = $aliasGiven;
    }

    public function GetAlias()
    {
        return $this->alias;
    }

    public function SetEmail($emailGiven)
    {
        if(!filter_var($emailGiven, FILTER_VALIDATE_EMAIL))
        {
           throw new $this->Exception('Email invalid');
        }
        else
        {
            $this->email = $emailGiven;
        }

    }

    public function GetEmail()
    {
        return $this->email;
    }
}