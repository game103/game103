<?php
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');

	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_resources");
?>

<html>
<body>

<h1>Add a resource</h1>

<form action = "resource.php" method = "POST" enctype = "multipart/form-data">
Name: <input type = "text" name = "name"><br>
URL: <input type = "text" name = "url"><br>
Description: <br><textarea name = "description"></textarea><br>
Image URL: <input type = "text" name = "image_url"><br>
or Image file <input type = "file" name = "imagefile_upload"><br><br>
Category 1: 
<select name = "cat1">
	<option value = ""></option>
	<option value = "1">Audio</option>
	<option value = "2">Games</option>
	<option value = "3">General</option>
	<option value = "4">Images</option>
	<option value = "5">Video</option>
	<option value = "6">Web</option>
    <option value = "25">Management</option>
    <option value = "26">Editor</option>
    <option value = "27">Language</option>
    <option value = "28">Api</option>
    <option value = "29">Education</option>
    <option value = "30">Mobile</option>
</select><br><br>
Category 2:
<select name = "cat2">
	<option value = ""></option>
	<option value = "1">Audio</option>
	<option value = "2">Games</option>
	<option value = "3">General</option>
	<option value = "4">Images</option>
	<option value = "5">Video</option>
	<option value = "6">Web</option>
    <option value = "25">Management</option>
    <option value = "26">Editor</option>
    <option value = "27">Language</option>
    <option value = "28">Api</option>
    <option value = "29">Education</option>
    <option value = "30">Mobile</option>
</select><br><br>
<input type = "submit" value = "submit" name = "submit"><br>
</form>

<?php

	$name = $_POST['name'];
	$url_name = str_replace(' ','',$name);
	$url_name = str_replace('&','',$url_name);
	$url_name = str_replace("'","",$url_name);
	$url_name = strtolower($url_name);
	$url = $_POST['url'];
	$description = $_POST['description'];
	$image_url = $_POST['image_url'];
	$submit = $_POST['submit'];

	$cat1 = $_POST['cat1'];
	$cat2 = $_POST['cat2'];
	
	if(!$cat1) {
		die("Please select a category");
	}
	if($cat2 == "") {
		unset($cat2);
	}

	if($submit) {
		$imagefile_target_dir = "../images/icons/resources/";
		//$imagefile_target_file = $imagefile_target_dir. basename($_FILES["imagefile_upload"]["name"]);
		$imagefile_target_file = $imagefile_target_dir. $url_name . "." . pathinfo($_FILES["imagefile_upload"]["name"], PATHINFO_EXTENSION);

		
		//move the files to the correct location
		//if the upload is successful that is the url
		if(move_uploaded_file($_FILES["imagefile_upload"]["tmp_name"],$imagefile_target_file)) {
			$image_url = $imagefile_target_file;
			echo "uploading the image was a success.<br>";
		}
		
		$image_url = substr($image_url, 2);
		
		$sql = "INSERT INTO entries(name, url, description, image_url) VALUES ('$name','$url','$description','$image_url')";
		$mysqli->query($sql);
		$id = $mysqli->insert_id;
		
		$sql = "INSERT INTO categories_entries(category_id, entry_id) VALUES ('$cat1','$id')";
		$mysqli->query($sql);
		if(isset($cat2)) {
			$sql = "INSERT INTO categories_entries(category_id, entry_id) VALUES ('$cat2','$id')";
			$mysqli->query($sql);
		}
		
		//$query = mysql_query("INSERT INTO entries VALUES ('$gameid','$embedcode','$description','$controls','$image_url','$date','0','0','0','$urlid','$cat1','$cat2')");
		
		/*$file = '../sitemap.xml';
		$xml = simplexml_load_file($file);

		$urlset = $xml;

		$url = $urlset->addChild('url');
		$url->addChild('loc', 'http://game103.net/game.php?urlid=' . $urlid);
		$url->addChild('changefreq', 'daily');
		$url->addChild('priority', '0.7');

		$xml->asXML($file);
		
		echo "game added :-)";*/
	}
		$mysqli->close();

	?>

</body>
</html>
