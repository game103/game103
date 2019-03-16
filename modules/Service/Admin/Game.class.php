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

            $this->generate_controls();
            $this->generate_actions();
            $this->listing();

            return $this->processed_post;
        }

        /**
		* Process the user's input.
		*/
        protected function process() {

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
            $this->processed_post = array(
                'id'            =>  $this->post['id'],
                'name'          =>  $this->post['name'],
                'url'           =>  $this->post['url'],
                'width'         =>  $this->post['width'],
                'height'        =>  $this->post['height'],
                'description'   =>  $this->post['description'],
                'image_url'     =>  $this->post['image_url'],
                'type'          =>  $this->post['type'],
                'cat1'          =>  $this->post['cat1'],
                'cat2'          =>  $this->post['cat2'],
                'keys'          =>  $keys,
                'actions'       =>  $actions
            );

            // If we are featuring, then update the featured table
            // We will not accept a non-existant game here, which is why
            // we check for id
            if($this->post['id'] && $this->post['feature']) {
                // Remove the oldest featured game
                $sql = "UPDATE featured set removed_date = now() where id = (select id from (select * from featured) as featured_inner where removed_date is null order by added_date asc limit 1)";
                $statement = $this->mysqli->prepare($sql);
                $statement->execute();
                $statement->close();
                // Insert the new game
                $sql = "INSERT INTO featured(entry_id, added_date) VALUES (?, now())";
                $statement = $this->mysqli->prepare($sql);
                $statement->bind_param("i", $this->processed_post['id']);
                $statement->execute();
                $statement->close();
                $this->processed_post['status'] = "success";
            }

            // If we are submitting, then do an insert/update
            else if($this->post['submit']) {
                // Generate the url name
                $this->generate_url_name();
                
                if($this->processed_post['cat2'] == "") {
                    unset($this->processed_post['cat2']);
                }     

                // Check for errors
                $this->error_check();
                if( $this->processed_post['status'] === 'error' ) {
                    return $this->processed_post;
                }

                // Insert actions and keys to the database that don't have IDs
                $this->processed_post['keys'] = $this->insert_new_actions_or_controls( $this->processed_post['keys'], "controls", "controls.key" );
                $this->processed_post['actions'] = $this->insert_new_actions_or_controls( $this->processed_post['actions'], "actions", "name" );
                
                // Generate actions_controls
                $this->insert_actions_controls();
            
                // move files
                $this->move_image_files( "games" );
                $this->move_game_files();

                if( !$this->processed_post['id'] ) {
                    $this->insert_new_game();
                }
                else {
                    $this->update_game();
                }
            }

            return $this->processed_post;
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

            $this->load_categories();

            return true;
           
        }

        /**
         * Load the values for an existing entry into the post data (this can later be processed)
         * (Overrides to not include game103 or distributable categories)
         */
        protected function load_categories() {
            // Load category data if necessary
            if( !isset($this->post['cat1']) ) {
                $cat_str = "SELECT categories.id FROM entries
                    join categories_entries on categories_entries.entry_id = entries.id
                    join categories on categories.id = categories_entries.category_id
                    where entries.url_name = ? and categories.url_name != ? and categories.url_name != ?";
                $statement = $this->mysqli->prepare($cat_str);
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
        }

        /**
         * Move game files.
         */
        protected function move_game_files() {
            $move_game_response = $this->move_file( $_FILES['gamefile_upload'], "/stock/games/flash/", $this->processed_post['url_name'] );

            if( $move_game_response ) {
                $this->processed_post['url'] = $move_game_response;
            }
        }

        /**
         * Insert a new game.
         * A file will override a url (for games and images)
         */
        protected function insert_new_game() {
            // Insert game    
            $sql = "INSERT INTO entries(name, url, width, height, description, image_url, url_name, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ssiissss", $this->processed_post['name'], $this->processed_post['url'], $this->processed_post['width'], 
                $this->processed_post['height'], $this->processed_post['description'], $this->processed_post['image_url'], $this->processed_post['url_name'], $this->processed_post['type']);
            $statement->execute();
            $this->processed_post['id'] = $this->mysqli->insert_id;
            $statement->close();

            $this->insert_actions_controls_entries();
            $this->insert_categories_entries();
        }

        /**
         * Update a game.
         */
        protected function update_game() {
            // Update
            $sql = "UPDATE entries set name = ?, url = ?, width = ?, height = ?, description = ?, image_url = ?, url_name = ?, type = ? where id = ?";
            $statement = $this->mysqli->prepare($sql);
            $statement->bind_param("ssiissssi", $this->processed_post['name'], $this->processed_post['url'], $this->processed_post['width'], 
                $this->processed_post['height'], $this->processed_post['description'], $this->processed_post['image_url'], 
                $this->processed_post['url_name'], $this->processed_post['type'], $this->processed_post['id']);
            $statement->execute();
            $statement->close();

            // Delete current acctions controls
            $delete_sql = "DELETE FROM actions_controls_entries WHERE entry_id = ?";
            $statement = $this->mysqli->prepare($delete_sql);
            $statement->bind_param("i", $this->processed_post['id']);
            $statement->execute();
            $statement->close();

            // Insert new actions controls
            $this->insert_actions_controls_entries();
            
            // Delete current categories entries
            $this->delete_categories_entries();

            // Insert new categories entries
            $this->insert_categories_entries();
        }

        /**
         * Insert values into actions controls entries.
         */
        protected function insert_actions_controls_entries() {
            for($i=0;$i<count($this->processed_post['actions_controls']);$i++) {
                $ac_id = $this->processed_post['actions_controls'][$i];
                $sql = "INSERT INTO actions_controls_entries(action_control_id, entry_id) VALUES (?,?)";
                $statement = $this->mysqli->prepare($sql);
                $statement->bind_param("ii", $ac_id, $this->processed_post['id']);
                $statement->execute();
                $statement->close();
            }
        }

        /**
         * Create an array of actions controls from keys and actions
         * The array will be keyed by the id of the actions_controls entry
         * in the database.
         */
        protected function insert_actions_controls() {
            $actions_controls = array();

            // we have to do a lookup to see if each pair/value already exists in actions_controls
            // If it does, get the id, otherwise insert and get id. Put in actions_controls array.
            for($i=0;$i<count($this->processed_post['keys']);$i++) {
                $key_id = $this->processed_post['keys'][$i];
                $action_id = $this->processed_post['actions'][$i];
                
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

            $this->processed_post['actions_controls'] = $actions_controls;
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

            $this->processed_post['controls_ids'] = $controls_ids_arr;
            $this->processed_post['controls_keys'] = $controls_keys_arr;
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

            $this->processed_post['actions_ids'] = $actions_ids_arr;
            $this->processed_post['actions_names'] = $actions_names_arr;
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

            // Check for duplicates
            // Remember, keys[$i] and actions[$i] correspond to a key action pair.
            for($i=0;$i<count($this->processed_post['keys'])-1;$i++) {
                if( $this->processed_post['keys'][$i] && $this->processed_post['actions'][$i] ) {
                    for($j=$i+1; $j<count($this->processed_post['keys']);$j++) {
                        if($this->processed_post['keys'][$i] == $this->processed_post['keys'][$j] 
                            && $this->processed_post['actions'][$i] == $this->processed_post['actions'][$j]) {
                            $this->processed_post['status'] = "error";
                            $this->processed_post['message'] = self::DUPLICATES_ERROR_MESSAGE;
                        }
                    }
                }
            }
        }
		
	}
	
?>
