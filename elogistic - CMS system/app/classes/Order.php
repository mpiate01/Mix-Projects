<?php

class Order
{
	private 	$_db = null,
				$_infoOrder;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoOrderFacID($input = '',$admin = false)
	{
		if(!$admin)	
		{	
			$sql = 'SELECT * FROM orders WHERE facility_id = "' . $input . '" ;'; 
		}
		else
		{
			$sql = 'SELECT * FROM orders;'; 
		}
		$infoOrder = $this->_db->setQuery($sql);
		
		if($infoOrder->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoOrder = $infoOrder->getResultsQuery();
			return true;
		}
		return false;
	}	

	public function setInfoOrderID($input = '')
	{
		$sql = 'SELECT * FROM orders WHERE id = "' . $input . '" ;'; 
		$infoOrder = $this->_db->setQuery($sql);
		
		if($infoOrder->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoOrder = $infoOrder->firstResult();
			return true;
		}
		return false;
	}

	public function getInfoOrder()
	{
		return $this->_infoOrder;
	}


	public function setOrder($fields = array())
	{
		if (!($this->_db->insertQuery('orders', $fields)))
		{
			throw new Exception('There was a problem entering address details.');
		}
	}

	public function getInfoOrderIDLast()
	{
		$sql = 'SELECT * FROM orders ORDER BY ID DESC LIMIT 1';
		$infoOrder = $this->_db->setQuery($sql);
		if($infoOrder->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoOrder = $infoOrder->firstResult();
			return true;
		}
		return false;
	}

}

