<?php


// PASSWORD FIELD SIZE :  60 caratteri   /// suggerita 255



$connect = mysqli_connect("127.0.0.1","root","password","hashpassword");
session_start();

if(isset($_SESSION['username']))
{
	header("Location:entry.php");
}
if(isset($_POST["register"]))
{
	if(empty($_POST['username']) || empty($_POST['password']))
	{
		echo '<script>alert("Both Fields are required")</script>';
	}
	else
	{
		$username = mysqli_real_escape_string($connect,$_POST['username']);
		$password = mysqli_real_escape_string($connect,$_POST['password']);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$query = "INSERT INTO users(username,password) VALUES ('$username','$password')";
		if(mysqli_query($connect, $query))
		{
			echo '<script>alert("Registration done!")</script>'; 
			header("Location:index.php?action=login");
		}
	}
}
if(isset($_POST["login"]))
{
	if(empty($_POST['username']) || empty($_POST['password']))
	{
		echo '<script>alert("Both Fields are required")</script>';
	}
	else
	{
		$username = mysqli_real_escape_string($connect,$_POST['username']);
		$password = mysqli_real_escape_string($connect,$_POST['password']);
		$query = "SELECT * FROM users WHERE username = '$username'";
		$result = mysqli_query($connect, $query);		
		
		if(mysqli_num_rows($result)> 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				if(password_verify($password,$row['password'] ))
				{
					//return true
					$_SESSION['username'] = $username;
					header("Location:entry.php");
				}
				else
				{
					//return false
					echo '<script>alert("Wrong user s details!")</script>'; 
				}
			}
		}
		else
		{
			echo '<script>alert("Wrong user details!")</script>'; 
		}
	}
}
if(isset($_GET['action']) == "login")
{ 
?>
<h1>Login</h1>
<form method="post">
	<div>
		<label for="username">Enter Username</label>
		<input type="text" name="username" id="username" class="form-control">
	</div>
	<div>	
		<label for="password">Enter password</label>
		<input type="password" name="password" id="password" class="form-control">
	</div>
	<div>	
		<input type="submit" name="login" value="login" class="btn btn-info">	
		<p><a href="index.php">Register</a></p>
	</div>
</form>
<?php 
}
else
{
?>
<h1>Register</h1>
<form method="post">
	<div>
		<label for="username">Enter Username</label>
		<input type="text" name="username" id="username" class="form-control">
	</div>
	<div>	
		<label for="password">Enter password</label>
		<input type="password" name="password" id="password" class="form-control">
	</div>
	<div>	
		<input type="submit" name="register" value="register" class="btn btn-info">	
		<p><a href="index.php?action=login">Login</a></p>
	</div>
</form>
<?php
}

?>