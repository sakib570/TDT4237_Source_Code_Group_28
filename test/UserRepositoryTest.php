<?php

namespace tdt4237\webapp;

use PHPUnit_Framework_TestCase;
use tdt4237\webapp\models\User;
use tdt4237\webapp\repository\UserRepository;

class UserRepositoryTest extends PHPUnit_Framework_TestCase
{

    private $pdo;

    function setUp()
    {
        $this->pdo = $this->getMockBuilder('PDO')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->pdo->method('query')
            ->willReturn(false);

        $this->repo = new UserRepository($this->pdo);
    }

    /** @test */
    function it_should_call_exec_on_pdo_when_deleting_by_username()
    {
        $this->pdo->expects($this->once())
            ->method('exec');
        
        $this->repo->saveNewUser(new User('onkel', 'skrue'));
    }
    
    /** @test */
    function it_should_call_query_with_SELECT_query()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with($this->equalTo(UserRepository::SELECT_ALL));
        
        $this->repo->all();
    }

}
