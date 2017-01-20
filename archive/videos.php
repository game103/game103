<!DOCTYPE html>

<html>
	
	<head>
		<!-- Meta Tags -->
		<meta name = "description" content = "A collection of family-friendly, entertaining videos available to watch directly on Game 103.">
		<meta name = "keywords" content = "Games, Development, Internet, Computers, Online, Projects, Programming">
		<meta name = "author" content = "James Grams">
		<meta http-equiv="Content-Type" content = "text/html;charset=utf-8">
		
		<!-- Title -->
		<title>Videos - Game 103: Family-Friendly Games and Entertainment</title>
		
		<!-- Load Style Sheet -->
		<link rel="stylesheet" type="text/css" href="styles.css">
		
		<!--Google Analytics Function-->
		<script type = "text/javascript">
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-4577874-3', 'auto');
		  ga('send', 'pageview');

		</script>
		<script type = "text/javascript" src="https://apis.google.com/js/platform.js"></script>
		
	</head>
	
	<body>
		<div class = "page">
			<!-- Header -->
			<div class="header">
				<h1 title="Thanks for visiting!" class="header-title">Game 103: Videos</h1>
				<h3 class="header-subtitle">Family-Friendly Games and Entertainment</h3>
			</div>

			<!-- Navbar -->
			<?php include 'navbar.html';?>
				
			<!-- Content -->
			<div class = "content">
				
				<!-- Entries-->
				<div class="entries">
			
				<?php
					//Connect to database
					$connect = mysql_connect("localhost","hallaby","***REMOVED***");
					mysql_select_db("hallaby_videos");
				
					$sort = mysql_real_escape_string($_GET['sort']);
					
					$actualSort = "";
					if($sort == "a") {
						$actualSort = 'videoid';
					}
					else if($sort == "d") {
						$actualSort = 'date DESC, rating DESC';
					}
					else {
						$sort = "r";
						$actualSort = 'rating DESC, date DESC';
					}
					
					$search = mysql_real_escape_string($_GET['search']);
					$cat = mysql_real_escape_string($_GET['cat']);
					$page = mysql_real_escape_string($_GET['page']);
					
					if($page == "") {
						$page = 1;
					}
					if($search == "") {
						$search = '';
						if($cat != "") {
							$searchquery = "WHERE (cat1 = '$cat' or cat2 = '$cat')";
						}
					}
					else if ($search != ""){
						$searchquery = "WHERE (videoid LIKE '%" . $search . "%' or description LIKE '%" . $search . "%' or date LIKE '%" . $search . "%')";
						if($cat != "") {
							$searchquery .= " and (cat1 = '$cat' or cat2 = '$cat')";
						}
					}
	
					$pageNumberTimesTen = (intval($page)-1) * 12;
					$str = "SELECT * FROM entries $searchquery ORDER BY $actualSort LIMIT 12 OFFSET $pageNumberTimesTen";
					$displayquery = mysql_query($str);
					$lines = 0;
					$zindex = 5;
					$totalentries = -1;
					
					$noLimitQuery = mysql_query("SELECT * FROM entries $searchquery ORDER BY $actualSort");
					$allEntriesAmount = mysql_num_rows($noLimitQuery);
					
					//Get info from database and make an entry
					while ($rows = mysql_fetch_array($displayquery)):
	
						$videoid = $rows['videoid'];
						$urlid = $rows['urlid'];
						$description = $rows['description'];
						$imageurl = $rows['imageurl'];
						$totalScore = $rows['totalScore'];
						$totalVotes = $rows['totalVotes'];
						
						//Calculate rating	
						if($totalVotes > 0) {
							$rating = $totalScore / $totalVotes;
							$rating = round($rating,2);
						}
						else {
							$rating = "-";
						}		
						
						$totalentries = $totalentries + 1;	

						//Strip tages
						$strippedvideoid  = str_replace('\'','',$videoid);

						//Echo the entry	
						echo "
						<a href = 'video.php?urlid=$urlid' class = 'entry-link'>
						<div class = 'entry-item' style='z-index:$zindex;'>
						<img alt = '$strippedvideoid' src = '$imageurl'><br>
						<div class = 'entry-title'>$videoid</div>";
						if($rating > 3) {
							echo "<div class = 'entry-rating' style = 'color:#003300'>Rating: $rating</div>";
						}
						else if($rating > 2) {
							echo "<div class = 'entry-rating' style = 'font-size:80%;color:#CC6600'>Rating: $rating</div>";
						}
						else if($rating > 0) {
							echo "<div class = 'entry-rating' style = 'font-size:80%;color:#CC0000'>Rating: $rating</div>";
						}
						else {
							echo "<div class = 'entry-rating' style = 'font-size:80%;'>Rating: $rating</div>";
						}
						echo "
						<div class = 'entry-description'> $description</div>
						</div>
						</a>";

						$lines = $lines + 1;
						
					endwhile;
					
					//Nothing found
					if($totalentries == -1) {
						echo "Sorry, no entries matching that search were found.";
					}
					else {
						//Page Links
						echo "<div class='page-links'><div class='page-links-back'>";
						if($page > 1) {
								echo "<a href = 'http://game103.net/videos.php?sort=$sort&amp;page=1&amp;search=$search&amp;cat=$cat'>&lt;&lt; First</a>&nbsp;&nbsp;&nbsp;";
								$temppage = $page - 1;
								echo "<a href = 'http://game103.net/videos.php?sort=$sort&amp;page=$temppage&amp;search=$search&amp;cat=$cat'>&lt; Previous</a>";
						}
					
					echo "</div>";
					echo "<div class='page-numbers'>";
					
						echo "Page ";

						$totalPages = ceil($allEntriesAmount/12) + 1;
						for($i = 1;$i < $totalPages;$i++) {
							if($page == $i) {
								echo "<a href = 'http://game103.net/videos.php?sort=$sort&amp;page=$i&amp;search=$search&amp;cat=$cat' style='font-weight: bold'>$i</a> ";
							}
							else {
								echo "<a href = 'http://game103.net/videos.php?sort=$sort&amp;page=$i&amp;search=$search&amp;cat=$cat'>$i</a> ";
							}
						}
						
					echo "</div>";
					echo "<div class = 'page-links-next'>";	
						
						if($page < ceil($allEntriesAmount/12)) {
								$temppage = $page + 1;
								echo "<a href = 'http://game103.net/videos.php?sort=$sort&amp;page=$temppage&amp;search=$search&amp;cat=$cat'>Next &gt;</a>";
								$temppage = ceil($allEntriesAmount/12);
								echo "&nbsp;&nbsp;&nbsp;<a href = 'http://game103.net/videos.php?sort=$sort&amp;page=$temppage&amp;search=$search&amp;cat=$cat'>Last &gt;&gt;</a>";
						}
						echo "</div></div>";
					}
				?>

				</div>

				<!--Sorting options-->
				<?php
					//The search is saved when sorting - click cancel search to go back to all games
					echo"
					<div class='sort'>";
						if($sort == "a") 
							echo "<a href = 'http://game103.net/videos.php?sort=a&amp;search=$search&amp;cat=$cat' style = 'font-weight: bold'>Sort videos alphabetically</a>";
						else
							echo "<a href = 'http://game103.net/videos.php?sort=a&amp;search=$search&amp;cat=$cat'>Sort videos alphabetically</a>";
						echo "<br>";
						if($sort == "d") 
							echo "<a href = 'http://game103.net/videos.php?sort=d&amp;search=$search&amp;cat=$cat' style = 'font-weight: bold'>Sort videos by how recently they were added</a>";
						else
							echo "<a href = 'http://game103.net/videos.php?sort=d&amp;search=$search&amp;cat=$cat'>Sort videos by how recently they were added</a>";
						echo "<br>";
						if($sort == "r") 
							echo "<a href = 'http://game103.net/videos.php?sort=r&amp;search=$search&amp;cat=$cat' style = 'font-weight: bold'>Sort videos by rating (Default)</a>";
						else
							echo "<a href = 'http://game103.net/videos.php?sort=r&amp;search=$search&amp;cat=$cat'>Sort videos by rating (Default)</a>";
						echo "<br>";
					echo"</div>";
				?>
				
				<!--Search-->
				<div class="search">
				<?php
					echo "<form action = 'videos.php' method = 'GET'>";
					if($search != "") {
						echo "Search: <input type = 'text' name = 'search' value=$search>";
					}
					else {
						echo "Search: <input type = 'text' name = 'search'>";
					}
					echo "<input type = 'hidden' value = '$sort' name = sort><input type = 'hidden' value = '$cat' name = cat>
						<input type = 'submit' value = 'Search'>
					</form>";
					//cancel search
					if($search != "") {
						echo "<a href = 'http://game103.net/videos.php?sort=$sort&cat=$cat' style = 'font-size:75%'>Cancel Search</a>";
					}
				?>
				</div>
				
				<!--Categories-->
				<div class="categories">
				<?php
					echo "Categories: <form style = 'display:inline'>
						<select id = 'list' onchange='window.location=this.options[this.selectedIndex].value'>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search'";echo ($cat=='')?('selected'):('');echo">All Videos</option>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search&cat=Comedy'";echo ($cat=='Comedy')?('selected'):('');echo">Comedy</option>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search&cat=Games'";echo ($cat=='Games')?('selected'):('');echo">Games</option>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search&cat=Instructional'";echo ($cat=='Instructional')?('selected'):('');echo">Instructional</option>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search&cat=Interesting'";echo ($cat=='Interesting')?('selected'):('');echo">Interesting</option>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search&cat=Musical'";echo ($cat=='Musical')?('selected'):('');echo">Musical</option>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search&cat=Nature'";echo ($cat=='Nature')?('selected'):('');echo">Nature</option>
							<option value = 'http://game103.net/videos.php?sort=$sort&search=$search&cat=Story'";echo ($cat=='Story')?('selected'):('');echo">Story</option>
						</select>
					</form>";
				?>
				</div>

				<!-- End main part of the page -->
				
				<!--Contact and Copyright-->
				<div class="footer">
					<div class="additional-links"><a href="http://game103.net/developers.html">Developers</a> | <a href="http://game103.net/aboutus.html">About Us</a> | <a href="http://game103.net/privacypolicy.html">Privacy Policy</a> | <a href="http://blog.game103.net">Blog</a> | <a href="mailto:james@game103.net">Email Game 103</a> | <a href="http://game103.net/funfacts.html">Fun Facts</a></div>
					<div class="copyright">&copy; 2016 <a href="http://game103.net">Game 103</a></div>
				</div>
			</div>
		</div>

	</body>
</html>