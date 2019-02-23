<?php
	namespace Service;
	
	require_once('Constants.class.php');
	require_once('Service.class.php');
	
	/**
	* Class represening a login page
	*/
	class Login extends \Service {

        const CREDENTIALS_ERROR = "Incorrect username or password.";

        private $username;
        private $password;
		
		/**
		* Constructor.
		*/
		public function __construct($username, $password) {
            \Service::__construct();
            $this->username = $username;
            $this->password = $password;
        }
        
        /**
         *  Attempt to login
         */
        public function generate() {
            $status = "failure";

            if( $this->username == \Constants::ADMIN_USER 
                && $this->password == \Constants::ADMIN_PASSWORD ) {
                $status = "success";
                $_SESSION["logged_in"] = true; 
            }
            else {
                $message = self::CREDENTIALS_ERROR;
            }

            return array(
                "status" => $status,
                "message" => $message,
            );
        }
		
	}
	
?>