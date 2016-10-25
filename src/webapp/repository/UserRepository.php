<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Phone;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\NullUser;
use tdt4237\webapp\models\User;

class UserRepository
{
    const INSERT_QUERY   = "INSERT INTO users(user, pass, first_name, last_name, phone, company, isadmin) VALUES('%s', '%s', '%s' , '%s' , '%s', '%s', '%d')"; //krishna
    const UPDATE_QUERY   = "UPDATE users SET email='%s', first_name='%s', last_name='%s', isadmin='%d', phone ='%s' , company ='%s' WHERE id='%s'"; //krishna
    const FIND_BY_NAME   = "SELECT * FROM users WHERE user = :";
    const DELETE_BY_NAME = "DELETE FROM users WHERE user='%s'";
    const SELECT_ALL     = "SELECT * FROM users";
    const FIND_FULL_NAME   = "SELECT * FROM users WHERE user = :";
   
    const FIND_ATTEMPTS_TIME = "SELECT attempt_count, time FROM attempts WHERE username = '%s'";
    const UPDATE_ATTEMPTS_TIME = "UPDATE attempts SET attempt_count = '%d', time = '%d' WHERE username = '%s'";
    const COUNT_ATTEMPT_USER  = "SELECT count(*) AS row_count FROM attempts WHERE username = '%s'";
    const INSERT_ATTEMPT = "INSERT INTO attempts(username,attempt_count,time) VALUES ('%s','%d','%d')";
    const DELETE_ATTEMPT = "DELETE FROM attempts WHERE username ='%s'";
    

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function checkAttempts($username)
    {
	$query = sprintf(self::COUNT_ATTEMPT_USER,$username );
        error_log( print_r( "check attempt", true ) );
        $result = $this->pdo->prepare($query);
        $result->execute();
        $count = $result->fetch();
        if(intval($count['row_count']) === 1){
          error_log( print_r( "Count more 1", true ) );
	  $query = sprintf(self::FIND_ATTEMPTS_TIME, $username );
          $result = $this->pdo->prepare($query);
          $result->execute();
          $row = $result->fetch();

          if(intval(time()) - intval($row['time'] <=10)){
	     $query = sprintf(self::UPDATE_ATTEMPTS_TIME, intval($row['attempt_count']) + 1, intval(time()),$username);
             $this->pdo->exec($query);
             $row['attempt_count'] = intval($row['attempt_count']) + 1;
	  }else{
             $query = sprintf(self::UPDATE_ATTEMPTS_TIME, 1, intval(time()),$username);
             $this->pdo->exec($query); 
             $row['attempt_count'] = 1;
          }
          

          if($row['attempt_count'] >=3){
             return 1;
	  }
          else
	  {
             return 0;
             	  
          }

	}else{
	    $query = sprintf(self::INSERT_ATTEMPT, $username, 1, intval(time()));
            error_log( print_r( "Inserted", true ) );
            $this->pdo->exec($query);
            return 0;
        }
    
    }
    
    public function userBlock($username){
         
        $query = sprintf(self::COUNT_ATTEMPT_USER,$username );
        error_log( print_r( "Called userBlock", true ) );
        $result = $this->pdo->prepare($query);
        $result->execute();
        $count = $result->fetch();
        if(intval($count['row_count']) === 1){

	  $query = sprintf(self::FIND_ATTEMPTS_TIME, $username );
          $result = $this->pdo->prepare($query);
          $result->execute();
          $row = $result->fetch();
          
          if(intval(time()) - intval($row['time']) > 30){
             $query = sprintf(self::DELETE_ATTEMPT, $username);
             $this->pdo->exec($query); 
             return 0;
          }

          if(intval($row['attempt_count']) >=3){
             error_log( print_r( "Called userBlock12", true ) );
             return 1;
	  }
          else
	  {
             return 0;
             	  
          }
        } 
        error_log( print_r( "0 returned", true ) );
        return 0;      
   }

    public function makeUserFromRow(array $row)
    {
        $user = new User($row['user'], $row['pass'], $row['first_name'], $row['last_name'], $row['phone'], $row['company']);
        $user->setUserId($row['id']);
        $user->setFirstName($row['first_name']);
        $user->setLastName($row['last_name']);
        $user->setPhone($row['phone']);
        $user->setCompany($row['company']);
        $user->setIsAdmin($row['isadmin']);

        if (!empty($row['email'])) {
            $user->setEmail(new Email($row['email']));
        }

        if (!empty($row['phone'])) {
            $user->setPhone(new Phone($row['phone']));
        }

        return $user;
    }

    public function getNameByUsername($username)
    {
	$query = sprintf("%s%s", self::FIND_FULL_NAME, 'username');

        $result = $this->pdo->prepare($query);
        $result->execute(array('username' => $username));
        
/*	$query = sprintf(self::FIND_FULL_NAME, $username);

        $result = $this->pdo->query($query, PDO::FETCH_ASSOC);*/
        $row = $result->fetch();
        $name = $row['first_name'] + " " + $row['last_name'];
        return $name;
    }

    public function findByUser($username)
    {
	$query  = sprintf("%s%s", self::FIND_BY_NAME, 'username');

        $result = $this->pdo->prepare($query);
        $result->execute(array('username' => $username));

/*      $query  = sprintf(self::FIND_BY_NAME, $username);
        $result = $this->pdo->query($query, PDO::FETCH_ASSOC);*/
        $row = $result->fetch();
        
        if ($row === false) {
            return false;
        }

        return $this->makeUserFromRow($row);
    }

    
    
   
    public function deleteByUsername($username)
    {
        return $this->pdo->exec(
            sprintf(self::DELETE_BY_NAME, $username)
        );
    }

    public function all()
    {
        $rows = $this->pdo->query(self::SELECT_ALL);
        
        if ($rows === false) {
            return [];
            throw new \Exception('PDO error in all()');
        }

        return array_map([$this, 'makeUserFromRow'], $rows->fetchAll());
    }

    public function save(User $user)
    {
        if ($user->getUserId() === null) {
            return $this->saveNewUser($user);
        }

        $this->saveExistingUser($user);
    }

    public function saveNewUser(User $user)
    {
        $query = sprintf(
            self::INSERT_QUERY, $user->getUsername(), $user->getHash(), $user->getFirstName(), $user->getLastName(), $user->getPhone(), $user->getCompany(), $user->isAdmin() //krishna
        );

        return $this->pdo->exec($query);
    }

    public function saveExistingUser(User $user)
    {
        $query = sprintf(
            self::UPDATE_QUERY, $user->getEmail(), $user->getFirstName(), $user->getLastName(), $user->isAdmin(), $user->getPhone(), $user->getCompany(), $user->getUserId()
        );

        return $this->pdo->exec($query);
    }

}
