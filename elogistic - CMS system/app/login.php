<?php
require_once 'core/init.php';


if(Input::exists())
{
	//Questo if viene usato per fermare ogni $_GET inserimento fatto dall url
	//per evitare attacchi.
	//il $_GET viene accettato solo dopo il submit e non automaticamente
	if (Token::checkToken(Input::get('token')))
	{
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username'			=> array(
				'required' => true
			),
			'password'			=> array(
				'required' => true
			)
		));
		if ($validation->getPassed())
		{
			//log user in
			$user = new User();
			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			
			if($login)
			{
				Session::flashMessage('login', 'You logged in!');
				Redirect::to('index.php');
			}
			else
			{
				echo '<p>Error password</p>';
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
		<label for="username">Username</label>
		<input type="text" name="username" id="username" autocomplete="off">
	</div>
	<div class="field">
		<label for="password">Choose a password</label>
		<input type="password" name="password" id="password">
	</div>
	<div class="field">
		<label for="remember">
			<input type="checkbox" name="remember" id="remember">Remember me
		</label>	
	</label>
	</div>

	<!--TOKEN PER SECURITY CHECK -->
	<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
	<input type="submit" value="Log in" name="submit">
</form>
<?php require_once 'includes/templates/footer.php';