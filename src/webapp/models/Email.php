<?php

namespace tdt4237\webapp\models;

class Email
{
    private $email;
    
    public function __construct($email)
    {
        if (! $this->isValid($email)) {
            throw new \Exception("Invalid email format on email");
        }
        
        $this->email = $email;
    }
    
    public function __toString()
    {
        return $this->email;
    }
    
    private function isValid($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
