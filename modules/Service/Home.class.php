<?php
	namespace Service;

	require_once('Constants.class.php');
	require_once('Service.class.php');
	require_once("Service/Find/GameFind.class.php");
	require_once("Service/Find/GameFind/Top.class.php");
	require_once("Service/Find/GameFind/Random.class.php");
	require_once("Service/Find/GameFind/Featured.class.php");
	require_once("Service/Find/GameFind/Daily.class.php");
	
	/**
	* Class represening the home page
	*/
	class Home extends \Service {
	
		protected $mysqli;
		
		/**
		* Constructor.
		*/
		public function __construct( $mysqli ) {
			\Service::__construct();
			$this->mysqli = $mysqli;
		}
		
		/**
		* Generate results hash.
		*/
		public function generate() {
			
			try {
				// Create services
				$weekly_service = new \Service\Find\GameFind\Top( 'weekly', $this->mysqli );
				$monthly_service = new \Service\Find\GameFind\Top( 'monthly', $this->mysqli );
				$top_service = new \Service\Find\GameFind( "", 'popularity', "all", 1, 6, $this->mysqli );
				$rating_service = new \Service\Find\GameFind( "", 'rating', "all", 1, 6, $this->mysqli );
				$featured_service = new \Service\Find\GameFind\Featured( $this->mysqli );
				$daily_service = new \Service\Find\GameFind\Daily( $this->mysqli );
				
				return array(
					'status' => 'success',
					'weekly' => $weekly_service->generate(),
					'monthly' => $monthly_service->generate(),
					'top' => $top_service->generate(),
					'rating' => $rating_service->generate(),
					'featured' => $featured_service->generate(),
					'daily' => $daily_service->generate()
				);
			}
			catch (\Exception $e) {
				return $this->return_error( $e->getMessage() );
			}
		}
	
	}
	
?>