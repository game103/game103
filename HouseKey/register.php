<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$email = trim($_POST['email']);
$date = date("Y-m-d");
$job = $_POST['job'];

	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
			mysql_select_db("hallaby_housekey");

			$query = mysql_query
			("SELECT * FROM userlist WHERE username = '$username'");
			
			if (mysql_num_rows($query) > 0) {
			$output = "Sorry, that username has already been taken";
			}

			else
			{
			$output = "Congratulations! You're account was created!";
			$queryreg = mysql_query("
			
			INSERT INTO userlist VALUES ('$username','$password','$email','$date');
			
			");
			$queryreg2 = mysql_query("
			
			INSERT INTO  `variables` (
			`username` ,
			`password` ,
			`room` ,
			`message` ,
			`moneys` ,
			`wood` ,
			`fish` ,
			`cotton` ,
			`xp` ,
			`attack` ,
			`defense` ,
			`health` ,
			`job` ,
			`speed` ,
			`item1` ,
			`item2` ,
			`item3` ,
			`item4` ,
			`item5` ,
			`item6` ,
			`item7` ,
			`item8` ,
			`item9` ,
			`item10` ,
			`headequipped` ,
			`bodyequipped` ,
			`lefthandequipped` ,
			`righthandequipped` ,
			`legsequipped` ,
			`leftfootequipped` ,
			`rightfootequipped` ,
			`x` ,
			`y` ,
			`battlewith` ,
			`power`,
			`ready`,
			`parcel`
			)
			VALUES (
			'$username',  '$password',  '',  '',  '500',  '0',  '0',  '0',  '0',  '5',  '5',  '10', '$job', '0',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  'None',  '400',  '300',  'None',  '0', '0', 'None'
			);
			
			");
			$queryreg3 = mysql_query("
			
			INSERT INTO `house` (`username`, `hi1`, `hi2`, `hi3`, `hi4`, `hi5`, `hi6`, `hi7`, `hi8`, `hi9`, `hi10`, `hi11`, `hi12`, `hi13`, `hi14`, `hi15`, `hi16`, `hi17`, `hi18`, `hi19`, `hi20`, `hi21`, `hi22`, `hi23`, `hi24`, `hi25`, `hi26`, `hi27`, `hi28`, `hi29`, `hi30`, `hi31`, `hi32`, `hi33`, `hi34`, `hi35`, `hi36`, `hi37`, `hi38`, `hi39`, `hi40`, `hi41`, `hi42`, `hi43`, `hi44`, `hi45`, `hi46`, `hi47`, `hi48`, `hi49`, `hi50`, `hi51`, `hi52`, `hi53`, `hi54`, `hi55`, `hi56`, `hi57`, `hi58`, `hi59`, `hi60`, `hi61`, `hi62`, `hi63`, `hi64`, `1x`, `2x`, `3x`, `4x`, `5x`, `6x`, `7x`, `8x`, `9x`, `10x`, `11x`, `12x`, `13x`, `14x`, `15x`, `16x`, `17x`, `18x`, `19x`, `20x`, `21x`, `22x`, `23x`, `24x`, `25x`, `26x`, `27x`, `28x`, `29x`, `30x`, `31x`, `32x`, `33x`, `34x`, `35x`, `36x`, `37x`, `38x`, `39x`, `40x`, `41x`, `42x`, `43x`, `44x`, `45x`, `46x`, `47x`, `48x`, `49x`, `50x`, `51x`, `52x`, `53x`, `54x`, `55x`, `56x`, `57x`, `58x`, `59x`, `60x`, `61x`, `62x`, `63x`, `64x`, `1y`, `2y`, `3y`, `4y`, `5y`, `6y`, `7y`, `8y`, `9y`, `10y`, `11y`, `12y`, `13y`, `14y`, `15y`, `16y`, `17y`, `18y`, `19y`, `20y`, `21y`, `22y`, `23y`, `24y`, `25y`, `26y`, `27y`, `28y`, `29y`, `30y`, `31y`, `32y`, `33y`, `34y`, `35y`, `36y`, `37y`, `38y`, `39y`, `40y`, `41y`, `42y`, `43y`, `44y`, `45y`, `46y`, `47y`, `48y`, `49y`, `50y`, `51y`, `52y`, `53y`, `54y`, `55y`, `56y`, `57y`, `58y`, `59y`, `60y`, `61y`, `62y`, `63y`, `64y`) VALUES ('$username', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', 'None', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50', '50');
			
			");
			}
			echo "&output=$output";
?>			