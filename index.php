<?php
	error_reporting(E_ERROR);
	
	date_default_timezone_set('America/New_York');
	$path = $_SERVER['DOCUMENT_ROOT'];
	$routes = explode('/', strtok($_SERVER["REQUEST_URI"],'?'));
	$ajax = $_GET['ws'];
	$widgets = array();
	set_include_path(get_include_path() . PATH_SEPARATOR . "modules");
	
	require_once("/Service/Find/GameFind.class.php");
	require_once("/Service/Find/VideoFind.class.php");
	require_once("/Service/Find/ResourceFind.class.php");
	require_once("/Service/Find/AppFind.class.php");
	require_once("/Service/Detail/Game/Browser.class.php");
	require_once("/Service/Detail/Game/Download.class.php");
	require_once("/Service/Detail/Video.class.php");
	
	require_once("/Widget/Find.class.php");
	require_once("/Widget/Detail/Game/Browser.class.php");
	require_once("/Widget/Detail/Game/Download.class.php");
	require_once("/Widget/Detail/Video.class.php");
	
	require_once("/Widget/Box.class.php");
	require_once("/Widget/App/LambInAPram.class.php");
	require_once("/Widget/App/DuckInATruck.class.php");
	require_once("/Widget/App/FlipABlox.class.php");
	require_once("/Service/Characters.class.php");
	require_once("/Widget/Characters.class.php");
	require_once("/Service/Home.class.php");
	require_once("/Widget/Home.class.php");
	require_once("/Widget/About.class.php");
	require_once("/Widget/FunFacts.class.php");
	require_once("/Widget/PrivacyPolicy.class.php");
	require_once('Constants.class.php');
	
	ob_start("\Constants::sanitize_output");
	
	if(end($routes) == '') {
		array_pop($routes);
	}
	
	// Routing
	//$is_404 = false;
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
			}
			else {
				$is_404 = true;
				break;
			}
			$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD );
			$service = new \Service\Detail\Game\Browser( $url_name, $mysqli );
			$generated = $service->generate();
			$widget = new \Widget\Detail\Game\Browser( $generated );
			$widget->generate();
			array_push( $widgets, $widget );
			$content = $widget->get_HTML();
			$title = $generated['name'];
			$description = $generated['description'] . "Play $title on Game 103!";
			$meta = "<meta property='og:image' content='http://game103.net{$generated['image_url']}'>
			<meta property='og:description' content=\"{$generated['description']}n\">";
			break;
		case 'games':
			$type = 'games';
			if(count($routes) == 2) {
				$category = 'all';
				$search = '';
				$sort = 'popularity';
				$page = 1;
			}
			else if(count($routes) == 3) {
				$category = $routes[2];
				$search = '';
				$sort = 'popularity';
				$page = 1;
			}
			else if(count($routes) == 5) {
				$category = $routes[2];
				$search = '';
				$sort = $routes[3];
				$page = $routes[4];
			}
			else if(count($routes) == 6) {
				$category = $routes[2];
				$search = $routes[3];
				$sort = $routes[4];
				$page = $routes[5];
			}
			else {
				$is_404 = true;
				break;
			}
			
			$response = find_response( 'games', $search, $sort, $category, $page, '\Service\Find\GameFind', $ajax, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			break;
		case 'video':
			if(count($routes) == 3) {
				$url_name = $routes[2];
			}
			else {
				$is_404 = true;
				break;
			}
			$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD );
			$service = new \Service\Detail\Video( $url_name, $mysqli );
			$generated = $service->generate();
			$widget = new \Widget\Detail\Video( $generated );
			$widget->generate();
			array_push( $widgets, $widget );
			$content = $widget->get_HTML();
			$title = $generated['name'];
			$description = $generated['description'] . "Watch $title on Game 103!";
			break;
		case 'videos':
			$type = 'videos';
			if(count($routes) == 2) {
				$category = 'all';
				$search = '';
				$sort = 'popularity';
				$page = 1;
			}
			else if(count($routes) == 3) {
				$category = $routes[2];
				$search = '';
				$sort = 'popularity';
				$page = 1;
			}
			else if(count($routes) == 5) {
				$category = $routes[2];
				$search = '';
				$sort = $routes[3];
				$page = $routes[4];
			}
			else if(count($routes) == 6) {
				$category = $routes[2];
				$search = $routes[3];
				$sort = $routes[4];
				$page = $routes[5];
			}
			else {
				$is_404 = true;
				break;
			}
			
			$response = find_response( 'videos', $search, $sort, $category, $page, '\Service\Find\VideoFind', $ajax, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			break;
		case 'resources':
			$type = 'resources';
			if(count($routes) == 2) {
				$category = 'all';
				$search = '';
				$sort = 'popularity';
				$page = 1;
			}
			else if(count($routes) == 3) {
				$category = $routes[2];
				$search = '';
				$sort = 'date';
				$page = 1;
			}
			else if(count($routes) == 5) {
				$category = $routes[2];
				$search = '';
				$sort = $routes[3];
				$page = $routes[4];
			}
			else if(count($routes) == 6) {
				$category = $routes[2];
				$search = $routes[3];
				$sort = $routes[4];
				$page = $routes[5];
			}
			else {
				$is_404 = true;
				break;
			}
			$response = find_response( 'resources', $search, $sort, $category, $page, '\Service\Find\ResourceFind', $ajax, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			break;
		case 'everything':
			$type = 'everything';
			if(count($routes) == 2) {
				$category = '';
				$search = '';
				$sort = 'popularity';
				$page = 1;
			}
			else if(count($routes) == 4) {
				$category = '';
				$search = '';
				$sort = $routes[2];
				$page = $routes[3];
			}
			else if(count($routes) == 5) {
				$category = '';
				$search = $routes[2];
				$sort = $routes[3];
				$page = $routes[4];
			}
			else {
				$is_404 = true;
				break;
			}
			$response = find_response( 'everything', $search, $sort, $category, $page, '\Service\Find', $ajax, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			break;
		case 'apps':
			$type = 'apps';
			if(count($routes) == 2) {
				$category = '';
				$search = '';
				$sort = 'popularity';
				$page = 1;
			}
			else if(count($routes) == 4) {
				$category = '';
				$search = '';
				$sort = $routes[2];
				$page = $routes[3];
			}
			else if(count($routes) == 5) {
				$category = '';
				$search = $routes[2];
				$sort = $routes[3];
				$page = $routes[4];
			}
			else {
				$is_404 = true;
				break;
			}
			$response = find_response( 'apps', $search, $sort, $category, $page, '\Service\Find\AppFind', $ajax, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			break;
		case 'app':
			if(count($routes) == 3) {
				$app = $routes[2];
				if($app == 'lambinapram') {
					$widget = new \Widget\App\LambInAPram();
					$widget->generate();
					$content = $widget->get_HTML();
					$title = "Lamb in a Pram";
					$description = "Gameplay tips, screenshots from the tutorial, credits, an FAQ, and a way to contact the developer for the Game 103 App, Lamb in a Pram.";
					array_push( $widgets, $widget );
				}
				else if($app == 'duckinatruck') {
					$widget = new \Widget\App\DuckInATruck();
					$widget->generate();
					$content = $widget->get_HTML();
					$title = "Duck in a Truck";
					$description = "Gameplay tips, tricks, and mechanics, credits, and a way to contact the developer for the Game 103 iOS app, Duck in a Truck.";
					array_push( $widgets, $widget );
				}
				else if($app == 'flip-a-blox') {
					$widget = new \Widget\App\FlipABlox();
					$widget->generate();
					$content = $widget->get_HTML();
					$title = "Flip-a-Blox";
					$description = "The privacy policy and other information for the Game 103 game, Flip-a-Blox.";
					array_push( $widgets, $widget );
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
			}
			else {
				$is_404 = true;
				break;
			}
			$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD );
			$service = new \Service\Detail\Game\Download( $url_name, $mysqli );
			$generated = $service->generate();
			$widget = new \Widget\Detail\Game\Download( $generated );
			$widget->generate();
			array_push( $widgets, $widget );
			$content = $widget->get_HTML();
			$title = $generated['name'];
			$description = $generated['description'] . "Play $title on Game 103!";
			break;
		case 'about':
			if(count($routes) == 2) {
				$widget = new \Widget\About();
				$widget->generate();
				$content = $widget->get_HTML();
				$title = "About Us";
				$description = "A description of Game 103, which is run by James Grams, a Christian, and was founded in 2008.";
				array_push( $widgets, $widget );
			}
			else {
				$is_404 = true;
			}
			break;
		case 'facts':
			if(count($routes) == 2) {
				$widget = new \Widget\FunFacts();
				$widget->generate();
				$content = $widget->get_HTML();
				$title = "Fun Facts";
				$description = "A list of fun facts about some of the different features and history of Game 103.";
				array_push( $widgets, $widget );
			}
			else {
				$is_404 = true;
			}
			break;
		case 'privacy':
			if(count($routes) == 2) {
				$widget = new \Widget\PrivacyPolicy();
				$widget->generate();
				$content = $widget->get_HTML();
				$title = "About Us";
				$description = "The privacy policy of Game 103.";
				array_push( $widgets, $widget );
			}
			else {
				$is_404 = true;
			}
			break;
		case 'characters':
			if(count($routes) == 2) {
				$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD );
				$service = new \Service\Characters( $mysqli );
				$generated = $service->generate();
				$widget = new \Widget\Characters( $generated );
				$widget->generate();
				array_push( $widgets, $widget );
				$content = $widget->get_HTML();
				$title = 'Characters';
				$description = "The tales behind the various characters that have been in Game 103 games over the years.";
			}
			else {
				$is_404 = true;
			}
			break;
		case 'index';
		case '':
			$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD );
			$service = new \Service\Home( $mysqli );
			$generated = $service->generate();
			$widget = new \Widget\Home( $generated );
			$widget->generate();
			array_push( $widgets, $widget );
			$content = $widget->get_HTML();
			$description = "Game 103 creates and hosts family-friendly games, entertainment, and development resources. Come see what you can find on Game 103!";
			break;
		default:
			$is_404 = true;
	}
	
	// Closing mysqli is not necessary 
	// https://stackoverflow.com/questions/2879500/mysqli-do-i-really-need-to-do-result-close-mysqli-close
	
	// We have a 404
	if( $is_404 ) {
		$title = "Error #404";
		$description = $title;
		$content = "Sorry, the page that you are looking for does not exist.
		<a href='/games'>Click here to go to our games page.</a>";
		http_response_code( 404 );
	}

	// We need a title, description, content variables, and hopefully
	// and array of widgets at this point
	// If we have a main service providing our title and description (maybe meta too)
	
	// Generate js and css
	foreach( $widgets as $widget ) {	
		foreach ( array_unique($widget->get_CSS()) as $css_file ) {
			$css .= "<link rel='stylesheet' type='text/css' href='$css_file'>";
		}
		foreach( array_unique($widget->get_JS()) as $js_file ) {
			$js .= "<script src='$js_file'></script>";
		}
	}
	
	// Response for a find page
	// search redirect -> for non JS searches
	function find_response( $type, $search, $sort, $category, $page, $service_class, $ajax, &$widgets ) {
		if( isset( $_GET['search'] ) ) {
			$search = $_GET['search'];
			if( $ajax ) {
				$ws = '?ws=1';
			}
			if( $category ) {
				$category .= '/';
			}
			if( $search ) {
				header( "Location: /$type/$category$search/$sort/$page$ws" );
			}
			else {
				header( "Location: /$type/$category/$sort/$page$ws" );
			}
		}
		
		$service = new $service_class( $search, $sort, $category, $page, 15, new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD ) );
		$generated = $service->generate();
		$widget = new \Widget\Find( $generated );
		$widget->generate();
		if( $ajax ) {
			$response = array( 
				'content' => \Constants::sanitize_output($widget->get_HTML()), 
				'status' => $generated['status'],
				'title' => $generated['title'] . " - " . Constants::TITLE_APPEND,
				'description' => $generated['description']
			);
			print json_encode( $response );
			die;
		}
		$content = $widget->get_HTML();
		$title = $generated['title'];
		$description = $generated['description'];
		// For JS and CSS
		array_push( $widgets, $widget );
		return array( $content, $title, $description );
	}
