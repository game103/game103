<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

$username = $_POST['username'];
$trimmedusername = trim($username);
$password = $_POST['password'];
$trimmedpassword= trim($password);

$moneys=$_POST['moneys'];
$wood=$_POST['wood'];
$fish=$_POST['fish'];
$cotton=$_POST['cotton'];

$xp=$_POST['xp'];
$attack=$_POST['attack'];
$defense=$_POST['defense'];
$health=$_POST['health'];

$job=$_POST['job'];

$item1=$_POST['item1'];
$item2=$_POST['item2'];
$item3=$_POST['item3'];
$item4=$_POST['item4'];
$item5=$_POST['item5'];
$item6=$_POST['item6'];
$item7=$_POST['item7'];
$item8=$_POST['item8'];
$item9=$_POST['item9'];
$item10=$_POST['item10'];

$headequipped=$_POST['headequipped'];
$bodyequipped=$_POST['bodyequipped'];
$lefthandequipped=$_POST['lefthandequipped'];
$righthandequipped=$_POST['righthandequipped'];
$legsequipped=$_POST['legsequipped'];
$leftfootequipped=$_POST['leftfootequipped'];
$rightfootequipped=$_POST['rightfootequipped'];

$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$trimmedusername' AND password='$trimmedpassword'";
	$query = mysql_query($str);
	
	$insert = "UPDATE variables SET moneys='$moneys', wood='$wood', fish='$fish', cotton='$cotton', xp='$xp', attack='$attack', defense='$defense', health='$health', item1='$item1', item2='$item2', item3='$item3', item4='$item4', item5='$item5', item6='$item6', item7='$item7', item8='$item8', item9='$item9', item10='$item10', item1='$item1',	headequipped='$headequipped', bodyequipped='$bodyequipped', lefthandequipped='$lefthandequipped', righthandequipped='$righthandequipped', legsequipped='$legsequipped', leftfootequipped='$leftfootequipped', rightfootequipped='$rightfootequipped' WHERE username='$trimmedusername'";
	$insertquery = mysql_query($insert, $connect);
	mysql_close();
?>