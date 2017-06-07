<?php
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => "Sorry, an error occured while trying to submit your view."
	));

	if(!isset($_GET['url_name']) || !isset($_GET['type'])) {
		echo $error_val;
		exit();
	}
	
	if($_GET['type'] != 'apps' && $_GET['type'] != 'resources') {
		echo $error_val;
		exit();
	}
	
	// Connect to database
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");

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
	if($type == 'downloads') {
		$table = 'downloads';
		$url_field = 'url_name';
	}
	else if($type == 'apps') {
		$table = 'apps';
		$url_field = 'store_url_android';
		$url_field2 = 'store_url_apple';
	}
	else if($type == 'resources') {
		$table = 'hallaby_resources.entries';
		$url_field = 'url';
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
	if($type == 'downloads') {
		$table = 'saves';
		$foreign_id = 'download_id';
	}
	else if($type == 'apps') {
		$table = 'visits';
		$foreign_id = 'app_id';
	}
	else if($type == 'resources') {
		$table = 'hallaby_resources.visits';
		$foreign_id = 'entry_id';
	}
	$insert_str = "INSERT INTO $table ($foreign_id, ip_address) VALUES (?, ?)";
	$insert_statement = $mysqli->prepare($insert_str);
	$insert_statement->bind_param("is", $id, $ip);
	$insert_statement->execute();
	//if(mysqli_stmt_error($insert_statement) != "") {
	//	echo $error_val;
	//	$mysqli->close();
	//	exit();
	//}
	$insert_statement->close();
	
	$mysqli->close();
	
	$return_val = array(
		"status" => 'success'
	);
	
	echo json_encode($return_val);
?>