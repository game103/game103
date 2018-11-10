<?php
	namespace Service\Find\GameFind;

	require_once('Service/Find/GameFind.class.php');
	
	/**
	* Class represening the find box for top games within a time period
	* This is a very limited version of Find (due to the overriding of some methods),
	* but is all that is needed for now.
	*/
	class Top extends \Service\Find\GameFind {
						
		/**
		* Constructor.
		*/
		public function __construct( $sort, $mysqli ) {
			// Add random to valid_sort
			\Service\Find\GameFind::__construct( "", $sort, "all", 1, 6, $mysqli );
			$this->valid_sort['weekly'] = array('sql' => "", 'name' => '', 'link' => '' );
			$this->valid_sort['monthly'] = array('sql' => "", 'name' => '', 'link' => '' );
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			if( $this->sort == 'weekly' ) {
				$period = "WEEK";
			}
			else {
				$period = "MONTH";
			}
			$items_per_page = $this->items_per_page;
			$select_str = "SELECT * FROM (
								SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.plays, 0) as plays, entries.plays as numeric_interactions, FORMAT(SUM(CASE WHEN plays.added_date > DATE_SUB(now(), INTERVAL 1 $period) THEN 1 ELSE 0 END), 0), entries.added_date, entries.type
								FROM hallaby_games.entries JOIN hallaby_games.plays on entries.id = plays.entry_id
								GROUP BY entries.id
								ORDER BY SUM(CASE WHEN plays.added_date > DATE_SUB(now(), INTERVAL 1 $period) THEN 1 ELSE 0 END) DESC
								LIMIT $items_per_page) AS main
								LEFT JOIN (
								SELECT count(distinct entries.id) AS total_count FROM hallaby_games.entries
								) AS count
								ON 1=1";
			return $select_str;
		}
		
		/**
		* Bind parameters to a mysqli statement
		*/
		protected function bind_params() {
			$this->escape();
			return $this->mysqli->prepare( $this->generate_sql() );
		}
		
		/**
		* Parse the result of a sql statement
		*/
		protected function parse_result( $select_statement ) {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $time_count, $added_date, $game_type, $total_count);
			
			$items = array();
			while($select_statement->fetch()) {
				$type = "game";
				$item_object = array (
					"title" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_src" => $image_url,
					"count" => $interactions,
					"time_count" => $time_count,
					"time_frame" => $this->sort == 'weekly' ? 'week' : 'month',
					"url_name" => $url_name,
					"rating" => $rating,
					"type" => 'game',
					"added_date" => $added_date,
					"game_type" => $game_type,
				);
				
				$items[] = $item_object;
			}
			$select_statement->close();
			return $this->supplement_items( $items, $total_count );
		}
		
	}
	
?>
