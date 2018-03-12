<?php
require_once 'core/init.php';

if (Session::existsSession('home'))
{
	echo '<p>' . Session::flashMessage('home') .'</p>';
}
if (Session::existsSession('logout'))
{
	echo '<p>' . Session::flashMessage('logout') .'</p>';
}
if (Session::existsSession('login'))
{
	echo '<p>' . Session::flashMessage('login') .'</p>';
}
/*
echo Session::getSession(Config::get('session/session_name'));*/


$user = new User();
if ($user->getIsLoggedIn())
{ ?>

<p>Hello <a href="profile.php?user=<?php echo escape($user->getInfoUser()->username) ; ?>"><?php echo escape($user->getInfoUser()->username); ?></a>!</p>
<ul>
	<li><a href="logout.php">Log out</a></li>
	<li><a href="update.php">Update details</a></li>
	<li><a href="changepassword.php">Change password</a></li>
</ul>
<?php
	if($user->getHasPermission('admin'))  //e' stato usato un json value
	{
		echo '<p>You are an administrator!</p>';
	}
}
else
{
	echo '<p>You need to <a href="login.php">log in</a> or <a href="register.php">register</a>';
}

/*ESEMPIO di query
$users = DB::getInstance()->	query('SELECT username FROM users');
								o, altro esempio
								get('users', array('username','=','alex'))
if($users->count())
{
	foreach ($users as $user) 
	{
		echo $user->username;
	}
}
*/
//funzionante

//$user = DB::getInstance()->query("SELECT username FROM users WHERE username = ?", array('alex'));
//$user = DB::getInstance()->getQuery('users' , array('username','=','alex'));
/*$user = DB::getInstance()->query("SELECT username From users");

if(!$user->affectedRowQuery())  //count() per il tipo
{
	echo 'No user';
} else {
	echo $user->firstResult()->username;
}*/

/*
$userInsert = DB::getInstance()->insertQuery('users', array(
		'username' => 'Dale',
		'password' => 'password',
		'salt' 	   => 'salt',
		'name'	   => 'name',
		'joined'   => '2017-01-13 11:26:19',
		'group'    => '1'	
));
*/
/*
$userUpdate = DB::getInstance()->updateQuery('users', 5, array(
		'password' => 'newpassword',
		'name'     => 'PincoPallino' 
	));
*/