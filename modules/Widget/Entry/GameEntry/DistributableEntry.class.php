<?php
	namespace Widget\Entry\GameEntry;
	
	require_once('Widget/Entry/GameEntry.class.php');

	/**
	*	Entry for a game 103 game entry with Distribution option.
	*/
	class DistributableEntry extends \Widget\Entry\GameEntry {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			$properties['add_to_site'] = <<<ADD
<span class='entry-distribute-button'>Add to your site!</span>
ADD;
			\Widget\Entry\GameEntry::__construct($properties);
		}
		
	}

?>