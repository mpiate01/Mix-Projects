<?php

class Address
{
	private 	$_db = null,
				$_infoAddr;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoAddr($input = array())
	{
		$sql = 'SELECT * FROM addresses WHERE address = "' . $input['address'] . '" AND post_code = "' . $input['post_code'] . '" AND city = "' . $input['city'] . '" AND country = "' . $input['country'] . '";'; 
		$infoAddr = $this->_db->setQuery($sql);
		
		if($infoAddr->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoAddr = $infoAddr->firstResult();
			return true;
		}
		return false;
	}

	public function setInfoAddrID($input = '')
	{
		$sql = 'SELECT * FROM addresses WHERE id = "' . $input . '" ;'; 
		$infoAddr = $this->_db->setQuery($sql);
		
		if($infoAddr->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoAddr = $infoAddr->firstResult();
			return true;
		}
		return false;
	}

	public function getInfoAddr()
	{
		return $this->_infoAddr;
	}


	public function setAddr($fields = array())
	{
		if (!($this->_db->insertQuery('addresses', $fields)))
		{
			throw new Exception('There was a problem entering address details.');
		}
	}

}