<?php
	namespace Widget\Detail\Game;

	require_once('Constants.class.php');
	require_once('Widget/Detail/Game.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of a Browser game.
	*/
	class Browser extends \Widget\Detail\Game {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Game Service for expected properties
		*/
		public function __construct($properties) {
			\Widget\Detail::__construct($properties);
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			if( $this->properties['status'] == 'failure' ) {
				$this->HTML = $this->properties['message'];
				return;
			}
			
			$game_code = "<embed wmode='direct' src='{$this->properties['url']}' style='width:{$this->properties['width']}"."px;height:{$this->properties['height']}"."px;' id='movie'/>";
			
			if( $this->properties['game_type'] == 'Flash' ) {
				$enable_flash = <<<HTML
				<div id="enable-flash" style="width:{$this->properties['width']}px;height:{$this->properties['height']}px;">
					<div id="enable-flash-message">
						You need Flash Player to play {$this->properties['name']}.
						<div id="enable-flash-message-default">
							Please click the button below to download/enable Flash.
						</div>
						<div id="enable-flash-message-ios">
							The best way to play Flash on iOS is with Puffin Web Browser (free).
							<br><br><a href="https://itunes.apple.com/us/app/puffin-web-browser/id472937654" rel="noopener"><button>Get Puffin Web Browser</button></a>
							<br><br>If you already have Puffin installed, click below to this page in Puffin Web Browser.
							<br><br><a href="puffins://game103.net/game/{$this->properties['url_name']}" rel="noopener"><button>Open in Puffin Web Browser</button></a>
							<br><br>If you are not on iOS, click below to download/enable Flash.
						</div>
						<div id="enable-flash-message-android">
							The best way to play Flash on Android is with Puffin Web Browser (free).
							<br><br><a href="https://play.google.com/store/apps/details?id=com.cloudmosa.puffinFree" rel="noopener"><button>Get Puffin Web Browser</button></a>
							<br><br>If you are not on Android, click below to download/enable Flash.
						</div>
						<br><a href="https://get.adobe.com/flashplayer/" rel="noopener"><button>Enable Flash</button></a>
						<br><br>
						If you are having trouble getting Flash to work on your device, please visit our <a href="/flash">Flash Guide</a>.
					</div>
				</div>
HTML;
			}

			$html = <<<HTML
					<div id='preview-box' style='width:{$this->properties['width']}px;height:{$this->properties['height']}px;'></div>
					<div id='movie-container' class='responsive'>
						$game_code
						$enable_flash
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
						
			$this->HTML = $this->generate_responsive_css() . $box->get_HTML() . $this->generate_side_boxes() . $this->generate_similar_items_placeholder();
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
			
			return $this->HTML;
		}
		
		/**
		* Generate side boxes.
		*/
		protected function generate_side_boxes() {
			$options = $this->generate_options();
			$ratings = $this->generate_rating();
			$information = $this->generate_information();
			$controls = $this->generate_controls();
			
			$html = <<<HTML
			<div class="detail-separator responsive"></div>
			<div class="detail-side-boxes responsive">
				<div class='detail-left-side-box detail-side-box responsive'>
					$controls
					$information
				</div>
				<div class='detail-right-side-box detail-side-box responsive'>
					$ratings
					$options
				</div>
			</div>
HTML;
			return $html;
		}
		
		/**
		* Generate plays.
		*/
		protected function generate_plays() {
			if($this->properties['plays'] == 1 && strpos($this->properties['plays'], ',') == false) {
				$plays_str = 'play';
			}
			else {
				$plays_str = 'plays';
			}
			return $this->properties['plays'] . " " . $plays_str;
		}
		
	}

?>
