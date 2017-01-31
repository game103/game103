<?php 
	$error_val = 'Sorry, there was an error loading top games. Please try again later.';
	
	try {
		$top_games = '<div class="box-content" id="top-games"><div class="box-content-title">Top Games</div><button class="box-content-button-selected box-content-button" id="weekly-button" onclick="changeView(\'weekly\')">Top Games This Week</button>';
		$top_games .= '<button class="box-content-button" id="monthly-button" onclick="changeView(\'monthly\')">Top Games This Month</button>';
		$top_games .= '<button class="box-content-button" id="all-button" onclick="changeView(\'all\')">Top Games of All Time</button>';
		$top_games .= '<button class="box-content-button" id="rating-button" onclick="changeView(\'rating\')">Highest Rated Games</button>';
		$top_games .= '<button class="box-content-button" id="random-button" onclick="changeView(\'random\')">Random Games</button>';
		$top_games .= "<div class='box-content-container'>";
		$all_games = [];
		$top_games_js = "";
		$limit = 6;
		
		if(!isset($mysqli)) {
			throw new Exception($error_val);
		}
		// If the connection is closed
		if (!$mysqli->ping()) {
			throw new Exception($error_val);
		}

		$select_games_str = "
		(SELECT entries.name, entries.url_name, entries.description, entries.image_url, entries.rating, entries.added_date, FORMAT(plays, 0),
		FORMAT(SUM(CASE WHEN plays.added_date > DATE_SUB(now(), INTERVAL 1 WEEK) THEN 1 ELSE 0 END), 0)
		FROM entries JOIN plays on entries.id = plays.entry_id
		GROUP BY entries.id
		ORDER BY SUM(CASE WHEN plays.added_date > DATE_SUB(now(), INTERVAL 1 WEEK) THEN 1 ELSE 0 END) DESC, plays DESC, rating DESC LIMIT $limit)
		UNION ALL
		(SELECT entries.name, entries.url_name, entries.description, entries.image_url, entries.rating, entries.added_date, FORMAT(plays, 0),
		FORMAT(SUM(CASE WHEN plays.added_date > DATE_SUB(now(), INTERVAL 1 MONTH) THEN 1 ELSE 0 END), 0) as special_plays
		FROM entries JOIN plays on entries.id = plays.entry_id
		GROUP BY entries.id
		ORDER BY SUM(CASE WHEN plays.added_date > DATE_SUB(now(), INTERVAL 1 MONTH) THEN 1 ELSE 0 END) DESC, plays DESC, rating DESC LIMIT $limit)
		UNION ALL
		(SELECT name, url_name, description, image_url, rating, added_date, FORMAT(plays, 0), -1
		FROM entries ORDER BY plays DESC, rating DESC LIMIT $limit)
		UNION ALL
		(SELECT name, url_name, description, image_url, rating, added_date, FORMAT(plays, 0), -1
		FROM entries ORDER BY rating DESC, plays DESC LIMIT $limit)
		UNION ALL
		(SELECT name, url_name, description, image_url, rating, added_date, FORMAT(plays, 0), -1
		FROM entries ORDER BY RAND() LIMIT $limit)";
		$select_games_statement = $mysqli->prepare($select_games_str);
		$select_games_statement->execute();
		if(mysqli_stmt_error($select_games_statement) != "") {
			$mysqli->close();
			throw new Exception($error_val);
		}
		$select_games_statement->bind_result($name, $url_name, $description, $image_url, $rating, $added_date, $plays, $special_plays);
		
		//Put the games results into an array
		while ($select_games_statement->fetch()) {
			$games[0] = htmlentities($name, ENT_QUOTES);
			$games[1] = $url_name;
			$games[2] = $description;
			$games[3] = $image_url;
			$games[4] = $rating;
			$games[5] = $added_date;
			$games[6] = $plays;
			$games[7] = $special_plays;
			$all_games[] = $games;
		}
		
		$select_games_statement->close();
		
		$lines = 0;
		$total_entries = -1;
		
		//Nothing found
		if(count($all_games) != 30) {
			$top_games =  "Sorry, no entries were found.";
		}
		else {
			//Make an entry
			for ($rows = 0; $rows < count($all_games); $rows++) {
				$name = $all_games[$rows][0];
				$url_name = $all_games[$rows][1];
				$description = $all_games[$rows][2];
				$image_url = $all_games[$rows][3];
				$rating = $all_games[$rows][4];
				$plays = $all_games[$rows][6];
				$special_plays = $all_games[$rows][7];

				if($plays == 1) {
					$plays_str = "$plays play";
				}
				else {
					$plays_str = "$plays plays";
				}
				
				if($rows < 6) {
					if($rows == 0) {
						$top_games .= "<div class='box-content-box' id='weekly-box'>";
					}
					$plays_str .= " ($special_plays this week)";
				}
				else if($rows < 12) {
					if($rows == 6) {
						$top_games .= "</div><div class='box-content-box' id='monthly-box'>";
					}
					$plays_str .= " ($special_plays this month)";
				}
				else if($rows < 18) {
					if($rows == 12) {
						$top_games .= "</div><div class='box-content-box' id='all-box'>";
					}
				}
				else if($rows < 24) {
					if($rows == 18) {
						$top_games .= "</div><div class='box-content-box' id='rating-box'>";
					}
				}
				else {
					if($rows == 24) {
						$top_games .= "</div><div class='box-content-box' id='random-box'>";
					}
				}
				
				$top_games .= "<a href = '/game/$url_name' class = 'entry-link'>";
				
				$rating_width = ($rating * 22) . 'px';
				
				//Echo the entry
				
				$top_games .= "
				<span class = 'entry-item'>
				<img alt = '$name' src = '$image_url'><br>
				<span class = 'entry-title'>$name</span>
				<span class='stars entry-stars'><span style='width: $rating_width'></span></span>
				<span class = 'entry-description'> $description</span>
				<span class = 'entry-plays'>$plays_str</span>
				</span>
				</a>";
				
				$total_entries ++;
			}
			
			$top_games .= "</div>";
			
			$top_games_js = "
			// The currently shown div
			var current = 'weekly';
			// Change which div is shown
			function changeView(view) {
				if(view == current && view == 'random') {
					updateRandom();
				}
				document.getElementById(current + '-button').classList.remove('box-content-button-selected');
				document.getElementById(current + '-box').style.display='none';
				current = view;
				document.getElementById(current + '-button').classList.add('box-content-button-selected');
				document.getElementById(current + '-box').style.display='block';
			}
			// Update the random games
			function updateRandom() {
				var xhttp = new XMLHttpRequest();
				var errorText = 'Sorry, an error occured while trying to fetch more games. Please try again later.';
				var games = document.getElementById('random-box');
				// var loading = document.getElementById('loading');
				games.style.opacity = 0.5;
				// loading.style.visibility = 'visible';
				xhttp.onreadystatechange = function() {
					if (xhttp.readyState == 4) {
						if(xhttp.status == 200) {
							try {
								// loading.style.visibility = 'hidden';
								games.style.opacity = 1;
								var object = JSON.parse(xhttp.responseText);
								var status = object['status'];
								if(status != 'success') {
									games.innerHTML = object['message'];
								}
								else {
									var gamesHTML = '';
									var gamesArr = object['games'];
									for(var i = 0; i < gamesArr.length; i++) {
										var ratingWidth;
										var ratingSpan;
										var playsSpan;
										var urlBase;
										ratingWidth = gamesArr[i]['rating'] * 22 + 'px';
										ratingSpan = \"<span class='stars entry-stars'><span style='width: \" + ratingWidth + \"'></span></span> \";
										urlBase = 'game';
										var playsStr;
										if(gamesArr[i]['plays'] == 1) {
											playsStr = 'play';
										}
										else {
											playsStr = 'plays';
										}
										playsSpan = \"<span class = 'entry-plays'> \" + gamesArr[i]['plays'] + ' ' + playsStr + \"</span>\";
										var gameURL = '/' + urlBase + '/' +  gamesArr[i]['url_name'];
										gamesHTML += \"<a href='\" + gameURL + \"' class = 'entry-link'> \"
										+ \"<span class = 'entry-item'> \"
										+ \"<img alt = '\" + gamesArr[i]['name'] + \"' src = '\" + gamesArr[i]['image_url'] + \"'> \"
										+ \"<span class = 'entry-title'>\" + gamesArr[i]['name'] + \"</span> \"
										+ ratingSpan
										+ \"<span class = 'entry-description'> \" + gamesArr[i]['description'] + \"</span> \"
										+ playsSpan;
										gamesHTML += \"</span></a>\";
									}
									games.innerHTML = gamesHTML;
									games.style.visibility = 'visible';
								}
							}
							catch(e) {
								games.innerHTML = '<span class=\"box-content-error-display-force\">' + errorText + '</span>';
								games.style.opacity = 1;
								console.log(e);
							}
						}
						else {
							games.innerHTML = errorText;
						}
					}
				};
				xhttp.open('GET', '/ws/loadrandom.php?limit=$limit', true);
				xhttp.send();
			}
			";
		}
		$top_games .= "</div></div>";
	}
	catch(Exception $e) {
		$top_games_js = "";
		$top_games = $e->getMessage();
	}
	
?>