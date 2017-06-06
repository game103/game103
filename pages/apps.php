<?php
	if(!isset($routed)) {
		throw new Exception($direct_access_message);
	}
	
	$display_page = "";
	
	// Connect to database
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
	if (mysqli_connect_errno()) {
		$mysqli->close();
		throw new Exception($mysql_message);
	}
	
	// Create the SQL Statement
	$select_str = "SELECT apps.name as name, apps.description, apps.url_name, apps.image_url, apps.store_url_android, apps.store_url_apple, apps.type FROM apps ORDER BY added_date ASC";
			
	$select_statement = $mysqli->prepare($select_str);
	
	// Exexecute the SQL Statement
	$select_statement->execute();
	if(mysqli_stmt_error($select_statement) != "") {
		throw new Exception($mysql_message);
		$mysqli->close();
		exit();
	}
	$select_statement->bind_result($name, $description, $url_name, $image_url, $store_url_android, $store_url_apple, $type);
	
	// Create the apps
	while($select_statement->fetch()) {
		$results = true;
		
		// Escape the quotes in the name of the entry
		$name = htmlentities($name, ENT_QUOTES);

		$location;
		$app_store_logo = "";
		if($url_name === NULL) {
			$location = $store_url;
		}
		else {
			$location = "/app/$url_name";
		}
		if($type == "iOS" || $type == "Both") {
			$app_store_logo .= "<span onclick='openURL(event, \"$store_url_apple\")' style=\"position:absolute;top:0;right:0;display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/images/badges/en-us/badge_appstore-sm.svg) no-repeat;width:61px;height:15px;\"></span>
			</span>";
		}
		else if($type == "Android" || $type == "Both") {
			$app_store_logo .= "<span onclick='openURL(event, \"$store_url_android\")' style=\"position:absolute;top:0;right:0;display:inline-block;overflow:hidden;width:80px;height:31px;\">
				<img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'
				style ='height:100%;width:100%'/>
			</span>";
		}
		
		$display_page .= "<a href=\"$location\" onclick='openURL(event, \"$location\")' class = 'entry-link'>
		<span class = 'entry-item'>
		<img alt = '$name icon' src = '$image_url'>
		<span class = 'entry-title'>$name</span>";
		$display_page .= "<span class = 'entry-description'> $description</span>
		$app_store_logo
		</a>";
	}
	
	$select_statement->close();
		
	$mysqli->close();
	
	$display_title = "Mobile Apps";
	$display_page = "
	<div class='box-content'>
		<div class='box-content-title'>$display_title</div>
		<div class='box-content-container'>
			$display_page
		</div>
	</div>
	";
	$display_description = "A listing of family-friendly mobile games that Game 103 has developed for iOS.";
	$display_javascript = "
	// Open URL
	function openURL(event, url) {
		event.preventDefault();
		window.location.href = url;
		event.stopPropagation();
	}
	";
?>