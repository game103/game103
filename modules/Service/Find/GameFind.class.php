<?php
	namespace Service\Find;

	require_once('Service/Find.class.php');
	
	/**
	* Class represening the find box only for games
	*/
	class GameFind extends \Service\Find {
		
		/**
		* Constructor.
		*/
		public function __construct( $search, $sort, $category, $page, $items_per_page, $platform, $mysqli ) {
			$this->type = 'games';
			$this->platform = $platform ? $platform : 'any';
			\Service\Find::__construct( $search, $sort, $category, $page, $items_per_page, $mysqli );
			$this->db = 'hallaby_games';
			if( $this->category == 'game103' ) {
				$this->valid_sort['creation'] = array( 'sql' => 'creation_date DESC', 'name' => 'Sort by creation', 'link' => $this->generate_state_link( array( 'sort' => 'creation' ) ) );
			}
			$this->valid_platforms = array(
				'any' => array( 'name' => 'Any Platform', 'link' => $this->generate_state_link( array( 'platform' => 'any', 'page' => 1 ) ) ), 
				'computer' => array( 'name' => 'Computer', 'link' => $this->generate_state_link( array( 'platform' => 'computer', 'page' => 1 ) ) ), 
				'mobile' => array( 'name' => 'Mobile', 'link' => $this->generate_state_link( array( 'platform' => 'mobile', 'page' => 1 ) ) )
			);
		}
		
		/**
		* Generate where sql.
		*/
		protected function generate_where() {
			if($this->category != "" && $this->category != 'all') {
				$category_sql = "WHERE categories.url_name = ?";
			}
			else {
				$category_sql = "";
			}
			if($this->search != '') {
				$search_sql = "entries.name LIKE ?";
				if($category_sql == "") {
					$where_sql = "WHERE $search_sql";
				}
				else {
					$where_sql = $category_sql . " AND $search_sql";
				}
			}
			else {
				$where_sql = $category_sql;
			}
			// We have a subtype
			if( $this->platform != 'any' ) {
				$subtype_sql = "entries.type = ?";
				if( $where_sql ) {
					$where_sql .= " AND $subtype_sql";
				}
				else {
					$where_sql = "WHERE $subtype_sql";
				}
			}
			
			return $where_sql;
		}
		
		/**
		* Generate downloads sql.
		* This gets the Game 103 downloadable games and should
		* only be called when the category is game103
		*/
		protected function generate_downloads_sql() {
			$union_sql = "
			UNION
			SELECT downloads.name as name, downloads.description, downloads.url_name, downloads.image_url, -1 as rating,  FORMAT(downloads.saves, 0) as plays, downloads.saves as numeric_interactions,
			downloads.added_date as added_date, -1 as game_type, YEAR(downloads.creation_date), downloads.creation_date as creation_date
			FROM hallaby_games.downloads";
			$union_count_sql = "
			UNION
			SELECT count(1)
			FROM hallaby_games.downloads";
			$union_sum_sql = "
			SELECT sum(total_count) FROM (";
			
			if($this->search != '') {
				$union_sql .= " WHERE downloads.name LIKE ?";
				$union_count_sql .= " WHERE downloads.name LIKE ?";
			}
			$union_count_sql .= ') AS inner_count';
			
			return array(
				'game103_extra_select' =>  ", YEAR(entries.creation_date), entries.creation_date as creation_date",
				'union_sql' => $union_sql,
				'union_count_sql' => $union_count_sql,
				'union_sum_sql' => $union_sum_sql
			);
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			$items_per_page = $this->items_per_page;
			$offset = $this->generate_offset();
			$where_sql = $this->generate_where();
			$sort_sql = $this->generate_sort();
			if( $this->category == 'game103' ) {
				$downloads_sections = $this->generate_downloads_sql();
				$game103_extra_select = $downloads_sections['game103_extra_select'];
				if( $this->platform == 'any' ) {
					$union_sql = $downloads_sections['union_sql'];
					$union_count_sql = $downloads_sections['union_count_sql'];
					$union_sum_sql = $downloads_sections['union_sum_sql'];
				}
			}
			$select_str = "SELECT * FROM (
								SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.plays, 0) as plays, entries.plays as numeric_interactions, entries.added_date as added_date, entries.type as game_type $game103_extra_select
								FROM hallaby_games.entries JOIN hallaby_games.categories_entries ON entries.id = categories_entries.entry_id
								JOIN hallaby_games.categories ON categories_entries.category_id = categories.id
								$where_sql
								GROUP BY entries.id
								$union_sql
								ORDER BY $sort_sql
								LIMIT $items_per_page
								OFFSET $offset) AS main
								LEFT JOIN (
								$union_sum_sql
								SELECT count(distinct entries.id) AS total_count
								FROM hallaby_games.entries JOIN hallaby_games.categories_entries ON entries.id = categories_entries.entry_id
								JOIN hallaby_games.categories ON categories_entries.category_id = categories.id
								$where_sql
								$union_count_sql
								) AS count
								ON 1=1";

			return $select_str;
		}
		
		/**
		* Bind parameters to a mysqli statement
		*/
		protected function bind_params() {
			$this->escape();
			$select_statement = $this->mysqli->prepare( $this->generate_sql() );
			$search_wildcards = '%' . $this->search . '%';

			if ( $this->search || $this->category != 'all' || $this->platform  != 'any'  ) {
				
				if( $this->platform == 'any' ) {
					if( $this->category != 'all' && ! $this->search ) {
						$select_statement->bind_param("ss", $this->category, $this->category);
					}
					else if( $this->category == 'all' ) {
						$select_statement->bind_param("ss", $search_wildcards, $search_wildcards);
					}
					else {
						if( $this->category == 'game103' ) {
							$select_statement->bind_param("ssssss", $this->category, $search_wildcards, $search_wildcards, $this->category, $search_wildcards, $search_wildcards);
						}
						else {
							$select_statement->bind_param("ssss", $this->category, $search_wildcards, $this->category, $search_wildcards);
						}
					}
				}
				else {
					// We may need to change this at some point
					$platform = $this->platform == 'mobile' ? 'JavaScript' : 'Flash';
					if( $this->category == 'all' && ! $this->search ) {
						$select_statement->bind_param("ss", $platform, $platform);
					}
					else if( ! $this->search ) {
						$select_statement->bind_param("ssss", $this->category, $platform, $this->category, $platform);
					}
					else if( $this->category == 'all' ) {
						$select_statement->bind_param("ssss", $search_wildcards, $platform, $search_wildcards, $platform);
					}
					else {
						// No downloads in platform
						$select_statement->bind_param("ssssss", $this->category, $search_wildcards, $platform, $this->category, $search_wildcards, $platform);
					}
				}
				
			}
			
			return $select_statement;
		}
		
		/**
		* Parse the result of a sql statement
		*/
		protected function parse_result( $select_statement ) {
			if( $this->category == 'game103' ) {
				$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $added_date, $game_type, $creation_date, $creation_unused, $total_count);
			}
			else {
				$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $added_date, $game_type, $total_count);
			}
			
			$items = array();
			while($select_statement->fetch()) {
				$type = "game";
				// -1 as rating signifies a download
				if( $rating == -1 ) {
					$type = 'download';
				}
				$item_object = array (
					"title" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_src" => $image_url,
					"count" => $interactions,
					"url_name" => $url_name,
					"rating" => $rating,
					"type" => $type,
					"added_date" => $added_date,
					"game_type" => $game_type
				);
				
				if( $this->category == 'game103' ) {
					$item_object["description"] .= " ($creation_date)";
				}
				$items[] = $item_object;
			}
			$select_statement->close();
			return $this->supplement_items( $items, $total_count );
		}
		
		/**
		* Generate hash.
		*/
		public function generate() {
			
			try {
				$this->valid_categories['all'] = array('name' => 'All', 'description' => 'A collection of family-friendly, entertaining, and quality games that are playable directly in your browser.', 'link' => $this->generate_state_link( array( 'category' => 'all', 'page' => 1 ) ));
				$this->generate_categories();
				$this->error_check();
				return $this->parse_result( $this->run_sql ( $this->bind_params() ) );
			}
			catch (\Exception $e) {
				return $this->return_error( $e->getMessage() );
			}
		}
		
		/**
		* Alter values
		*/
		protected function alter_values( $values ) {
			$category = $values['category'] ? $values['category'] : $this->category;
			$type = $values['type'] ? $values['type'] : $this->type;
			if( $this->sort == 'creation' && ( $category != 'game103' || $type != 'games' ) ) {
				$values['sort'] = 'date';
			}
			elseif( $this->sort == 'rating' && ( $type == 'apps' || $type == 'resources' ) ) {
				$values['sort'] = 'popularity';
			}
			return $values;
		}
		
	}
	
?>
