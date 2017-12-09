<?php

	error_reporting(E_ERROR);

	/**
	* Script to load site search
	*/
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php' );
	require_once( 'Service\Find.class.php' );
	
	$search = $_GET['search'];
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	
	$service = new \Service\Find( $search, "popularity", "", 1, 8, $mysqli );
	
	print json_encode( $service->generate() );
?>