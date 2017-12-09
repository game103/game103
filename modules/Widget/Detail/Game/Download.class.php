<?php
	namespace Widget\Detail\Game;

	require_once('Constants.class.php');
	require_once('Widget/Detail/Game.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of a Download.
	*/
	class Download extends \Widget\Detail\Game {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Game Service for expected properties
		*/
		public function __construct($properties) {
			\Widget\Detail\Game::__construct($properties);
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			if( $this->properties['status'] == 'failure' ) {
				$this->HTML = $this->properties['message'];
				return;
			}
			
			$html = <<<HTML
			<div class='detail-download-body'>
				<div>
					<p>{$this->properties['name']} is a downloadable game. Once downloaded, the game stores nothing but high scores and save files on your computer. 
					The game has no viruses or other files attached to it. Enjoy!</p>
					<p>By clicking download, you agree not to upload {$this->properties['name']} anywhere on the internet,
					not to sell copies of the game, and not to claim it as your own.</p>
					<a class='button detail-download-button' href='{$this->properties['url']}'>Download</a>
				</div>
				<img src='{$this->properties['screenshot_url']}' alt='{$this->properties['name']} screenshot slideshow' />
			</div>
HTML;
			
			$box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
				'title'			=> $this->properties['name'],
				'tight'			=> 1,
			) );
			$box->generate();
			$this->HTML = $box->get_HTML() . $this->generate_side_boxes();
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
			
			return $this->HTML;
		}
		
		/**
		* Generate side boxes.
		*/
		protected function generate_side_boxes() {
			$information = $this->generate_information();
			$controls = $this->generate_controls();
			
			$html = <<<HTML
			<div class="detail-separator responsive"></div>
			<div class="detail-side-boxes responsive">
				<div class='detail-left-side-box detail-side-box responsive'>
					$controls
				</div>
				<div class='detail-right-side-box detail-side-box responsive'>
					$information
				</div>
			</div>
HTML;
			return $html;
		}
		
		/**
		* Generate plays.
		* Still named plays as it easier with extension
		* (this method is called in super class)
		*/
		protected function generate_plays() {
			if($this->properties['saves'] == 1) {
				$plays_str = 'download';
			}
			else {
				$plays_str = 'downloads';
			}
			return $this->properties['saves'] . " " . $plays_str;
		}
		
	}

?>