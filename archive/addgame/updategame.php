<html>
<body>

<form action = "updategame.php" method = "post">
Game ID: <input type = "text" name = "gameid"><br>
Field: <input type = "text" name = "field"><br>
New Value: <input type = "text" name = "newvalue"><br>
<input type = "submit" value = "submit" name = "submit"><br>
</form>

<?php

$gameid = $_POST['gameid'];
$field = $_POST['field'];
$newvalue = $_POST['newvalue'];

if($submit) {
	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_outsidegames");
		
	$query = mysql_query("UPDATE entries SET '$field'='$newvalue' WHERE gameid = '$gameid'");
	echo "game added :-)";
}

?>

</body>
</html>