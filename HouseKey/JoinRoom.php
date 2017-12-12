<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

$username = $_POST['username'];
$trimmedusername = trim($username);
$room = $_POST['room'];
$trimmedroom= trim($room);
$battlewith = "None";
$power = 0;
$ready = 0;

$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername' AND room='$trimmedroom'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET room='$room', battlewith='$battlewith', power='$power', ready='$ready' WHERE username='$trimmedusername'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>