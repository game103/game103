<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of the Characters application.
	*/
	class Characters extends \Widget {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Characters Service for expected properties
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			$this->CSS[] = "/css/characters.css";
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			// Get the array with the result from fetching items from the db
			if( $this->properties['status'] == 'success' ) {
				$items = $this->properties['items'];
				$items_section = "";
				for( $i=0; $i<sizeof( $items ); $i++ ) {
					$items_section .= $this->generate_character( $items[$i] );
				}
			}
			else {
				// Set the items to be the message
				$items_section = $this->properties['message'];
			}
			
			$this->HTML = $items_section;
			$box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $items_section ),
									),
				'title'			=> 'Characters',
				'footer'		=> '',
			) );
			$box->generate();
			$this->HTML = $box->get_HTML();
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
			
			return $this->HTML;
		}
		
		/**
		* Generate character
		*/
		protected function generate_character( $item ) {
			
			$games_list = array();
			foreach( $item['games'] as $game ) {
				$games_list[] = "<a class='characters-game-link' href='/{$game['type']}/{$game['url_name']}'>{$game['name']}</a>";
			}
			$games = implode( ", ", $games_list );
			
			$no_space_name = str_replace(" ", "+", $item['name']);
			return <<<HTML
		<div class='characters-entry'>
			<a name="$no_space_name"></a>
			<div class='characters-image-container'>
				<div class='characters-image-size-boundaries'>
					<img class='characters-image' src='{$item['image_src']}'/>
				</div>
			</div>
			<div class='characters-text-container'>
				<div class='characters-name'>{$item['name']}</div>
				<div class='characters-ipa-name'>[{$item['ipa_name']}]</div>
				<div class='characters-appears'>Appears in: $games</div>
				<div class='characters-description'>{$item['description']}</div>
			</div>
		</div>
HTML;
		}
		
	}

?>
