<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

$username = $_POST['username'];
$trimmedusername = trim($username);
$room = "None";

$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET room='$room' WHERE username='$trimmedusername'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>