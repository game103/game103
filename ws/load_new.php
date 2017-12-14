<?php

	error_reporting(E_ERROR);

	/**
	* Script to load random games of a certain category
	*/
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');
	require_once( 'Service/Find.class.php');
	require_once( 'Widget/Find/Dated.class.php');

	// A standard error message
	$error_val = json_encode(array(
		"status" => "failure",
		"message" => 'Sorry, there was an error loading new content. Please try again later.'
	));
	
	$page = $_GET['page'];
	$no_box = $_GET['no_box'];
	
	// Connect to database
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);

	// Generate the response
	$service = new \Service\Find( "", "date", "", $page, \Constants::NEW_CONTENT_ITEMS_PER_PAGE, $mysqli );
	$properties = $service->generate();
	$properties['no_box'] = $no_box;
	$properties['header'] = "New Content";
	$properties['footer'] = "<span id='load-earlier-content' class='box-content-footer-link'>Load earlier content</span>";
	
	$widget = new \Widget\Find\Dated( $properties );
	$widget->generate();
	
	print json_encode( 
		array(
			'status' => $properties['status'],
			'content' => \Constants::sanitize_output( $widget->get_HTML() )
		)
	);
?>