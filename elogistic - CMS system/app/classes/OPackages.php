<?php

class OPackages
{
	private 	$_db = null,
				$_infoOPackages;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoOPackages($id)
	{
		
			if(is_numeric($id))
			{
				$infoOPackages = $this->_db->getQuery('orders_packages', array('order_id', '=',$id));
				if($infoOPackages->getAffectedRowQuery())
				{
					//Group table been found
					$this->_infoOPackages = $infoOPackages->getResultsQuery();
					return true;
				}
			}
			
		return false;
	}

	public function existsRecord($id_order = '' , $id_package= '')
	{
		if($id_user)
		{
			$sql = "SELECT * FROM orders_packages WHERE order_id = " . $id_order . " AND package_id = " . $id_package . ";";
			$infoOPackages = $this->_db->setQuery($sql);
			if ($infoOPackages->getAffectedRowQuery())
			{
				return true;
			}

		}
		return false;
	}

	public function getInfoOPackages()
	{
		return $this->_infoOPackages;
	}

	public function setOPackages($fields = array())
	{
		if (!($this->_db->insertQuery('orders_packages', $fields)))
		{
			throw new Exception('There was a problem creating an account.');
		}
	}
}