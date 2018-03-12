<?php

class Customer extends Order
{
	protected 	$_db,
				$_infoCustomer;
	public function __construct($order_id)
	{
		$this->_db = DB::getInstance();
		$this->setCustomerDetails($order_id);
	}
	protected function setCustomerDetails($order_id)
	{
		$sql = 	"SELECT ID, post_excerpt, post_modified, meta_value, meta_key  FROM wp_posts JOIN wp_postmeta ON wp_posts.ID = wp_postmeta.post_id WHERE ID = $order_id AND (meta_key = '_order_total' OR meta_key = '_shipping_method' OR meta_key = '_payment_method_title' OR meta_key = '_billing_address_index' OR meta_key = '_shipping_address_index' )";
		$val = $this->_db->setQuery($sql);
		if($val->getAffectedRowQuery())
		{
			$this->setInfoCustomer($val->getResultsQuery());

		}
		else
		{
			die('<div id="message" class="updated below-h2"><p>Error while checking order details. <a class="no-print" href="' . get_admin_url() .  'admin.php?page=refreshing_setting_page">Please refresh the page</a>!</p></div>');
		}
	}
	public function getInfoCustomer()
	{
		return $this->_infoCustomer;
	}
	protected function setInfoCustomer($info)
	{
		foreach ($info as $key => $values) {
			foreach ($values as $key => $value) {

				switch ($key) {
					case 'ID':
						$this->_infoCustomer['id_order'] = $value;
						break;
					case 'post_excerpt':
						$this->_infoCustomer['notes_order'] = $value;
						break;					
					case 'post_modified':
						$this->_infoCustomer['date_order'] = $value;
						break;
					case 'meta_value' :
						$value_meta = (empty($value)) ? 'No shipping address set.' : $value;
						break;	
					case 'meta_key':
						switch ($value) {
							case '_payment_method_title':
								$this->_infoCustomer['payment_method'] = $value_meta;
								break;
							case '_order_total':
								$this->_infoCustomer['total_order'] = $value_meta;
								break;			
							case '_shipping_method':
								$this->_infoCustomer['shipping_method'] = $value_meta;
								break;
							case '_billing_address_index':
								$this->_infoCustomer['billing_details'] = $value_meta;
								break;		
							case '_shipping_address_index':
								$this->_infoCustomer['shipping_details'] = $value_meta;
								break;
						}
						break;
				}
			}
		}
	}

}