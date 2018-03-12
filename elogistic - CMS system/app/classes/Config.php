<?php
//Class used to access $GLOBALS[]

/******
*	Params = path from $GLOBALS
*	Return = content of the path entered or FALSE if not existing path
******/
class Config
{
	//$path = null so it can be later checked if passed
	public static function get($path = null)
	{
		if($path)
		{
			$config = $GLOBALS['config'];		//ARRAY NAME for configurations 
			$path = explode('/', $path);

			foreach ($path as $bit) {
				if(isset($config[$bit]))
				{
					$config = $config[$bit]; //To access to second level of config
				}
				else
				{
					return false;
				}
			}
			return $config;
		}
		return false;
	}
}