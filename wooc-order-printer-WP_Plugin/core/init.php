<?php
//define('wop_plugin_url', $_SERVER['DOCUMENT_ROOT'] . '/wooc/');
//require_once wop_plugin_url . 'wp-config.php';

//inizializzazione di tutto
session_start();

//config settings
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => DB_HOST, 
		'username' => DB_USER,
		'password' => DB_PASSWORD,
		'db' => DB_NAME
	)
);


/*spl_autoload_register(function($class)
{
	require_once woo_ord_pnt_dir . 'classes/' . $class . '.php';
});*/
require_once woo_ord_pnt_dir . 'classes/Config.php';
require_once woo_ord_pnt_dir .  'functions/sanitize.php';
require_once woo_ord_pnt_dir . 'classes/DB.php';
require_once woo_ord_pnt_dir . 'classes/Redirect.php';
require_once woo_ord_pnt_dir . 'classes/Session.php';
require_once woo_ord_pnt_dir . 'classes/Order.php';
require_once woo_ord_pnt_dir . 'classes/Customer.php';
require_once woo_ord_pnt_dir . 'classes/Order_details.php';

require_once woo_ord_pnt_dir . 'classes/CSS.php';



