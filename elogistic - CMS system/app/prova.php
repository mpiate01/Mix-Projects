<?php
$to = "pi.at@hotmail.it";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com";

mail($to,$subject,$txt,$headers);
?>