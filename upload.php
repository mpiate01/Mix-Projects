<?php 
// Import functions and configuration file
require_once dirname(__FILE__) . '/includes/config.inc.php';

// Check if the form has been submitted
if (isset($_POST['galleryupload'])) {

	//Validation form's field 'title' and 'description'
	if(isset($_POST['description']) && isset($_POST['title']))
	{
		//Validation class used
		$val_descr = new Validation();
		$validation = $val_descr->check($_POST,array(
				'description' => array(
						'required' => true,
						'min' => 5,
						'max' => 40 //no limit by database	
					),
				'title' => array(
						'required' => true,
						'min' => 3,
						'max' => 20 //max allowed by database is 35	
					)
			));	

		//Saved field Title and Description into SESSION to be displayed after redirection
		Session::flashMessage('title', $_POST['title'] );
		Session::flashMessage('descr', $_POST['description'] );

		//if validation form's field 'title' and 'description' has passed
		if ($validation->getPassed())
		{	
			
			// Process the uploaded files through File class methods
			$fileUpload = new File();

			//Get format from config.inc file	
			$fileUpload->formats($GLOBALS['config']['accepted_formats']);	
				
			if(isset($_FILES['userfile']))
			{
				$fileUpload->upload_error_type($_FILES['userfile']);			
				if($fileUpload->getPassed())
				{
					
					//Process validation of the file ( and resizing, uploading file and file information) through Gallery class methods
					$gallery = new Gallery();
					$gallery->add_img($fileUpload->getFilenameTemp(),$fileUpload->getFilename(), $_POST['title'] , $_POST['description'] );
					
					if($gallery->getPassed())
					{	
						//Message for the user saved into a session. It ll be displayed following redirection
						Session::flashMessage('upload', 'Your file has been uploaded!');

						//Session's destruction following data been saved into a database				
						Session::deleteSession('title');
						Session::deleteSession('descr');
					}			
					else
					{
						//save output errors
					
						//Error message created while checking the file to be uploaded and saved for the user into a session. they ll be displayed following redirection
						Session::flashMessage('errorsF', $gallery->getErrors() );
					}
				
				}
				else
				{
					//save output errors
					
					//Error message created while checking the file to be uploaded and saved for the user into a session. they ll be displayed following redirection
					Session::flashMessage('errorsF', $fileUpload->getErrors() );	

				}
				//To avoid default browser action to resubmit form after refreshing the page
				Redirect::to($_SERVER[REQUEST_URI]);
			}
			else
			{
				Session::flashMessage('errorsF', 'File has not been submitted!');
			}
		}
		else
		{
			//Error message created while checking the title and description of the file and saved for the user into a session. they ll be displayed following redirection
			Session::flashMessage('errorsD', $validation->getErrors() );

		}
	}
	else
	{
		Session::flashMessage('errorsD','Description has not been submitted!');
	}
	
}


//Variables containing all errors to be displayed
$err='';
//Messages displayed to the user
if (Session::existsSession('upload'))
{
	$err .= Html::errMess(Session::flashMessage('upload'));
}

//Error messages displayed to the user
if (Session::existsSession('errorsD'))
{
	//output errors
	foreach(Session::flashMessage('errorsD') as $errorr)
	{
		$err .= Html::errMess(ucfirst($errorr));
	}
}
if(Session::existsSession('errorsF'))
{
	//output errors
	foreach(Session::flashMessage('errorsF') as $errorr)
	{
		$err .= Html::errMess(ucfirst($errorr));
	}
}
if(Session::existsSession('errorsDB'))
{
	//output errors
	$err .= Html::errMess(Session::flashMessage('errorsDB'));
	
	//If there are errors with DB or queries while the uploading process, the file will be deleted.
	if(Session::existsSession('nameFile'))
	{
		$n = Session::flashMessage('nameFile');
		if (file_exists($GLOBALS['config']['upload_dir'] . $n))
		{
			//delete file
			unlink($GLOBALS['config']['upload_dir'] . $n);
		} 
		if (file_exists($GLOBALS['config']['upload_dir_thumb'] . $n))
		{
			unlink($GLOBALS['config']['upload_dir_thumb'] . $n);
		}		
	}
}
else
{
	Session::deleteSession('nameFile');
}

//Get output template base on configuration option choose
$template = new $GLOBALS['config']['application_type']();
$title = (Session::existsSession('title')) ? escape(Session::flashMessage('title')) : '';
$descr = (Session::existsSession('descr')) ? escape(Session::flashMessage('descr')) : '';

//Create page
$heading = 'Upload Images';
$main_content = $template->upload(htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'), $title, $descr,$err );
$output = $template->create_page($main_content,$heading);

echo $output;