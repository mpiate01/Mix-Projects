<?php
//class done only for security..to generate hash
class Hash
{
	public static function setHash($string, $salt = '')   //x user is make
	{
		return hash('sha256', $string . $salt);   //da controllare su internet why
	}
	public static function setSalt($length)
	{
		return mcrypt_create_iv($length);
	}
	public static function setUnique()
	{
		return self::setHash(uniqid());
	}
}