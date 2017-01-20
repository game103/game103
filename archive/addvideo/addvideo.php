<html>
<body>

<form action = "addvideo.php" method = "POST" enctype = "multipart/form-data">
Name: <input type = "text" name = "videoid"><br>
Youtube code: <input type = "text" name = "string"><br>
Description: <textarea name = "description"></textarea><br>
Image URL: <input type = "text" name = "imageurl"><br><br>
or Image file: <input type = "file" name = "imagefileUpload"><br><br>
Category 1: 
<select name = "cat1">
	<option value = ""></option>
	<option value = "Comedy">Comedy</option>
	<option value = "Games">Games</option>
	<option value = "Instructional">Instructional</option>
	<option value = "Interesting">Interesting</option>
	<option value = "Musical">Musical</option>
	<option value = "Nature">Nature</option>
	<option value = "Story">Story</option>
</select><br><br>
Category 2:
<select name = "cat2">
	<option value = ""></option>
	<option value = "Comedy">Comedy</option>
	<option value = "Games">Games</option>
	<option value = "Instructional">Instructional</option>
	<option value = "Interesting">Interesting</option>
	<option value = "Musical">Musical</option>
	<option value = "Nature">Nature</option>
	<option value = "Story">Story</option>
</select><br><br>
Date (YYYY-Mo-Da) (No zeros before months or days): <input type = "text" name = "date"><br>
<input type = "submit" value = "submit" name = "submit"><br>
</form>

<?php

$videoid = $_POST['videoid'];
$string = $_POST['string'];
$description = $_POST['description'];
$imageurl = $_POST['imageurl'];
$submit = $_POST['submit'];
$date = $_POST['date'];
$urlid = str_replace(' ','',$videoid);
$urlid = str_replace('&','',$urlid);
$urlid = str_replace("'","",$urlid);
$urlid = strtolower($urlid);
$cat1 = $_POST['cat1'];
$cat2 = $_POST['cat2'];

if($submit) {
	$imagefile_target_dir = "../images/entryicons/outsidevideos/";
	$imagefile_target_file = $imagefile_target_dir. basename($_FILES["imagefileUpload"]["name"]);

	//move the files to the correct location
	//if the upload is successful that is the url
	if(move_uploaded_file($_FILES["imagefileUpload"]["tmp_name"],$imagefile_target_file)) {
		$imageurl = $imagefile_target_file;
		echo "uploading the image was a success.<br>";
	}
	
	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_videos");
		
	$query = mysql_query("INSERT INTO entries VALUES ('$videoid','$string','$description','$imageurl','$date','0','0','0','$urlid','$cat1','$cat2')");
	
	$file = '../sitemap.xml';
	$xml = simplexml_load_file($file);

	$urlset = $xml;

	$url = $urlset->addChild('url');
	$url->addChild('loc', 'http://game103.net/video.php?urlid=' . $urlid);
	$url->addChild('changefreq', 'daily');
	$url->addChild('priority', '0.5');

	$xml->asXML($file);
	
	echo "video added :-)";
}

?>

</body>
</html>