<?php
	namespace Widget;

	require_once('Constants.class.php');
    require_once('Widget.class.php');
    require_once('Widget/Box.class.php');

	/**
	*	Widget representing an admin.
	*/
	abstract class Admin extends \Widget {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Admin Service for expected properties
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			$this->JS[] = '/javascript/admin.js';
			$this->CSS[] = '/css/admin.css';
		}

		/**
		* Generate listing
		*/
		abstract protected function generate_listing();
		
	}

?>
