<?php
	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_housekey");
	$insert = "UPDATE variables SET room='None'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>