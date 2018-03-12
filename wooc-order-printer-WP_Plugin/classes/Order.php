<?php

class Order
{
	protected 	$_db,
				$_infoOrder = array();
	public function __construct()
	{
		$this->_db = DB::getInstance();
		//$this->checkLastOrder();
		//$this->_setOrderDetails();
	}

	public function checkLastOrder()
	{
		$sql = 'SELECT * FROM wp_last_order_printed';
		$val = $this->_db->setQuery($sql); 
		if($val->getAffectedRowQuery())
		{
			//die('output values' . );
			$where = 'WHERE order_id > ' . $this->_getLastOrder();
			$this->_setOrderDetails($where);
		}
		else
		{
			$this->_createTableLastOPrinted();
			$this->_setOrderDetails();
		}
	}

	//it has been changed from protected to public
	public function _getLastOrder()
	{
		$sql = 'SELECT last_order_id FROM wp_last_order_printed';
		$val = $this->_db->setQuery($sql); 
		if($val->getAffectedRowQuery())
		{
			return $val->firstResult()->last_order_id;
		}	
		return false;
	}

	protected function _setLastOrder($fields)
	{
		if (!($this->_db->insertQuery('wp_last_order_printed', $fields)))
		{
			throw new Exception('There was a problem updating last order printed record.');
		}
	}

	protected function _updateLastOrder($fields)
	{
		$id = $fields['last_order_id'];
		$sql="UPDATE `wp_last_order_printed` SET `id`=1,`last_order_id`= $id WHERE 1";

		if (!$this->_db->setQuery($sql))
		{
			throw new Exception('There was a problem updating last order printed record.');
		}
	}

	protected function _createTableLastOPrinted()
	{
		$sql='CREATE TABLE wp_last_order_printed (
					id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					last_order_id INT(11) NOT NULL,
					l_o_id_noerror INT(11) NULL
				)';
		$this->_db->setQuery($sql);		
	}

	public function activation_setup_plugin($start)
	{
		$sql = 'SELECT * FROM wp_last_order_printed';
		$val = $this->_db->setQuery($sql); 
		if($val->getAffectedRowQuery())
		{
			$fields['last_order_id'] = $start;
			$this->_updateLastOrder($fields);
		}
		else
		{
			$this->_createTableLastOPrinted();
			$sql='INSERT INTO wp_last_order_printed (last_order_id) VALUES ($start)';
			$this->_db->setQuery($sql);	
		}	

	}

	public function getListOrders()
	{
		$sql = 	"SELECT `order_id` FROM `wp_woocommerce_order_items` GROUP BY `order_id` ORDER BY `order_id` ASC";
		$val = $this->_db->setQuery($sql);
		if($val->getAffectedRowQuery())
		{
			return $val->getResultsQuery();
		}
		return 0;
	}	

	protected function _setOrderDetails($where='')
	{
		$sql = 	"SELECT `order_id` FROM `wp_woocommerce_order_items` $where GROUP BY `order_id` ORDER BY `order_id` ASC";
		$val = $this->_db->setQuery($sql);
		if($val->getAffectedRowQuery())
		{
			$this->_setInfoOrder($val->getResultsQuery());
		}
		else
		{
			$message = 'There are not new orders to print!';
			return $this->_setInfoOrder($message);
			//die('There are not new orders to print!');
		}

		$last_order = array( 
				'last_order_id' => $val->getResultsQuery()[count($val->getResultsQuery())-1]->order_id
			);
		
		if(empty($where))
		{	
			try {
				$this->_setLastOrder($last_order);
			} catch (Exception $e) {
				die($e->getMessage());
			}			
		}
		else
		{
			try {	
				$this->_updateLastOrder($last_order);
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
	}
	public function getInfoOrder()
	{
		return $this->_infoOrder;
	}
	protected function _setInfoOrder($info)
	{
		if(is_array($info))
		{
			foreach ($info as $key => $values) 
			{
				$this->_infoOrder[] = $values->order_id;
			}
		}
		else
		{
			$this->_infoOrder = $info;
		}
	}
}

# SELECT `order_id` FROM `wp_woocommerce_order_items` WHERE order_id > 14 GROUP BY `order_id` ORDER BY `order_id` ASC