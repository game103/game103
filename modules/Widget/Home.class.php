<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');
	require_once("Widget/Find.class.php");
	require_once("Widget/Find/Dated.class.php");

	/**
	*	Widget representing the Front end of the Home page.
	*/
	class Home extends \Widget {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Home Service for expected properties
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			$this->JS[] = "/javascript/home.min.js";
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			if( $this->properties['status'] == 'success' ) {
				// Update properties to prepare for find widget
				$this->properties['weekly']['no_box'] = true;
				$this->properties['monthly']['no_box'] = true;
				$this->properties['top']['no_box'] = true;
				$this->properties['rating']['no_box'] = true;
				$this->properties['featured']['no_box'] = true;
				$this->properties['daily']['no_box'] = true;
				$this->properties['new']['header'] = "New Content";
				$this->properties['new']['footer'] = "<span id='load-earlier-content' class='box-content-footer-link'>Load earlier content</span>";
				
				// Create the sections
				$weekly_section = new \Widget\Find( $this->properties['weekly'] );
				$monthly_section = new \Widget\Find( $this->properties['monthly'] );
				$top_section = new \Widget\Find( $this->properties['top'] );
				$rating_section = new \Widget\Find( $this->properties['rating'] );
				$featured_section = new \Widget\Find( $this->properties['featured'] );
				$daily_section = new \Widget\Find( $this->properties['daily'] );
				$new_section = new \Widget\Find\Dated( $this->properties['new'] );
				
				// Generate the sections
				$weekly_section->generate();
				$monthly_section->generate();
				$top_section->generate();
				$rating_section->generate();
				$featured_section->generate();
				$daily_section->generate();
				$new_section->generate();
				
				$top_box = new \Widget\Box( array(
					'content'		=> array( 
										array( 'title' => 'Top Games This Week', 'content' => $weekly_section->get_HTML() ),
										array( 'title' => 'Top Games This Month', 'content' => $monthly_section->get_HTML() ),
										array( 'title' => 'Top Games of All Time', 'content' => $top_section->get_HTML() ),
										array( 'title' => 'Highest Rated Games', 'content' => $rating_section->get_HTML() ),
										array( 'title' => 'Random Games', 'content' => "<span class='home-random-items-placeholder'><span>" )
										),
					'title'			=> "Top Games",
					'footer'		=> "",
				) );
				$top_box->generate();
				
				$featured_box = new \Widget\Box( array(
					'content'		=> array( 
										array( 'title' => "Editor's Choices", 'content' => $featured_section->get_HTML() ),
										array( 'title' => "Daily Games", 'content' => $daily_section->get_HTML() )
										),
					'title'			=> "Featured Games",
					'footer'		=> "",
				) );
				$featured_box->generate();
				
				// Get box the find JS and CSS (includes box and entry)
				$this->JS = array_merge( $this->JS, $new_section->get_JS() );
				$this->CSS = array_merge( $this->CSS, $new_section->get_CSS() );
				
				$this->HTML = $top_box->get_HTML() . $featured_box->get_HTML() . $new_section->get_HTML();
			}
			// Failure
			else {
				$this->HTML = $this->properties['message'];
			}
			// Return failure
			return $this->HTML;
		}
		
	}

?>