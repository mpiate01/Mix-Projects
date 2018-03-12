<?php

class Group
{
	private 	$_db = null,
				$_infoGroup;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoGroup()
	{
		$infoGroup = $this->_db->setQuery("SELECT * FROM `groups`");
		if($infoGroup->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoGroup = $infoGroup->getResultsQuery();
			return true;
		}
		return false;
	}

	public function getInfoGroup()
	{
		return $this->_infoGroup;
	}
}