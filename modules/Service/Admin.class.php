<?php
	namespace Service;
	
	require_once('Constants.class.php');
	require_once('Service.class.php');
	
	/**
	* Class represening an admin page
	*/
	abstract class Admin extends \Service {

        const CATEGORY_ERROR_MESSAGE = "Please select a category";
				
		protected $mysqli;
        protected $db;
        protected $post;
        protected $url_name;
		
		/**
		* Constructor.
		*/
		public function __construct( $post, $url_name, $mysqli, $db ) {
            \Service::__construct();
            $this->post = $post;
            $this->mysqli = $mysqli;
            $this->db = $db;
            $this->url_name = $url_name;
            $this->mysqli->select_db( $this->db );
		}

        /**
		* Process the user's input.
		*/
        abstract protected function process();

        /**
		* Get a listing of the current entries
		*/
        abstract protected function listing();

        /**
         * Load the values for a pre-existing entry
         */
        abstract protected function load();
        
        /**
         * Generate url name
         */
        protected function generate_url_name($name) {
            $url_name = str_replace(' ','',$name);
            $url_name = str_replace('&','',$url_name);
            $url_name = str_replace("'","",$url_name);
            $url_name = strtolower($url_name);
            return $url_name;
        }
		
	}
	
?>