<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

$username = $_POST['username'];
$score = $_POST['score'];



$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
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
	
	if($score < $hs1) {
	$push1down = "UPDATE clicktheelephant SET hs2='$hs1', hsuser2='$hsuser1'";
	$push1downquery = mysql_query($push1down, $connect);
	$push2down = "UPDATE clicktheelephant SET hs3='$hs2', hsuser3='$hsuser2'";
	$push2downquery = mysql_query($push2down, $connect);
	$push3down = "UPDATE clicktheelephant SET hs4='$hs3', hsuser4='$hsuser3'";
	$push3downquery = mysql_query($push3down, $connect);
	$push4down = "UPDATE clicktheelephant SET hs5='$hs4', hsuser5='$hsuser4'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE clicktheelephant SET hs6='$hs5', hsuser6='$hsuser5'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE clicktheelephant SET hs7='$hs6', hsuser7='$hsuser6'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE clicktheelephant SET hs8='$hs7', hsuser8='$hsuser7'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs1='$score', hsuser1='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs2 and $score >= $hs1) {
	$push2down = "UPDATE clicktheelephant SET hs3='$hs2', hsuser3='$hsuser2'";
	$push2downquery = mysql_query($push2down, $connect);
	$push3down = "UPDATE clicktheelephant SET hs4='$hs3', hsuser4='$hsuser3'";
	$push3downquery = mysql_query($push3down, $connect);
	$push4down = "UPDATE clicktheelephant SET hs5='$hs4', hsuser5='$hsuser4'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE clicktheelephant SET hs6='$hs5', hsuser6='$hsuser5'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE clicktheelephant SET hs7='$hs6', hsuser7='$hsuser6'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE clicktheelephant SET hs8='$hs7', hsuser8='$hsuser7'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs2='$score', hsuser2='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs3 and $score >= $hs2) {
	$push3down = "UPDATE clicktheelephant SET hs4='$hs3', hsuser4='$hsuser3'";
	$push3downquery = mysql_query($push3down, $connect);
	$push4down = "UPDATE clicktheelephant SET hs5='$hs4', hsuser5='$hsuser4'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE clicktheelephant SET hs6='$hs5', hsuser6='$hsuser5'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE clicktheelephant SET hs7='$hs6', hsuser7='$hsuser6'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE clicktheelephant SET hs8='$hs7', hsuser8='$hsuser7'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs3='$score', hsuser3='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs4 and $score >= $hs3) {
	$push4down = "UPDATE clicktheelephant SET hs5='$hs4', hsuser5='$hsuser4'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE clicktheelephant SET hs6='$hs5', hsuser6='$hsuser5'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE clicktheelephant SET hs7='$hs6', hsuser7='$hsuser6'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE clicktheelephant SET hs8='$hs7', hsuser8='$hsuser7'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs4='$score', hsuser4='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs5 and $score >= $hs4) {
	$push5down = "UPDATE clicktheelephant SET hs6='$hs5', hsuser6='$hsuser5'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE clicktheelephant SET hs7='$hs6', hsuser7='$hsuser6'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE clicktheelephant SET hs8='$hs7', hsuser8='$hsuser7'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs5='$score', hsuser5='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs6 and $score >= $hs5) {
	$push6down = "UPDATE clicktheelephant SET hs7='$hs6', hsuser7='$hsuser6'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE clicktheelephant SET hs8='$hs7', hsuser8='$hsuser7'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs6='$score', hsuser6='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs7 and $score >= $hs6) {
	$push7down = "UPDATE clicktheelephant SET hs8='$hs7', hsuser8='$hsuser7'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs7='$score', hsuser7='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs8 and $score >= $hs7) {
	$push8down = "UPDATE clicktheelephant SET hs9='$hs8', hsuser9='$hsuser8'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs8='$score', hsuser8='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs9 and $score >= $hs8) {
	$push9down = "UPDATE clicktheelephant SET hs10='$hs9', hsuser10='$hsuser9'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE clicktheelephant SET hs9='$score', hsuser9='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score < $hs10 and $score >= $hs9) {
	$insert = "UPDATE clicktheelephant SET hs10='$score', hsuser10='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	
	
	
	
	
	
	if($score < $wkhs1) {
	$mopush1down = "UPDATE clicktheelephant SET wkhs2='$wkhs1', wkhsuser2='$wkhsuser1'";
	$mopush1downquery = mysql_query($mopush1down, $connect);
	$mopush2down = "UPDATE clicktheelephant SET wkhs3='$wkhs2', wkhsuser3='$wkhsuser2'";
	$mopush2downquery = mysql_query($mopush2down, $connect);
	$mopush3down = "UPDATE clicktheelephant SET wkhs4='$wkhs3', wkhsuser4='$wkhsuser3'";
	$mopush3downquery = mysql_query($mopush3down, $connect);
	$mopush4down = "UPDATE clicktheelephant SET wkhs5='$wkhs4', wkhsuser5='$wkhsuser4'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE clicktheelephant SET wkhs6='$wkhs5', wkhsuser6='$wkhsuser5'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE clicktheelephant SET wkhs7='$wkhs6', wkhsuser7='$wkhsuser6'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE clicktheelephant SET wkhs8='$wkhs7', wkhsuser8='$wkhsuser7'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs1='$score', wkhsuser1='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs2 and $score >= $wkhs1) {
	$mopush2down = "UPDATE clicktheelephant SET wkhs3='$wkhs2', wkhsuser3='$wkhsuser2'";
	$mopush2downquery = mysql_query($mopush2down, $connect);
	$mopush3down = "UPDATE clicktheelephant SET wkhs4='$wkhs3', wkhsuser4='$wkhsuser3'";
	$mopush3downquery = mysql_query($mopush3down, $connect);
	$mopush4down = "UPDATE clicktheelephant SET wkhs5='$wkhs4', wkhsuser5='$wkhsuser4'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE clicktheelephant SET wkhs6='$wkhs5', wkhsuser6='$wkhsuser5'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE clicktheelephant SET wkhs7='$wkhs6', wkhsuser7='$wkhsuser6'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE clicktheelephant SET wkhs8='$wkhs7', wkhsuser8='$wkhsuser7'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs2='$score', wkhsuser2='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs3 and $score >= $wkhs2) {
	$mopush3down = "UPDATE clicktheelephant SET wkhs4='$wkhs3', wkhsuser4='$wkhsuser3'";
	$mopush3downquery = mysql_query($mopush3down, $connect);
	$mopush4down = "UPDATE clicktheelephant SET wkhs5='$wkhs4', wkhsuser5='$wkhsuser4'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE clicktheelephant SET wkhs6='$wkhs5', wkhsuser6='$wkhsuser5'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE clicktheelephant SET wkhs7='$wkhs6', wkhsuser7='$wkhsuser6'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE clicktheelephant SET wkhs8='$wkhs7', wkhsuser8='$wkhsuser7'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs3='$score', wkhsuser3='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs4 and $score >= $wkhs3) {
	$mopush4down = "UPDATE clicktheelephant SET wkhs5='$wkhs4', wkhsuser5='$wkhsuser4'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE clicktheelephant SET wkhs6='$wkhs5', wkhsuser6='$wkhsuser5'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE clicktheelephant SET wkhs7='$wkhs6', wkhsuser7='$wkhsuser6'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE clicktheelephant SET wkhs8='$wkhs7', wkhsuser8='$wkhsuser7'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs4='$score', wkhsuser4='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs5 and $score >= $wkhs4) {
	$mopush5down = "UPDATE clicktheelephant SET wkhs6='$wkhs5', wkhsuser6='$wkhsuser5'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE clicktheelephant SET wkhs7='$wkhs6', wkhsuser7='$wkhsuser6'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE clicktheelephant SET wkhs8='$wkhs7', wkhsuser8='$wkhsuser7'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs5='$score', wkhsuser5='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs6 and $score >= $wkhs5) {
	$mopush6down = "UPDATE clicktheelephant SET wkhs7='$wkhs6', wkhsuser7='$wkhsuser6'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE clicktheelephant SET wkhs8='$wkhs7', wkhsuser8='$wkhsuser7'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs6='$score', wkhsuser6='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs7 and $score >= $wkhs6) {
	$mopush7down = "UPDATE clicktheelephant SET wkhs8='$wkhs7', wkhsuser8='$wkhsuser7'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs7='$score', wkhsuser7='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs8 and $score >= $wkhs7) {
	$mopush8down = "UPDATE clicktheelephant SET wkhs9='$wkhs8', wkhsuser9='$wkhsuser8'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs8='$score', wkhsuser8='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs9 and $score >= $wkhs8) {
	$mopush9down = "UPDATE clicktheelephant SET wkhs10='$wkhs9', wkhsuser10='$wkhsuser9'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE clicktheelephant SET wkhs9='$score', wkhsuser9='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score < $wkhs10 and $score >= $wkhs9) {
	$moinsert = "UPDATE clicktheelephant SET wkhs10='$score', wkhsuser10='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	}
	
	echo "$hs1";
?>