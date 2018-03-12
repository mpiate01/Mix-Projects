<?php
require_once 'core/init.php';


$user = new User();


if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}
$address = new Address();
$addr = $user->getAddrId();
if ($address->setInfoAddrID($addr))
{
	if(Input::exists())
	{
		if(Token::checkToken(Input::get('token')))
		{			
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
						'username'			=> array(
							'name_error' 	=> 'Username',	
							'required' 	=> true,
							'min'      	=> 2,
							'max'      	=> 20
						),
						'phone'				=> array(
							'name_error'	=> 'Telephone number',
							'required' 	=> true,
							'min'      	=> 10,
							'max'      	=> 15,   	//DA CONTROLLARE
							'numeric'   => true
						),
						'mail'				=> array(
							'name_error'	=> 'Email address',
							'required' 	=> true,
							'max'      	=> 30, 
							'valid_email' => true  	
						),					
						'address'				=> array(
							'name_error'	=> 'Address',
							'required' 	=> true,
							'min'      	=> 5,
							'max'      	=> 30   	
						),
						'postcode'				=> array(
							'name_error'	=> 'Postcode',
							'required' 	=> true,
							'min'      	=> 2,
							'max'      	=> 12   	
						),
						'city'				=> array(
							'name_error'	=> 'City',
							'required' 	=> true,
							'min'      	=> 4,
							'max'      	=> 20   	
						),
						'country'			=> array(
							'name_error'	=> 'Country',
							'required' 	=> true,
							'min'      	=> 4,
							'max'      	=> 20   	
						)
					));


			if ($validation->getPassed())
			{
				
				$input = array(
							'address' 	=> trim(Input::get('address')),
							'post_code'	=> trim(Input::get('postcode')),
							'city'		=> trim(Input::get('city')),
							'country'	=> trim(Input::get('country'))
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
				$address->setInfoAddr($input);
					//Get id to associate to address_id in facility
				$address_id = $address->getInfoAddr()->id;		

				//update
				try
				{
					$user->update(array(
								'username'	=> Input::get('username'),	
								'phone'		=> Input::get('phone'),
								'email'		=> Input::get('mail'),		
								'address_id'	=> $address_id/*,
								'address_id_2'	=> ''*/
							));

					Session::flashMessage('home', 'Your details have been update');
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
					Redirect::to('index.php');
				}
				catch(Exception $e)
				{
					die($e->getMessage());
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
	foreach($errors as $error)
	{
		echo '<p class="error">' . $error . '</p>';
	}
}
?>


<form action="" method="post">
	
	<div class="field">
		<label for="username">Your username</label>
		<input type="text" name="username" id="username" value="<?php echo escape($user->getInfoUser()->username); ?>">
	</div>
	<div class="field">
		<label for="phone">Telephone number *min 10 digits</label>
		<input type="text" name="phone" id="phone" placeholder = "Please enter Telephone number" value="<?php echo escape($user->getInfoUser()->phone); ?>" required  maxlength ="15">
	</div>
	<div class="field">
		<label for="mail">Email address *</label>
		<input type="email" name="mail" id="mail" placeholder = "Please enter Email address" value="<?php echo escape($user->getInfoUser()->email); ?>" required  maxlength ="30">
	</div>
	<div class="field">
		<label for="address">Address *</label>
		<input type="text" name="address" id="address" placeholder="Please enter Address" value="<?php echo escape($address->getInfoAddr()->address); ?>" required  maxlength ="40">
		<label for="postcode">Postcode *</label>
		<input type="text" name="postcode" id="postcode" placeholder="Please enter Postcode" value="<?php echo escape($address->getInfoAddr()->post_code); ?>" required  maxlength ="12">
		<label for="city">City*</label>
		<input type="text" name="city" id="city" placeholder="Please enter City" value="<?php echo escape($address->getInfoAddr()->city); ?>" required  maxlength ="20">
		<label for="country">Country *</label>
		<input type="text" name="country" id="country" placeholder="Please enter Country" value="<?php echo escape($address->getInfoAddr()->country); ?>" required  maxlength ="20">
	</div>	

	<!--TOKEN PER SECURITY CHECK -->
	<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
	<input type="submit" value="Update" name="submit">
</form>
<p>Go back to <a href="index.php">home page</a></p>
<?php }

require_once 'includes/templates/footer.php';