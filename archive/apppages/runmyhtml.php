<!DOCTYPE html>

<?php
	$originalmyhtml = $_GET['myhtml'];
	$oldmyhtml = str_replace("%20", ' ', $originalmyhtml);
	$oldmyhtml2 = str_replace("<bk>", '', $oldmyhtml);
	$oldmyhtml3 = str_replace("<tabtab>", '', $oldmyhtml2);
	$myhtml = str_replace("\\", '', $oldmyhtml3);

	echo "
		<html>
			<head>
				<!-- Meta Tags -->
				<meta name = 'description' content = 'Test your HTML code and generate a url so that you can edit it later.'>
				<meta name = 'keywords' content = 'Games, Development, Internet, Computers, Online, Projects, Programming'>
				<meta name = 'author' content = 'James Grams'>
				<meta http-equiv='Content-Type' content = 'text/html;charset=utf-8'>
				
				<!-- Title -->
				<title>Run My HTML - Game 103: Family-Friendly Games and Entertainment</title>
				
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
				<script type = 'text/javascript' src='https://apis.google.com/js/platform.js'></script>";
?>
			
				<!--Outside so replace does not get messed up-->
				<script type = 'text/javascript'>
					function runhtml() {
						var input = document.getElementById('input').value;
						document.getElementById('display').innerHTML = input;
						var codedinput = input.replace(/(\r\n|\n|\r)/gm, '<bk>');
						//replace number signs so the page does not get messed up
						codedinput = codedinput.replace(/#/g,'mrnumbersign');
						//replace tabs
						codedinput = codedinput.replace(/\t/g,'<tabtab>');
						//replace single quotes
						codedinput = codedinput.replace(/'/g,"<singingquote>");
						document.getElementById('linkurl').value = 'http://game103.net/apppages/runmyhtml.php?myhtml=' + codedinput;
					}
					
<?php
	echo "
					function pageload() {
						var loadvalue = '$myhtml';";
?>
						loadvalue = loadvalue.replace(/mrnumbersign/g,'#');
						loadvalue = loadvalue.replace(/<singingquote>/g,'\'');
<?php
	echo "
						document.getElementById('display').innerHTML = loadvalue;
						document.getElementById('input').innerHTML = loadvalue;
					}
				</script>
			</head>
				
			<body onload = 'pageload()'>
				<div class = 'page'>
					<!-- Header -->
					<img alt = 'header' src = '../images/header2015.png' style = 'width:100%;height:130px;border-top-left-radius:5.5px;border-top-right-radius:5.5px;'>
					<h1 style='position:absolute;top:-5px;text-align:center;width:880px;' title = 'Thanks for visiting!'>Game 103: Run My HTML</h1>
					<h3 style='position:absolute;top:35px;text-align:center;width:880px;'>Family-Friendly Games and Entertainment</h3>

					<!-- Navbar -->
					<div class = 'navbar'>
						<br>";
						$path = $_SERVER['DOCUMENT_ROOT'];
						$path .= "/navbar.html";
						include_once($path);
					echo"</div>
						
					<!-- Content -->
					<br>
					<div class = 'content' style = 'text-align:center;'>
						<div id = 'display'></div><br>
						<textarea id='input' cols=70 rows=10></textarea><br>
						<input type = 'button' onclick='runhtml()' value='Run my HTML'><br>

						Copy the URL below anywhere to link back to this page.<br>
						<textarea id = 'linkurl' rows='3' cols = '0' style='display:inline;width:800px;white-space: pre-wrap;word-wrap:break-word;'>http://game103.net/apppages/runmyhtml.php?myhtml=$myhtml</textarea>

						<!-- End main part of the page -->
						<hr>
						
						<!--Contact and Copyright-->
						<br>
						<a href = 'http://game103.net/account.php'>Your Page</a> | <a href = 'http://game103.net/developers.html'>Developers</a> | <a href = 'http://game103.net/aboutus.html'>About Us</a> | <a href = 'http://game103.net/privacypolicy.html'>Privacy Policy</a> | <a href = 'http://blog.game103.net'>Blog</a> | <a href = 'mailto:james@game103.net'>Email Game 103</a> | <a href = 'http://game103.net/funfacts.html'>Fun Facts</a><br><br>
						&copy; 2015 <a href = 'http://game103.net'>Game 103</a><br><br>
					</div>
				</div>

			</body>
		</html>";
?>
