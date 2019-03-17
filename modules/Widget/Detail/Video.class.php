<?php
	namespace Widget\Detail;

	require_once('Constants.class.php');
	require_once('Widget/Detail.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of a Video.
	*/
	class Video extends \Widget\Detail {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Video Service for expected properties
		*/
		public function __construct($properties) {
			\Widget\Detail::__construct($properties);
			$this->CSS[] = "/css/detail-video.css";
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			if( $this->properties['status'] == 'failure' ) {
				$this->HTML = $this->properties['message'];
				return;
			}

			$type_string = "";
			// If this is a regular video
			if( $this->properties['video_type'] == "Video" ) {
				$type_string = "{$this->properties['string']}?";
			}
			// This is a playlist
			else {
				$type_string = "videoseries?list={$this->properties['string']}&";
			}

			// Check if it is a Facebook or YouTube video
			if( strpos($type_string, "facebook") !== false ) {
				$iframe = "<iframe style='width:800px;height:450px;' src='https://www.facebook.com/plugins/video.php?href={$type_string}&show_text=0&width=800&mute=0' scrolling='no' frameborder='0' allowTransparency='true' allowFullScreen='true' id='movie'></iframe>";
			}
			else {
				$iframe = "<iframe style='width:800px;height:450px;' allowfullscreen='allowfullscreen' src='https://www.youtube.com/embed/{$type_string}rel=0&amp;modestbranding=1&amp;theme=light&amp;iv_load_policy=3' frameborder='0' id='movie'></iframe>";
			}
			
			$html = <<<HTML
					<div id='preview-box' style='width:800px;height:450px;'></div>
					<div id='movie-container' class='responsive'>
						$iframe
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
			$this->HTML = $box->get_HTML() . $this->generate_side_boxes() . $this->generate_similar_items_placeholder();
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
			
			$html = <<<HTML
			<div class="detail-separator responsive"></div>
			<div class="detail-side-boxes responsive">
				<div class='detail-left-side-box detail-side-box responsive'>
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
		* Generate information box
		*/
		protected function generate_information() {
			$parts = array();
			array_push( $parts, $this->generate_views() );
			array_push( $parts, $this->generate_date() );
			if( $this->properties['games'] ) {
				array_push( $parts, $this->generate_games() );
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
		* Generate views.
		*/
		protected function generate_views() {
			if($this->properties['views'] == 1 && strpos($this->properties['views'], ',') == false) {
				$plays_str = 'view';
			}
			else {
				$plays_str = 'views';
			}
			return $this->properties['views'] . " " . $plays_str;
		}
		
		/**
		* Generate games display.
		*/
		protected function generate_games() {
			$links = array();
			foreach ( $this->properties['games'] as $game_url_name => $game_type_name ) {
				$link = "<a class='detail-side-box-link' href='/{$game_type_name['type']}/$game_url_name'>{$game_type_name['name']}</a>";
				array_push( $links, $link );
			}
			return "Games: " . implode( ', ', $links );
		}

	}

?>
