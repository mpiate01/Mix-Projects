<?php

class CSS
{
	protected 	$_db,
				$_width,
				$_size;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function getCSS()
	{
		$sql = 'SELECT * FROM wp_last_order_printed_css_settings';
		$val = $this->_db->setQuery($sql); 
		if($val->getAffectedRowQuery())
		{
			//die('output values' . );
			$this->_setCSSvalues();
		}
		else
		{
			$this->_createTableCSS();
			$this->_setCSSvalues();
		}
	}
	protected function _createTableCSS()
	{
		$sql='CREATE TABLE wp_last_order_printed_css_settings (
					id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					width INT(11) NOT NULL,
					size INT(11) NOT NULL
				)';
		$this->_db->setQuery($sql);	
		$sql='INSERT INTO wp_last_order_printed_css_settings (width,size) VALUES (5,8)';
		$this->_db->setQuery($sql);		
	}
	protected function _setCSSvalues()
	{
		$sql = 'SELECT * FROM wp_last_order_printed_css_settings';
		$val = $this->_db->setQuery($sql); 
		if($val->getAffectedRowQuery())
		{
			//die('output values' . );
			$this->_setSize($val->firstResult()->size);
			$this->_setWidth($val->firstResult()->width);
		}
		else
		{
			die('Error while setting the CSS');
		}
	}
	public function updateCSS($fields=array())
	{
		$width = $fields['width'];
		$size = $fields['size'];	
		$sql= "UPDATE wp_last_order_printed_css_settings SET width = $width , size = $size  WHERE id = 1";

		if (!$this->_db->setQuery($sql))
		{
			throw new Exception('There was a problem updating last order printed record.');
		}else{		
		/*if(!$this->_db->updateQuery('wp_last_order_printed_css_settings',0, $fields))
		{
			throw new Exception('There was a problem updating the printer settings');
		}*/
		return true;}
	}

	protected function _setSize($val)
	{
		$this->_size = $val;
	}

	public function getFontSizePage()
	{
		return $this->_size;
	}

	protected function _setWidth($val)
	{
		$this->_width = $val;
	}

	public function getWidthPage()
	{
		return $this->_width;
	}	

}