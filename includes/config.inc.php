<?php
session_start();
/**
* 	Global application configuration
*	PDO database connection details
*	application directory
*	upload directory
*	upload directory thumbnails
*	application name
*	application type
*	thumbnails size pixels
*	imgage full size pixels
*	accepted image formats
*	Language used to display errors
*/
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'mysqlsrv.dcs.bbk.ac.uk',
		'username' => 'mpiate01',
		'password' => 'bbkmysql',
		'db' => 'mpiate01db'
		),
	'application_dir' => dirname(dirname(__FILE__)),
	'upload_dir' => './uploads/',//dirname(dirname(__FILE__)) . '/uploads/', 2nd option 
	'upload_dir_thumb' => './uploads/thumbnail/',
	'application_name' => 	'Gallery App',
	'application_type' => 	'Html',
	'thumb_size'	=> 150,
	'img_size'	=> 600,
	//At the moment, the resize function allowed only jpeg/jpg formats
	'accepted_formats' => 	array('image/jpeg','image/jpg'),	
	'language' => 'en' // or 'it'
);

// Import functions and language files
require_once 'functions.inc.php'; 
require_once './language/language.php';