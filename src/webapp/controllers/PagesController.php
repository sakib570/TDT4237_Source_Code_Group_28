<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\User;

class PagesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function frontpage()
    {
        $this->render('frontpage.twig');
    }

    public function aboutUs()
    {
        $this->render('aboutUs.twig', [
            'users' => $this->userRepository->all()
        ]);
    }
}
