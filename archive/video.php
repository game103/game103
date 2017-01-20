<!DOCTYPE html>

<?php
	//Connect to database
	$connect = mysql_connect("localhost","hallaby","***REMOVED***");
	mysql_select_db("hallaby_videos");

	$urlid = mysql_real_escape_string($_GET['urlid']);

	$str = "SELECT * FROM entries WHERE urlid = '$urlid'";
	$displayquery = mysql_query($str);
	
	while ($rows = mysql_fetch_array($displayquery)):
		
		$videoid = $rows['videoid'];
		$string = $rows['string'];
		$description = $rows['description'];
		$date = $rows['date'];
		$totalScore = $rows['totalScore'];
		$totalVotes = $rows['totalVotes'];
		$imgurl = $rows['imageurl'];
		
	endwhile;
	
	//rating calculator
	if($totalVotes > 0) {
		$rating = $totalScore / $totalVotes;
		$rating = round($rating,2);
	}
	else {
		$rating = "-";
	}	

	echo "
<html>
	
	<head>
		<!-- Meta Tags -->
		<meta name = 'description' content = '$description Watch $videoid on Game 103!'>
		<meta name = 'keywords' content = 'Games, Development, Internet, Computers, Online, Projects, Programming'>
		<meta name = 'author' content = 'James Grams'>
		<meta http-equiv='Content-Type' content = 'text/html;charset=utf-8'>
		
		<!-- Title -->
		<title>$videoid - Game 103: Family-Friendly Games and Entertainment</title>
		
		<!-- Load Style Sheet -->
		<link rel='stylesheet' type='text/css' href='../styles.css'>
		
		<!--Google Analytics Function-->
		<script type = 'text/javascript'>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-4577874-3', 'auto');
		  ga('send', 'pageview');

		</script>
		<script type = 'text/javascript' src='https://apis.google.com/js/platform.js'></script>
		
		<!--Resize function-->
		<script type = 'text/javascript'>
			function bigger() {
				var movie = document.getElementById('movie');
				movie.style.width = (parseInt(movie.offsetWidth) * 1.25).toString().concat('px');
				movie.style.height = Height = (parseInt(movie.offsetHeight) * 1.25).toString().concat('px');
			}
			function smaller() {
				var movie = document.getElementById('movie');
				movie.style.width = (parseInt(movie.offsetWidth) / 1.25).toString().concat('px');
				movie.style.height = Height = (parseInt(movie.offsetHeight) / 1.25).toString().concat('px');
			}
			function hideembedlink() {
				document.getElementById('embedlink').style.display = 'none';
				document.getElementById('embedlinkhidebutton').style.display = 'none';
				document.getElementById('embedlinkshowbutton').style.visibility = 'visible';
			}
			function showembedlink() {
				document.getElementById('embedlink').style.display = 'block';
				document.getElementById('embedlinkhidebutton').style.display = 'inline';
				document.getElementById('embedlinkshowbutton').style.visibility = 'hidden';
			}
			function hidecontrols() {
				document.getElementById('controls').style.display = 'none';
				document.getElementById('controlshidebutton').style.display = 'none';
				document.getElementById('controlsshowbutton').style.visibility = 'visible';
			}
			function showcontrols() {
				document.getElementById('controls').style.display = 'block';
				document.getElementById('controlshidebutton').style.display = 'inline';
				document.getElementById('controlsshowbutton').style.visibility = 'hidden';
			}
		</script>
		
	</head>
	
	<body>
		<div class = 'page'>
			<!-- Header -->
			<div class='header'>
				<h1 title='Thanks for visiting!' class='header-title'>Game 103: $videoid</h1>
				<h3 class='header-subtitle'>Family-Friendly Games and Entertainment</h3>
			</div>

			<!-- Navbar -->
			";
			include 'navbar.html';
			echo "	
				
			<!-- Content -->
			<div class = 'content' style = 'text-align:center;'>
				
				<!--Video-->
				";
				if($urlid != "cocoamp3player") {
					echo "<iframe id = 'movie' width='800' height='482' src='http://www.youtube.com/embed/$string?rel=0&amp;modestbranding=1&amp;theme=light&amp;iv_load_policy=3' frameborder='0' id = 'video'></iframe>";
				}
				else {
					echo $string;
				}
				
				echo "<br>
				
				<!--Resize Button-->
				<input type = 'button' value = 'Smaller' onclick='smaller()'><input type = 'button' value = 'Larger' onclick='bigger()'>
				
				<table border = 0 style='background-color:#0066FF;margin-left:auto;margin-right:auto;'>
					<tr>
						<!--Rating-->
						<td style = 'vertical-align:top;text-align:center;'>
							<div style='background-color:#0066FF;color:#66CCFF;'>
								<div id = 'rating' style='text-align:center;'>
										<iframe src = 'gamepages/videorating.php?urlid=$urlid' frameborder = 0 width = 220></iframe>
								</div>
							</div>
						</td>
						
						<!--Embed Link-->
						<td style = 'vertical-align:top;background-color:#0066FF;color:#66CCFF;'>
							<div id = 'embedlink' style='text-align:center;'><b>Embed a link to this game</b>
								<table border = 0>
									<tr>
										<td style = 'vertical-align:top;'>
											
											<!-- Example Link-->
											<table border = 0>
												<tr>
													<td width = '95%'>
														<div style = 'text-align:center;border-style:solid;font-family:Tahoma,Arial,serif;background-color:#66CCFF'> 
															<a href = 'http://game103.net/video.php?urlid=$urlid'>Click to watch<br>
																<img alt = '$videoid icon' src = '$imgurl' width = 75 height = 75><br>
																$videoid<br>
																on Game103.net
															</a>
														</div>
													</td>
												</tr>
											</table>

										</td>
										
										<!--Text Area Link-->
										<td style = 'vertical-align:top;'>
											
