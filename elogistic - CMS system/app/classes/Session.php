<?php
/**
* 
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

	public static function useSession($item='',$itemb ='',$itemc ='')
	{
		if(isset($_SESSION[$item][$itemb][$itemc]))
		{			
			return $_SESSION[$item][$itemb][$itemc];
		}
		else if(isset($_SESSION[$item][$itemb]))
		{
			if ($itemc == null)
			{
				return $_SESSION[$item][$itemb];
			}
			else
			{
				return false;
			}
		}
		else if(isset($_SESSION[$item]))
		{
			if ($itemb == null)
			{
				return $_SESSION[$item];
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

	}
	
	public static function deleteSession($name)
	{
		if(self::existsSession($name))
		{
			unset($_SESSION[$name]);
		}
	}

	//Flash message, shown only once
	public static function flashMessage($name, $string = '')
	{
		if (self::existsSession($name))
		{
			$session = self::getSession($name);
			self::deleteSession($name);				//deleted so if redirect the message is shown, but if u refresh that page it would reappear if u dont delete the session
			return $session;
		}
		else
		{
			self::setSession($name, $string);
		}
	}


}