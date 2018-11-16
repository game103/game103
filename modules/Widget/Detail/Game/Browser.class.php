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
			
			$html = <<<HTML
					<div id='preview-box' style='width:{$this->properties['width']}px;height:{$this->properties['height']}px;'></div>
					<div id='movie-container' class='responsive'>
						$game_code
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
		
		/**
		* Generate special responsive css
		*/
		protected function generate_responsive_css() {
			// We add an extra 20 pixels to account for a possible scrollbar.
			// It's OK if we switch to responsive a little early (the 10 is the border).
			$screen_width = ( (string) $this->properties['width'] + 10 + 20 ) . 'px';
			$game_ratio = $this->properties['height']/$this->properties['width'];
			$padding_bottom = ( (string) $game_ratio * 100) . '%';
			$css = "<style>
				/* This has a known issue when scrollbars are included in the media screen width
				as part of the game will be cut of to the user, even though it would be visible
				if the scrollbars were gone */
				@media screen and (max-width: $screen_width) {
					.box-content-tight {
						width: calc(100% - 10px);
					}
					#movie-container {
						position: relative;
						width: 100%;
						height: 0;
						padding-bottom: $padding_bottom;
					}
					#movie {
						position: absolute;
						width: 100% !important;
						height: 100% !important;
						visibility: visible;
					}
					.box-content-container {
						overflow: hidden;
					}
					.detail-zoom-options {
						display: none;
					}
				}
			</style>";
			return $css;
		}
		
	}

?>
