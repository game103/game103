<?php
	error_reporting(0);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');

	$user_id = $_POST['id'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	
	if( strlen( $username ) < 5 or strlen( $password ) < 5 or strlen( $username ) > 15 or strlen( $password ) > 15 ) {
		echo json_encode ( array(
			'status'	=> 'failure',
			'message'	=> 'Usernames and passwords must be between 5 and 15 characters'
		) );
		die;
	}
	if( $email ) {
		if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			echo json_encode ( array(
				'status'	=> 'failure',
				'message'	=> 'The email provided is invalid'
			) );
			die;
		}
		if( strlen($email) > 300 ) {
			echo json_encode ( array(
				'status'	=> 'failure',
				'message'	=> 'Emails must be less than 500 characters'
			) );
			die;
		}
	}
	
	$password = md5($password);
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$email_section = "";
	if( $email ) {
		$email_section = ", email = ?";
	}

	$update_str = "UPDATE users SET username = ?, password = ?$email_section WHERE id = ?";
	
	$statement = $mysqli->prepare($update_str);
	if( $email ) {
		$statement->bind_param("ssss", $username, $password, $email, $user_id);
	}
	else {
		$statement->bind_param("sss", $username, $password, $user_id);
	}
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