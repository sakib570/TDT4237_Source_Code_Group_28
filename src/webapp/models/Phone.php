<?php

namespace tdt4237\webapp\models;

class Phone
{

    private $phone;
    
    public function __construct($phone)
    { 
        $this->phone = $phone;
    }
    
    public function __toString()
    {
        return $this->phone;
    }
}
