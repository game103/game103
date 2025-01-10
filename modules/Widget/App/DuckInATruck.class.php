<?php
	namespace Widget\App;

	require_once('Widget/App.class.php');

	/**
	*	Widget representing the Duck in a Truck page.
	*/
	class DuckInATruck extends \Widget\App {
		
		/**
		*	Constructor.
		*/
		public function __construct( ) {
			$properties = array( 'title' => 'Duck in a Truck' );
			\Widget\App::__construct( $properties );
		}
		
		/**
		* Generate Content
		*/
		protected function generateContent() {
			return <<<HTML
			<a class="app-store-link" href="/game103games/distribute/duckinatruck.apk">Download for Android</a>
			<br/>
			<a class="app-store-link" href="/game103games/distribute/duckinatruck.ipa">Download for iOS</a>
			<br/>
			<a href='/game/duckinatruck'>Play online</a>
			<p>Thanks for playing the Game 103 app, Duck in a Truck. On this page you will find some gameplay tips tricks, and mechanics, credits, and a way to contact the developer with any questions that you have.</p>
			<h3>Gameplay Tips, Tricks, and Mechanics</h3>
			<p style = "text-align:left">
				<ul style = "text-align:left">
					<li>See missions in the pause menu.</li>
					<li>Many missions are easiest to complete when the truck is stopped after getting the stop powerup.</li>
					<li>The stop, invincibility, double coins, and double points powerups are time based. The +3 seconds and slow truck powerups are not.</li>
					<li>Every time you land the hay in the truck, powerups have a chance to reset. Powerups will reset when you lose the game as well.</li>
					<li>The timer indicates how much time you have left to drop the hay before it drops automatically. This decreases as you earn more points.</li>
					<li>There are 6 Game Center achievements in Duck in a Truck. Check them by clicking view leaderboard.</li>
					<li>Landing the hay in the back of the truck gives you 5 points multiplied by the tier you dropped the hay from. Landing on the top gives you 6 points multiplied by the tier you dropped the hay from, and landing on the front gives you 7 points multiplied by the tier you dropped the hay from.
					<li>Buying double coins will also double any coins you buy (20,000 or 100,000 coins packs).</li>
				</ul>
			</p>
			<h3>Contact</h3>
			<p>To contact me, James Grams, the developer of Duck in a Truck, send an email to <a href = "mailto:james@game103.net">james@game103.net</a>. Thanks for playing!</p>
			<h3>Credits and other information</h3>
			<p><b>Programming, Art, and Music</b>: James Grams</p>
			<p><b>Product Testing</b>: Rachel Grams, James Grams</p>
			<p><b>Font (Accidental Presidency)</b>: Tepid Monkey</p>
HTML;
		}
		
	}

?>
