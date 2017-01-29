<?php

	$error_val = 'Sorry, an error occurred while trying to download your file.';
	if(!isset($_GET['name'])) {
		echo $error_val;
		exit();
	}
		
	$path = $_SERVER['DOCUMENT_ROOT'];
	$url_name = $_GET['name'];
	$name = $path . "/game103games/download/" . $url_name . ".exe";

	if(file_exists($name)){
		
		// Connect to database
		$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
		if (mysqli_connect_errno()) {
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		
		// Get the id
		// String to query the database with
		$str = "SELECT id FROM downloads WHERE url_name = ?";
		// Prepare the statement
		$statement = $mysqli->prepare($str);
		// Bind parameters
		$url_name = $mysqli->real_escape_string($url_name);
		$statement->bind_param("s", $url_name);
		// Execute the statement
		$statement->execute();
		// Check for errors {
		if(mysqli_stmt_error($statement) != "") {
			$statement->close();
			$mysqli->close();
			echo $error_val;
			exit();
		}
		// Get the one result
		$statement->bind_result($id);
		// Fetch the result
		$statement->fetch();
		// Close the statement
		$statement->close();
		if(!isset($id)) {
			echo $error_val;
			exit();
		}
		
		// Update the downloads counter
		$ip = $_SERVER['REMOTE_ADDR'];
		$plays_insert_str = "INSERT INTO saves(download_id, ip_address) VALUES (?, ?)";
		$plays_insert_statement = $mysqli->prepare($plays_insert_str);
		$plays_insert_statement->bind_param("is", $id, $ip);
		$plays_insert_statement->execute();
		if(mysqli_stmt_error($plays_insert_statement) != "") {
			$plays_insert_statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		$plays_insert_statement->close();

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