<?php
require_once 'core/init.php';


//var_dump(Token::checkToken(Input::get('token')));
$user = new User();

if ($user->getIsLoggedIn())
{
	if($user->getHasPermission('admin'))  //e' stato usato un json value
	{
		
	
		//Input check if $_POST or $_GET exist
		if(Input::exists())
		{
			//Questo if viene usato per fermare ogni $_GET inserimento fatto dall url
			//per evitare attacchi.
			//il $_GET viene accettato solo dopo il submit e non automaticamente
			
			//Session token viene generato quando si genera la form, vedi relativo field nella form
			if (Token::checkToken(Input::get('token')))		//get('token') viene preso dalla form
			{
				$validate = new Validate();

				//Parameters for validation
				//username e' il nome pure del field name della form, ed e' da qui che verra' preso il nome per visualizzare l errore (min 23.00 del video 11 di youtube).
				//ho aggiunto 'name' per indicare il nome da visualizzare in caso di errore
				$validation = $validate->check($_POST, array(
					'username'			=> array(
						'name_error' 	=> 'Username',	
						'required' 	=> true,
						'min'      	=> 2,
						'max'      	=> 20,
						'unique'   	=> 'facilities' //unique => where
					),
					'password'			=> array(
						'name_error'	=> 'Password',
						'required' 	=> true,
						'min'      	=> 6,
						'max'		=> 32
					),
					'password_again'	=> array(
						'name_error'	=> 'Confirmed password',
						'required' 	=> true,
						'matches'  	=> 'password'
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
					'currency'				=> array(
						'name_error'	=> 'Currency permission',
						'exists'		=> array ('currencies' => 'currency') 	
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

				//Register User
				if ($validation->getPassed())
				{
					/*Session::flashMessage('success', 'You registered successfully!');
					header('Location: index.php');*/
					
					$address = new Address();

					//$input is used to check if a record of that address already exist
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

					//Get record with input details 
					$address->setInfoAddr($input);
					//Get id to associate to address_id in facility
					$address_id = $address->getInfoAddr()->id;

					$userN = new User();
					//32 is size for the salt
					$salt = Hash::setSalt(32);
					//Throw exception is in User class
					try
					{
						$userN->setUser(array(
							'username'	=> Input::get('username'),
							'password'	=> Hash::setHash(Input::get('password'), $salt),
							'salt'		=> $salt,
							'phone'		=> Input::get('phone'),
							'email'		=> Input::get('mail'),
							'group_id'		=> Input::get('group'),
							'currency_id'	=> Input::get('currency'),
							'address_id'	=> $address_id/*,
							'address_id_2'	=> ''*/
						));

						Session::flashMessage('home', 'New user has been added');

						$favourite_location = new FLocation();
						if($userN->setInfoUser(Input::get('username')))
						{	
							if (!($favourite_location->existsRecord($userN->getInfoUser()->id,$address_id)))
							{
								try
								{
									$favourite_location->setFLocation(array(
										'facility_id'	=> $userN->getInfoUser()->id,	
										'address_id'	=> $address_id
									));	
								}
								catch(Exception $e)
								{
									die($e->getMessage());
								}
							}
						}	

						Redirect::to('index.php');
					}
					catch(Exception $e)
					{
						//o redirect to an other page with error message
						die($e->getMessage());  //prende l errore scritto in throw User.php
					}
				}
				else
				{
					$errors = $validation->getErrors();
				}
			}
			/*else
			{
							SE SI VUOLE AGGIUNGERE QUALCOSA SE TOKEN NON ESISTE e VIENE FATTO QUALCOSA, in teoria non serve aggiungere nulla
			}*/	
		}
		require_once 'includes/templates/header.php';
		echo '<p>You are an administrator!</p>';
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
				<label for="username">Username *</label>
				<input type="text" name="username" id="username" placeholder = "Please enter Username" value="<?php echo escape(Input::get('username')); ?>" required maxlength ="20">
			</div>
			<div class="field">
				<label for="password">Choose a password *min 6 characters </label>
				<input type="password" name="password" id="password" placeholder = "Please enter Password" value="" required maxlength ="32">
			</div>
			<div class="field">
				<label for="password_again">Enter the password again *</label>
				<input type="password" name="password_again" id="password_again" placeholder = "Please enter Password again" value="" required>
			</div>
			<div class="field">
				<label for="phone">Telephone number *min 10 digits</label>
				<input type="text" name="phone" id="phone" placeholder = "Please enter Telephone number" value="<?php echo escape(Input::get('phone')); ?>" required  maxlength ="15">
			</div>
			<div class="field">
				<label for="mail">Email address *</label>
				<input type="email" name="mail" id="mail" placeholder = "Please enter Email address" value="<?php echo escape(Input::get('mail')); ?>" required  maxlength ="30">
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
							echo '<option value="' . $row->id . '">' . $row->name . '</option>';
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
							echo '<option value="' . $row->id . '">' . $row->name . '</option>';
						}
					}					
				?>
				</select>
			</div>
			<div class="field">
				<label for="address">Address *</label>
				<input type="text" name="address" id="address" placeholder="Please enter Address" value="<?php echo escape(Input::get('address')); ?>" required  maxlength ="40">
				<label for="postcode">Postcode *</label>
				<input type="text" name="postcode" id="postcode" placeholder="Please enter Postcode" value="<?php echo escape(Input::get('postcode')); ?>" required  maxlength ="12">
				<label for="city">City*</label>
				<input type="text" name="city" id="city" placeholder="Please enter City" value="<?php echo escape(Input::get('city')); ?>" required  maxlength ="20">
				<label for="country">Country *</label>
				<input type="text" name="country" id="country" placeholder="Please enter Country" value="<?php echo escape(Input::get('country')); ?>" required  maxlength ="20">
			</div>
			<!--<div class="field">
				<label for="phone">Address secondary</label>
				<input type="text" name="phone" id="phone" value="<?php //echo escape(Input::get('phone')); ?>">
			</div>-->



			<!--TOKEN PER SECURITY CHECK -->
			<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
			<input type="submit" value="Register" name="submit">
		</form>
		<p>Go back to <a href="index.php">home page</a></p>
<?php

	}
	else
	{
		echo "<p>You haven't got the right level of permission. " . 'Please go to <a href="index.php">home page</a></p>';
	}
}
else
{?>
	<p>You are not logged in at the moment. Go to <a href="login.php">log in</a></p>
	<?php
}


require_once 'includes/templates/footer.php';