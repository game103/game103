<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of the About page.
	*/
	class About extends \Widget {
		
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
			<p>Hi, I'm James Grams, and I run Game 103. I am a Christian. Game 103 creates family-friendly games (mostly about animals). We also host other games that fit our brand to serve as a hub for fun and safe games. In addition to games, Game 103 contains entertaining videos and resource links for aspiring developers.
			I strive to make Game 103 a place where people can enjoy fun, quality games no matter their age. I, or some one that I trust, play a game or watch a video before it is placed on Game 103. We do this to ensure that it is entertaining, and to make sure that it does not contain any violence or inappropriate language, themes, or images.</p>
			<p>Game 103 was officially started on May 2, 2008. It began as a website for interesting games and videos.
			Around the time Game 103 started, I began making downloadable games with a program called Game Maker in which I made some simple games. After Game Maker, I moved into Adobe Flash. Since then, I have created multiple flash games and gained a lot of experience in programming ActionScript.
			The desire to learn how to create a website experience unique to different users caused me to start learning PHP and SQL. This began with a simple login system.
			Learning Flash and PHP allowed me to pick up a good amount of HTML and CSS. That's how you're viewing this site today! In 2013, I began studying Computer Science at college and graduated in May, 2017. I currently work as a Web Developer.</p>
			<p>Thanks for visiting, and enjoy the site.</p>
HTML;
			
			$box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
				'title'			=> 'About Us',
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