<?php

	error_reporting(E_ERROR);

	/**
	* Script to load others also played for a game
    */
    
    // Have caching for this
    $cached_file = str_replace( "?", "-", str_replace("/", "-", $_SERVER["REQUEST_URI"]) );
    $cached_file = $_SERVER['DOCUMENT_ROOT'] . "/cache-user/" . $cached_file . ".html";
    if( !$_GET['no_cache'] ) {
        // If the file exists and is within the past hour
		if( file_exists( $cached_file ) && ( time() - filemtime( $cached_file ) < 3600 ) ) {
			print file_get_contents( $cached_file );
			exit;
		}
    }
    // End caching
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');
	require_once( 'Service/Find/GameFind/OthersAlsoPlayed.class.php');
	require_once( 'Widget/Find.class.php');

	// A standard error message
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => 'Sorry, there was an error loading similar items. Please try again later.'
	));
	
	// We need a numeric id
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		echo $error_val;
		exit();
	}
	
	$id = $_GET['id'];
    $no_box = $_GET['no_box'];
    
	// Connect to database
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_games");

	try {
		$service = new \Service\Find\GameFind\OthersAlsoPlayed( $id, $mysqli );

		$properties = $service->generate();
		
		$properties['header'] = "Others Also Played";
		$properties['footer'] = "";
		if( $no_box ) {
			$properties['no_box'] = true;
		}
		$widget = new \Widget\Find( $properties );
		$widget->generate();
		
		$response = json_encode( 
			array(
				'status' => $properties['status'],
				'content' => \Constants::sanitize_output( $widget->get_HTML() )
			)
        );

        print $response;
        
        // Cache the file to disk
        $fp = fopen( $cached_file, 'w' );
        fwrite( $fp, $response );
        fclose( $fp );
	}
	catch(Exception $e) {
		print $e->getMessage();
	}
?>