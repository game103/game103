<?php
	namespace Widget;
	
	require_once('Widget.class.php');
	
	//namespace Widget;

	/**
	*	Entry for a game, video, or other Game 103 item.
	*/
	class Box extends \Widget {
		
		/**
		*	Constructor.
		*	Required properties
		*		content				array	array of keyed arrays - a title and content for each
		*	Optional properties
		*		title				string	the title for this box
		*		footer				string	HTML to display in the footer
		*		tight				boolean	render the box to tightly fit the content
		*		id					string	the id for the box content
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			$this->JS[] = "/javascript/box.min.js";
			$this->CSS[] = "/css/box.min.css";
		}
		
		public function generate() {
			$title;
			if( $this->properties['title'] ) {
				$title = "<div class='box-content-title'>{$this->properties['title']}</div>";
			}
			
			$buttons = "";
			$all_content = "";
			$count = 0;
			foreach ( $this->properties['content'] as $content ) {
				$button_selected = "";
				$box_selected = "";
				if( $count == 0 ) {
					$button_selected = " box-content-button-selected";
					$box_selected = " box-content-tab-selected";
				}
				$buttons .= "<button class='box-content-button$button_selected'>{$content['title']}</button>";
				$all_content .= "<div class='box-content-tab$box_selected'>{$content['content']}</div>";
				$count += 1;
			}
			if( count($this->properties['content']) == 1 ) {
				$buttons = "";
			}
			
			$footer;
			if ( $this->properties['footer'] ) {
				$footer = "<div class='box-content-footer'>{$this->properties['footer']}</div>";
			}
			
			if ( $this->properties['tight'] ) {
				$tight = " box-content-tight";
				$tight_container = " box-content-container-tight";
			}

			$id;
			if ( $this->properties['id'] ) {
				$id = " id='{$this->properties['id']}'";
			}
			
			$this->HTML .= <<<HTML
<div class='box-content$tight'$id>
	$title
	$buttons
	<div class='box-content-container$tight_container'>
		$all_content
	</div>
	$footer
</div>
HTML;

			return $this->HTML;
		}
		
	}

?>
