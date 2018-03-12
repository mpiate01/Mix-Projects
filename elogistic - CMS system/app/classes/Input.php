<?php

/**
* 	Quicker way to check if a form has been submitted checking and using $_POST or $_GET
*/
class Input
{	
	//Check
	public static function exists($type = 'post')
	{
		switch ($type) {
			case 'post':
				return (!empty($_POST)) ? true : false;
				break;
			case 'get':
				return (!empty($_GET)) ? true : false;
				break;
			default:
				return false;
				break;
		}
	}


	//Use  , example $_POST['username']  will be Input::get('username')
	public static function get($item)
	{
		if(isset($_POST[$item]))
		{
			return $_POST[$item];
		}
		else if(isset($_GET[$item]))
		{
			return $_GET[$item];
		}
		return '';
	}

	public static function unsett($type = 'post', $what = '')
	{
		switch ($type) {
			case 'post':
				unset($_POST[$what]);
				break;
			case 'get':
				unset($_GET[$what]);
				break;
			default:
				return false;
				break;
		}
	}
}