<?php
	namespace Service\Find\GameFind;

	require_once('Service/Find/GameFind.class.php');
	
	/**
	* Class represening the find box for random games
	*/
	class Random extends \Service\Find\GameFind {
				
		protected $invalid_id;
		
		/**
		* Constructor.
		*/
		public function __construct( $category, $invalid_id, $mysqli ) {
			// Add random to valid_sort
			\Service\Find\GameFind::__construct( "", "random", $category, 1, 6, $mysqli );
			$this->valid_sort['random'] = array('sql' => "RAND()", 'name' => '', 'link' => '' );
			$this->invalid_id = $invalid_id;
		}
		
		/**
		* Generate where sql.
		*/
		protected function generate_where() {
			$sql = array();
			// category
			if($this->category != "" && $this->category != 'all') {
				$sql[] = "categories.url_name = ?";
			}
			// invalid id
			if( $this->invalid_id ) {
				$sql[] = "entries.id != ?";
			}
			if( sizeof( $sql ) > 0 ) {
				$where_sql = "WHERE " . implode( ' and ', $sql );
			}
			return $where_sql;
		}
		
		/**
		* Bind parameters to a mysqli statement
		*/
		protected function bind_params() {
			$this->escape();
			$select_statement = $this->mysqli->prepare( $this->generate_sql() );
			
			// category
			if($this->category != "" && $this->category != 'all') {
				// and id
				if( $this->invalid_id ) {
					$select_statement->bind_param("ssss", $this->category, $this->invalid_id, $this->category, $this->invalid_id);
				}
				// just category
				else {
					$select_statement->bind_param("ss", $this->category, $this->category);
				}
			}
			// just id
			else if ( $this->invalid_id ) {
				$select_statement->bind_param("ss", $this->invalid_id, $this->invalid_id);
			}
			
			return $select_statement;
		}
		
	}
	
?>