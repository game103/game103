<?php
	if(!isset($routed)) {
		throw new Exception($direct_access_message);
	}
	
	$display_title = "Duck in a Truck";
	$display_description = "Gameplay tips, tricks, and mechanics, credits, and a way to contact the developer for the Game 103 iOS app, Duck in a Truck.";
	$display_page = <<<HTML
	<div class='box-content'>
		<div class='box-content-title'>$display_title</div>
		<div class='box-content-container'>
			<iframe src="//banners.itunes.apple.com/banner.html?partnerId=&aId=&bt=catalog&t=catalog_black&id=907135188&c=us&l=en-US&w=728&h=90&store=apps" frameborder=0 style="overflow-x:hidden;overflow-y:hidden;width:728px;height:90px;border:0px"></iframe>
			<p>Thanks for playing the Game 103 iOS app, Duck in a Truck. On this page you will find some gameplay tips tricks, and mechanics, credits, and a way to contact the developer with any questions that you have.</p>
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
		</div>
	</div>
HTML;
	$display_javascript = "";
?>