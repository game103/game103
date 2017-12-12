<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_cocoaball");

	$str = "SELECT * FROM highscores";
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
		$hs1user = $rows['hs1user'];
		$hs2user = $rows['hs2user'];
		$hs3user = $rows['hs3user'];
		$hs4user = $rows['hs4user'];
		$hs5user = $rows['hs5user'];
		$hs6user = $rows['hs6user'];
		$hs7user = $rows['hs7user'];
		$hs8user = $rows['hs8user'];
		$hs9user = $rows['hs9user'];
		$hs10user = $rows['hs10user'];
		
		$mohs1 = $rows['mohs1'];
		$mohs2 = $rows['mohs2'];
		$mohs3 = $rows['mohs3'];
		$mohs4 = $rows['mohs4'];
		$mohs5 = $rows['mohs5'];
		$mohs6 = $rows['mohs6'];
		$mohs7 = $rows['mohs7'];
		$mohs8 = $rows['mohs8'];
		$mohs9 = $rows['mohs9'];
		$mohs10 = $rows['mohs10'];
		$mohs1user = $rows['mohs1user'];
		$mohs2user = $rows['mohs2user'];
		$mohs3user = $rows['mohs3user'];
		$mohs4user = $rows['mohs4user'];
		$mohs5user = $rows['mohs5user'];
		$mohs6user = $rows['mohs6user'];
		$mohs7user = $rows['mohs7user'];
		$mohs8user = $rows['mohs8user'];
		$mohs9user = $rows['mohs9user'];
		$mohs10user = $rows['mohs10user'];

	endwhile;
	
	echo "&hs1=$hs1&hs2=$hs2&hs3=$hs3&hs4=$hs4&hs5=$hs5&hs6=$hs6&hs7=$hs7&hs8=$hs8&hs9=$hs9&hs10=$hs10&hs1user=$hs1user&hs2user=$hs2user&hs3user=$hs3user&hs4user=$hs4user&hs5user=$hs5user&hs6user=$hs6user&hs7user=$hs7user&hs8user=$hs8user&hs9user=$hs9user&hs10user=$hs10user&mohs1=$mohs1&mohs2=$mohs2&mohs3=$mohs3&mohs4=$mohs4&mohs5=$mohs5&mohs6=$mohs6&mohs7=$mohs7&mohs8=$mohs8&mohs9=$mohs9&mohs10=$mohs10&mohs1user=$mohs1user&mohs2user=$mohs2user&mohs3user=$mohs3user&mohs4user=$mohs4user&mohs5user=$mohs5user&mohs6user=$mohs6user&mohs7user=$mohs7user&mohs8user=$mohs8user&mohs9user=$mohs9user&mohs10user=$mohs10user&nothing=1";
	mysql_close();
?>