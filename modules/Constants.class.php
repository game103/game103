<?php

	class Constants {
		
		const DB_HOST = 'localhost';
		const DB_USER = 'root';
		const DB_PASSWORD = '***REMOVED***';
		const FB_TOKEN = "EAAPBY3lZBjdgBAEkULO8N5nESPP49QfAiKMcmucJThYQZBTl2HnS971SwMoVhZAl3RvbzpD42CO3CEGvbDTf9YmE1ZBJy2ZC7Ouc8wnVztoklb9lkD7DcZB41da9mA5ZCRUWbcfe9TwezemKkMsfYkkYNkJwxdh6i1viaNY4jwhKwl7N2LWT8Mw";
		const TWITTER_CONSUMER_KEY = "***REMOVED***";
		const TWITTER_CONSUMER_KEY_SECRET = "541sBemV617Nf7VPEamiaIXxd5m2kbSBcM5qeHlgXetmPSwbZH";
		const TWITTER_TOKEN = "789785198707761156-BIfQd6vxlytsXFsjlOSg3OFLRQGUbeD";
		const TWITTER_TOKEN_SECRET = "0iW0N7VqpIA6FWkHNPANx9ZdN487CgAAMOq3yKFaEdysH";
		const TITLE_APPEND = 'Game 103: Family-Friendly Games and Entertainment';
		const MYSQL_MESSAGE = "Sorry, there was an error connecting to the database.";
		const STAR_WIDTH = 22;
		const NEW_CONTENT_ITEMS_PER_PAGE = 10;
		
		// Sanitize output (remove unecessary space)
		// https://stackoverflow.com/questions/6225351/how-to-minify-php-page-html-output
		public static function sanitize_output($buffer) {

			$search = array(
				'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
				'/[^\S ]+\</s',     // strip whitespaces before tags, except space
				'/(\s)+/s',         // shorten multiple whitespace sequences
				'/<!--(.|\s)*?-->/' // Remove HTML comments
			);

			$replace = array(
				'>',
				'<',
				'\\1',
				''
			);

			$buffer = preg_replace($search, $replace, $buffer);

			return $buffer;
		}
		
	}

?>
