<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;

class RegistrationFormValidation
{
    const MIN_USER_LENGTH = 3;
    
    private $validationErrors = [];
    
    public function __construct($username, $password, $first_name, $last_name, $phone, $company)
    {
        return $this->validate($username, $password, $first_name, $last_name, $phone, $company);
    }
    
    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($username, $password, $first_name, $last_name, $phone, $company)
    {
        if (empty($password)) {
            $this->validationErrors[] = 'Password cannot be empty';
        }

        if(empty($first_name)) {
            $this->validationErrors[] = "Please write in your first name";
        }
        //Begin-Changes for vulnerability mitigation-Viswa
        if (strlen($first_name) > "30") {
            $this->validationErrors[] = "First Name should be less than 30 characters";
        }
        if (preg_match('/^[A-Za-z]+$/', $first_name) === 0) {
            $this->validationErrors[] = 'First Name can only contain letters';
        }
        //End-Changes for vulnerability mitigation-Viswa
        
         if(empty($last_name)) {
            $this->validationErrors[] = "Please write in your last name";
        }
        //Begin-Changes for vulnerability mitigation-Viswa
        if (strlen($last_name) > "30") {
            $this->validationErrors[] = "Last Name should be less than 30 characters";
        }
        if (preg_match('/^[A-Za-z]+$/', $last_name) === 0) {
            $this->validationErrors[] = 'Last Name can only contain letters';
        }
        //End-Changes for vulnerability mitigation-Viswa
        
        if(empty($phone)) {
            $this->validationErrors[] = "Please write in your post code";
        }

        if (strlen($phone) != "8") {
            $this->validationErrors[] = "Phone number must be exactly eight digits";
        }

        if(strlen($company) > 0 && (!preg_match('/[^0-9]/',$company)))
        {
            $this->validationErrors[] = 'Company can only contain letters';
        }
        //Begin-Changes for vulnerability mitigation-Viswa
        if (strlen($company) > "30") {
            $this->validationErrors[] = "Company name should be less than 30 characters";
        }
        //End-Changes for vulnerability mitigation-Viswa
        
        if (preg_match('/^[A-Za-z0-9_]+$/', $username) === 0) {
            $this->validationErrors[] = 'Username can only contain letters and numbers';
        }
        
        //Begin-Changes for vulnerability mitigation-Viswa
	    if (strlen($username) > "30") {
            $this->validationErrors[] = "Username should be less than 30 characters";
        }
	    //End-Changes for vulnerability mitigation-Viswa
    }
}
