<?php
	date_default_timezone_set('America/New_York');
	$path = $_SERVER['DOCUMENT_ROOT'];
	$routes = explode('/', $_SERVER['REQUEST_URI']);
	$mysql_message = "Sorry, there was an error connecting to the database.";
	$direct_access_message = "You are not allowed to access this page.";
	$no_results_message = "Sorry, no results were found for your search.";
	$routed = true;
	$display_meta = ""; // Most pages don't need to define this, so set it to be empty on default.
	
	if(end($routes) == '') {
		array_pop($routes);
	}
	
	$is_404 = false;
	if(isset($routes[1])) {
		$base_route = $routes[1];
	}
	else {
		$base_route = '';
	}
	switch($base_route) {
		case 'game':
			if(count($routes) == 3) {
				$url_name = $routes[2];
				include $path . '/pages/game.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'games':
			if(count($routes) == 2) {
				$category = 'all';
				$search = '';
				$sort = 'popularity';
				$page = 1;
				include $path . '/pages/games.php';
			}
			else if(count($routes) == 3) {
				$category = $routes[2];
				$search = '';
				$sort = 'popularity';
				$page = 1;
				include $path . '/pages/games.php';
			}
			else if(count($routes) == 5) {
				$category = $routes[2];
				$search = '';
				$sort = $routes[3];
				$page = $routes[4];
				include $path . '/pages/games.php';
			}
			else if(count($routes) == 6) {
				$category = $routes[2];
				$search = $routes[3];
				$sort = $routes[4];
				$page = $routes[5];
				include $path . '/pages/games.php';
			}
			else if(count($routes) == 7) {
				if($routes[2] == 'ws') {
					$ws = true;
					$category = $routes[3];
					$search = $routes[4];
					$sort = $routes[5];
					$page = $routes[6];
					include $path . '/pages/games.php';
				}
				else {
					$is_404 = true;
				}
			}
			else {
				$is_404 = true;
			}
			break;
		case 'video':
			if(count($routes) == 3) {
				$url_name = $routes[2];
				include $path . '/pages/video.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'videos':
			if(count($routes) == 2) {
				$category = 'all';
				$search = '';
				$sort = 'popularity';
				$page = 1;
				include $path . '/pages/videos.php';
			}
			else if(count($routes) == 3) {
				$category = $routes[2];
				$search = '';
				$sort = 'popularity';
				$page = 1;
				include $path . '/pages/videos.php';
			}
			else if(count($routes) == 5) {
				$category = $routes[2];
				$search = '';
				$sort = $routes[3];
				$page = $routes[4];
				include $path . '/pages/videos.php';
			}
			else if(count($routes) == 6) {
				$category = $routes[2];
				$search = $routes[3];
				$sort = $routes[4];
				$page = $routes[5];
				include $path . '/pages/videos.php';
			}
			else if(count($routes) == 7) {
				if($routes[2] == 'ws') {
					$ws = true;
					$category = $routes[3];
					$search = $routes[4];
					$sort = $routes[5];
					$page = $routes[6];
					include $path . '/pages/videos.php';
				}
				else {
					$is_404 = true;
				}
			}
			else {
				$is_404 = true;
			}
			break;
		case 'resources':
			if(count($routes) == 2) {
				$category = 'all';
				$search = '';
				$sort = 'date';
				$page = 1;
				include $path . '/pages/resources.php';
			}
			else if(count($routes) == 3) {
				$category = $routes[2];
				$search = '';
				$sort = 'date';
				$page = 1;
				include $path . '/pages/resources.php';
			}
			else if(count($routes) == 5) {
				$category = $routes[2];
				$search = '';
				$sort = $routes[3];
				$page = $routes[4];
				include $path . '/pages/resources.php';
			}
			else if(count($routes) == 6) {
				$category = $routes[2];
				$search = $routes[3];
				$sort = $routes[4];
				$page = $routes[5];
				include $path . '/pages/resources.php';
			}
			else if(count($routes) == 7) {
				if($routes[2] == 'ws') {
					$ws = true;
					$category = $routes[3];
					$search = $routes[4];
					$sort = $routes[5];
					$page = $routes[6];
					include $path . '/pages/resources.php';
				}
				else {
					$is_404 = true;
				}
			}
			else {
				$is_404 = true;
			}
			break;
		case 'apps':
			if(count($routes) == 2) {
				include $path . '/pages/apps.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'app':
			if(count($routes) == 3) {
				$app = $routes[2];
				if($app == 'lambinapram') {
					include $path . '/pages/lambinapram.php';
				}
				else if($app == 'duckinatruck') {
					include $path . '/pages/duckinatruck.php';
				}
				else {
					$is_404 = true;
				}
			}
			else {
				$is_404 = true;
			}
			break;
		// For legacy iTunes support
		case 'help':
			if(count($routes) == 3) {
				$app = $routes[2];
				if($app == 'lambinapram.html') {
					header( 'Location: /app/lambinapram' ) ;
				}
				else if($app == 'duckinatruck.html') {
					header( 'Location: /app/duckinatruck' ) ;
				}
				else {
					$is_404 = true;
				}
			}
			else {
				$is_404 = true;
			}
			break;
		case 'download': 
			if(count($routes) == 3) {
				$url_name = $routes[2];
				include $path . '/pages/download.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'about':
			if(count($routes) == 2) {
				include $path . '/pages/about.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'facts':
			if(count($routes) == 2) {
				include $path . '/pages/facts.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'privacy':
			if(count($routes) == 2) {
				include $path . '/pages/privacy.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'characters':
			if(count($routes) == 2) {
				include $path . '/pages/characters.php';
			}
			else {
				$is_404 = true;
			}
			break;
		case 'index';
		case '':
			include $path . '/widgets/newgames.php';
			include $path . '/widgets/topgames.php';
			include $path . '/widgets/featuredgames.php';
			$display_description = "Game 103 creates and hosts family-friendly games, entertainment, and development resources. Come see what you can find on Game 103!";
			$display_title = "";
			$display_javascript = $top_games_js . $new_games_js;
			$display_page = "
					
			<!--Newest Games-->
			<div class='new-entries'>
				$top_games
				$featured_games
				$new_games
			</div>
					
			<a href='/games/'>Click here to view many more games!</a>
			";
			break;
		default:
			$is_404 = true;
	}
	
	if($is_404) {
		$display_title = "Error #404";
		$display_description = $display_title;
		$display_page = "Sorry, the page that you are looking for does not exist.
		<a href='/games'>Click here to go to our games page.</a>";
		$display_javascript = "";
	}
	
	if($display_title != "") {
		$display_title = $display_title . " - Game 103: Family-Friendly Games and Entertainment";
	}
	else {
		$display_title = $display_title . "Game 103: Family-Friendly Games and Entertainment";
	}
	
?>

<!DOCTYPE html>

<html>
	
	<head>
		<!-- Meta Tags -->
		<meta name="description" content="<?php echo $display_description ?>">
		<meta name="keywords" content="Games, Development, Internet, Computers, Online, Projects, Programming">
		<meta name="author" content="James Grams">
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<?php echo $display_meta ?>
		
		<!-- Title -->
		<title><?php echo $display_title ?></title>
		
		<!-- Load Style Sheet -->
		<link rel="stylesheet" type="text/css" href="/styles.css">
		
		<!--Google Analytics Function-->
		<script type="text/javascript">
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-4577874-3', 'auto');
		  ga('send', 'pageview');

		</script>
		
		<!--Google JavaScript-->
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
		
		<script>
			<?php echo $display_javascript ?>
		</script>
		
	</head>
	
	<body>
		<div class="page">
			
			<!-- Header -->
			<div class="header">
				<div class='header-title'>
					<a href="/"><img src='/images/logo2016.png' alt='Game 103 logo' class='logo'/></a>
				</div>
			</div>
			
			<!-- Navbar -->
			<?php include $path . '/navbar.html';?>
				
			<!-- Content -->
			<div class="content">
			
				<?php echo $display_page ?>
				
				<!-- End main part of the page -->
				
				<!--Contact and Copyright-->
				<div class="footer">
					<div class="additional-links"><a href="/about">About Us</a> | <a href="/privacy">Privacy Policy</a> | <a href="/facts">Fun Facts</a> | <a href="/games/distributable">Developers</a></div>
					<div class="copyright">&copy; 2016 <a href="https://game103.net">Game 103</a></div>
				</div>
			</div>
		</div>

	</body>
</html>