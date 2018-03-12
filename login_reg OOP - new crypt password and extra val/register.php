<?php
require_once 'core/init.php';

//var_dump(Token::checkToken(Input::get('token')));

//Input controlla il $_POST con ISSET
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
				//qui si potrebbe pure aggiungere il nome a cui dare al field 
				//da poi usare quando display l errore, non mettendelo, si fa riferimento
				//all html tag name=""
				//esempio: 'name' => 'username'
				'required' => true,
				'min'      => 2,
				'max'      => 20,
				'unique'   => 'users', //unico nel users table
				'nospecialchar' => true
			),
			'password'			=> array(
				'required' => true,
				'min'      => 6,
				'strongPassword' => true
			),
			'password_again'	=> array(
				'required' => true,
				'matches'  => 'password'
			),
			'name'				=> array(
				'required' => true,
				'min'      => 2,
				'max'      => 35
			),
			'sname'				=> array(
				'required' => true,
				'min'      => 2,
				'max'      => 35
			),
			'mail'				=> array(
				'required'	=> true,
				'mail'		=> true
			)
		));

		if ($validation->getPassed())
		{
			//register user
			/*Session::flashMessage('success', 'You registered successfully!');
			header('Location: index.php');*/
			$user = new User();

			try
			{
				$user->setUser(array(
					'username'	=> Input::get('username'),
					'password'	=> Hash::setHash(Input::get('password')),
					'email'		=> Input::get('mail'),
					'name'		=> Input::get('name'),
					'sname'		=> Input::get('sname'),
					'joined'	=> date('Y-m-d H:i:s'),
					'group_perm'	=> 1
				));

				//Session::flashMessage('home', 'You have been registered and can now log in!');
				$user->login(Input::get('username'), Input::get('password'));
				Session::flashMessage('home', 'You have been registered!');
				Session::flashMessage('login', 'You logged in!');
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
			//output errors
			foreach($validation->getErrors() as $error)
			{
				echo "$error, <br>";
			}
		}
	}	
}

?>



<form action="" method="post">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
	</div>
	<div class="field">
		<label for="password">Choose a password</label>
		<input type="password" name="password" id="password" value="">
	</div>
	<div class="field">
		<label for="password_again">Enter your password</label>
		<input type="password" name="password_again" id="password_again" value="">
	</div>
	<div class="field">
		<label for="name">Your name</label>
		<input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
	</div>
	<div class="field">
		<label for="name">Your Surname</label>
		<input type="text" name="sname" id="sname" value="<?php echo escape(Input::get('sname')); ?>">
	</div>
	<div class="field">
		<label for="name">Your email</label>
		<input type="email" name="mail" id="mail" value="<?php echo escape(Input::get('mail')); ?>">
	</div>

	<!--TOKEN PER SECURITY CHECK -->
	<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
	<input type="submit" value="Register" name="submit">
</form>