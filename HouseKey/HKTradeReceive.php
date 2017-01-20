<?php

	$username = $_POST['username'];
	$trimmedusername = trim($username);

	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$parcel = $rows['parcel'];

	endwhile;
	
	echo "&parcel=$parcel";
	mysql_close();
?>