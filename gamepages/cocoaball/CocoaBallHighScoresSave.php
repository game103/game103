<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

$username = $_POST['username'];
$score = $_POST['score'];



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
	
	if($score > $hs1) {
	$push1down = "UPDATE highscores SET hs2='$hs1', hs2user='$hs1user'";
	$push1downquery = mysql_query($push1down, $connect);
	$push2down = "UPDATE highscores SET hs3='$hs2', hs3user='$hs2user'";
	$push2downquery = mysql_query($push2down, $connect);
	$push3down = "UPDATE highscores SET hs4='$hs3', hs4user='$hs3user'";
	$push3downquery = mysql_query($push3down, $connect);
	$push4down = "UPDATE highscores SET hs5='$hs4', hs5user='$hs4user'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE highscores SET hs6='$hs5', hs6user='$hs5user'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE highscores SET hs7='$hs6', hs7user='$hs6user'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE highscores SET hs8='$hs7', hs8user='$hs7user'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs1='$score', hs1user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs2 and $score <= $hs1) {
	$push2down = "UPDATE highscores SET hs3='$hs2', hs3user='$hs2user'";
	$push2downquery = mysql_query($push2down, $connect);
	$push3down = "UPDATE highscores SET hs4='$hs3', hs4user='$hs3user'";
	$push3downquery = mysql_query($push3down, $connect);
	$push4down = "UPDATE highscores SET hs5='$hs4', hs5user='$hs4user'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE highscores SET hs6='$hs5', hs6user='$hs5user'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE highscores SET hs7='$hs6', hs7user='$hs6user'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE highscores SET hs8='$hs7', hs8user='$hs7user'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs2='$score', hs2user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs3 and $score <= $hs2) {
	$push3down = "UPDATE highscores SET hs4='$hs3', hs4user='$hs3user'";
	$push3downquery = mysql_query($push3down, $connect);
	$push4down = "UPDATE highscores SET hs5='$hs4', hs5user='$hs4user'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE highscores SET hs6='$hs5', hs6user='$hs5user'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE highscores SET hs7='$hs6', hs7user='$hs6user'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE highscores SET hs8='$hs7', hs8user='$hs7user'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs3='$score', hs3user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs4 and $score <= $hs3) {
	$push4down = "UPDATE highscores SET hs5='$hs4', hs5user='$hs4user'";
	$push4downquery = mysql_query($push4down, $connect);
	$push5down = "UPDATE highscores SET hs6='$hs5', hs6user='$hs5user'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE highscores SET hs7='$hs6', hs7user='$hs6user'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE highscores SET hs8='$hs7', hs8user='$hs7user'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs4='$score', hs4user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs5 and $score <= $hs4) {
	$push5down = "UPDATE highscores SET hs6='$hs5', hs6user='$hs5user'";
	$push5downquery = mysql_query($push5down, $connect);
	$push6down = "UPDATE highscores SET hs7='$hs6', hs7user='$hs6user'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE highscores SET hs8='$hs7', hs8user='$hs7user'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs5='$score', hs5user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs6 and $score <= $hs5) {
	$push6down = "UPDATE highscores SET hs7='$hs6', hs7user='$hs6user'";
	$push6downquery = mysql_query($push6down, $connect);
	$push7down = "UPDATE highscores SET hs8='$hs7', hs8user='$hs7user'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs6='$score', hs6user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs7 and $score <= $hs6) {
	$push7down = "UPDATE highscores SET hs8='$hs7', hs8user='$hs7user'";
	$push7downquery = mysql_query($push7down, $connect);
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs7='$score', hs7user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs8 and $score <= $hs7) {
	$push8down = "UPDATE highscores SET hs9='$hs8', hs9user='$hs8user'";
	$push8downquery = mysql_query($push8down, $connect);
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs8='$score', hs8user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs9 and $score <= $hs8) {
	$push9down = "UPDATE highscores SET hs10='$hs9', hs10user='$hs9user'";
	$push9downquery = mysql_query($push9down, $connect);
	$insert = "UPDATE highscores SET hs9='$score', hs9user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	else if($score > $hs10 and $score <= $hs9) {
	$insert = "UPDATE highscores SET hs10='$score', hs10user='$username'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
	}
	
	
	
	
	
	
	
	if($score > $mohs1) {
	$mopush1down = "UPDATE highscores SET mohs2='$mohs1', mohs2user='$mohs1user'";
	$mopush1downquery = mysql_query($mopush1down, $connect);
	$mopush2down = "UPDATE highscores SET mohs3='$mohs2', mohs3user='$mohs2user'";
	$mopush2downquery = mysql_query($mopush2down, $connect);
	$mopush3down = "UPDATE highscores SET mohs4='$mohs3', mohs4user='$mohs3user'";
	$mopush3downquery = mysql_query($mopush3down, $connect);
	$mopush4down = "UPDATE highscores SET mohs5='$mohs4', mohs5user='$mohs4user'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE highscores SET mohs6='$mohs5', mohs6user='$mohs5user'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE highscores SET mohs7='$mohs6', mohs7user='$mohs6user'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE highscores SET mohs8='$mohs7', mohs8user='$mohs7user'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs1='$score', mohs1user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs2 and $score <= $mohs1) {
	$mopush2down = "UPDATE highscores SET mohs3='$mohs2', mohs3user='$mohs2user'";
	$mopush2downquery = mysql_query($mopush2down, $connect);
	$mopush3down = "UPDATE highscores SET mohs4='$mohs3', mohs4user='$mohs3user'";
	$mopush3downquery = mysql_query($mopush3down, $connect);
	$mopush4down = "UPDATE highscores SET mohs5='$mohs4', mohs5user='$mohs4user'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE highscores SET mohs6='$mohs5', mohs6user='$mohs5user'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE highscores SET mohs7='$mohs6', mohs7user='$mohs6user'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE highscores SET mohs8='$mohs7', mohs8user='$mohs7user'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs2='$score', mohs2user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs3 and $score <= $mohs2) {
	$mopush3down = "UPDATE highscores SET mohs4='$mohs3', mohs4user='$mohs3user'";
	$mopush3downquery = mysql_query($mopush3down, $connect);
	$mopush4down = "UPDATE highscores SET mohs5='$mohs4', mohs5user='$mohs4user'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE highscores SET mohs6='$mohs5', mohs6user='$mohs5user'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE highscores SET mohs7='$mohs6', mohs7user='$mohs6user'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE highscores SET mohs8='$mohs7', mohs8user='$mohs7user'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs3='$score', mohs3user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs4 and $score <= $mohs3) {
	$mopush4down = "UPDATE highscores SET mohs5='$mohs4', mohs5user='$mohs4user'";
	$mopush4downquery = mysql_query($mopush4down, $connect);
	$mopush5down = "UPDATE highscores SET mohs6='$mohs5', mohs6user='$mohs5user'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE highscores SET mohs7='$mohs6', mohs7user='$mohs6user'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE highscores SET mohs8='$mohs7', mohs8user='$mohs7user'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs4='$score', mohs4user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs5 and $score <= $mohs4) {
	$mopush5down = "UPDATE highscores SET mohs6='$mohs5', mohs6user='$mohs5user'";
	$mopush5downquery = mysql_query($mopush5down, $connect);
	$mopush6down = "UPDATE highscores SET mohs7='$mohs6', mohs7user='$mohs6user'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE highscores SET mohs8='$mohs7', mohs8user='$mohs7user'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs5='$score', mohs5user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs6 and $score <= $mohs5) {
	$mopush6down = "UPDATE highscores SET mohs7='$mohs6', mohs7user='$mohs6user'";
	$mopush6downquery = mysql_query($mopush6down, $connect);
	$mopush7down = "UPDATE highscores SET mohs8='$mohs7', mohs8user='$mohs7user'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs6='$score', mohs6user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs7 and $score <= $mohs6) {
	$mopush7down = "UPDATE highscores SET mohs8='$mohs7', mohs8user='$mohs7user'";
	$mopush7downquery = mysql_query($mopush7down, $connect);
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs7='$score', mohs7user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs8 and $score <= $mohs7) {
	$mopush8down = "UPDATE highscores SET mohs9='$mohs8', mohs9user='$mohs8user'";
	$mopush8downquery = mysql_query($mopush8down, $connect);
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs8='$score', mohs8user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs9 and $score <= $mohs8) {
	$mopush9down = "UPDATE highscores SET mohs10='$mohs9', mohs10user='$mohs9user'";
	$mopush9downquery = mysql_query($mopush9down, $connect);
	$moinsert = "UPDATE highscores SET mohs9='$score', mohs9user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	mysql_close();
	}
	
	else if($score > $mohs10 and $score <= $mohs9) {
	$moinsert = "UPDATE highscores SET mohs10='$score', mohs10user='$username'";
	$moinsertquery = mysql_query($moinsert, $connect);
	}
	
	echo "$hs1";
?>