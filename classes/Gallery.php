<?php
/*
*	Gallery class used to deal with adding/resizing images and creating thumbnails
*
*/
class Gallery extends Validation
{
	private $_db;
	private $_width, $_height;

	public function __construct()
	{
		//Get database instance
		$this->_db = DB::getInstance();
	}
	
	/*
	*	used to create thumbnail images while uploading the image
	*	@param $tempnam and $name used for the resize function
	*	it creates the thumbnail directory if it does not exist
	*/

	private function _create_thumb($tempnam,$name)
	{
		//Create thumbnail image and folder if it does not exist
		if(!file_exists($GLOBALS['config']['upload_dir_thumb']))
		{
			mkdir($GLOBALS['config']['upload_dir_thumb'], 0777, true);
		}
		$thumb_name = str_replace($GLOBALS['config']['upload_dir'], $GLOBALS['config']['upload_dir_thumb'], $name);

		//Create thumbnail
		$this->_resize($GLOBALS['config']['thumb_size'],$tempnam,false,$thumb_name);
	}

	/*
	*	Used to add images into directory and database
	*	@params $tempnam,$name = used when saving the file;
	*			$title,$description = title and description of the file to be saved into database
	*	return Gallery's instance
	*/
	public function add_img($tempnam,$name,$title,$description)
	{
		$this->_resize($GLOBALS['config']['img_size'],$tempnam,false,$name);

		if(empty($this->_errors))
		{	
			
			//Image's height and width for query
			$w = $this->_getW();
			$h = $this->_getH();

			$this->_create_thumb($tempnam,$name);				
			//Save only filename from the path into database
			$name = explode('/',$name);
			$name = $name[count($name)-1];

			//Save file name to be used to delete the file if there are errors with database/query	
			Session::flashMessage('nameFile',$name);

			$sql = "INSERT INTO Gallery (file_name, title, description, width, height) VALUES ('$name','$title','$description',$w,$h);";

			$this->_db->setQuery($sql,"insert");

			$this->setPassed(true);
		}

		return $this;
	}
	/*
	*	used on the webservice 
	*	@param $id => default value is equal to '', if not empty, it is used to include the clause WHERE id = $id 
	* 					into the query
	*	return query result on object form
	*/

	
	public function get_info($id='')
	{
		$where = ($id != '') ? "WHERE id = $id" : ""; 
		$sql = "SELECT title, description, file_name, width, height FROM Gallery $where";
		$this->_db->setQuery($sql);
		$rowQuery = $this->_db->getAffectedRowQuery();

		return $this->_db->getResultsQuery();
	}



	/*
	*	Used to return all thumbnail images to display
	*
	*/
	public function get_img_thumb()
	{
		$sql = "SELECT * FROM Gallery;";
		
		$this->_db->setQuery($sql);
		$rowQuery = $this->_db->getAffectedRowQuery();

		//Thumbnail name list
		$listThumb = array();

		for ($i=0; $i < $rowQuery; $i++) 
		{ 			
			$listThumb[escape($this->_db->getResultsQuery()[$i]->file_name)] = array(escape($this->_db->getResultsQuery()[$i]->title), escape($this->_db->getResultsQuery()[$i]->description));
		}

		return $listThumb;

	}
	
	/*
	*	Used to return data regarding the full image to display
	*
	*/
	public function get_img($name)
	{
		$sql ="SELECT `file_name`, `title`, `description` FROM `Gallery` WHERE file_name = '$name' ;";
		$this->_db->setQuery($sql);
		$rowQuery = $this->_db->getAffectedRowQuery();

		$img_details = array();
		if($rowQuery != 0)
		{
			$img_details = array(escape($this->_db->getResultsQuery()[0]->file_name), escape($this->_db->getResultsQuery()[0]->title), escape($this->_db->getResultsQuery()[0]->description));		
		}

		return $img_details;
	
	}
	

/**
 * Resize images
 *
 * Function to resize images to fit area specified when called
 * ONLY JPEG FORMAT ALLOWED
 *
 * @param string $tempname Input image filename
 * @param string $newname Output image filename
 * @param boolean $output if false, it ll save the image;if true, it ll display directly on the browser
 * @param int $maxSize max Width or Height of the image should fill
 * @return $message string value 
 */
	private function _resize($maxSize,$tempname,$output=false,$newname=null)
	{
		$message = false;
		try
		{
			$details = getimagesize($tempname);				
			
			if ($details[2] == IMAGETYPE_JPEG)
			{
				$src = imagecreatefromjpeg($tempname); //forse imagecreatefromstring( file_get_contents( $file_name ) );

				$width = $details[0];   
				$height = $details[1]; 
				//Check if any measure is larger than max size
				if ( $width > $maxSize || $height > $maxSize ) 
				{
				    
				    $ratio = $width/$height; // width/height
				    if( $ratio > 1) 
				    {
				        $new_width = $maxSize;
				        $new_height = $maxSize/$ratio;
				    } 
				    else 
				    {
				        $new_width = $maxSize*$ratio;
				        $new_height = $maxSize;
				    }											
				 											   
				    $dst = imagecreatetruecolor( $new_width, $new_height );
				    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
					if(!$output)
					{
						imagejpeg($dst, $newname); // adjust format as needed
						$this->_setW($new_width);
						$this->_setH($new_height);
					}
					else
					{
						header('Content-type: image/jpeg');
						imagejpeg($dst, $newname);
					}
					
					imagedestroy($dst);
				}
				else
				{
					imagejpeg($src, $newname); 
					$this->_setW($width);
					$this->_setH($height);
				}
				
				imagedestroy($src); 													
			}
			else
			{
				$this->_setErrors('File upload error. Wrong file extension, only JPG/JPEG allowed!');
			}
		}
		catch(Exception $e)
		{
			$this->_setErrors('File upload error. File not resizeable!');
		}
		return $message;
	}

	private function _setW($width)
	{
		$this->_width = $width;
	}

	private function _getW()
	{
		return $this->_width;
	}

	private function _setH($height)
	{
		$this->_height = $height;
	}

	private function _getH()
	{
		return $this->_height;
	}	

/*
	This method can be used to delete all records from the table
	public function deleteRecords()
	{
		$sql = "truncate Gallery;";
		
		$this->_db->setQuery($sql);
	}
*/
}