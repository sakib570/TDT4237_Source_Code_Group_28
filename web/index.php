<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

if (! extension_loaded('openssl')) {
    die('You must enable the openssl extension.');
}

session_cache_limiter(false);

//lifetime,path,domain,secure,httppnly
session_set_cookie_params(3600,'/','localhost',false,true); 

session_start();

//change maximum session cookie timeout here//sri krishna
ini_set('session.gc_maxlifetime', 3600);

if (!isset($_SESSION['CREATED'])) {//sri krishna

    //time out if no activity for certain time
    $_SESSION['CREATED'] = time();
    // session timeout after certain time (irrespective of user's activity or inactivity) 
    $_SESSION['START'] = time(); 
    error_log( print_r( "Time created", true ) );
} else if (intval(time()) - intval($_SESSION['CREATED']) > 300 ||
            intval(time()) - intval($_SESSION['START']) > 3600) {
    error_log( print_r( "Session Invalid", true ) );
    session_unset();
    session_destroy();
    session_start();
}else{ //sri krishna in activity for 1 min will logout

    $_SESSION['CREATED'] = time();
    error_log( print_r( time()-$_SESSION['CREATED'], true ) );
}


if (preg_match('/\.(?:png|jpg|jpeg|gif|txt|css|js)$/', $_SERVER["REQUEST_URI"]))
    return false; // serve the requested resource as-is.
else {
    $app = require __DIR__ . '/../src/app.php';
    $app->run();
}
