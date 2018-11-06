<?php

error_reporting(E_ERROR);
set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");

require_once( 'Constants.class.php');

$connect = mysql_connect( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD) or die("Could not connect");
mysql_select_db("hallaby_housekey") or die("Could not find database");

$email = $_POST['email'];
$email = mysql_real_escape_string($email);

if ($email)
{

$query = mysql_query("SELECT * FROM userlist WHERE email='$email'");

$numrows = mysql_num_rows($query);


//if the username exists (numrows) then itll check if its valid
if($numrows!=0)
{
	while ($row = mysql_fetch_assoc($query))
	{
		$username= $row['username'];
		$password = $row['password'];
	}
	$to = "$email";
	$subject = "Grand Estuary Username/Password Recovery";
	$headers = "From: james@game103.net\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
	$message = "<html>Here is your Grand Estuary Username and Password<p><b>Username:</b> $username</p><p><b>Password:</b> $password</p><p>Thanks for Playing!</p><p>James</p></html>";
	$send=mail($to,$subject,$message,$headers);
		echo("Your Username and Password have been sent to your email.");
}
else
	die("Sorry, No account matches that email in our database.");

}
?>

<html>
<head> 
<title>Grand Estuary Username/Password Recovery System</title>
</head>
<h1>Grand Estuary Username/Password Recovery System</h1>
	<form action='PasswordRecover.php' method='POST'>
		E-Mail: <input type='text' name='email'><br>
		<input type='submit' value='Retrieve Password'>
	</form>
</html>
