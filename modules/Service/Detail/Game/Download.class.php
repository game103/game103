<?php
	namespace Service\Detail\Game;
	
	require_once('Constants.class.php');
	require_once('Service/Detail/Game.class.php');
	
	/**
	* Class represening a downloadable game
	*/
	class Download extends \Service\Detail\Game {
				
		/**
		* Constructor.
		*/
		public function __construct( $url_name, $mysqli ) {
			\Service\Detail\Game::__construct( $url_name, $mysqli );
			$this->table = 'downloads';
			$this->table_singular = 'download';
		}
		
		/**
		* Get download details.
		*/
		protected function generate_game() {
			$this->url_name = $this->mysqli->real_escape_string($this->url_name);
			// String to query the database with
			$str = "SELECT id, name, url, description, screenshot_url, FORMAT(saves, 0)
			FROM hallaby_games.downloads WHERE url_name = ? LIMIT 1";
			// Prepare the statement
			$statement = $this->mysqli->prepare($str);
			// Bind parameters
			$statement->bind_param("s", $this->url_name);
			// Execute the statement
			$statement->execute();
			// Check for errors {
			if(mysqli_stmt_error($statement) != "") {
				$statement->close();
				$this->mysqli->close();
				throw new \Exception(\Constants::MYSQL_MESSAGE);
			}
			// Get the one result
			$statement->bind_result($id, $name, $url, $description, $screenshot_url, $saves);
			// Fetch the result
			$statement->fetch();
			// Close the statement
			$statement->close();
			if(!isset($id)) {
				throw new \Exception(self::GAME_DOES_NOT_EXIST_MESSAGE);
			}
			if($id == 0) {
				throw new \Exception(self::GAME_DOES_NOT_EXIST_MESSAGE);
			}
			
			// Note that id is set here
			$this->id = $id;
			
			return array(
				'id' => $id,
				'name' => $name,
				'url' => $url,
				'description' => $description,
				'saves' => $saves,
				'screenshot_url' => $screenshot_url
			);
		}
		
		/**
		* Generate results hash.
		*/
		public function generate() {
			
			try {
				$response = $this->generate_game();
				$response['controls'] = $this->generate_controls();
				$response['characters'] = $this->generate_characters();
				$response['videos'] = $this->generate_videos();
				return $response;
			}
			catch (\Exception $e) {
				return $this->return_error( $e->getMessage() );
			}
		}
	}
	
?>