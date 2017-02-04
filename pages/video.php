<?php
	try {
		$video_does_not_exist_message = "The video specified does not exist.";
		$original_width = 800;
		$original_height = 482;
		$ip = $_SERVER['REMOTE_ADDR'];
		
		if(!isset($routed)) {
			throw new Exception($direct_access_message);
		}
		
		// Connect to database
		$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_videos");
		if (mysqli_connect_errno()) {
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		
		//////////
		// VIDEO //
		//////////
		// Get the required url params
		$url_name = $mysqli->real_escape_string($url_name);
		// String to query the database with
		$str = "SELECT id, name, entries.string, DATE_FORMAT(added_date, '%M %D, %Y'), description,
		FORMAT(views, 0), image_url FROM entries WHERE url_name = ? LIMIT 1";
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
		$statement->bind_result($id, $name, $string, $added_date, $description, $views, $image_url);
		// Fetch the result
		$statement->fetch();
		// Close the statement
		$statement->close();
		if(!isset($id)) {
			throw new Exception($video_does_not_exist_message);
		}
		if($id == 0) {
			throw new Exception($video_does_not_exist_message);
		}
		if($views == 1) {
			$views_str = 'view';
		}
		else {
			$views_str = 'views';
		}
		
		//////////////////
		// VIEW COUNTER //
		//////////////////
		// Update the views counter
		$views_insert_str = "INSERT INTO views(entry_id, ip_address) VALUES (?, ?)";
		$views_insert_statement = $mysqli->prepare($views_insert_str);
		$views_insert_statement->bind_param("is", $id, $ip);
		$views_insert_statement->execute();
		//if(mysqli_stmt_error($views_insert_statement) != "") {
		//	$views_insert_statement->close();
		//	$mysqli->close();
		//	throw new Exception($mysql_message);
		//}
		$views_insert_statement->close();

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
		
		///////////
		// GAMES //
		///////////
		$games_str = "
		SELECT hallaby_games.entries.name as name, hallaby_games.entries.url_name, hallaby_games.entries_videos.video_id as video_id, 0
		FROM hallaby_games.entries_videos
		JOIN hallaby_games.entries on hallaby_games.entries_videos.entry_id = hallaby_games.entries.id
		WHERE video_id = ?
		UNION
		SELECT hallaby_games.downloads.name as name, hallaby_games.downloads.url_name, hallaby_games.downloads_videos.video_id as video_id, 1
		FROM hallaby_games.downloads_videos
		JOIN hallaby_games.downloads on hallaby_games.downloads_videos.download_id = hallaby_games.downloads.id
		WHERE video_id = ?
		UNION
		SELECT hallaby_games.apps.name as name, hallaby_games.apps.url_name, hallaby_games.apps_videos.video_id as video_id, 2
		FROM hallaby_games.apps_videos
		JOIN hallaby_games.apps on hallaby_games.apps_videos.app_id = hallaby_games.apps.id
		WHERE video_id = ?
		ORDER BY name";
		$games_statement = $mysqli->prepare($games_str);
		$games_statement->bind_param("iii", $id, $id, $id);
		$games_statement->execute();
		if(mysqli_stmt_error($games_statement) != "") {
			$games_statement->close();
			$mysqli->close();
			throw new Exception($mysql_message);
		}
		$games_statement->bind_result($game_name, $game_url_name, $unused_id, $type);
		$games_display = "";
		$games_count = 0;
		while($games_statement->fetch()) {
			if($games_count == 0) {
				if($games_display != "") {
					$games_display .= "<br>";
				}
				$games_display .= "Games: ";
			}
			else {
				$games_display .= ", ";
			}
			if($type == 0) {
				$games_display .= "<a class='side-box-link' href='/game/$game_url_name'>$game_name</a>";
			}
			else if($type == 1) {
				$games_display .= "<a class='side-box-link' href='/download/$game_url_name'>$game_name</a>";
			}
			else if($type == 2) {
				$games_display .= "<a class='side-box-link' href='/app/$game_url_name'>$game_name</a>";
			}
			$games_count++;
		}
		
		$games_statement->close();
		
		include $_SERVER['DOCUMENT_ROOT'] . '/widgets/randomvideos.php';
		
		$mysqli->close();

		$display_description = "$description Watch $name on Game 103!";
		$display_meta = "<meta property='og:image' content='http://game103.net$image_url'>
		<meta property='og:description' content=\"$display_description\">";
		$display_title = $name;
		$display_javascript = "
		// If the movie is small enough that the desktop view is possible
		var responsive = true;
		// The current scaling factor of the video
		var curValue = 1;
		// If the mouse is down
		var mouseDown = 0;
		// The original width of the video
		var originalWidth = $original_width;
		// The original height of the video
		var originalHeight = $original_height;
		
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
				document.getElementById('shrink').style.display = 'inline-block';
				document.getElementById('grow').style.display = 'inline-block';
			}
			// Respsonsive zoom
			if(window.innerWidth <= 800) {
				fullScreen();
			}
		}
		// Change the zoom of the movie
		// This is called on input from the slider
		function changeZoom(value) {
			var movie = document.getElementById('video');
			var preview = document.getElementById('preview-box');
			if(value == null) {
				value = document.getElementById('zoom-slider').value/100;
			}
			curValue = value;
			setSizeFromVideoSize(movie, value);
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
				var movie = document.getElementById('video');
				var preview = document.getElementById('preview-box');
				var value = document.getElementById('zoom-slider').value/100;
				setSizeFromVideoSize(preview, value);
			}
		}
		// Hide the video and show the preview box
		// This is called on mouse down from the slider
		function hideGame() {
			var movie = document.getElementById('video');
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
		// because the video is too big
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
		// Function to shrink the video size for when range is not supported
		function shrink() {
			var movie = document.getElementById('video');
			if(curValue - 0.25 > 0.5) {
				curValue -= 0.25;
			}
			else {
				curValue = 0.5;
			}
			changeZoom(curValue);
			ensureValue();
		}
		// Function to grow the video size for when range is not supported
		function grow() {
			var movie = document.getElementById('video');
			if(curValue + 0.25 < 1.75) {
				curValue += 0.25;
			}
			else {
				curValue = 1.75;
			}
			changeZoom(curValue);
			ensureValue();
		}
		// Function to make the video full screen
		function fullScreen() {
			var movie = document.getElementById('video');
			var gameTop = 209;
			var scrollX = 0;
			var widthCalculator = document.createElement('div');
			widthCalculator.style.position = 'fixed';
			widthCalculator.style.width = '1px';
			widthCalculator.style.height = '1px';
			widthCalculator.style.bottom = '0';
			widthCalculator.style.right = '0';
			widthCalculator.style.visibility = 'hidden';
			document.body.appendChild(widthCalculator);
			var sizeToSetGame = widthCalculator.offsetTop + 1;
			widthCalculator.style.position = 'absolute';
			var sizeToSetGameWidth = widthCalculator.offsetLeft + 1;
			var percentToSetGame = sizeToSetGame/originalHeight;
			var percentToSetGameWidth = (sizeToSetGameWidth-10)/originalWidth;
			if(percentToSetGameWidth < percentToSetGame) {
				percentToSetGame = percentToSetGameWidth;
			}
			changeZoom(percentToSetGame);
			ensureValue();
			widthCalculator.parentNode.removeChild(widthCalculator);
		}
		// Set an element's size based on the offset of the original video size
		function setSizeFromVideoSize(element, value) {
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
			xhttp.open('GET', '/ws/videorating.php?id=$id&rating=' + rating, true);
			xhttp.send();
		}
		
		";
		
		$display_page = 
		"
		<!--Video-->
			<div class='box-content box-content-tight'>
				<div class='box-content-title'>$name</div>
				<div class='box-content-container box-content-container-tight'>
					<div id='preview-box' style='width:$original_width"."px;height:$original_height"."px;'></div>
					<div id='video-container' class='responsive'>
						<iframe width='$original_width' height='$original_height' allowfullscreen='allowfullscreen' src='https://www.youtube.com/embed/$string?rel=0&amp;modestbranding=1&amp;theme=light&amp;iv_load_policy=3' frameborder='0' id='video'></iframe>
					</div>
				</div>
			</div>
			
			<div class='side-boxes responsive'>
				<div class='left-side-box side-box responsive'>
					<!--Info-->
					<div id='information' class='side-box-item responsive'>
						<span class='side-box-title'>Information</span>
						$views $views_str<br>
						Added on $added_date
						$games_display
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
						Zoom<br>
						<input type='range' min='50' max='175' step='5' value='100' id='zoom-slider' autocomplete='off' 
						onmousedown='hideGame()' onmouseup='changeZoom()' onchange='ensureValue()' oninput='preview()'/>
						<button id='shrink' onclick='shrink()'>Smaller</button>
						<button id='grow' onclick='grow()'>Larger</button>
						<button onclick='window.open(\"https://www.facebook.com/sharer/sharer.php?u=http%3A//game103.net/video/$url_name\")'>Share on Facebook</button>
						<button onclick='window.open(\"https://twitter.com/home?status=Check%20out%20$name%20on%20game103.net%3A%20http%3A//game103.net/video/$url_name\")'>Share on Twitter</button>
						<button onclick='window.open(\"https://plus.google.com/share?url=http%3A//game103.net/video/$url_name\")'>Share on Google+</button>
					</div>
				</div>
			</div>
			
			<div class='random-games'><div class='box-content'><div class='box-content-title'>Similar Videos</div><div class='box-content-container'>$random_videos</div></div></div>
		";
	}
	catch(Exception $e) {
		$display_description = "An error has occured.";
		$display_title = 'Error';
		$display_javascript = "";
		$display_page = $e->getMessage();
	}
?>