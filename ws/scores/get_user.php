<?php
	error_reporting(0);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');

	$id = $_GET['id'];
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$select_str = "SELECT users.username FROM users WHERE id = ?" ;
	$statement = $mysqli->prepare($select_str);
	$statement->bind_param("s", $id);
	$statement->execute();
	$statement->bind_result($username);
	
	$results = array();
	$statement->fetch();
	$results['username'] = $username;
	$results['id'] = $id;
	
	// Username will be null if not found
	echo json_encode( $results );
	
	$mysqli->close();
?>