<?php

set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
// Require modules
require_once( 'Constants.class.php');

	$username = $_POST['username'];
	$trimmedusername = trim($username);
	$playwith = $_POST['playwith'];
	$playwith2 = $_POST['playwith2'];
	$playwith3 = $_POST['playwith3'];
	$playwith4 = $_POST['playwith4'];
	$playwith5 = $_POST['playwith5'];
	$playwith6 = $_POST['playwith6'];
	$playwith7 = $_POST['playwith7'];
	$playwith8 = $_POST['playwith8'];
	$playwith9 = $_POST['playwith9'];
	$playwith10 = $_POST['playwith10'];
	$playwith11 = $_POST['playwith11'];
	$playwith12 = $_POST['playwith12'];
	$trimmedroom = trim($room);
	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE username = '$playwith'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$user = $rows['username'];
		$x = $rows['x'];
		$y = $rows['y'];
		$xp = $rows['xp'];
		$message = $rows['message'];
		$speed = $rows['speed'];

	endwhile;
	
	$str2 = "SELECT * FROM variables WHERE username = '$playwith2'";
	$query2 = mysql_query($str2);
	
	$num2 = mysql_num_rows($query2);
	
	while($rows2 = mysql_fetch_array($query2)):

		$user2 = $rows2['username'];
		$x2 = $rows2['x'];
		$y2 = $rows2['y'];
		$xp2 = $rows2['xp'];
		$message2 = $rows2['message'];
		$speed2 = $rows2['speed'];

	endwhile;
	
	$str3 = "SELECT * FROM variables WHERE username = '$playwith3'";
	$query3 = mysql_query($str3);
	
	$num3 = mysql_num_rows($query3);
	
	while($rows3 = mysql_fetch_array($query3)):

		$user3 = $rows3['username'];
		$x3 = $rows3['x'];
		$y3 = $rows3['y'];
		$xp3 = $rows3['xp'];
		$message3 = $rows3['message'];
		$speed3 = $rows3['speed'];

	endwhile;
	
	$str4 = "SELECT * FROM variables WHERE username = '$playwith4'";
	$query4 = mysql_query($str4);
	
	$num4 = mysql_num_rows($query4);
	
	while($rows4 = mysql_fetch_array($query4)):

		$user4 = $rows4['username'];
		$x4 = $rows4['x'];
		$y4 = $rows4['y'];
		$xp4 = $rows4['xp'];
		$message4 = $rows4['message'];
		$speed4 = $rows4['speed'];

	endwhile;
	
	$str5 = "SELECT * FROM variables WHERE username = '$playwith5'";
	$query5 = mysql_query($str5);
	
	$num5 = mysql_num_rows($query5);
	
	while($rows5 = mysql_fetch_array($query5)):

		$user5 = $rows5['username'];
		$x5 = $rows5['x'];
		$y5 = $rows5['y'];
		$xp5 = $rows5['xp'];
		$message5 = $rows5['message'];
		$speed5 = $rows5['speed'];

	endwhile;
	
	$str6 = "SELECT * FROM variables WHERE username = '$playwith6'";
	$query6 = mysql_query($str6);
	
	$num6 = mysql_num_rows($query6);
	
	while($rows6 = mysql_fetch_array($query6)):

		$user6 = $rows6['username'];
		$x6 = $rows6['x'];
		$y6 = $rows6['y'];
		$xp6 = $rows6['xp'];
		$message6 = $rows6['message'];
		$speed6 = $rows6['speed'];

	endwhile;
	
	$str7 = "SELECT * FROM variables WHERE username = '$playwith7'";
	$query7 = mysql_query($str7);
	
	$num7 = mysql_num_rows($query7);
	
	while($rows7 = mysql_fetch_array($query7)):

		$user7 = $rows7['username'];
		$x7 = $rows7['x'];
		$y7 = $rows7['y'];
		$xp7 = $rows7['xp'];
		$message7 = $rows7['message'];
		$speed7 = $rows7['speed'];

	endwhile;
	
	$str8 = "SELECT * FROM variables WHERE username = '$playwith8'";
	$query8 = mysql_query($str8);
	
	$num8 = mysql_num_rows($query8);
	
	while($rows8 = mysql_fetch_array($query8)):

		$user8 = $rows8['username'];
		$x8 = $rows8['x'];
		$y8 = $rows8['y'];
		$xp8 = $rows8['xp'];
		$message8 = $rows8['message'];
		$speed8 = $rows8['speed'];

	endwhile;
	
	$str9 = "SELECT * FROM variables WHERE username = '$playwith9'";
	$query9 = mysql_query($str9);
	
	$num9 = mysql_num_rows($query9);
	
	while($rows9 = mysql_fetch_array($query9)):

		$user9 = $rows9['username'];
		$x9 = $rows9['x'];
		$y9 = $rows9['y'];
		$xp9 = $rows9['xp'];
		$message9 = $rows9['message'];
		$speed9 = $rows9['speed'];

	endwhile;
	
	$str10 = "SELECT * FROM variables WHERE username = '$playwith10'";
	$query10 = mysql_query($str10);
	
	$num10 = mysql_num_rows($query10);
	
	while($rows10 = mysql_fetch_array($query10)):

		$user10 = $rows10['username'];
		$x10 = $rows10['x'];
		$y10 = $rows10['y'];
		$xp10 = $rows10['xp'];
		$message10 = $rows10['message'];
		$speed10 = $rows10['speed'];

	endwhile;
	
	$str11 = "SELECT * FROM variables WHERE username = '$playwith11'";
	$query11 = mysql_query($str11);
	
	$num11 = mysql_num_rows($query11);
	
	while($rows11 = mysql_fetch_array($query11)):

		$user11 = $rows11['username'];
		$x11 = $rows11['x'];
		$y11 = $rows11['y'];
		$xp11 = $rows11['xp'];
		$message11 = $rows11['message'];
		$speed11 = $rows11['speed'];

	endwhile;
	
	$str12 = "SELECT * FROM variables WHERE username = '$playwith12'";
	$query12 = mysql_query($str12);
	
	$num12 = mysql_num_rows($query12);
	
	while($rows12 = mysql_fetch_array($query12)):

		$user12 = $rows12['username'];
		$x12 = $rows12['x'];
		$y12 = $rows12['y'];
		$xp12 = $rows12['xp'];
		$message12 = $rows12['message'];
		$speed12 = $rows12['speed'];

	endwhile;
	
	$str13 = "SELECT * FROM variables WHERE username = '$playwith13'";
	$query13 = mysql_query($str13);
	
	$num13 = mysql_num_rows($query13);
	
	while($rows13 = mysql_fetch_array($query13)):

		$user13 = $rows13['username'];
		$x13 = $rows13['x'];
		$y13 = $rows13['y'];
		$xp13 = $rows13['xp'];
		$message13 = $rows13['message'];
		$speed13 = $rows13['speed'];

	endwhile;
	
	$str14 = "SELECT * FROM variables WHERE username = '$playwith14'";
	$query14 = mysql_query($str14);
	
	$num14 = mysql_num_rows($query14);
	
	while($rows14 = mysql_fetch_array($query14)):

		$user14 = $rows14['username'];
		$x14 = $rows14['x'];
		$y14 = $rows14['y'];
		$xp14 = $rows14['xp'];
		$message14 = $rows14['message'];
		$speed14 = $rows14['speed'];

	endwhile;
	
	$str15 = "SELECT * FROM variables WHERE username = '$playwith15'";
	$query15 = mysql_query($str15);
	
	$num15 = mysql_num_rows($query15);
	
	while($rows15 = mysql_fetch_array($query15)):

		$user15 = $rows15['username'];
		$x15 = $rows15['x'];
		$y15 = $rows15['y'];
		$xp15 = $rows15['xp'];
		$message15 = $rows15['message'];
		$speed15 = $rows15['speed'];

	endwhile;
	
	$str16 = "SELECT * FROM variables WHERE username = '$playwith16'";
	$query16 = mysql_query($str16);
	
	$num16 = mysql_num_rows($query16);
	
	while($rows16 = mysql_fetch_array($query16)):

		$user16 = $rows16['username'];
		$x16 = $rows16['x'];
		$y16 = $rows16['y'];
		$xp16 = $rows16['xp'];
		$message16 = $rows16['message'];
		$speed16 = $rows16['speed'];

	endwhile;
	
	$str17 = "SELECT * FROM variables WHERE username = '$playwith17'";
	$query17 = mysql_query($str17);
	
	$num17 = mysql_num_rows($query17);
	
	while($rows17 = mysql_fetch_array($query17)):

		$user17 = $rows17['username'];
		$x17 = $rows17['x'];
		$y17 = $rows17['y'];
		$xp17 = $rows17['xp'];
		$message17 = $rows17['message'];
		$speed17 = $rows17['speed'];

	endwhile;
	
	$str18 = "SELECT * FROM variables WHERE username = '$playwith18'";
	$query18 = mysql_query($str18);
	
	$num18 = mysql_num_rows($query18);
	
	while($rows18 = mysql_fetch_array($query18)):

		$user18 = $rows18['username'];
		$x18 = $rows18['x'];
		$y18 = $rows18['y'];
		$xp18 = $rows18['xp'];
		$message18 = $rows18['message'];
		$speed18 = $rows18['speed'];

	endwhile;
	
	$str19 = "SELECT * FROM variables WHERE username = '$playwith19'";
	$query19 = mysql_query($str19);
	
	$num19 = mysql_num_rows($query19);
	
	while($rows19 = mysql_fetch_array($query19)):

		$user19 = $rows19['username'];
		$x19 = $rows19['x'];
		$y19 = $rows19['y'];
		$xp19 = $rows19['xp'];
		$message19 = $rows19['message'];
		$speed19 = $rows19['speed'];

	endwhile;
	
	$str20 = "SELECT * FROM variables WHERE username = '$playwith20'";
	$query20 = mysql_query($str20);
	
	$num20 = mysql_num_rows($query20);
	
	while($rows20 = mysql_fetch_array($query20)):

		$user20 = $rows20['username'];
		$x20 = $rows20['x'];
		$y20 = $rows20['y'];
		$xp20 = $rows20['xp'];
		$message20 = $rows20['message'];
		$speed20 = $rows20['speed'];

	endwhile;
	
	$str21 = "SELECT * FROM variables WHERE username = '$playwith21'";
	$query21 = mysql_query($str21);
	
	$num21 = mysql_num_rows($query21);
	
	while($rows21 = mysql_fetch_array($query21)):

		$user21 = $rows21['username'];
		$x21 = $rows21['x'];
		$y21 = $rows21['y'];
		$xp21 = $rows21['xp'];
		$message21 = $rows21['message'];
		$speed21 = $rows21['speed'];

	endwhile;
	
	$str22 = "SELECT * FROM variables WHERE username = '$playwith22'";
	$query22 = mysql_query($str22);
	
	$num22 = mysql_num_rows($query22);
	
	while($rows22 = mysql_fetch_array($query22)):

		$user22 = $rows22['username'];
		$x22 = $rows22['x'];
		$y22 = $rows22['y'];
		$xp22 = $rows22['xp'];
		$message22 = $rows22['message'];
		$speed22 = $rows22['speed'];

	endwhile;
	
	$str23 = "SELECT * FROM variables WHERE username = '$playwith23'";
	$query23 = mysql_query($str23);
	
	$num23 = mysql_num_rows($query23);
	
	while($rows23 = mysql_fetch_array($query23)):

		$user23 = $rows23['username'];
		$x23 = $rows23['x'];
		$y23 = $rows23['y'];
		$xp23 = $rows23['xp'];
		$message23 = $rows23['message'];
		$speed23 = $rows23['speed'];

	endwhile;
	
	$str24 = "SELECT * FROM variables WHERE username = '$playwith24'";
	$query24 = mysql_query($str24);
	
	$num24 = mysql_num_rows($query24);
	
	while($rows24 = mysql_fetch_array($query24)):

		$user24 = $rows24['username'];
		$x24 = $rows24['x'];
		$y24 = $rows24['y'];
		$xp24 = $rows24['xp'];
		$message24 = $rows24['message'];
		$speed24 = $rows24['speed'];

	endwhile;
	
	echo "&x=$x&y=$y&xp=$xp&message=$message&speed=$speed&x2=$x2&y2=$y2&xp2=$xp2&message2=$message2&speed2=$speed2&x3=$x3&y3=$y3&xp3=$xp3&message3=$message3&speed3=$speed3&x4=$x4&y4=$y4&xp4=$xp4&message4=$message4&speed4=$speed4&x5=$x5&y5=$y5&xp5=$xp5&message5=$message5&speed5=$speed5&x6=$x6&y6=$y6&xp6=$xp6&message6=$message6&speed6=$speed6&x7=$x7&y7=$y7&xp7=$xp7&message7=$message7&speed7=$speed7&x8=$x8&y8=$y8&xp8=$xp8&message8=$message8&speed8=$speed8&x9=$x9&y9=$y9&xp9=$xp9&message9=$message9&speed9=$speed9&x10=$x10&y10=$y10&xp10=$xp10&message10=$message10&speed10=$speed10&x11=$x11&y11=$y11&xp11=$xp11&message11=$message11&speed11=$speed11&x12=$x12&y12=$y12&xp12=$xp12&message12=$message12&speed12=$speed12&x13=$x13&y13=$y13&xp13=$xp13&message13=$message13&speed13=$speed13&x14=$x14&y14=$y14&xp14=$xp14&message14=$message14&speed14=$speed14&x15=$x15&y15=$y15&xp15=$xp15&message15=$message15&speed15=$speed15&x16=$x16&y16=$y16&xp16=$xp16&message16=$message16&speed16=$speed16&x17=$x17&y17=$y17&xp17=$xp17&message17=$message17&speed17=$speed17&x18=$x18&y18=$y18&xp18=$xp18&message18=$message18&speed18=$speed18&x19=$x19&y19=$y19&xp19=$xp19&message19=$message19&speed19=$speed19&x20=$x20&y20=$y20&xp20=$xp20&message20=$message20&speed20=$speed20&x21=$x21&y21=$y21&xp21=$xp21&message21=$message21&speed21=$speed21&x22=$x22&y22=$y22&xp22=$xp22&message22=$message22&speed22=$speed22&x23=$x23&y23=$y23&xp23=$xp23&message23=$message23&speed23=$speed23&x24=$x24&y24=$y24&xp24=$xp24&message24=$message24&speed24=$speed24";
	mysql_close();
?>