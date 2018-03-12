<?php
require_once 'core/init.php';
require_once 'includes/templates/header.php';
$user = new User();

if(!$user->getIsLoggedIn())
{
	Redirect::to('index.php');
}
else
{
	if (Session::existsSession('email') && Session::existsSession('order_id') )
	{
		$txt = $txt . "Order No: " . Session::useSession('order_id') . ";" . PHP_EOL;	
		/*$subject = $txt;*/
		$txt = $txt ."Date: " . Session::useSession('email','date') . ";" . PHP_EOL;
		$txt = $txt ."Facility id: " . Session::useSession('email','facility_id') . ";" . PHP_EOL;
		$txt = $txt ."Facility name: " . Session::useSession('email','facility_name') . ";" . PHP_EOL;
		$txt = $txt ."Shipment type: " . Session::useSession('email','shipment') . ";" . PHP_EOL;
		$txt = $txt ."Pickup Address: " . Session::useSession('email','from_addr') . ";" . PHP_EOL;
		$txt = $txt ."Delivery Address: " . Session::useSession('email','to_addr') . ";" . PHP_EOL;	
		$txt = $txt ."Pickup Date: " . Session::useSession('email','from_date') . ";" . PHP_EOL;
		$txt = $txt ."Extra info: " . Session::useSession('email','extra_info') . ";" . PHP_EOL;
		//$msg = wordwrap($msg,70);
		
		
		// send email
		//$to = 'pi.at@hotmail.it' . $user->getInfoUser()->email;		
		/*$headers = "From: <app.eastendlogistics.co.uk>";
		mail($to,$subject,$txt,$headers);		
		Session::deleteSession('email');
		Session::flashMessage('sent','Order has been submitted');*/
		Session::deleteSession('order_id');
	}
	
	//Redirect::to('index.php');
	
}
?>


<html>
<head><title>Complete Order</title></head>

<body>
<form action="http://eastendlogistics.co.uk/app/sendemail/" method="post">
<table border="0" bgcolor="#ececec" cellspacing="5">
	<tr>
		<td>Name</td><td><input type="text" size="30" name="your-name" readonly="readonly" value="<?php echo Session::useSession('email','facility_name'); Session::deleteSession('email'); ?>"></td>
	</tr>
	<tr>
		<td>Email address</td><td><input type="text" size="30" name="your-email" readonly="readonly" value="<?php echo $user->getInfoUser()->email;	 ?>"></td>
	</tr>
	<tr>
		<td valign="top">Order content</td><td><textarea name="your-message" rows="4" cols="30" readonly="readonly"><?php echo $txt  ; ?></textarea></td>
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

