<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    	//Begin - Change for vulnerability mitigation-Viswa
    	//static $salt = "password"; -- commented
	static $salt;
	//End - Change for vulnerability mitigation-Viswa

    public function __construct()
    {
	   $salt = uniqid(mt_rand(), true); 
    }

    public static function make($plaintext)
    {
        //Begin - Change for vulnerability mitigation-Viswa
        //return hash('sha1', $plaintext . Hash::$salt); --commented
        return hash('sha512', $plaintext . Hash::$salt);
        //End - Change for vulnerability mitigation-Viswa

    }

    public function check($plaintext, $hash)
    {
        return $this->make($plaintext) === $hash;
    }

}
