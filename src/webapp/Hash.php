<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    //Begin - Change for vulnerability mitigation-Viswa
    //static $salt = "password";
	$salt = mcrypt_create_iv(16,MCRYPT_DEV_URANDOM);
	//End - Change for vulnerability mitigation-Viswa

    public function __construct()
    {
    }

    public static function make($plaintext)
    {
        //Begin - Change for vulnerability mitigation-Viswa
        //return hash('sha1', $plaintext . Hash::$salt);
        return hash('sha512', $plaintext . Hash::$salt);
        //End - Change for vulnerability mitigation-Viswa

    }

    public function check($plaintext, $hash)
    {
        return $this->make($plaintext) === $hash;
    }

}
