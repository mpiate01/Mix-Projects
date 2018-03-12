<?php
/*
*	Class used to generate HTML template. It implements BASETEMPLATE interface.
*
*/
class Html implements BaseTemplate
{
	/*
	*	Used to create html tags for magnifier page
	*	$img_details used to be displayed for tags h2,link and img
	*/ 
	public function magnifier($img_details)
	{
		if (count($img_details)>0)
		{	
			return '<div><h2>' . ucfirst(escape($img_details[1])) . ' </h2><p> ' .  escape($img_details[2])  . '</p><a href="index.php" title="Click here to go back"><img src="' . $GLOBALS['config']['upload_dir'] . escape($img_details[0]). '" alt= "' .  escape($img_details[2])  . '"></a></div>';
		}
		return '<div>' . Self::errMess($GLOBALS['errors'][$GLOBALS['config']['language']]['img404']) . '</div>';
	}
// ******************************************************************************** //
	//Used to create html tags for gallery page
	public function galleryUL($output)
	{
		return '<ul id="gallery">' . $output . '</ul>';
	}

	/*
	*	@params 	$filename = used for redirection to full size image
	*				$val = values used for img tags and link	
	*/
	public function gallery($fileName,$val=array())
	{
		return '<li><figure><a href="magnifier.php?page=' . $fileName . '"><img src="' . $GLOBALS['config']['upload_dir_thumb'] . $fileName  . '" alt="' . escape($val[1]) . '"></a><figcaption>' . ucfirst(escape($val[0])) . '</figcaption></figure></li>';
	}

// ******************************************************************************** //
	
	/*
	*	Used to create html tags for upload page 
	* 	It creates a form used to upload the file
	*	@params: 	$action => action form
	*				$title and $descr => $_POST values
	*				$err => errors to be displayed			
	*/ 
	public function upload($action, $title, $descr, $err)
	{
		return '<section><h3>Please upload your image.</h3>
		<!-- Make form submit to the current page-->
        <form enctype="multipart/form-data" action="' . $action . '" method="post">
            <fieldset>
            	<legend>Upload JPEG/JPG images format</legend>
	            <div class="controlgroup">
	                <label for="fileinput">Upload a file:</label>
	                <input name="userfile" type="file" id="fileinput">
	            </div>
	            <div class="controlgroup">
	                <label for="title">Image Title *max 20:</label>
	                <input name="title" type="text" id="title" value="' . $title . '">
	            </div>
	            <div class="controlgroup">
	                <label for="description">Image description *max 30:</label>
	                <input name="description" type="text" id="description" value="' . $descr . '">
	            </div>
	            <div class="controlgroup">
	                <input type="submit" value="Upload File" name="galleryupload">
	            </div>
           	</fieldset>
        </form>
        ' . $err .'</section>';
	}


	public static function errMess($message)
	{
		return '<p class="error">' . $message .'</p>';
	}

	public function create_page($content,$h1)
	{
		return $this->header($h1) . $content . $this->footer();
	}

	public function header($h1)
	{
		return '
		<!DOCTYPE html>
		<html>
		    <head>
		        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />     
		        <title>' . $GLOBALS['config']['application_name'] . '</title>
		        <link rel="stylesheet" type="text/css" href="./css/style.css">
		    </head>
		    <body>
		        <div id="page">
		            <header id="header_page">
		                <nav id="primary_nav">
		                    <ul>
		                        <li><a href="index.php">Gallery</a></li>
		                        <li><a href="upload.php">Upload an image</a></li>
		                    </ul>             
		                </nav>
		                <h1>' . $h1 . '</h1>            
		            </header>
		        	<main id="main_page">
		';
	}

	public function footer()
	{
		return
		'            </main>
		            <footer id="footer_page">
		                <p>Copyright 2017 by C-eight</p>
		            </footer>
		    	</div>	
		    </body>
		</html>';
	}
}