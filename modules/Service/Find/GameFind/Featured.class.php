<?php
	namespace Service\Find\GameFind;

	require_once('Service/Find/GameFind.class.php');
	
	/**
	* Class represening the find box for featured games
	* This is a very limited version of Find (due to the overriding of some methods),
	* but is all that is needed for now.
	*/
	class Featured extends \Service\Find\GameFind {
		
		/**
		* Constructor.
		*/
		public function __construct( $mysqli ) {
			// Add random to valid_sort
			\Service\Find\GameFind::__construct( "", "featured", "all", 1, 3, $mysqli );
			$this->valid_sort['featured'] = array('sql' => "", 'name' => '', 'link' => '' );
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			$items_per_page = $this->items_per_page;
			$select_str = "SELECT * FROM (
								SELECT * FROM (
									SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.plays, 0) as plays, entries.plays as numeric_interactions, entries.added_date
									FROM hallaby_games.entries JOIN hallaby_games.featured on entries.id = featured.entry_id
									ORDER BY featured.added_date DESC
									LIMIT $items_per_page) AS main
									ORDER BY main.plays DESC, main.rating DESC
								) AS main_outer
								LEFT JOIN (
								SELECT count(distinct entries.id) AS total_count FROM hallaby_games.entries
								) AS count
								ON 1=1";

			return $select_str;
		}
		
	}
	
?>