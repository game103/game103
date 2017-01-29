<?php
	try {
		$game_does_not_exist_message = "The game specified does not exist.";
		
		if(!isset($routed)) {
			throw new Exception($direct_access_message);
		}
		
		// Connect to database
		$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
		if (mysqli_connect_errno()) {
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		
		//////////
		// GAME //
		//////////
		// Get the required url params
		$url_name = $mysqli->real_escape_string($url_name);
		// String to query the database with
		$str = "SELECT id, name, url, description, screenshot_url, FORMAT(saves, 0)
		FROM downloads WHERE url_name = ? LIMIT 1";
		// Prepare the statement
		$statement = $mysqli->prepare($str);
		// Bind parameters
		$statement->bind_param("s", $url_name);
		// Execute the statement
		$statement->execute();
		// Check for errors {
		if(mysqli_stmt_error($statement) != "") {
			$statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		// Get the one result
		$statement->bind_result($id, $name, $url, $description, $screenshot_url, $saves);
		// Fetch the result
		$statement->fetch();
		// Close the statement
		$statement->close();
		if(!isset($id)) {
			throw new Exception($game_does_not_exist_message);
		}
		if($saves == 1) {
			$saves_str = 'download';
		}
		else {
			$saves_str = 'downloads';
		}

		//////////////
		// CONTROLS //
		//////////////
		// Get the controls
		$controls_str = "SELECT controls.key, actions.name FROM downloads
			join actions_controls_downloads on actions_controls_downloads.download_id = downloads.id
			join actions_controls on actions_controls_downloads.action_control_id = actions_controls.id
			join actions on actions_controls.action_id = actions.id
			join controls on actions_controls.control_id = controls.id
		WHERE downloads.id = ?
		ORDER BY controls.key;";
		$controls_statement = $mysqli->prepare($controls_str);
		$controls_statement->bind_param("i", $id);
		$controls_statement->execute();
		if(mysqli_stmt_error($controls_statement) != "") {
			$controls_statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		$controls_statement->bind_result($key, $action);
		$keys_actions_arr = array();
		while($controls_statement->fetch()) {
			$keys_arr = array_keys($keys_actions_arr);
			// For all the current keys in the array
			for($i=0;$i<count($keys_arr);$i++) {
				$cur_key = $keys_arr[$i];
				$cur_actions_arr = $keys_actions_arr[$cur_key];
				// Check if any of the keys already map to the action
				if(in_array($action, $cur_actions_arr)) {
					// If so, append the key to the found key (cur_key)
					$key = $cur_key . "/$key";
					// If the only entry in the found keys actions array
					// was the matched action, get rid of that whole
					// key from the list of keys
					if(count($keys_actions_arr[$cur_key]) == 1) {
						unset($keys_actions_arr[$cur_key]);
					}
					// Otherwise, just remove the action from that keys list
					else {
						$action_index = array_search($action, $cur_actions_arr);
						unset($keys_actions_arr[$cur_key][$action_index]);
					}
					
					break;
				}
			}
			
			// Add the key to the keys actions array
			$keys_actions_arr[$key][] = $action;
		}
		
		// Now that the array is set, create the html
		$keys_arr = array_keys($keys_actions_arr);
		sort($keys_arr);
		$controls = "<ul>";
		$controls_count = 0;
		for($i=0;$i<count($keys_arr);$i++) {
			$key = $keys_arr[$i];
			sort($keys_actions_arr[$key]);
			$action = join('/', $keys_actions_arr[$key]);
			$controls .= "<li>";
			$controls .= "<span class='side-box-alt-text'>" . $key . ":</span> <span class='side-box-text'>" . $action . "</span>";	
			$controls .= "</li>";
			$controls_count ++;
		}
		
		$controls_statement->close();
		$controls .= "</ul>";
		
		////////////////
		// CHARACTERS //
		////////////////
		// Get the controls
		$characters_str = "SELECT characters.name FROM characters_downloads
			JOIN characters on characters_downloads.character_id = characters.id
		WHERE characters_downloads.download_id = ?
		ORDER BY characters.name";
		$characters_statement = $mysqli->prepare($characters_str);
		$characters_statement->bind_param("i", $id);
		$characters_statement->execute();
		if(mysqli_stmt_error($characters_statement) != "") {
			$characters_statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		$characters_statement->bind_result( $character_name);
		$characters_display = "";
		$characters_count = 0;
		while($characters_statement->fetch()) {
			if($characters_count == 0) {
				$characters_display .= "<br>Characters: ";
			}
			else {
				$characters_display .= ", ";
			}
			$no_space_name = str_replace(" ", "+", $character_name);
			$characters_display .= "<a class='side-box-link' href='/characters#$no_space_name'>$character_name</a>";
			$characters_count++;
		}
		
		$characters_statement->close();

		////////////
		// VIDEOS //
		////////////
		$videos_str = "SELECT hallaby_videos.entries.name, hallaby_videos.entries.url_name FROM downloads_videos
			JOIN hallaby_videos.entries on downloads_videos.video_id = hallaby_videos.entries.id
		WHERE downloads_videos.download_id = ?
		ORDER BY hallaby_videos.entries.name";
		$videos_statement = $mysqli->prepare($videos_str);
		$videos_statement->bind_param("i", $id);
		$videos_statement->execute();
		if(mysqli_stmt_error($videos_statement) != "") {
			$characters_statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		$videos_statement->bind_result($video_name, $video_url_name);
		$videos_display = "";
		$videos_count = 0;
		while($videos_statement->fetch()) {
			if($videos_count == 0) {
				if($characters_display != "") {
					$videos_display .= "<br>";
				}
				$videos_display .= "Videos: ";
			}
			else {
				$videos_display .= ", ";
			}
			$videos_display .= "<a class='side-box-link' href='/video/$video_url_name'>$video_name</a>";
			$videos_count++;
		}
		
		$videos_statement->close();
		
		$mysqli->close();

		$display_description = "$description Play $name on Game 103!";
		$display_title = $name;
		$display_javascript = "
		// Open URL
		function openURL(event, url) {
			window.location.href = url;
		}
		";
		
		$display_page = 
		"
			<div class='box-content box-content-tight'>
				<div class='box-content-title'>$display_title</div>
				<div class='box-content-container'>
					<div class='download-body'>
						<div>
							<p>$name is a downloadable game. Once downloaded, the game stores nothing but high scores and save files on your computer. 
							The game has no viruses or other files attached to it. Enjoy!</p>
							<p>By clicking download, you agree not to upload $name anywhere on the internet,
							not to sell copies of the game, and not to claim it as your own.</p>
							<button onclick='openURL(event, \"$url\")'>Download</button>
						</div>
						<img src='$screenshot_url' alt='$name screenshot slideshow' />
					</div>
				</div>
			</div>
					
			<div class='side-boxes responsive'>
				<div class='left-side-box side-box responsive'>
					<!--Controls-->
					<div id='controls' class='side-box-item responsive'><span class='side-box-title'>Controls</span>$controls</div>
				</div>
				
				<div class='right-side-box side-box responsive'>
					<!--Info-->
					<div id='information' class='side-box-item responsive'>
						<span class='side-box-title'>Information</span>
						Compatible with Microsoft Windows only<br>
						$saves $saves_str
						$characters_display
						$videos_display
					</div>
				</div>
			</div>
		";
	}
	catch(Exception $e) {
		$display_description = "An error has occured.";
		$display_title = 'Error';
		$display_javascript = "";
		$display_page = $e->getMessage();
	}
?>