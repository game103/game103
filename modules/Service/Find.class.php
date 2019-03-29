<?php
	namespace Service;

	require_once('Constants.class.php');
	require_once('Service.class.php');
	
	/**
	* Class represening the find box for all items on the site
	*/
	class Find extends \Service {
		
		const PAGE_MAX = 2;
		const PAGE_MIN = -2;
		const EVERYTHING_DESCRIPTION = "A list including most of the items on Game 103.";
		const APPS_DESCRIPTION = "A listing of family-friendly mobile games and apps that Game 103 has developed for iOS and android.";
		const AJAX_ERROR =  "Sorry, an error occured while trying to fetch more items. Please try again later.";
		const NO_RESULTS_MESSAGE = "Sorry, no results were found for your search.";
		const BAD_PARAMS_MESSAGE = "Unable to fetch items based on the url.";
		
		protected $mysqli;
		protected $page;
		protected $search;
		protected $category;
		protected $sort;
		protected $db;
		protected $type;
		protected $items_per_page;
		protected $platform;
		
		// These are all similar arrays
		// with keys of the url name to use they,
		// and values being arrays with keys
		// for potentially 'name', 'description',
		// 'sql', 'link'
		protected $valid_sort;
		protected $valid_categories;
		protected $valid_types;
		protected $valid_platforms;
		
		// This is similar to the valid arrays
		// The key is the display and the value
		// is the link
		protected $linked_pages;

		// Whether levenshtein search is enabled
		protected $levenshtein_search_enabled; 
		
		/**
		* Constructor.
		*/
		public function __construct( $search, $sort, $category, $page, $items_per_page, $mysqli ) {
			\Service::__construct();
			$this->page = $page;
			$this->items_per_page = $items_per_page;
			$this->sort = $sort;
			$this->search = $search;
			$this->category = $category;
			$this->mysqli = $mysqli;
			$this->type = $this->type ? $this->type : 'everything';
			
			$this->valid_categories = array();
			$this->valid_sort = array(
				'popularity'   => array('sql' => "numeric_interactions DESC, rating DESC", 'name' => 'Sort by popularity', 'link' => $this->generate_state_link( array( 'sort' => 'popularity' ) ) ),
				'rating'	   => array('sql' => "rating DESC, numeric_interactions DESC", 'name' => 'Sort by rating', 'link' => $this->generate_state_link( array( 'sort' => 'rating' ) ) ),
				'date'		   => array('sql' => 'added_date DESC, rating DESC', 'name' => 'Sort by date', 'link' => $this->generate_state_link( array( 'sort' => 'date' ) ) ),
				'alphabetical' => array('sql' => 'name', 'name' => 'Sort alphabetically', 'link' => $this->generate_state_link( array( 'sort' => 'alphabetical' ) ) )
			);
			$this->valid_types = array(
				'everything' => array( 'name' => 'Everything', 'link' => $this->generate_state_link( array( 'type' => 'everything', 'page' => 1, 'category' => '', 'platform' => '' ) ) ), 
				'games' => array( 'name' => 'Games','link' => $this->generate_state_link( array( 'type' => 'games', 'page' => 1, 'category' => 'all', 'platform' => '' ) ) ), 
				'videos' => array( 'name' => 'Videos', 'link' => $this->generate_state_link( array( 'type' => 'videos', 'page' => 1, 'category' => 'all', 'platform' => '' ) ) ), 
				'resources' => array( 'name' => 'Resources', 'link' => $this->generate_state_link( array( 'type' => 'resources', 'page' => 1, 'category' => 'all', 'platform' => '' ) ) ), 
				'apps' => array( 'name' => 'Apps', 'link' => $this->generate_state_link( array( 'type' => 'apps', 'page' => 1, 'category' => '', 'platform' => '' ) ) )
			);
			$this->valid_platforms = array();
			$this->linked_pages = array();

			$this->levenshtein_search_enabled = false;
			if( \Constants::LEVENSHTEIN_SEARCH_ENABLED && strlen($this->search) >= 3 ) {
				$this->levenshtein_search_enabled = true;
			}
			// It is important that we don't error check yet unless the above arrays are altered
		}
		
		/**
		* Error Check
		*/
		protected function error_check() {
			// Ensure the Page is valid
			if( !is_numeric($this->page) || $this->page <= 0 ) {
				throw new \Exception(self::BAD_PARAMS_MESSAGE);
			}
			// Ensure that we have a valid sort
			if( !$this->valid_sort[$this->sort] ) {
				throw new \Exception(self::BAD_PARAMS_MESSAGE);
			}
			// Ensure we have a valid category
			if( $this->valid_categories && !$this->valid_categories[$this->category] ) {
				throw new \Exception(self::BAD_PARAMS_MESSAGE);
			}
			// Ensure we have a valid sub type
			if( $this->valid_platforms && !$this->valid_platforms[$this->platform] ) {
				throw new \Exception(self::BAD_PARAMS_MESSAGE);
			}
		}
		
		/**
		* Escape bad characters.
		*/
		protected function escape() {
			// Escape bad characters
			$this->search = urldecode( $this->search );
			$this->category = $this->mysqli->real_escape_string($this->category);
			$this->search = $this->mysqli->real_escape_string($this->search);
			$this->sort = $this->mysqli->real_escape_string($this->sort);
			$this->page = $this->mysqli->real_escape_string($this->page);
		}
		
		/**
		* Generate sort sql.
		*/
		protected function generate_sort() {
			return $this->valid_sort[$this->sort]['sql'];
		}
		
		/**
		* Generate where sql for a specified table.
		*/
		protected function generate_where( $table ) {
			if($this->search != '' && !$this->levenshtein_search_enabled) {
				return "WHERE $table.name LIKE ?";
			}
			else {
				return "";
			}
		}
		
		/**
		* Generate the offset.
		*/
		protected function generate_offset() {
			return ($this->page - 1) * $this->items_per_page;
		}
		
		/**
		* Generate the sql
		*/
		protected function generate_sql() {
			$items_per_page = $this->items_per_page;
			$offset = $this->generate_offset();
			$where_sql = $this->generate_where('entries');
			$apps_sql = $this->generate_where('apps');
			$downloads_sql = $this->generate_where('downloads');
			$sort_sql = $this->generate_sort();
			$limit = "LIMIT $items_per_page OFFSET $offset";
			if( $this->levenshtein_search_enabled ) {
				$limit = "";
			}
			$select_str = "
					SELECT * FROM(
					SELECT name, description, url_name, image_url, rating, FORMAT(plays, 0), plays as numeric_interactions, added_date, -1 as store_url_android, -1 as store_url_apple, -1 as type, 'game', type as game_type FROM hallaby_games.entries $where_sql
					UNION
					SELECT name, description, url_name, image_url, -1 as rating, FORMAT(saves, 0), saves as numeric_interactions, added_date, -1 as store_url_android, -1 as store_url_apple, -1 as type, 'download', -1 as game_type FROM hallaby_games.downloads $downloads_sql
					UNION
					SELECT name, description, url_name, image_url, rating, FORMAT(views, 0), views as numeric_interactions, added_date, -1 as store_url_android, -1 as store_url_apple, -1 as type, 'video', -1 as game_type FROM hallaby_videos.entries $where_sql
					UNION
					SELECT name, description, url_name, image_url, -1 as rating, FORMAT(visits, 0), visits as numeric_interactions, added_date, store_url_android, store_url_apple, type, 'app', -1 as game_type FROM hallaby_games.apps $apps_sql
					ORDER BY $sort_sql
					$limit
					) AS main
					LEFT JOIN (
					SELECT sum(c) AS total_count
					FROM
					(SELECT count(1) as c FROM hallaby_games.entries $where_sql
					UNION
					SELECT count(1) as c FROM hallaby_games.downloads $downloads_sql
					UNION
					SELECT count(1) as c FROM hallaby_videos.entries $where_sql
					UNION
					SELECT count(1) as c FROM hallaby_games.apps $apps_sql)
					AS inner_count_query
					) AS count
					ON 1=1";
			return $select_str;
		}
		
		/**
		* Bind parameters to a mysqli statement
		*/
		protected function bind_params() {
			$this->escape();
			$select_statement = $this->mysqli->prepare( $this->generate_sql() );
			
			if( $this->search ) {
				$search_wildcards = '%' . $this->search . '%';
				$select_statement->bind_param("ssssssss", 
				$search_wildcards,
				$search_wildcards,
				$search_wildcards,
				$search_wildcards,
				$search_wildcards,
				$search_wildcards,
				$search_wildcards,
				$search_wildcards);
			}
			
			return $select_statement;
		}
		
		/**
		* Run a sql statement
		*/
		protected function run_sql( $select_statement ) {
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
			$select_statement->bind_result( $name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $added_date, $store_url_android, $store_url_apple, $app_type, $item_type, $game_type, $total_count );

			$items = array();
			while($select_statement->fetch()) {

				$item_object = array (
					"title" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_src" => $image_url,
					"count" => $interactions,
					"url_name"	=> $url_name,
					"rating" => $rating,
					"type" => $item_type,
					"app_type" => $app_type,
					"store_url_android" => $store_url_android,
					"store_url_apple" => $store_url_apple,
					"added_date" => $added_date,
					"game_type" => $game_type,
					"name" => $name
				);

				$items[] = $item_object;
			}
			$select_statement->close();

			if( $this->levenshtein_search_enabled ) {
				return $this->filter_result_levenshtein( $items );
			}
			return $this->supplement_items( $items, $total_count );
		}

		/**
		 * Filter result by levenshtein matches
		 */
		protected function filter_result_levenshtein( $items ) {

			$manual_count = 0;
			$new_items = array();
			foreach ( $items as $item ) {

				$ratio = $this->levenshtein_ratio( $this->search, $item['name'], true );
				// If we have similar text match enabled, we do the search filter in php
				if( $ratio['ratio'] > 85 || 
					$ratio['boosted'] > 92 || 
					$ratio['max_part_ratio'] > 70 || 
					$ratio['word_ratio_boosted'] > 80 ) {

					$item['word_ratio_boosted'] = $ratio['word_ratio_boosted'];
					$item['max_part_ratio'] = $ratio['max_part_ratio'];
					$item['original_place'] = $manual_count;
					$new_items[] = $item;
					
					$manual_count ++;
				}

			}

			// Sort by match num (how well matched), then string length diff absolute value, then string length (more text = more like what user wanted), then actual sort (popularity, etc.)
			usort($new_items, function ($a, $b) { 
				if( $a['word_ratio_boosted'] > $b['word_ratio_boosted'] ) { return -1; }
				if( $a['word_ratio_boosted'] < $b['word_ratio_boosted'] ) { return 1; }
				if( $a['max_part_ratio'] > $b['max_part_ratio'] ) { return -1; }
				if( $a['max_part_ratio'] < $b['max_part_ratio'] ) { return 1; }
				if( $a['original_place'] < $b['original_place'] ) { return -1; }
				if( $a['original_place'] > $b['original_place'] ) { return 1; }
				return 0;
			});

			// Take care of pagination
			$items_per_page = $this->items_per_page;
			$offset = $this->generate_offset();
			$paginated_items = array_splice( $new_items, $offset, $items_per_page );

			return $this->supplement_items( $paginated_items, $manual_count );
		}

		/**
		 * Find the levenshtein ratio between a search and a name.
		 * Future suggestions
		 * sooper bouncy should have same results as bouncy sooper, do you need to get permutations after all for part ratios?
		 * jellybears > should find jelly bears. we should create all possible combined words, but full ratio matches for now.
		 * stemming
		 * prefix favorability
		 */
		protected function levenshtein_ratio( $search, $name, $substrings ) {
			
			if( $substrings ) { // should be already fixed with substrings
				// Fix the phrases
				$search = strtolower( $search );
				$name = strtolower( $name );
				$search = str_replace( "é", "e", $search );
				$name = str_replace( "é", "e", $name );
				$search = preg_replace( "/[\?<>,\.;:'\[\]{}\(\)\*&^%$#@!]/", "", $search );
				$name = preg_replace( "/[\?<>,\.;:'\[\]{}\(\)\*&^%$#@!]/", "", $name );
				// Remove stopwords
				$stopwords = array("a", "about", "above", "after", "again", "against", "ain", "all", "am", "an", "and", "any", "are", "aren", "aren't", "as", "at", "be", "because", "been", "before", "being", "below", "between", "both", "but", "by", "can", "couldn", "couldn't", "d", "did", "didn", "didn't", "do", "does", "doesn", "doesn't", "doing", "don", "don't", "down", "during", "each", "few", "for", "from", "further", "had", "hadn", "hadn't", "has", "hasn", "hasn't", "have", "haven", "haven't", "having", "he", "her", "here", "hers", "herself", "him", "himself", "his", "how", "i", "if", "in", "into", "is", "isn", "isn't", "it", "it's", "its", "itself", "just", "ll", "m", "ma", "me", "mightn", "mightn't", "more", "most", "mustn", "mustn't", "my", "myself", "needn", "needn't", "no", "nor", "not", "now", "o", "of", "off", "on", "once", "only", "or", "other", "our", "ours", "ourselves", "out", "over", "own", "re", "s", "same", "shan", "shan't", "she", "she's", "should", "should've", "shouldn", "shouldn't", "so", "some", "such", "t", "than", "that", "that'll", "the", "their", "theirs", "them", "themselves", "then", "there", "these", "they", "this", "those", "through", "to", "too", "under", "until", "up", "ve", "very", "was", "wasn", "wasn't", "we", "were", "weren", "weren't", "what", "when", "where", "which", "while", "who", "whom", "why", "will", "with", "won", "won't", "wouldn", "wouldn't", "y", "you", "you'd", "you'll", "you're", "you've", "your", "yours", "yourself", "yourselves", "could", "he'd", "he'll", "he's", "here's", "how's", "i'd", "i'll", "i'm", "i've", "let's", "ought", "she'd", "she'll", "that's", "there's", "they'd", "they'll", "they're", "they've", "we'd", "we'll", "we're", "we've", "what's", "when's", "where's", "who's", "why's", "would");
				foreach( $stopwords as $stopword ) {
					$search = preg_replace( '/^'.$stopword.'[\s-_]|[\s-_]'.$stopword.'[\s-_]|[\s-_]'.$stopword.'$/', " ", $search );
					$name = preg_replace( '/^'.$stopword.'[\s-_]|[\s-_]'.$stopword.'[\s-_]|[\s-_]'.$stopword.'$/', " ", $name );
				}
				$search = trim($search);
				$name = trim($name);
			}

			$search_length = strlen($search);
			$name_length = strlen($name);
			$greater = $name_length;   
  			if( $search_length > $name_length ) { $greater = $search_length; }
			$match_percent = (1 - levenshtein($search, $name)/$greater) * 100;
			// Boosted will match any successive characters in the string (so search is "boo", "Big Old Octopus" would be 100 - this is why we have phrase and word match)
			// Boost is pretty sloppy
			$boost = min( max( 1, $name_length/$search_length ), 4 );

			// If substrings is true, we generate in order substrings of the name
			// of length - the same number of words as the search. We then find the maximum
			// levenshtein ratio of all the substrings compared to the search and include that in the result.
			// This allows us to favor phrases with matching terms that directly follow one another
			// (e.g. for "bald eagle" favor "bald eagle game" over "help the bald man find the eagle")
			if( $substrings ) {

				$search_parts = preg_split( "/[\s-_]/", $search );
				$search_num_of_words = count($search_parts);
				$name_parts = preg_split( "/[\s-_]/", $name );
				$max_part_ratio = 0;
				for( $i=0; $i<count($name_parts); $i++ ) {

					// We can have equal since the index used will be one less
					if( $i + $search_num_of_words <= count($name_parts) ) {

						$part_ratio = $this->levenshtein_ratio( $search, implode( array_slice( $name_parts, $i, $search_num_of_words ) ), false );
						if( $part_ratio['ratio'] > $max_part_ratio ) {
							$max_part_ratio = $part_ratio['ratio'];
						}

					}

				}
				
				// For each of the search parts, get the maximum of all the
				// levenshtein distances between this search part and each of the name parts
				// Then, return to minimum of all the previously calulated values for the search parts
				// This allows us to match terms that just have all the right words (even if in wrong order)
				$min_word_dist = 100;
				for( $i=0; $i<count($search_parts); $i++) {
					$max_word_dist = 0;
					for( $j=0; $j<count($name_parts); $j++ ) {
						$dist = $this->levenshtein_ratio( $search_parts[$i], $name_parts[$j], false );
						// We use boosted to allow for substrings
						if( $dist['boosted'] > $max_word_dist ) {
							$max_word_dist = $dist['boosted'];
						}
					}
					if( $max_word_dist < $min_word_dist ) {
						$min_word_dist = $max_word_dist;
					}
				}

			}

			return array( 'ratio' => round($match_percent, 2), "boosted" => round($match_percent * $boost, 2), "max_part_ratio" => round($max_part_ratio, 2), "word_ratio_boosted" => round($min_word_dist, 2) );
		}
		
		/**
		* Supplement items to create everything needed for the front end
		*/
		protected function supplement_items( $items, $total_count ) {
			if(count($items) > 0) {
				$this->generate_linked_pages( $total_count );
				$title = ucfirst( $this->type );
				if( $this->get_category_name() ) {
					$title = $this->get_category_name() . " " . $title;
				}
				$description = $this->get_category_description();
				if( !$description ) {
					$description = \Service\Find::EVERYTHING_DESCRIPTION;
				}
				$return_val = array(
					"status" => "success",
					"count" => $total_count,
					"items" => $items,
					"valid_categories" => $this->valid_categories,
					"valid_types" => $this->valid_types,
					"valid_sort" => $this->valid_sort,
					"valid_platforms" => $this->valid_platforms,
					"linked_pages" => $this->linked_pages,
					"category" => $this->category,
					"type" => $this->type,
					"sort" => $this->sort,
					"page" => $this->page,
					"search" => $this->search,
					"platform" => $this->platform,
					"title" => $title,
					"description" => $description
 				);
			}
			else {
				$return_val = array(
					"status" => "failure",
					"message" => self::NO_RESULTS_MESSAGE,
					"valid_categories" => $this->valid_categories,
					"valid_types" => $this->valid_types,
					"valid_sort" => $this->valid_sort,
					"valid_platforms" => $this->valid_platforms,
					"category" => $this->category,
					"type" => $this->type,
					"sort" => $this->sort,
					"page" => $this->page,
					"search" => $this->search,
					"platform" => $this->platform,
					"title" => 'Error',
					"description" => self::NO_RESULTS_MESSAGE
				);
			}
			return $return_val;
		}
		
		/**
		* Generate results hash.
		*/
		public function generate() {
			
			try {
				$this->error_check();
				return $this->parse_result( $this->run_sql ( $this->bind_params() ) );
			}
			catch (\Exception $e) {
				return $this->return_error( $e->getMessage() );
			}
		}
		
		/**
		* Generate categories.
		* Setup the array of categories
		* Keys are id, values are arrays with [name] = display name
		* and [description] = is their description
		* NOTE: it is important here that the ids match the database search term
		* save for - filling in for a space.
		* The javascript will use these IDs to perform web service requests to fetch new games
		* This should be called within a try catch block as it throws an \Exception
		*/
		public function generate_categories() {
			$category_select_str = "SELECT name, url_name, description FROM {$this->db}.categories order by sort asc;";
			$category_select_statement = $this->mysqli->prepare($category_select_str);
			$category_select_statement->execute();
			if(mysqli_stmt_error($category_select_statement) != "") {
				throw new \Exception(\Constants::MYSQL_MESSAGE);
				$this->mysqli->close();
				exit();
			}
			$category_select_statement->bind_result($category_name, $category_url_name, $category_description);
			while($category_select_statement->fetch()) {
				$category_arr = array( 'name' => $category_name, 'description' => $category_description, 'link' => $this->generate_state_link( array( 'category' => $category_url_name, 'page' => 1 ) ) );
				$this->valid_categories[$category_url_name] = $category_arr;
			}
		}
		
		/**
		* Get category name
		*/
		public function get_category_name() {
			return $this->valid_categories[ $this->category ]['name'];
		}
		
		/**
		* Get category description
		*/
		public function get_category_description() {
			return $this->valid_categories[ $this->category ]['description'];
		}

		/**
		 * Get valid categories
		 */
		public function get_valid_categories() {
			return $this->valid_categories;
		}
		
		/**
		* Generate the pages to be linked to from this page
		*/
		public function generate_linked_pages( $total_count ) {
			// First, find the maximum and minimum pages
			$last_page = ceil($total_count /  $this->items_per_page);
			$max_numeric_page = $this->page + self::PAGE_MAX;
			$min_numeric_page = $this->page + self::PAGE_MIN;
			// Now we'll see if just one of our values is invalid
			// If so, we'll add it on to the valid one
			if( $max_numeric_page > $last_page xor $min_numeric_page < 1 ) {
				if( $max_numeric_page > $last_page ) {
					// subtract from the min page the number of pages we are over last in max
					$min_numeric_page -= ($max_numeric_page - $last_page);
				}
				else {
					// add to the max page the number of pages we are under in min
					$max_numeric_page += ( -$min_numeric_page + 1 );
				}
			}
			// Now, we'll ensure what we have is valid
			$max_numeric_page = min( $last_page, $max_numeric_page );
			$min_numeric_page = max( 1, $min_numeric_page );
			
			$this->linked_pages['First'] =  $this->generate_state_link( array( 'page' => 1 ) );
			$this->linked_pages['Previous'] =  $this->generate_state_link( array( 'page' => $this->page - 1 ) );
			for($i = $min_numeric_page; $i <= $max_numeric_page; $i++) {
				$this->linked_pages[$i] = $this->generate_state_link( array ( 'page' => $i ) );
			}
			$this->linked_pages['Next'] =  $this->generate_state_link( array( 'page' => $this->page + 1 ) );
			$this->linked_pages['Last'] =  $this->generate_state_link( array( 'page' => $last_page ) );
			
			
		}
		
		/**
		* Generate a link for a state of this application.
		*/
		protected function generate_state_link( $values ) {
			// Alter values if need be
			$values = $this->alter_values( $values );
			
			$type = isset($values['type']) ? $values['type'] : $this->type;
			$category = isset($values['category']) ? $values['category'] : $this->category;
			$sort = isset($values['sort']) ? $values['sort'] : $this->sort;
			$page = isset($values['page']) ? $values['page'] : $this->page;
			$search = isset($values['search']) ? $values['search'] : $this->search;
			$platform = isset($values['platform']) ? $values['platform'] : $this->platform;

			if( !$platform && $type == 'games' ) {
				$platform = 'any';
			}

			$link = '/' . $type;
			if( $platform ) {
				$link .= '/' . $platform;
				$category = $category ? $category : 'all';
			}
			if( $category ) {
				$link .= '/' . $category;
			}
			if( $search ) {
				$link .= '/' . $search;
			}
			$link .= '/' . $sort . '/' . $page;
			return $link;
		}
		
		/**
		* Alter values if necessary
		*/
		protected function alter_values( $values ) {
			$type = $values['type'] ? $values['type'] : $this->type;
			if( $this->sort == 'rating' && ( $type == 'apps' || $type == 'resources' ) ) {
				$values['sort'] = 'popularity';
			}
			return $values;
		}
	
	}
	
?>