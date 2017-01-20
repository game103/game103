<?php
	if(!isset($routed)) {
		throw new Exception($direct_access_message);
	}
	
	$display_page = "";
	
	// Connect to database
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
	if (mysqli_connect_errno()) {
		$mysqli->close();
		throw new Exception($mysql_message);
	}
	
	// Create the SQL Statement
	$select_str = "SELECT * FROM(
					SELECT characters.name, characters.ipa_name, characters.description, characters.image_url, game_name, game_url_name, type
					FROM characters 
					JOIN characters_entries ON characters_entries.character_id = characters.id
					JOIN (SELECT entries.id as inner_id, entries.name as game_name, entries.url_name as game_url_name, 0 as type FROM entries) as entries_games
					ON characters_entries.entry_id = inner_id
					UNION ALL
					SELECT characters.name, characters.ipa_name, characters.description, characters.image_url, game_name, game_url_name, type
					FROM characters 
					JOIN apps_characters ON apps_characters.character_id = characters.id
					JOIN (SELECT apps.id as inner_id, apps.name as game_name, apps.url_name as game_url_name, 1 as type FROM apps) as apps_games
					ON apps_characters.app_id = inner_id
					UNION ALL
					SELECT characters.name, characters.ipa_name, characters.description, characters.image_url, game_name, game_url_name, type
					FROM characters
					JOIN characters_downloads ON characters_downloads.character_id = characters.id
					JOIN (SELECT downloads.id as inner_id, downloads.name as game_name, downloads.url_name as game_url_name, 2 as type FROM downloads) as downloads_games
					ON characters_downloads.download_id = inner_id
					) AS characters_query
					ORDER BY name ASC, game_name ASC";
			
	$select_statement = $mysqli->prepare($select_str);
	
	// Exexecute the SQL Statement
	$select_statement->execute();
	if(mysqli_stmt_error($select_statement) != "") {
		throw new Exception($mysql_message);
		$mysqli->close();
		exit();
	}
	$select_statement->bind_result($name, $ipa_name, $description, $image_url, $game_name, $game_url_name, $game_type);
	
	// Create the characters
	$prev_name = "";
	$games_list = "";
	while($select_statement->fetch()) {
		// Escape the name
		$name = htmlentities($name, ENT_QUOTES);
		// Check to see if this is a whole new character
		if($name != $prev_name) {
			// Add the previous entry
			if($prev_name != "") {
				$display_page .= createCode($cur_name, $cur_ipa_name, $cur_description, $cur_image_url, $games_list);
			}
			
			// Set the current game to the new games info (note: this will be added to the display page a new game is detected)
			$games_list = "";
			$cur_name = $name;
			$cur_ipa_name = $ipa_name;
			$cur_description = $description;
			$cur_image_url = $image_url;
			
			$prev_name = $name;
		}
		// Add a comma if this is the second game
		if($games_list != "") {
			$games_list .= ", ";
		}
		// If it is a game
		if($game_type == 0) {
			$games_list .= "<a class='character-game-link' href='/game/$game_url_name'>$game_name</a>";
		}
		// If it is an app
		else if($game_type == 1) {
			$games_list .= "<a class='character-game-link' href='/app/$game_url_name'>$game_name</a>";
		}
		// If it is a download
		else {
			$games_list .= "<a class='character-game-link' href='/download/$game_url_name'>$game_name</a>";
		}
	}
	
	$select_statement->close();
		
	$mysqli->close();
	
	// Make sure that the last entry is added
	$display_title = "Characters";
	$display_page .= createCode($cur_name, $cur_ipa_name, $cur_description, $cur_image_url, $games_list);
	$display_page = "
	<div class='box-content'>
		<div class='box-content-title'>$display_title</div>
		<div class='box-content-container'>
			<div class='character-entries'>" . $display_page . "</div>
		</div>
	</div>";
	$display_description = "The tales behind the various characters that have been in Game 103 games over the years.";
	$display_javascript = "";
	
	// Function to create the HTML for a character
	function createCode($c_name, $c_ipa_name, $c_description, $c_image_url, $c_games_list) {
		$no_space_name = str_replace(" ", "+", $c_name);
		$return_string = <<<HTML
		<div class='character-entry'>
			<a name="$no_space_name"></a>
			<div class='character-image-container'>
				<div class='character-image-size-boundaries'>
					<img class='character-image' src='$c_image_url'/>
				</div>
			</div>
			<div class='character-text-container'>
				<div class='character-name'>$c_name</div>
				<div class='character-ipa-name'>[$c_ipa_name]</div>
				<div class='character-appears'>Appears in: $c_games_list</div>
				<div class='character-description'>$c_description</div>
			</div>
		</div>
HTML;
		return $return_string;
	}
?>