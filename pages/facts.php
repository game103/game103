<?php
	if(!isset($routed)) {
		throw new Exception($direct_access_message);
	}
	
	$display_title = "Fun Facts";
	$display_description = "A list of fun facts about some of the different features and history of Game 103.";
	$display_page = <<<HTML
	<div class='box-content'>
		<div class='box-content-title'>$display_title</div>
		<div class='box-content-container'>
			<p>Mousie's Adventure was made in three days.</p>
			<p>Trampoline Chickadee was made in 24 hours.</p>
			<p>Cocoa, my dog who features in many Game 103 games, was born on January 2, 2006. He is a chocolate/black labrador mix.</p>
			<p>Elephants are my favorite animal.</p>
			<p>Cocoa is the dog on the left in Rainin' Cats & Dogs. Sophie, the Jack Russell, is the dog on the right.</p>
			<p>Grand Estuary was started in January 2013. It was mostly finished around the beginning of June 2013.</p>
			<p>Kasey, the young lady in The Great Duckdee Chase, is my wife. She called ducks, "Duckdees," as a small child.</p>
			<p>Pony's Predicament is based on a classic problem called the Traveling Salesman Problem. The game's twist is having to return to the Post Office.</p>
		</div>
	</div>
HTML;
	$display_javascript = "";
?>