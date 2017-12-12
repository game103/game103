<?php

	set_include_path("/var/www/game103/modules");
		
	// Require modules
	require_once( 'Constants.class.php');

	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");
	$insert = "UPDATE variables SET room='None'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>
