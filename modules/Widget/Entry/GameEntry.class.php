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
			$properties['type_icon'] = '<i class="fas fa-gamepad"></i>';
			if( $properties['game_type'] == 'JavaScript' ) {
				$properties['type_icon'] .= ' <i class="fab fa-html5"></i>';
			}
			else {
				$properties['type_icon'] .= ' <i class="fab fa-adobe"></i>';
			}
			$properties['link'] = '/' . $properties['type'] . '/' . $properties['url_name'];
			$properties['count_verb'] = 'play';
			$properties['count_verb_plural'] = $properties['count_verb'] . 's';
			\Widget\Entry::__construct($properties);
		}
		
	}

?>
