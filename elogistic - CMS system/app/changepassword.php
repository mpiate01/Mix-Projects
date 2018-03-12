<?php
require_once 'core/init.php';

$user = new User();

if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}

if (Input::exists())
{
	if(Token::checkToken(Input::get('token')))
	{
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true
			),
			'password_new' => array(
				'required' => true,
				'min'      	=> 6,
				'max'		=> 32
			),
			'password_again' => array(
				'required' => true,
				'matches'  => 'password_new'
			)
		));

		if($validation->getPassed())
		{
			//update
			if(Hash::setHash(Input::get('password_current'), $user->getInfoUser()->salt) !== $user->getInfoUser()->password)
			{
				echo 'Your current password is wrong';
			}
			else
			{
				//current password is valid
				$salt = Hash::setSalt(32);
				$user->update(array(
					'password' => Hash::setHash(trim(Input::get('password_new')), $salt),
					'salt'	   => $salt
				));

				Session::flashMessage('home','Your password has been changed');
				Redirect::to('index.php');
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
		<label for="password_current">Current password</label>
		<input type="password" name="password_current" id="password-current">
	</div>
	<div class="field">
		<label for="password_new">New password</label>
		<input type="password" name="password_new" id="password_new">
	</div>
	<div class="field">
		<label for="password_again">Enter your password</label>
		<input type="password" name="password_again" id="password_again">
	</div>
	<!--TOKEN PER SECURITY CHECK -->
	<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
	<input type="submit" value="Change password" name="submit">
</form>
<p>Go back to <a href="index.php">home page</a></p>
<?php

require_once 'includes/templates/footer.php';