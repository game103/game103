<?php
	$message=$_POST['message'];
	$notagsmessage = strip_tags($message);
	$username = $_POST['username'];
	$trimmedusername = trim($username);
	$password = $_POST['password'];
	$trimmedpassword= trim($password);
	

$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername' AND password='$trimmedpassword'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET message='$notagsmessage' WHERE username='$trimmedusername'";
	$insertquery = mysql_query($insert, $connect);
	echo $message;
	mysql_close();
?>