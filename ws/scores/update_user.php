<?php
	error_reporting(0);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');

	$user_id = $_POST['id'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if( strlen( $username ) < 5 or strlen( $password ) < 5 or strlen( $username ) > 15 or strlen( $password ) > 15 ) {
		echo json_encode ( array(
			'status'	=> 'failure',
			'message'	=> 'Usernames and passwords must be between 5 and 15 characters'
		) );
		die;
	}
	
	$password = md5($password);
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$insert_str = "UPDATE users SET username = ?, password = ? WHERE id = ?";
	
	$statement = $mysqli->prepare($insert_str);
	$statement->bind_param("sss", $username, $password, $user_id);
	$success = $statement->execute();
	
	if( !$success ) {
		echo json_encode ( array(
			'status'	=> 'failure',
			'message'	=> 'That username already exists'
		) );
	}
	else {
		echo json_encode ( array(
			'status'   => 'success'
		) );
	}
	
	$mysqli->close();
?>