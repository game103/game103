<html>
<body>

<form action = "testupload.php" method = "POST" enctype="multipart/form-data">
File to upload:<br>
<input type = "file" name = "fileToUpload">
<input type = "submit" value = "submit" name = "submit"><br>
</form>

</body>
</html>

<?php

$target_dir = "../gamepages/outsidegameswfs/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
if(isset($_POST["submit"])) {
	if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$target_file)) {
		echo "success";
	}
	else {
		echo "failure";
	}
}

?>