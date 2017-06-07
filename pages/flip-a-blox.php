<?php
	if(!isset($routed)) {
		throw new Exception($direct_access_message);
	}
	
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_flipablox");
	$update_str = "DELETE FROM levels WHERE id = 31";
	$update_statement = $mysqli->prepare($update_str);
	$update_statement->execute();
	if(mysqli_stmt_error($update_statement) != "") {
		echo $error_val;
		$mysqli->close();
		exit();
	}
	$update_statement->close();
	
	$mysqli->close();
	
	$display_title = "Flip-a-Blox";
	$display_description = "The privacy policy and other information for the Game 103 game, Flip-a-Blox.";
	$display_page = <<<HTML
	<div class='box-content box-content'>
		<div class='box-content-title'>$display_title</div>
		<div class='box-content-container'>
			<a href='https://itunes.apple.com/us/app/flip-a-blox/id1241934713' target='_blank' onclick="logInteraction('https://itunes.apple.com/us/app/flip-a-blox/id1241934713')">
				<img src='/images/banners/flip-a-blox.png'/>
			</a>
			<br/>
			<a href='https://play.google.com/store/apps/details?id=net.game103.flipablox' target='_blank' onclick="logInteraction('https://play.google.com/store/apps/details?id=net.game103.flipablox')">
				<img style='height: 50px' src='/images/banners/googleplay.png'/>
			</a>
			<br/>
			<a href='/game/flip-a-blox'>Play online</a>
			<p>Thanks for playing the Game 103 game, Flip-a-Blox. On this page you will a detailed account of how your data is handled, and a way to contact the developer with any questions that you have.</p>
			<h3>Privacy Policy</h3>
			<p>
				Flip-a-Blox stores information about levels that you upload, and the levels that you play online.
				When you upload a level, level-specific information is stored (i.e. tiles, background color) along with your Facebook user ID if
				you choose the option to connect your Facebook account to Flip-a-Blox. Your Facebook ID is stored with your level, so that your friends
				can find your levels when using the 'Find Levels Online' feature. They can choose to log into Facebook to find levels by their Facebook
				friends. Facebook will provide a list of their friends' user IDs, and then Game 103 will return a list of all the levels that are associated
				with their friends' user IDs - including your levels. A Facebook user ID simply allows one to identify your Facebook profile. 
				Your Facebook user ID is not shared by Game 103 and only used for allowing you and your Facebook friends to find each others levels.<br/>
				Additionally, when you play an online level, the fact that you played it is recorded. This is to allow levels to be sorted by popularity. When your play is recorded,
				your IP address is also stored to ensure that one can not manipulate the popularity rankings by playing the same level over and over.<br/>
				No other information about you, the user, is stored when playing Flip-a-Blox. If you do not upload levels with Flip-a-Blox connected to Facebook,
				and you do not play levels online, no data about you is collected.
			</p>
			<h3>Contact</h3>
			<p>To contact me, James Grams, one of the developers of Flip-a-Blox, send an email to <a href = "mailto:james@game103.net">james@game103.net</a>. Thanks for playing!</p>
			<h3>Credits and other information</h3>
			<p><b>Music, levels, and desgin</b>: Kasey Mann</p>
			<p><b>Programming and concept</b>: James Grams</p>
			<p><b>Product Testing</b>: Kasey Mann, James Grams</p>
			<p><b>Sound effects</b>: <a href="http://freesfx.co.uk">freesfx.co.uk</a></p>
			<p><b>Font (Abril Fatface)</b>: TypeTogether</p>
		</div>
	</div>
HTML;
	$display_javascript = "
	// Log an interaction
	function logInteraction(url) {
		var xhttp = new XMLHttpRequest();
		xhttp.open('GET', '/ws/addview.php?type=apps&url_name='+url, true);
		xhttp.send();
	}
	
	window.onload = function() {
	
	}
	";
?>