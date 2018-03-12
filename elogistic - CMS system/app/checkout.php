<?php
require_once 'core/init.php';

if (Session::existsSession('continue_order'))
{
	echo '<p class="flash">' . Session::flashMessage('continue_order') .'</p>';
}

if (Session::existsSession('add'))
{
	Session::deleteSession('add');
}
if (Session::existsSession('position'))
{
	Session::deleteSession('position');
}
if (Session::existsSession('deletePackage'))
{
	Session::deleteSession('deletePackage');
}

if (Session::existsSession('pdeleted'))
{
	echo '<p class="flash">' . Session::flashMessage('pdeleted') .'</p>';
}


$user = new User();

if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}
else
{
	if(Session::existsSession('order'))
	{
		if(Session::existsSession('packages') && !empty(Session::getSession('packages')))
		{
			//SET VAIRABLE TO SAVE EMAIL CONTENT
			//if(Session::existsSession('email'))
			//{
				$email_content = array();//Session::existsSession('email');
			//}

			if(Input::exists())
			{
				
				$validate = new Validate();
				
				$name_check_box = 'conf';
				$validate->check_box_validation($name_check_box ,$name_check_box ,'Confirmation');
				
				if ($validate->getPassed())
				{
					//CHECK if new ADDRESS and SAVE it into database
					if (Session::useSession('order','from_addr','address'))
					{
						$address = new Address();
						$input = array(
							'address' 	=>  Session::useSession('order','from_addr','address'),
							'post_code'	=> 	Session::useSession('order','from_addr','post_code'),
							'city'		=> 	Session::useSession('order','from_addr','city'),
							'country'	=> 	Session::useSession('order','from_addr','country')	
						);  
						if(!$address->setInfoAddr($input))
						{					
							//Create a new record in table Addresses
							try
							{
								$address->setAddr($input);						
							}
							catch(Exception $e)
							{
								die($e->getMessage());
							}
						}
						$address->setInfoAddr($input);
						//GET ID FOR ORDER
						$from_addr = $address->getInfoAddr()->id;	
					}
					else
					{
						
						if (Session::useSession('order','from_addr'))
						{
							$from_addr = Session::useSession('order','from_addr');
						}
					}
					if (Session::useSession('order','to_addr','address'))
					{
						$address = new Address();
						$input = array(
							'address' 	=>  Session::useSession('order','to_addr','address'),
							'post_code'	=> 	Session::useSession('order','to_addr','post_code'),
							'city'		=> 	Session::useSession('order','to_addr','city'),
							'country'	=> 	Session::useSession('order','to_addr','country'),
							'to_addr'	=>  '1'
						);  
						if(!$address->setInfoAddr($input))
						{					
							//Create a new record in table Addresses
							try
							{
								$address->setAddr($input);						
							}
							catch(Exception $e)
							{
								die($e->getMessage());
							}
						}
						$address->setInfoAddr($input);
						//GET ID FOR ORDER
						$to_addr = $address->getInfoAddr()->id;	
					}
					else
					{
						
						if (Session::useSession('order','to_addr'))
						{
							$to_addr = Session::useSession('order','to_addr');
						}
					}
					
					try
					{	
						//ADD PACKAGES
						for ($i=0; $i < count(Session::useSession('packages')); $i++) 
	  					{ 	  						
	  						$package = new Package();

	  						$weight = 	Session::useSession('packages')[$i]['weight'];
	  						$height =	Session::useSession('packages')[$i]['height'];
	  						$length =  	Session::useSession('packages')[$i]['length'];
	  						$width  =	Session::useSession('packages')[$i]['width'];
	  						$description = Session::useSession('packages')[$i]['description'];

							try
							{
								$package->setPackage(array(
									'weight' 			=>  $weight,	
									'height' 			=>	$height,
									'length'			=> 	$length,
									'width' 			=>	$width,
									'description'		=>	$description
								));

								//SAVE ID packages FOR OTHER TABLES
								if ($package->getInfoPackageIDLast())
								{									
									$packagesID[] = $package->getInfoPackage()->id;
								}
							}	
							catch(Exception $e)
							{
								die($e->getMessage());
							}
						}
						$order = new Order();
						//ADD ORDER 
						$order->setOrder(array(
							'facility_id'	=> Session::useSession('order','facility_id'),	
							'date'			=> Session::useSession('order','date'),	
							'shipment_id'	=> Session::useSession('order','shipment_id'),
							'from_addr'	=> $from_addr,
							'to_addr'	=> $to_addr,
							'from_date'	=> Session::useSession('order','from_date'),
							'extra_info'=> Session::useSession('order','extra_info')
						));
						$orderN = new Order();
						if ($orderN->getInfoOrderIDLast())
						{									
							$order_id = $orderN->getInfoOrder()->id;
						}
						
						Session::setSession('order_id', $order_id);

						//ADD ORDER/PACKAGES references
						try
						{
							$OPackages = new OPackages();
							foreach ($packagesID as $value) 
							{
								$OPackages->setOPackages(array(
									'order_id'		=> $order_id,
									'package_id' 	=> $value
								));
							}
						}
						catch(Exception $e)
						{
							die($e->getMessage());
						}

						//Favourite address Pickup
						$favourite_location = new FLocation();
						if (!($favourite_location->existsRecord($user->getInfoUser()->id,$from_addr)))
						{
							try
							{
								$favourite_location->setFLocation(array(
									'facility_id'	=> $user->getInfoUser()->id,	
									'address_id'	=> $from_addr
								));	
							}
							catch(Exception $e)
							{
								die($e->getMessage());
							}
						}
						//Favourite address Delivery to
						$favourite_location_to = new FLocation();
						if (!($favourite_location_to->existsRecord($user->getInfoUser()->id,$to_addr)))
						{
							try
							{
								$favourite_location_to->setFLocation(array(
									'facility_id'	=> $user->getInfoUser()->id,	
									'address_id'	=> $to_addr
								));	
							}
							catch(Exception $e)
							{
								die($e->getMessage());
							}
						}

					}	
					catch(Exception $e)
					{
						die($e->getMessage());
					}
					///////////////////////////////////////////////////////
					///////////////////////////////////////////////////////
					///////////////////////////////////////////////////////
					//////		CONTINARE col ordine
					//////	
					///////////////////////////////////////////////////////
					///////////////////////////////////////////////////////
					///////////////////////////////////////////////////////


					//QUANDO SI FA L'ordine, salvare delivery to e favourite e packages??


					//Destroy Sessions
					Session::deleteSession('order');
					Session::deleteSession('packages');



					

					Redirect::to('formphp.php');
					//Redirect::to('sendemail.php');
					///////////////////////////////
					///////////////////////////////
					///////////////////////////////
					///////////////////////////////
					///////////////////////////////
					///////////////////////////////
					///////////			////////////
					//////////			///////////
					//////////	DATABASE ///////////
					///////// DATA		 ////////////
					/////////	TO BE ENTERED ///////////
					///////////////////////////////
					///////////////////////////////
					///////////////////////////////
					///////////////////////////////
				}
				else
				{
					$errors = $validation->getErrors();
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

?>
	<div class="dList">
		<h2>Order details</h2>
		<p><strong>Type of shipment</strong>: <?php 			
					$shipment_list = new Shipment();
					if ($shipment_list->setInfoShipmentID(Session::useSession('order','shipment_id')))
					{
						echo '<p>' . $shipment_list->getInfoShipment()->name. '</p>';
						$email_content['shipment'] = $shipment_list->getInfoShipment()->name;						
					}					
	  ?></p>
		<p><strong>Pickup address</strong>: <?php 
					if (Session::useSession('order','from_addr','address'))
					{
						echo '<p>' . Session::useSession('order','from_addr','address') . ', ' . Session::useSession('order','from_addr','post_code') . ', ' . Session::useSession('order','from_addr','city') . ', ' . Session::useSession('order','from_addr','country'). '</p>';
						$email_content['from_addr'] = Session::useSession('order','from_addr','address') . ', ' . Session::useSession('order','from_addr','post_code') . ', ' . Session::useSession('order','from_addr','city') . ', ' . Session::useSession('order','from_addr','country');
					}
					else
					{
						$addr = new Address();
						if ($addr->setInfoAddrID(Session::useSession('order','from_addr')))
						{
							echo '<p>' . $addr->getInfoAddr()->address . ', ' . $addr->getInfoAddr()->post_code . ', ' . $addr->getInfoAddr()->city . ', ' . $addr->getInfoAddr()->country . '</p>';
							$email_content['from_addr'] = $addr->getInfoAddr()->address . ', ' . $addr->getInfoAddr()->post_code . ', ' . $addr->getInfoAddr()->city . ', ' . $addr->getInfoAddr()->country ;
						}
					}
	  ?></p>
		<p><strong>Delivery address</strong>: <?php 
					if (Session::useSession('order','to_addr','address'))
					{
						echo '<p>' . Session::useSession('order','to_addr','address') . ', ' . Session::useSession('order','to_addr','post_code') . ', ' . Session::useSession('order','to_addr','city') . ', ' . Session::useSession('order','to_addr','country'). '</p>';
						$email_content['to_addr'] = Session::useSession('order','to_addr','address') . ', ' . Session::useSession('order','to_addr','post_code') . ', ' . Session::useSession('order','to_addr','city') . ', ' . Session::useSession('order','to_addr','country');
					}
					else
					{
						$addr = new Address();
						if ($addr->setInfoAddrID(Session::useSession('order','to_addr')))
						{
							echo '<p>' . $addr->getInfoAddr()->address . ', ' . $addr->getInfoAddr()->post_code . ', ' . $addr->getInfoAddr()->city . ', ' . $addr->getInfoAddr()->country . '</p>';
							$email_content['to_addr'] = $addr->getInfoAddr()->address . ', ' . $addr->getInfoAddr()->post_code . ', ' . $addr->getInfoAddr()->city . ', ' . $addr->getInfoAddr()->country ;
						}
					}
	  ?></p>
	  	<p><strong>Pickup date</strong>: <?php
	  				if(Session::useSession('order','from_date'))
	  				{
	  					echo '<p>' . Session::useSession('order','from_date'). '</p>';
	  					$email_content['from_date'] = Session::useSession('order','from_date');
	  				}			

	  	?></p>	
	  	<p><strong>Extra info</strong>: <?php
	  				if(Session::useSession('order','extra_info'))
	  				{
	  					echo '<p>' . Session::useSession('order','extra_info');
	  					$email_content['extra_info'] = Session::useSession('order','extra_info');
	  				}			

	  	?></p>
	  	</div>	
	  	<p>Go back to <a href="index.php">home page</a></p>
	  	<p><a href="createorder.php">Change order details.</a></p>
	  	<p><a href="addpackage.php">Add another package.</a></p>
	  	<form action="" method="post">
	  		<input type="checkbox" required name="conf" value="conf">Confirm
			<input type="submit" value="Submit order" name="submit">
	  	</form>
	  	<div class="pack2" >
	  	<h2>Package list</h2>
	  	<?php 
	  				if(Session::useSession('packages'))
	  				{	  					
	  					for ($i=0; $i < count(Session::useSession('packages')); $i++) 
	  					{ 
	  						$package = $i+1;
	  						$weight = 	urlencode(Session::useSession('packages')[$i]['weight']);
	  						$height =	urlencode(Session::useSession('packages')[$i]['height']);
	  						$length =  	urlencode(Session::useSession('packages')[$i]['length']);
	  						$width  =	urlencode(Session::useSession('packages')[$i]['width']);
	  						$description = urlencode(Session::useSession('packages')[$i]['description']);


	  						$output = '';
	  						$output = '<h3><u>Package n. ' . $package . '</u></h3>';
	  						$output .= '<p><strong>Weight</strong>: ' .  $weight;
	  						$output .= ', <strong>Height</strong>: ' . $height;
	  						$output .= ', <strong>Length</strong>: ' .  $length;
	  						$output .= ', <strong>Width</strong>: ' . $width;
	  						$output .= ', <strong>Description</strong>: ' . urldecode($description) . '</p>';

	  						//SAVE CONTENT FOR EMAIL

	  						$email_content['packages'][$i] = $output;

	  						//Update link
	  						$output .= '    <a href=addpackage.php?p=' . $i . '&w=' . $weight . '&h=' . $height . '&l=' . $length . '&w=' . $width . '&d=' . $description . '>Update package details.</a></p>';
	  						//Delete link
	  						$output .= '<p><a href=addpackage.php?delete=true&p=' . $i . '&w=' . $weight . '&h=' . $height . '&l=' . $length . '&w=' . $width . '&d=' . $description . '>Delete package.</a></p>';
	  						echo $output;
	  					}
	  				}
			echo '</div>'; 
	  		//Save content for email	
	  		$email_content['date'] = Session::useSession('order')['date'];		
	  		$email_content['facility_id'] = Session::useSession('order')['facility_id'];
	  		$email_content['facility_name'] = $user->getInfoUser()->username;
	  			

	 		Session::setSession('email', $email_content);
			 				

	  		
		}
		else
		{
			echo '<p>No packages have been entered, Please go back to the <a href="addpackage.php">previous page</a>.</p>';
		}
	}	
	else
	{
		echo '<p>Oops, something went wrong with your order, please go back to the <a href="createorder.php">previous page</a>.</p>';		
	}

}
require_once 'includes/templates/footer.php';
?>

