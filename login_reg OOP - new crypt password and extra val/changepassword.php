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
				'min'	   => 6	
			),
			'password_again' => array(
				'required' => true,
				'min'	   => 6,
				'matches'  => 'password_new',
				'anew'		=> 'password_current'
			)
		));

		if($validation->getPassed())
		{
			print_r(Input::get('password_current'));
			print_r($user->getInfoUser()->password);
			//update
			if(Hash::getHash(Input::get('password_current'), $user->getInfoUser()->password))
			{
				//current password is valid
				$user->update(array(
					'password' => Hash::setHash(Input::get('password_new'))
				));

				Session::flashMessage('home','Your password has been changed');
				Redirect::to('index.php');				
			}
			else
			{
				echo 'Your current password is wrong';
			}
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