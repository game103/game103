<?php
	error_reporting(0);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');

	$user_id = $_POST['id'];
	$score = $_POST['score'];
	$game = $_POST['game'];
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$insert_str = "INSERT INTO high_scores(game, user_id, score, score_date) VALUES (?,?,?,NOW())";
	
	$statement = $mysqli->prepare($insert_str);
	$statement->bind_param("ssi", $game, $user_id, $score);
	$statement->execute();
	
	$mysqli->close();
?>