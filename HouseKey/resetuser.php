<html>
<body>

<form action = "resetuser.php"  method = "post">
<input type = "text" name = "username">
<input type = "submit">

<?php

	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
		
	// Require modules
	require_once( 'Constants.class.php');

	$user = $_POST['username'];
	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$user'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET room='None' WHERE username='$user'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();

?>

</body>
</html>