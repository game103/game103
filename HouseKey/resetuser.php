<html>
<body>

<form action = "resetuser.php"  method = "post">
<input type = "text" name = "username">
<input type = "submit">

<?php
$user = $_POST['username'];
	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$user'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET room='None' WHERE username='$user'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();

?>

</body>
</html>