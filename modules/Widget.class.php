<?php
	/**
	*	Base class for a widget.
	* 	Note: Properties should only be HTML if the HTML is only expected to be generated
	*	in one place (i.e. a subclass) in which HTML is preferred since it is less code
	*	in the parent class.
	*	Note 2: Only abstract classes should have HTML properties UNLESS it is a "wrapper" widget 
	*	I.E. A widget that's purpose is to wrap other widgets (e.g. the box widget)
	*/
	abstract class Widget {
		
		protected $properties;
		protected $HTML;
		protected $JS;
		protected $CSS;
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			$this->properties = $properties;
			$this->HTML = '';
			$this->JS = array();
			$this->CSS = array();
		}
		
		/**
		* Generate the HTML for this widget.
		*/
		public abstract function generate();
		
		/**
		* Get this widget's HTML
		*/
		public function get_HTML() {
			return $this->HTML;
		}
		
		/**
		* Get this widget's JavaScript files
		*/
		public function get_JS() {
			return $this->JS;
		}
		
		/**
		* Get this widget's CSS files
		*/
		public function get_CSS() {
			return $this->CSS;
		}
	}
?>