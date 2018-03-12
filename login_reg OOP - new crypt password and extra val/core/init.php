<?php
//inizializzazione di tutto
session_start();

//config settings
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1', //invece di usare localhost, dice che cosi velocizza il tutto
		'username' => 'root',
		'password' => 'password',
		'db' => 'socialnetwork'
	), 
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'session_name' => 'user',  //usato per login
		'token_name'   => 'token'
	)
);

spl_autoload_register(function($class)
{
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

//Remember me functionality

if(Cookie::existsCookie(Config::get('remember/cookie_name')) && !Session::existsSession(Config::get('session/session_name')))
{
	$hash = Cookie::getCookie(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->getQuery('users_session', array('hash', '=', $hash));

	if($hashCheck->getAffectedRowQuery())
	{
		$user = new User($hashCheck->firstResult()->user_id);
		$user->login();
	}
}