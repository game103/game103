<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

$x = $_POST['x'];
$y = $_POST['y'];
$speed = $_POST['speed'];
$username = $_POST['username'];
$trimmedusername = trim($username);
$password = $_POST['password'];
$trimmedpassword= trim($password);

$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername' AND password='$trimmedpassword'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET x='$x', y='$y', speed='$speed' WHERE username='$trimmedusername'";
	$insertquery = mysql_query($insert, $connect);
	echo $x;
	echo $y;
	echo $trimmedusername;
	echo $trimmedpassword;
	mysql_close();
?>