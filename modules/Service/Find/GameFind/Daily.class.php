<?php
	namespace Service\Find\GameFind;

	require_once('Service/Find/GameFind.class.php');
	
	/**
	* Class represening the find box for daily games
	* This is a very limited version of Find (due to the overriding of some methods),
	* but is all that is needed for now.
	*/
	class Daily extends \Service\Find\GameFind {
		
		/**
		* Constructor.
		*/
		public function __construct( $mysqli ) {
			// Add random to valid_sort
			\Service\Find\GameFind::__construct( "", "daily", "all", 1, 3, "any", $mysqli );
			$this->valid_sort['daily'] = array('sql' => "", 'name' => '', 'link' => '' );
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			$items_per_page = $this->items_per_page;
			$select_str = "SELECT * FROM (
								SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.plays, 0) as plays, entries.plays as numeric_interactions, entries.type, entries.added_date
								FROM hallaby_games.entries JOIN hallaby_games.daily_game on entries.id = daily_game.entry_id
								ORDER BY daily_game.added_date DESC
								LIMIT $items_per_page) AS main
								LEFT JOIN (
								SELECT count(distinct entries.id) AS total_count FROM hallaby_games.entries
								) AS count
								ON 1=1";

			return $select_str;
		}
		
	}
	
?>
