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
        protected $post; // It's must simpler to just pass all post data to the admin
        protected $processed_post; // The processed post data that is returned to the user
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
        protected function listing() {
            $sql = "select id, name, url_name from entries order by id desc";
			// Prepare the statement
			$statement = $this->mysqli->prepare($sql);
			// Execute the statement
			$statement->execute();
			// Get the one result
			$statement->bind_result($id, $name, $url_name);
            // Fetch the result
            $current_listing = array();
			while( $statement->fetch() ) {
                $current_listing[] = array(
                    "id"        =>  $id,
                    "name"      =>  $name,
                    "url_name"  =>  $url_name
                );
            }
			// Close the statement
            $statement->close();
            
            $this->processed_post['listing'] = $current_listing;
        }

        /**
         * Load the values for a pre-existing entry
         */
        abstract protected function load();
        
        /**
         * Generate url name
         */
        protected function generate_url_name() {
            $url_name = str_replace(' ','',$this->processed_post['name']);
            $url_name = str_replace('&','',$url_name);
            $url_name = str_replace("'","",$url_name);
            $url_name = strtolower($url_name);
            $this->processed_post['url_name'] = $url_name;
        }

        /**
         * Move files.
         */
        protected function move_image_files() {
            $move_image_response = $this->move_file( $_FILES['imagefile_upload'], "/images/icons/games/", $this->processed_post['url_name'] );

            if( $move_image_response ) {
                $this->processed_post['image_url'] = $move_image_response;
            }
        }

        /**
         * Move an uploaded file to a given location
         * Returns the url of the uploaded file
         * target dir is the path from game 103 root (so should be accessible in 
         * the file system and through apache)
         */
        protected function move_file( $file, $target_dir, $url_name ) {
            $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
            $target_file = $target_dir . $url_name . "." . $extension;

            $url = false;
            if(move_uploaded_file($file["tmp_name"],getcwd() . $target_file)) {
                $url = $target_file;

                // Create a webp version
                if( $extension == 'png' || $extension == "jpeg" || $extension == "jpg" ) {
                    exec( "cwebp " . getcwd() . $target_file . " -o " . getcwd().$target_dir.$url_name."webp" . " -z 6" );
                }
                else if( $extension == "gif" ) {
                    exec( "gif2webp " . getcwd() . $target_file . " -o " . getcwd().$target_dir.$url_name."webp" );
                }
            }
            return $url;
        }

        /**
         * Load category data
         */
        protected function load_categories() {
            // Load category data if necessary
            if( !isset($this->post['cat1']) ) {
                $cat_str = "SELECT categories.id FROM entries
                    join categories_entries on categories_entries.entry_id = entries.id
                    join categories on categories.id = categories_entries.category_id
                    where entries.url_name = ?";
                $statement = $this->mysqli->prepare($cat_str);
                $statement->bind_param("s", $this->url_name);
                $statement->execute();
                $statement->bind_result($category);

                $i = 1;
                while( $statement->fetch() ) {
                    $this->post['cat' . strval($i)] = $category;
                    $i++;
                }
                $statement->close();
            }
        }

        /**
         * Insert values into categories entries.
         */
        protected function insert_categories_entries() {
            $sql = "INSERT INTO categories_entries(category_id, entry_id) VALUES (?,?)";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ii", $this->processed_post['cat1'], $this->processed_post['id']);
            $statement->execute();
            $statement->close();
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ii", $this->processed_post['cat2'], $this->processed_post['id']);
            $statement->execute();
            $statement->close();
        }

        /**
         * Delete categories entries
         */
        protected function delete_categories_entries() {
            $delete_sql = "DELETE FROM categories_entries WHERE entry_id = ?";
            $statement = $this->mysqli->prepare($delete_sql);
            $statement->bind_param("i", $this->processed_post['id']);
            $statement->execute();
            $statement->close();
        }

        /**
         * Error check.
         */
        protected function error_check() {
            $this->processed_post['status'] = "success";

            // Need at least one category
            if(!$this->processed_post['cat1']) {
                $this->processed_post['status'] = "error";
                $this->processed_post['message'] = self::CATEGORY_ERROR_MESSAGE;
            }
        }
		
	}
	
?>