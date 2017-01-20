<html>
<head>
</head>
<body>

<h1>Add a game</h1>

<form action = "addgame.php" method = "POST" enctype = "multipart/form-data">
Name: <input type = "text" name = "gameid"><br>
URL (if not in gamefile below): <input type = "text" name = "url"><br>
or Game file: <input type = "file" name = "gamefileUpload"><br><br>
Width: <input type = "text" name = "width"><br>
Height: <input type = "text" name = "height"><br>
Description: <br><textarea name = "description"></textarea><br>
Controls: <br><textarea name = "controls"></textarea><br>
Image URL: <input type = "text" name = "imageurl"><br>
or Image file <input type = "file" name = "imagefileUpload"><br><br>
Category 1: 
<select name = "cat1">
	<option value = ""></option>
	<option value = "Adventure">Adventure</option>
	<option value = "Arcade">Arcade</option>
	<option value = "Driving">Driving</option>
	<option value = "Idle">Idle</option>
	<option value = "Platformer">Platformer</option>
	<option value = "Puzzle">Puzzle</option>
	<option value = "Simulation">Simulation</option>
	<option value = "Sports">Sports</option>
	<option value = "Tower Defense">Tower Defense</option>
	<option value = "Upgrade">Upgrade</option>
</select><br><br>
Category 2:
<select name = "cat2">
	<option value = ""></option>
	<option value = "Adventure">Adventure</option>
	<option value = "Arcade">Arcade</option>
	<option value = "Driving">Driving</option>
	<option value = "Idle">Idle</option>
	<option value = "Platformer">Platformer</option>
	<option value = "Puzzle">Puzzle</option>
	<option value = "Simulation">Simulation</option>
	<option value = "Sports">Sports</option>
	<option value = "Tower Defense">Tower Defense</option>
	<option value = "Upgrade">Upgrade</option>
</select><br><br>
Code (For custom): <textarea name = "embedcode"></textarea><br>
Date (YYYY-Mo-Da) (No zeros before months or days): <input type = "text" name = "date"><br>
<input type = "submit" value = "submit" name = "submit"><br>
</form>

<?php

$gameid = $_POST['gameid'];
$url = $_POST['url'];
$width = $_POST['width'];
$height = $_POST['height'];

$description = $_POST['description'];
$controls = $_POST['controls'];
$imageurl = $_POST['imageurl'];
$submit = $_POST['submit'];
$date = $_POST['date'];
$urlid = str_replace(' ','',$gameid);
$urlid = str_replace('&','',$urlid);
$urlid = str_replace("'","",$urlid);
$urlid = strtolower($urlid);

$cat1 = $_POST['cat1'];
$cat2 = $_POST['cat2'];

$embedcode = $_POST['embedcode'];
if($embedcode == '') {
	$embedcode = '<embed src = "'.$url.'" height = "'.$height.'" width = "'.$width.'" id = "game">
';
}


if($submit) {
	$gamefile_target_dir = "../gamepages/outsidegameswfs/";
	$gamefile_target_file = $gamefile_target_dir . basename($_FILES["gamefileUpload"]["name"]);

	$imagefile_target_dir = "../images/entryicons/outsidegames/";
	$imagefile_target_file = $imagefile_target_dir. basename($_FILES["imagefileUpload"]["name"]);

	
	//move the files to the correct location
	//if the upload is successful that is the url
	if(move_uploaded_file($_FILES["gamefileUpload"]["tmp_name"],$gamefile_target_file)) {
		$url = $gamefile_target_file;
		$embedcode = '<embed src = "'.$url.'" height = "'.$height.'" width = "'.$width.'" id = "game">';
		echo "uploading the game was a success.<br>";
	}
	if(move_uploaded_file($_FILES["imagefileUpload"]["tmp_name"],$imagefile_target_file)) {
		$imageurl = $imagefile_target_file;
		echo "uploading the image was a success.<br>";
	}
	
	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_outsidegames");
		
	$query = mysql_query("INSERT INTO entries VALUES ('$gameid','$embedcode','$description','$controls','$imageurl','$date','0','0','0','$urlid','$cat1','$cat2')");
	
	$file = '../sitemap.xml';
	$xml = simplexml_load_file($file);

	$urlset = $xml;

	$url = $urlset->addChild('url');
	$url->addChild('loc', 'http://game103.net/game.php?urlid=' . $urlid);
	$url->addChild('changefreq', 'daily');
	$url->addChild('priority', '0.7');

	$xml->asXML($file);
	
	echo "game added :-)";
}

?>

</body>
</html>