<?php
	
	ob_start(); 
	get_template_part( 'multi', null, array( "h" => "h2") );
	comments_template();
	$custom_content = ob_get_contents();
	ob_get_clean();

	$custom = array(
		"route" => ["", "wp-blog"],
		"title" => get_the_title(),
		"content" => $custom_content
	);

	require $_SERVER['DOCUMENT_ROOT'] . '/index.php';
?>
