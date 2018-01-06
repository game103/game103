<?php
	error_reporting(0);
	
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$select_str = "SELECT id FROM users where username = ? AND password = ? AND password IS NOT NULL";
		
	$statement = $mysqli->prepare($select_str);
	$statement->bind_param("ss", $username, $password);
	$statement->execute();
	$statement->bind_result($id);
	$statement->fetch();
	
	if( !$id ) {
		echo json_encode ( array(
			'status'	=> 'failure',
			'message'	=> 'Invalid credentials'
		) );
	}
	else {
		echo json_encode ( array(
			'status'   => 'success',
			'id'	   => $id,
		) );
	}
	
	$mysqli->close();
?>