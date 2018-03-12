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
	 		$this->setInfoUser($user);
	 	}

	}

	public function setUser($fields = array())
	{
		if (!($this->_db->insertQuery('users', $fields)))
		{
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function getInfoUser()
	{
		return $this->_infoUser;
	}

	//username can be numeric 
	public function setInfoUser($user = null)
	{
		if($user)
		{
			$field = (is_numeric($user)) ? 'id' : 'username';
			$info = $this->_db->getQuery('users', array($field, '=', $user));

			if($info->getAffectedRowQuery())
			{
				$this->_infoUser = $info->firstResult();
				return true;
			}
		}
		return false;
	}

	public function login($username = null, $password =null, $remember = false)
	{
	

		if(!$username && !$password && $this->existUser())
		{
			//log user in
			Session::setSession($this->_sessionName, $this->getInfoUser()->id);
		}
		else
		{
			$user = $this->setInfoUser($username);	
			if ($user)
			{
				if(Hash::getHash($password,$this->getInfoUser()->password ))
				{
					Session::setSession($this->_sessionName, $this->_infoUser->id);  //SESSION['user'] = id  e
																					// non username qui
					//remember checkbox ha un relativo spazione del database, chiamato session_user
					//dove viene salvato id ed hash codice
					if($remember)
					{			
						$hashCheck = $this->_db->getQuery('users_session', array('user_id' , '=', $this->getInfoUser()->id));

						if(!$hashCheck->getAffectedRowQuery())
						{
							$hash = Hash::setUnique();
							$this->_db->insertQuery('users_session', array(
								'user_id' => $this->getInfoUser()->id , 
								'hash' => $hash
							));
						}
						else
						{
							$hash = $hashCheck->firstResult()->hash;
						}
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

		if(!$this->_db->updateQuery('users', $id, $fields))
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
		$this->_db->deleteQuery('users_session', array('user_id', '=', $this->getInfoUser()->id));
		Session::deleteSession($this->_sessionName);
		Cookie::deleteCookie($this->_cookieName);
	}

	public function getHasPermission($key)
	{
		$group = $this->_db->getQuery('groups', array('id', '=', $this->getInfoUser()->group_perm));

		if($group->getAffectedRowQuery())
		{
			$permissions = json_decode($group->firstResult()->permissions, true); // true per aver un array 
																				//come risultato
			if($permissions[$key] == true)
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