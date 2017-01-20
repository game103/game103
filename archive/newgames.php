<!-- Entries-->
<div class="embedded-entries">
	
	<?php 

		//Connect to database
		$connect = mysql_connect("localhost","hallaby","***REMOVED***");
		//mysql_select_db("hallaby_outsidegames");

		$str = "SELECT * FROM hallaby_outsidegames.entries ORDER BY date DESC, rating DESC LIMIT 6";
		$displayquery = mysql_query($str);
		$str2= "SELECT * FROM hallaby_videos.entries ORDER BY date DESC, rating DESC LIMIT 6";
		$displayquery2 = mysql_query($str2);
		
		$lines = 0;
		$zindex = 2;
		$totalentries = -1;
		$allEntriesAmount = 0;
		$prevDate = 0;
		
		//Put the results into arrays
		while ($games = mysql_fetch_array($displayquery)) {
			$games['type'] = 'game';
			$allgames[] = $games;
		}
		while ($videos = mysql_fetch_array($displayquery2)) {
			$videos['type'] = 'video';
			$allvideos[] = $videos;
		}
		
		//Now merge the two arrays by date
		$gamesCounter = 0;
		$videosCounter = 0;
		while($gamesCounter < count($allgames) && $videosCounter < count($allvideos)) {
			if(strtotime($allgames[$gamesCounter]['date']) >= strtotime($allvideos[$videosCounter]['date'])) {
				$content[] = $allgames[$gamesCounter];
				$gamesCounter ++;
			}
			else {
				$content[] = $allvideos[$videosCounter];
				$videosCounter ++;
			}
		}
		
		//Get info from database and make an entry
		for ($rows = 0; $rows < count($content); $rows++) {

			$gameid = $content[$rows][0];
			$urlid = $content[$rows]['urlid'];
			$description = $content[$rows]['description'];
			$imageurl = $content[$rows]['imageurl'];
			$totalScore = $content[$rows]['totalScore'];
			$totalVotes = $content[$rows]['totalVotes'];
			$exactDate = $content[$rows]['date'];
			$date = strtotime($content[$rows]['date']);
			$datePlusOne = strtotime($content[$rows + 1]['date']);
			$type = $content[$rows]['type'];
			
			//If the first item, display a header
			if($totalentries == -1) {
				$formattedDate = date("l, F jS",$date);
				echo "<h4>Content Added on ".$formattedDate."</h4>";
				$prevDate = $date;
			}
			//If the previous date does not match the current date, display a header
			else if($date != $prevDate) {
				$formattedDate = date("l, F jS",$date);
				echo "<h4>Content Added on ".$formattedDate."</h4>";
				$lines = 0;
				$prevDate = $date;
			}
			
			$daysCount ++;
			
			//Calculate rating	
			if($totalVotes > 0) {
				$rating = $totalScore / $totalVotes;
				$rating = round($rating,2);
			}
			else {
				$rating = "-";
			}		
			
			$totalentries = $totalentries + 1;
			
			if($type == 'game') {
				echo "<a href = 'game.php?urlid=$urlid' class = 'entry-link'>";
			}
			else {
				echo "<a href = 'video.php?urlid=$urlid' class = 'entry-link'>";
			}
			//Echo the entry	
			echo "
			<div class = 'entry-item' style='z-index:$zindex;'>
			<img alt = '$strippedgameid' src = '$imageurl'><br>
			<div class = 'entry-title'>$gameid</div>";
			if($rating > 3) {
				echo "<div class = 'entry-rating' style = 'color:#003300'>Rating: $rating</div>";
			}
			else if($rating > 2) {
				echo "<div class = 'entry-rating' style = 'font-size:80%;color:#CC6600'>Rating: $rating</div>";
			}
			else if($rating > 0) {
				echo "<div class = 'entry-rating' style = 'font-size:80%;color:#CC0000'>Rating: $rating</div>";
			}
			else {
				echo "<div class = 'entry-rating' style = 'font-size:80%;'>Rating: $rating</div>";
			}
			echo "
			<div class = 'entry-description'> $description</div>
			</div>
			</a>";

			$lines = $lines + 1;
		
		}
		
		//Nothing found
		if($totalentries == -1) {
			echo "Sorry, no entries were found.";
		}
	?>

</div>