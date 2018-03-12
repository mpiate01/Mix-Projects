<?php
require_once 'core/init.php';

$user = new User();
$user_to_be_update = new User(Session::getSession('id_user_to_be_update'));


//Check if user is logged in and if got 'ADMIN' permission
if ($user->getIsLoggedIn())
{
	if($user->getHasPermission('admin'))  //e' stato usato un json value
	{
		

		//Check if $_POST exists, check TOken and get ID user from $_POST['id']
		if(Input::exists())
		{	
			//Unset $_POST after setting user to be update
			/*if(Input::get('reset_value') == true)
			{
				$user_to_be_update = new User(Input::get('id'));
				$address = new Address();
				$addr = $user_to_be_update->getAddrId();
				$address->setInfoAddrID($addr);
				Input::unset();
			}*/
			if(true)//Input::exists())
			{
				
				if (Token::checkToken(Input::get('token')))	
				{	
					if (true)//$address->setInfoAddrID($addr))////NON SICURO< FORSE VA PRIMA DEL TOKEN
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
								'group'				=> array(
									'name_error'	=> 'Group permission',
									'exists'		=> array ('groups' => 'group') 	// table_name => $POST[name]
								),
								'currency'			=> array(
									'name_error'	=> 'Currency permission',
									'exists'		=> array ('currencies' => 'currency') 	
								),				
								'address'			=> array(
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
							$address = new Address();
							$addr = $user_to_be_update->getAddrId();
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
								$user_to_be_update->update(array(
											'username'	=> Input::get('username'),	
											'phone'		=> Input::get('phone'),
											'email'		=> Input::get('mail'),
											'group_id'		=> Input::get('group'),
											'currency_id'	=> Input::get('currency'),		
											'address_id'	=> $address_id/*,
											'address_id_2'	=> ''*/
										), $user_to_be_update->getInfoUser()->id);

								Session::flashMessage('updatemaster', "Facility's details have been update");

								$favourite_location = new FLocation();
								if (!($favourite_location->existsRecord($user_to_be_update->getInfoUser()->id,$address_id)))
								{
									try
									{
										$favourite_location->setFLocation(array(
											'facility_id'	=> $user_to_be_update->getInfoUser()->id,	
											'address_id'	=> $address_id
										));	
									}
									catch(Exception $e)
									{
										die($e->getMessage());
									}
								}
								Session::deleteSession('id_user_to_be_update');
								Redirect::to('chooseuser.php');
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
			}	
		}

		require_once 'includes/templates/header.php';

		echo '<p>You are an administrator!</p>';

		if(isset($errors))
		{
			foreach($errors() as $error)
			{
				echo '<p class="error">' . $error . '</p>';
			}
		}
		?>



		<form action="" method="post">
			<div class="field">
				<label for="username">Username *</label>
				<input type="text" name="username" id="username" placeholder = "Please enter Username" value="<?php echo escape($user_to_be_update->getInfoUser()->username); ?>" required maxlength ="20">
			</div>
			<div class="field">
				<label for="phone">Telephone number *min 10 digits</label>
				<input type="text" name="phone" id="phone" placeholder = "Please enter Telephone number" value="<?php echo escape($user_to_be_update->getInfoUser()->phone); ?>" required  maxlength ="15">
			</div>
			<div class="field">
				<label for="mail">Email address *</label>
				<input type="email" name="mail" id="mail" placeholder = "Please enter Email address" value="<?php echo escape($user_to_be_update->getInfoUser()->email); ?>" required  maxlength ="30">
			</div>		
			
			<div class="field">
				<label for="group">Level of permission *</label>
				<select id="group" name="group">
				<?php
					$group_list = new Group();
					if ($group_list->setInfoGroup())
					{
						foreach($group_list->getInfoGroup() as $row)
						{
							$selected = ($row->id == $user_to_be_update->getInfoUser()->group_id) ? 'selected' : '';
							echo '<option value="' . $row->id . '" ' . $selected . ' >' . $row->name . '</option>';
						}
					}					
				?>
				</select>
			</div>
			<div class="field">
				<label for="currency">Type of currency *</label>
				<select id="currency" name="currency">
				<?php
					$currency_list = new Currency();
					if ($currency_list->setInfoCurrency())
					{
						foreach($currency_list->getInfoCurrency() as $row)
						{
							$selected = ($row->id == $user_to_be_update->getInfoUser()->currency_id) ? 'selected' : '' ;
							echo '<option value="' . $row->id . '" ' . $selected . ' >' . $row->name . '</option>';
						}
					}					
				?>
				</select>
			</div>
			<div class="field">
			<?php                     
				$address_to_display = new Address();
				$addr = $user_to_be_update->getAddrId();
				$address_to_display->setInfoAddrID($addr);	
			?>
				<label for="address">Address *</label>
				<input type="text" name="address" id="address" placeholder="Please enter Address" value="<?php echo escape($address_to_display->getInfoAddr()->address); ?>" required  maxlength ="40">
				<label for="postcode">Postcode *</label>
				<input type="text" name="postcode" id="postcode" placeholder="Please enter Postcode" value="<?php echo escape($address_to_display->getInfoAddr()->post_code); ?>" required  maxlength ="12">
				<label for="city">City*</label>
				<input type="text" name="city" id="city" placeholder="Please enter City" value="<?php echo escape($address_to_display->getInfoAddr()->city); ?>" required  maxlength ="20">
				<label for="country">Country *</label>
				<input type="text" name="country" id="country" placeholder="Please enter Country" value="<?php echo escape($address_to_display->getInfoAddr()->country); ?>" required  maxlength ="20">
			</div>
			<!--<div class="field">
				<label for="phone">Address secondary</label>
				<input type="text" name="phone" id="phone" value="<?php //echo escape(Input::get('phone')); ?>">
			</div>-->



			<!--TOKEN PER SECURITY CHECK -->
			<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
			<input type="submit" value="Update" name="submit">
		</form>
		<p>Re-select another <a href="chooseuser.php">user</a></p>
		<p>Go back to <a href="index.php">home page</a></p>
<?php

	}
	else
	{
		echo "<p>You haven't got the right level of permission. " . 'Please go to <a href="index.php">home page</a></p>';
	}
}
else
{
	echo '<p>You are not logged in at the moment. Go to <a href="login.php">log in</a></p>';	
}
require_once 'includes/templates/footer.php';