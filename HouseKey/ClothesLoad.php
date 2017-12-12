<?php

	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
		
	// Require modules
	require_once( 'Constants.class.php');

	$playwith = $_POST['playwith'];
	$trimmedplaywith = trim($playwith);
	$playwith2 = $_POST['playwith2'];
	$trimmedplaywith2 = trim($playwith2);
	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedplaywith'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$headequipped=$rows['headequipped'];
		$bodyequipped=$rows['bodyequipped'];
		$lefthandequipped=$rows['lefthandequipped'];
		$righthandequipped=$rows['righthandequipped'];
		$legsequipped=$rows['legsequipped'];
		$leftfootequipped=$rows['leftfootequipped'];
		$rightfootequipped=$rows['rightfootequipped'];

	endwhile;
	
	$str2 = "SELECT * FROM variables WHERE username = '$trimmedplaywith2'";
	$query2 = mysql_query($str2);
	
	$num2 = mysql_num_rows($query2);
	
	while($rows2 = mysql_fetch_array($query2)):

		$headequipped2=$rows2['headequipped'];
		$bodyequipped2=$rows2['bodyequipped'];
		$lefthandequipped2=$rows2['lefthandequipped'];
		$righthandequipped2=$rows2['righthandequipped'];
		$legsequipped2=$rows2['legsequipped'];
		$leftfootequipped2=$rows2['leftfootequipped'];
		$rightfootequipped2=$rows2['rightfootequipped'];

	endwhile;
	
	
	echo "&headequipped=$headequipped&bodyequipped=$bodyequipped&lefthandequipped=$lefthandequipped&righthandequipped=$righthandequipped&legsequipped=$legsequipped&leftfootequipped=$leftfootequipped&rightfootequipped=$rightfootequipped&headequipped2=$headequipped2&bodyequipped2=$bodyequipped2&lefthandequipped2=$lefthandequipped2&righthandequipped2=$righthandequipped2&legsequipped2=$legsequipped2&leftfootequipped2=$leftfootequipped2&rightfootequipped2=$rightfootequipped2";
	mysql_close();
?>