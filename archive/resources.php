<!DOCTYPE html>

<html>
	
	<head>
		<!-- Meta Tags -->
		<meta name = "description" content = "A listing of resources that are used by Game 103 for various projects.">
		<meta name = "keywords" content = "Games, Development, Internet, Computers, Online, Projects, Programming">
		<meta name = "author" content = "James Grams">
		<meta http-equiv="Content-Type" content = "text/html;charset=utf-8">
		
		<!-- Title -->
		<title>Resources - Game 103: Family-Friendly Games and Entertainment</title>
		
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
				<h1 title="Thanks for visiting!" class="header-title">Game 103: Resources</h1>
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
					mysql_select_db("hallaby_webtools");
				
					$sort = mysql_real_escape_string($_GET['sort']);
					
					$actualSort = "";
					if($sort == "a") {
						$actualSort = 'name';
					}
					else {
						$sort = "d";
						$actualSort = 'date DESC';
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
	
						$name = $rows['name'];
						$link = $rows['link'];
						$description = $rows['description'];
						$imageurl = $rows['imageurl'];
						
						$totalentries = $totalentries + 1;	

						//Strip tages
						$strippedname  = str_replace('\'','',$strippedname);

						//Echo the entry	
						echo "
						<a href = '$link' class = 'entry-link'>
						<div class = 'entry-item' style='z-index:$zindex;'>
						<img alt = '$strippedname' src = '$imageurl'><br>
						<div class = 'entry-title'>$name</div>";
						echo "
						<div class = 'entry-description-no-rating'> $description</div>
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
								echo "<a href = 'http://game103.net/resources.php?sort=$sort&amp;page=1&amp;search=$search&amp;cat=$cat'>&lt;&lt; First</a>&nbsp;&nbsp;&nbsp;";
								$temppage = $page - 1;
								echo "<a href = 'http://game103.net/resources.php?sort=$sort&amp;page=$temppage&amp;search=$search&amp;cat=$cat'>&lt; Previous</a>";
						}
					
					echo "</div>";
					echo "<div class='page-numbers'>";
					
						echo "Page ";

						$totalPages = ceil($allEntriesAmount/12) + 1;
						for($i = 1;$i < $totalPages;$i++) {
							if($page == $i) {
								echo "<a href = 'http://game103.net/resources.php?sort=$sort&amp;page=$i&amp;search=$search&amp;cat=$cat' style='font-weight: bold'>$i</a> ";
							}
							else {
								echo "<a href = 'http://game103.net/resources.php?sort=$sort&amp;page=$i&amp;search=$search&amp;cat=$cat'>$i</a> ";
							}
						}
						
					echo "</div>";
					echo "<div class = 'page-links-next'>";	
						
						if($page < ceil($allEntriesAmount/12)) {
								$temppage = $page + 1;
								echo "<a href = 'http://game103.net/resources.php?sort=$sort&amp;page=$temppage&amp;search=$search&amp;cat=$cat'>Next &gt;</a>";
								$temppage = ceil($allEntriesAmount/12);
								echo "&nbsp;&nbsp;&nbsp;<a href = 'http://game103.net/resources.php?sort=$sort&amp;page=$temppage&amp;search=$search&amp;cat=$cat'>Last &gt;&gt;</a>";
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
							echo "<a href = 'http://game103.net/resources.php?sort=a&amp;search=$search&amp;cat=$cat' style = 'font-weight: bold'>Sort resources alphabetically</a>";
						else
							echo "<a href = 'http://game103.net/resources.php?sort=a&amp;search=$search&amp;cat=$cat'>Sort resources alphabetically</a>";
						echo "<br>";
						if($sort == "d") 
							echo "<a href = 'http://game103.net/resources.php?sort=d&amp;search=$search&amp;cat=$cat' style = 'font-weight: bold'>Sort resources by how recently they were added (Default)</a>";
						else
							echo "<a href = 'http://game103.net/resources.php?sort=d&amp;search=$search&amp;cat=$cat'>Sort resources by how recently they were added (Default)</a>";
						echo "<br>";
					echo"</div>";
				?>
				
				<!--Search-->
				<div class="search">
				<?php
					echo "<form action = 'resources.php' method = 'GET'>";
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
						echo "<a href = 'http://game103.net/resources.php?sort=$sort&amp;cat=$cat' style = 'font-size:75%'>Cancel Search</a>";
					}
				?>
				</div>
				
				<!--Categories-->
				<div class="categories">
				<?php
					echo "Categories: <form style = 'display:inline'>
						<select id = 'list' onchange='window.location=this.options[this.selectedIndex].value'>
							<option value = 'http://game103.net/resources.php?sort=$sort&search=$search'";echo ($cat=='')?('selected'):('');echo">All Resources</option>
							<option value = 'http://game103.net/resources.php?sort=$sort&search=$search&cat=Audio'";echo ($cat=='Audio')?('selected'):('');echo">Audio</option>
							<option value = 'http://game103.net/resources.php?sort=$sort&search=$search&cat=Game Development'";echo ($cat=='Game Development')?('selected'):('');echo">Game Development</option>
							<option value = 'http://game103.net/resources.php?sort=$sort&search=$search&cat=Images'";echo ($cat=='Images')?('selected'):('');echo">Images</option>
							<option value = 'http://game103.net/resources.php?sort=$sort&search=$search&cat=Other Programming'";echo ($cat=='Other Programming')?('selected'):('');echo">Other Programming</option>
							<option value = 'http://game103.net/resources.php?sort=$sort&search=$search&cat=Video'";echo ($cat=='Video')?('selected'):('');echo">Video</option>
							<option value = 'http://game103.net/resources.php?sort=$sort&search=$search&cat=Web Programming'";echo ($cat=='Web Programming')?('selected'):('');echo">Web Programming</option>
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