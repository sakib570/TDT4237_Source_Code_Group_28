<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    	//Begin - Change for vulnerability mitigation-Viswa
    	//static $salt = "password"; -- commented
	//static $salt; -- commented
	//End - Change for vulnerability mitigation-Viswa

    public function __construct()
    {
	   //$salt = uniqid(mt_rand(), true); -- commented
    }

    public static function make($plaintext)
    {
        //Begin - Change for vulnerability mitigation-Viswa
        //return hash('sha1', $plaintext . Hash::$salt); --commented
        //return hash('sha512', $plaintext . Hash::$salt);-- commented
	return password_hash($plaintext, PASSWORD_BCRYPT);    
        //End - Change for vulnerability mitigation-Viswa

    }

    public function check($plaintext, $hash)
    {
        //return $this->make($plaintext) === $hash;-- commented
	return password_verify($plaintext, $hash);    
    }
}
