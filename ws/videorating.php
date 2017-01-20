<?php
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => "Sorry, an error occured while trying to process your vote. Please try again later."
	));
	
	$voted_val = json_encode(array(
		"status" => "failure",
		"message" => "Sorry, you have already voted today."
	));

	if(!isset($_GET['id']) || !isset($_GET['rating'])) {
		echo $error_val;
		exit();
	}
	
	if(!is_numeric($_GET['id']) || !is_numeric($_GET['rating'])) {
		echo $error_val;
		exit();
	}
	
	if($_GET['rating'] > 5 || $_GET['rating'] < 0) {
		echo $error_val;
		exit();
	}
	
	// Connect to database
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_videos");

	if (mysqli_connect_errno()) {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	
	$id = $mysqli->real_escape_string($_GET['id']);
	$rating = $mysqli->real_escape_string((int)$_GET['rating']);
	$ip = $_SERVER['REMOTE_ADDR'];

	// Check to make sure that the user has not voted today
	$check_str = "SELECT count(1) FROM votes WHERE entry_id = ? AND ip_address = ? and DATE(added_date) = CURDATE()";
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
	if($already_voted == 0) {
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
		echo $voted_val;
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