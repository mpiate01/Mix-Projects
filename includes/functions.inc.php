<?php
// Define the autoload function
spl_autoload_register(function($class)
{
	// Construct path to the class file
	require_once 'classes/' . $class . '.php';
});

/**
* function used to sanitize a value
* @param $string to be sanitize
**/
function escape($string)
{
	return htmlentities($string, ENT_QUOTES, 'UTF-8');  //ent_quotes aiuta ad aumentare la sicurezza
}

?>