?>

<!DOCTYPE html>

<html>
	
	<head>
		<!-- Meta Tags -->
		<meta name="description" content="<?php echo $description ?>">
		<meta name="keywords" content="Games, Development, Internet, Computers, Online, Projects, Programming">
		<meta name="author" content="James Grams">
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<?php echo $meta ?>
		
		<!-- Title -->
		<title><?php echo $title ? $title . " - " . Constants::TITLE_APPEND : Constants::TITLE_APPEND?></title>
		
		<!-- Load Style Sheet -->
		<link rel="stylesheet" type="text/css" href="/css/base.min.css">
		<?php echo $css ?>
		
		<!-- Load JS -->
		<script src='/javascript/base.min.js'></script>
		<?php echo $js ?>
		
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
		
	</head>
	
	<body>
		<div class="page">
			
			<!-- Header -->
			<div class="header">
				<div class='header-title'>
					<a href="/"><img src='/images/logo2016.png' alt='Game 103 logo' class='logo'/></a>
				</div>
				<form class="site-search" action="/everything/popularity/1">
					<input name='search' placeholder="Find games and more!" id="site-search-input" autocomplete="off" type="text"><input type="submit" value="Search" class="button" id="site-search-go">
					<div class="header-dropdown">
						<ul class="header-dropdown-menu" id="site-search-results-dropdown" style="display: block;">
						</ul>
					</div>
				</form>
			</div>
			
			<!-- Navbar -->
			<?php include $path . '/navbar.html';?>
				
			<!-- Content -->
			<div class="content">
			
				<?php echo $content ?>
				
				<!-- End main part of the page -->
				
			</div>
			<!--Contact and Copyright-->
			<div class="footer">
				<div class="additional-links"><a href="/about">About Us</a> | <a href="/privacy">Privacy Policy</a> | <a href="/facts">Fun Facts</a> | <a href="/characters">Characters</a> | <a href="/games/distributable">Developers</a></div>
				<div class="copyright">&copy; 2017 <a href="https://game103.net">Game 103</a></div>
			</div>
		</div>

	</body>
</html>