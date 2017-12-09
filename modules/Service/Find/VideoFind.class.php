<?php
	namespace Service\Find;

	require_once('Service/Find.class.php');
	
	/**
	* Class represening the find box only for video
	*/
	class VideoFind extends \Service\Find {
		
		/**
		* Constructor.
		*/
		public function __construct( $search, $sort, $category, $page, $items_per_page, $mysqli ) {
			$this->type = 'videos';
			\Service\Find::__construct( $search, $sort, $category, $page, $items_per_page, $mysqli );
			$this->db = 'hallaby_videos';
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
			
			return $where_sql;
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			$items_per_page = $this->items_per_page;
			$offset = $this->generate_offset();
			$where_sql = $this->generate_where();
			$sort_sql = $this->generate_sort();
			$select_str = "SELECT * FROM (
							SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.views, 0), entries.views as numeric_interactions, entries.added_date
							FROM hallaby_videos.entries JOIN hallaby_videos.categories_entries ON entries.id = categories_entries.entry_id
							JOIN hallaby_videos.categories ON categories_entries.category_id = categories.id
							$where_sql
							GROUP BY entries.id
							ORDER BY $sort_sql
							LIMIT $items_per_page
							OFFSET $offset) AS main
							LEFT JOIN (
							SELECT count(distinct entries.id) AS total_count
							FROM hallaby_videos.entries JOIN hallaby_videos.categories_entries ON entries.id = categories_entries.entry_id
							JOIN hallaby_videos.categories ON categories_entries.category_id = categories.id
							$where_sql
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

			if ( $this->search || $this->category != 'all' ) {
				
				if( $this->category != 'all' && ! $this->search ) {
					$select_statement->bind_param("ss", $this->category, $this->category);
				}
				else if( $this->category == 'all' ) {
					$select_statement->bind_param("ss", $search_wildcards, $search_wildcards);
				}
				// We have a search and a category
				else {
					$select_statement->bind_param("ssss", $this->category, $search_wildcards, $this->category, $search_wildcards);
				}
				
			}
			
			return $select_statement;
		}
		
		/**
		* Parse the result of a sql statement
		*/
		protected function parse_result( $select_statement ) {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $added_date, $total_count);
			
			$items = array();
			while($select_statement->fetch()) {
				$item_object = array (
					"title" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_src" => $image_url,
					"count" => $interactions,
					"url_name" => $url_name,
					"rating" => $rating,
					"type" => "video",
					"added_date" => $added_date
				);
				
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
				$this->valid_categories['all'] = array('name' => 'All', 'description' => 'A number of family-friendly, entertaining videos available to watch directly on Game 103.', 'link' => $this->generate_state_link( array( 'category' => 'all', 'page' => 1 ) ));
				$this->generate_categories();
				$this->error_check();
				return $this->parse_result( $this->run_sql ( $this->bind_params() ) );
			}
			catch (\Exception $e) {
				return $this->return_error( $e->getMessage() );
			}
		}
		
	}
	
?>