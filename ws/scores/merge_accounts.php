<?php
	error_reporting(0);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');

    $user_id = $_POST['id'];
    // We only need the credentials for the merge account, since this is all that matters
    // security-wise - the person whose account is going away is willing to give it up
	$merge_username = $_POST['username'];
	$merge_password = $_POST['password'];

	$merge_password = md5($merge_password);
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
    
    // Make sure the credentials for the merge account is correct
    $select_str = "SELECT id FROM users where username = ? AND password = ? AND password IS NOT NULL";	
	$statement = $mysqli->prepare($select_str);
	$statement->bind_param("ss", $merge_username, $merge_password);
	$statement->execute();
	$statement->bind_result($merge_id);
    $statement->fetch();
    $statement->close();
    
    if( !$merge_id ) {
		echo json_encode ( array(
			'status'	=> 'failure',
			'message'	=> 'Invalid credentials'
		) );
    }
    else {

        // Make sure the merge account id is valid!

        $select_str = "SELECT count(*) FROM users WHERE id = ?";	
        $statement = $mysqli->prepare($select_str);
        $statement->bind_param("s", $user_id);
        $statement->execute();
        $statement->bind_result($result);
        $statement->fetch();
        $statement->close();

        if( $result ) {

            // Perform the update
            $update_str = "UPDATE high_scores SET user_id = ? WHERE user_id = ?";
	
            $statement = $mysqli->prepare($update_str);
            $statement->bind_param("ss", $user_id, $merge_id);
            $success = $statement->execute();
            $statement->close();
            
            if( !$success ) {
                echo json_encode ( array(
                    'status'	=> 'failure',
                    'message'	=> 'An error ocurred updating high scores, but both accounts are still intact'
                ) );
            }
            else {
                echo json_encode ( array(
                    'status'   => 'success'
                ) );

                // Delete the old account
                $delete_str = "DELETE from users where id = ?";

                $statement = $mysqli->prepare($delete_str);
                $statement->bind_param("s", $merge_id);
                $statement->execute();
                $statement->close();
            }
        }
        else {
            echo json_encode ( array(
                'status'	=> 'failure',
                'message'	=> 'Invalid ID'
            ) );
        }
    }
	
	$mysqli->close();
?>