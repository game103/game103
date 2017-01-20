<?php
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => 'Sorry, an error occured while trying to fetch more games. Please try again later.'
	));
	
	if(!isset($_GET['limit'])) {
		echo $error_val;
		exit();
	}
	
	$limit = $_GET['limit'];
	
	// Connect to database
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");

	if (mysqli_connect_errno()) {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	
	$limit = $mysqli->real_escape_string($limit);
	
	// Get the random games
	$random_str = "SELECT SQL_NO_CACHE name, description, url_name, image_url, rating, FORMAT(plays, 0)
	FROM entries ORDER BY RAND() LIMIT ?";
	$random_statement = $mysqli->prepare($random_str);
	$random_statement->bind_param("i", $limit);
	$random_statement->execute();
	if(mysqli_stmt_error($random_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$random_statement->bind_result($name, $description, $url_name, $image_url, $rating, $plays);
	
	$games = array();
	while($random_statement->fetch()) {
		$game_object = array (
			"name" => htmlentities($name, ENT_QUOTES),
			"description" => $description,
			"url_name" => $url_name,
			"image_url" => $image_url,
			"rating" => $rating,
			"plays" => $plays,
		);
		$games[] = $game_object;
	}
	$random_statement->close();
	$mysqli->close();
	
	if(count($games) > 0) {
		$return_val = array(
			"status" => "success",
			"games" => $games
		);
		echo json_encode($return_val);
	}
	else {
		echo $error_val;
	}
?>