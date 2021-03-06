<?php
	error_reporting(0);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');

	$range = $_GET['range'];
	$game = $_GET['game'];
	$page = $_GET['page'] ? $_GET['page'] : 1;
	$items_per_page = 25;
	
	$where_clause = "WHERE game = ?";
	if($range == "day") {
		$where_clause .= " AND DATE(score_date) = CURDATE()";
	}
	else if($range == "week") {
		$where_clause .= " AND DATE(score_date) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()";		
	}
	else if($range == "month") {
		$where_clause .= " AND DATE(score_date) BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";	
	}
	else if($range == "year") {
		$where_clause .= " AND DATE(score_date) BETWEEN CURDATE() - INTERVAL 365 DAY AND CURDATE()";	
	}
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$select_str = "SELECT users.username, max(high_scores.score) as max, high_scores.score_date 
	FROM high_scores join users on high_scores.user_id = users.id " 
	. $where_clause . " GROUP BY users.username ORDER BY max DESC, score_date DESC LIMIT $items_per_page OFFSET ?";
	$statement = $mysqli->prepare($select_str);
	$offset = ($page-1) * $items_per_page;
	$statement->bind_param("si", $game, $offset);
	$statement->execute();
	$statement->bind_result($username, $score, $score_date);
	
	$results = array();
	while( $statement->fetch() ) {
		$result = array();
		$result['username'] = $username;
		$result['score'] = $score;
		$result['score_date'] = $score_date;
		array_push( $results, $result );
	}
	
	echo json_encode( array( 'results' => $results ) );
	
	$mysqli->close();
?>