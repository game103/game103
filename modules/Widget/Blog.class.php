<?php
	namespace Widget;

    require_once('Widget.class.php');
    require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Game 103 Blog.
	*/
	class Blog extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
            		$this->CSS[] = "/css/blog.css";
            		$this->JS[] = "/javascript/blog.js";
        	}
		
		/**
		* Generate HTML
		*/
		public function generate() {

            		$box = new \Widget\Box( array(
                		'content'		=> array( array( 'title' => $this->properties["title"], 'content' => $this->properties["content"] ) ),
                		'title'			=> $this->properties["title"],
                		'footer'		=> "",
                		'id'			=> "blog"
            		) );
            		$box->generate();
            
            		// Get box the find JS and CSS (includes box and entry)
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
            		$this->HTML = $box->get_HTML();
            
			return $this->HTML;
		}
		
	}

?>
