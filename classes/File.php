<?php
/*
*	Class used for file validation
*
*/
class File extends Validation
{
	private $_acceptedFormats;
	private $_filename;
	private $_filenameTemp;
	private $_arrayFiles = array();

	/*
		Open a directory $dir and with a custom function read 
		the files contained in it
		Parameter:
		$dir = directory's name
	*/
	public function read_dir($dir)
	{
		//directory checks for errors
		if(is_dir($dir))
		{
			// Sort in ascending order - this is default
			$this->_setArrayFiles(scandir($dir));
		}
		else
		{
			$this->_setErrors('Oops, directory error.');
		}
	}

	//Getters and setter 
	public function formats($format)
	{
		$this->_acceptedFormats = $format;
	}
	public function getArrayFiles()
	{
		return $this->_arrayFiles;
	}

	private function _setArrayFiles($files)
	{
		$this->_arrayFiles = $files;
	}

	/*
	*	Function used to check upload type error while uploading the file
	*	return File's instance
	*/
	public function upload_error_type($file)
	{
		$error_type = $file['error'];
		switch ($error_type) 
		{
			case UPLOAD_ERR_OK://error ok
				$this->_uploadFile($file);
				break;
			case UPLOAD_ERR_INI_SIZE:
				$this->_setErrors('File upload error. File size exceeded');
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$this->_setErrors('File upload error. Form size exceeded');
				break;
			case UPLOAD_ERR_PARTIAL:
				$this->_setErrors('File upload error. Partial uploaded');
				break;
			case UPLOAD_ERR_NO_FILE:
				$this->_setErrors('File upload error. No file uploaded');
				break;	
			default:
				$this->_setErrors('Error code: ' . $error_type);  //nel caso ci sia un nuovo errore nel futuro
				break;
		}
		if(empty($this->_errors))
		{
			$this->setPassed(true);
		}
		return $this;	
	}
	/*
	*	Used to check file's format extension and creating the uploading folder(if not existing)
	*	and to create newname & tempname variables used by resize function
	*/
	private function _uploadFile($file)
	{
		if(is_array($file))
		{
			if(is_uploaded_file($file['tmp_name']))
			{				
				$title = explode('.',$file['name']);
				$ext = 'image/' . $title[(count($title)-1)] ;
				if(in_array($ext, $this->_acceptedFormats))  //in_array($file['type'], $this->_acceptedFormats)
				{	
					//if upload directory does not exist, it ll be created
					if(!file_exists($GLOBALS['config']['upload_dir']))
					{
						mkdir($GLOBALS['config']['upload_dir'], 0777, true);
					}
					
					//check the upload directory
					if (is_dir($GLOBALS['config']['upload_dir']))	
					{
						//current folder where the script is
						$upfilename = basename($file['name']);
						$newname = $GLOBALS['config']['upload_dir'] . $upfilename;
						$tempname = $file['tmp_name'];
					
						//Check if file exists
						if(file_exists($newname))
						{
							$this->_setErrors(escape($upfilename) . ' - ' . $GLOBALS['errors'][$GLOBALS['config']['language']]['double']);
						}
						else
						{
							if (is_file($tempname) && is_readable($tempname))
							{								
								$this->_setFilename($newname);
								$this->_setFilenameTemp($tempname);
							}
							else
							{
								$this->_setErrors( 'File upload error. File not readable!');
							}
						}
					}
					else
					{
						$this->_setErrors( 'File upload error. Directory error!');
					}
				}
				else
				{
					$this->_setErrors( $GLOBALS['errors'][$GLOBALS['config']['language']]['typeimg']);
				}
			}
			else
			{
				$this->_setErrors('No file was uploaded!');
			}	
		}	
		else
		{
			$this->_setErrors('No file was uploaded!');
		}
	}

	private function _setFilename($name)
	{
		$this->_filename = $name;
	}

	public function getFilename()
	{
		return $this->_filename;
	}

	private function _setFilenameTemp($name)
	{	
		$this->_filenameTemp = $name;
	}

	public function getFilenameTemp()
	{
		return $this->_filenameTemp;
	}

}