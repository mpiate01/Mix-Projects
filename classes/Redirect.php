<?php
/*
*	Class used for redirections
*	@params of method to $location = page where the usere will be redirect to
*/

class Redirect
{	
	public static function to($location = null)
	{
		if($location)
		{
			if(is_numeric($location))
			{
				switch ($location) {
					case 404:
							header('HTTP/1.0 404 Not Found');
							Session::flashMessage('errorsDB', $GLOBALS['errors'][$GLOBALS['config']['language']]['404']);
							include 'index.php';
							exit();
						break;
					
				}
			}
			header('Location: ' . $location);
			exit();
		}
	}
}