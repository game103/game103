<?php

	/**
	* Script to add a view for an item
	*/

	// Require Constants
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/modules/Constants.class.php');

	// A standard error message
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => "Sorry, an error occured while trying to submit your view."
	));

	// We need a URL name and a type
	if(!isset($_GET['url_name']) || !isset($_GET['type'])) {
		echo $error_val;
		exit();
	}
	
	// These are the valid types
	if($_GET['type'] != 'app' && $_GET['type'] != 'resource' && 
		$_GET['type'] != 'game' && $_GET['type'] != 'video' && $_GET['type'] != 'download' ) {
		echo $error_val;
		exit();
	}
	
	// Connect to database
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_games");

	if (mysqli_connect_errno()) {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	
	$url_name = $mysqli->real_escape_string($_GET['url_name']);
	$type = $mysqli->real_escape_string($_GET['type']);
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$table = '';
	$url_field = '';
	$url_field2 = '';
	if($type == 'download') {
		$table = 'downloads';
		$url_field = 'url_name';
	}
	else if($type == 'app') {
		$table = 'apps';
		$url_field = 'store_url_android';
		$url_field2 = 'store_url_apple';
	}
	else if($type == 'resource') {
		$table = 'hallaby_resources.entries';
		$url_field = 'url';
	}
	else if($type == 'game') {
		$table = 'entries';
		$url_field = 'url_name';
	}
	else if($type == 'video') {
		$table = 'hallaby_videos.entries';
		$url_field = 'url_name';
	}
	// Get the game id
	$fetch_id_str = "SELECT id FROM $table WHERE $url_field = ?";
	if($url_field2) {
		$fetch_id_str .= " or $url_field2 = ?";
	}
	$fetch_id_statement = $mysqli->prepare($fetch_id_str);
	if($url_field2) {
		$fetch_id_statement->bind_param("ss", $url_name, $url_name);
	}
	else {
		$fetch_id_statement->bind_param("s", $url_name);
	}
	$fetch_id_statement->execute();
	if(mysqli_stmt_error($fetch_id_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$fetch_id_statement->bind_result($id);
	$fetch_id_statement->fetch();
	$fetch_id_statement->close();
	if(!$id) {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	
	// Insert the view
	$foreign_id = '';
	if($type == 'download') {
		$table = 'saves';
		$foreign_id = 'download_id';
	}
	else if($type == 'app') {
		$table = 'visits';
		$foreign_id = 'app_id';
	}
	else if($type == 'resource') {
		$table = 'hallaby_resources.visits';
		$foreign_id = 'entry_id';
	}
	else if($type == 'game') {
		$table = 'plays';
		$foreign_id = 'entry_id';
	}
	else if($type == 'video') {
		$table = 'hallaby_videos.views';
		$foreign_id = 'entry_id';
	}
	$insert_str = "INSERT INTO $table ($foreign_id, ip_address) VALUES (?, ?)";
	$insert_statement = $mysqli->prepare($insert_str);
	$insert_statement->bind_param("is", $id, $ip);
	$insert_statement->execute();
	$insert_statement->close();
	
	$mysqli->close();
	
	$return_val = array(
		"status" => 'success'
	);
	
	echo json_encode($return_val);
?>