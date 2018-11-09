<?php

	error_reporting(E_ERROR);

	/**
	 * Script to receieve a web hook from GitHub
	 */

	set_include_path( $_SERVER['DOCUMENT_ROOT'] . "/" . "modules" );

	// Require modules
	require_once( 'Constants.class.php');

	// Receive POST data
	$post_data = file_get_contents('php://input');

	// HMAC the data using the secret token
	// We'll make sure this matches the data GitHub sent us
	$signature = hash_hmac('sha1', $post_data, Constants::GITHUB_WEBHOOK_TOKEN);

	// If the signature is correct
	if( $_SERVER['HTTP_X_HUB_SIGNATURE'] == 'sha1=' . $signature ) {
		system("sudo /var/www/game103/scripts/minor_reboot.sh");
	}
	else {
		http_response_code( 403 );
		die("Forbidden\n");
	}
?>
