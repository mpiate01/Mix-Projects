<?php
// Import functions and configuration file
require_once dirname(__FILE__) . '/includes/config.inc.php';

//data
$gallery = new Gallery();

if(isset($_GET['imageID']) && is_numeric($_GET['imageID']))
{
	$data = $gallery->get_info($_GET['imageID']);
}
else
{
	$data = $gallery->get_info();
}
// Send appropriate header 
header('Content-type: application/json');
echo json_encode($data);