<?php

use tdt4237\webapp\models\User;
use tdt4237\webapp\models\Age;

class UserTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->user = new User('luckylucke', 'myshadow');
        $this->user->setUserId(5);
    }

    function testUser()
    {
        $user = $this->user;

        $this->assertEquals($user->getUserId(), 5);
        $this->assertEquals($user->getUsername(), 'luckylucke');

        $user->setUserId(1337);
        $this->assertEquals($user->getUserId(), 1337);
    }

    /** @test
     *  @expectedException \Exception
     */
    function throw_exception_on_negative_age()
    {
        new Age(-20);
    }
}
