<?php
/**
* 	Class used to deal with Sessions (CRUD)
*/
class Session
{	
	public static function existsSession($name)
	{
		return (isset($_SESSION[$name])) ? true : false;
	}

	public static function setSession($name, $value)  //per il tipo is put
	{
		return $_SESSION[$name] = $value;
	}

	public static function getSession($name)
	{
		return $_SESSION[$name];
	}
	
	public static function deleteSession($name)
	{
		if(self::existsSession($name))
		{
			unset($_SESSION[$name]);
		}
	}

	//Flash message, shown only once message
	public static function flashMessage($name, $string = '')
	{
		if (self::existsSession($name))
		{
			$session = self::getSession($name);
			self::deleteSession($name);
			return $session;
		}
		else
		{
			self::setSession($name, $string);
		}
	}


}