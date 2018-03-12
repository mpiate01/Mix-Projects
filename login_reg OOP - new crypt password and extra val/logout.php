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
{?>
<p>You are not logged in at the moment. Go to <a href="login.php">log in</a> or <a href="register.php">register</a> page</p>
<?php
}

