<?php

class FLocation
{
	private 	$_db = null,
				$_infoFLocation;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoFLocation($user=null)
	{
		if($user)
		{
			if(is_numeric($user))
			{
				$infoFLocation = $this->_db->getQuery('favourite_locations', array('facility_id', '=',$user));
				if($infoFLocation->getAffectedRowQuery())
				{
					//Group table been found
					$this->_infoFLocation = $infoFLocation->getResultsQuery();
					return true;
				}
			}
		}	
		return false;
	}

	public function existsRecord($id_user = '' , $id_addr= '')
	{
		if($id_user)
		{
			$sql = "SELECT * FROM favourite_locations WHERE facility_id = " . $id_user . " AND address_id = " . $id_addr . ";";
			$infoFLocation = $this->_db->setQuery($sql);
			if ($infoFLocation->getAffectedRowQuery())
			{
				return true;
			}

		}
		return false;
	}

	public function getInfoFLocation()
	{
		return $this->_infoFLocation;
	}

	public function setFLocation($fields = array())
	{
		if (!($this->_db->insertQuery('favourite_locations', $fields)))
		{
			throw new Exception('There was a problem creating an account.');
		}
	}
}