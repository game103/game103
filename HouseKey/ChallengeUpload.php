<?php
$battlewith = $_POST['battlewith'];
$username = $_POST['username'];
$trimmedusername = trim($username);

$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET battlewith='$battlewith' WHERE username='$trimmedusername'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>