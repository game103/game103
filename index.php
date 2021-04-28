<?php
	error_reporting(E_ERROR);

	// Check dark mode cookie
	if(isset($_COOKIE['dark']) && $_COOKIE['dark']) {
		$dark_mode = ' class="dark"';
	}
	
	// Check if we can respond with Cache
	
	if( !$_GET['no_cache'] ) {
		$cached_file = str_replace( "?", "-", str_replace("/", "-", $_SERVER["REQUEST_URI"]) );
		$cached_file = $_SERVER['DOCUMENT_ROOT'] . "/cache/" . $cached_file . ".html";
		if( file_exists( $cached_file ) ) {
			$contents = file_get_contents( $cached_file );
			if( $dark_mode ) {
				$contents = preg_replace("/<body>/", "<body$dark_mode>", $contents, 1);
			}
			if(isset($_COOKIE['html5']) && $_COOKIE['html5']) {
				$contents = preg_replace("/Play in HTML5 \(Beta\)<\/button>/", "Play in Flash</button>", $contents);
				preg_match('/\?v=([^"\']+)/', $contents, $matches);
				$commit_hash = $matches[1];
				$contents = preg_replace("/<\/head>/", "<script defer src=\"/javascript/ruffle/ruffle.js?v=$commit_hash\"></script></head>", $contents, 1);
			}
			print $contents;
			exit;
		}
	}
	
	// End cache section
	
	date_default_timezone_set('America/New_York');
	$base_url = 'https://game103.net';
	$request_uri = strtok($_SERVER["REQUEST_URI"],'?');
	$routes = explode('/', $request_uri);
	$ajax = $_GET['ws'];
	$widgets = array();
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	require_once('Constants.class.php');
	
	// Redirects
	$redirects = array(
		"/gamepages/cocoaball/Ball%20Launch.swf" => "/game103games/flash/cocoaball/build/game.swf",
		"/gamepages/cocoaball/" => "/game103games/flash/cocoaball/src/",
		"/PasswordRecover.php" => "/game103games/flash/grandestuary/PasswordRecover.php",
		"/HouseKey/" => "/game103games/flash/grandestuary/src/",
		"/HouseKey/HouseKey.swf" => "/game103games/flash/game.swf",
		"/game103games/flash/duckdee/" => "/game103games/flash/thegreatduckdeechase/src/",
		"/game103games/flash/thegreatduckdeechase.swf" => "/game103games/flash/thegreatduckdeechase/build/game.swf",
		"/game103games/flash/cte/" => "/game103games/flash/clicktheelephant/src/",
		"/fun/" => "/game103games/flash/clicktheelephant/src/",
		"/game103games/flash/clicktheelephant.swf" => "/game103games/flash/clicktheelephant/build/game.swf",
		"/game103games/flash/pony/" => "/game103games/flash/ponyspredicament/src/",
		"/game103games/flash/pony/game.swf" => "/game103games/flash/ponyspredicament/build/game.swf",
		"/game103games/flash/daxpy/" => "/game103games/flash/daxpythedino/src/",
		"/game103games/flash/daxpy/game.swf" => "/game103games/flash/daxpythedino/build/game.swf",
		"/Solitaire.swf" => "/game103games/flash/wooltycoon/build/solitaire.swf"
	);
	// First check exact match
	if( $redirects[$request_uri] ) {
		header('Location: ' . $redirects[$request_uri]);
		die();
	}
	// Then check for starts with
	else {
		foreach ( $redirects as $key => $value ) {
			if( substr( $request_uri, 0, strlen($key) ) === $key ) {
				$new_uri = substr_replace( $request_uri, $value, 0, strlen($key) );
				if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
					Constants::redirect_post( $base_url . $new_uri, $_POST );
				}
				else {
					header('Location: ' . $new_uri);
				}
				die();
			}
		}
	}
	
	require_once("Service/Find/GameFind.class.php");
	require_once("Service/Find/GameFind/Random.class.php");
	require_once("Service/Find/VideoFind.class.php");
	require_once("Service/Find/VideoFind/Random.class.php");
	require_once("Service/Find/ResourceFind.class.php");
	require_once("Service/Find/AppFind.class.php");
	require_once("Service/Detail/Game/Browser.class.php");
	require_once("Service/Detail/Game/Download.class.php");
	require_once("Service/Detail/Video.class.php");
	require_once("Service/Admin/Game.class.php");
	require_once("Service/Admin/Video.class.php");
	require_once("Service/Admin/Resource.class.php");
	require_once("Service/Login.class.php");
	
	require_once("Widget/Find.class.php");
	require_once("Widget/Detail/Game/Browser.class.php");
	require_once("Widget/Detail/Game/Download.class.php");
	require_once("Widget/Detail/Video.class.php");
	
	require_once("Widget/Box.class.php");
	require_once("Widget/App/LambInAPram.class.php");
	require_once("Widget/App/DuckInATruck.class.php");
	require_once("Widget/App/FlipABlox.class.php");
	require_once("Service/Characters.class.php");
	require_once("Widget/Characters.class.php");
	require_once("Service/Home.class.php");
	require_once("Widget/Home.class.php");
	require_once("Widget/About.class.php");
	require_once("Widget/FunFacts.class.php");
	require_once("Widget/PrivacyPolicy.class.php");
	require_once("Widget/Blog.class.php");
	require_once("Widget/Admin/Game.class.php");
	require_once("Widget/Admin/Video.class.php");
	require_once("Widget/Admin/Resource.class.php");
	require_once("Widget/Login.class.php");
	require_once("Widget/Store.class.php");
	require_once("Widget/FlashGuide.class.php");
	require_once("Widget/Account.class.php");
	
	ob_start("\Constants::sanitize_output");
	
	if(end($routes) == '') {
		array_pop($routes);
	}
	
	// Schema JSON object for breadcrumbs (good for SEO, they use this in results)
	$breadcrumbs = array(
		"@context" 	=> "http://schema.org",
		"@type"		=>	"BreadcrumbList",
		"itemListElement" => array()
	);
	$GAMES_BREADCRUMBS_NAME = "Games";
	$VIDEOS_BREADCRUMBS_NAME = "Videos";
	$APPS_BREADCRUMBS_NAME = "Apps";
	$RESOURCES_BREADCRUMBS_NAME = "Resources";
	$COMMIT_HASH = shell_exec("git rev-parse HEAD");
	
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
			$description = $generated['description'] . " Play $title on Game 103!";
			$fb_image_url = "/images/icons/games/bordered/" . basename($generated['image_url']);
			$fb_image_url = file_exists( $_SERVER['DOCUMENT_ROOT'] . $fb_image_url ) ? $fb_image_url : $generated['image_url'];
			$meta = "<meta property='og:image' content='https://game103.net{$fb_image_url}'>
			<meta property='og:description' content=\"{$generated['description']}\"><meta name='twitter:card' content='summary'>
			<meta name='twitter:site' content='@game103games'><meta name='twitter:description' content=\"{$generated['description']}\">
			<meta name='twitter:title' content=\"$title\"><meta name='twitter:image' content='https://game103.net{$fb_image_url}'>";
			$breadcrumbs = add_breadcrumb( $breadcrumbs, $base_url . "/games", $GAMES_BREADCRUMBS_NAME ); 
			break;
		case 'games':
			$type = 'games';
			$platform = 'any';
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
				$platform = $routes[2];
				$category = $routes[3];
				$search = '';
				$sort = $routes[4];
				$page = $routes[5];
			}
			else if(count($routes) == 7) {
				$platform = $routes[2];
				$category = $routes[3];
				$search = $routes[4];
				$sort = $routes[5];
				$page = $routes[6];
			}
			else {
				$is_404 = true;
				break;
			}

			$response = find_response( 'games', $search, $sort, $category, $page, '\Service\Find\GameFind', $ajax, $platform, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			$breadcrumb_title = $GAMES_BREADCRUMBS_NAME;
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
			$description = $generated['description'] . " Watch $title on Game 103!";
			$breadcrumbs = add_breadcrumb( $breadcrumbs, $base_url . "/videos", $VIDEOS_BREADCRUMBS_NAME ); 
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
			
			$response = find_response( 'videos', $search, $sort, $category, $page, '\Service\Find\VideoFind', $ajax, null, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			$breadcrumb_title = $VIDEOS_BREADCRUMBS_NAME;
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
			$response = find_response( 'resources', $search, $sort, $category, $page, '\Service\Find\ResourceFind', $ajax, null, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			$breadcrumb_title = $RESOURCES_BREADCRUMBS_NAME;
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
			$response = find_response( 'everything', $search, $sort, $category, $page, '\Service\Find', $ajax, null, $widgets );
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
			$response = find_response( 'apps', $search, $sort, $category, $page, '\Service\Find\AppFind', $ajax, null, $widgets );
			$content = $response[0];
			$title = $response[1];
			$description = $response[2];
			$breadcrumb_title = $APPS_BREADCRUMBS_NAME;
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
			$breadcrumbs = add_breadcrumb( $breadcrumbs, $base_url . '/apps', $APPS_BREADCRUMBS_NAME );
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
			$description = $generated['description'] . " Play $title on Game 103!";
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
				$title = "Privacy Policy";
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
		case 'blog':
			if(count($routes) == 2) {
				$widget = new \Widget\Blog();
				$widget->generate();
				array_push( $widgets, $widget );
				$content = $widget->get_HTML();
				$title = 'Blog';
				$meta = '<link rel="alternate" type="application/rss+xml" title="Subscribe" href="https://game103blog.blogspot.com/feeds/posts/default"/>';
				$description = "The Game 103 blog containing updates about the site.";
			}
			else {
				$is_404 = true;
			}
			break;
		case 'admin':
			session_start(); // Start the PHP session
			$meta = '<meta name="robots" content="noindex">'; // Don't index administration

			// If we are not logged in, but we have a username and password indicating
			// a login attempt, attempt to login
			if( !$_SESSION['logged_in'] && $_POST['username'] && $_POST['password'] ) {
				$service = new \Service\Login( $_POST['username'], $_POST['password'] );
				$generated = $service->generate(); // Attempt to login
				$_POST = array(); // Clear out anything in post
			}
			// If we are logged in
			if( $_SESSION['logged_in'] ) {
				if(count($routes) == 3 || count($routes) == 4) {

					$admin_type = $routes[2];
					$url_name = $routes[3];

					$class;
					if( $admin_type == "game" ) {
						$class = "Game";
					}
					else if( $admin_type == "video" ) {
						$class = "Video";
					}
					else if( $admin_type == "resource" ) {
						$class = "Resource";
					}
					
					if( $class ) {
						$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD );
						$service_class = "\Service\Admin\\$class";
						$service = new $service_class( $_POST, $url_name, $mysqli );
						$generated = $service->generate();
						if( $generated["status"] != "failure" ) { // "failure" doesn't mean user error in form; rather, trying to edit an invalid item
							$widget_class = "\Widget\Admin\\$class";
							$widget = new $widget_class( $generated );
							$widget->generate();
							array_push( $widgets, $widget );
							$content = $widget->get_HTML();
							$title = "$class Admin";
							$description = "Game 103 $class Admin";
						}
						else { $is_404 = true; }
					}
					else { $is_404 = true; }	
				} 
				else { $is_404 = true; }
			}
			else {
				// Generated will already exist if this was a previous login attempt
				// and it will have a message
				if( !$generated ) {
					$generated = array();
				}
				// The action for the login form will be the current attempted location
				$generated['action'] = "/admin/" . $routes[2] . "/" . $routes[3];
				$widget = new \Widget\Login( $generated );
				$widget->generate();
				array_push( $widgets, $widget );
				$content = $widget->get_HTML();
				$title = "Login";
				$description = "";
			}
			break;
		case 'random':
			if(count($routes) == 2) {
				$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD );
				$service = new \Service\Find\GameFind\Random( 'all', null, $mysqli );
				$generated = $service->generate();
				header('Location: ' . '/game/' . $generated['items'][0]['url_name']);
			}
			else {
				$is_404 = true;
			}
			break;
		case 'store':
			if(count($routes) == 2) {
				$widget = new \Widget\Store();
				$widget->generate();
				$content = $widget->get_HTML();
				$title = "Store";
				$description = "A Store where you can order Game 103 Merchandise.";
				array_push( $widgets, $widget );
			}
			else {
				$is_404 = true;
			}
			break;
		case 'flash':
			if(count($routes) == 2) {
				$widget = new \Widget\FlashGuide();
				$widget->generate();
				$content = $widget->get_HTML();
				$title = "Flash Player Guide";
				$description = "A guide to running Adobe Flash Player as painlessly as possible on various browsers and devices in 2019.";
				array_push( $widgets, $widget );
			}
			else {
				$is_404 = true;
			}
			break;
		case 'account':
			if(count($routes) == 2) {
				$widget = new \Widget\Account();
				$widget->generate();
				$content = $widget->get_HTML();
				$title = "Account";
				$description = "Manage your Game 103 account.";
				array_push( $widgets, $widget );
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
			$description = "Game 103 creates and hosts family-friendly games and entertainment. Come see what you can find on Game 103!";
			$website_schema = array(
				"@context" => "http://schema.org",
				"@type" => "WebSite",
				"url"	=>	$base_url,
				"potentialAction"	=> array(
					"@type"		=>	"SearchAction",
					"target"	=>	"$base_url/everything/{search_term_string}/popularity/1",
					"query-input"=> "required name=search_term_string"
				)
			);
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
		$content = "Sorry, the page that you are looking for does not exist.<br>
		<a href='/games'>Click here to go to our games page.</a>";
		http_response_code( 404 );
	}
	
	// If this is not a 404 and not home, use a breadcrumb
	if( $title && !$is_404 ) {
		$breadcrumbs = add_breadcrumb( $breadcrumbs, $base_url . $request_uri, $breadcrumb_title ? $breadcrumb_title : $title );
	}

	// We need a title (or not for home page ), description, content variables, and hopefully
	// and array of widgets at this point
	// we may also have metadata and some schema objects to encode as json
	if( sizeof( $breadcrumbs['itemListElement'] ) > 0 || $website_schema) {
		$schema_json = "<script type='application/ld+json'>";

		if( sizeof( $breadcrumbs['itemListElement'] ) > 0 ) {
			$schema_json .= json_encode( $breadcrumbs );
		}
		if( $website_schema ) {
			$schema_json .= json_encode( $website_schema );
		}
		
		$schema_json .= "</script>";
	}
	
	// Generate js and css for all the widgets
	foreach( $widgets as $widget ) {
		foreach ( array_unique($widget->get_CSS()) as $css_file ) {
			if (strpos($css_file, 'http') !== false) {
				$css .= "<link rel='stylesheet' type='text/css' href='$css_file'>";
			}
			// Inline Game 103 styles
			else {
				$file = fopen( dirname(__FILE__) . $css_file, "r" );
				$css .= "<style>" . fread( $file,filesize(dirname(__FILE__) . $css_file) ) . "</style>";
				fclose($file);
			}
		}
		foreach( array_unique($widget->get_JS()) as $js_file ) {
			if( strpos($js_file, "http") === false ) {
				$js_file .= "?v=$COMMIT_HASH";
			}
			$js .= "<script defer src='$js_file'></script>";
		}
	}
	
	// Response for a find page
	// search redirect -> for non JS searches
	function find_response( $type, $search, $sort, $category, $page, $service_class, $ajax, $platform, &$widgets ) {
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
		
		if( $platform ) {
			$service = new $service_class( $search, $sort, $category, $page, 50, $platform, new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD ) );
		}
		else {
			$service = new $service_class( $search, $sort, $category, $page, 50, new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD ) );
		}

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
	
	
	// Add a breadcrumb to the breadcrumbs schema
	function add_breadcrumb( $breadcrumbs, $url, $name ) {
		$item = array(
			"@type" 	=> "ListItem",
			"position"	=>	sizeof( $breadcrumbs['itemListElement'] ) + 1,
			"item"		=>	array(
				"@id"	=>	$url,
				"name"	=>	$name
			)
		);
		array_push( $breadcrumbs['itemListElement'], $item );
		return $breadcrumbs;
	}
	
