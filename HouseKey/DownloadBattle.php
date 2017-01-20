<?php

	$playwith = $_POST['playwith'];

	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$playwith'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$power = $rows['power'];
		$ready = $rows['ready'];

	endwhile;
	
	echo "&power=$power&ready=$ready";
	mysql_close();
?>