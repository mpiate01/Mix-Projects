<?php
require_once 'core/init.php';


$user = new User();

if ($user->getIsLoggedIn())
{
	$user->logout();
	Session::flashMessage('logout', 'You have been logged out!');
	Redirect::to('index.php');
}
else
{	require_once 'includes/templates/header.php';?>
	<p>You are not logged in at the moment. Go to <a href="login.php">log in</a></p>
	<?php
}
require_once 'includes/templates/footer.php';

