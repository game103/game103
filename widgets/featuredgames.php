<?php 
	$error_val = 'Sorry, there was an error loading top games. Please try again later.';
	$featured_games = '<div class="box-content" id="featured-games"><div class="box-content-title">Featured Games</div>';
	$featured_games .= '<div class="box-hidden-subheading">These are some of the editor\'s favorite games!</div>';
	$featured_games .= "<div class='box-content-container'>";
	$all_games = [];
	$featured_games_js = "";
	$limit = 3;
	
	// Connect to database
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
	if (mysqli_connect_errno()) {
		echo $error_val;
		$mysqli->close();
		exit();
	}

	$select_games_str = "
	SELECT entries.name, entries.url_name, entries.description, entries.image_url, 
	entries.rating, entries.added_date, FORMAT(entries.plays, 0)
	FROM entries JOIN featured ON featured.entry_id = entries.id 
	ORDER BY plays DESC, rating DESC LIMIT $limit";
	$select_games_statement = $mysqli->prepare($select_games_str);
	$select_games_statement->execute();
	if(mysqli_stmt_error($select_games_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$select_games_statement->bind_result($name, $url_name, $description, $image_url, $rating, $added_date, $plays);
	
	//Put the games results into an array
	while ($select_games_statement->fetch()) {
		$games[0] = htmlentities($name, ENT_QUOTES);
		$games[1] = $url_name;
		$games[2] = $description;
		$games[3] = $image_url;
		$games[4] = $rating;
		$games[5] = $added_date;
		$games[6] = $plays;
		$all_games[] = $games;
	}
	
	$select_games_statement->close();
	
	$lines = 0;
	$total_entries = -1;
	
	//Nothing found
	if(count($all_games) != 3) {
		$featured_games =  "Sorry, no entries were found.";
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

			if($plays == 1) {
				$plays_str = "$plays play";
			}
			else {
				$plays_str = "$plays plays";
			}
			
			$featured_games .= "<a href = '/game/$url_name' class = 'entry-link'>";
			
			$rating_width = ($rating * 22) . 'px';
			
			//Echo the entry
			
			$featured_games .= "
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
		
	}
	$featured_games .= "</div></div>";
	
?>