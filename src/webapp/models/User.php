<?php

namespace tdt4237\webapp\models;

class User
{

    protected $userId  = null;
    protected $username;
    protected $hash;
    protected $firstName;
    protected $lastName;
    protected $phone;
    protected $company = null;
    protected $email   = null;
    protected $isAdmin = 0;

    function __construct($username, $hash, $firstName, $lastName, $phone, $company)
    {
        $this->username = $username;
        $this->hash = $hash;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->company = $company;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

     public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company){
        $this->company = $company;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

     public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;

    }

    public function isAdmin()
    {
        //return $this->isAdmin === '1';
          return $this->isAdmin; //krishna
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

}
