<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace tdt4237\webapp;

/**
 * Description of HashTest
 *
 * @author tor
 */
class HashTest extends \PHPUnit_Framework_TestCase
{
    private $hash;
    
    function setUp()
    {
        $this->hash = new Hash;
    }
    
    public function testHash()
    {
        $password = 'qwerty';
        $hash = $this->hash->make($password);
        
        $this->assertTrue($this->hash->check($password, $hash));
        
    }
}
