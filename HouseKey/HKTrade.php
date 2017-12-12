<?php

	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
		
	// Require modules
	require_once( 'Constants.class.php');

	$tradewithut = $_POST['tradewith'];
	$tradewith = trim($tradewithut);
	$tradeitem = $_POST['tradeitem'];
	
	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$tradewith'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	if($num == 0) {
	$work = "fail";
	}
	else {
	while($rows = mysql_fetch_array($query)):

		$parcel = $rows['parcel'];
		
	endwhile;	
		
	if($parcel == "None" OR $tradeitem == "None") {
	$insert = "UPDATE variables SET parcel='$tradeitem' WHERE username='$tradewith'";
	$insertquery = mysql_query($insert, $connect);
	$work = "success";
	}
	else {
	$work = "fail";
	}
	}
	mysql_close();
	echo "&work=$work";
?>