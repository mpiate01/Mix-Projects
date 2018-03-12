<?php

class Package
{
	private 	$_db = null,
				$_infoPackage;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}


	public function setInfoPackageID($input = '')
	{
		$sql = 'SELECT * FROM packages WHERE id = "' . $input . '" ;'; 
		$infoPackage = $this->_db->setQuery($sql);
		
		if($infoPackage->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoPackage = $infoPackage->firstResult();
			return true;
		}
		return false;
	}

	public function getInfoPackage()
	{
		return $this->_infoPackage;
	}

	public function getInfoPackageIDLast()
	{
		$sql = 'SELECT * FROM packages ORDER BY ID DESC LIMIT 1';
		$infoPackage = $this->_db->setQuery($sql);
		if($infoPackage->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoPackage = $infoPackage->firstResult();
			return true;
		}
		return false;
	}

	public function setPackage($fields = array())
	{
		if (!($this->_db->insertQuery('packages', $fields)))
		{
			throw new Exception('There was a problem entering packages details.');
		}
	}

}