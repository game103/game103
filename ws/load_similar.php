<?php

	error_reporting(E_ERROR);

	/**
	* Script to load random games of a certain category
	*/
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');
	require_once( 'Service/Find/GameFind/Random.class.php');
	require_once( 'Service/Find/VideoFind/Random.class.php');
	require_once( 'Widget/Find.class.php');

	// A standard error message
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => 'Sorry, there was an error loading similar items. Please try again later.'
	));
	
	// We need a type
	if(!isset($_GET['type'])) {
		echo $error_val;
		exit();
	}
	
	// These are the valid types
	if($_GET['type'] != 'game' && $_GET['type'] != 'video') {
		echo $error_val;
		exit();
	}
	
	$id = $_GET['id'];
	$type = $_GET['type'];
	$no_box = $_GET['no_box'];
	
	if( $type == 'game' ) {
		$db = 'hallaby_games';
	}
	else {
		$db = 'hallaby_videos';
	}
	
	// Connect to database
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, $db);

	try {
		if( $id ) {
			// Get categories for the current media
			$random_category_str = "SELECT categories.url_name, entries.url_name FROM entries
				JOIN categories_entries ON entries.id = categories_entries.entry_id
				JOIN categories ON categories.id = categories_entries.category_id
				WHERE entries.id = ? and categories.url_name != 'game103' 
				and categories.url_name != 'distributable'
				LIMIT 2";
			$random_category_statement = $mysqli->prepare($random_category_str);
			$random_category_statement->bind_param("i", $id);
			$random_category_statement->execute();
			if(mysqli_stmt_error($random_category_statement) != "") {
				$mysqli->close();
				throw new Exception($error_val);
			}
			$random_category_statement->bind_result($category, $url_name);
			$category_names = array();
			while($random_category_statement->fetch()) {
				$category_names[] = $category;
			}
			$random_category_statement->close();
			
			// Pick the category to use
			$category = $category_names[1] ? $category_names[rand(0,1)] : $category_names[0];
		}
		else {
			$category = 'all';
		}
		
		// Generate the response
		if( $type == 'game' ) {
			$service = new \Service\Find\GameFind\Random( $category, $id, $mysqli );
		}
		else {
			$service = new \Service\Find\VideoFind\Random( $category, $id, $mysqli );
		}
		$properties = $service->generate();
		
		$new_items = array();
		// Limit to six, no self
		for( $i=0; $i<sizeof( $properties['items'] ); $i++ ) {
			if( $properties['items'][$i]['url_name'] != $url_name ) {
				array_push( $new_items, $properties['items'][$i] );
			}
		}
		$properties['items'] = $new_items;
		
		$properties['header'] = "Similar " . ucfirst($type) . "s";
		$properties['footer'] = "";
		if( $no_box ) {
			$properties['no_box'] = true;
		}
		$widget = new \Widget\Find( $properties );
		$widget->generate();
		
		print json_encode( 
			array(
				'status' => $properties['status'],
				'content' => \Constants::sanitize_output( $widget->get_HTML() )
			)
		);
		
	}
	catch(Exception $e) {
		print $e->getMessage();
	}
?>