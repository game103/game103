<?php

	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
		
	// Require modules
	require_once( 'Constants.class.php');

	$username = $_POST['username'];
	$trimmedusername = trim($username);

	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
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