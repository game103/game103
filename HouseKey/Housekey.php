<?php

	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
		
	// Require modules
	require_once( 'Constants.class.php');

	$username = $_POST['username'];
	$trimmedusername = trim($username);
	$password = $_POST['password'];
	$trimmedpassword= trim($password);
	$room = $_POST['room'];
	$trimmedroom = trim($room);
	
	$connect = mysql_connect(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
	mysql_select_db("hallaby_housekey");

	$str = "SELECT * FROM variables WHERE BINARY username = '$trimmedusername' AND BINARY password='$trimmedpassword'";
	$query = mysql_query($str);
	
	$num = mysql_num_rows($query);
	
	while($rows = mysql_fetch_array($query)):

		$moneys = $rows['moneys'];
		$wood=$rows['wood'];
		$fish=$rows['fish'];
		$cotton=$rows['cotton'];

		$xp=$rows['xp'];
		$attack=$rows['attack'];
		$defense=$rows['defense'];
		$health=$rows['health'];
		
		$job=$rows['job'];

		$item1=$rows['item1'];
		$item2=$rows['item2'];
		$item3=$rows['item3'];
		$item4=$rows['item4'];
		$item5=$rows['item5'];
		$item6=$rows['item6'];
		$item7=$rows['item7'];
		$item8=$rows['item8'];
		$item9=$rows['item9'];
		$item10=$rows['item10'];

		$headequipped=$rows['headequipped'];
		$bodyequipped=$rows['bodyequipped'];
		$lefthandequipped=$rows['lefthandequipped'];
		$righthandequipped=$rows['righthandequipped'];
		$legsequipped=$rows['legsequipped'];
		$leftfootequipped=$rows['leftfootequipped'];
		$rightfootequipped=$rows['rightfootequipped'];

	endwhile;
	
	$str2 = "SELECT * FROM variables WHERE room = '$trimmedroom' AND username != '$trimmedusername'";
	$query2 = mysql_query($str2);
	
	$num2 = mysql_num_rows($query2);
	
	while($rows2 = mysql_fetch_array($query2)):

		$playwith = $rows2['username'];
		$c1moneys = $rows2['moneys'];
		$c1wood=$rows2['wood'];
		$c1fish=$rows2['fish'];
		$c1cotton=$rows2['cotton'];

		$c1xp=$rows2['xp'];
		$c1attack=$rows2['attack'];
		$c1defense=$rows2['defense'];
		$c1health=$rows2['health'];
		
		$c1job=$rows2['job'];

		$c1item1=$rows2['item1'];
		$c1item2=$rows2['item2'];
		$c1item3=$rows2['item3'];
		$c1item4=$rows2['item4'];
		$c1item5=$rows2['item5'];
		$c1item6=$rows2['item6'];
		$c1item7=$rows2['item7'];
		$c1item8=$rows2['item8'];
		$c1item9=$rows2['item9'];
		$c1item10=$rows2['item10'];

		$c1headequipped=$rows2['headequipped'];
		$c1bodyequipped=$rows2['bodyequipped'];
		$c1lefthandequipped=$rows2['lefthandequipped'];
		$c1righthandequipped=$rows2['righthandequipped'];
		$c1legsequipped=$rows2['legsequipped'];
		$c1leftfootequipped=$rows2['leftfootequipped'];
		$c1rightfootequipped=$rows2['rightfootequipped'];

	endwhile;
	
	$str3 = "SELECT * FROM variables WHERE room = '$trimmedroom' AND username != '$playwith' AND username != '$trimmedusername'";
	$query3 = mysql_query($str3);
	
	$num3 = mysql_num_rows($query3);
	
	while($rows3 = mysql_fetch_array($query3)):

		$playwith2 = $rows3['username'];
		$c2moneys = $rows3['moneys'];
		$c2wood=$rows3['wood'];
		$c2fish=$rows3['fish'];
		$c2cotton=$rows3['cotton'];

		$c2xp=$rows3['xp'];
		$c2attack=$rows3['attack'];
		$c2defense=$rows3['defense'];
		$c2health=$rows3['health'];
		
		$c2job=$rows3['job'];

		$c2item1=$rows3['item1'];
		$c2item2=$rows3['item2'];
		$c2item3=$rows3['item3'];
		$c2item4=$rows3['item4'];
		$c2item5=$rows3['item5'];
		$c2item6=$rows3['item6'];
		$c2item7=$rows3['item7'];
		$c2item8=$rows3['item8'];
		$c2item9=$rows3['item9'];
		$c2item10=$rows3['item10'];

		$c2headequipped=$rows3['headequipped'];
		$c2bodyequipped=$rows3['bodyequipped'];
		$c2lefthandequipped=$rows3['lefthandequipped'];
		$c2righthandequipped=$rows3['righthandequipped'];
		$c2legsequipped=$rows3['legsequipped'];
		$c2leftfootequipped=$rows3['leftfootequipped'];
		$c2rightfootequipped=$rows3['rightfootequipped'];

	endwhile;
	
	$str4 = "SELECT * FROM variables WHERE room = '$trimmedroom' AND username != '$playwith' AND username != '$playwith2' AND username != '$trimmedusername'";
	$query4 = mysql_query($str4);
	
	$num4 = mysql_num_rows($query4);
	
	while($rows4 = mysql_fetch_array($query4)):

		$playwith3 = $rows4['username'];
		$c3moneys = $rows4['moneys'];
		$c3wood=$rows4['wood'];
		$c3fish=$rows4['fish'];
		$c3cotton=$rows4['cotton'];

		$c3xp=$rows4['xp'];
		$c3attack=$rows4['attack'];
		$c3defense=$rows4['defense'];
		$c3health=$rows4['health'];
		
		$c3job=$rows4['job'];

		$c3item1=$rows4['item1'];
		$c3item2=$rows4['item2'];
		$c3item3=$rows4['item3'];
		$c3item4=$rows4['item4'];
		$c3item5=$rows4['item5'];
		$c3item6=$rows4['item6'];
		$c3item7=$rows4['item7'];
		$c3item8=$rows4['item8'];
		$c3item9=$rows4['item9'];
		$c3item10=$rows4['item10'];

		$c3headequipped=$rows4['headequipped'];
		$c3bodyequipped=$rows4['bodyequipped'];
		$c3lefthandequipped=$rows4['lefthandequipped'];
		$c3righthandequipped=$rows4['righthandequipped'];
		$c3legsequipped=$rows4['legsequipped'];
		$c3leftfootequipped=$rows4['leftfootequipped'];
		$c3rightfootequipped=$rows4['rightfootequipped'];

	endwhile;
	
	$str5 = "SELECT * FROM variables WHERE room = '$trimmedroom' AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3' AND username != '$trimmedusername'";
	$query5 = mysql_query($str5);
	
	$num5 = mysql_num_rows($query5);
	
	while($rows5 = mysql_fetch_array($query5)):

		$playwith4 = $rows5['username'];
		$c4moneys = $rows5['moneys'];
		$c4wood=$rows5['wood'];
		$c4fish=$rows5['fish'];
		$c4cotton=$rows5['cotton'];

		$c4xp=$rows5['xp'];
		$c4attack=$rows5['attack'];
		$c4defense=$rows5['defense'];
		$c4health=$rows5['health'];
		
		$c4job=$rows5['job'];

		$c4item1=$rows5['item1'];
		$c4item2=$rows5['item2'];
		$c4item3=$rows5['item3'];
		$c4item4=$rows5['item4'];
		$c4item5=$rows5['item5'];
		$c4item6=$rows5['item6'];
		$c4item7=$rows5['item7'];
		$c4item8=$rows5['item8'];
		$c4item9=$rows5['item9'];
		$c4item40=$rows5['item40'];

		$c4headequipped=$rows5['headequipped'];
		$c4bodyequipped=$rows5['bodyequipped'];
		$c4lefthandequipped=$rows5['lefthandequipped'];
		$c4righthandequipped=$rows5['righthandequipped'];
		$c4legsequipped=$rows5['legsequipped'];
		$c4leftfootequipped=$rows5['leftfootequipped'];
		$c4rightfootequipped=$rows5['rightfootequipped'];

	endwhile;
	
	$str6 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$trimmedusername'";
	$query6 = mysql_query($str6);
	
	$num6 = mysql_num_rows($query6);
	
	while($rows6 = mysql_fetch_array($query6)):

		$playwith5 = $rows6['username'];
		$c5moneys = $rows6['moneys'];
		$c5wood=$rows6['wood'];
		$c5fish=$rows6['fish'];
		$c5cotton=$rows6['cotton'];

		$c5xp=$rows6['xp'];
		$c5attack=$rows6['attack'];
		$c5defense=$rows6['defense'];
		$c5health=$rows6['health'];
		
		$c5job=$rows6['job'];

		$c5item1=$rows6['item1'];
		$c5item2=$rows6['item2'];
		$c5item3=$rows6['item3'];
		$c5item4=$rows6['item4'];
		$c5item5=$rows6['item5'];
		$c5item6=$rows6['item6'];
		$c5item7=$rows6['item7'];
		$c5item8=$rows6['item8'];
		$c5item9=$rows6['item9'];
		$c5item50=$rows6['item50'];

		$c5headequipped=$rows6['headequipped'];
		$c5bodyequipped=$rows6['bodyequipped'];
		$c5lefthandequipped=$rows6['lefthandequipped'];
		$c5righthandequipped=$rows6['righthandequipped'];
		$c5legsequipped=$rows6['legsequipped'];
		$c5leftfootequipped=$rows6['leftfootequipped'];
		$c5rightfootequipped=$rows6['rightfootequipped'];

	endwhile;
	
	$str7 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$trimmedusername'";
	$query7 = mysql_query($str7);
	
	$num7 = mysql_num_rows($query7);
	
	while($rows7 = mysql_fetch_array($query7)):

		$playwith6 = $rows7['username'];
		$c6moneys = $rows7['moneys'];
		$c6wood=$rows7['wood'];
		$c6fish=$rows7['fish'];
		$c6cotton=$rows7['cotton'];

		$c6xp=$rows7['xp'];
		$c6attack=$rows7['attack'];
		$c6defense=$rows7['defense'];
		$c6health=$rows7['health'];
		
		$c6job=$rows7['job'];

		$c6item1=$rows7['item1'];
		$c6item2=$rows7['item2'];
		$c6item3=$rows7['item3'];
		$c6item4=$rows7['item4'];
		$c6item5=$rows7['item5'];
		$c6item6=$rows7['item6'];
		$c6item7=$rows7['item7'];
		$c6item8=$rows7['item8'];
		$c6item9=$rows7['item9'];
		$c6item60=$rows7['item60'];

		$c6headequipped=$rows7['headequipped'];
		$c6bodyequipped=$rows7['bodyequipped'];
		$c6lefthandequipped=$rows7['lefthandequipped'];
		$c6righthandequipped=$rows7['righthandequipped'];
		$c6legsequipped=$rows7['legsequipped'];
		$c6leftfootequipped=$rows7['leftfootequipped'];
		$c6rightfootequipped=$rows7['rightfootequipped'];

	endwhile;
	
	$str8 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6' AND username != '$trimmedusername'";
	$query8 = mysql_query($str8);
	
	$num8 = mysql_num_rows($query8);
	
	while($rows8 = mysql_fetch_array($query8)):

		$playwith7 = $rows8['username'];
		$c7moneys = $rows8['moneys'];
		$c7wood=$rows8['wood'];
		$c7fish=$rows8['fish'];
		$c7cotton=$rows8['cotton'];

		$c7xp=$rows8['xp'];
		$c7attack=$rows8['attack'];
		$c7defense=$rows8['defense'];
		$c7health=$rows8['health'];
		
		$c7job=$rows8['job'];

		$c7item1=$rows8['item1'];
		$c7item2=$rows8['item2'];
		$c7item3=$rows8['item3'];
		$c7item4=$rows8['item4'];
		$c7item5=$rows8['item5'];
		$c7item6=$rows8['item6'];
		$c7item7=$rows8['item7'];
		$c7item8=$rows8['item8'];
		$c7item9=$rows8['item9'];
		$c7item70=$rows8['item70'];

		$c7headequipped=$rows8['headequipped'];
		$c7bodyequipped=$rows8['bodyequipped'];
		$c7lefthandequipped=$rows8['lefthandequipped'];
		$c7righthandequipped=$rows8['righthandequipped'];
		$c7legsequipped=$rows8['legsequipped'];
		$c7leftfootequipped=$rows8['leftfootequipped'];
		$c7rightfootequipped=$rows8['rightfootequipped'];

	endwhile;
	
	$str9 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$trimmedusername'";
	$query9 = mysql_query($str9);
	
	$num9 = mysql_num_rows($query9);
	
	while($rows9 = mysql_fetch_array($query9)):

		$playwith8 = $rows9['username'];
		$c8moneys = $rows9['moneys'];
		$c8wood=$rows9['wood'];
		$c8fish=$rows9['fish'];
		$c8cotton=$rows9['cotton'];

		$c8xp=$rows9['xp'];
		$c8attack=$rows9['attack'];
		$c8defense=$rows9['defense'];
		$c8health=$rows9['health'];
		
		$c8job=$rows9['job'];

		$c8item1=$rows9['item1'];
		$c8item2=$rows9['item2'];
		$c8item3=$rows9['item3'];
		$c8item4=$rows9['item4'];
		$c8item5=$rows9['item5'];
		$c8item6=$rows9['item6'];
		$c8item7=$rows9['item7'];
		$c8item8=$rows9['item8'];
		$c8item9=$rows9['item9'];
		$c8item80=$rows9['item80'];

		$c8headequipped=$rows9['headequipped'];
		$c8bodyequipped=$rows9['bodyequipped'];
		$c8lefthandequipped=$rows9['lefthandequipped'];
		$c8righthandequipped=$rows9['righthandequipped'];
		$c8legsequipped=$rows9['legsequipped'];
		$c8leftfootequipped=$rows9['leftfootequipped'];
		$c8rightfootequipped=$rows9['rightfootequipped'];

	endwhile;
	
	$str10 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$trimmedusername'";
	$query10 = mysql_query($str10);
	
	$num10 = mysql_num_rows($query10);
	
	while($rows10 = mysql_fetch_array($query10)):

		$playwith9 = $rows10['username'];
		$c9moneys = $rows10['moneys'];
		$c9wood=$rows10['wood'];
		$c9fish=$rows10['fish'];
		$c9cotton=$rows10['cotton'];

		$c9xp=$rows10['xp'];
		$c9attack=$rows10['attack'];
		$c9defense=$rows10['defense'];
		$c9health=$rows10['health'];
		
		$c9job=$rows10['job'];

		$c9item1=$rows10['item1'];
		$c9item2=$rows10['item2'];
		$c9item3=$rows10['item3'];
		$c9item4=$rows10['item4'];
		$c9item5=$rows10['item5'];
		$c9item6=$rows10['item6'];
		$c9item7=$rows10['item7'];
		$c9item8=$rows10['item8'];
		$c9item9=$rows10['item9'];
		$c9item90=$rows10['item90'];

		$c9headequipped=$rows10['headequipped'];
		$c9bodyequipped=$rows10['bodyequipped'];
		$c9lefthandequipped=$rows10['lefthandequipped'];
		$c9righthandequipped=$rows10['righthandequipped'];
		$c9legsequipped=$rows10['legsequipped'];
		$c9leftfootequipped=$rows10['leftfootequipped'];
		$c9rightfootequipped=$rows10['rightfootequipped'];

	endwhile;
	
	$str11 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$trimmedusername'";
	$query11 = mysql_query($str11);
	
	$num11 = mysql_num_rows($query11);
	
	while($rows11 = mysql_fetch_array($query11)):

		$playwith10 = $rows11['username'];
		$c10moneys = $rows11['moneys'];
		$c10wood=$rows11['wood'];
		$c10fish=$rows11['fish'];
		$c10cotton=$rows11['cotton'];

		$c10xp=$rows11['xp'];
		$c10attack=$rows11['attack'];
		$c10defense=$rows11['defense'];
		$c10health=$rows11['health'];
		
		$c10job=$rows11['job'];

		$c10item1=$rows11['item1'];
		$c10item2=$rows11['item2'];
		$c10item3=$rows11['item3'];
		$c10item4=$rows11['item4'];
		$c10item5=$rows11['item5'];
		$c10item6=$rows11['item6'];
		$c10item7=$rows11['item7'];
		$c10item8=$rows11['item8'];
		$c10item9=$rows11['item9'];
		$c10item100=$rows11['item100'];

		$c10headequipped=$rows11['headequipped'];
		$c10bodyequipped=$rows11['bodyequipped'];
		$c10lefthandequipped=$rows11['lefthandequipped'];
		$c10righthandequipped=$rows11['righthandequipped'];
		$c10legsequipped=$rows11['legsequipped'];
		$c10leftfootequipped=$rows11['leftfootequipped'];
		$c10rightfootequipped=$rows11['rightfootequipped'];

	endwhile;
	
	$str12 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$trimmedusername'";
	$query12 = mysql_query($str12);
	
	$num12 = mysql_num_rows($query12);
	
	while($rows12 = mysql_fetch_array($query12)):

		$playwith11 = $rows12['username'];
		$c11moneys = $rows12['moneys'];
		$c11wood=$rows12['wood'];
		$c11fish=$rows12['fish'];
		$c11cotton=$rows12['cotton'];

		$c11xp=$rows12['xp'];
		$c11attack=$rows12['attack'];
		$c11defense=$rows12['defense'];
		$c11health=$rows12['health'];
		
		$c11job=$rows12['job'];

		$c11item1=$rows12['item1'];
		$c11item2=$rows12['item2'];
		$c11item3=$rows12['item3'];
		$c11item4=$rows12['item4'];
		$c11item5=$rows12['item5'];
		$c11item6=$rows12['item6'];
		$c11item7=$rows12['item7'];
		$c11item8=$rows12['item8'];
		$c11item9=$rows12['item9'];
		$c11item110=$rows12['item110'];

		$c11headequipped=$rows12['headequipped'];
		$c11bodyequipped=$rows12['bodyequipped'];
		$c11lefthandequipped=$rows12['lefthandequipped'];
		$c11righthandequipped=$rows12['righthandequipped'];
		$c11legsequipped=$rows12['legsequipped'];
		$c11leftfootequipped=$rows12['leftfootequipped'];
		$c11rightfootequipped=$rows12['rightfootequipped'];

	endwhile;
	
	$str13 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11'  AND username != '$trimmedusername'";
	$query13 = mysql_query($str13);
	
	$num13 = mysql_num_rows($query13);
	
	while($rows13 = mysql_fetch_array($query13)):

		$playwith12 = $rows13['username'];
		$c12moneys = $rows13['moneys'];
		$c12wood=$rows13['wood'];
		$c12fish=$rows13['fish'];
		$c12cotton=$rows13['cotton'];

		$c12xp=$rows13['xp'];
		$c12attack=$rows13['attack'];
		$c12defense=$rows13['defense'];
		$c12health=$rows13['health'];
		
		$c12job=$rows13['job'];

		$c12item1=$rows13['item1'];
		$c12item2=$rows13['item2'];
		$c12item3=$rows13['item3'];
		$c12item4=$rows13['item4'];
		$c12item5=$rows13['item5'];
		$c12item6=$rows13['item6'];
		$c12item7=$rows13['item7'];
		$c12item8=$rows13['item8'];
		$c12item9=$rows13['item9'];
		$c12item120=$rows13['item120'];

		$c12headequipped=$rows13['headequipped'];
		$c12bodyequipped=$rows13['bodyequipped'];
		$c12lefthandequipped=$rows13['lefthandequipped'];
		$c12righthandequipped=$rows13['righthandequipped'];
		$c12legsequipped=$rows13['legsequipped'];
		$c12leftfootequipped=$rows13['leftfootequipped'];
		$c12rightfootequipped=$rows13['rightfootequipped'];

	endwhile;
	
	$str14 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$trimmedusername'";
	$query14 = mysql_query($str14);
	
	$num14 = mysql_num_rows($query14);
	
	while($rows14 = mysql_fetch_array($query14)):

		$playwith13 = $rows14['username'];
		$c13moneys = $rows14['moneys'];
		$c13wood=$rows14['wood'];
		$c13fish=$rows14['fish'];
		$c13cotton=$rows14['cotton'];

		$c13xp=$rows14['xp'];
		$c13attack=$rows14['attack'];
		$c13defense=$rows14['defense'];
		$c13health=$rows14['health'];
		
		$c13job=$rows14['job'];

		$c13item1=$rows14['item1'];
		$c13item2=$rows14['item2'];
		$c13item3=$rows14['item3'];
		$c13item4=$rows14['item4'];
		$c13item5=$rows14['item5'];
		$c13item6=$rows14['item6'];
		$c13item7=$rows14['item7'];
		$c13item8=$rows14['item8'];
		$c13item9=$rows14['item9'];
		$c13item130=$rows14['item130'];

		$c13headequipped=$rows14['headequipped'];
		$c13bodyequipped=$rows14['bodyequipped'];
		$c13lefthandequipped=$rows14['lefthandequipped'];
		$c13righthandequipped=$rows14['righthandequipped'];
		$c13legsequipped=$rows14['legsequipped'];
		$c13leftfootequipped=$rows14['leftfootequipped'];
		$c13rightfootequipped=$rows14['rightfootequipped'];

	endwhile;
	
	$str15 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$trimmedusername'";
	$query15 = mysql_query($str15);
	
	$num15 = mysql_num_rows($query15);
	
	while($rows15 = mysql_fetch_array($query15)):

		$playwith14 = $rows15['username'];
		$c14moneys = $rows15['moneys'];
		$c14wood=$rows15['wood'];
		$c14fish=$rows15['fish'];
		$c14cotton=$rows15['cotton'];

		$c14xp=$rows15['xp'];
		$c14attack=$rows15['attack'];
		$c14defense=$rows15['defense'];
		$c14health=$rows15['health'];
		
		$c14job=$rows15['job'];

		$c14item1=$rows15['item1'];
		$c14item2=$rows15['item2'];
		$c14item3=$rows15['item3'];
		$c14item4=$rows15['item4'];
		$c14item5=$rows15['item5'];
		$c14item6=$rows15['item6'];
		$c14item7=$rows15['item7'];
		$c14item8=$rows15['item8'];
		$c14item9=$rows15['item9'];
		$c14item140=$rows15['item140'];

		$c14headequipped=$rows15['headequipped'];
		$c14bodyequipped=$rows15['bodyequipped'];
		$c14lefthandequipped=$rows15['lefthandequipped'];
		$c14righthandequipped=$rows15['righthandequipped'];
		$c14legsequipped=$rows15['legsequipped'];
		$c14leftfootequipped=$rows15['leftfootequipped'];
		$c14rightfootequipped=$rows15['rightfootequipped'];

	endwhile;
	
	$str16 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$trimmedusername'";
	$query16 = mysql_query($str16);
	
	$num16 = mysql_num_rows($query16);
	
	while($rows16 = mysql_fetch_array($query16)):

		$playwith15 = $rows16['username'];
		$c15moneys = $rows16['moneys'];
		$c15wood=$rows16['wood'];
		$c15fish=$rows16['fish'];
		$c15cotton=$rows16['cotton'];

		$c15xp=$rows16['xp'];
		$c15attack=$rows16['attack'];
		$c15defense=$rows16['defense'];
		$c15health=$rows16['health'];
		
		$c15job=$rows16['job'];

		$c15item1=$rows16['item1'];
		$c15item2=$rows16['item2'];
		$c15item3=$rows16['item3'];
		$c15item4=$rows16['item4'];
		$c15item5=$rows16['item5'];
		$c15item6=$rows16['item6'];
		$c15item7=$rows16['item7'];
		$c15item8=$rows16['item8'];
		$c15item9=$rows16['item9'];
		$c15item150=$rows16['item150'];

		$c15headequipped=$rows16['headequipped'];
		$c15bodyequipped=$rows16['bodyequipped'];
		$c15lefthandequipped=$rows16['lefthandequipped'];
		$c15righthandequipped=$rows16['righthandequipped'];
		$c15legsequipped=$rows16['legsequipped'];
		$c15leftfootequipped=$rows16['leftfootequipped'];
		$c15rightfootequipped=$rows16['rightfootequipped'];

	endwhile;
	
	$str17 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$trimmedusername'";
	$query17 = mysql_query($str17);
	
	$num17 = mysql_num_rows($query17);
	
	while($rows17 = mysql_fetch_array($query17)):

		$playwith16 = $rows17['username'];
		$c16moneys = $rows17['moneys'];
		$c16wood=$rows17['wood'];
		$c16fish=$rows17['fish'];
		$c16cotton=$rows17['cotton'];

		$c16xp=$rows17['xp'];
		$c16attack=$rows17['attack'];
		$c16defense=$rows17['defense'];
		$c16health=$rows17['health'];
		
		$c16job=$rows17['job'];

		$c16item1=$rows17['item1'];
		$c16item2=$rows17['item2'];
		$c16item3=$rows17['item3'];
		$c16item4=$rows17['item4'];
		$c16item5=$rows17['item5'];
		$c16item6=$rows17['item6'];
		$c16item7=$rows17['item7'];
		$c16item8=$rows17['item8'];
		$c16item9=$rows17['item9'];
		$c16item160=$rows17['item160'];

		$c16headequipped=$rows17['headequipped'];
		$c16bodyequipped=$rows17['bodyequipped'];
		$c16lefthandequipped=$rows17['lefthandequipped'];
		$c16righthandequipped=$rows17['righthandequipped'];
		$c16legsequipped=$rows17['legsequipped'];
		$c16leftfootequipped=$rows17['leftfootequipped'];
		$c16rightfootequipped=$rows17['rightfootequipped'];

	endwhile;
	
	$str18 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$trimmedusername'";
	$query18 = mysql_query($str18);
	
	$num18 = mysql_num_rows($query18);
	
	while($rows18 = mysql_fetch_array($query18)):

		$playwith17 = $rows18['username'];
		$c17moneys = $rows18['moneys'];
		$c17wood=$rows18['wood'];
		$c17fish=$rows18['fish'];
		$c17cotton=$rows18['cotton'];

		$c17xp=$rows18['xp'];
		$c17attack=$rows18['attack'];
		$c17defense=$rows18['defense'];
		$c17health=$rows18['health'];
		
		$c17job=$rows18['job'];

		$c17item1=$rows18['item1'];
		$c17item2=$rows18['item2'];
		$c17item3=$rows18['item3'];
		$c17item4=$rows18['item4'];
		$c17item5=$rows18['item5'];
		$c17item6=$rows18['item6'];
		$c17item7=$rows18['item7'];
		$c17item8=$rows18['item8'];
		$c17item9=$rows18['item9'];
		$c17item170=$rows18['item170'];

		$c17headequipped=$rows18['headequipped'];
		$c17bodyequipped=$rows18['bodyequipped'];
		$c17lefthandequipped=$rows18['lefthandequipped'];
		$c17righthandequipped=$rows18['righthandequipped'];
		$c17legsequipped=$rows18['legsequipped'];
		$c17leftfootequipped=$rows18['leftfootequipped'];
		$c17rightfootequipped=$rows18['rightfootequipped'];

	endwhile;
	
	$str19 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$playwith17' AND username != '$trimmedusername'";
	$query19 = mysql_query($str19);
	
	$num19 = mysql_num_rows($query19);
	
	while($rows19 = mysql_fetch_array($query19)):

		$playwith18 = $rows19['username'];
		$c18moneys = $rows19['moneys'];
		$c18wood=$rows19['wood'];
		$c18fish=$rows19['fish'];
		$c18cotton=$rows19['cotton'];

		$c18xp=$rows19['xp'];
		$c18attack=$rows19['attack'];
		$c18defense=$rows19['defense'];
		$c18health=$rows19['health'];
	
		$c18job=$rows19['job'];

		$c18item1=$rows19['item1'];
		$c18item2=$rows19['item2'];
		$c18item3=$rows19['item3'];
		$c18item4=$rows19['item4'];
		$c18item5=$rows19['item5'];
		$c18item6=$rows19['item6'];
		$c18item7=$rows19['item7'];
		$c18item8=$rows19['item8'];
		$c18item9=$rows19['item9'];
		$c18item180=$rows19['item180'];

		$c18headequipped=$rows19['headequipped'];
		$c18bodyequipped=$rows19['bodyequipped'];
		$c18lefthandequipped=$rows19['lefthandequipped'];
		$c18righthandequipped=$rows19['righthandequipped'];
		$c18legsequipped=$rows19['legsequipped'];
		$c18leftfootequipped=$rows19['leftfootequipped'];
		$c18rightfootequipped=$rows19['rightfootequipped'];

	endwhile;
	
	$str20 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$playwith17' AND username != '$playwith18' AND username != '$trimmedusername'";
	$query20 = mysql_query($str20);
	
	$num20 = mysql_num_rows($query20);
	
	while($rows20 = mysql_fetch_array($query20)):

		$playwith19 = $rows20['username'];
		$c19moneys = $rows20['moneys'];
		$c19wood=$rows20['wood'];
		$c19fish=$rows20['fish'];
		$c19cotton=$rows20['cotton'];

		$c19xp=$rows20['xp'];
		$c19attack=$rows20['attack'];
		$c19defense=$rows20['defense'];
		$c19health=$rows20['health'];
		
		$c19job=$rows20['job'];

		$c19item1=$rows20['item1'];
		$c19item2=$rows20['item2'];
		$c19item3=$rows20['item3'];
		$c19item4=$rows20['item4'];
		$c19item5=$rows20['item5'];
		$c19item6=$rows20['item6'];
		$c19item7=$rows20['item7'];
		$c19item8=$rows20['item8'];
		$c19item9=$rows20['item9'];
		$c19item190=$rows20['item190'];

		$c19headequipped=$rows20['headequipped'];
		$c19bodyequipped=$rows20['bodyequipped'];
		$c19lefthandequipped=$rows20['lefthandequipped'];
		$c19righthandequipped=$rows20['righthandequipped'];
		$c19legsequipped=$rows20['legsequipped'];
		$c19leftfootequipped=$rows20['leftfootequipped'];
		$c19rightfootequipped=$rows20['rightfootequipped'];

	endwhile;
	
	$str21 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$playwith17' AND username != '$playwith18' AND username != '$playwith19' AND username != '$trimmedusername'";
	$query21 = mysql_query($str21);
	
	$num21 = mysql_num_rows($query21);
	
	while($rows21 = mysql_fetch_array($query21)):

		$playwith20 = $rows21['username'];
		$c20moneys = $rows21['moneys'];
		$c20wood=$rows21['wood'];
		$c20fish=$rows21['fish'];
		$c20cotton=$rows21['cotton'];

		$c20xp=$rows21['xp'];
		$c20attack=$rows21['attack'];
		$c20defense=$rows21['defense'];
		$c20health=$rows21['health'];
		
		$c20job=$rows21['job'];

		$c20item1=$rows21['item1'];
		$c20item2=$rows21['item2'];
		$c20item3=$rows21['item3'];
		$c20item4=$rows21['item4'];
		$c20item5=$rows21['item5'];
		$c20item6=$rows21['item6'];
		$c20item7=$rows21['item7'];
		$c20item8=$rows21['item8'];
		$c20item9=$rows21['item9'];
		$c20item200=$rows21['item200'];

		$c20headequipped=$rows21['headequipped'];
		$c20bodyequipped=$rows21['bodyequipped'];
		$c20lefthandequipped=$rows21['lefthandequipped'];
		$c20righthandequipped=$rows21['righthandequipped'];
		$c20legsequipped=$rows21['legsequipped'];
		$c20leftfootequipped=$rows21['leftfootequipped'];
		$c20rightfootequipped=$rows21['rightfootequipped'];

	endwhile;
	
	$str22 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$playwith17' AND username != '$playwith18' AND username != '$playwith19' AND username != '$playwith20' AND username != '$trimmedusername'";
	$query22 = mysql_query($str22);
	
	$num22 = mysql_num_rows($query22);
	
	while($rows22 = mysql_fetch_array($query22)):

		$playwith21 = $rows22['username'];
		$c21moneys = $rows22['moneys'];
		$c21wood=$rows22['wood'];
		$c21fish=$rows22['fish'];
		$c21cotton=$rows22['cotton'];

		$c21xp=$rows22['xp'];
		$c21attack=$rows22['attack'];
		$c21defense=$rows22['defense'];
		$c21health=$rows22['health'];
		
		$c21job=$rows22['job'];

		$c21item1=$rows22['item1'];
		$c21item2=$rows22['item2'];
		$c21item3=$rows22['item3'];
		$c21item4=$rows22['item4'];
		$c21item5=$rows22['item5'];
		$c21item6=$rows22['item6'];
		$c21item7=$rows22['item7'];
		$c21item8=$rows22['item8'];
		$c21item9=$rows22['item9'];
		$c21item210=$rows22['item210'];

		$c21headequipped=$rows22['headequipped'];
		$c21bodyequipped=$rows22['bodyequipped'];
		$c21lefthandequipped=$rows22['lefthandequipped'];
		$c21righthandequipped=$rows22['righthandequipped'];
		$c21legsequipped=$rows22['legsequipped'];
		$c21leftfootequipped=$rows22['leftfootequipped'];
		$c21rightfootequipped=$rows22['rightfootequipped'];

	endwhile;
	
	$str23 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$playwith17' AND username != '$playwith18' AND username != '$playwith19' AND username != '$playwith20' AND username != '$playwith21' AND username != '$trimmedusername'";
	$query23 = mysql_query($str23);
	
	$num23 = mysql_num_rows($query23);
	
	while($rows23 = mysql_fetch_array($query23)):

		$playwith22 = $rows23['username'];
		$c22moneys = $rows23['moneys'];
		$c22wood=$rows23['wood'];
		$c22fish=$rows23['fish'];
		$c22cotton=$rows23['cotton'];

		$c22xp=$rows23['xp'];
		$c22attack=$rows23['attack'];
		$c22defense=$rows23['defense'];
		$c22health=$rows23['health'];
		
		$c22job=$rows23['job'];

		$c22item1=$rows23['item1'];
		$c22item2=$rows23['item2'];
		$c22item3=$rows23['item3'];
		$c22item4=$rows23['item4'];
		$c22item5=$rows23['item5'];
		$c22item6=$rows23['item6'];
		$c22item7=$rows23['item7'];
		$c22item8=$rows23['item8'];
		$c22item9=$rows23['item9'];
		$c22item220=$rows23['item220'];

		$c22headequipped=$rows23['headequipped'];
		$c22bodyequipped=$rows23['bodyequipped'];
		$c22lefthandequipped=$rows23['lefthandequipped'];
		$c22righthandequipped=$rows23['righthandequipped'];
		$c22legsequipped=$rows23['legsequipped'];
		$c22leftfootequipped=$rows23['leftfootequipped'];
		$c22rightfootequipped=$rows23['rightfootequipped'];

	endwhile;
	
	$str24 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$playwith17' AND username != '$playwith18' AND username != '$playwith19' AND username != '$playwith20' AND username != '$playwith21' AND username != '$playwith22' AND username != '$trimmedusername'";
	$query24 = mysql_query($str24);
	
	$num24 = mysql_num_rows($query24);
	
	while($rows24 = mysql_fetch_array($query24)):

		$playwith23 = $rows24['username'];
		$c23moneys = $rows24['moneys'];
		$c23wood=$rows24['wood'];
		$c23fish=$rows24['fish'];
		$c23cotton=$rows24['cotton'];

		$c23xp=$rows24['xp'];
		$c23attack=$rows24['attack'];
		$c23defense=$rows24['defense'];
		$c23health=$rows24['health'];
		
		$c23job=$rows24['job'];

		$c23item1=$rows24['item1'];
		$c23item2=$rows24['item2'];
		$c23item3=$rows24['item3'];
		$c23item4=$rows24['item4'];
		$c23item5=$rows24['item5'];
		$c23item6=$rows24['item6'];
		$c23item7=$rows24['item7'];
		$c23item8=$rows24['item8'];
		$c23item9=$rows24['item9'];
		$c23item230=$rows24['item230'];

		$c23headequipped=$rows24['headequipped'];
		$c23bodyequipped=$rows24['bodyequipped'];
		$c23lefthandequipped=$rows24['lefthandequipped'];
		$c23righthandequipped=$rows24['righthandequipped'];
		$c23legsequipped=$rows24['legsequipped'];
		$c23leftfootequipped=$rows24['leftfootequipped'];
		$c23rightfootequipped=$rows24['rightfootequipped'];

	endwhile;
	
	$str25 = "SELECT * FROM variables WHERE room = '$trimmedroom'  AND username != '$playwith' AND username != '$playwith2' AND username != '$playwith3'  AND username != '$playwith4' AND username != '$playwith5' AND username != '$playwith6'  AND username != '$playwith7' AND username != '$playwith8' AND username != '$playwith9' AND username != '$playwith10' AND username != '$playwith11' AND username != '$playwith12' AND username != '$playwith13' AND username != '$playwith14' AND username != '$playwith15' AND username != '$playwith16' AND username != '$playwith17' AND username != '$playwith18' AND username != '$playwith19' AND username != '$playwith20' AND username != '$playwith21' AND username != '$playwith22' AND username != '$playwith23'  AND username != '$trimmedusername'";
	$query25 = mysql_query($str25);
	
	$num25 = mysql_num_rows($query25);
	
	while($rows25 = mysql_fetch_array($query25)):

		$playwith24 = $rows25['username'];
		$c24moneys = $rows25['moneys'];
		$c24wood=$rows25['wood'];
		$c24fish=$rows25['fish'];
		$c24cotton=$rows25['cotton'];

		$c24xp=$rows25['xp'];
		$c24attack=$rows25['attack'];
		$c24defense=$rows25['defense'];
		$c24health=$rows25['health'];
		
		$c24job=$rows25['job'];

		$c24item1=$rows25['item1'];
		$c24item2=$rows25['item2'];
		$c24item3=$rows25['item3'];
		$c24item4=$rows25['item4'];
		$c24item5=$rows25['item5'];
		$c24item6=$rows25['item6'];
		$c24item7=$rows25['item7'];
		$c24item8=$rows25['item8'];
		$c24item9=$rows25['item9'];
		$c24item240=$rows25['item240'];

		$c24headequipped=$rows25['headequipped'];
		$c24bodyequipped=$rows25['bodyequipped'];
		$c24lefthandequipped=$rows25['lefthandequipped'];
		$c24righthandequipped=$rows25['righthandequipped'];
		$c24legsequipped=$rows25['legsequipped'];
		$c24leftfootequipped=$rows25['leftfootequipped'];
		$c24rightfootequipped=$rows25['rightfootequipped'];

	endwhile;
	
	echo "&moneys=$moneys&wood=$wood&fish=$fish&cotton=$cotton&xp=$xp&attack=$attack&defense=$defense&health=$health&job=$job&item1=$item1&item2=$item2&item3=$item3&item4=$item4&item5=$item5&item6=$item6&item7=$item7&item8=$item8&item9=$item9&item10=$item10&headequipped=$headequipped&bodyequipped=$bodyequipped&lefthandequipped=$lefthandequipped&righthandequipped=$righthandequipped&legsequipped=$legsequipped&leftfootequipped=$leftfootequipped&rightfootequipped=$rightfootequipped&playwith=$playwith&c1moneys=$c1moneys&c1wood=$c1wood&c1fish=$c1fish&c1cotton=$c1cotton&c1xp=$c1xp&c1attack=$c1attack&c1defense=$c1defense&c1health=$c1health&c1job=$c1job&c1item1=$c1item1&c1item2=$c1item2&c1item3=$c1item3&c1item4=$c1item4&c1item5=$c1item5&c1item6=$c1item6&c1item7=$c1item7&c1item8=$c1item8&c1item9=$c1item9&c1item10=$c1item10&c1headequipped=$c1headequipped&c1bodyequipped=$c1bodyequipped&c1lefthandequipped=$c1lefthandequipped&c1righthandequipped=$c1righthandequipped&c1legsequipped=$c1legsequipped&c1leftfootequipped=$c1leftfootequipped&c1rightfootequipped=$c1rightfootequipped&playwith2=$playwith2&c2moneys=$c2moneys&c2wood=$c2wood&c2fish=$c2fish&c2cotton=$c2cotton&c2xp=$c2xp&c2attack=$c2attack&c2defense=$c2defense&c2health=$c2health&c2job=$c2job&c2item1=$c2item1&c2item2=$c2item2&c2item3=$c2item3&c2item4=$c2item4&c2item5=$c2item5&c2item6=$c2item6&c2item7=$c2item7&c2item8=$c2item8&c2item9=$c2item9&c2item10=$c2item10&c2headequipped=$c2headequipped&c2bodyequipped=$c2bodyequipped&c2lefthandequipped=$c2lefthandequipped&c2righthandequipped=$c2righthandequipped&c2legsequipped=$c2legsequipped&c2leftfootequipped=$c2leftfootequipped&c2rightfootequipped=$c2rightfootequipped&playwith3=$playwith3&c3moneys=$c3moneys&c3wood=$c3wood&c3fish=$c3fish&c3cotton=$c3cotton&c3xp=$c3xp&c3attack=$c3attack&c3defense=$c3defense&c3health=$c3health&c3job=$c3job&c3item1=$c3item1&c3item2=$c3item2&c3item3=$c3item3&c3item4=$c3item4&c3item5=$c3item5&c3item6=$c3item6&c3item7=$c3item7&c3item8=$c3item8&c3item9=$c3item9&c3item10=$c3item10&c3headequipped=$c3headequipped&c3bodyequipped=$c3bodyequipped&c3lefthandequipped=$c3lefthandequipped&c3righthandequipped=$c3righthandequipped&c3legsequipped=$c3legsequipped&c3leftfootequipped=$c3leftfootequipped&c3rightfootequipped=$c3rightfootequipped&playwith4=$playwith4&c4moneys=$c4moneys&c4wood=$c4wood&c4fish=$c4fish&c4cotton=$c4cotton&c4xp=$c4xp&c4attack=$c4attack&c4defense=$c4defense&c4health=$c4health&c4job=$c4job&c4item1=$c4item1&c4item2=$c4item2&c4item3=$c4item3&c4item4=$c4item4&c4item5=$c4item5&c4item6=$c4item6&c4item7=$c4item7&c4item8=$c4item8&c4item9=$c4item9&c4item10=$c4item10&c4headequipped=$c4headequipped&c4bodyequipped=$c4bodyequipped&c4lefthandequipped=$c4lefthandequipped&c4righthandequipped=$c4righthandequipped&c4legsequipped=$c4legsequipped&c4leftfootequipped=$c4leftfootequipped&c4rightfootequipped=$c4rightfootequipped&playwith5=$playwith5&c5moneys=$c5moneys&c5wood=$c5wood&c5fish=$c5fish&c5cotton=$c5cotton&c5xp=$c5xp&c5attack=$c5attack&c5defense=$c5defense&c5health=$c5health&c5job=$c5job&c5item1=$c5item1&c5item2=$c5item2&c5item3=$c5item3&c5item4=$c5item4&c5item5=$c5item5&c5item6=$c5item6&c5item7=$c5item7&c5item8=$c5item8&c5item9=$c5item9&c5item10=$c5item10&c5headequipped=$c5headequipped&c5bodyequipped=$c5bodyequipped&c5lefthandequipped=$c5lefthandequipped&c5righthandequipped=$c5righthandequipped&c5legsequipped=$c5legsequipped&c5leftfootequipped=$c5leftfootequipped&c5rightfootequipped=$c5rightfootequipped&playwith6=$playwith6&c6moneys=$c6moneys&c6wood=$c6wood&c6fish=$c6fish&c6cotton=$c6cotton&c6xp=$c6xp&c6attack=$c6attack&c6defense=$c6defense&c6health=$c6health&c6job=$c6job&c6item1=$c6item1&c6item2=$c6item2&c6item3=$c6item3&c6item4=$c6item4&c6item5=$c6item5&c6item6=$c6item6&c6item7=$c6item7&c6item8=$c6item8&c6item9=$c6item9&c6item10=$c6item10&c6headequipped=$c6headequipped&c6bodyequipped=$c6bodyequipped&c6lefthandequipped=$c6lefthandequipped&c6righthandequipped=$c6righthandequipped&c6legsequipped=$c6legsequipped&c6leftfootequipped=$c6leftfootequipped&c6rightfootequipped=$c6rightfootequipped&playwith7=$playwith7&c7moneys=$c7moneys&c7wood=$c7wood&c7fish=$c7fish&c7cotton=$c7cotton&c7xp=$c7xp&c7attack=$c7attack&c7defense=$c7defense&c7health=$c7health&c7job=$c7job&c7item1=$c7item1&c7item2=$c7item2&c7item3=$c7item3&c7item4=$c7item4&c7item5=$c7item5&c7item6=$c7item6&c7item7=$c7item7&c7item8=$c7item8&c7item9=$c7item9&c7item10=$c7item10&c7headequipped=$c7headequipped&c7bodyequipped=$c7bodyequipped&c7lefthandequipped=$c7lefthandequipped&c7righthandequipped=$c7righthandequipped&c7legsequipped=$c7legsequipped&c7leftfootequipped=$c7leftfootequipped&c7rightfootequipped=$c7rightfootequipped&playwith8=$playwith8&c8moneys=$c8moneys&c8wood=$c8wood&c8fish=$c8fish&c8cotton=$c8cotton&c8xp=$c8xp&c8attack=$c8attack&c8defense=$c8defense&c8health=$c8health&c8job=$c8job&c8item1=$c8item1&c8item2=$c8item2&c8item3=$c8item3&c8item4=$c8item4&c8item5=$c8item5&c8item6=$c8item6&c8item7=$c8item7&c8item8=$c8item8&c8item9=$c8item9&c8item10=$c8item10&c8headequipped=$c8headequipped&c8bodyequipped=$c8bodyequipped&c8lefthandequipped=$c8lefthandequipped&c8righthandequipped=$c8righthandequipped&c8legsequipped=$c8legsequipped&c8leftfootequipped=$c8leftfootequipped&c8rightfootequipped=$c8rightfootequipped&playwith9=$playwith9&c9moneys=$c9moneys&c9wood=$c9wood&c9fish=$c9fish&c9cotton=$c9cotton&c9xp=$c9xp&c9attack=$c9attack&c9defense=$c9defense&c9health=$c9health&c9job=$c9job&c9item1=$c9item1&c9item2=$c9item2&c9item3=$c9item3&c9item4=$c9item4&c9item5=$c9item5&c9item6=$c9item6&c9item7=$c9item7&c9item8=$c9item8&c9item9=$c9item9&c9item10=$c9item10&c9headequipped=$c9headequipped&c9bodyequipped=$c9bodyequipped&c9lefthandequipped=$c9lefthandequipped&c9righthandequipped=$c9righthandequipped&c9legsequipped=$c9legsequipped&c9leftfootequipped=$c9leftfootequipped&c9rightfootequipped=$c9rightfootequipped&playwith10=$playwith10&c10moneys=$c10moneys&c10wood=$c10wood&c10fish=$c10fish&c10cotton=$c10cotton&c10xp=$c10xp&c10attack=$c10attack&c10defense=$c10defense&c10health=$c10health&c10job=$c10job&c10item1=$c10item1&c10item2=$c10item2&c10item3=$c10item3&c10item4=$c10item4&c10item5=$c10item5&c10item6=$c10item6&c10item7=$c10item7&c10item8=$c10item8&c10item9=$c10item9&c10item10=$c10item10&c10headequipped=$c10headequipped&c10bodyequipped=$c10bodyequipped&c10lefthandequipped=$c10lefthandequipped&c10righthandequipped=$c10righthandequipped&c10legsequipped=$c10legsequipped&c10leftfootequipped=$c10leftfootequipped&c10rightfootequipped=$c10rightfootequipped&playwith11=$playwith11&c11moneys=$c11moneys&c11wood=$c11wood&c11fish=$c11fish&c11cotton=$c11cotton&c11xp=$c11xp&c11attack=$c11attack&c11defense=$c11defense&c11health=$c11health&c11job=$c11job&c11item1=$c11item1&c11item2=$c11item2&c11item3=$c11item3&c11item4=$c11item4&c11item5=$c11item5&c11item6=$c11item6&c11item7=$c11item7&c11item8=$c11item8&c11item9=$c11item9&c11item10=$c11item10&c11headequipped=$c11headequipped&c11bodyequipped=$c11bodyequipped&c11lefthandequipped=$c11lefthandequipped&c11righthandequipped=$c11righthandequipped&c11legsequipped=$c11legsequipped&c11leftfootequipped=$c11leftfootequipped&c11rightfootequipped=$c11rightfootequipped&playwith12=$playwith12&c12moneys=$c12moneys&c12wood=$c12wood&c12fish=$c12fish&c12cotton=$c12cotton&c12xp=$c12xp&c12attack=$c12attack&c12defense=$c12defense&c12health=$c12health&c12job=$c12job&c12item1=$c12item1&c12item2=$c12item2&c12item3=$c12item3&c12item4=$c12item4&c12item5=$c12item5&c12item6=$c12item6&c12item7=$c12item7&c12item8=$c12item8&c12item9=$c12item9&c12item10=$c12item10&c12headequipped=$c12headequipped&c12bodyequipped=$c12bodyequipped&c12lefthandequipped=$c12lefthandequipped&c12righthandequipped=$c12righthandequipped&c12legsequipped=$c12legsequipped&c12leftfootequipped=$c12leftfootequipped&c12rightfootequipped=$c12rightfootequipped&playwith13=$playwith13&c13moneys=$c13moneys&c13wood=$c13wood&c13fish=$c13fish&c13cotton=$c13cotton&c13xp=$c13xp&c13attack=$c13attack&c13defense=$c13defense&c13health=$c13health&c13job=$c13job&c13item1=$c13item1&c13item2=$c13item2&c13item3=$c13item3&c13item4=$c13item4&c13item5=$c13item5&c13item6=$c13item6&c13item7=$c13item7&c13item8=$c13item8&c13item9=$c13item9&c13item10=$c13item10&c13headequipped=$c13headequipped&c13bodyequipped=$c13bodyequipped&c13lefthandequipped=$c13lefthandequipped&c13righthandequipped=$c13righthandequipped&c13legsequipped=$c13legsequipped&c13leftfootequipped=$c13leftfootequipped&c13rightfootequipped=$c13rightfootequipped&playwith14=$playwith14&c14moneys=$c14moneys&c14wood=$c14wood&c14fish=$c14fish&c14cotton=$c14cotton&c14xp=$c14xp&c14attack=$c14attack&c14defense=$c14defense&c14health=$c14health&c14job=$c14job&c14item1=$c14item1&c14item2=$c14item2&c14item3=$c14item3&c14item4=$c14item4&c14item5=$c14item5&c14item6=$c14item6&c14item7=$c14item7&c14item8=$c14item8&c14item9=$c14item9&c14item10=$c14item10&c14headequipped=$c14headequipped&c14bodyequipped=$c14bodyequipped&c14lefthandequipped=$c14lefthandequipped&c14righthandequipped=$c14righthandequipped&c14legsequipped=$c14legsequipped&c14leftfootequipped=$c14leftfootequipped&c14rightfootequipped=$c14rightfootequipped&playwith15=$playwith15&c15moneys=$c15moneys&c15wood=$c15wood&c15fish=$c15fish&c15cotton=$c15cotton&c15xp=$c15xp&c15attack=$c15attack&c15defense=$c15defense&c15health=$c15health&c15job=$c15job&c15item1=$c15item1&c15item2=$c15item2&c15item3=$c15item3&c15item4=$c15item4&c15item5=$c15item5&c15item6=$c15item6&c15item7=$c15item7&c15item8=$c15item8&c15item9=$c15item9&c15item10=$c15item10&c15headequipped=$c15headequipped&c15bodyequipped=$c15bodyequipped&c15lefthandequipped=$c15lefthandequipped&c15righthandequipped=$c15righthandequipped&c15legsequipped=$c15legsequipped&c15leftfootequipped=$c15leftfootequipped&c15rightfootequipped=$c15rightfootequipped&playwith16=$playwith16&c16moneys=$c16moneys&c16wood=$c16wood&c16fish=$c16fish&c16cotton=$c16cotton&c16xp=$c16xp&c16attack=$c16attack&c16defense=$c16defense&c16health=$c16health&c16job=$c16job&c16item1=$c16item1&c16item2=$c16item2&c16item3=$c16item3&c16item4=$c16item4&c16item5=$c16item5&c16item6=$c16item6&c16item7=$c16item7&c16item8=$c16item8&c16item9=$c16item9&c16item10=$c16item10&c16headequipped=$c16headequipped&c16bodyequipped=$c16bodyequipped&c16lefthandequipped=$c16lefthandequipped&c16righthandequipped=$c16righthandequipped&c16legsequipped=$c16legsequipped&c16leftfootequipped=$c16leftfootequipped&c16rightfootequipped=$c16rightfootequipped&playwith17=$playwith17&c17moneys=$c17moneys&c17wood=$c17wood&c17fish=$c17fish&c17cotton=$c17cotton&c17xp=$c17xp&c17attack=$c17attack&c17defense=$c17defense&c17health=$c17health&c17job=$c17job&c17item1=$c17item1&c17item2=$c17item2&c17item3=$c17item3&c17item4=$c17item4&c17item5=$c17item5&c17item6=$c17item6&c17item7=$c17item7&c17item8=$c17item8&c17item9=$c17item9&c17item10=$c17item10&c17headequipped=$c17headequipped&c17bodyequipped=$c17bodyequipped&c17lefthandequipped=$c17lefthandequipped&c17righthandequipped=$c17righthandequipped&c17legsequipped=$c17legsequipped&c17leftfootequipped=$c17leftfootequipped&c17rightfootequipped=$c17rightfootequipped&playwith18=$playwith18&c18moneys=$c18moneys&c18wood=$c18wood&c18fish=$c18fish&c18cotton=$c18cotton&c18xp=$c18xp&c18attack=$c18attack&c18defense=$c18defense&c18health=$c18health&c18job=$c18job&c18item1=$c18item1&c18item2=$c18item2&c18item3=$c18item3&c18item4=$c18item4&c18item5=$c18item5&c18item6=$c18item6&c18item7=$c18item7&c18item8=$c18item8&c18item9=$c18item9&c18item10=$c18item10&c18headequipped=$c18headequipped&c18bodyequipped=$c18bodyequipped&c18lefthandequipped=$c18lefthandequipped&c18righthandequipped=$c18righthandequipped&c18legsequipped=$c18legsequipped&c18leftfootequipped=$c18leftfootequipped&c18rightfootequipped=$c18rightfootequipped&playwith19=$playwith19&c19moneys=$c19moneys&c19wood=$c19wood&c19fish=$c19fish&c19cotton=$c19cotton&c19xp=$c19xp&c19attack=$c19attack&c19defense=$c19defense&c19health=$c19health&c19job=$c19job&c19item1=$c19item1&c19item2=$c19item2&c19item3=$c19item3&c19item4=$c19item4&c19item5=$c19item5&c19item6=$c19item6&c19item7=$c19item7&c19item8=$c19item8&c19item9=$c19item9&c19item10=$c19item10&c19headequipped=$c19headequipped&c19bodyequipped=$c19bodyequipped&c19lefthandequipped=$c19lefthandequipped&c19righthandequipped=$c19righthandequipped&c19legsequipped=$c19legsequipped&c19leftfootequipped=$c19leftfootequipped&c19rightfootequipped=$c19rightfootequipped&playwith20=$playwith20&c20moneys=$c20moneys&c20wood=$c20wood&c20fish=$c20fish&c20cotton=$c20cotton&c20xp=$c20xp&c20attack=$c20attack&c20defense=$c20defense&c20health=$c20health&c20job=$c20job&c20item1=$c20item1&c20item2=$c20item2&c20item3=$c20item3&c20item4=$c20item4&c20item5=$c20item5&c20item6=$c20item6&c20item7=$c20item7&c20item8=$c20item8&c20item9=$c20item9&c20item10=$c20item10&c20headequipped=$c20headequipped&c20bodyequipped=$c20bodyequipped&c20lefthandequipped=$c20lefthandequipped&c20righthandequipped=$c20righthandequipped&c20legsequipped=$c20legsequipped&c20leftfootequipped=$c20leftfootequipped&c20rightfootequipped=$c20rightfootequipped&playwith21=$playwith21&c21moneys=$c21moneys&c21wood=$c21wood&c21fish=$c21fish&c21cotton=$c21cotton&c21xp=$c21xp&c21attack=$c21attack&c21defense=$c21defense&c21health=$c21health&c21job=$c21job&c21item1=$c21item1&c21item2=$c21item2&c21item3=$c21item3&c21item4=$c21item4&c21item5=$c21item5&c21item6=$c21item6&c21item7=$c21item7&c21item8=$c21item8&c21item9=$c21item9&c21item10=$c21item10&c21headequipped=$c21headequipped&c21bodyequipped=$c21bodyequipped&c21lefthandequipped=$c21lefthandequipped&c21righthandequipped=$c21righthandequipped&c21legsequipped=$c21legsequipped&c21leftfootequipped=$c21leftfootequipped&c21rightfootequipped=$c21rightfootequipped&playwith22=$playwith22&c22moneys=$c22moneys&c22wood=$c22wood&c22fish=$c22fish&c22cotton=$c22cotton&c22xp=$c22xp&c22attack=$c22attack&c22defense=$c22defense&c22health=$c22health&c22job=$c22job&c22item1=$c22item1&c22item2=$c22item2&c22item3=$c22item3&c22item4=$c22item4&c22item5=$c22item5&c22item6=$c22item6&c22item7=$c22item7&c22item8=$c22item8&c22item9=$c22item9&c22item10=$c22item10&c22headequipped=$c22headequipped&c22bodyequipped=$c22bodyequipped&c22lefthandequipped=$c22lefthandequipped&c22righthandequipped=$c22righthandequipped&c22legsequipped=$c22legsequipped&c22leftfootequipped=$c22leftfootequipped&c22rightfootequipped=$c22rightfootequipped&playwith23=$playwith23&c23moneys=$c23moneys&c23wood=$c23wood&c23fish=$c23fish&c23cotton=$c23cotton&c23xp=$c23xp&c23attack=$c23attack&c23defense=$c23defense&c23health=$c23health&c23job=$c23job&c23item1=$c23item1&c23item2=$c23item2&c23item3=$c23item3&c23item4=$c23item4&c23item5=$c23item5&c23item6=$c23item6&c23item7=$c23item7&c23item8=$c23item8&c23item9=$c23item9&c23item10=$c23item10&c23headequipped=$c23headequipped&c23bodyequipped=$c23bodyequipped&c23lefthandequipped=$c23lefthandequipped&c23righthandequipped=$c23righthandequipped&c23legsequipped=$c23legsequipped&c23leftfootequipped=$c23leftfootequipped&c23rightfootequipped=$c23rightfootequipped&playwith24=$playwith24&c24moneys=$c24moneys&c24wood=$c24wood&c24fish=$c24fish&c24cotton=$c24cotton&c24xp=$c24xp&c24attack=$c24attack&c24defense=$c24defense&c24health=$c24health&c24job=$c24job&c24item1=$c24item1&c24item2=$c24item2&c24item3=$c24item3&c24item4=$c24item4&c24item5=$c24item5&c24item6=$c24item6&c24item7=$c24item7&c24item8=$c24item8&c24item9=$c24item9&c24item10=$c24item10&c24headequipped=$c24headequipped&c24bodyequipped=$c24bodyequipped&c24lefthandequipped=$c24lefthandequipped&c24righthandequipped=$c24righthandequipped&c24legsequipped=$c24legsequipped&c24leftfootequipped=$c24leftfootequipped&c24rightfootequipped=$c24rightfootequipped&success=$num&success2=$num2";
	mysql_close();
	?>