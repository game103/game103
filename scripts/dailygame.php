<?php
	/**
	* Script to post daily games to Twitter and Facebook
	*/
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	require_once( 'Constants.class.php');

	// Connect to database
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_games");
	if (mysqli_connect_errno()) {
		$mysqli->close();
		throw new Exception($mysql_message);
	}
		
	$min_days = 20;
	$use_game_103_game = rand(1, 10);
	if($use_game_103_game > 8) {
		$use_game_103_game = true;
	}
	else {
		$use_game_103_game = false;
	}
	
	if($use_game_103_game) {
		$select_str = "SELECT all_games.id, all_games.name, all_games.url_name, all_games.daily_date, all_games.description, all_games.image_url FROM (
		SELECT entries.id, entries.name, entries.url_name, MAX(daily_game.added_date) AS daily_date, entries.description, entries.image_url
		FROM entries join game103 on game103.entry_id = entries.id left outer join daily_game on entries.id = daily_game.entry_id
		GROUP BY entries.name
		) as all_games
		WHERE daily_date <= DATE_SUB(now(), INTERVAL $min_days DAY) or daily_date is null ORDER BY RAND() LIMIT 1";
	}
	else {
		// Get the games that have not been the daily game since at least 20 days ago
		// (So, 19 games must have been daily games before a game can be the daily game again)
		// This was originally designed so that all the Game 103 games could be cycled through without any empty time
		// as there were 20 game 103 games at the time of this scripts creation 
		$select_str = "SELECT all_games.id, all_games.name, all_games.url_name, all_games.daily_date, all_games.description, all_games.image_url FROM (
		SELECT entries.id, entries.name, entries.url_name, MAX(daily_game.added_date) AS daily_date, entries.description, entries.image_url
		FROM entries left outer join daily_game on entries.id = daily_game.entry_id
		GROUP BY entries.name
		) as all_games
		WHERE daily_date <= DATE_SUB(now(), INTERVAL $min_days DAY) or daily_date is null ORDER BY RAND() LIMIT 1";
	}
	$select_statement = $mysqli->prepare($select_str);
	$select_statement->execute();
	if(mysqli_stmt_error($select_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$select_statement->bind_result($id, $name, $url_name, $prev_date, $description, $image_url);
	$select_statement->fetch();
	$select_statement->close();
	
	// Update the daily games list
	$daily_insert_str = "INSERT INTO daily_game(entry_id) VALUES (?)";
	$daily_insert_statement = $mysqli->prepare($daily_insert_str);
	$daily_insert_statement->bind_param("i", $id);
	$daily_insert_statement->execute();
	if(mysqli_stmt_error($daily_insert_statement) != "") {
		$daily_insert_statement->close();
		$mysqli->close();
		throw new Exception($mysql_message);
	}
	$daily_insert_statement->close();
	
	// require Facebook PHP SDK
	// see: https://developers.facebook.com/docs/php/gettingstarted/
	require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
	 
	// initialize Facebook class using your own Facebook App credentials
	// see: https://developers.facebook.com/docs/php/gettingstarted/#install
	$config = array();
	$config['app_id'] = '***REMOVED***';
	$config['app_secret'] = '***REMOVED***';
	$config['fileUpload'] = false; // optional
	 
	$fb = new Facebook\Facebook($config);
	 
	// define your POST parameters (replace with your own values)
	$params = array(
	  "access_token" => "***REMOVED***w", // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
	  "message" => "Check out today's daily game, $name!",
	  "link" => "https://game103.net/game/$url_name",
	  "picture" => "https://game103.net$image_url",
	  "name" => "$name",
	  //"caption" => "$description",
	  "description" => "$description"
	);
	 
	// post to Facebook
	// see: https://developers.facebook.com/docs/reference/php/facebook-api/
	try {
	  $ret = $fb->post('/game103/feed', $params);
	  echo 'Successfully posted to Facebook';
	} catch(Exception $e) {
	  echo $e->getMessage();
	}
	
	echo "$name ... $id ... $url_name ... $use_game_103_game";
	
	// require codebird
	require_once __DIR__ . '/codebird/codebird.php';
	 
	\Codebird\Codebird::setConsumerKey("***REMOVED***", "***REMOVED***");
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken("***REMOVED***", "***REMOVED***");
	 
	$params = array(
	  'status' => "Check out today's daily game, $name, at https://game103.net/game/$url_name!"
	);
	$reply = $cb->statuses_update($params);
?>