<?php
	namespace Service\Admin;
	
	require_once('Constants.class.php');
	require_once('Service/Admin.class.php');
	
	/**
	* Class represening an admin page
	*/
	class Game extends \Service\Admin {

        const DUPLICATES_ERROR_MESSAGE = "Please do not enter duplicate key/action pairs";
		
		/**
		* Constructor.
		*/
		public function __construct( $post, $url_name, $mysqli ) {
            \Service\Admin::__construct( $post, $url_name, $mysqli, "hallaby_games" );
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
                $return_arr = $this->process();
            }

            $all_controls = $this->generate_controls();
            $all_actions = $this->generate_actions();

            $return_arr["controls_ids"] = $all_controls["controls_ids"];
            $return_arr["controls_keys"] = $all_controls["controls_keys"];
            $return_arr["actions_ids"] = $all_actions["actions_ids"];
            $return_arr["actions_names"] = $all_actions["actions_names"];

            $return_arr["listing"] = $this->listing();

            return $return_arr;
        }

        /**
		* Process the user's input.
		*/
        protected function process() {

            $id = $this->post['id'];
            $name = $this->post['name'];
            $url = $this->post['url'];
            $width = $this->post['width'];
            $height = $this->post['height'];
            $description = $this->post['description'];
            $image_url = $this->post['image_url'];
            $game_type = $this->post['type'];
            $submit = $this->post['submit'];
            $cat1 = $this->post['cat1'];
            $cat2 = $this->post['cat2'];

            // Fetch the keys listed
            $keys = array();
            $index = 0;
            if(isset($this->post['key_0'])) {
                while(isset($this->post['key_' . $index])) {
                    $keys[] = $this->post['key_' . $index];
                    $index ++;
                }
            }

            // Fetch the actions listed
            $actions = array();
            $index = 0;
            if(isset($this->post['action_0'])) {
                while(isset($this->post['action_' . $index])) {
                    $actions[] = $this->post['action_' . $index];
                    $index ++;
                }
            }

            // Create the return array at this point in case of failure
            $return_arr = array(
                'id'            =>  $id,
                'name'          =>  $name,
                'url'           =>  $url,
                'width'         =>  $width,
                'height'        =>  $height,
                'description'   =>  $description,
                'image_url'     =>  $image_url,
                'game_type'     =>  $game_type,
                'cat1'          =>  $cat1,
                'cat2'          =>  $cat2,
                'keys'          =>  $keys,
                'actions'       =>  $actions
            );

            // If we are submitting, then do an insert/update
            if($submit) {
                // Generate the url name
                $url_name = $this->generate_url_name($name);
                
                if($cat2 == "") {
                    unset($cat2);
                }     

                // Check for errors
                $return_arr = $this->error_check( $return_arr, $cat1, $keys, $actions );
                if( $return_arr['status'] === 'error' ) {
                    return $return_arr;
                }

                // Insert actions and keys to the database that don't have IDs
                $keys = $this->insert_new_actions_or_controls( $keys, "controls", "controls.key" );
                $actions = $this->insert_new_actions_or_controls( $actions, "actions", "name" );
                
                // Generate actions_controls
                $actions_controls = $this->insert_actions_controls( $keys, $actions );
            
                // move files
                $return_arr = $this->move_files( $return_arr, $url_name );

                if( !$id ) {
                    $return_arr = $this->insert_new_game( $return_arr, $name, $url, $width, $height, $description, $image_url, $url_name, $game_type, $actions_controls, $cat1, $cat2 );
                }
                else {
                    $return_arr = $this->update_game( $return_arr, $name, $url, $width, $height, $description, $image_url, $url_name, $game_type, $actions_controls, $cat1, $cat2, $id );
                }
            }

            return $return_arr;
        }

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
            
            return $current_listing;
        }

         /**
         * Load the values for an existing entry into the post data (this can later be processed)
         */
        protected function load() {

            // Load data for the entry
            $sql = "select entries.id, entries.name, entries.url, entries.width, entries.height, entries.description, 
            entries.image_url, entries.type from entries where url_name = ?";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("s", $this->url_name);
            $statement->execute();
            $statement->bind_result($id, $name, $url, $width, $height, $description, $image_url, $type);
            $statement->fetch();
            $statement->close();
            if( !$id ) {
                return false;
            }

            $this->post['id'] = $id;
            // This could either be a request ot generate the edit form
            // or to actually edit.
            if( !$this->post['name'] ) { $this->post['name'] = $name; }
            if( !$this->post['url'] ) { $this->post['url'] = $url; }
            if( !$this->post['width'] ) { $this->post['width'] = $width; }
            if( !$this->post['height'] ) { $this->post['height'] = $height; }
            if( !$this->post['description'] ) { $this->post['description'] = $description; }
            if( !$this->post['image_url'] ) { $this->post['image_url'] = $image_url; }
            if( !$this->post['type'] ) { $this->post['type'] = $type; }
            
            // Load controls data if necessary
            if( !isset($this->post['key_0']) ) {
                $controls_str = "SELECT controls.id, actions.id FROM entries
                    join actions_controls_entries on actions_controls_entries.entry_id = entries.id
                    join actions_controls on actions_controls_entries.action_control_id = actions_controls.id
                    join actions on actions_controls.action_id = actions.id
                    join controls on actions_controls.control_id = controls.id
                    where entries.url_name = ?";
                $statement = $this->mysqli->prepare($controls_str);
                $statement->bind_param("s", $this->url_name);
                $statement->execute();
                $statement->bind_result($key, $action);

                $i = 0;
                while( $statement->fetch() ) {
                    $this->post['key_' . strval($i)] = $key;
                    $this->post['action_' . strval($i)] = $action;
                    $i++;
                }
                $statement->close();
            }

            // Load category data if necessary
            if( !isset($this->post['cat1']) ) {
                $controls_str = "SELECT categories.id FROM entries
                    join categories_entries on categories_entries.entry_id = entries.id
                    join categories on categories.id = categories_entries.category_id
                    where entries.url_name = ? and categories.url_name != ? and categories.url_name != ?";
                $statement = $this->mysqli->prepare($controls_str);
                $statement->bind_param("sss", $this->url_name, $g = "game103", $d = "distributable");
                $statement->execute();
                $statement->bind_result($category);

                $i = 1;
                while( $statement->fetch() ) {
                    $this->post['cat' . strval($i)] = $category;
                    $i++;
                }
                $statement->close();
            }

            return true;
           
        }

        /**
         * Move image and game files.
         */
        protected function move_files( $return_arr, $url_name ) {
            $gamefile_target_dir = getcwd() . "/stock/games/flash/";
            $gamefile_target_file = $gamefile_target_dir. $url_name . "." .  pathinfo($_FILES["gamefile_upload"]["name"], PATHINFO_EXTENSION);
            $imagefile_target_dir = getcwd() . "/images/icons/games/";
            $imagefile_target_file = $imagefile_target_dir. $url_name . "." . pathinfo($_FILES["imagefile_upload"]["name"], PATHINFO_EXTENSION);
            
            // move the files to the correct location
            // if the upload is successful that is the url
            if(move_uploaded_file($_FILES["gamefile_upload"]["tmp_name"],$gamefile_target_file)) {
                $url = $gamefile_target_file;
                $url = substr($url, 2);
                $return_arr["gamefile_status"] = "success";
            }
            if(move_uploaded_file($_FILES["imagefile_upload"]["tmp_name"],$imagefile_target_file)) {
                $image_url = $imagefile_target_file;
                $image_url = substr($image_url, 2);
                $return_arr["imagefile_status"] = "success";
            }
            return $return_arr;
        }

        /**
         * Insert a new game.
         * A file will override a url (for games and images)
         */
        protected function insert_new_game( $return_arr, $name, $url, $width, $height, $description, $image_url, $url_name, $game_type, $actions_controls, $cat1, $cat2 ) {
            // Insert game    
            $sql = "INSERT INTO entries(name, url, width, height, description, image_url, url_name, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ssiissss", $name, $url, $width, $height, $description, $image_url, $url_name, $game_type);
            $statement->execute();
            $id = $this->mysqli->insert_id;
            $statement->close();

            $this->insert_actions_controls_entries( $actions_controls, $id );
            $this->insert_categories_entries( $cat1, $cat2, $id );

            return $return_arr;
        }

        /**
         * Update a game.
         */
        protected function update_game( $return_arr, $name, $url, $width, $height, $description, $image_url, $url_name, $game_type, $actions_controls, $cat1, $cat2, $id ) {
            // Update
            $sql = "UPDATE entries set name = ?, url = ?, width = ?, height = ?, description = ?, image_url = ?, url_name = ?, type = ? where id = ?";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ssiissssi", $name, $url, $width, $height, $description, $image_url, $url_name, $game_type, $id);
            $statement->execute();
            $statement->close();

            // Delete current acctions controls
            $delete_sql = "DELETE FROM actions_controls_entries WHERE entry_id = ?";
            $statement = $this->mysqli->prepare($delete_sql);
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();

            // Insert new actions controls
            $this->insert_actions_controls_entries( $actions_controls, $id );
            
            // Delete current categories entries
            $delete_sql = "DELETE FROM categories_entries WHERE entry_id = ?";
            $statement = $this->mysqli->prepare($delete_sql);
            $statement->bind_param("i", $id);
            $statement->execute();
            $statement->close();

            // Insert new categories entries
            $this->insert_categories_entries( $cat1, $cat2, $id );

            return $return_arr;
        }

        /**
         * Insert values into actions controls entries.
         */
        protected function insert_actions_controls_entries( $actions_controls, $id ) {
            for($i=0;$i<count($actions_controls);$i++) {
                $ac_id = $actions_controls[$i];
                $sql = "INSERT INTO actions_controls_entries(action_control_id, entry_id) VALUES (?,?)";
                $statement = $this->mysqli->prepare($sql);
                $statement->bind_param("ii", $ac_id, $id);
                $statement->execute();
                $statement->close();
            }
        }

        /**
         * Insert values into categories entries.
         */
        protected function insert_categories_entries( $cat1, $cat2, $id ) {
            $sql = "INSERT INTO categories_entries(category_id, entry_id) VALUES (?,?)";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ii", $cat1, $id);
            $statement->execute();
            $statement->close();
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ii", $cat2, $id);
            $statement->execute();
            $statement->close();
        }

        /**
         * Create an array of actions controls from keys and actions
         * The array will be keyed by the id of the actions_controls entry
         * in the database.
         */
        protected function insert_actions_controls( $keys, $actions ) {
            $actions_controls = array();

            // we have to do a lookup to see if each pair/value already exists in actions_controls
            // If it does, get the id, otherwise insert and get id. Put in actions_controls array.
            for($i=0;$i<count($keys);$i++) {
                $key_id = $keys[$i];
                $action_id = $actions[$i];
                
                $sql = "SELECT id FROM actions_controls WHERE action_id = ? AND control_id = ?";
                $statement = $this->mysqli->prepare($sql);
                $statement->bind_param("ii", $action_id, $key_id);
                $statement->execute();
                $statement->bind_result($id);
                $statement->fetch();
                $statement->close();

                if($id) {
                    $actions_controls[] = $id;
                }
                else {
                    $sql = "INSERT INTO actions_controls(control_id, action_id) VALUES (?,?)";
                    $statement = $this->mysqli->prepare($sql);
                    $statement->bind_param("ii", $key_id, $action_id);
                    $statement->execute();
                    $id = $this->mysqli->insert_id;
                    $actions_controls[] = $id;
                    $statement->close();
                }
                
            }

            return $actions_controls;
        }

        /**
         * Insert new actions or controls.
         * The keys/actions array will be modified to include the ids
         * for the newly added keys/actions.
         */
        protected function insert_new_actions_or_controls( $arr, $table, $col_name ) {
            $set = [];

            for($i=0;$i<count($arr);$i++) {
                if(!is_numeric($arr[$i]) && !in_array($arr[$i], $set)) {
                    $set[] = $arr[$i];
                }
            }
            
            // Insert new rows and get new id
            for($i=0;$i<count($set);$i++) {
                $key = $set[$i];
                $sql = "INSERT INTO $table($col_name) VALUES (?)";

                $statement = $this->mysqli->prepare($sql);
                $statement->bind_param("s", $key);
                $statement->execute();
                $id = $this->mysqli->insert_id;
                $statement->close();

                for($j=0;$j<count($arr);$j++) {
                    if($arr[$j] == $key) {
                        $arr[$j] = $id;
                    }
                }
            }

            return $arr;
        }

        /**
         * Generate controls
         */
        protected function generate_controls() {
            $controls_str = "SELECT controls.id, controls.key FROM controls ORDER BY controls.key";
            $controls_statement = $this->mysqli->prepare($controls_str);
            $controls_statement->execute();
            $controls_statement->bind_result($controls_id, $controls_key);
            $controls_ids_arr = array();
            $controls_keys_arr = array();
            while($controls_statement->fetch()) {
                $controls_ids_arr[] = $controls_id;
                if( !$json_controls_only) { 
                    $controls_keys_arr[] = "'" . $controls_key . "'";
                }
                else {
                    $controls_keys_arr[] = $controls_key;
                }
            }
            $controls_statement->close();

            return array(
                'controls_ids' => $controls_ids_arr,
                'controls_keys' => $controls_keys_arr 
            );
        }

        /**
         * Generate actions.
         */
        protected function generate_actions() {
            $actions_str = "SELECT id, name FROM actions ORDER BY name";
            $actions_statement = $this->mysqli->prepare($actions_str);
            $actions_statement->execute();
            $actions_statement->bind_result($actions_id, $actions_key);
            $actions_ids_arr = array();
            $actions_names_arr = array();
            while($actions_statement->fetch()) {
                $actions_ids_arr[] = $actions_id;
                if( !$json_controls_only ) {
                    $actions_names_arr[] = "'" . $actions_key . "'";
                }
                else {
                    $actions_names_arr[] = $actions_key;
                }
            }
            $actions_statement->close();

            return array(
                'actions_ids' => $actions_ids_arr,
                'actions_names' => $actions_names_arr 
            );
        }

        /**
         * Error check.
         */
        protected function error_check($return_arr, $cat1, $keys, $actions) {
            $return_arr['status'] = "success";

            // Need at least one category
            if(!$cat1) {
                die("Please select a category");
                $return_arr['status'] = "error";
                $return_arr['message'] = self::CATEGORY_ERROR_MESSAGE;
            }

            // Check for duplicates
            // Remember, keys[$i] and actions[$i] correspond to a key action pair.
            for($i=0;$i<count($keys)-1;$i++) {
                $key_check = $keys[$i];
                for($j=$i+1; $j<count($keys);$j++) {
                    if($keys[$i] == $keys[$j] && $actions[$i] == $actions[$j]) {
                        $return_arr['status'] = "error";
                        $return_arr['message'] = self::DUPLICATES_ERROR_MESSAGE;
                    }
                }
            }

            return $return_arr;
        }
		
	}
	
?>