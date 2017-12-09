<?php
	namespace Service\Detail;
	
	require_once('Constants.class.php');
	require_once('Service/Detail.class.php');
	
	/**
	* Abstract class represening a game
	*/
	abstract class Game extends \Service\Detail {
		
		const GAME_DOES_NOT_EXIST_MESSAGE = "The game specified does not exist.";
		
		protected $table;
		protected $table_singular;
		
		/**
		* Constructor.
		*/
		public function __construct( $url_name, $mysqli ) {
			\Service\Detail::__construct( $url_name, $mysqli );
			$this->db = 'hallaby_games';
		}
		
		/**
		* Get game details.
		*/
		abstract protected function generate_game();
		
		/**
		* Generate controls.
		*/
		protected function generate_controls() {
			$controls_str = "SELECT controls.key, actions.name FROM hallaby_games.{$this->table}
				join hallaby_games.actions_controls_{$this->table} on actions_controls_{$this->table}.{$this->table_singular}_id = {$this->table}.id
				join hallaby_games.actions_controls on actions_controls_{$this->table}.action_control_id = actions_controls.id
				join hallaby_games.actions on actions_controls.action_id = actions.id
				join hallaby_games.controls on actions_controls.control_id = controls.id
			WHERE {$this->table}.id = ?
			ORDER BY controls.key;";
			$controls_statement = $this->mysqli->prepare($controls_str);
			$controls_statement->bind_param("i", $this->id);
			$controls_statement->execute();
			if(mysqli_stmt_error($controls_statement) != "") {
				$controls_statement->close();
				$this->mysqli->close();
				throw new \Exception(\Constants::MYSQL_MESSAGE);
			}
			$controls_statement->bind_result($key, $action);
			$keys_actions_arr = array();
			while($controls_statement->fetch()) {
				$keys_arr = array_keys($keys_actions_arr);
				
				// Combine info for controls
				// For all the current keys in the array
				for($i=0;$i<count($keys_arr);$i++) {
					$cur_key = $keys_arr[$i];
					$cur_actions_arr = $keys_actions_arr[$cur_key];
					// Check if any of the keys already map to the action
					if(in_array($action, $cur_actions_arr)) {
						// If so, append the key to the found key (cur_key)
						$key = $cur_key . "/$key";
						// If the only entry in the keys actions array
						// for cur_key is the matched action, get rid of that whole
						// key from the list of keys (Our current key will include
						// that key seperated with a /).
						if(count($keys_actions_arr[$cur_key]) == 1) {
							unset($keys_actions_arr[$cur_key]);
						}
						// Otherwise, just remove the action from that key's list
						// Since now the action will be a part of the currently fetched key
						else {
							$action_index = array_search($action, $cur_actions_arr);
							unset($keys_actions_arr[$cur_key][$action_index]);
						}
						
						break;
					}
				}
				
				// Add the key to the keys actions array
				$keys_actions_arr[$key][] = $action;
			}
			
			return $keys_actions_arr;
		}
		
		/**
		* Generate characters
		*/
		protected function generate_characters() {
			// Get the controls
			$characters_str = "SELECT characters.name FROM hallaby_games.characters_{$this->table}
			JOIN hallaby_games.characters on characters_{$this->table}.character_id = characters.id
			WHERE characters_{$this->table}.{$this->table_singular}_id = ?
			ORDER BY characters.name";
			$characters_statement = $this->mysqli->prepare($characters_str);
			$characters_statement->bind_param("i", $this->id);
			$characters_statement->execute();
			if(mysqli_stmt_error($characters_statement) != "") {
				$characters_statement->close();
				$this->mysqli->close();
				throw new \Exception(\Constants::MYSQL_MESSAGE);
			}
			$characters_statement->bind_result( $character_name );
			$characters = array();
			while($characters_statement->fetch()) {
				array_push( $characters, $character_name );
			}
			
			return $characters;
		}
		
		/**
		* Generate videos
		*/
		protected function generate_videos() {
			$videos_str = "SELECT entries.name, entries.url_name FROM hallaby_games.{$this->table}_videos
			JOIN hallaby_videos.entries on {$this->table}_videos.video_id = hallaby_videos.entries.id
			WHERE {$this->table}_videos.{$this->table_singular}_id = ?
			ORDER BY hallaby_videos.entries.name";
			$videos_statement = $this->mysqli->prepare($videos_str);
			$videos_statement->bind_param("i", $this->id);
			$videos_statement->execute();
			if(mysqli_stmt_error($videos_statement) != "") {
				$characters_statement->close();
				$this->mysqli->close();
				throw new \Exception(\Constants::MYSQL_MESSAGE);
			}
			$videos_statement->bind_result($video_name, $video_url_name);
			$videos_count = 0;
			$videos = array();
			while($videos_statement->fetch()) {
				$videos[$video_url_name] = $video_name;
			}

			return $videos;
		}
	}
	
?>