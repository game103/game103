<html>
<body>

<form action = "addwebtool.php" method = "POST" enctype = "multipart/form-data">
Link: <input type = "text" name = "link"><br>
Name: <input type = "text" name = "name"><br>
Description: <textarea name = "description"></textarea><br>
Image URL: <input type = "text" name = "imageurl"><br><br>
or Image file: <input type = "file" name = "imagefileUpload"><br><br>
Category 1: 
<select name = "cat1">
	<option value = ""></option>
	<option value = "Audio">Audio</option>
	<option value = "Game Development">Game Development</option>
	<option value = "Images">Images</option>
	<option value = "Other Programming">Other Programming</option>
	<option value = "Video">Video</option>
	<option value = "Web Programming">Web Programming</option>
</select><br><br>
Category 2:
<select name = "cat2">
	<option value = ""></option>
	<option value = "Audio">Audio</option>
	<option value = "Game Development">Game Development</option>
	<option value = "Images">Images</option>
	<option value = "Other Programming">Other Programming</option>
	<option value = "Video">Video</option>
	<option value = "Web Programming">Web Programming</option>
</select><br><br>
Date (YYYY-Mo-Da) (No zeros before months or days): <input type = "text" name = "date"><br>
<input type = "submit" value = "submit" name = "submit"><br>
</form>

<?php

$link = $_POST['link'];
$name = $_POST['name'];
$description = $_POST['description'];
$imageurl = $_POST['imageurl'];
$submit = $_POST['submit'];
$date = $_POST['date'];
$cat1 = $_POST['cat1'];
$cat2 = $_POST['cat2'];

if($submit) {
	$imagefile_target_dir = "../images/entryicons/outsidewebtools/";
	$imagefile_target_file = $imagefile_target_dir. basename($_FILES["imagefileUpload"]["name"]);

	//move the files to the correct location
	//if the upload is successful that is the url
	if(move_uploaded_file($_FILES["imagefileUpload"]["tmp_name"],$imagefile_target_file)) {
		$imageurl = $imagefile_target_file;
		echo "uploading the image was a success.<br>";
	}
	
	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_webtools");
		
	$query = mysql_query("INSERT INTO entries VALUES ('$link','$name','$description','$imageurl','$date','0','0','0','$cat1','$cat2')");
	
	echo "web tool added :-)";
}

?>

</body>
</html>