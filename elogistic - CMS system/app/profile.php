<?php
require_once 'core/init.php';

$display = false;
if(!$username = Input::get('user'))
{
	Redirect::to('index.php');
}
else
{
	$user = new User($username);
	if(!$user->existUser())
	{
		Redirect::to(404);
	}
	else
	{
		$infoUser = $user->getInfoUser();
	}

	if(Input::exists())
	{
		
		if (Token::checkToken(Input::get('token')))		//get('token') viene preso dallaform
		{
			$validate = new Validate();
				
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
?>

	<h3>Hello <?php echo ucfirst(escape($infoUser->username)); ?>!</h3>
	<form action="" method="post">			
		<div class="field">
			<label for="id">Select facility to display releted details: *</label>
			<select id="id" name="id">
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
			<input type="submit" value="Display" name="submit">
		</form>

	<p>Go back to <a href="index.php">home page</a></p>

<?php
	if($display)
	{				
		$user_to_display = new User(Input::get('id'));
		echo '<p>Facility name: ' . $user_to_display->getInfoUser()->username . '</p>';
		echo '<p>Facility name: ' . $user_to_display->getInfoUser()->phone . '</p>';
		echo '<p>Facility name: ' . $user_to_display->getInfoUser()->email . '</p>';
	}




}

require_once 'includes/templates/footer.php';
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
/////// non c'e nessun controllo, se digito nell url qualsiasi nome 
/////// esistente, lo posso trovare
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////


////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
/////      DA MODIFICARE ASSOLUTAMENTE
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
