<?php 
// Import functions and configuration file
require_once dirname(__FILE__) . '/includes/config.inc.php';

$output = '';

//Get output template base on configuration option choose
$template = new $GLOBALS['config']['application_type']();
if(Session::existsSession('errorsDB'))
{
	//output errors
	$output.= Html::errMess(Session::flashMessage('errorsDB'));
}
else
{
	//Create thumbnail images to be displayed
	$gallery = new Gallery();
	$content = $gallery->get_img_thumb();
	
	$thumb_files = new File();
	$thumb_files->read_dir($GLOBALS['config']['upload_dir_thumb']);
	
	foreach ($content as $fileName => $val) 
	{
		//Check if files on the folder are the same as name saved on database
		//To avoid script injections 
		if(in_array($fileName,$thumb_files->getArrayFiles()))
		{
			$output .= $template->gallery(escape($fileName),$val);
		}	
	}
	if(empty($output))
	{
		$output.= Html::errMess('No images have been found!');
	}
	
}

//Create page
$heading = 'Gallery';
$main_content = $template->galleryUL($output);
$output = $template->create_page($main_content,$heading);

echo $output;

?>

