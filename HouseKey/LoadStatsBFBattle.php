<?php

	$battlewith = $_POST['battlewith'];

	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
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