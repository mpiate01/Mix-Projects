<?php
class Order_details extends Order
{
	protected 	$_db,
				$_infoOrderDetails;
	public function __construct($order_id)
	{
		$this->_db = DB::getInstance();
		$this->setOrderDetails($order_id);
	}
	protected function setOrderDetails($order_id)
	{
		$sql = 	"SELECT order_id, order_item_name,meta_value,meta_key  FROM wp_woocommerce_order_items JOIN wp_woocommerce_order_itemmeta ON wp_woocommerce_order_items.order_item_id = wp_woocommerce_order_itemmeta.order_item_id WHERE order_id = $order_id AND ( meta_key = '_qty' OR meta_key = '_line_total')";
		$val = $this->_db->setQuery($sql);
		if($val->getAffectedRowQuery())
		{
			$this->setInfoOrderDetails($val->getResultsQuery());
		}
		else
		{
			die('<div id="message" class="updated below-h2"><p>Error while checking order details. <a class="no-print" href="' . get_admin_url() .  'admin.php?page=refreshing_setting_page">Please refresh the page</a>!</p></div>');
		}
	}
	public function getInfoOrderDetails()
	{
		return $this->_infoOrderDetails;
	}
	protected function setInfoOrderDetails($info)
	{
		$this->_infoOrderDetails = $info;
	}

}