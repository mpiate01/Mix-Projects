<?php
session_start();
print_r($_SESSION);
if(!isset($_SESSION['username']))
{
	header("Location:index.php?action=login");

}
echo "<h1>Welcome " . $_SESSION['username'] . " </h1>";
echo '<label><a href="logout.php">Logout</a></label>';