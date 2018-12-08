<?php
	namespace Widget\App;

	require_once('Widget/App.class.php');

	/**
	*	Widget representing the Lamb in a Pram page.
	*/
	class LambInAPram extends \Widget\App {
		
		/**
		*	Constructor.
		*/
		public function __construct( ) {
			$properties = array( 'title' => 'Lamb in a Pram' );
			\Widget\App::__construct( $properties );
		}
		
		/**
		* Generate Content
		*/
		protected function generateContent() {
			return <<<HTML
			<a href='https://itunes.apple.com/us/app/lamb-in-a-pram/id894750816' target='_blank' rel="noopener" class='app-store-link'">
				<img src='/images/banners/lambinapram.png'/>
			</a>
			<br/>
			<a href='https://play.google.com/store/apps/details?id=air.net.game103.lambinapram' target='_blank' rel="noopener" class='app-store-link'>
				<img style='height: 50px' src='/images/banners/googleplay.png'/>
			</a>
			<br/>
			<a href='/game/lambinapram'>Play online</a>
			<p>Thanks for playing the Game 103 app, Lamb in a Pram. On this page you will find some gameplay tips, screenshots from the tutorial, credits, an FAQ, and a way to contact the developer with any questions that you have.</p>
			<h3>Gameplay Tips and Tricks</h3>
			<p style = "text-align:left">
				<ul style = "text-align:left">
					<li>Not every coin is worth going for. If you are aiming for a high score, you will want to be certain that you can get out of the way of the next enemy before jumping for a coin.</li>
					<li>The store is available from the main menu.</li>
					<li>If items in the store seem expensive, try completing missions. Missions give 1000-3000 coins per mission.</li>
					<li>Levels 1-5 contain easy missions providing 1000 coins each, levels 5-10 contain medium missions providing 2000 coins each, levels 11+ contain hard missions providing 3000 coins each.</li>
					<li>Mute the game in the pause menu.</li>
					<li>When exiting the app while in the middle of a game, it is best to pause the game manually before quitting. Otherwise, the game delays about a second from where you left off and then pauses.</li>
					<li>If you cannot connect to Game Center when you get a high score, do not worry. It will be uploaded later.</li>
					<li>Tap the pram to change the pram's color.</li>
				</ul>
			</p>
			<h3>Tutorial</h3>
			<div id="tutorial" style="margin: 0 auto;">
				<div>
					<img alt = "Lamb in a Pram Tutorial" src="/images/screenshots/lambinpramtutorial/1.png" width = "480" height = "320" name="show">
				</div>

				<select id="tutorial-slide">
					<option value="/images/screenshots/lambinpramtutorial/1.png"></option>
					<option value="/images/screenshots/lambinpramtutorial/2.png"></option>
					<option value="/images/screenshots/lambinpramtutorial/3.png"></option>
					<option value="/images/screenshots/lambinpramtutorial/4.png"></option>
					<option value="/images/screenshots/lambinpramtutorial/5.png"></option>
				</select>

				<button id='tutorial-first' title="Jump to beginning">First</button>
				<button id='tutorial-previous' title="Last Picture">Previous</button>
				<button id='tutorial-next' title="Next Picture">Next</button>
				<button id='tutorial-last' title="Jump to end">Last</button>
			</div>
			<h3>FAQ</h3>
			<ul style = "text-align:left">
				<li><b>What's the difference between the free and paid versions?</b> The free version includes ads and only has 4 items in the store. The paid version has 16 items in the store.</li>
				<li><b>What items are in the store?</b> In the free version, the store items include a red cap, a bow tie, a rain hat, and a bandana. The paid version has all of those as well as a top hat, a necklace, a crown, a bell, a blue cap, a collar, a straw hat, a tie, a helmet, a pair of glasses, a pirate hat, and an eyepatch.</li>
				<li><b>How many updates have there been?</b> Both Lamb in a Pram and Lamb in a Pram Lite are on version 1.1.0 and have had one update each.</li>
				<li><b>When was Lamb in a Pram released?</b> July 11th, 2014. Version 1.1.0 was released on July 22nd 2014.</li>
				<li><b>When was Lamb in a Pram Lite released?</b> July 21th, 2014. Version 1.1.0 was released on July 30nd 2014.</li>
			</ul>
			<h3>Contact</h3>
			<p>To contact me, James Grams, the developer of Lamb in a Pram, send an email to <a href = "mailto:james@game103.net">james@game103.net</a>. Thanks for playing!</p>
			<h3>Credits and other information</h3>
			<p><b>Programming, Art, and Music</b>: James Grams</p>
			<p><b>Product Testing</b>: Rachel Grams, James Grams</p>
			<p><b>Font (Conformity)</b>: Unitech Fonts</p>		
HTML;
		}
		
	}

?>
