<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Phone;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\NullUser;
use tdt4237\webapp\models\User;

class UserRepository
{
    const INSERT_QUERY   = "INSERT INTO users(user, pass, first_name, last_name, phone, company, isadmin) VALUES(?, ?, ? , ? , ?, ?, ?)"; //krishna
    const UPDATE_QUERY   = "UPDATE users SET email=?, first_name=?, last_name=?, isadmin=?, phone =? , company =? WHERE id=?"; //krishna
    const FIND_BY_NAME   = "SELECT * FROM users WHERE user = :";
    const DELETE_BY_NAME = "DELETE FROM users WHERE user= :";
    const SELECT_ALL     = "SELECT * FROM users";
    const FIND_FULL_NAME   = "SELECT * FROM users WHERE user = :";
   
    const FIND_ATTEMPTS_TIME = "SELECT attempt_count, time FROM attempts WHERE username = :";
    const UPDATE_ATTEMPTS_TIME = "UPDATE attempts SET attempt_count = ?, time = ? WHERE username = ?";
    const COUNT_ATTEMPT_USER  = "SELECT count(*) AS row_count FROM attempts WHERE username = :";
    const INSERT_ATTEMPT = "INSERT INTO attempts(username,attempt_count,time) VALUES (?,?,?)";
    const DELETE_ATTEMPT = "DELETE FROM attempts WHERE username = :";
    

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
        error_log( print_r( "check attempt", true ) );
	
	$query = sprintf("%s%s", self::COUNT_ATTEMPT_USER, 'username');
        $result = $this->pdo->prepare($query);
        $result->execute(array('username' => $username));

        $count = $result->fetch();
        if(intval($count['row_count']) === 1){
          error_log( print_r( "Count more 1", true ) );
	  
	  $query = sprintf("%s%s", self::FIND_ATTEMPTS_TIME, 'username');
          $result = $this->pdo->prepare($query);
          $result->execute(array('username' => $username));

          $row = $result->fetch();

          if(intval(time()) - intval($row['time'] <=10)){
	     //$query = sprintf(self::UPDATE_ATTEMPTS_TIME, intval($row['attempt_count']) + 1, intval(time()),$username);
             $results = $this->pdo->prepare(self::UPDATE_ATTEMPTS_TIME);
             $curr_time = intval(time());
             $try = intval($row['attempt_count']) + 1;
             $results->bindParam(1, $try);
             $results->bindParam(2, $curr_time);
             $results->bindParam(3, $username);
             $results->execute();
             $row['attempt_count'] = intval($row['attempt_count']) + 1;
	  }else{
             //$query = sprintf(self::UPDATE_ATTEMPTS_TIME, 1, intval(time()),$username);
             $results = $this->pdo->prepare(self::UPDATE_ATTEMPTS_TIME);
             $curr_time = intval(time());
             $try = 1;
             $results->bindParam(1, $try);
             $results->bindParam(2, $curr_time);
             $results->bindParam(3, $username);
             $results->execute();
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
	    //$query = sprintf(self::INSERT_ATTEMPT, $username, 1, intval(time()));
            $results = $this->pdo->prepare(self::INSERT_ATTEMPT);
            $curr_time = intval(time());
            $try = 1;
            $results->bindParam(1, $username);
            $results->bindParam(2, $try);
            $results->bindParam(3, $curr_time);
            error_log( print_r( "Inserted", true ) );
            $results->execute();
            return 0;
        }
    
    }
    
    public function userBlock($username){
         
     //   $query = sprintf("%s%s",self::COUNT_ATTEMPT_USER, 'username');
        error_log( print_r( "Called userBlock", true ) );
   /*     $result = $this->pdo->prepare($query);
        $result->execute(array('username' => $username));
        $count = $result->fetch();*/

	$query = sprintf("%s%s", self::COUNT_ATTEMPT_USER, 'username');
        $result = $this->pdo->prepare($query);
        $result->execute(array('username' => $username));
        $count = $result->fetch();
        if(intval($count['row_count']) === 1){
	  
	  $query = sprintf("%s%s",self::FIND_ATTEMPTS_TIME, 'username');
          $result = $this->pdo->prepare($query);
          $result->execute(array('username' => $username));
          error_log( print_r( "Called userBlock13", true ) );
          $row = $result->fetch();
          
          if(intval(time()) - intval($row['time']) > 30){
             $query = sprintf("%s%s",self::DELETE_ATTEMPT, 'username');
             $result = $this->pdo->prepare($query);
             $result->execute(array('username' => $username)); 
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
	//sri krishna
        $query = sprintf("%s%s", self::DELETE_BY_NAME, 'username');
        $result = $this->pdo->prepare($query);
        return $result->execute(array('username' => $username));

//        return $this->pdo->exec(sprintf(self::DELETE_BY_NAME, $username));
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

        //sri krishna
        $uname = $user->getUsername();
        $passw = $user->getHash();
        $fname = $user->getFirstName();
        $lname = $user->getLastName();
        $pnum = $user->getPhone();
        $company = $user->getCompany();
        $admin = $user->isAdmin();

        $results = $this->pdo->prepare(self::INSERT_QUERY);

        $results->bindParam(1, $uname);
        $results->bindParam(2, $passw);
        $results->bindParam(3, $fname);
        $results->bindParam(4, $lname);
        $results->bindParam(5, $pnum);
        $results->bindParam(6, $company);
        $results->bindParam(7, $admin);
 
        return $results->execute();
    }

    public function saveExistingUser(User $user)
    {
        error_log( print_r( "Updated", true ) );
	//sri krishna
        $email = $user->getEmail();
        $fname = $user->getFirstName();
        $lname = $user->getLastName();
        $admin = $user->isAdmin();
        $pnum = $user->getPhone();
        $company = $user->getCompany();
        $uid = $user->getUserId();
        
	$results = $this->pdo->prepare(self::UPDATE_QUERY);
        $results->bindParam(1, $email);
        $results->bindParam(2, $fname);
        $results->bindParam(3, $lname);
	$results->bindParam(4, $admin);
        $results->bindParam(5, $pnum);
        $results->bindParam(6, $company);
	$results->bindParam(7, $uid);

        return $results->execute();
    }

}
