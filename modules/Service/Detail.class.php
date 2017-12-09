<?php
	namespace Service;
	
	require_once('Constants.class.php');
	require_once('Service.class.php');
	
	/**
	* Class represening a game
	*/
	abstract class Detail extends \Service {
				
		protected $url_name;
		protected $id;
		protected $mysqli;
		protected $db;
		
		/**
		* Constructor.
		*/
		public function __construct( $url_name, $mysqli ) {
			\Service::__construct();
			$this->url_name = $url_name;
			$this->mysqli = $mysqli;
		}
		
		/**
		* Generate rating
		*/
		protected function generate_rating() {
			// Get the total rating
			// Don't use rating since we need num votes
			$rating_str = "SELECT sum(score), count(1) FROM {$this->db}.votes WHERE entry_id = ?";
			$rating_statement = $this->mysqli->prepare($rating_str);
			$rating_statement->bind_param("i", $this->id);
			$rating_statement->execute();
			$rating_statement->bind_result($summed_rating, $num_votes);
			$rating_statement->fetch();
			if(mysqli_stmt_error($rating_statement) != "") {
				$rating_statement->close();
				$this->mysqli->close();
				throw new \Exception(\Constants::MYSQL_MESSAGE);
			}
			
			if($num_votes > 0) {
				$total_rating = $summed_rating/$num_votes;
			}
			else {
				$total_rating = 0;
			}
			
			return( array( 'rating' => $total_rating, 'votes' => $num_votes ) );
		}
	}
	
?>