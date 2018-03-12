<?php
//session initialization
session_start();

//config settings
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '64.34.75.143', //invece di usare localhost, dice che cosi velocizza il tutto
		'username' => 'dsbro213_elapp',
		'password' => 'elogisticapp',
		'db' => 'dsbro213_elogistic'
	), 
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800	//time
	),
	'session' => array(
		'session_name' => 'user', 	 //usato per login
		'token_name'   => 'token'
	)
);

//PHP autoload function
spl_autoload_register(function($class)
{
	require_once 'classes/' . $class . '.php';
});

//Function used to sanitize user's input
require_once 'functions/sanitize.php';

//Remember me functionality
if(Cookie::existsCookie(Config::get('remember/cookie_name')) && !Session::existsSession(Config::get('session/session_name')))
{
	$hash = Cookie::getCookie(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->getQuery('facilities_session', array('hash', '=', $hash));

	if($hashCheck->getAffectedRowQuery())
	{
		$user = new User($hashCheck->firstResult()->user_id);
		$user->login();
	}
}