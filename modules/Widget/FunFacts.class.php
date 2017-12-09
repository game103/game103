<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of the Fun Facts page.
	*/
	class FunFacts extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct() {
			\Widget::__construct( array() );
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			$html = <<<HTML
			<p>Mousie's Adventure was made in three days.</p>
			<p>Trampoline Chickadee was made in 24 hours.</p>
			<p>Cocoa, my dog who features in many Game 103 games, was born on January 2, 2006. He is a chocolate/black labrador mix.</p>
			<p>Elephants are my favorite animal.</p>
			<p>Cocoa is the dog on the left in Rainin' Cats & Dogs. Sophie, the Jack Russell, is the dog on the right.</p>
			<p>Grand Estuary was started in January 2013. It was mostly finished around the beginning of June 2013.</p>
			<p>Kasey, the young lady in The Great Duckdee Chase, is my fiancée. She called ducks, "Duckdees," as a small child.</p>
			<p>Pony's Predicament is based on a classic problem called the Traveling Salesman Problem. The game's twist is having to return to the Post Office.</p>
HTML;
			
			$box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
				'title'			=> 'Fun Facts',
				'footer'		=> '',
			) );
			$box->generate();
			$this->HTML = $box->get_HTML();
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
			
			return $this->HTML;
		}
		
	}

?>