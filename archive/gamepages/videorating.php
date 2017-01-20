<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<?php
	$vote = $_POST['vote'];
	$voteValue = $_POST ['voteValue'];
	$urlid = $_GET['urlid'];

	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_videos");

	//get current totalScore and Votes
	$str = "SELECT * FROM entries WHERE urlid = '$urlid'";
	$displayquery = mysql_query($str);

	while ($rows = mysql_fetch_array($displayquery)):
		
		$totalScore = $rows['totalScore'];
		$totalVotes = $rows['totalVotes'];
		
	endwhile;
	
	if($totalVotes > 0) {
		$rating = $totalScore / $totalVotes;
		$rating = round($rating,2);
	}
	else {
		$rating = "-";
	}

	if($vote and $voteValue > 0) {
		$totalVotes = $totalVotes + 1;
		$totalScore = $totalScore + $voteValue;
		
		$rating = (double)$totalScore / (double)$totalVotes;
		$rating = round($rating,2);
		
		//Update total score and votes
		$query = mysql_query("UPDATE entries SET totalVotes = '$totalVotes', totalScore = '$totalScore', rating = '$rating' WHERE urlid = '$urlid'");
		echo("<div scrolling = 'no' seamless = 'seamless' style='background-color:#0066FF;color:#66CCFF;text-align:center;font-family:Tahoma;position:absolute;top:0px;left:0px;width:220px;min-width:220px;max-width:220px;'><b>Rating</b><br>$rating<br>Total Votes: $totalVotes<br>Thanks for voting!</div>");
	}
	else {
		echo(" 
		<div scrolling = 'no' seamless = 'seamless' style='background-color:#0066FF;color:#66CCFF;text-align:center;font-family:Tahoma;position:absolute;top:0px;left:0px;width:220px;min-width:220px;max-width:220px;'>
			<b>Rating</b><br>$rating<br>Total Votes: $totalVotes
			<form action = 'videorating.php?urlid=$urlid' method = 'POST'>
				1<input type = 'radio' name = 'voteValue' value = '1'>
				2<input type = 'radio' name = 'voteValue' value = '2'>
				3<input type = 'radio' name = 'voteValue' value = '3'>
				4<input type = 'radio' name = 'voteValue' value = '4'>
				5<input type = 'radio' name = 'voteValue' value = '5'>
				<input type = 'hidden' name = 'urlid' value = '$urlid'><br>
				<input type = 'submit' name = 'vote' value = 'Vote!'>
			</form>	
		</div>
		");
	}
?>