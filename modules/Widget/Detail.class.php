<?php
	namespace Widget;

	require_once('Constants.class.php');
	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing details for an item.
	*/
	abstract class Detail extends \Widget {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Game Service for expected properties
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			$this->JS[] = "/javascript/detail.js";
			$this->CSS[] = "/css/detail.css";
			// Add entry JavaScript and CSS for similar items
			// Note, these are included in base.css now
			// so they are not needed
		}
		
		/**
		* Generate side boxes
		*/
		abstract protected function generate_side_boxes();
		
		/**
		* Generate ratings box
		*/
		protected function generate_rating() {
			if($this->properties['rating']['votes'] == 1) {
				$vote_str = 'vote';
			}
			else {
				$vote_str = 'votes';
			}
			$total_votes_width = $this->properties['rating']['rating'] * \Constants::STAR_WIDTH . "px";
			return <<<RATING
			<!--Rating-->
			<div id='rating' class='detail-side-box-item responsive'>
				<span class='detail-side-box-title'>Rating</span>
				<div id='show-rating-loading'>
					Loading...
				</div>
				<span id='show-rating-normal'>
					<div class='detail-thanks-for-voting'>
						<div>Thanks for voting today!</div>
						<div>Come back tomorrow to vote again!</div>
					</div>
					<div>Your Rating</div>
					<div class='detail-your-rating'>
						<span class='detail-stars' data-id='{$this->properties['id']}'>
							<div class='detail-star' id='star-1' data-value='1'></div>
							<div class='detail-star' id='star-2' data-value='2'></div>
							<div class='detail-star' id='star-3' data-value='3'></div>
							<div class='detail-star' id='star-4' data-value='4'></div>
							<div class='detail-star' id='star-5' data-value='5'></div>
							<div class='detail-stars-indication'></div>
						</span>
					</div>
					<div>Total Rating</div>
					<div class='detail-total-rating'>
						<span class='detail-stars' id='total-stars'><span style='width: $total_votes_width'></span></span>
					</div>
					<div class='detail-total-votes'>
						{$this->properties['rating']['votes']} $vote_str
					</div>
				</span>
			</div>
RATING;
		}
		
		/**
		* Generate options box.
		*/
		protected function generate_options() {
			$fb_link = "https://www.facebook.com/sharer/sharer.php?u=https%3A//game103.net/game/" . $this->properties['url_name'];
			$twitter_link = "https://twitter.com/home?status=Check%20out%20{$this->properties['name']}%20on%20game103.net%3A%20https%3A//game103.net/game/" . $this->properties['url_name'];
			$pinterest_link = "https://www.pinterest.com/pin/create/link/?url=https%3A//game103.net/game/" . $this->properties['url_name'];
			return <<<OPTIONS
			<!--Options-->
			<div id='options' class='detail-side-box-item responsive'>
				<span class='detail-side-box-title'>
					Options
				</span>
				<span class='detail-zoom-options'>
					Zoom<br>
					<input type='range' min='50' max='175' step='5' value='100' id='zoom-slider' autocomplete='off''/>
					<button id='full'>Fit Screen</button>
					<button id='shrink'>Smaller</button>
					<button id='grow'>Larger</button>
				</span>
				<br>Share<br>
				<a class='button' target='_blank' href="$fb_link">Share on Facebook</a>
				<a class='button' target='_blank' href="$twitter_link">Share on Twitter</a>
				<a class='button' target='_blank' href="$pinterest_link">Share on Pinterest</a>
			</div>
OPTIONS;
		}
		
		/**
		* Generate added date.
		*/
		protected function generate_date() {
			return "Added on " . $this->properties['added_date'];
		}
		
		/**
		* Generate similar items placeholder
		*/
		protected function generate_similar_items_placeholder() {
			return "<span class='detail-similar-items-placeholder' data-id='{$this->properties['id']}'><span>";
		}
		
	}

?>
