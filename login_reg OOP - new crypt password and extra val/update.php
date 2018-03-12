<?php
require_once 'core/init.php';

$user = new User();

if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}

if(Input::exists())
{
	if(Token::checkToken(Input::get('token')))
	{
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min'	   => 2,
				'max'	   => 50
				)
		));

		if ($validation->getPassed())
		{
			//update
			try
			{
				$user->update(array(
					'name' => trim(Input::get('name'))
				));

				Session::flashMessage('home', 'Your details have been update');
				Redirect::to('index.php');
			}
			catch(Exception $e)
			{
				die($e->getMessage());
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
		<label for="name">Your name</label>
		<input type="text" name="name" id="name" value="<?php echo escape($user->getInfoUser()->name); ?>">
	</div>

	<!--TOKEN PER SECURITY CHECK -->
	<input type="hidden" name="token" value="<?php echo Token::generateToken() ?>"> 
	<input type="submit" value="Update" name="submit">
</form>