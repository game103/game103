<?php
	namespace Widget;

	require_once('Widget.class.php');

	/**
	*	Widget representing the login page for the administration section of Game 103.
	*/
	class Login extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
            \Widget::__construct( $properties );
			$this->CSS[] = '/css/admin.css';
		}
		
		/**
		* Generate Content
		*/
		public function generate() {

            // Create the error message
            $error_message = $this->properties["message"] ? '<div class="admin-error-message">'.$this->properties["message"].'</div>' : "";
            
            $html = <<<HTML
<form class="admin login" action = "{$this->properies['action']}" method = "POST" enctype = "multipart/form-data">
    $error_message
    <label for="username"><span class='admin-label-text'>Username: </span><input required id="username" type = "text" name = "username"></label>
    <label for="password"><span class='admin-label-text'>Password: </span><input required id="password" type = "password" name = "password"></label>
    <input id='submit' type = "submit" value = "Submit" name = "submit" class="button"><br>
    <div class='clear'></div>
</form>
HTML;
            
            // Place the HTML in a box
            $box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
                'title'			=> "Login",
                "tight"         => 1
			) );
            $box->generate();

            $this->HTML = $box->get_HTML();
            $this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
        }
		
	}

?>
