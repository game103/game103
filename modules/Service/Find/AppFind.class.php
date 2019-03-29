<?php
	namespace Service\Find;

	require_once('Service/Find.class.php');
	
	/**
	* Class represening the find box only for apps
	*/
	class AppFind extends \Service\Find {
		
		/**
		* Constructor.
		*/
		public function __construct( $search, $sort, $category, $page, $items_per_page, $mysqli ) {
			$this->type = 'apps';
			\Service\Find::__construct( $search, $sort, $category, $page, $items_per_page, $mysqli );
			$this->db = 'hallaby_apps';
			unset( $this->valid_sort['rating'] );
			$this->valid_sort['popularity']['sql'] = 'numeric_interactions DESC';
			$this->valid_sort['date']['sql'] = 'added_date DESC';
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			$items_per_page = $this->items_per_page;
			$offset = $this->generate_offset();
			$where_sql = $this->generate_where( 'apps' );
			$sort_sql = $this->generate_sort();
			$limit = "LIMIT $items_per_page OFFSET $offset";
			if( $this->levenshtein_search_enabled ) {
				$limit = "";
			}
			$select_str = "SELECT * FROM(
				SELECT apps.name as name, apps.description, apps.url_name, apps.image_url, FORMAT(apps.visits, 0), apps.visits as numeric_interactions, apps.store_url_android, apps.store_url_apple, apps.type, apps.added_date 
				FROM hallaby_games.apps $where_sql
				ORDER BY $sort_sql
				$limit) AS main
				LEFT JOIN (select count(1) AS total_count
				FROM hallaby_games.apps 
				$where_sql) AS count
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

			if ( $this->search && !$this->levenshtein_search_enabled ) {
				$select_statement->bind_param("ss", $search_wildcards, $search_wildcards);
			}
			
			return $select_statement;
		}
		
		/**
		* Parse the result of a sql statement
		*/
		protected function parse_result( $select_statement ) {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $interactions, $numeric_interactions, $store_url_android, $store_url_apple, $app_type, $added_date, $total_count);
			
			$items = array();
			while($select_statement->fetch()) {
				$item_object = array (
					"title" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_src" => $image_url,
					"count" => $interactions,
					"url_name" => $url_name,
					"store_url_android" => $store_url_android,
					"store_url_apple" => $store_url_apple,
					"type" => "app",
					"app_type" => $app_type,
					"added_date" => $added_date,
					"name" => $name
				);
				
				$items[] = $item_object;
			}
			$select_statement->close();
			
			if( $this->levenshtein_search_enabled ) {
				return $this->filter_result_levenshtein( $items );
			}
			return $this->supplement_items( $items, $total_count );
		}
		
	}
	
?>