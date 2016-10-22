<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Patent;
use tdt4237\webapp\controllers\UserController;
use tdt4237\webapp\validation\PatentValidation;

class PatentsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $patent = $this->patentRepository->all();
        if($patent != null)
        {
            $patent->sortByDate();
        }
        $users = $this->userRepository->all();
        $this->render('patents/index.twig', ['patent' => $patent, 'users' => $users]);
    }

    public function show($patentId)
    {
        $patent = $this->patentRepository->find($patentId);
        $username = $_SESSION['user'];
        $user = $this->userRepository->findByUser($username);
        $request = $this->app->request;
        //Modification by Sakib
        $message = htmlentities($request->get('msg'), ENT_QUOTES);
        $variables = [];

        if($message) {
            $variables['msg'] = $message;

        }

        $this->render('patents/show.twig', [
            'patent' => $patent,
            'user' => $user,
            'flash' => $variables
        ]);

    }

    public function newpatent()
    {

        if ($this->auth->check()) {
            $username = $_SESSION['user'];
            $this->render('patents/new.twig', ['username' => $username]);
        } else {

            $this->app->flash('error', "You need to be logged in to register a patent");
            $this->app->redirect("/");
        }

    }

    public function create()
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged on to register a patent");
            $this->app->redirect("/login");
        } else {
            $request     = $this->app->request;
            //Modification by Sakib
            $title       = htmlentities($request->post('title'), ENT_QUOTES);
            $description = htmlentities($request->post('description'), ENT_QUOTES);
            $company     = htmlentities($request->post('company'), ENT_QUOTES);
            //Modification by Sakib
            $date        = date("dmY");
            $file        = true;
            if ($_FILES['uploaded']['tmp_name'])
            	$file = $this -> startUpload();

            $validation = new PatentValidation($title, $description);
            if ($validation->isGoodToGo() && $file) {  //Modification by Sakib
                $patent = new Patent($company, $title, $description, $date, $file);
                $patent->setCompany($company);
                $patent->setTitle($title);
                $patent->setDescription($description);
                $patent->setDate($date);
                $patent->setFile($file);
                $savedPatent = $this->patentRepository->save($patent);
                $this->app->redirect('/patents/' . $savedPatent . '?msg="Patent succesfully registered');
            }
        }

            $this->app->flashNow('error', join('<br>', $validation->getValidationErrors()));
            $this->app->render('patents/new.twig');
    }

    public function startUpload()
    {
    	//Modification by Sakib
        if(isset($_POST['submit']) &&  $_FILES['uploaded']['tmp_name'])
        {
            $target_dir =  getcwd()."/web/uploads/";
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['uploaded']['tmp_name']);
            $ok = false;
            switch ($mime) {
            	case 'application/pdf':
            			$ok = true;
            			break;
            }
            if($_FILES['uploaded']['size'] > 10485760) //10Mb
            	$ok =false;
            if($ok)
            {
	            $targetFile = $target_dir . basename($this->fileNameSantiztion($_FILES['uploaded']['name']));
	            if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $targetFile))
	            {
	                return $targetFile;
	            }
            }
            else 
            {
            	/* $validationErrors [] = "Invalid File Type!!! Only PDF allowed.";
            	$this->app->flashNow('error', join('<br>', $validationErrors));
            	$this->app->render('patents/new.twig'); */
            	return false;
            }
            //Modification by Sakib
            	
        }
    }

    public function destroy($patentId)
    {
        if ($this->patentRepository->deleteByPatentid($patentId) === 1) {
            $this->app->flash('info', "Sucessfully deleted '$patentId'");
            $this->app->redirect('/admin');
            return;
        }

        $this->app->flash('info', "An error ocurred. Unable to delete user '$username'.");
        $this->app->redirect('/admin');
    }
    
    //Modification by Sakib
    public function fileNameSantiztion($name)
    {
    	$ext = pathinfo($name, PATHINFO_EXTENSION);
    	$name = pathinfo($name, PATHINFO_FILENAME);
    	$sanitzedFilename = $this->str_file($name, '-', $ext);
    	return $sanitzedFilename;
    }
    
    public function str_file($str, $sep = '_', $ext = '', $default = '', $trim = 248)
    {
    	// Run $str and/or $ext through filters to clean up strings
    	$str = $this->str_file_filter($str, $sep);
    	$ext = '.' . 'pdf';//$this->str_file_filter($ext, '', true);
    
    	// Default file name in case all chars are trimmed from $str, then ensure there is an id at tail
    	if (empty($str) && empty($default))
    	{
    		$str = 'no_name__' . date('Y-m-d_H-m_A') . '__' . uniqid();
    	}
    	elseif (empty($str))
    	{
    		$str = $default;
    	}
    
    	// Return completed string
    	if (!empty($ext)) {
    		return $str . $ext;
    	} else {
    		return $str;
    	}
    }
    
    public function str_file_filter($str, $sep = '_', $strict = true, $trim = 248)
    {
    	$str = strip_tags(htmlspecialchars_decode(strtolower($str))); // lowercase -> decode -> strip tags
    	$str = str_replace("%20", ' ', $str); // convert rogue %20s into spaces
    	$str = preg_replace("/%[a-z0-9]{1,2}/i", '', $str); // remove hexy things
    	$str = str_replace("&nbsp;", ' ', $str); // convert all nbsp into space
    	$str = preg_replace("/&#?[a-z0-9]{2,8};/i", '', $str); // remove the other non-tag things  			
    	$str = preg_replace("/\s+/", $sep, $str); // filter multiple spaces
    	$str = preg_replace("/\.+/", '', $str); // filter multiple periods
    	$str = preg_replace("/^\.+/", '', $str); // trim leading period
    	error_log( print_r( $str, true ) );
    
    	if ($strict)
    	{
    		$str = preg_replace("/([^\w\d\\" . $sep . ".])/", '', $str); // only allow words and digits
    	}
    	else
    	{
    		$str = preg_replace("/([^\w\d\\" . $sep . "\[\]\(\).])/", '', $str); // allow words, digits, [], and ()
    	}
    
    	$str = preg_replace("/\\" . $sep . "+/", $sep, $str); // filter multiple separators
    	$str = substr($str, 0, $trim); // trim filename to desired length, note 255 char limit on windows
    
    	return $str;
    }
    //Modification by Sakib
}
