<?php
	namespace Service;

	require_once('Constants.class.php');
	require_once('Service.class.php');
	
	/**
	* Class represening the character's service
	*/
	class Characters extends \Service {
	
		protected $mysqli;
		
		/**
		* Constructor.
		*/
		public function __construct( $mysqli ) {
			\Service::__construct();
			$this->mysqli = $mysqli;
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			$select_str =
					"SELECT * FROM(
					SELECT characters.name, characters.ipa_name, characters.description, characters.image_url, game_name, game_url_name, type
					FROM hallaby_games.characters 
					JOIN hallaby_games.characters_entries ON characters_entries.character_id = characters.id
					JOIN (SELECT entries.id as inner_id, entries.name as game_name, entries.url_name as game_url_name, 'game' as type FROM hallaby_games.entries) as entries_games
					ON characters_entries.entry_id = inner_id
					UNION ALL
					SELECT characters.name, characters.ipa_name, characters.description, characters.image_url, game_name, game_url_name, type
					FROM hallaby_games.characters 
					JOIN hallaby_games.apps_characters ON apps_characters.character_id = characters.id
					JOIN (SELECT apps.id as inner_id, apps.name as game_name, apps.url_name as game_url_name, 'app' as type FROM hallaby_games.apps) as apps_games
					ON apps_characters.app_id = inner_id
					UNION ALL
					SELECT characters.name, characters.ipa_name, characters.description, characters.image_url, game_name, game_url_name, type
					FROM hallaby_games.characters
					JOIN hallaby_games.characters_downloads ON characters_downloads.character_id = characters.id
					JOIN (SELECT downloads.id as inner_id, downloads.name as game_name, downloads.url_name as game_url_name, 'download' as type FROM hallaby_games.downloads) as downloads_games
					ON characters_downloads.download_id = inner_id
					) AS characters_query
					ORDER BY name ASC, game_name ASC";
			return $select_str;
		}
		
		/**
		* Run a sql statement
		*/
		protected function run_sql( $select_statement ) {
			print mysqli_error( $this->mysqli );
			$select_statement->execute();
			if(mysqli_stmt_error($select_statement) != "") {
				throw new \Exception(\Constants::MYSQL_MESSAGE);
				$this->mysqli->close();
				exit();
			}
			
			return $select_statement;
		}
		
		/**
		* Parse the result of a sql statement
		*/
		protected function parse_result( $select_statement ) {
			$select_statement->bind_result( $name, $ipa_name, $description, $image_url, $game_name, $game_url_name, $game_type );
			
			$items = array();
			$prev_name = "";
			$character_index = -1;
			while($select_statement->fetch()) {
				// We have a new entry
				if( $name != $prev_name ) {
					$item_object = array (
						"name" => htmlentities($name, ENT_QUOTES),
						"ipa_name" => $ipa_name,
						"description" => $description,
						"image_src" => $image_url,
						"games" => array()
					);
					$character_index ++;
					// Update our index - we keep track of which character we are on
					// due to the join with the games
					$items[$character_index] = $item_object;
					$prev_name = $name;
				}
				// Add to the games list of the current character
				$items[$character_index]['games'][] = array( 'name' => $game_name, 'url_name' => $game_url_name, 'type' => $game_type );
			}
			$select_statement->close();
			return $this->supplement_items( $items, $total_count );
		}
		
		/**
		* Supplement items to create everything needed for the front end
		*/
		protected function supplement_items( $items, $total_count ) {
			if(count($items) > 0) {
				$return_val = array(
					"status" => "success",
					"items" => $items
 				);
			}
			else {
				$return_val = array(
					"status" => "failure",
					"message" => "No characters were found."
				);
			}
			return $return_val;
		}
		
		/**
		* Generate results hash.
		*/
		public function generate() {
			
			try {
				return $this->parse_result( $this->run_sql ( $this->mysqli->prepare($this->generate_sql()) ) );
			}
			catch (\Exception $e) {
				return $this->return_error( $e->getMessage() );
			}
		}
	
	}
	
?>