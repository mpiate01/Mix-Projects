<?php
require_once 'core/init.php';


if (Session::existsSession('continue_order'))
{
	echo '<p>' . Session::flashMessage('continue_order') .'</p>';
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
		if(Input::exists() || Session::existsSession('packages'))
		{
			if (Token::checkToken(Input::get('token')) || Session::existsSession('packages'))		//get('token') viene preso dalla form
			{	
				$loop_count = Session::existsSession('packages') ? count(Session::getSession('packages')) : '1';
				print_r($loop_count);
				for ($i=0; $i < $loop_count ; $i++) { 
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						"weight$i"		=> array(
							'required' 	=> true,
							'name_error'	=> 'Weight',
							'numeric'		=> true,
							'max_value'		=> 500 	// table_name => $POST[name]
						),
						'height' . ($i)				=> array(
							'required' 	=> true,
							'name_error'	=> 'Height',
							'numeric'		=> true,
							'max_value'		=> 500 	// table_name => $POST[name]
						),
						'length' . ($i)				=> array(
							'required' 	=> true,
							'name_error'	=> 'Length' . $i,
							'numeric'		=> true,
							'max_value'		=> 500 	// table_name => $POST[name]
						),
						'width' . ($i)				=> array(
							'required' 	=> true,
							'name_error'	=> 'Width',
							'numeric'		=> true,
							'max_value'		=> 500 	// table_name => $POST[name]
						),
						'description' . ($i)			=> array(
							'name_error'	=> 'Description',
							'required' 	=> true,
							'min'      	=> 6,
							'max'		=> 40
						)					
					));
				}
				if ($validation->getPassed())
				{					
					/*if(Session::existsSession('packages'))
					{
						Session::deleteSession('packages');
					}*/
					print_r($_SESSION);

					////////////////////////////////////////////
					////////////////////////////////////////////
//CONTROLLARE IL LOOP COUNT , prima volta serve zero , poi (tot -2)/5

					////////////////////////////////////////////
					////////////////////////////////////////////
					$details = array();
					$loop_count = Session::existsSession('packages') ? count(Session::getSession('packages')) : '1';

					print_r($loop_count);
					for ($i=0; $i < $loop_count ; $i++) { 
						$details[$i] = array(
							'weight' 		=> Input::get('weight' . ($i+1)),
							'height' 		=> Input::get('height' . ($i+1)),
							'length'		=> Input::get('length' . ($i+1)),
							'width' 		=> Input::get('width' . ($i+1)),
							'description' 	=> Input::get('description' . ($i+1))
						);
					}

					Session::setSession('packages', $details);
					
				}
				else
				{
					foreach($validation->getErrors() as $error)
					{
						echo $error, '<br>';
					}
				}	
			}
		}
			
		

		/////////////////////////////////////////////////
		//  RESETTARE  INPUT::GET()  se tutto valido 
		////////////////////////////////////////////////
/*print_r($_POST);
print_r('</br><pre>');

print_r(count(Session::getSession('packages')));
print_r('</pre></br>');*/
?>
<p>se metti nei campi il numero 0 si cancella come da Pret</p>
		<form action="addpackage.php" method="post">	
