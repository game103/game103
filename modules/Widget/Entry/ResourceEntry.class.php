<?php
	namespace Widget\Entry;
	
	require_once('Widget/Entry.class.php');

	/**
	*	Entry for a resource.
	*/
	class ResourceEntry extends \Widget\Entry {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			$properties['type'] = 'resource';
			$properties['type_icon'] = '<i class="fas fa-tools"></i>';
			$properties['count_verb'] = 'visit';
			$properties['count_verb_plural'] = $properties['count_verb'] . 's';
			$properties['target'] = '_blank';
			$properties['rel'] = 'noopener';
			unset( $properties['rating'] );
			// For data attribute
			if( !$properties['link'] ) {
				$properties['link'] = $properties['url_name'];
			}
			$properties['url_name'] = $properties['link'];
			\Widget\Entry::__construct($properties);
		}
		
	}

?>
