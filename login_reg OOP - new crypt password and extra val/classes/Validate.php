<?php

class Validate
{
	private $_passed = false;
	private $_errors = array();
	private $_db = null;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function check($source,$items = array())
	{
		foreach ($items as $item => $rules)  
		//$item = password, username, name...  $rules = required,min,max...
		{
			foreach ($rules as $rule => $rule_value) 
			{
			//echo "{$item} {$rule} must be {$rule_value}</br>";
				
				$value = trim($source[$item]);
				$item = escape($item);

				if($rule === 'required' && empty($value))
				{
					//item is the form field, se nell array gli dai il nome, si puo usare quello
					$this->setErrors("{$item} is required");	
				}
				else if (!empty($value))
				{
					switch ($rule) {
						case 'min':
							if(strlen($value)< $rule_value)
							{
								$this->setErrors("{$item} must be a minimum of {$rule_value} characters.");
							}
							break;
						case 'max':
							if(strlen($value)> $rule_value)
							{
								$this->setErrors("{$item} must be a maximum of {$rule_value} characters.");
							}							
							break;
						case 'matches':
							if($value != $source[$rule_value])
							{
								$this->setErrors("{$rule_value} must match {$item}.");
							}
							break;
						case 'anew':
							if($value == $source[$rule_value])
							{
								$this->setErrors("{$rule_value} must be different from previous {$item}.");
							}
							break;
						case 'unique':
							$check = $this->_db->getQuery($rule_value, array($item, '=', $value ));
							if ($check->getAffectedRowQuery())
							{
								$this->setErrors("{$item} already exists.");
							}
							break;
						case 'mail':
							if (!filter_var($value,FILTER_VALIDATE_EMAIL) && $rule_value)
							{
								$this->setErrors("{$value} is not a valid email address.");
							}
							break;
						case 'nospecialchar':
							if(!preg_match('/^([a-zA-Z0-9\-]+)$/', $value) && $rule_value)
							{
								$this->setErrors("{$item}, only numeric digits and letters are allowed.");	
							}
							break;
						case 'strongPassword':
							if((!preg_match('/\d/', $value) || !preg_match('/^([a-zA-Z0-9\-\@\?\!\#]+)$/', $value) || preg_match('/\s/', $value)) && $rule_value)
							{
								$this->setErrors("{$item}, only numeric digits and letters are allowed and ?!@#. Must contain at least a number.");	
							}
							break;											
					}

				}
			}
		}
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
}