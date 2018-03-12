<?php
require_once 'core/init.php';
require_once 'includes/templates/header.php';
if (Session::existsSession('home'))
{
	echo '<p class="flash">' . Session::flashMessage('home') .'</p>';
}
if (Session::existsSession('logout'))
{
	echo '<p class="flash">' . Session::flashMessage('logout') .'</p>';
}
if (Session::existsSession('login'))
{
	echo '<p class="flash">' . Session::flashMessage('login') .'</p>';
}
if (Session::existsSession('sent'))
{
	echo '<p class="flash">' . Session::flashMessage('sent') .'</p>';
}
/*
echo Session::getSession(Config::get('session/session_name'));*/


$user = new User();
if ($user->getIsLoggedIn())
{ ?>

	<p>Hello <a href="profile.php?user=<?php echo escape($user->getInfoUser()->username) ; ?>" title="Check other facilities' details"><?php echo escape($user->getInfoUser()->username); ?></a>!</p>
	<ul id="menu">
			<li><a href="logout.php">Log out</a></li>
			<li><a href="update.php">Update details</a></li>
			<li><a href="changepassword.php">Change password</a></li>
			<li><a href="createorder.php">Create an order</a></li>
			<li><a href="orders.php">View Orders</a></li>
	<?php
		if($user->getHasPermission('admin'))  //e' stato usato un json value
		{ ?>
			<li><a href="register.php">Register new facility</a></li>
			<li><a href="chooseuser.php">Update facility's details</a></li>
			<li><a href="reports.php">View reports</a></li>			
			
		<?php } ?>
	</ul>	

<?php	
}
else
{
	echo '<p>You need to <a href="login.php">log in</a>';
}        
require_once 'includes/templates/footer.php';