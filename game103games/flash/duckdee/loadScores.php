<?php

	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
		
	// Require modules
	require_once( 'Constants.class.php');
	
	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_duckdee");
	
	$str = "SELECT * FROM high_scores ORDER BY score DESC, score_date DESC";
	$query = mysql_query($str);
	
	$i = 1;
	while($rows = mysql_fetch_array($query)):
		
		$username = $rows['username'];
		$score = $rows['score'];
		$score_date = $rows['score_date'];
		echo"$i";
		echo "&score$i=$score&username$i=$username&scoreDate$i=$score_date";
		
		$i++;
		
	endwhile;

	while($i < 11) {
		$username = "Duckdee";
		$score = 0;
		$score_date = 0;
		
		echo "&score$i=$score&username$i=$username&scoreDate$i=$score_date";
		$i++;
	}
	
	$weeklyStr = "SELECT * FROM high_scores WHERE score_date > DATE_SUB(NOW(), INTERVAL 1 WEEK) ORDER BY score DESC, score_date DESC";
	$weeklyQuery = mysql_query($weeklyStr);
	
	$i = 1;
	while($rows = mysql_fetch_array($weeklyQuery)):
		
		$username = $rows['username'];
		$score = $rows['score'];
		$score_date = $rows['score_date'];
		
		echo "&wscore$i=$score&wusername$i=$username&wscoreDate$i=$score_date";
		
		$i++;
		
	endwhile;
	
	while($i < 11) {
		$username = "Duckdee";
		$score = 0;
		$score_date = 0;
		
		echo "&wscore$i=$score&wusername$i=$username&wscoreDate$i=$score_date";
		$i++;
	}
	
	mysql_close();
?>