<?php

// Validation Class for FORMS

class Validate
{
	private $_passed = false;
	private $_errors = array();
	private $_db = null;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	/****
	*	Params = 	$source is where the data are coming from ($_POST or $_GET)
	*				$items is an array of rules
	*	Returns=	if not error it sets passed equal to true
	*
	*****/
	public function check($source,$items = array())
	{
			
		foreach ($items as $item => $rules)  
		//$item = password, username, name...  $rules = required,min,max...
		{
			if (isset($source[$item]))
			{
				foreach ($rules as $rule => $rule_value) 
				{
				//echo "{$item} {$rule} must be {$rule_value}</br>";
					if ($rule === 'name_error')
					{					
						$item_name = $rule_value;
					}
					else
					{
						if (!is_array($source[$item]))
						{	
							//remove white spaces
							$value = trim($source[$item]);

							//$item = escape($item);   MODIFICATO DA ME IN
							$select = escape($item);		//USED FOR sql query
						}
						$item_name =  ($rule === 'name_error') ? $rule : escape($item);
						if($rule === 'required' && empty($value))
						{
							//item is the form field, se nell array gli dai il nome, si puo usare quello
							$this->setErrors("{$item_name} is required");	
						}
						else if (!empty($value))
						{	
							//Check each rule defined by $items
							switch ($rule) 
							{
									case 'min':
										if(strlen($value)< $rule_value)
										{
											$this->setErrors("{$item_name} must be a minimum of {$rule_value} characters");
										}
										break;
									case 'max':
										if(strlen($value)> $rule_value)
										{
											$this->setErrors("{$item_name} must be a maximum of {$rule_value} characters");
										}							
										break;
									case 'max_value':
										if(intval($value)> $rule_value)
										{
											$this->setErrors("{$item_name} must be a maximum of {$rule_value}");
										}							
										break;	
									case 'numeric':
										if(!is_numeric($value) === $rule_value)
										{
											$this->setErrors("{$item_name} must be only digits");
										}							
										break;	
									case 'matches':
										if($value != $source[$rule_value])
										{
											$this->setErrors("{$item_name} must match {$rule_value}");
										}
										break;
									case 'unique':
										$sql = "SELECT `" . $select . "` FROM `" . $rule_value . "` WHERE " . $select . " = '" . $value . "';";
										$check = $this->_db->setQuery($sql);
										if ($check->getAffectedRowQuery())
										{
											$this->setErrors("{$item_name} already exists");
										}
										break;
									case 'valid_email':
										// Remove all illegal characters from email
										if($rule_value){
											$value = filter_var($value, FILTER_SANITIZE_EMAIL);
											// Validate e-mail
											if ($value)
											{
												if (!filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
				   									 
												} else {
												   $this->setErrors("{$item_name} is not a valid");
												}
											}
										}
										break;
									case 'exists':
										$table = array_keys($rule_value)[0];
										$input = $rule_value[array_keys($rule_value)[0]];

										$sql = "SELECT `id` FROM `" . $table . "` WHERE `id`  = '" . $source[$input] . "';";
										
										$check = $this->_db->setQuery($sql);
										if ($check->getAffectedRowQuery() < 1)
										{
											$this->setErrors("{$item_name} not existing");
										}					
										break;
									case 'valid_date':
									
										//check if date entered is valid
										if (!checkdate($rule_value['month'],$rule_value['day'], $rule_value['year']))
										{
											$this->setErrors("{$item_name} not correct");
										}
										break;
									case 'existing_favourite_location':
										$table = array_keys($rule_value)[0];
										$input = $rule_value[array_keys($rule_value)[0]];

										$sql = "SELECT `address_id` FROM `" . $table . "` WHERE `address_id`  = '" . $source[$input] . "';";
										
										$check = $this->_db->setQuery($sql);
										if ($check->getAffectedRowQuery() < 1)
										{
											$this->setErrors("{$item_name} not existing");
										}					
										break;
									case 'time_pickup_correct':
										$today = strtotime(date("Y-m-d h:i:s"));
										$pickup = strtotime($rule_value);
										if ($pickup < $today)
										{
											$this->setErrors("{$item_name} cannot be set in the past");
										}

										break;
							}
						}
					}
				}
			}
		}
		

		// Check if any error has been found, if not set passed = true
		if(empty($this->_errors))
		{
			$this->setPassed(true);
		}
		return $this;
	}

	private function setErrors($error)
	{
		$this->_errors[] = $error;
	}

	public function getErrors()
	{
		return $this->_errors;
	}

	public function getPassed()
	{
		return $this->_passed;
	}

	public function setPassed($flag)
	{
		$this->_passed = $flag;
	}

	public function check_box_validation($name,$value,$name_error ='')
	{
		
		$item_name = ($name_error != '') ? $name_error : $name;
		if(!isset($_POST[$name]) || ($_POST[$name] != $value ))
		{				
			$this->setErrors("Check box {$item_name} was not clicked");
		}
		// Check if any error has been found, if not set passed = true
		if(empty($this->_errors))
		{
			$this->setPassed(true);
		}
		else
		{
			$this->setPassed(false);
		}
		return $this;
	}
}