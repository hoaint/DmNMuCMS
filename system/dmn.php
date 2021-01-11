<?php
    define('MIN_PHP_VERS', '7.1.0');
	
	use Tracy\Debugger;

    Debugger::enable((ENVIRONMENT == 'development') ? Debugger::DEVELOPMENT : Debugger::PRODUCTION, APP_PATH . DS . 'logs' . DS . 'Tracy');
	
    $security = load_class('security');
    if($security->isIPBanned()){
        header("HTTP/1.1 403 Forbidden");
        exit('Your ip is black listed!');
    }
	
	set_exception_handler(function($e){
        print_exception($e);
    });
	
    if(!version_compare(MIN_PHP_VERS, PHP_VERSION, '<=')){
        throw new Exception('You must be using PHP ' . MIN_PHP_VERS . ' or better. You are currently using: ' . PHP_VERSION);
    }
    $request = load_class('request');
	
	require(SYSTEM_PATH . DS . 'controller.class.php');
	$router = load_class('router');
	$router->route($request);
