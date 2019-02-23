<?php
	namespace Widget;

	require_once('Constants.class.php');
    require_once('Widget.class.php');
    require_once('Widget/Box.class.php');

	/**
	*	Widget representing an admin.
	*/
	abstract class Admin extends \Widget {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Admin Service for expected properties
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			$this->JS[] = '/javascript/admin.js';
			$this->CSS[] = '/css/admin.css';
		}

		/**
		 * Generate an error message
		 */
		protected function generate_error_message() {
			// Create the error message
            $error_message = $this->properties["message"] ? '<div class="admin-error-message">'.$this->properties["message"].'</div>' : "";
            if( $this->properties["status"] == "success" ) {
                $error_message = '<div class="admin-success-message">Success!</div>';
			}
			return $error_message;
		}

		/**
		* Generate Listing.
        */
        protected function generate_listing( $admin_path_name, $admin_name ) {
			$listing = $this->properties['listing'];
            $html = "<table><thead><tr><th>ID</th><th>Name</th><th>Action</th></tr></thead><tbody>";
            foreach ( $listing as $item ) {
                $html .= "<tr>";
                foreach( $item as $key => $value ) {
                    if( $key == 'url_name' ) {
                        $html .= "<td><a href='/admin/$admin_path_name/$value'>Edit</a></td>";
                    }
                    else {
                        $html .= "<td>$value</td>";
                    }
                }
                $html .= "</tr>";
            }
            $html .= "</tbody></table>";
            // Place the HTML in a box
            $box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
                'title'			=> $admin_name
			) );
            $box->generate();
            return $box->get_HTML();
        }
		
	}

?>
