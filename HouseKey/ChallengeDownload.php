<?php

	$battlewith = $_POST['battlewith'];

	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$battlewith'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$battlewithloaded = $rows['battlewith'];

	endwhile;
	
	echo "&battlewith=$battlewithloaded";
	mysql_close();
?>