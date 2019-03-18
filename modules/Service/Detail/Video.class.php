<?php
	namespace Service\Detail;
	
	require_once('Constants.class.php');
	require_once('Service/Detail.class.php');
	
	/**
	* Class represening a video
	*/
	class Video extends \Service\Detail {
		
		const VIDEO_DOES_NOT_EXIST_MESSAGE = "The video specified does not exist.";
		
		/**
		* Constructor.
		*/
		public function __construct( $url_name, $mysqli ) {
			\Service\Detail::__construct( $url_name, $mysqli );
			$this->db = 'hallaby_videos';
		}
		
		/**
		* Get game details.
		*/
		protected function generate_video() {
			$this->url_name = $this->mysqli->real_escape_string($this->url_name);
			// String to query the database with
			$str = "SELECT id, name, entries.string, DATE_FORMAT(added_date, '%M %D, %Y'), description,
			FORMAT(views, 0), image_url, type, width, height FROM hallaby_videos.entries WHERE url_name = ? LIMIT 1";
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
			$statement->bind_result($id, $name, $string, $added_date, $description, $views, $image_url, $type, $width, $height);
			// Fetch the result
			$statement->fetch();
			// Close the statement
			$statement->close();
			if(!isset($id)) {
				throw new \Exception(self::VIDEO_DOES_NOT_EXIST_MESSAGE);
			}
			if($id == 0) {
				throw new \Exception(self::VIDEO_DOES_NOT_EXIST_MESSAGE);
			}
			
			// Note that id is set here
			$this->id = $id;
			
			return array(
				'id' => $id,
				'name' => $name,
				'string' => $string,
				'added_date' => $added_date,
				'description' => $description,
				'views' => $views,
				'image_url' => $image_url,
				'url_name' => $this->url_name,
				'video_type' => $type,
				'width' => $width,
				'height' => $height
			);
		}
		
		/**
		* Generate games
		*/
		protected function generate_games() {
			$games_str = "
			SELECT hallaby_games.entries.name as name, hallaby_games.entries.url_name, hallaby_games.entries_videos.video_id as video_id, 'game'
			FROM hallaby_games.entries_videos
			JOIN hallaby_games.entries on hallaby_games.entries_videos.entry_id = hallaby_games.entries.id
			WHERE video_id = ?
			UNION
			SELECT hallaby_games.downloads.name as name, hallaby_games.downloads.url_name, hallaby_games.downloads_videos.video_id as video_id, 'download'
			FROM hallaby_games.downloads_videos
			JOIN hallaby_games.downloads on hallaby_games.downloads_videos.download_id = hallaby_games.downloads.id
			WHERE video_id = ?
			UNION
			SELECT hallaby_games.apps.name as name, hallaby_games.apps.url_name, hallaby_games.apps_videos.video_id as video_id, 'app'
			FROM hallaby_games.apps_videos
			JOIN hallaby_games.apps on hallaby_games.apps_videos.app_id = hallaby_games.apps.id
			WHERE video_id = ?
			ORDER BY name";
			$games_statement = $this->mysqli->prepare($games_str);
			$games_statement->bind_param("iii", $this->id, $this->id, $this->id);
			$games_statement->execute();
			if(mysqli_stmt_error($games_statement) != "") {
				$characters_statement->close();
				$this->mysqli->close();
				throw new \Exception(\Constants::MYSQL_MESSAGE);
			}
			$games_statement->bind_result($game_name, $game_url_name, $unused_id, $type);
			$games = array();
			while($games_statement->fetch()) {
				$games[$game_url_name] = array( 'name' => $game_name, 'type' => $type );
			}

			return $games;
		}
	
		
		/**
		* Generate results hash.
		*/
		public function generate() {
			
			try {
				$response = $this->generate_video();
				$response['games'] = $this->generate_games();
				$response['rating'] = $this->generate_rating();
				return $response;
			}
			catch (\Exception $e) {
				return $this->return_error( $e->getMessage() );
			}
		}
	}
	
?>