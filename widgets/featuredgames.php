<?php 
	$error_val = 'Sorry, there was an error loading top games. Please try again later.';
	$featured_games = '<div class="box-content" id="featured-games"><div class="box-content-title">Featured Games</div>';
	//$featured_games .= '<div class="box-hidden-subheading">These are some of the editor\'s favorite games!</div>';
	$featured_games .= '<button class="box-content-button-selected box-content-button" id="editors-pick-button" onclick="changeViewFeatured(\'editors-pick\')">Editor\'s Choices</button>';
	$featured_games .= '<button class="box-content-button" id="daily-game-button" onclick="changeViewFeatured(\'daily-game\')">Daily Games</button>';
	$featured_games .= "<div class='box-content-container'>";
	$all_games = [];
	$featured_games_js = "
	// The currently shown div
	var featuredCurrent = 'editors-pick';
	// Change which div is shown
	function changeViewFeatured(view) {
		if(view == featuredCurrent && view == 'random') {
			updateRandom();
		}
		document.getElementById(featuredCurrent + '-button').classList.remove('box-content-button-selected');
		document.getElementById(featuredCurrent + '-box').style.display='none';
		featuredCurrent = view;
		document.getElementById(featuredCurrent + '-button').classList.add('box-content-button-selected');
		document.getElementById(featuredCurrent + '-box').style.display='block';
	}
	";
	$limit = 3;
	
	// Connect to database
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
	if (mysqli_connect_errno()) {
		echo $error_val;
		$mysqli->close();
		exit();
	}

	$select_games_str = "
	SELECT * FROM (
	SELECT * FROM(
	SELECT entries.name, entries.url_name, entries.description, entries.image_url, 
	entries.rating, entries.added_date, FORMAT(entries.plays, 0), entries.plays, featured.added_date as featured_added_date
	FROM entries JOIN featured ON featured.entry_id = entries.id 
	ORDER BY featured.added_date DESC LIMIT $limit
	) AS main
	ORDER BY plays DESC, rating DESC
	) AS main_outer
	UNION ALL
	SELECT * FROM (
	SELECT entries.name, entries.url_name, entries.description, entries.image_url, 
	entries.rating, entries.added_date, FORMAT(entries.plays, 0), entries.plays, daily_game.added_date as featured_added_date
	FROM entries JOIN daily_game ON daily_game.entry_id = entries.id 
	ORDER BY daily_game.added_date DESC LIMIT $limit
	) AS daily";
	$select_games_statement = $mysqli->prepare($select_games_str);
	$select_games_statement->execute();
	if(mysqli_stmt_error($select_games_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$select_games_statement->bind_result($name, $url_name, $description, $image_url, $rating, $added_date, $plays, $numeric_entry_plays, $featured_added_date);
	
	//Put the games results into an array
	while ($select_games_statement->fetch()) {
		$games[0] = htmlentities($name, ENT_QUOTES);
		$games[1] = $url_name;
		$games[2] = $description;
		$games[3] = $image_url;
		$games[4] = $rating;
		$games[5] = $added_date;
		$games[6] = $plays;
		$games[7] = date("n/j/y", strtotime($featured_added_date));
		$all_games[] = $games;
	}
	
	$select_games_statement->close();
	
	$lines = 0;
	$total_entries = -1;
	
	//Nothing found
	if(count($all_games) != 6) {
		$featured_games =  "Sorry, no entries were found.";
	}
	else {
		$featured_games .= "<div class='box-content-box' id='editors-pick-box'>";
		//Make an entry
		for ($rows = 0; $rows < count($all_games); $rows++) {
			$name = $all_games[$rows][0];
			$url_name = $all_games[$rows][1];
			$description = $all_games[$rows][2];
			$image_url = $all_games[$rows][3];
			$rating = $all_games[$rows][4];
			$plays = $all_games[$rows][6];
			$featured_added_date = $all_games[$rows][7];

			if($plays == 1) {
				$plays_str = "$plays play";
			}
			else {
				$plays_str = "$plays plays";
			}
			
			if($rows == 3) {
				$featured_games .= "</div>";
				$featured_games .= "<div class='box-content-box' id='daily-game-box'>";
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
		$featured_games .= "</div>";
	}
	$featured_games .= "</div></div>";
	
?>