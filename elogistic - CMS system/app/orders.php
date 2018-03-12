<?php
require_once 'core/init.php';
require_once 'includes/templates/header.php';

$user = new User();
$display = false;

if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}
if(Input::exists())
{
	if (Token::checkToken(Input::get('token')))	
	{		
		$validate = new Validate();
		$validation = $validate->setPassed(false);
		$validation = $validate->check($_POST, array(
			'order_id'	=> array(
				'exists'		=> array ('orders' => 'order_id') 
			)

		));

		if(Input::get('order_id') == 0)
		{
			$validation->setPassed(false);
		}

		if ($validation->getPassed())
		{	
			$display = true;
		}
		else
				{
					$errors = $validation->getErrors();
				}
	}
	
}
require_once 'includes/templates/header.php';
if(isset($errors))
{
	foreach($errors as $error)
	{
		echo '<p class="error">' . $error . '</p>';
	}
}
// DIFFERENZA TRA NORMAL E ADMIN USER,     SELEZIONARE quello interessato e poi si apre in dettaglio
?>
<form action="" method="post">
	<div class="field">
		<label for="order_id">Select an Order to be displayed *</label>
		<select id="order_id" name="order_id">
		<option value="0"></option>
		<?php
			$order = new Order();
			if ($order->setInfoOrderFacID($user->getInfoUser()->id, $user->getHasPermission('admin') ))
			{	
				foreach($order->getInfoOrder() as $row)
				{
					echo '<option value="' . $row->id . '">' . $row->id	 .'</option>';		
				}
			}					
		?>
		</select></div>
		<!--TOKEN PER SECURITY CHECK -->
		<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
		<input type="submit" value="Continue" name="submit">	
</form>
<p>Go back to <a href="index.php">home page</a></p>
<?php

	if($display)
	{		
			echo '<div class="dList">';
			$diplay_order = new Order();
			$diplay_order->setInfoOrderID(Input::get('order_id'));
			echo "<p><strong>Order No:</strong> " . $diplay_order->getInfoOrder()->id . "</p>";	
			echo "<p><strong>Date:</strong> " . $diplay_order->getInfoOrder()->date . "</p>";
			echo "<p><strong>Facility id:</strong> " . $diplay_order->getInfoOrder()->facility_id . "</p>";
			$user_id = new User($diplay_order->getInfoOrder()->facility_id);
			echo "<p><strong>Facility name:</strong> " . $user_id->getInfoUser()->username . "</p>";
			//Shipment name
			$shipment_list = new Shipment();
			if($shipment_list->setInfoShipmentID($diplay_order->getInfoOrder()->shipment_id))
			{
				echo "<p><strong>Shipment type:</strong> " . $shipment_list->getInfoShipment()->name . "</p>";
			}
			//Delivery Addresses
			$addr = new Address();
			if ($addr->setInfoAddrID($diplay_order->getInfoOrder()->to_addr))
			{
				echo "<p><strong>Pickup Address:</strong> " . $addr->getInfoAddr()->address . ', ' . $addr->getInfoAddr()->post_code . ', ' . $addr->getInfoAddr()->city . ', ' . $addr->getInfoAddr()->country . "</p>";
			}
			$addr = new Address();
			if ($addr->setInfoAddrID($diplay_order->getInfoOrder()->from_addr))
			{
				echo "<p><strong>Delivery Address:</strong> " . $addr->getInfoAddr()->address . ', ' . $addr->getInfoAddr()->post_code . ', ' . $addr->getInfoAddr()->city . ', ' . $addr->getInfoAddr()->country . "</p>";
			}
			echo "<p><strong>Pickup Date:</strong> " . $diplay_order->getInfoOrder()->from_date . "</p>";
			echo "<p><strong>Extra info:</strong> " . $diplay_order->getInfoOrder()->extra_info . "</p>";
			echo '<div class="pack">';
			echo '<h2>Package list</h2>';

			$OPackages = new OPackages();
			$id_packages = $OPackages->setInfoOPackages($diplay_order->getInfoOrder()->id);
			
			$i = 0;
			if($id_packages)
			{
				foreach ($OPackages->getInfoOPackages() as $row) 
				{
					$package = new Package();
					if($package->setInfoPackageID($row->package_id))
					{					
						$packageN = $i+1;
		  				$weight = 	$package->getInfoPackage()->weight;
		  				$height =	$package->getInfoPackage()->height;
		  				$length =  	$package->getInfoPackage()->length;
		  				$width  =	$package->getInfoPackage()->width;
		  				$description = $package->getInfoPackage()->description;

		  				$output = '';
		  				$output = '<h3><u>Package n. ' . $packageN . '</u></h3>';
		  				$output .= '<p><strong>Weight</strong>: ' .  $weight;
		  				$output .= ', <strong>Height</strong>: ' . $height;
		  				$output .= ', <strong>Length</strong>: ' .  $length;
		  				$output .= ', <strong>Width</strong>: ' . $width;
		  				$output .= ', <strong>Description</strong>: ' . $description . '</p>';
		  				echo $output;
		  				$i++;
					}
				}
			}
			echo '</div>';
			echo '</div>';	
	}



	require_once 'includes/templates/footer.php';