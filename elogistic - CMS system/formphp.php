<?php
require_once 'core/init.php';

$user = new User();

if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}
else
{
	if (Session::existsSession('email'))
	{
		$txt = "Order No: " . Session::useSession('email','order_id') . "\n";	
		/*$subject = $txt;*/
		$txt = "Date: " . Session::useSession('email','date') . "\n";
		$txt = "Facility id: " . Session::useSession('email','facility_id') . "\n";
		$txt = "Facility name: " . Session::useSession('email','facility_name') . "\n";
		$txt = "Shipment type: " . Session::useSession('email','shipment') . "\n";
		$txt = "Pickup Address: " . Session::useSession('email','from_addr') . "\n";
		$txt = "Delivery Address: " . Session::useSession('email','to_addr') . "\n";	
		$txt = "Pickup Date: " . Session::useSession('email','from_date') . "\n";
		$txt = "Extra info: " . Session::useSession('email','extra_info') . "\n";
		//$msg = wordwrap($msg,70);
		
		// send email
		$to = 'pi.at@hotmail.it' . $user->getInfoUser()->email;		
		/*$headers = "From: <app.eastendlogistics.co.uk>";
		mail($to,$subject,$txt,$headers);		
		Session::deleteSession('email');
		Session::flashMessage('sent','Order has been submitted');*/
	}
	
	//Redirect::to('index.php');
	
}
?>


<html>
<head><title>Complete Order</title></head>

<body>
<form action="FormToEmail.php" method="post">
<table border="0" bgcolor="#ececec" cellspacing="5">
	<tr>
		<td>Name</td><td><input type="text" size="30" name="name" value="<?php echo Session::useSession('email','facility_name'); ?>"></td>
	</tr>
	<tr>
		<td>Email address</td><td><input type="text" size="30" name="email" value="<?php echo $user->getInfoUser()->email;	 ?>"></td>
	</tr>
	<tr>
		<td valign="top">Order content</td><td><textarea name="comments" rows="6" cols="30"></textarea></td>
		<input type="text" name="order" size="30" value="<?php echo $txt  ; ?>" readonly="readonly">
	</tr>
	<tr>
		<td>&nbsp;</td><td><input type="submit" value="Send">
		<font face="arial" size="1">&nbsp;&nbsp;<a href="http://FormToEmail.com">Form Mail</a> by FormToEmail.com</font>
		</td>
	</tr>
</table>
</form>

</body>
</html>

