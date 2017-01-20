<?php
	$urlid = $_POST ['urlid'];

	$file = 'xml.xml';

	$xml = simplexml_load_file($file);

	$urlset = $xml;

	$url = $urlset->addChild('url');
	$url->addChild('loc', 'http://game103.net/gamepages/outsidegamepage.php?urlid=$ganes');
	$url->addChild('changefreq', 'daily');
	$url->addChild('priority', '0.7');

	$xml->asXML($file);
?>