<?php
		$number_packages_entered = (Session::existsSession('packages')) ? count(Session::getSession('packages')) : '0';
		for ($i=$number_packages_entered; $i >= 0; $i--) { 		
		
?>
			<label><?php echo 'Package n.'; echo ($i+1); ?></label>
			<div class="field">
				<label for="weight<?php echo $i; ?>">Weight *</label>
				<input type="text" name="weight<?php echo $i; ?>" id="weight<?php echo $i; ?>" placeholder = "Please enter weight" value="<?php echo (Session::useSession($i,'weight')) ? Session::getSession($i)['weight'] : escape(Input::get('weight'. $i)); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="height<?php echo $i; ?>">Height *</label>
				<input type="text" name="height<?php echo $i; ?>" id="height<?php echo $i; ?>" placeholder = "Please enter height" value="<?php echo (Session::useSession($i,'height')) ? Session::getSession($i)['height'] : escape(Input::get('height' . $i)); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="length<?php echo $i; ?>">Length *</label>
				<input type="text" name="length<?php echo $i; ?>" id="length<?php echo $i; ?>" placeholder = "Please enter length" value="<?php echo (Session::useSession($i,'length')) ? Session::getSession($i)['length'] : escape(Input::get('length' . $i)); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="width<?php echo $i; ?>">Width *</label>
				<input type="text" name="width<?php echo $i; ?>" id="width<?php echo $i; ?>" placeholder = "Please enter width" value="<?php echo (Session::useSession($i,'width')) ? Session::getSession($i)['width'] : escape(Input::get('width' . $i)); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="description<?php echo $i; ?>">description *</label>
				<input type="text" name="description<?php echo $i; ?>" id="description<?php echo $i; ?>" placeholder = "Please enter description" value="<?php echo (Session::useSession($i,'description')) ? Session::getSession($i)['description'] : escape(Input::get('description' . $i)); ?>" required maxlength ="30">
			</div>
	<?php } ?>

			<!--TOKEN PER SECURITY CHECK -->
			<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
			<input type="submit" value="Add package" name="submit">

		</form>
		<p>Go back to <a href="checkout.php">checkout page</a></p>
		<p>Go back to <a href="createorder.php">create order page</a></p>
<?php
	
	}
	else
	{
		echo '<p>Oops, something went wrong with your order, please go back to the <a href="createorder.php">previous page</a></p>';		
	}




}
?>

<p>Go back to <a href="index.php">home page</a></p>




					/*
//If new address has been entered, it will be saved as a new favourite for the facility and saved in the Addresses table
					if(!isset($_POST['address_id']))
					{
						//New Favourite to be added
						$address = new Address();

						$input = array(
							'address' 	=> trim(Input::get('p_address')),
							'post_code'	=> trim(Input::get('p_postcode')),
							'city'		=> trim(Input::get('p_city')),
							'country'	=> trim(Input::get('p_country'))
						);
						//check if Address exists 
						if(!$address->setInfoAddr($input))
						{					
							//Create a new record in table Addresses
							try
							{
								$address->setAddr($input);
							}
							catch(Exception $e)
							{
								echo($e->getMessage());
							}
						}
						$address_id = $address->getInfoAddr()->id;
						$favourite_location = new FLocation();
						if (!($favourite_location->existsRecord($user->getInfoUser()->id,$address_id)))
						{
							try
							{
								$favourite_location->setFLocation(array(
									'facility_id'	=> $user->getInfoUser()->id,	
									'address_id'	=> $address_id
								));	
							}
							catch(Exception $e)
							{
								die($e->getMessage());
							}
						}						
					}
					//Delivery Location will be saved
					$to_addr = new Address();

					$input = array(
						'address' 	=> trim(Input::get('d_address')),
						'post_code'	=> trim(Input::get('d_postcode')),
						'city'		=> trim(Input::get('d_city')),
						'country'	=> trim(Input::get('d_country')),
						'to_addr'	=> 1
					);
					//check if Address exists 
					if(!$to_addr->setInfoAddr($input))
					{					
						//Create a new record in table Addresses
						try
						{
							$to_addr->setAddr($input);
						}
						catch(Exception $e)
						{
							echo($e->getMessage());
						}
					}
					$to_addr_id = $to_addr->getInfoAddr()->id;

					$_SESSION['order'] = array(
						'facility_id'	=> $user->getInfoUser()->id,	
						'date'			=> date("Y-m-d h:i:s"),
						'shipment_id'	=> Input::get('shipment'),
						'from_addr'	=> $address_id,
						'to_addr'	=> $to_addr_id,
						'from_date'	=> Input::get('year'),
						'extra_info'=> Input::get('extra_info')
					);*/