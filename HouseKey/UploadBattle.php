<?php
$power = $_POST['power'];
$ready = $_POST['ready'];
$username = $_POST['username'];
$trimmedusername = trim($username);
$password = $_POST['password'];
$trimmedpassword= trim($password);

$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET power='$power', ready='$ready' WHERE username='$trimmedusername'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>