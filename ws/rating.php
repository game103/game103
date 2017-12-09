<?php

	error_reporting(E_ERROR);

	/**
	* Script to record a vote
	*/
	
	// Require Constants
	require_once( $_SERVER['DOCUMENT_ROOT'] . '\modules\Constants.class.php');

	// Failure for error
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => "Sorry, an error occured while trying to process your vote. Please try again later."
	));
	
	// Failure for vote already recorded
	$voted_val = array(
		"status" => "failure",
		"message" => "Sorry, you have already voted today."
	);

	// required parameters
	if(!isset($_GET['id']) || !isset($_GET['type']) || !isset($_GET['rating'])) {
		echo $error_val;
		exit();
	}
	
	// Id and rating must be numeric
	if(!is_numeric($_GET['id']) || !is_numeric($_GET['rating'])) {
		echo $error_val;
		exit();
	}
	
	// These are the valid types
	if($_GET['type'] != 'game' && $_GET['type'] != 'video') {
		echo $error_val;
		exit();
	}
	
	// Rating must be valid
	if($_GET['rating'] > 5 || $_GET['rating'] < 0) {
		echo $error_val;
		exit();
	}
	
	// Everything is same but the dbs
	if( $_GET['type'] == 'game' ) {
		$db = 'hallaby_games';
	}
	else {
		$db = 'hallaby_videos';
	}
	
	// Connect to database
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, $db);

	if (mysqli_connect_errno()) {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	
	$id = $mysqli->real_escape_string($_GET['id']);
	$rating = $mysqli->real_escape_string((int)$_GET['rating']);
	$ip = $_SERVER['REMOTE_ADDR'];

	// Check to make sure that the user has not voted today
	$check_str = "SELECT score FROM votes WHERE entry_id = ? AND ip_address = ? and DATE(added_date) = CURDATE()";
	$check_statement = $mysqli->prepare($check_str);
	$check_statement->bind_param("is", $id, $ip);
	$check_statement->execute();
	if(mysqli_stmt_error($check_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$check_statement->bind_result($already_voted);
	$check_statement->fetch();
	$check_statement->close();
	
	// If they haven't already voted, go ahead an insert the vote
	if(!$already_voted) {
		$insert_str = "INSERT INTO votes (entry_id, ip_address, score) VALUES (?, ?, ?)";
		$insert_statement = $mysqli->prepare($insert_str);
		$insert_statement->bind_param("isi", $id, $ip, $rating);
		$insert_statement->execute();
		if(mysqli_stmt_error($insert_statement) != "") {
			echo $error_val;
			$mysqli->close();
			exit();
		}
		$insert_statement->close();
	}
	// Otherwise, return an error
	else {
		$voted_val['your_rating'] = $already_voted;
		echo json_encode($voted_val);
		$mysqli->close();
		exit();
	}
	
	// Get the new total rating to return to the user
	// Don't use rating because we need num votes
	$rating_str = "SELECT sum(score), count(1) FROM votes WHERE entry_id = ?";
	$rating_statement = $mysqli->prepare($rating_str);
	$rating_statement->bind_param("i", $id);
	$rating_statement->execute();
	if(mysqli_stmt_error($rating_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$rating_statement->bind_result($summed_rating, $num_votes);
	$rating_statement->fetch();
	$rating_statement->close();
	if($num_votes > 0) {
		$total_rating = $summed_rating/$num_votes;
	}
	else {
		$total_rating = 0;
	}
	
	$mysqli->close();
	
	$return_val = array(
		"status" => 'success',
		"rating" => $total_rating,
		"votes" => $num_votes
	);
	
	echo json_encode($return_val);
?>