<textarea rows = 8 cols = 15>
&lt;table border = 0&gt;
&lt;tr&gt;
&lt;td width = '95%'&gt;
&lt;div style = 'text-align:center;border-style:solid;font-family:Tahoma,Arial,serif;background-color:#66CCFF'&gt; 
&lt;a href = 'http://game103.net/video.php?urlid=$urlid'&gt;Click to watch&lt;br&gt;
&lt;img alt = '$videoid icon' src = '$imgurl' width = 75 height = 75&gt;&lt;br&gt;
$videoid&lt;br&gt;
on Game103.net
&lt;/a&gt;
&lt;/div&gt;
&lt;/td&gt;
&lt;/tr&gt;
&lt;/table&gt;
</textarea>
										
										</td>
									</tr>
								</table>

							</div>
							
							<span style='text-align:middle;'><input id = 'embedlinkhidebutton' type = 'button' value = 'Hide Embed Link' onclick='hideembedlink()' style = 'position:absolute'></span>
							<input id = 'embedlinkshowbutton' type = 'button' value = 'Show Embed Link' onclick='showembedlink()' style = 'visibility:hidden;'>
						</td>

					</tr>
				</table>
			
				<!-- End main part of the page -->
				
				<!--Contact and Copyright-->
				<div class='footer'>
					<div class='additional-links'><a href='http://game103.net/developers.html'>Developers</a> | <a href='http://game103.net/aboutus.html'>About Us</a> | <a href='http://game103.net/privacypolicy.html'>Privacy Policy</a> | <a href='http://blog.game103.net'>Blog</a> | <a href='mailto:james@game103.net'>Email Game 103</a> | <a href='http://game103.net/funfacts.html'>Fun Facts</a></div>
					<div class='copyright'>&copy; 2016 <a href='http://game103.net'>Game 103</a></div>
				</div>
			</div>
		</div>

	</body>
</html>";