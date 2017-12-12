<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_cocoaball");

	$insert = "UPDATE highscores SET mohs1='0', mohs2='0', mohs3='0', mohs4='0', mohs5='0', mohs6='0', mohs7='0', mohs8='0', mohs9='0', mohs10='0',mohs1user='Cocoa',mohs2user='Cocoa',mohs3user='Cocoa',mohs4user='Cocoa',mohs5user='Cocoa',mohs6user='Cocoa',mohs7user='Cocoa',mohs8user='Cocoa',mohs9user='Cocoa',mohs10user='Cocoa'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>