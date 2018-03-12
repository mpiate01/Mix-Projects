<?php

class Currency
{
	private 	$_db = null,
				$_infoCurrency;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoCurrency()
	{
		$infoCurrency = $this->_db->setQuery("SELECT * FROM `currencies`");
		if($infoCurrency->getAffectedRowQuery())
		{
			//Group table been found
			$this->_infoCurrency = $infoCurrency->getResultsQuery();
			return true;
		}
		return false;
	}

	public function getInfoCurrency()
	{
		return $this->_infoCurrency;
	}
}