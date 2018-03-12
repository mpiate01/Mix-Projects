<?php
class Cookie
{
	public static function existsCookie($name)
	{
		return (isset($_COOKIE[$name])) ? true : false;
	}

	public static function getCookie($name)
	{
		return $_COOKIE[$name];
	}

	public static function setCookie($name, $value, $expiry)
	{
		if(setcookie($name, $value, time() + $expiry, '/'))
		{
			return true;
		}
		return false;
	}

	public static function deleteCookie($name)
	{
		//delete
		self::setCookie($name,'', time() - time());
	}
}