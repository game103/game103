<?php

	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
		
	// Require modules
	require_once( 'Constants.class.php');

	$battlewith = $_POST['battlewith'];

	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$battlewith'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$enemyattack = $rows['attack'];
		$enemydefense = $rows['defense'];
		$enemyhealth = $rows['health'];

	endwhile;
	
	echo "&enemyattack=$enemyattack&enemydefense=$enemydefense&enemyhealth=$enemyhealth";
	mysql_close();
?>