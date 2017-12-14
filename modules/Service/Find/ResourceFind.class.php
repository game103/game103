<?php
	namespace Service\Find;

	require_once('Service/Find.class.php');
	
	/**
	* Class represening the find box only for resources
	*/
	class ResourceFind extends \Service\Find {
		
		/**
		* Constructor.
		*/
		public function __construct( $search, $sort, $category, $page, $items_per_page, $mysqli ) {
			$this->type = 'resources';
			\Service\Find::__construct( $search, $sort, $category, $page, $items_per_page, $mysqli );
			$this->db = 'hallaby_resources';
			unset( $this->valid_sort['rating'] );
			$this->valid_sort['popularity']['sql'] = 'numeric_interactions DESC';
			$this->valid_sort['date']['sql'] = 'added_date DESC';
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
							SELECT entries.name as name, entries.description, entries.url, entries.image_url, FORMAT(entries.visits, 0), entries.visits as numeric_interactions, entries.added_date
							FROM hallaby_resources.entries JOIN hallaby_resources.categories_entries ON entries.id = categories_entries.entry_id
							JOIN hallaby_resources.categories ON categories_entries.category_id = categories.id
							$where_sql
							GROUP BY entries.id
							ORDER BY $sort_sql
							LIMIT $items_per_page
							OFFSET $offset) AS main
							LEFT JOIN (
							SELECT count(distinct entries.id) AS total_count
							FROM hallaby_resources.entries JOIN hallaby_resources.categories_entries ON entries.id = categories_entries.entry_id
							JOIN hallaby_resources.categories ON categories_entries.category_id = categories.id
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
			$select_statement->bind_result($name, $description, $url, $image_url, $interactions, $numeric_interactions, $added_date, $total_count);
			
			$items = array();
			while($select_statement->fetch()) {
				$item_object = array (
					"title" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_src" => $image_url,
					"count" => $interactions,
					"link" => $url,
					"type" => "resource",
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
				$this->valid_categories['all'] = array('name' => 'All', 'description' => 'A listing of links to resources that are useful for developers and used by Game 103.', 'link' => $this->generate_state_link( array( 'category' => 'all', 'page' => 1 ) ));
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