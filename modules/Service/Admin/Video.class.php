<?php
	namespace Service\Admin;
	
	require_once('Constants.class.php');
	require_once('Service/Admin.class.php');
	
	/**
	* Class represening an admin page
	*/
	class Video extends \Service\Admin {
		
		/**
		* Constructor.
		*/
		public function __construct( $post, $url_name, $mysqli ) {
            \Service\Admin::__construct( $post, $url_name, $mysqli, "hallaby_videos" );
		}

        /**
		* Get admin details and call process, listing, and load_edit if necessary.
		*/
		public function generate() {

            $return_arr = array();

            // Load data if we have a url name
            if ( $this->url_name ) {
                $return_val = $this->load(); // This will populate "post"
                // The major difference!! submit will not be defined
                // After going to the edit page through a post, the ID as a hidden variable will
                // be stored on the page until a successful edit
                // this section will not be triggered. That's fine, since we don't need to load the data
                // more than once.
                if( !$return_val ) {
                    return $this->return_error( "" ); // This is not a real game
                }
            }

            // Process the post data if necessary
            // (If the user has given us some form of input be it data or a url_name)
            if( $this->post ) {
                $this->process();
            }

            $this->listing();

            return $this->processed_post;
        }

        /**
		* Process the user's input.
		*/
        protected function process() {

            // Create the return array at this point in case of failure
            $this->processed_post = array(
                'id'            =>  $this->post['id'],
                'name'          =>  $this->post['name'],
                'string'        =>  $this->post['string'],
                'description'   =>  $this->post['description'],
                'image_url'     =>  $this->post['image_url'],
                'type'          =>  $this->post['type'],
                'cat1'          =>  $this->post['cat1'],
                'cat2'          =>  $this->post['cat2']
            );

            // If we are submitting, then do an insert/update
            if($this->post['submit']) {
                // Generate the url name
                $this->generate_url_name();
                
                if($cat2 == "") {
                    unset($cat2);
                }     

                // Check for errors
                $this->error_check();
                if( $this->processed_post['status'] === 'error' ) {
                    return $this->processed_post;
                }
            
                // move files
                $this->move_image_files();

                if( !$this->processed_post['id'] ) {
                    $this->insert_new_video();
                }
                else {
                    $this->update_video();
                }
            }

            return $this->processed_post;
        }

         /**
         * Load the values for an existing entry into the post data (this can later be processed)
         */
        protected function load() {

            // Load data for the entry
            $sql = "select entries.id, entries.name, entries.string, entries.description, entries.image_url, entries.type from entries where url_name = ?";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("s", $this->url_name);
            $statement->execute();
            $statement->bind_result($id, $name, $string, $description, $image_url, $type);
            $statement->fetch();
            $statement->close();
            if( !$id ) {
                return false;
            }

            $this->post['id'] = $id;
            // This could either be a request ot generate the edit form
            // or to actually edit.
            if( !$this->post['name'] ) { $this->post['name'] = $name; }
            if( !$this->post['string'] ) { $this->post['string'] = $string; }
            if( !$this->post['description'] ) { $this->post['description'] = $description; }
            if( !$this->post['image_url'] ) { $this->post['image_url'] = $image_url; }
            if( !$this->post['type'] ) { $this->post['type'] = $type; }

            $this->load_categories();

            return true;
           
        }
 
        /**
         * Insert a new game.
         * A file will override a url (for games and images)
         */
        protected function insert_new_video() {
            // Insert game    
            $sql = "INSERT INTO entries(name, string, description, image_url, type, url_name) VALUES (?, ?, ?, ?, ?, ?)";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ssssss", $this->processed_post['name'], $this->processed_post['string'],
                $this->processed_post['description'], $this->processed_post['image_url'], $this->processed_post['type'],
                $this->processed_post['url_name'] );
            $statement->execute();
            $this->processed_post['id'] = $this->mysqli->insert_id;
            $statement->close();

            $this->insert_categories_entries();

            return $return_arr;
        }

        /**
         * Update a game.
         */
        protected function update_video() {
            // Update
            $sql = "UPDATE entries set name = ?, string = ?, description = ?, image_url = ?, type = ?, url_name = ? where id = ?";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("sssssss", $this->processed_post['name'], $this->processed_post['string'],
                $this->processed_post['description'], $this->processed_post['image_url'], $this->processed_post['type'],
                $this->processed_post['url_name'], $this->processed_post['id'] );
            $statement->execute();
            $statement->close();

            // Delete categories entries
            $this->delete_categories_entries();

            // Insert new categories entries
            $this->insert_categories_entries();

            return $return_arr;
        }
		
	}
	
?>
