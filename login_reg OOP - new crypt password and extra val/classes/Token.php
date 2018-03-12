<?php
//crossover forgery to check id session con database id

//il token varra' utilizzato ogni volta che we submit data
class Token
{
	public static function generateToken()
	{
		return Session::setSession(Config::get('session/token_name'), md5(uniqid()));
		//Config::get('session/token_name')  = prende il nome a cui dare alla session che identifica il token
		//md5(uniqid()) = codice creato randomly associato alla $_SESSION['token']
	}

	public static function checkToken($token)
	{
		$tokenName = (Config::get('session/token_name'));

		if(Session::existsSession($tokenName) && $token === Session::getSession($tokenName))
		{
			Session::deleteSession($tokenName);
			return true;
		}	

		return false;
	}
}

// PART (12/23) of video PHP OOP Login/Register System: CSRF Protection