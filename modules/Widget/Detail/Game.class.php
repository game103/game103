<?php
	namespace Widget\Detail;

	require_once('Constants.class.php');
	require_once('Widget/Detail.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of a Game.
	*/
	abstract class Game extends \Widget\Detail {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Game Service for expected properties
		*/
		public function __construct($properties) {
			\Widget\Detail::__construct($properties);
		}
		
		/**
		* Generate side boxes.
		*/
		
		/**
		* Generate controls
		*/
		protected function generate_controls() {
			// Now that the array is set, create the html
			$keys_arr = array_keys($this->properties['controls']);
			sort($keys_arr);
			$controls = "<ul>";
			$controls_count = 0;
			for($i=0;$i<count($keys_arr);$i++) {
				$key = $keys_arr[$i];
				sort($this->properties['controls'][$key]);
				$action = join('/', $this->properties['controls'][$key]);
				$controls .= "<li>";
				$controls .= "<span class='detail-side-box-alt-text'>" . $key . ":</span> <span class='detail-side-box-text'>" . $action . "</span>";	
				$controls .= "</li>";
				$controls_count ++;
			}
		
			return <<<CONTROLS
				<div id='controls' class='detail-side-box-item responsive'><span class='detail-side-box-title'>Controls</span>$controls</div>
CONTROLS;
		}
		
		/**
		* Generate information box
		*/
		protected function generate_information() {
			$parts = array();
			array_push( $parts, $this->generate_plays() );
			array_push( $parts, $this->generate_date() );
			if( $this->properties['characters'] ) {
				array_push( $parts, $this->generate_characters() );
			}
			if( $this->properties['videos'] ) {
				array_push( $parts, $this->generate_videos() );
			}
			if( $this->properties['game_type'] == 'JavaScript' ) {
				array_push( $parts, $this->generate_fullscreen() );
			}
			$content = implode("<br>", $parts);
			return <<<INFO
			<div id='information' class='detail-side-box-item responsive'>
				<span class='detail-side-box-title'>Information</span>
				$content
			</div>
INFO;
		}
		
		/**
		* Generate plays.
		*/
		protected function generate_plays() {
			if($this->properties['plays'] == 1) {
				$plays_str = 'play';
			}
			else {
				$plays_str = 'plays';
			}
			return $this->properties['plays'] . " " . $plays_str;
		}
		
		/**
		* Generate characters display.
		*/
		protected function generate_characters() {
			$links = array();
			foreach ( $this->properties['characters'] as $character ) {
				$no_space_name = str_replace(" ", "+", $character);
				$link = "<a class='detail-side-box-link' href='/characters#$no_space_name'>$character</a>";
				array_push( $links, $link );
			}
			return "Characters: " . implode( ', ', $links );
		}
		
		/**
		* Generate videos display.
		*/
		protected function generate_videos() {
			$links = array();
			foreach ( $this->properties['videos'] as $video_url_name => $video_name ) {
				$link = "<a class='detail-side-box-link' href='/video/$video_url_name'>$video_name</a>";
				array_push( $links, $link );
			}
			return "Videos: " . implode( ', ', $links );
		}
		
		/**
		* Generate fullscreen.
		*/
		protected function generate_fullscreen() {
			return "<a class='detail-side-box-link' href='{$this->properties['url']}'>Play Full Screen</a>";
		}
		
	}

?>