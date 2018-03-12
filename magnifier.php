<?php 
// Import functions and configuration file
require_once dirname(__FILE__) . '/includes/config.inc.php';

// output variable initialisation
$output ='';
$template = new $GLOBALS['config']['application_type']();
if(Session::existsSession('errorsDB'))
{
	//output errors
	$output.= Html::errMess(Session::flashMessage('errorsDB'));
}
else
{
	$gallery = new Gallery();
	
	//image's name passed by url used to recover info's image 
	if (!isset($_GET['page'])) {
	    $id = 'errorM'; // if wrong, display error message
	} else {
	    $id = 'correct'; // else requested image
	}
	
	switch ($id) {
	    case 'correct' :
	    	//File Class initialisation for image
			$img = new File();
			$img->read_dir($GLOBALS['config']['upload_dir']);
			
			//Check if image's name passed by url exists among the file's names on the 'uploads' directory
			if(in_array($_GET['page'],$img->getArrayFiles()))
			{
				$fname = $_GET['page'];
	
				$img_details = $gallery->get_img($fname);
				//Get output template base on configuration option choose
				$output .= $template->magnifier($img_details);		
				
			}
			else
			{
				//Display errors
				$output .= Html::errMess($GLOBALS['errors'][$GLOBALS['config']['language']]['img404']); 
			}
	        break;
	    case 'errorM' :
	        //Error message
	        $output .= Html::errMess($GLOBALS['errors'][$GLOBALS['config']['language']]['img404']); 	
	    	break;
	    default :
	        //Error message
	        $output .= Html::errMess($GLOBALS['errors'][$GLOBALS['config']['language']]['img404']);  
	}
}

//Create page
$heading = 'Magnifier';
$main_content = $output;
$output = $template->create_page($main_content,$heading);

echo $output;





?>
