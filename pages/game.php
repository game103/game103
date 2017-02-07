<?php
	try {
		$game_does_not_exist_message = "The game specified does not exist.";
		$ip = $_SERVER['REMOTE_ADDR'];
		
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
		$str = "SELECT id, name, url, width, height, DATE_FORMAT(added_date, '%M %D, %Y'), description,
		FORMAT(plays, 0), image_url FROM entries WHERE url_name = ? LIMIT 1";
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
		$statement->bind_result($id, $name, $url, $width, $height, $added_date, $description, $plays, $image_url);
		// Fetch the result
		$statement->fetch();
		// Close the statement
		$statement->close();
		if(!isset($id)) {
			throw new Exception($game_does_not_exist_message);
		}
		if($id == 0) {
			throw new Exception($game_does_not_exist_message);
		}
		if($plays == 1) {
			$plays_str = 'play';
		}
		else {
			$plays_str = 'plays';
		}

		//////////////
		// CONTROLS //
		//////////////
		// Get the controls
		$controls_str = "SELECT controls.key, actions.name FROM entries
			join actions_controls_entries on actions_controls_entries.entry_id = entries.id
			join actions_controls on actions_controls_entries.action_control_id = actions_controls.id
			join actions on actions_controls.action_id = actions.id
			join controls on actions_controls.control_id = controls.id
		WHERE entries.id = ?
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
		
		//////////////////
		// PLAY COUNTER //
		//////////////////
		// Update the plays counter
		$plays_insert_str = "INSERT INTO plays(entry_id, ip_address) VALUES (?, ?)";
		$plays_insert_statement = $mysqli->prepare($plays_insert_str);
		$plays_insert_statement->bind_param("is", $id, $ip);
		$plays_insert_statement->execute();
		//if(mysqli_stmt_error($plays_insert_statement) != "") {
		//	$plays_insert_statement->close();
		//	$mysqli->close();
		//	throw new Exception($mysql_message);
		//}
		$plays_insert_statement->close();

		////////////
		// RATING //
		////////////
		// Get the total rating
		// Don't use rating since we need num votes
		$rating_str = "SELECT sum(score), count(1) FROM votes WHERE entry_id = ?";
		$rating_statement = $mysqli->prepare($rating_str);
		$rating_statement->bind_param("i", $id);
		$rating_statement->execute();
		$rating_statement->bind_result($summed_rating, $num_votes);
		$rating_statement->fetch();
		if(mysqli_stmt_error($rating_statement) != "") {
			$rating_statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		$rating_statement->close();
		if($num_votes > 0) {
			$total_rating = $summed_rating/$num_votes;
		}
		else {
			$total_rating = 0;
		}
		if($num_votes == 1) {
			$vote_str = 'vote';
		}
		else {
			$vote_str = 'votes';
		}
		$total_votes_width = ($total_rating * 22) . 'px';

		// Check if the user has voted today
		// If so, don't show the option to voted
		// Just show the rating
		$check_str = "SELECT score FROM votes WHERE entry_id = ? AND ip_address = ? and DATE(added_date) = CURDATE()";
		$check_statement = $mysqli->prepare($check_str);
		$check_statement->bind_param("is", $id, $ip);
		$check_statement->execute();
		$check_statement->bind_result($your_rating);
		$check_statement->fetch();
		if(mysqli_stmt_error($check_statement) != "") {
			$check_statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		$check_statement->close();

		if($your_rating == 0) {
			unset($your_rating);
		}
		
		// Determine the display of the rating
		if(is_null($your_rating)) {
			$thanks_display = 'none';
			$your_rating = "
				<span class='stars'>
					<div class='star' id='star-1' onclick=rate(1)></div>
					<div class='star' id='star-2' onclick=rate(2)></div>
					<div class='star' id='star-3' onclick=rate(3)></div>
					<div class='star' id='star-4' onclick=rate(4)></div>
					<div class='star' id='star-5' onclick=rate(5)></div>
					<div class='stars-indication'></div>
				</span>
			";
			$your_rating_js = "";
		}
		else {
			$your_votes_width = ($your_rating * 22) . 'px';
			$thanks_display = 'block';
			$your_rating = "<span class='stars' id='your-stars'><span style='width:$your_votes_width;'></span></span>";
		}
		
		////////////////
		// CHARACTERS //
		////////////////
		// Get the controls
		$characters_str = "SELECT characters.name FROM characters_entries
			JOIN characters on characters_entries.character_id = characters.id
		WHERE characters_entries.entry_id = ?
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
		$videos_str = "SELECT hallaby_videos.entries.name, hallaby_videos.entries.url_name FROM entries_videos
			JOIN hallaby_videos.entries on entries_videos.video_id = hallaby_videos.entries.id
		WHERE entries_videos.entry_id = ?
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
		
		include $_SERVER['DOCUMENT_ROOT'] . '/widgets/randomgames.php';

		$mysqli->close();

		$display_description = "$description Play $name on Game 103!";
		$display_meta = "<meta property='og:image' content='http://game103.net$image_url'>
		<meta property='og:description' content=\"$display_description\">";
		$display_title = $name;
		$display_javascript = "
		// If the movie is small enough that the desktop view is possible
		var responsive = true;
		// The current scaling factor of the game
		var curValue = 1;
		// If the mouse is down
		var mouseDown = 0;
		// The original width of the game
		var originalWidth;
		// The original height of the game
		var originalHeight;
		
		window.onload = function() {
			// Always check for mouse down
			document.body.onmousedown = function() { 
				++mouseDown;
			}
			document.body.onmouseup = function() {
				--mouseDown;
			}
			// See if range is supported
			if(document.getElementById('zoom-slider').type != 'range') {
				document.getElementById('zoom-slider').style.display = 'none';
				document.getElementById('full').style.display = 'none';
				document.getElementById('shrink').style.display = 'inline-block';
				document.getElementById('grow').style.display = 'inline-block';
			}
			// Get the original dimensions
			var movie = document.getElementById('game');
			originalWidth = parseInt(movie.style.width);
			originalHeight = parseInt(movie.style.height);
		}
		// Change the zoom of the movie
		// This is called on input from the slider
		function changeZoom(value) {
			var movie = document.getElementById('game');
			var preview = document.getElementById('preview-box');
			if(value == null) {
				value = document.getElementById('zoom-slider').value/100;
			}
			curValue = value;
			setSizeFromGameSize(movie, value);
			preview.style.display = 'none';
			movie.style.visibility = 'visible';
			if(responsive) {
				if(movie.offsetWidth > 825) {
					stripResponsiveClasses();
					responsive = false;
				}
			}
			else {
				if(movie.offsetWidth <= 825) {
					addResponsiveClasses();
					responsive = true;
				}
			}
		}
		// Change the size of the preview box
		// This is called on mouse move on the slider
		// the mouseDown check ensures that this will only occur when the slider
		// is pressed
		function preview() {
			if(mouseDown) {
				var movie = document.getElementById('game');
				var preview = document.getElementById('preview-box');
				var value = document.getElementById('zoom-slider').value/100;
				setSizeFromGameSize(preview, value);
			}
		}
		// Hide the game and show the preview box
		// This is called on mouse down from the slider
		function hideGame() {
			var movie = document.getElementById('game');
			var preview = document.getElementById('preview-box');
			preview.style.display = 'block';
			movie.style.visibility = 'hidden';
		}
		// Ensure that the slider displays the correct value
		// This is called on change from the slider
		// This is mainly to fix a bug where the slider
		// displays the wrong value after the view changes due to game size
		// The mouse down is solely for Internet Explorer since it calls on change
		// When the slider is still pressed
		function ensureValue() {
			if(!mouseDown) {
				document.getElementById('zoom-slider').value = curValue * 100;
			}
			else {
				// Since IE treats onchange like oninput
				preview();
			}
		}
		// Strip the classes that make this page responsive
		// because the game is too big
		function stripResponsiveClasses() {
			var responsiveClasses = Array.prototype.slice.call(document.getElementsByClassName('responsive'), 0);
			for (var i = 0; i < responsiveClasses.length; i++) {
				responsiveClasses[i].classList.remove('responsive');
				responsiveClasses[i].classList.add('was-responsive');
			}
		}
		// Add the responsive classes back
		function addResponsiveClasses() {
			var responsiveClasses = Array.prototype.slice.call(document.getElementsByClassName('was-responsive'), 0);
			for (var i = 0; i < responsiveClasses.length; i++) {
				responsiveClasses[i].classList.add('responsive');
				responsiveClasses[i].classList.remove('was-responsive');
			}
		}
		// Function to shrink the game size for when range is not supported
		function shrink() {
			var movie = document.getElementById('game');
			if(curValue - 0.25 > 0.5) {
				curValue -= 0.25;
			}
			else {
				curValue = 0.5;
			}
			changeZoom(curValue);
			ensureValue();
		}
		// Function to grow the game size for when range is not supported
		function grow() {
			var movie = document.getElementById('game');
			if(curValue + 0.25 < 1.75) {
				curValue += 0.25;
			}
			else {
				curValue = 1.75;
			}
			changeZoom(curValue);
			ensureValue();
		}
		// Function to make the game full screen
		function fullScreen() {
			var movie = document.getElementById('game');
			var gameTop = 209;
			var scrollX = 0;
			var widthCalculator = document.createElement('div');
			widthCalculator.style.position = 'fixed';
			widthCalculator.style.width = '1px';
			widthCalculator.style.height = '1px';
			widthCalculator.style.bottom = '0';
			widthCalculator.style.right = '0';
			widthCalculator.style.visibility = 'hidden';
			document.getElementsByClassName('page')[0].appendChild(widthCalculator);
			var sizeToSetGame = widthCalculator.offsetTop + 1;
			widthCalculator.style.position = 'absolute';
			var sizeToSetGameWidth = widthCalculator.offsetLeft + 1;
			var percentToSetGame = sizeToSetGame/originalHeight;
			var percentToSetGameWidth = (sizeToSetGameWidth-10)/originalWidth;
			if(percentToSetGameWidth < percentToSetGame) {
				percentToSetGame = percentToSetGameWidth;
			}
			changeZoom(percentToSetGame);
			window.scrollTo(document.getElementById('game-container').offsetLeft-5, gameTop);
			ensureValue();
			widthCalculator.parentNode.removeChild(widthCalculator);
		}
		// Set an element's size based on the offset of the original game size
		function setSizeFromGameSize(element, value) {
			element.style.width = (originalWidth * value).toString().concat('px');
			element.style.height = (originalHeight * value).toString().concat('px');
		}
		// Set the total stars value for an element
		function setTotalStars(value, id) {
			var totalStars = document.getElementById(id);
			totalStars.innerHTML = '';
			value = parseFloat(value);
			var size = Math.max(0, (Math.min(5, value))) * 22;
			var ratingDisplay = document.createElement('span');
			ratingDisplay.style.width = size.toString().concat('px');
			totalStars.appendChild(ratingDisplay);
		}
		// Rate the game
		function rate(rating) {
			if(isNaN(Number(rating)) || Number(rating) < 0 || Number(rating) > 5) {
				return;
			}
			var normal = document.getElementById('show-rating-normal');
			var loading = document.getElementById('show-rating-loading');
			loading.style.display = 'block';
			normal.style.display = 'none';
			var xhttp = new XMLHttpRequest();
			var errorText = 'Sorry, an error occured while trying to process your vote. Please try again later.';
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
					try {
						var object = JSON.parse(xhttp.responseText);
						var status = object['status'];
						if(status != 'success') {
							loading.innerHTML = object['message'];
						}
						else {
							var newRating = object['rating'];
							var newVotes = object['votes'];
							document.getElementsByClassName('your-rating')[0].innerHTML = \"<span class='stars' id='your-stars'></span>\";
							loading.style.display= 'none';
							normal.style.display = 'inline';
							document.getElementsByClassName('thanks-for-voting')[0].style.display = 'block';
							setTotalStars(rating, 'your-stars');
							setTotalStars(newRating, 'total-stars');
							voteStr = ' vote';
							if(newVotes != 1) {
								voteStr = ' votes';
							}
							document.getElementsByClassName('total-votes')[0].innerHTML = newVotes + voteStr;
						}
					}
					catch(e) {
						loading.innerHTML = errorText;
					}
				}
			};
			xhttp.open('GET', '/ws/gamerating.php?id=$id&rating=' + rating, true);
			xhttp.send();
		}
		
		";
		
		$display_page = 
		"
		<!--Game-->
			<div class='box-content box-content-tight game-box-content'>
				<div class='box-content-title'>$name</div>
				<div class='box-content-container box-content-container-tight'>
					<div id='preview-box' style='width:$width"."px;height:$height"."px;'></div>
					<div id='game-container' class='responsive'>
						<embed wmode='direct' src='$url' style='width:$width"."px;height:$height"."px;' id='game'>
					</div>
				</div>
			</div>
			
			
			<div class='side-boxes responsive'>
				<div class='left-side-box side-box responsive'>
					<!--Controls-->
					<div id='controls' class='side-box-item responsive'><span class='side-box-title'>Controls</span>$controls</div>
					
					<!--Info-->
					<div id='information' class='side-box-item responsive'>
						<span class='side-box-title'>Information</span>
						$plays $plays_str<br>
						Added on $added_date
						$characters_display
						$videos_display
					</div>
				</div>
				
				<div class='right-side-box side-box responsive'>
					<!--Rating-->
					<div id='rating' class='side-box-item responsive'>
					<span class='side-box-title'>Rating</span>
					<div id='show-rating-loading'>
						Loading...
					</div>
					<span id='show-rating-normal'>
						<div class='thanks-for-voting' style='display: $thanks_display;'>
							<div>Thanks for voting today!</div>
							<div>Come back tomorrow to vote again!</div>
						</div>
						<div>Your Rating</div>
						<div class='your-rating'>
							$your_rating
						</div>
						<div>Total Rating</div>
						<div class='total-rating'>
							<span class='stars' id='total-stars'><span style='width: $total_votes_width'></span></span>
						</div>
						<div class='total-votes'>
							$num_votes $vote_str
						</div>
					</span>
					</div>
					
					<!--Options-->
					<div id='options' class='side-box-item responsive'>
						<span class='side-box-title'>
							Options
						</span>
						<span class='game-zoom-options'>
							Zoom<br>
							<input type='range' min='50' max='175' step='5' value='100' id='zoom-slider' autocomplete='off' 
							onmousedown='hideGame()' onmouseup='changeZoom()' onchange='ensureValue()' oninput='preview()'/>
							<button id='full' onclick='fullScreen()'>Fit Screen</button>
							<button id='shrink' onclick='shrink()'>Smaller</button>
							<button id='grow' onclick='grow()'>Larger</button>
						</span>
						<br>Share<br>
						<button onclick='window.open(\"https://www.facebook.com/sharer/sharer.php?u=http%3A//game103.net/game/$url_name\")'>Share on Facebook</button>
						<button onclick='window.open(\"https://twitter.com/home?status=Check%20out%20$name%20on%20game103.net%3A%20http%3A//game103.net/game/$url_name\")'>Share on Twitter</button>
						<button onclick='window.open(\"https://plus.google.com/share?url=http%3A//game103.net/game/$url_name\")'>Share on Google+</button>
					</div>
				</div>
			</div>
			
			<div class='random-games'><div class='box-content'><div class='box-content-title'>Similar Games</div><div class='box-content-container'>$random_games</div></div></div>
		";
		
		$screen_width = ( (string) $width + 10 ) . 'px';
		$game_ratio = $height/$width;
		$padding_bottom = ( (string) $game_ratio * 100) . '%';
		$display_css = "
			/* This has a known issue when scrollbars are included in the media screen width
			as part of the game will be cut of to the user, even though it would be visible
			if the scrollbars were gone */
			@media screen and (max-width: $screen_width) {
				.game-box-content {
					width: calc(100% - 10px);
				}
				#game-container {
					position: relative;
					width: 100%;
					height: 0;
					padding-bottom: $padding_bottom;
				}
				#game {
					position: absolute;
					width: 100% !important;
					height: 100% !important;
					visibility: visible;
				}
				.box-content-container {
					overflow: hidden;
				}
				.game-zoom-options {
					display: none;
				}
			}
		";
	}
	catch(Exception $e) {
		$display_description = "An error has occured.";
		$display_title = 'Error';
		$display_javascript = "";
		$display_page = $e->getMessage();
	}
?>