<?php 
	$error_val = 'Sorry, there was an error loading new entries. Please try again later.';
	$new_games = '';
	$content = array();
	$limit = 8;
	
	try {
		$ws = false;
		if(isset($_GET['ws'])) {
			if($_GET['ws']) {
				// Otherwise, we will be already connected
				$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
				if (mysqli_connect_errno()) {
					$mysqli->close();
					exit();
				}
			}
			$ws = $mysqli->real_escape_string($_GET['ws']);
			$game_offset = $mysqli->real_escape_string($_GET['game_offset']);
			$video_offset = $mysqli->real_escape_string($_GET['video_offset']);
		}
		
		if(!isset($mysqli)) {
			throw new Exception($error_val);
		}
		// If the connection is closed
		if (!$mysqli->ping()) {
			throw new Exception($error_val);
		}

		$select_games_str = "SELECT name, url_name, description, image_url, rating, added_date, FORMAT(plays, 0)
						FROM entries ORDER BY added_date DESC, rating DESC LIMIT $limit";
		if($ws) {
			$select_games_str .= " OFFSET ?";
		}
		
		$select_games_statement = $mysqli->prepare($select_games_str);
		if($ws) {
			// Bind parameters
			$select_games_statement->bind_param("i", $game_offset);
		}
		$select_games_statement->execute();
		if(mysqli_stmt_error($select_games_statement) != "") {
			$mysqli->close();
			throw new Exception($error_val);
		}
		$select_games_statement->bind_result($name, $url_name, $description, $image_url, $rating, $added_date, $plays);
		
		$all_games = array();
		//Put the games results into an array
		while ($select_games_statement->fetch()) {
			$games[0] = htmlentities($name, ENT_QUOTES);
			$games[1] = $url_name;
			$games[2] = $description;
			$games[3] = $image_url;
			$games[4] = $rating;
			$games[5] = $added_date;
			$games[6] = $plays;
			$games['type'] = 'game';
			$all_games[] = $games;
		}
		
		$select_games_statement->close();
		
		$select_videos_str = "SELECT name, url_name, description, image_url, rating, added_date, FORMAT(views, 0)
			FROM hallaby_videos.entries ORDER BY added_date DESC, rating DESC LIMIT $limit";
		if($ws) {
			$select_videos_str .= " OFFSET ?";
		}
		$select_videos_statement = $mysqli->prepare($select_videos_str);
		if($ws) {
			// Bind parameters
			$select_videos_statement->bind_param("i", $video_offset);
		}
		$select_videos_statement->execute();
		if(mysqli_stmt_error($select_videos_statement) != "") {
			$mysqli->close();
			throw new Exception($error_val);
		}
		$select_videos_statement->bind_result($name, $url_name, $description, $image_url, $rating, $added_date, $views);
		
		$all_videos = array();
		//Put the videos results into an array
		while ($select_videos_statement->fetch()) {
			$videos[0] = htmlentities($name, ENT_QUOTES);
			$videos[1] = $url_name;
			$videos[2] = $description;
			$videos[3] = $image_url;
			$videos[4] = $rating;
			$videos[5] = $added_date;
			$videos[6] = $views;
			$videos['type'] = 'video';
			$all_videos[] = $videos;
		}
		
		$select_videos_statement->close();
		
		//Now merge the two arrays by date
		$games_counter = 0;
		$videos_counter = 0;
		$total_counter = 0;
		$max = max(count($all_games), count($videos_counter));
		while($total_counter < $max) {
			if($videos_counter < count($all_videos) && $games_counter < count($all_games)) {
				if(strtotime($all_games[$games_counter][5]) >= strtotime($all_videos[$videos_counter][5])) {
					$content[] = $all_games[$games_counter];
					$games_counter ++;
				}
				else {
					$content[] = $all_videos[$videos_counter];
					$videos_counter ++;
				}
			}
			else if($games_counter < count($all_games)) {
				$content[] = $all_games[$games_counter];
				$games_counter ++;
			}
			else if($videos_counter < count($all_videos)) {
				$content[] = $all_videos[$videos_counter];
				$videos_counter ++;
			}
			else {
				break;
			}
			$total_counter ++;
		}
		
		$lines = 0;
		$prev_date = 0;
		$total_entries = -1;
		
		//Make an entry
		for ($rows = 0; $rows < count($content); $rows++) {
			$type = $content[$rows]['type'];
			$name = $content[$rows][0];
			$url_name = $content[$rows][1];
			$description = $content[$rows][2];
			$image_url = $content[$rows][3];
			$rating = $content[$rows][4];
			$date = strtotime($content[$rows][5]);
			$plays_views = $content[$rows][6];
			if($plays_views == 1 && $type == 'game') {
				$plays_views_str = 'play';
			}
			else if ($type == 'game') {
				$plays_views_str = 'plays';
			}
			else if ($plays_views == 1) {
				$plays_views_str = 'view';
			}
			else {
				$plays_views_str = 'views';
			}
			
			// Format the date and exclude any time reference
			$check_date = date('m/d/Y', $date);
			
			$rating_width = ($rating * 22) . 'px';
			
			//If the first item, display a header
			if($total_entries == -1) {
				$formatted_date = date("n/j/y", $date);
				$new_games .= "<div class='date-box'><div class='date-box-title'>Added on ".$formatted_date."</div>";
				$prev_date = $check_date;
			}
			//If the previous date does not match the current date, display a header
			else if($check_date != $prev_date) {
				$formatted_date = date("n/j/y", $date);
				$new_games .= "</div><div class='date-box'><div class='date-box-title'>Added on ".$formatted_date."</div>";
				$prev_date = $check_date;
			}
			
			if($type == 'game') {
				$new_games .= "<a href = '/game/$url_name' class = 'entry-link'>";
			}
			else {
				$new_games .= "<a href = '/video/$url_name' class = 'entry-link'>";
			}
			//Echo the entry
			
			$new_games .= "<span class = 'entry-item $type-entry'>
			<img alt = '$name' src = '$image_url'>
			<span class = 'entry-title'>$name</span>
			<span class='stars entry-stars'><span style='width: $rating_width'></span></span>";
			$new_games .= "<span class = 'entry-description'> $description</span>
			<span class = 'entry-plays'> $plays_views $plays_views_str</span>
			</span>
			</a>";
			
			$total_entries ++;
		}
		
		//Nothing found
		if($total_entries == -1) {
			$new_games = "Sorry, no entries were found.";
		}
		else {
			$new_games .= "</div>";
			if(!$ws) {
				$new_games = "
				<div class='box-content'>
					<div class='box-content-title'>
						New Content
					</div>
					<div class='box-content-container'>
						<div id='new-box'>$new_games</div>
					</div>
					<div id='load-more-content' onclick='loadMoreContent()'>Load earlier content</div>
				</div>";
				$new_games_js = "
				// Load more content
				function loadMoreContent() {
					var errorText = 'Sorry, an error occured while trying to fetch more content. Please try again later.';
					var games = document.getElementById('new-box');
					if(games.style.opacity != 0.5) {
						var xhttp = new XMLHttpRequest();
						// var loading = document.getElementById('loading');
						games.style.opacity = 0.5;
						// loading.style.visibility = 'visible';
						xhttp.onreadystatechange = function() {
							if (xhttp.readyState == 4) {
								if(xhttp.status == 200) {
									try {
										// loading.style.visibility = 'hidden';
										games.style.opacity = 1;
										var newContent = xhttp.responseText;
										
										// Get the last date box and its title
										var dateBoxes = document.getElementsByClassName('date-box');
										var dateBoxTitles = document.getElementsByClassName('date-box-title');
										var previousDateBoxCount = dateBoxes.length;
										var lastDateBox = dateBoxes[previousDateBoxCount - 1];
										var lastDateBoxTitle = dateBoxTitles[dateBoxTitles.length - 1];
										
										// Add the new data and get the first newly added game box and its title
										games.innerHTML = games.innerHTML + newContent;
										dateBoxes = document.getElementsByClassName('date-box');
										dateBoxTitles = document.getElementsByClassName('date-box-title');
										var newestLoadedDateBox = dateBoxes[previousDateBoxCount];
										
										if(newestLoadedDateBox) {
											var newestLoadedDateBoxTitle = dateBoxTitles[previousDateBoxCount];
											
											// If the previous last and newest loaded boxes have the same title (date)
											// Combine them
											if(lastDateBoxTitle.innerHTML == newestLoadedDateBoxTitle.innerHTML) {
												var innerContent = newestLoadedDateBox.childNodes;
												var keptInnerContent = '';
												for(var i = 0; i < innerContent.length; i++) {
													if(innerContent[i].tagName == 'A') {
														keptInnerContent += innerContent[i].outerHTML;
													}
												}
												lastDateBox = document.getElementsByClassName('date-box')[previousDateBoxCount-1];
												lastDateBox.innerHTML = lastDateBox.innerHTML + keptInnerContent;
												newestLoadedDateBox.parentNode.removeChild(newestLoadedDateBox);
											}
										}
										else {
											document.getElementById('load-more-content').style.display = 'none';
										}
									}
									catch(e) {
										games.innerHTML = errorText;
										games.style.opacity = 1;
										console.log(e);
									}
								}
								else {
									games.innerHTML = errorText;
								}
							}
						}
						var gameOffset = document.getElementsByClassName('game-entry').length;
						var videoOffset = document.getElementsByClassName('video-entry').length;
						xhttp.open('GET', '/widgets/newgames.php?ws=true&game_offset=' + gameOffset + '&video_offset=' + videoOffset, true);
						xhttp.send();
					};
				}
				
				";
			}
			else {
				echo $new_games;
			}
		}
	}
	catch(Exception $e) {
		$new_games = $e->getMessage();
	}
?>