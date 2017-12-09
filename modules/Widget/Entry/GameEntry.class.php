<?php
	namespace Widget\Entry;

	require_once('Widget/Entry.class.php');

	/**
	*	Entry for a game.
	*/
	class GameEntry extends \Widget\Entry {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			$properties['type'] = 'game';
			$properties['link'] = '/' . $properties['type'] . '/' . $properties['url_name'];
			$properties['count_verb'] = 'play';
			$properties['count_verb_plural'] = $properties['count_verb'] . 's';
			\Widget\Entry::__construct($properties);
		}
		
	}

?>