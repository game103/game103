<?php
	/**
	* Base class for a service (backend).
	* Subclasses should be 'types' of the parent class.
	* (e.g. a Random Video Find is a Video Find with Random capabilities)
	* may be appropriate.
	* A service will usually connect to a database and return JSON.
	*/
	abstract class Service {
		
		/**
		*	Constructor.
		*/
		public function __construct() {
		}
		
		/**
		* Generate the response values for this service.
		*/
		public abstract function generate();
		
		/**
		* Return error
		*/
		protected function return_error( $error ) {
			return array(
				"status" => "failure",
				"message" => $error,
				"title" => 'Error',
				"description" => $error
			);
		}
	}
?>