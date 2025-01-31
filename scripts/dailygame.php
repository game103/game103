<?php
	/**
	* Script to post daily games to Twitter and Facebook
	*/
	
	set_include_path("/var/www/game103/modules");
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

	$body = '{
	  	"status": "Check out today\'s daily game, '.$name.', at https://game103.net/game/'.$url_name.'!"
	}';
	$options = [
		'http' => [
			'header' => ["Content-type: application/json","Authorization: Bearer " . Constants::PATCHES_TOKEN],
			'method' => 'POST',
			'content' => $body
		],
	];
	$context = stream_context_create( $options );
	$result = file_get_contents( "https://patches.social/api/v1/statuses", false, $context );
	
	// require Facebook PHP SDK
	// see: https://developers.facebook.com/docs/php/gettingstarted/
	// require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
	 
	// initialize Facebook class using your own Facebook App credentials
	// see: https://developers.facebook.com/docs/php/gettingstarted/#install
	// $config = array();
	// $config['app_id'] = Constants::FB_APP_ID;
	// $config['app_secret'] = Constants::FB_APP_SECRET;
	// $config['fileUpload'] = false; // optional
	// $config['default_graph_version'] = "v6.0";
	 
	// $fb = new Facebook\Facebook($config);
	 
	// define your POST parameters (replace with your own values)
	/*$params = array(
	  "access_token" => Constants::FB_TOKEN, // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
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
	 
	\Codebird\Codebird::setConsumerKey(Constants::TWITTER_CONSUMER_KEY, Constants::TWITTER_CONSUMER_KEY_SECRET);
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken(Constants::TWITTER_TOKEN, Constants::TWITTER_TOKEN_SECRET);
	 
	$params = array(
	  'status' => "Check out today's daily game, $name, at https://game103.net/game/$url_name!"
	);
	// Post to Twitter
	$reply = $cb->statuses_update($params);

	// Post to Instagram
	$image_path = dirname("/var/www/game103$image_url");
	$image_file = basename($image_url);
	exec( "convert $image_path/bordered/$image_file /var/www/game103/temp.jpeg"); // Always convert to a jpeg
	exec( "sudo -u james node /var/www/game103/scripts/instagram-poster/index.js --username " . Constants::INSTAGRAM_USER . " --password " . Constants::INSTAGRAM_PASSWORD . " --image /var/www/game103/temp.jpeg --caption \"Check out today's daily game, $name, at https://game103.net/game/$url_name!\" --executablePath /usr/bin/chromium-browser" );
	exec( "rm /var/www/game103/temp.jpeg" );*/
?>
