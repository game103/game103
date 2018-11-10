<?php
	namespace Widget\Entry;

	require_once('Widget/Entry.class.php');

	/**
	*	Entry for a video.
	*/
	class VideoEntry extends \Widget\Entry {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			$properties['type'] = 'video';
			$properties['type_icon'] = '&#127902;';
			$properties['link'] = '/' . $properties['type'] . '/' . $properties['url_name'];
			$properties['count_verb'] = 'view';
			$properties['count_verb_plural'] = $properties['count_verb'] . 's';
			\Widget\Entry::__construct($properties);
		}
		
	}

?>
