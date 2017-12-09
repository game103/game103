<?php

	/**
	* Script to provide a download service for items
	* This hides the download links
	*/
	
	// A standard error message
	$error_val = "Sorry, an error occured while trying to get your download link.";
	
	if(!isset($_GET['name'])) {
		echo $error_val;
		exit();
	}
	
	// Create the link
	$path = $_SERVER['DOCUMENT_ROOT'];
	$url_name = $_GET['name'];
	$name = $path . "/game103games/download/" . $url_name . ".exe";

	// Try to open the file
	if(file_exists($name)){
		
		// Log the interaction - just set the required parameters
		$_GET['type'] = 'download';
		$_GET['url_name'] = $url_name;
		ob_start();
		include( 'log_interaction.php' );
		ob_end_clean();

		//Get file type and set it as Content Type
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		header('Content-Type: ' . finfo_file($finfo, $name));
		finfo_close($finfo);

		//Use Content-Disposition: attachment to specify the filename
		header('Content-Disposition: attachment; filename='.basename($name));

		//No cache
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');

		//Define file size
		header('Content-Length: ' . filesize($name));

		ob_clean();
		flush();
		readfile($name);
		exit;
	}
	else {
		echo $error_val;
	}

?>