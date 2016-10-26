<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

if (! extension_loaded('openssl')) {
    die('You must enable the openssl extension.');
}

session_cache_limiter(false);


session_set_cookie_params(3600,'/','localhost',false,true); //change session cookie timeout here //sri krishna

session_start();

ini_set('session.gc_maxlifetime', 3600);//change maximum session cookie timeout here//sri krishna

if (!isset($_SESSION['CREATED'])) {//sri krishna
    $_SESSION['CREATED'] = time();
    error_log( print_r( "Time created", true ) );
} else if (intval(time()) - intval($_SESSION['CREATED']) > 60) {//change session cookie timeout here//sri krishna
    error_log( print_r( "Session Invalid", true ) );
    session_unset();
    session_destroy();
    //$_SESSION['CREATED'] = time();  // update creation time
    session_start();
}else{ //sri krishna in activity for 1 min will logout

    $_SESSION['CREATED'] = time();
    error_log( print_r( time()-$_SESSION['CREATED'], true ) );
}


//sri krishna
if (!isset($_SESSION['user']))
{
      //session_regenerate_id();
      error_log( print_r( "Not logged in", true ) );
      error_log( print_r( $_SERVER["REQUEST_URI"], true ) );
      //$_SERVER["REQUEST_URI"] = "/login"; //ask for login again
      
}else{
      error_log( print_r( $_SESSION['user'], true ) );
}


if (preg_match('/\.(?:png|jpg|jpeg|gif|txt|css|js)$/', $_SERVER["REQUEST_URI"]))
    return false; // serve the requested resource as-is.
else {
    $app = require __DIR__ . '/../src/app.php';
    $app->run();
}
