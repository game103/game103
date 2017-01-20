<?php

	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_clicktheelephant");

	$str = "SELECT * FROM clicktheelephant";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$hs1 = $rows['hs1'];
		$hs2 = $rows['hs2'];
		$hs3 = $rows['hs3'];
		$hs4 = $rows['hs4'];
		$hs5 = $rows['hs5'];
		$hs6 = $rows['hs6'];
		$hs7 = $rows['hs7'];
		$hs8 = $rows['hs8'];
		$hs9 = $rows['hs9'];
		$hs10 = $rows['hs10'];
		$hsuser1 = $rows['hsuser1'];
		$hsuser2 = $rows['hsuser2'];
		$hsuser3 = $rows['hsuser3'];
		$hsuser4 = $rows['hsuser4'];
		$hsuser5 = $rows['hsuser5'];
		$hsuser6 = $rows['hsuser6'];
		$hsuser7 = $rows['hsuser7'];
		$hsuser8 = $rows['hsuser8'];
		$hsuser9 = $rows['hsuser9'];
		$hsuser10 = $rows['hsuser10'];
		
		$wkhs1 = $rows['wkhs1'];
		$wkhs2 = $rows['wkhs2'];
		$wkhs3 = $rows['wkhs3'];
		$wkhs4 = $rows['wkhs4'];
		$wkhs5 = $rows['wkhs5'];
		$wkhs6 = $rows['wkhs6'];
		$wkhs7 = $rows['wkhs7'];
		$wkhs8 = $rows['wkhs8'];
		$wkhs9 = $rows['wkhs9'];
		$wkhs10 = $rows['wkhs10'];
		$wkhsuser1 = $rows['wkhsuser1'];
		$wkhsuser2 = $rows['wkhsuser2'];
		$wkhsuser3 = $rows['wkhsuser3'];
		$wkhsuser4 = $rows['wkhsuser4'];
		$wkhsuser5 = $rows['wkhsuser5'];
		$wkhsuser6 = $rows['wkhsuser6'];
		$wkhsuser7 = $rows['wkhsuser7'];
		$wkhsuser8 = $rows['wkhsuser8'];
		$wkhsuser9 = $rows['wkhsuser9'];
		$wkhsuser10 = $rows['wkhsuser10'];

	endwhile;
	
	echo "&hs1=$hs1&hs2=$hs2&hs3=$hs3&hs4=$hs4&hs5=$hs5&hs6=$hs6&hs7=$hs7&hs8=$hs8&hs9=$hs9&hs10=$hs10&hsuser1=$hsuser1&hsuser2=$hsuser2&hsuser3=$hsuser3&hsuser4=$hsuser4&hsuser5=$hsuser5&hsuser6=$hsuser6&hsuser7=$hsuser7&hsuser8=$hsuser8&hsuser9=$hsuser9&hsuser10=$hsuser10&wkhs1=$wkhs1&wkhs2=$wkhs2&wkhs3=$wkhs3&wkhs4=$wkhs4&wkhs5=$wkhs5&wkhs6=$wkhs6&wkhs7=$wkhs7&wkhs8=$wkhs8&wkhs9=$wkhs9&wkhs10=$wkhs10&wkhsuser1=$wkhsuser1&wkhsuser2=$wkhsuser2&wkhsuser3=$wkhsuser3&wkhsuser4=$wkhsuser4&wkhsuser5=$wkhsuser5&wkhsuser6=$wkhsuser6&wkhsuser7=$wkhsuser7&wkhsuser8=$wkhsuser8&wkhsuser9=$wkhsuser9&wkhsuser10=$wkhsuser10&nothing=1";
	mysql_close();
?>