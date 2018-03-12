<?php
//class done only for security..to generate hash
class Hash
{
	/*
		
		I seguenti methods si basano su mcrypt_create_iv che e' deprecate. ho provato a sostituirlo cn bytes.. ma non funziona piu nulla

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


	*/

	//Percio uso passwordhash and password_verify
	public static function setHash($string)
	{
		return password_hash($string, PASSWORD_DEFAULT);
	}

	public static function getHash($password, $string)
	{
		return password_verify($password, $string);
	}
	public static function setUnique()    //genera numero x remember me
	{
		return self::setHash(uniqid());
	}
}