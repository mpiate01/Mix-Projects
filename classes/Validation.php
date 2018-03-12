<?php
/*
*	Class used to validate form's fields
*	Validation is passed if there are not errors
*/
class Validation
{
	protected $_passed = false;
	protected $_errors = array();

/*
*	@params $source => source to be validated (examples $_POST[] or $_GET[])
*			$items => rules for validations and values*
*/
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
					$this->_setErrors("{$item} is required");	
				}
				else if (!empty($value))
				{
					switch ($rule) 
					{
						case 'min':
							if(strlen($value)< $rule_value)
							{
								$this->_setErrors("{$item} must be a minimum of {$rule_value} characters.");
							}
							break;
						case 'max':
							if(strlen($value)> $rule_value)
							{
								$this->_setErrors("{$item} must be a maximum of {$rule_value} characters.");
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

	protected function _setErrors($errorr)
	{
		$this->_errors[] = $errorr;
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