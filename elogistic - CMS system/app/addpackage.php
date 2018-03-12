<?php
require_once 'core/init.php';


if (Input::exists('get')) 
{	
	Session::setSession('position',Input::get('p') );

	if(Input::get('delete') == true)
	{
		Session::setSession('deletePackage', Input::get('delete') );
	} 
}

if (Session::existsSession('continue_order'))
{
	echo '<p class="flash">' . Session::flashMessage('continue_order') .'</p>';
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
		if(Input::exists())
		//if(Input::exists() || Session::existsSession('packages'))
		{			
			if (Token::checkToken(Input::get('token')) || Session::existsSession('packages'))		//get('token') viene preso dalla form
			{	
					$validate = new Validate();
					$validation = $validate->check($_POST, array(
						'weight'		=> array(
							'required' 	=> true,
							'name_error'	=> 'Weight',
							'numeric'		=> true,
							'max_value'		=> 500 
						),
						'height'				=> array(
							'required' 	=> true,
							'name_error'	=> 'Height',
							'numeric'		=> true,
							'max_value'		=> 500 
						),
						'length'				=> array(
							'required' 	=> true,
							'name_error'	=> 'Length',
							'numeric'		=> true,
							'max_value'		=> 500 
						),
						'width'				=> array(
							'required' 	=> true,
							'name_error'	=> 'Width',
							'numeric'		=> true,
							'max_value'		=> 500 
						),
						'description'			=> array(
							'name_error'	=> 'Description',
							'required' 	=> true,
							'min'      	=> 6,
							'max'		=> 40
						)
					));

				//checkbox validation -- different because if not clicked, it doesnt appear in the $_POST
				$name_check_box = 'addB';	
				if (Session::existsSession('deletePackage'))
				{
					$name_check_box = 'deleteB';
				}
				else if (Session::existsSession('position'))
				{
					$name_check_box = 'updateB';
				}
				$validation->check_box_validation($name_check_box ,$name_check_box ,'Confirmation');
				
				//IF VALIDATION HAS PASSED
				if ($validation->getPassed())
				{		
					//GET PACKAGES DETAILS FROM SESSION IF EXISTS
					if(Session::existsSession('packages'))	
					{	
						$details = Session::getSession('packages');
						Session::deleteSession('packages');
					}

 					$position = (Session::existsSession('position')) ? Session::getSession('position') : 'noposition';

					if( Input::get('deleteB') == 'deleteB')
					{
												
						array_splice($details, intval($position), 1);			
						Session::flashMessage('pdeleted', 'Package has been deleted');
	
					}
					else if( Input::get('updateB') == 'updateB')
					{
						
						//To avoid error while navigating throw pages without submitting the form	
						//if (!empty($_POST))
						//{			
							//if($position === 'noposition')
							//{								
							//	$details[] = array(
							//	'weight' 		=> Input::get('weight'),
							//	'height' 		=> Input::get('height'),
							//	'length'		=> Input::get('length'),
							//	'width' 		=> Input::get('width'),
							//	'description' 	=> Input::get('description')
							//	);

							//	Session::flashMessage('add', 'Package has been added, continue to checkout or add another package ');
							//}
							//else
							//{
								$details[$position] = array(
								'weight' 		=> Input::get('weight'),
								'height' 		=> Input::get('height'),
								'length'		=> Input::get('length'),
								'width' 		=> Input::get('width'),
								'description' 	=> Input::get('description')
								);

								/////////////////////////////////////
								###mettere  qui il messagio per l update
							//}	
						//}
					}
					else if($position === 'noposition')
					{
						$details[] = array(
								'weight' 		=> Input::get('weight'),
								'height' 		=> Input::get('height'),
								'length'		=> Input::get('length'),
								'width' 		=> Input::get('width'),
								'description' 	=> Input::get('description')
								);
						Session::flashMessage('add', 'Package has been added, continue to <a href="checkout.php">checkout page</a> or add another package ');
						//$_POST = array();
					
					}
					/*$_POST = array();
					Session::setSession('packages', $details);	*/				
											
					if((Input::get('deleteB') == 'deleteB') || (Input::get('updateB') == 'updateB') || (Input::get('addB') == 'addB'))
					{						
						$_POST = array();
						Session::setSession('packages', $details);	
						Redirect::to('checkout.php');
					}
					
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
	foreach($errors() as $error)
	{
		echo '<p class="error">' . $error . '</p>';
	}
}


		/////////////////////////////////////////////////
		//  RESETTARE  INPUT::GET()  se tutto valido 
		////////////////////////////////////////////////

?>
		<form action="" method="post">
			<label><?php 
			if(Session::existsSession('position'))
			{
				if(Session::existsSession('deletePackage') == true) 
				{
					echo 'Delete ';
				}
				else
				{
					echo 'Update ';
				}
				echo 'package n. ' . (Session::getSession('position') + 1);
			}
				

			?></label>
			<div class="field">
				<label for="weight">Weight *</label>
				<input type="text" name="weight" id="weight" placeholder = "Please enter weight" value="<?php echo (Input::exists('get')) ? escape(Input::get('w')) : escape(Input::get('weight')); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="height">Height *</label>
				<input type="text" name="height" id="height" placeholder = "Please enter height" value="<?php echo (Input::exists('get')) ? escape(Input::get('h')) : escape(Input::get('height')); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="length">Length *</label>
				<input type="text" name="length" id="length" placeholder = "Please enter length" value="<?php echo (Input::exists('get')) ? escape(Input::get('l')) : escape(Input::get('length')); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="width">Width *</label>
				<input type="text" name="width" id="width" placeholder = "Please enter width" value="<?php echo (Input::exists('get')) ? escape(Input::get('w')) : escape(Input::get('width')); ?>" required maxlength ="5">
			</div>
			<div class="field">
				<label for="description">description *</label>
				<input type="text" name="description" id="description" placeholder = "Please enter description" value="<?php echo (Input::exists('get')) ? escape(Input::get('d')) : escape(Input::get('description')); ?>" required maxlength ="30">
			</div>
	
			<!--TOKEN PER SECURITY CHECK -->
			<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
		<?php	
			if (Session::existsSession('deletePackage'))
			{
				if(Session::getSession('deletePackage') == true)
				{
	?>	
					<input type="checkbox" required name="deleteB" value="deleteB">Confirm
					<input type="submit" value="Delete package" name="submit">
	<?php
				}
			}
			else
			{
				if(Session::existsSession('position'))
				{
	?>	
					<input type="checkbox" required name="updateB" value="updateB">Confirm
	<?php 		
				} 
				else
				{
	?>	
					<input type="checkbox" required name="addB" value="addB">Confirm
	<?php			
				}
	?>		
					<input type="submit" value="<?php echo (Session::existsSession('position')) ? 'Update package' : 'Add package'; ?>" name="submit">
	<?php
			}
	?>		
		</form>
		<p>Go back to <a href="createorder.php">create order page</a></p>
		<!--<p>Go to <a href="checkout.php">checkout page</a></p>-->
<?php
	
	}
	else
	{
		echo '<p>Oops, something went wrong with your order, please go back to the <a href="createorder.php">previous page</a></p>';		
	}
?>
<p>Go back to <a href="index.php">home page</a></p>


<?php
	if (Session::existsSession('add'))
	{
		echo '<p class="flash">' . Session::flashMessage('add') .'</p>';
	}
	
	if(Session::existsSession('packages'))
	{
		echo '<p><a href="checkout.php">Go back to checkout</a></p>';
	}
	

}

require_once 'includes/templates/footer.php';





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