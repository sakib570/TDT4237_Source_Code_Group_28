<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\repository\UserRepository;

class SessionsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function newSession()
    {
        if ($this->auth->check()) {
            $username = $this->auth->user()->getUsername();
            $this->app->flash('info', 'You are already logged in as ' . $username);
            $this->app->redirect('/');
            return;
        }

        $this->render('sessions/new.twig', []);
    }

    public function create()
    {
        $request = $this->app->request;
        $user    = $request->post('user');
        $pass    = $request->post('pass');

        if ($this->auth->checkCredentials($user, $pass)) {
            session_regenerate_id(true);//krishna
            $_SESSION['user'] = $user;
	    $_SESSION['isadmin'] = $this->auth->checkAdmin($user);
            error_log( print_r( "Init", true ) );
            //setcookie(30);
/*krishna
            setcookie("user", $user); 
            setcookie("password",  $pass);
*/
            $isAdmin = $this->auth->user()->isAdmin();
/*krishna
            if ($isAdmin) {
                setcookie("isadmin", "yes");
            } else {
                setcookie("isadmin", "no");
            }
*/
            $this->app->flash('info', "You are now successfully logged in as $user.");
            $this->app->redirect('/');
            return;
        }

        $this->app->flashNow('error', 'Incorrect user/pass combination.');
        $this->render('sessions/new.twig', []);
    }

    public function destroy()
    {
        $this->auth->logout();
        error_log( print_r( "Logout called", true ) );
        $this->app->redirect('http://www.ntnu.no/');
    }
}
