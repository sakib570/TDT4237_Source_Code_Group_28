<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    static $salt = "password";


    public function __construct()
    {
    }

    public static function make($plaintext)
    {
        return hash('sha1', $plaintext . Hash::$salt);

    }

    public function check($plaintext, $hash)
    {
        return $this->make($plaintext) === $hash;
    }

}
