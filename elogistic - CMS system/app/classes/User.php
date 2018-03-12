<?php
class User
{
	private $_db,
			$_infoUser,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;

	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();
	 
	 	$this->_sessionName = Config::get('session/session_name');
	 	$this->_cookieName = Config::get('remember/cookie_name');

	 	//how to detect if user is log in or not
	 	//check if user exists or not -- SESSION as well
	 	if(!$user)
	 	{
	 		if(Session::existsSession($this->_sessionName))
	 		{
	 			$user = Session::getSession($this->_sessionName);

	 			if($this->setInfoUser($user))
	 			{
	 				$this->_isLoggedIn = true;
	 			}
	 			else
	 			{
	 				//process log out
	 			}
	 		}	 		
	 	}
	 	else
	 	{
	 		//find user
	 		$this->setInfoUser($user);
	 	}

	}

	public function setUser($fields = array())
	{
		if (!($this->_db->insertQuery('facilities', $fields)))
		{
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function getInfoUser()
	{
		return $this->_infoUser;
	}

	//FIND user    username can be numeric 
	public function setInfoUser($user = null)
	{
		if($user)
		{
			$field = (is_numeric($user)) ? 'id' : 'username';
			$info = $this->_db->getQuery('facilities', array($field, '=', $user));

			if($info->getAffectedRowQuery())
			{
				//User been found
				$this->_infoUser = $info->firstResult();
				return true;
			}
		}
		return false;
	}
	public function setInfoAllUsers()
	{
		$sql = "SELECT * FROM facilities;";
		$info = $this->_db->setQuery($sql);
	
		if($info->getAffectedRowQuery())
			{
				//User been found
				$this->_infoUser = $info->getResultsQuery();
				return true;
			}
		
		return false;
	}

	public function login($username = null, $password =null, $remember = false)
	{	

		//Check if session already exists 
		if(!$username && !$password && $this->existUser())
		{
			//log user in and SET session to save user details for login
			Session::setSession($this->_sessionName, $this->getInfoUser()->id);
		}
		else
		{
			$user = $this->setInfoUser($username);
			if ($user)
			{
				//check password+salt saved in database with entered by the user
				if($this->getInfoUser()->password === Hash::setHash($password, $this->getInfoUser()->salt))
				{
					Session::setSession($this->_sessionName, $this->getInfoUser()->id);  //SESSION['user'] = id  e
																					// non username qui
					//remember checkbox ha un relativo spazione del database, chiamato session_user
					//dove viene salvato id ed hash codice
					if($remember)
					{			
						//Check if session has been already saved
						$hashCheck = $this->_db->getQuery('facilities_session', array('facility_id' , '=', $this->getInfoUser()->id));

						if(!$hashCheck->getAffectedRowQuery())
						{
							//Generate Hash and save into table
							$hash = Hash::setUnique();
							$this->_db->insertQuery('facilities_session', array(
								'facility_id' => $this->getInfoUser()->id , 
								'hash' => $hash
							));
						}
						else
						{
							$hash = $hashCheck->firstResult()->hash;
						}
						//SET cookie
						Cookie::setCookie($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}

					return true;
				}
			}
		}
		return false;
	}

	public function update($fields = array(), $id = null)// $id serve nel caso in cui si voglia fare l update di un determinato ID
	{
		if (!$id && $this->getIsLoggedIn())
		{
			$id = $this->getInfoUser()->id;
		}

		if(!$this->_db->updateQuery('facilities', $id, $fields))
		{
			throw new Exception('There was a problem updating');
		}
	}
	public function existUser()
	{
		return (!empty($this->_infoUser)) ? true : false;
	}
	public function logout()
	{
		$this->_db->deleteQuery('facilities_session', array('facility_id', '=', $this->getInfoUser()->id));
		Session::deleteSession($this->_sessionName);
		Cookie::deleteCookie($this->_cookieName);
	}

	public function getAddrId()
	{
		return $this->getInfoUser()->address_id;
	}

	public function getHasPermission($key)
	{
		$group = $this->_db->getQuery('groups', array('id', '=', $this->getInfoUser()->group_id));

		if($group->getAffectedRowQuery())
		{
			$permissions = json_decode($group->firstResult()->permissions, true); // true per aver un array 
																				//come risultato
			if($permissions[$key] == true)   //if we assigned value 1, example {admin:1}
			{
				return true;
			}
		}
		return false;
	}

	public function getIsLoggedIn()
	{
		return $this->_isLoggedIn;
	}
}