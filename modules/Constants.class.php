<?php

	class Constants {
		
		const DB_HOST = 'localhost';
		const DB_USER = 'root';
		const DB_PASSWORD = '***REMOVED***';
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
