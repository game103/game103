<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing an App page.
	*/
	abstract class App extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct( $properties ) {
			\Widget::__construct( $properties );
			$this->JS[] = '/javascript/app.min.js';
			$this->CSS[] = '/css/app.min.css';
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			$box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => 'Title', 'content' => $this->generateContent() ),
									),
				'title'		=> $this->properties['title'],
				'footer'		=> "",
			) );
			$box->generate();
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
			$this->HTML = $box->get_HTML();
			return $this->HTML;
		}
		
		/**
		* Generate content
		*/
		abstract protected function generateContent();
		
	}

?>