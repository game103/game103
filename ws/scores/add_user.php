<?php
	error_reporting(0);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$insert_str = "INSERT INTO users(id, username) VALUES (?,?)";
	
	// Notice how the actual id is different to the username id
	// to prevent shady stuff :) with resetting
	$id = bin2hex(random_bytes(7));
	$username = 'player_' . bin2hex(random_bytes(7));
	
	$statement = $mysqli->prepare($insert_str);
	$statement->bind_param("ss", $id, $username);
	$statement->execute();
	
	echo json_encode ( array(
		'id'	   => $id,
		'username' => $username
	) );
	
	$mysqli->close();
?>