<?php
require_once 'core/init.php';

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
?>

	<h3><?php echo escape($infoUser->username); ?></h3>
	<p>Full name: <?php echo escape($infoUser->name); ?></p>

<?php
}