?>

<!DOCTYPE html>

<html lang="en" class="no-js">
	
	<head>
		<!-- Meta Tags -->
		<meta name="description" content="<?php echo $description ?>">
		<meta name="keywords" content="Games, Development, Internet, Computers, Online, Projects, Programming">
		<meta name="author" content="James Grams">
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
		<meta name="theme-color" content="#3274da">
		<!--Preload woff2-->
		<link rel="preload" as="font" type="font/woff2" href="/fonts/opensans.woff2" crossorigin />
		<link rel="apple-touch-icon" href="/images/logoiconsocialmedia.png">
		<link rel="manifest" href="/manifest.json" />
		<?php echo $meta ?>
		
		<!-- Title -->
		<title><?php echo $title ? $title . " - " . Constants::TITLE_APPEND : Constants::TITLE_APPEND?></title>
		
		<!-- Load Style Sheet -->
		<?php $file = fopen( dirname(__FILE__) . "/css/base.css", "r" );
			$css = "<style>" . fread( $file,filesize(dirname(__FILE__) . "/css/base.css") ) . "</style>" . $css;
			fclose($file); ?>
		<?php $file = fopen( dirname(__FILE__) . "/css/fontawesome.css", "r" );
			$css = "<style>" . fread( $file,filesize(dirname(__FILE__) . "/css/base.css") ) . "</style>" . $css;
			fclose($file); ?>
		<?php echo $css ?>
		
		<!-- Load JS -->
		<script>document.documentElement.classList.remove("no-js");</script>
		<script defer src='/javascript/base.js?v=<?php echo $COMMIT_HASH?>'></script>
		<?php echo $js ?>
		
		<!--Include schema-->
		<?php echo $schema_json ?>
	</head>
	
	<body<?php echo $dark_mode?>>
		<div class="page">
			
			<!-- Header -->
			<header class="header">
				<div class='header-title'>
					<a href="/">
						<picture>
							<source srcset="/images/logo2016.webp" type="image/webp">
							<source srcset="/images/logo2016.png"> 
							<img src="/images/logo2016.png" alt="Game 103 logo" class='logo'>
						</picture>
					</a>
				</div>
				<form class="site-search" action="/everything/popularity/1">
					<label for="site-search-input" id="site-search-label">
						<span id="site-search-label-text">Search: </span>
						<input name='search' placeholder="Find games and more!" id="site-search-input" autocomplete="off" type="text"><input type="submit" value="Search" class="button" id="site-search-go">
					</label>
					<div class="header-dropdown">
						<ul class="header-dropdown-menu" id="site-search-results-dropdown" style="display: block;">
						</ul>
					</div>
				</form>
			</header>
			
			<!-- Navbar -->
			<nav>
				<?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.html';?>
			</nav>
				
			<!-- Content -->
			<main class="content">
			
				<?php echo $content ?>
				
			</main>
			<!-- End main part of the page -->

			<!--Contact and Copyright-->
			<footer class="footer">
				<div class="additional-links">
					<div class="additional-links-section">
						<div class="additional-links-section-heading">Navigation</div>
						<a href="/">Home</a>
						<a href="/games">Games</a>
						<a href="/games/game103">Game 103 Games</a>
						<a href="/videos">Videos</a>
						<a href="/resources">Resources</a>
						<a href="/apps">Mobile Apps</a>
						<a href="/everything">Search</a>
						<a href="/blog">Blog</a>
					</div>
					<div class="additional-links-section">
						<div class="additional-links-section-heading">Social</div>
						<a target='_blank' rel="noopener" href="https://www.facebook.com/game103"><i class="fab fa-facebook"></i>Facebook</a>
						<a target='_blank' rel="noopener" href="https://twitter.com/game103games"><i class="fab fa-twitter"></i>Twitter</a>
						<a target='_blank' rel="noopener" href="https://www.instagram.com/jamescocoagrams/"><i class="fab fa-instagram"></i>Instagram</a>
						<a target='_blank' rel="noopener" href="https://www.youtube.com/user/game103games"><i class="fab fa-youtube"></i>YouTube</a>
						<div class="additional-links-section-heading addition-links-section-heading-second">Stores</div>
						<a target='_blank' rel="noopener" href="https://itunes.apple.com/tt/developer/james-grams/id894750819"><i class="fab fa-app-store-ios"></i>Apple App Store</a>
						<a target='_blank' rel="noopener" href="https://play.google.com/store/apps/developer?id=James+Grams"><i class="fab fa-google-play"></i>Google Play</a>
						<a href="/store">Game 103 Store</a>
					</div>
					<div class="additional-links-section">
						<div class="additional-links-section-heading">Extras</div>
						<a href="/facts">Fun Facts</a>
						<a href="/characters">Characters</a>
						<a href="/random">Random Game</a>
						<a onclick="toggleDarkMode()" href="javascript:;">Toggle Dark Mode</a>
						<div class="additional-links-section-heading addition-links-section-heading-second">Information</div>
						<a href="/about">About Us</a>
						<a href="/flash">Flash Player Guide</a>
						<a href="/privacy">Privacy Policy</a>
						<a href="/account">Account Settings</a>
					</div>
					<div class="additional-links-section">
						<div class="additional-links-section-heading">Contact</div>
						<a href="mailto:james@game103.net"><i class="fas fa-envelope"></i>Email</a>
						<a target='_blank' rel="noopener" href="https://m.me/game103"><i class="fab fa-facebook-messenger"></i>Messenger</a>
						<a target='_blank' rel="noopener" href="https://twitter.com/messages/compose?recipient_id=789785198707761156"><i class="fab fa-twitter"></i>Twitter DM</a>
						<div class="additional-links-section-heading addition-links-section-heading-second">Developers</div>
						<a href="/games/distributable">Games for Your Site</a>
						<a target='_blank' rel="noopener" href="https://github.com/game103/game103"><i class="fab fa-github"></i>Source Code</a>
					</div>
				</div>
				<div class="copyright">&copy; 2008-<?php echo date("Y");?> <a href="https://game103.net">Game 103</a></div>
			</footer>
		</div>

	</body>
</html>
