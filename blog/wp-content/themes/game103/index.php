<?php
	
	ob_start(); 
	get_template_part( 'multi' );
	$custom_content = ob_get_contents();
	ob_get_clean();

	$custom = array(
		"route" => ["", "wp-blog"],
		"title" => "Blog",
		"content" => $custom_content
	);

	require $_SERVER['DOCUMENT_ROOT'] . '/index.php';
?>
