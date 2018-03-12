<?php

class Shipment
{
	private 	$_db = null,
				$_infoShipment;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoShipment()
	{
		$infoShipment = $this->_db->setQuery("SELECT * FROM `shipments`");
		if($infoShipment->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoShipment = $infoShipment->getResultsQuery();
			return true;
		}
		return false;
	}

	public function setInfoShipmentID($input = '')
	{
		$sql = 'SELECT * FROM shipments WHERE id = "' . $input . '" ;'; 
		$infoShipment = $this->_db->setQuery($sql);
		
		if($infoShipment->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoShipment = $infoShipment->firstResult();
			return true;
		}
		return false;
	}

	public function getInfoShipment()
	{
		return $this->_infoShipment;
	}

}