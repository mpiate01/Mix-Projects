<?php
class Report
{
	private 	$_db = null,
				$_infoReport;

	public function __construct()
	{
		$this->_db = DB::getInstance();
	}

	public function setInfoOrdersFacility($id)
	{
		if($id)	
		{	
			$sql = "SELECT o.id, o.date, (SELECT s.name FROM shipments WHERE o.shipment_id = s.id LIMIT 1) as 'shipment', (SELECT CONCAT(a.address,' ,', a.post_code,' ,', a.city,' ,', a.country) FROM addresses WHERE o.from_addr = a.id LIMIT 1) as 'from_addr', (SELECT CONCAT(ad.address,' ,', ad.post_code,' ,', ad.city,' ,', ad.country) FROM addresses WHERE o.to_addr = ad.id LIMIT 1) as 'to_addr', o.from_date, o.extra_info FROM orders o JOIN shipments s ON ( s.id = o.shipment_id) JOIN addresses a ON ( a.id = o.from_addr) JOIN addresses ad ON ( ad.id = o.to_addr) WHERE o.facility_id = " . $id . " ORDER BY o.id";
			$infoReport = $this->_db->setQuery($sql);
		
			if($infoReport->getAffectedRowQuery())
			{
				//Group table been found
				$this->_infoReport = $infoReport->getResultsQuery();
				return true;
			}
		}
		return false;
	}

	public function setInfoOrderPackages($id = null)
	{
			$sql = "SELECT p.id, p.weight, p.height, p.length, p.width, p.description FROM packages p JOIN orders_packages op ON ( op.package_id = p.id) WHERE op.order_id = '" . $id . "'";
			$infoReport = $this->_db->setQuery($sql);
		
			if($infoReport->getAffectedRowQuery())
			{
				//Group table been found
				$this->_infoReport = $infoReport->getResultsQuery();
				return true;
			}
		
		return false;
	}
	public function getInfoReport()
	{
		return $this->_infoReport;
	}	
}?>
<?php
/*
SELECT o.id, o.date, (SELECT s.name FROM shipments WHERE o.shipment_id = s.id LIMIT 1) as 'shipment', (SELECT CONCAT(a.address,' ,', a.post_code,' ,', a.city,' ,', a.country) FROM addresses WHERE o.from_addr = a.id LIMIT 1) as 'from_addr', (SELECT CONCAT(ad.address,' ,', ad.post_code,' ,', ad.city,' ,', ad.country) FROM addresses WHERE o.to_addr = ad.id LIMIT 1) as 'to_addr', o.from_date, o.extra_info FROM orders o JOIN shipments s ON ( s.id = o.shipment_id) JOIN addresses a ON ( a.id = o.from_addr) JOIN addresses ad ON ( ad.id = o.to_addr) WHERE o.facility_id = 2 ORDER BY o.id




SELECT p.id, p.weight, p.height, p.length, p.width, p.description FROM packages p JOIN orders_packages op ON ( op.package_id = p.id) WHERE op.order_id = 1
*/?>