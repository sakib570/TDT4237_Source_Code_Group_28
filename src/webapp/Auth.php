<?php

namespace tdt4237\webapp;

use Exception;
use tdt4237\webapp\Hash;
use tdt4237\webapp\repository\UserRepository;

class Auth
{

    /**
     * @var Hash
     */
    private $hash;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository, Hash $hash)
    {
        $this->userRepository = $userRepository;
        $this->hash           = $hash;
    }

    public function checkCredentials($username, $password)
    {
        $user = $this->userRepository->findByUser($username);

        if ($user === false) {
            return false;
        }

        return $this->hash->check($password, $user->getHash());
    }
    
    public function checkAdmin($username){

	$user = $this->userRepository->findByUser($username);
        return $user->isAdmin();
    }
   
    public function checkUserAttempts($username){
          return $this->userRepository->checkAttempts($username);
    }
    
    public function isUserBlocked($username){
            return $this->userRepository->userBlock($username);
    }

    /**
     * Check if is logged in.
     */
    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function getUsername() {
        if(isset($_SESSION['user'])){
        return $_SESSION['user'];
        }
    }

    /**
     * Check if the person is a guest.
     */
    public function guest()
    {
        return $this->check() === false;
    }

    /**
     * Get currently logged in user.
     */
    public function user()
    {
        if ($this->check()) {
            return $this->userRepository->findByUser($_SESSION['user']);
        }

        throw new Exception('Not logged in but called Auth::user() anyway');
    }

    /**
     * Is currently logged in user admin?
     */
    public function isAdmin()
    {

/*krishna
        if ($this->check()) {
            return $_COOKIE['isadmin'] === 'yes';
        }
*/

        if(isset($_SESSION['isadmin'])){
	    return $_SESSION['isadmin'] === 1;
	}else{
            return 1 === 0;	
	}
        throw new Exception('Not logged in but called Auth::isAdmin() anyway');
    }

    public function logout()
    {
       
        if(!$this->guest()) { //krishna
            error_log( print_r( "Destroyed", true ) );
            session_destroy();
        }
    }

}
