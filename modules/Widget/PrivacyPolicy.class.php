<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of the Privacy Policy page.
	*/
	class PrivacyPolicy extends \Widget {
		
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
			<p>Your privacy will be respected on Game 103. Any personal information entered in emails or databases will not shared with anyone outside of Game 103.</p>
			<p>Unless you ask to be sent emails, you will not be sent any.</p>
			<p>Many Game 103 games store cookies on your computer. These cookies save your progress for the next time you play the games. Cookies are simply text files on your computer. These games only store game information, and no personal information is stored.</p>
			<p>Game 103 downloadable games will be saved on your computer once downloaded. They have no viruses or malware attached with them. The game will store high scores and save states on your computer. These scores and saves are private to your computer. Nothing from these games will be put online or shared with anyone.</p>
			<p><b>Grand Estuary</b> is a online-based game. The email you use to sign up will be kept private, and the only emails you will ever receive will be the ones that you request if you forget your username or password. Your username, password, chat messages, and other variables within <b>Grand Estuary</b> are all visible to Game 103. Please be mindful of this when chatting with other players. Your password will never be given out unless you request it. In that case, your password will be sent to your email account.</p>
			<p>Many of the games on Game 103 not created by Game 103 (found on the games page) will store cookies on your computer in the same way many Game 103 games do. I trust the games that I put on my website, and I have personally played all of them. I have found no danger in any of them.</p>
			<p>If you created a game that is on Game 103 and you want it removed, email me at <a href = "mailto:james@game103.net">james@game103.net</a>, and I will remove the game as soon as possible.</p>
HTML;
			
			$box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
				'title'			=> 'Privacy Policy',
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