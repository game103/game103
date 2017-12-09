<?php
	namespace Widget\Entry;
	
	require_once('Widget/Entry.class.php');

	/**
	*	Entry for a download.
	*/
	class DownloadEntry extends \Widget\Entry {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			$properties['type'] = 'download';
			$properties['link'] = '/' . $properties['type'] . '/' . $properties['url_name'];
			$properties['count_verb'] = 'download';
			$properties['count_verb_plural'] = $properties['count_verb'] . 's';
			\Widget\Entry::__construct($properties);
		}
		
	}

?>