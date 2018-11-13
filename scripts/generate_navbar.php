<?php

	error_reporting(E_ERROR);

	/**
	* Script to load random games of a certain category
	*/
	
	set_include_path("/var/www/game103/modules");
	
	// Require modules
	require_once( 'Constants.class.php');
	require_once( 'Service/Find/GameFind.class.php');
	require_once( 'Service/Find/AppFind.class.php');
	require_once( 'Widget/Find.class.php');
	
	// Connect to database
	$mysqli = new mysqli( Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD);
    
	// Get the categories
	$service = new \Service\Find\GameFind( "", "popularity", $category, 1, 4, $mysqli );
	$service->generate_categories();
	$categories = array_keys($service->get_valid_categories());
	array_unshift( $categories, "all" );
	array_pop( $categories );

	$previews = "";
	$links = "";

	$i=0;
    // Loop through and get the top games for each category
    foreach( $categories as $category ) {
		$service = new \Service\Find\GameFind( "", "popularity", $category, 1, 4, $mysqli );
		$properties = $service->generate();
		$properties['no_box'] = 1;
		$widget = new \Widget\Find( $properties );
		$widget->generate();
		$previews .= "<div class='nav-item-preview-category'>" . $widget->get_HTML() . "</div>";
		// The all category will go straight to the games page
		$name = 'All';
		if( $category != 'all' ) {
			$name = $service->get_valid_categories()[$category]['name'];
			$category = "/$category"; 
		}
		else {
			$category = "";
		}
		$links .= "<div class='nav-item-list-item' onmouseover='showNavbarGames($i)'><a href='/games$category'><span class = 'nav-item-list-item-text'>$name</span></a></div>";
		$i++;
	}
	$service = new \Service\Find\AppFind( "", "popularity", "", 1, 6, $mysqli );
	$properties = $service->generate();
	// We will only include games
	// For now, we can tell this by knowing that only games
	// are on the Apple App Store
	for( $i=sizeof($properties{'items'})-1; $i >= 0; $i-- ) {
		if( !$properties{'items'}[$i]{'store_url_apple'} ) {
			array_splice($properties{'items'}, $i, 1);
		}
	}
	$properties['no_box'] = 1;
	$widget = new \Widget\Find( $properties );
	$widget->generate();
	$previews .= "<div class='nav-item-preview-category'>" . $widget->get_HTML() . "</div>";
   
    $output = <<<HTML
<ul class = "nav">
	<li class="nav-item" id="nav-menu-opener" onclick='toggleMobileMenuDisplay()'>
		<div class="nav-item-dropdown-title" id="menu-title">
			<span class = "nav-item-dropdown-title-text">Menu</span>
			<span id='nav-dropdown-arrow'>
					&#9660;
			</span>
		</div>
	</li>
	<li class="nav-item">
		<div class="nav-item-dropdown-title" id="games-title"><a href="/games"><span class = "nav-item-dropdown-title-text">Games</span></a></div>
		<div class="nav-item-dropdown" id="games-drop-down">
			<div class="nav-item-dropdown-contents">
				<div class="nav-item-list">
					$links
					<div class="nav-item-list-item" onmouseover="showNavbarGames(12)"><a href="/apps"><span class = "nav-item-list-item-text">Game 103 Mobile</span></a></div>
				</div>
				<div class="nav-item-preview">
					$previews
				</div>
			</div>
		</div>
	</li>
	<li class="nav-item">
		<div class="nav-item-dropdown-title" id="game103-title"><a href="/games/game103"><span class = "nav-item-dropdown-title-text">Game 103 Content</span></a></div>
		<div class="nav-item-dropdown" id="game103-drop-down">
			<div class="nav-item-dropdown-contents">
				<div class="nav-item-list">
					<div class="nav-item-list-item"><a href="/games/game103"><span class = "nav-item-list-item-text">Computer Games</span></a></div>
					<div class="nav-item-list-item"><a href="/apps"><span class = "nav-item-list-item-text">Mobile Apps</span></a></div>
					<div class="nav-item-list-item"><a href="/videos/game103"><span class = "nav-item-list-item-text">Videos</span></a></div>
					<div class="nav-item-list-item"><a href="/characters"><span class = "nav-item-list-item-text">Characters</span></a></div>
					<div class="nav-item-list-item"><a href="/games/distributable"><span class = "nav-item-list-item-text">Games for Your Site</span></a></div>
				</div>
			</div>
		</div>
	</li>
	<li class="nav-item">
		<div class="nav-item-dropdown-title" id="more-title"><a href="/videos"><span class = "nav-item-dropdown-title-text">More</span></a></div>
		<div class="nav-item-dropdown" id="more-drop-down">
			<div class="nav-item-dropdown-contents">
				<div class="nav-item-list">
					<div class="nav-item-list-item"><a href="/videos"><span class = "nav-item-list-item-text">Videos</span></a></div>
					<div class="nav-item-list-item"><a href="/apps"><span class = "nav-item-list-item-text">Mobile Apps</span></a></div>
					<div class="nav-item-list-item"><a href="/resources"><span class = "nav-item-list-item-text">Resources</span></a></div>
					<div class="nav-item-list-item"><a target='_blank' href="http://blog.game103.net"><span class = "nav-item-list-item-text">Blog</span></a></div>
					<div class="nav-item-list-item"><a target='_blank' href="https://www.facebook.com/game103"><span class = "nav-item-list-item-text">Facebook</span></a></div>
					<div class="nav-item-list-item"><a target='_blank' href="https://twitter.com/game103games"><span class = "nav-item-list-item-text">Twitter</span></a></div>
					<div class="nav-item-list-item"><a target='_blank' href="https://www.youtube.com/user/game103games"><span class = "nav-item-list-item-text">YouTube</span></a></div>
				</div>
			</div>
		</div>
	</li>
</ul>
HTML;

	print \Constants::sanitize_output( $output );
?>
