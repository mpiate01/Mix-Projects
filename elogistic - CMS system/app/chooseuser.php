<?php
require_once 'core/init.php';


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
					'id'			=> array(
						'name_error' 	=> 'User',	
						'exists'		=> array ('facilities' => 'id')
					)
				));
				if(Input::get('id') == 0)
				{
					$validation->setPassed(false);
				}

				//Register User
				if ($validation->getPassed())
				{	
					Session::setSession('id_user_to_be_update',Input::get('id'));
					Redirect::to('./updatemaster.php');
				}
				else
				{
					//Output errors
					$errors = $validation->getErrors();
				}
			}
			/*else
			{
							SE SI VUOLE AGGIUNGERE QUALCOSA SE TOKEN NON ESISTE e VIENE FATTO QUALCOSA, in teoria non serve aggiungere nulla
			}*/	
		}
		require_once 'includes/templates/header.php';
		if(isset($errors))
		{
			foreach($errors as $error)
			{
				echo '<p class="error">' . $error . '</p>';
			}
		}


		echo '<p>You are an administrator!</p>';
		?>



		<form action="" method="post">			
			<div class="field">
				<label for="id">Select facility to update *</label>
				<select id="id" name="id">
				<option value="0"></option>
				<?php				
				$users = new User();
					if ($users->setInfoAllUsers())
					{
						foreach($users->getInfoUser() as $row)
						{
							if($user->getInfoUser()->id != $row->id)
							{
								echo '<option value="' . $row->id . '">' . $row->username . '</option>';
							}
						}
					}					
				?>
				</select>
			</div>
			
			<!--TOKEN PER SECURITY CHECK -->
			<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
			<!--<input type="hidden" name="reset_value" value="1"> -->
			<input type="submit" value="Update user details" name="submit">
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


if (Session::existsSession('updatemaster'))
{
	echo '<p>' . Session::flashMessage('updatemaster') .'</p>';
}