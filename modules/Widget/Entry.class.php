<?php
	namespace Widget;

	require_once('Constants.class.php');
	require_once('Widget.class.php');

	/**
	*	Entry for a game, video, or other Game 103 item.
	*/
	abstract class Entry extends \Widget {
		
		/**
		*	Constructor.
		*	Required properties
		*		id					number	the id of this entry
		*		title				string	the title of of the entry
		*		image_src			string	the image src of the entry
		*		description			string	the description of the entry
		*		link				string	the link for this entry
		*		type				string 	the item's type (you probably won't have to set as subclass will)
		*	Optional properties
		*		url_name			string	the url name for this entry (likely based off of title)
		*		count				number	the number of interactions with the entry
		*		count_verb			string	the verb to use for interactions (e.g. plays)
		*		count_verb_plural	string	the plural version of the count verb
		*		rating				string	the rating for the entry
		*		target				string	the target to open the link with
		* 		app_store_logo		string	HTML displaying links to an app store(s)
		*		add_to_site			string	HTML displaying add to site
		*		time_count			number	the number of interactions within a time frame
		*		time_frame			string	the time frame for the time count (i.e. weekly or monthly)
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			$this->JS[] = "/javascript/entry.min.js";
			$this->CSS[] = "/css/entry.min.css";
		}
		
		public function generate() {
			$count;
			$rating;
			$target;
			if( $this->properties['count'] ) {
				$count_verb = $this->properties['count_verb'] ?: 'visit';
				if( $this->properties['count'] > 1 ) {
					$count_verb = $this->properties['count_verb_plural'] ?: 'visits';
				}
				// Time frame count
				if( $this->properties['time_frame'] && $this->properties['time_count'] ) {
					$time_count = " ({$this->properties['time_count']} this {$this->properties['time_frame']})";
				}
				$count = "<span class='entry-plays'>{$this->properties['count']} $count_verb$time_count</span>";
			}
			if( isset( $this->properties['rating'] ) ) {
				$rating_width = \Constants::STAR_WIDTH * $this->properties['rating'] . 'px';
				$rating = "<span class='entry-stars'><span style='width: $rating_width'></span></span>";
			}
			if( $this->properties['target'] ) {
				$target = 'target="' . $this->properties['target'] . '"';
			}
			
			$this->HTML .= <<<HTML
<a href="{$this->properties['link']}" $target class='entry-link' data-id="{$this->properties['id']}" data-type="{$this->properties['type']}" data-url-name="{$this->properties['url_name']}">
	<span class="entry-item">
		<img alt="{$this->properties['title']}" src="{$this->properties['image_src']}">
		<span class="entry-title">{$this->properties['title']}</span>
		$rating
		<span class="entry-description"> {$this->properties['description']}</span>
		{$this->properties['app_store_logo']}
		$count
		{$this->properties['add_to_site']}
	</span>
</a>
HTML;

			return $this->HTML;
		}
		
	}

?>