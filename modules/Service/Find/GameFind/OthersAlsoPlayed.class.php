<?php
	namespace Service\Find\GameFind;

	require_once('Service/Find/GameFind.class.php');
	
	/**
	* Class represening the find box for others also played games
	* This is a very limited version of Find (due to the overriding of some methods),
	* but is all that is needed for now.
	*/
	class OthersAlsoPlayed extends \Service\Find\GameFind {
		
		/**
		* Constructor.
		*/
		public function __construct( $id, $mysqli ) {
			// Add random to valid_sort
			\Service\Find\GameFind::__construct( "", "recommended", "all", 1, 6, "any", $mysqli );
            $this->valid_sort['recommended'] = array('sql' => "", 'name' => '', 'link' => '' );
            $this->id = $id;
		}
		
		/**
        * Generate the sql
        * This will take a game and look at how many unique players also played every other game
        * It will also look at the unique players of every other game, regardless of whether or not they
        * played this game.
        * It will order the response by <number of shared plays>^2/<number of all plays>
        * basically, we want to order by games that have a high percentage of matchings plays (<number of shared plays>/<number of all plays>)
        * but we also want an extra boost with games with a lot of shared plays, which is why we square it.
        * we also fetch more than we need and take a random sample from that.
		*/
		protected function generate_sql() {
            $items_per_page = $this->items_per_page;
			$select_str = "SELECT * FROM (
                SELECT e.name, e.description, e.url_name, e.image_url, e.rating, FORMAT(e.plays, 0) AS plays, e.added_date, e.type, c.both_unique_plays, c.all_unique_plays, c.both_unique_plays*c.both_unique_plays/c.all_unique_plays AS play_score FROM (
                    SELECT ic.entry_id, ic.both_unique_plays, ic2.all_unique_plays FROM (
                        SELECT entry_id, count( DISTINCT ip_address ) AS both_unique_plays FROM plays WHERE ip_address IN (
                            SELECT ip_address FROM plays WHERE entry_id = ?
                        ) AND entry_id != ?
                        GROUP BY entry_id
                    ) AS ic
                    JOIN (
                        SELECT entry_id, count( DISTINCT ip_address ) AS all_unique_plays FROM plays GROUP BY entry_id
                    ) AS ic2
                    ON ic.entry_id = ic2.entry_id
                ) AS c
                JOIN
                entries e ON e.id = c.entry_id
                ORDER BY play_score DESC
                LIMIT 10
            ) as q
            ORDER BY RAND()
            LIMIT $items_per_page";

			return $select_str;
        }
        
        /**
		* Bind parameters to a mysqli statement
		*/
		protected function bind_params() {
            $this->escape();
            $select_statement = $this->mysqli->prepare( $this->generate_sql() );
            $select_statement->bind_param("ii", $this->id, $this->id);
            return $select_statement;
        }

        /**
		* Parse the result of a sql statement
		*/
		protected function parse_result( $select_statement ) {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $added_date, $game_type, $both_unique_plays, $all_unique_plays, $play_score);
			
			$items = array();
			while($select_statement->fetch()) {
				$type = "game";
				$item_object = array (
					"title" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_src" => $image_url,
					"count" => $interactions,
					"url_name" => $url_name,
					"rating" => $rating,
					"type" => $type,
					"added_date" => $added_date,
                    "game_type" => $game_type,
                    "both_unique_plays" => $both_unique_plays,
                    "all_unique_plays" => $all_unique_plays,
                    "play_score" => $play_score
				);
				
				$items[] = $item_object;
			}
            $select_statement->close();
            // We don't have paging for recommendations so we just use items per page as total items.
			return $this->supplement_items( $items, $items_per_page );
		}
		
	}
	
?>
