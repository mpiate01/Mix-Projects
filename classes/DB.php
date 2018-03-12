<?php
//Singleton pattern been used
//PDO used to increase compatibility 
class DB
{	
	//Variables initialisation
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_results,
			$_affectedRowQuery;

	private function __construct()
	{
		
		try
		{
			$this->_pdo = new PDO('mysql:host=' .  $GLOBALS['config']['mysql']['host'] . ';dbname=' . $GLOBALS['config']['mysql']['db'], $GLOBALS['config']['mysql']['username'], $GLOBALS['config']['mysql']['password']);
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			/*
			*
				the application ends in the error page if there are issues with the connession 	
			*/
			Session::flashMessage('errorsDB', $GLOBALS['errors'][$GLOBALS['config']['language']]['connession']);	
			//To redirect properly in case of url with data
			if ($paths = explode('?',$_SERVER[REQUEST_URI]))
			{
				$path = $paths[0];
			}
			else
			{
				$path = $paths;
			} 
			die(Redirect::to($path));			
		}
	}

	//Singleton Pattern to create database connession
	public static function getInstance()
	{
		if(!isset(self::$_instance))
		{
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	//PDO method to be used to prepare and execute a query (data validated and escaped)
	//$action used if fetchAll is not needed and would cause an error  (insert queries)
	public function setQuery($sql,$action="")
	{
		//Prepare query
		if($this->_query = $this->_pdo->prepare($sql))
		{
			//Execute query
			try
			{
				$this->_query->execute();		
				$action = strtolower($action);
				if($action != "insert")	
				{
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);// now the results are accessible _query->_results->name of column
				}

				//Method used later to loop into the results
				$this->setAffectedRowQuery($this->_query->rowCount());
			}
			catch(Exception $e)
			{		
				/*
				*
					the application ends in the error page if there are issues with the query execution	
				*/		
				Session::flashMessage('errorsDB', $GLOBALS['errors'][$GLOBALS['config']['language']]['query']);
				if ($paths = explode('?',$_SERVER[REQUEST_URI]))
				{
					$path = $paths[0];
				}
				else
				{
					$path = $paths;
				} 
				die(Redirect::to($path));	
				// TO USE WHILE DEBUGGING die($e->getMessage());
			}
			return $this;
		}
	}

	function setAffectedRowQUery($result)
	{
		$this->_affectedRowQuery = $result;
	}
	function getAffectedRowQuery()
	{
		return $this->_affectedRowQuery;
	}

	//To SHOW query results
	public function getResultsQuery()
	{
		return $this->_results;
	}
}