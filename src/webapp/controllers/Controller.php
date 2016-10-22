<?php

namespace tdt4237\webapp\controllers;

class Controller
{
    protected $app;
    
    protected $userRepository;
    protected $auth;
    protected $patentRepository;

    public function __construct()
    {
        $this->app = \Slim\Slim::getInstance();
        $this->userRepository = $this->app->userRepository;
        $this->patentRepository = $this->app->patentRepository;
        $this->patentRepository = $this->app->patentRepository;
        $this->auth = $this->app->auth;
        $this->hash = $this->app->hash;
    }

    protected function render($template, $variables = [])
    {
        if ($this->auth->check()) {
            $variables['isLoggedIn'] = true;
            $variables['isAdmin'] = $this->auth->isAdmin();
            $variables['loggedInUsername'] = $_SESSION['user'];
        }

        print $this->app->render($template, $variables);
    }
}
