<?php
	try {
		// THIS PAGE CAN ACT AS A WEB SERVICE
		
		if(!isset($routed)) {
			throw new Exception($direct_access_message);
		}
		
		$bad_params_message = "Unable to fetch items based on the url.";
		$dropdown_selected_in_list_class = "dropdown-selected-in-list";
		$paging_selected_page_class = "paging-selected-page";
		$hide_style = 'style="visibility:hidden"';
		$none_class = 'none-view';
		$items_per_page = 15;
		$display_page = '';
		// For paging
		$max = 2;
		$min = - $max;
		$max_page = -1;
		$game103_games = false;
		$distributable = false;
		$game103_extra_select = "";
		$interactions_verb = "";
		$interactions_verb_sing = "";
		// When everything is there, most conditionals invlove type hijacking for individual items
		// and changes for when category is non-existant/"" (category non-existant is also true for apps)
		// When type is apps or resources, sorts have to be restricted
		$everything_description = "A list including most of the items on Game 103.";
		$apps_description = "A listing of family-friendly mobile games and apps that Game 103 has developed for iOS and android.";
		$ajax_error = "Sorry, an error occured while trying to fetch more items. Please try again later.";
		
		// Ensure the Page is valid
		if(!is_numeric($page)) {
			throw new Exception($bad_params_message);
		}
		if(!isset($type)) {
			throw new Exception($bad_params_message);
		}
		if($type != 'games' && $type != 'videos' && $type != 'resources' && $type != 'everything' && $type != 'apps') {
			throw new Exception($bad_params_message);
		}
		$type_capital = ucfirst($type);
		
		// Connect to database
		if($type == 'everything') {
			$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***");
		}
		else {
			if($type != 'apps') {
				$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_$type");
			}
			else {
				$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
			}
		}
	
		if (mysqli_connect_errno()) {
			$mysqli->close();
			throw new Exception($mysql_message);
		}
	
		// Escape bad characters
		$category = $mysqli->real_escape_string($category);
		$search = $mysqli->real_escape_string($search);
		$search = str_replace('%20', ' ', $search);
		$search = str_replace('+', ' ', $search);
		$sort = $mysqli->real_escape_string($sort);
		$page = $mysqli->real_escape_string($page);
		
		// *************************
		// **** FOR CATEGORIES *****
		// *************************
		// Setup the array of categories
		// Keys are id, values are arrays with [0] = display name
		// and [1] = whether or not they are selected
		// and [2] = is their description
		// NOTE: it is important here that the ids match the database search term
		// save for - filling in for a space.
		// The javascript will use these IDs to perform web service requests to fetch new games
		if($type != 'everything' && $type != 'apps') {
			if($type == 'games') {
				$categories_arr = array(
					'all' => array('All', false, 'A collection of family-friendly, entertaining, and quality games that are playable directly in your browser.')
				);
			}
			else if($type == 'videos') {
				$categories_arr = array(
					'all' => array('All', false, 'A number of family-friendly, entertaining videos available to watch directly on Game 103.')
				);
			}
			else if($type == 'resources') {
				$categories_arr = array(
					'all' => array('All', false, 'A listing of links to resources that are useful for developers and used by Game 103.')
				);
			}
			$category_select_str = "SELECT name, url_name, description FROM categories;";
			$category_select_statement = $mysqli->prepare($category_select_str);
			$category_select_statement->execute();
			if(mysqli_stmt_error($category_select_statement) != "") {
				throw new Exception($mysql_message);
				$mysqli->close();
				exit();
			}
			$category_select_statement->bind_result($category_name, $category_url_name, $category_description);
			while($category_select_statement->fetch()) {
				$category_arr = array($category_name, false, $category_description);
				$categories_arr[$category_url_name] = $category_arr;
			}
			
			// Check to see if the category is there
			$category_match_ids_str = strtolower($category);
			if(isset($categories_arr[$category_match_ids_str])) {
				$categories_arr[$category_match_ids_str][1] = true;
				$dropdown_selected_str = $categories_arr[$category_match_ids_str][0];
			}
			else {
				throw new Exception($bad_params_message);
			}
			// End for categories
		}
		else {
			$categories_arr = array();
			$categories_match_id_str = "";
			$dropdown_selected_str = "";
		}
		
		// *************************
		// ***** FOR DOWNLOADS *****
		// *************************
		if($type == 'games') {
			$union_sql = "";
			$union_count_sql = "";
			$union_sum_sql = "";
			// Make use of an already case insensitive variable here
			if($category_match_ids_str == 'game103') {
				$game103_games = true;
				$game103_extra_select = ", YEAR(entries.creation_date), entries.added_date as added_date, entries.creation_date as creation_date";
				$union_sql = "
				UNION
				SELECT downloads.name as name, downloads.description, downloads.url_name, downloads.image_url, -1 as rating, -1 as plays, -1 as numeric_plays,
				YEAR(downloads.creation_date), downloads.added_date as added_date, downloads.creation_date as creation_date
				FROM downloads";
				$union_count_sql = "
				UNION
				SELECT count(1)
				FROM downloads";
				$union_sum_sql = "
				SELECT sum(total_count) FROM (";
			}
			else if($category == 'distributable') {
				$distributable = true;
			}
		}
		// End for downloads
		
		// *************************
		// ****** FOR TYPING *******
		// *************************
		// Set info used throughout the program on typing
		$games_class = "";
		$videos_class = "";
		$resources_class = "";
		$apps_class = "";
		$all_items_class = "";
		if($type == 'games') {
			$interactions_verb_sing = 'play';
			$interactions_verb = $interactions_verb_sing . "s";
			$games_class = $dropdown_selected_in_list_class;
		}
		else if($type == 'videos') {
			$interactions_verb_sing = 'view';
			$interactions_verb = $interactions_verb_sing . "s";
			$videos_class = $dropdown_selected_in_list_class;
		}
		else if($type == 'resources') {
			$resources_class = $dropdown_selected_in_list_class;
		}
		else if($type == 'apps') {
			$apps_class = $dropdown_selected_in_list_class;
		}
		else {
			$interactions_verb_sing = 'interaction';
			$interactions_verb = $interactions_verb_sing . "s";
			$all_items_class = $dropdown_selected_in_list_class;
		}
		
		// *************************
		// ****** FOR SORTING ******
		// *************************
		// Ensure the sort is valid and construct the sort section of the sql statement
		$alphabetically_class = "";
		$date_class = "";
		$rating_class = "";
		$popularity_class = "";
		$creation_class = "";
		if($sort == "alphabetical") {
			$sort_sql = 'name';
			$sort_selected_str = 'Sort alphabetically';
			$alphabetically_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "date") {
			if($type != 'resources' && $type != 'apps') {
				$sort_sql = 'added_date DESC, rating DESC';
			}
			else {
				$sort_sql = 'added_date DESC';
			}
			$sort_selected_str = 'Sort by date';
			$date_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "rating" && $type != 'resources' && $type != 'apps') {
			$sort_sql = "rating DESC, numeric_$interactions_verb DESC";
			$sort_selected_str = 'Sort by rating';
			$rating_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "popularity" && $type != 'resources' && $type != 'apps') {
			$sort_sql = "numeric_$interactions_verb DESC, rating DESC";
			$sort_selected_str = 'Sort by popularity';
			$popularity_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "creation" && $game103_games) {
			$sort_sql = 'creation_date DESC';
			$sort_selected_str = 'Sort by creation';
			$creation_class = $dropdown_selected_in_list_class;
		}
		else {
			throw new Exception($bad_params_message);
		}
		
		// *************************
		// ******* MAKE SQL ********
		// *************************
		// Construct the category section of the SQL statement
		// Category will be blank for types without categories (apps and all)
		if($category != "" && $category != 'all') {
			$category_sql = "WHERE categories.url_name = ?";
		}
		else {
			$category_sql = "";
		}
		
		// Construct the search and where section of the SQL statement
		// Used only for 'all' type
		$downloads_sql = "";
		$apps_sql = "";
		if($search != '') {
			$search_wildcards = '%' . $search . '%';
			$search_sql = "entries.name LIKE ?";
			if($category_sql == "") {
				$where_sql = "WHERE $search_sql";
			}
			else {
				$where_sql = $category_sql . " AND $search_sql";
			}
			if($game103_games) {
				$union_sql .= " WHERE downloads.name LIKE ?";
				$union_count_sql .= " WHERE downloads.name LIKE ?";
			}
			$downloads_sql = " WHERE downloads.name LIKE ?";
			$apps_sql = " WHERE apps.name LIKE ?";
		}
		else {
			$where_sql = $category_sql;
		}
		
		// This must be here
		if($game103_games) {
			$union_count_sql .= ') AS inner_count';
		}
		
		// Get the offset
		$offset = ($page - 1) * $items_per_page;
		
		// Create the SQL Statement
		if($type == 'games') {
			$select_str = "SELECT * FROM (
							SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.plays, 0) as plays, entries.plays as numeric_plays $game103_extra_select
							FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
							JOIN categories ON categories_entries.category_id = categories.id
							$where_sql
							GROUP BY entries.id
							$union_sql
							ORDER BY $sort_sql
							LIMIT $items_per_page
							OFFSET $offset) AS main
							LEFT JOIN (
							$union_sum_sql
							SELECT count(distinct entries.id) AS total_count
							FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
							JOIN categories ON categories_entries.category_id = categories.id
							$where_sql
							$union_count_sql
							) AS count
							ON 1=1";
		}
		else if($type == 'videos') {
			$select_str = "SELECT * FROM (
						SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.views, 0), entries.views as numeric_views
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						JOIN categories ON categories_entries.category_id = categories.id
						$where_sql
						GROUP BY entries.id
						ORDER BY $sort_sql
						LIMIT $items_per_page
						OFFSET $offset) AS main
						LEFT JOIN (
						SELECT count(distinct entries.id) AS total_count
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						JOIN categories ON categories_entries.category_id = categories.id
						$where_sql
						) AS count
						ON 1=1";
		}
		else if($type == 'resources') {
			$select_str = "SELECT * FROM (
						SELECT entries.name as name, entries.description, entries.url, entries.image_url
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						JOIN categories ON categories_entries.category_id = categories.id
						$where_sql
						GROUP BY entries.id
						ORDER BY $sort_sql
						LIMIT $items_per_page
						OFFSET $offset) AS main
						LEFT JOIN (
						SELECT count(distinct entries.id) AS total_count
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						JOIN categories ON categories_entries.category_id = categories.id
						$where_sql
						) AS count
						ON 1=1";
		}
		else if($type == 'apps') {
			$select_str = "SELECT * FROM(
			SELECT apps.name as name, apps.description, apps.url_name, apps.image_url, apps.store_url, apps.type 
			FROM apps $apps_sql
			ORDER BY $sort_sql
			LIMIT $items_per_page
			OFFSET $offset) AS main
			LEFT JOIN (select count(1) AS total_count
			FROM apps 
			$where_sql) AS count
			ON 1=1";
		}
		// type is all
		else {
			$select_str = "
				SELECT * FROM(
				SELECT name, description, url_name, image_url, rating, FORMAT(plays, 0), plays as numeric_interactions, added_date, -1 as store_url, -1 as type, 'game' FROM hallaby_games.entries $where_sql
				UNION
				SELECT name, description, url_name, image_url, -1 as rating, -1 as plays, -1 as numeric_interactions, added_date, -1 as store_url, -1 as type, 'download' FROM hallaby_games.downloads $downloads_sql
				UNION
				SELECT name, description, url_name, image_url, rating, FORMAT(views, 0), views as numeric_interactions, added_date, -1 as store_url, -1 as type, 'video' FROM hallaby_videos.entries $where_sql
				UNION
				SELECT name, description, url, image_url, -1 as rating, -1 as plays, -1 as numeric_interactions, added_date, -1 as store_url, -1 as type, 'resource' FROM hallaby_resources.entries $where_sql
				UNION
				SELECT name, description, url_name, image_url, -1 as rating, -1 as plays, -1 as numeric_interactions, added_date, store_url, type, 'app' FROM hallaby_games.apps $apps_sql
				ORDER BY $sort_sql
				LIMIT $items_per_page
				OFFSET $offset) AS main
				LEFT JOIN (
				SELECT sum(c) AS total_count
				FROM
				(SELECT count(1) as c FROM hallaby_games.entries $where_sql
				UNION
				SELECT count(1) as c FROM hallaby_games.downloads $downloads_sql
				UNION
				SELECT count(1) as c FROM hallaby_videos.entries $where_sql
				UNION
				SELECT count(1) as c FROM hallaby_resources.entries $where_sql
				UNION
				SELECT count(1) as c FROM hallaby_games.apps $apps_sql)
				AS inner_count_query
				) AS count
				ON 1=1";
		}
		
		$select_statement = $mysqli->prepare($select_str);
		if(!$where_sql == "") {
			// Only Category SQL is defined
			if($where_sql == $category_sql) {
				$select_statement->bind_param("ss", $category, $category);
			}
			// Only Search SQL is defined
			else if($category_sql == '') {
				if($type != 'everything') {
					$select_statement->bind_param("ss", $search_wildcards, $search_wildcards);
				}
				// When type is everything
				else {
					$select_statement->bind_param("ssssssssss", 
					$search_wildcards,
					$search_wildcards,
					$search_wildcards,
					$search_wildcards,
					$search_wildcards,
					$search_wildcards,
					$search_wildcards,
					$search_wildcards,
					$search_wildcards,
					$search_wildcards);
				}
			}
			// Category and search SQL is defined
			else {
				if(!$game103_games) {
					$select_statement->bind_param("ssss", $category, $search_wildcards, $category, $search_wildcards);
				}
				else {
					$select_statement->bind_param("ssssss", $category, $search_wildcards, $search_wildcards, $category, $search_wildcards, $search_wildcards);
				}
			}
		}
		
		// *************************
		// ******* RUN SQL *********
		// *************************
		// Execute the SQL Statement
		$select_statement->execute();
		if(mysqli_stmt_error($select_statement) != "") {
			throw new Exception($mysql_message);
			$mysqli->close();
			exit();
		}
		
		if($game103_games) {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $creation_date, $date_unused, $creation_unused, $total_count);
		}
		else if($type != 'resources' && $type != 'apps') {
			if($type != 'everything') {
				$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $total_count);
			}
			else {
				$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $interactions, $numeric_interactions, $added_date, $store_url, $app_type, $item_type, $total_count);
			}
		}
		else if($type == 'resources') {
			$select_statement->bind_result($name, $description, $url, $image_url, $total_count);
		}
		else {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $store_url, $app_type, $total_count);
		}
		
		// *************************
		// ******** RUN WS *********
		// *************************
		// If this is being used as Web Service, respond accordingly
		if(isset($ws)) {
			$items = array();
			while($select_statement->fetch()) {
				$item_object = array (
					"name" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"image_url" => $image_url
				);
				if($game103_games) {
					// Add the creation date to description
					$item_object["description"] .= " ($creation_date)";
				}
				if($type != 'resources' && $type != 'apps') {
					$item_object["url_name"] = $url_name;
					$item_object["rating"] = $rating;
					$item_object["interactions"] = $interactions;
				}
				else if($type == 'resources') {
					$item_object["url"] = $url;
				}
				// Type is apps
				else {
					$item_object["url_name"] = $url_name;
					$item_object["app_type"] = $app_type;
					$item_object["store_url"] = $store_url;
				}
				if($type == 'everything') {
					$item_object["type"] = $item_type;
					$item_object["app_type"] = $app_type;
					$item_object["store_url"] = $store_url;
				}
				$items[] = $item_object;
			}
			$select_statement->close();
			if(count($items) > 0) {
				$return_val = array(
					"status" => "success",
					"count" => $total_count,
					"items" => $items,
					"categories" => $categories_arr
				);
			}
			else {
				$return_val = array(
					"status" => "failure",
					"message" => $no_results_message,
					"categories" => $categories_arr
				);
			}
			echo json_encode($return_val);
			die;
		}
		
		// Continue here if this is not a web service
		$results = false;
		// Create the games
		// This same sort of thing is also done in javascript when an
		// ajax request comes in
		

		while($select_statement->fetch()) {
			$results = true;
			
			// Escape the quotes in the name of the entry
			$name = htmlentities($name, ENT_QUOTES);
			
			$app_store_logo = "";
			if($type == 'everything') {
				$cur_type = $item_type . "s";
				if($cur_type == 'games') {
					$cur_interactions_verb_sing = 'play';
				}
				// Must be a video
				else {
					$cur_interactions_verb_sing = 'view';
				}
				$cur_interactions_verb = $cur_interactions_verb_sing . "s";
			}
			else {
				$cur_type = $type;
				$cur_interactions_verb_sing = $interactions_verb_sing;
				$cur_interactions_verb = $interactions_verb;
			}
			// Resources do not have ratings, so skip this part for resources
			if($cur_type != 'resources' && $cur_type != 'apps') {
				// This is if it is a downloadable game
				if($rating < 0) {
					$rating_span = "";
					$interactions_span = "";
					$url_base = "download";
				}
				else {
					$rating_width = ($rating * 22) . 'px';
					if($cur_type == 'games') {
						$url_base = "game";
					}
					else if($cur_type == 'videos') {
						$url_base = "video";
					} 
					$rating_span = "<span class='stars entry-stars'><span style='width: $rating_width'></span></span>";
					if($interactions == 1) {
						$interactions_str = $cur_interactions_verb_sing;
					}
					else {
						$interactions_str = $cur_interactions_verb;
					}
					$interactions_span = "<span class = 'entry-plays'> $interactions $interactions_str</span>";
				}
				$url = "/$url_base/$url_name";
				$target = "_self";
			}
			else if ($cur_type == 'resources') {
				$interactions_span = "";
				$rating_span = "";
				$target = "_blank";
				if($type == 'everything') {
					$url = $url_name;
				}
			}
			// Type must be apps
			else {
				$interactions_span = "";
				$rating_span = "";
				if($url_name == NULL) {
					$url = $store_url;
					$target = "_blank";
				}
				else {
					$url = "/app/$url_name";
					$target = "_self";
				}
				if($app_type == "iOS") {
					$app_store_logo = "<span onclick='openURL(event, \"$store_url\")' style=\"position:absolute;top:0;right:0;display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/images/badges/en-us/badge_appstore-sm.svg) no-repeat;width:61px;height:15px;\"></span>
					</span>";
				}
				else if($app_type == "Android") {
					$app_store_logo = "<span onclick='openURL(event, \"$store_url\")' style=\"position:absolute;top:0;right:0;display:inline-block;overflow:hidden;width:80px;height:31px;\">
						<img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'
						style ='height:100%;width:100%'/>
					</span>";
				}
			}
			
			// This doesn't need to be done in the JS since it is done in the ws
			if($game103_games) {
				$description .= " ($creation_date)";
			}
			// This does though
			if($distributable) {
				$add_to_site = "<span class='distribute-button' onclick=\"addToSite(event, '$url_name')\">Add to your site!</span>";
			}
			else {
				$add_to_site = "";
			}
			
			$display_page .= "<a href=\"$url\" target='$target' class = 'entry-link'>
			<span class = 'entry-item'>
			<img alt = '$name' src = '$image_url'>
			<span class = 'entry-title'>$name</span>
			$rating_span";
			$display_page .= "<span class = 'entry-description'> $description</span>
			$app_store_logo
			$interactions_span
			$add_to_site
			</span>
			</a>";
		}
		
		$select_statement->close();
		
		$mysqli->close();
		
		if(!$results) {
			$display_page = $no_results_message;
		}
		
		// *************************
		// *** CREATE CATEGORIES ***
		// *************************
		// Create the list of categories
		$categories_options = "";
		foreach($categories_arr as $key => $value) {
			$display = $value[0];
			$category_decription = $value[2];
			if($value[1]) {
				$selected = $dropdown_selected_in_list_class;
			}
			else {
				$selected = '';
			}
			$categories_options .= "
			<li class='dropdown-item $selected' onclick='categoryAndFetch(\"$key\")'>
				<span id='$key' class='dropdown-item-text' description=\"$category_decription\">
					$display
				</span>
			</li>";
		}
		$categories_display_class = "";
		if($type == 'everything' || $type == 'apps') {
			$categories_display_class = $none_class;
		}
		
		// *************************
		// ***** CREATE PAGING *****
		// *************************
		// Create the paging controls
		if($results) {
			$paging_controls = "<div class='paging-controls'>";
		}
		else {
			$paging_controls = "<div class='paging-controls' style='display:none;'>";
			$total_count = 0;
		}
		$max_page = ceil($total_count / $items_per_page);
		$previous_paging_style = "";
		if($page == 1) {
			$previous_paging_style = $hide_style;
		}
		$prev_page = $page - 1;
		$paging_controls .= "
			<div id='backwards-paging' class='word-paging' $previous_paging_style>
				<button id='first-paging' onclick='pageAndFetch(1)'>First</button><button id = 'previous-paging' onclick='pageAndFetch($prev_page)'>Previous</button>
			</div>";
		
		$prepend_count = 1;
		$append_count = 1;
		$values = range($min, $max);
		$pages = array();
		// Since we go from lower to higher, we can just add
		// normal and prepend to the controls
		$normal_prepend_number_controls = "";
		// However, those that get appended, must go on last
		$append_number_controls = "";
		foreach($values as $value) {
			$page_num = $page + $value;
			// If the page number is greater than 0
			if($page_num > 0) {
				// And the page number is less than the max page
				// Add the page
				if($page_num <= $max_page) {
					$normal_prepend_number_controls .= create_page_num($page_num, $page);
				}
				// Otherwise, check if the next up previous value
				// is valid and add that
				else {
					$prev_page_num = $page + ($min - $prepend_count);
					if($prev_page_num > 0) {
						$normal_prepend_number_controls = create_page_num($prev_page_num, $page) . $normal_prepend_number_controls;
						$prepend_count ++;
					}
					else {
						$normal_prepend_number_controls .= create_page_num(null, $page);
					}
				}
			}
			// The page number is less than 0;
			else {
				// Check to see if the next up next value
				// is valid and add that
				$next_page_num = $page + $max + $append_count;
				if($next_page_num <= $max_page) {
					$append_number_controls .= create_page_num($next_page_num, $page);
					$append_count ++;
				}
				else {
					$normal_prepend_number_controls .= create_page_num(null, $page);
				}
			}
		}
		$paging_controls .= $normal_prepend_number_controls . $append_number_controls;
		
		$next_paging_style = "";
		if($page == $max_page) {
			$next_paging_style = $hide_style;
		}
		$next_page = $page + 1;
		
		$paging_controls .= "<div id='forwards-paging' class='word-paging' $next_paging_style>
				<button id='next-paging' onclick='pageAndFetch($next_page)'>Next</button><button id='last-paging' onclick='pageAndFetch($max_page)'>Last</button>
			</div>";
		$paging_controls .= "</div>";
		
		// *************************
		// ****** CREATE OTHER *****
		// *************************
		// Create everything else needed on the page and put it together.
		
		if($game103_games) {
			$creation_hidden_class = "";
		}
		else {
			$creation_hidden_class = $none_class;
		}
		
		if($category != "all" && $category != '') {
			$category_for_title = $categories_arr[$category][0] . " ";
		}
		else {
			$category_for_title = "";
		}
		$display_title = $category_for_title . $type_capital;
		
		$hidden_sorts_class = "";
		if($type == 'resources' || $type == 'apps') {
			$hidden_sorts_class = $none_class;
		}
		
		$display_page = "
		<div class='box-content'>
			<div class='box-content-title'>
				<div class='search'>
					<div class='dropdown'>
						<div class='dropdown-selected' onclick='openDropDown(event, \"sort\")'>
							<span class='dropdown-selected-text'>
								<span class='dropdown-selected-text-no-arrow' id='sort-dropdown-selected-text-no-arrow'>
									$sort_selected_str
								</span>
								<span id='sort-dropdown-arrow' class='dropdown-arrow'>
									&#9660;
								</span>
							</span>
						</div>
						<ul class='dropdown-menu' id='sort-dropdown-menu'>
							<li class='dropdown-item $popularity_class $hidden_sorts_class' onclick='sortAndFetch(\"popularity\")'>
								<span id='popularity' class='dropdown-item-text'>
									Sort by popularity
								</span>
							</li>
							<li class='dropdown-item $rating_class $hidden_sorts_class' onclick='sortAndFetch(\"rating\")'>
								<span id='rating' class='dropdown-item-text'>
									Sort by rating
								</span>
							</li>
							<li class='dropdown-item $creation_class $creation_hidden_class' onclick='sortAndFetch(\"creation\")'>
								<span id='creation' class='dropdown-item-text'>
									Sort by creation
								</span>
							</li>
							<li class='dropdown-item $date_class' onclick='sortAndFetch(\"date\")'>
								<span id='date' class='dropdown-item-text'>
									Sort by date
								</span>
							</li>
							<li class='dropdown-item $alphabetically_class' onclick='sortAndFetch(\"alphabetical\")'>
								<span id='alphabetical' class='dropdown-item-text'>
									Sort alphabetically
								</span>
							</li>
						</ul>
					</div>
					<div class='dropdown'>
						<div class='dropdown-selected' onclick='openDropDown(event, \"type\")'>
							<span class='dropdown-selected-text'>
								<span class='dropdown-selected-text-no-arrow' id='type-dropdown-selected-text-no-arrow'>
									$type_capital
								</span>
								<span id='type-dropdown-arrow' class='dropdown-arrow'>
									&#9660;
								</span>
							</span>
						</div>
						<ul class='dropdown-menu' id='type-dropdown-menu'>
							<li class='dropdown-item $all_items_class' onclick='typeAndFetch(\"everything\")'>
								<span id='everything' class='dropdown-item-text'>
									Everything
								</span>
							</li>
							<li class='dropdown-item $games_class' onclick='typeAndFetch(\"games\")'>
								<span id='games' class='dropdown-item-text'>
									Games
								</span>
							</li>
							<li class='dropdown-item $videos_class' onclick='typeAndFetch(\"videos\")'>
								<span id='videos' class='dropdown-item-text'>
									Videos
								</span>
							</li>
							<li class='dropdown-item $resources_class' onclick='typeAndFetch(\"resources\")'>
								<span id='resources' class='dropdown-item-text'>
									Resources
								</span>
							</li>
							<li class='dropdown-item $apps_class' onclick='typeAndFetch(\"apps\")'>
								<span id='apps' class='dropdown-item-text'>
									Apps
								</span>
							</li>
						</ul>
					</div>
					<div class='dropdown $categories_display_class'>
						<div class='dropdown-selected' onclick='openDropDown(event, \"categories\")'>
							<span class='dropdown-selected-text'>
								<span class='dropdown-selected-text-no-arrow' id='categories-dropdown-selected-text-no-arrow'>
									$dropdown_selected_str
								</span>
								<span id='categories-dropdown-arrow' class='dropdown-arrow'>
									&#9660;
								</span>
							</span>
						</div>
						<ul class='dropdown-menu' id='categories-dropdown-menu'>
							$categories_options
						</ul>
					</div>
					<input type='text' value='$search' placeholder='Search' id='search' oninput='searchAndFetch()' autocomplete='off' />
				</div>
			</div>
			<div class='box-content-container'>
				<div id='$type-entries'>
					$display_page
				</div>
			</div>
			$paging_controls
		</div>
		";
		
		if($type != 'everything' && $type != 'apps') {
			$display_description = $categories_arr[$category][2];
		}
		else if($type == 'everything'){
			$display_description = $everything_description;
		}
		// type is apps
		else {
			$display_description = $apps_description;
		}
		$display_javascript = "
		var type = '$type';
		var category = '$category';
		// In JS, the category uses '-' as a space (for ids)
		category = category.replace(/\s/g, '-').toLowerCase();
		// URL is plus (for display)
		// PHP is a space (for the sql query)
		var sort = '$sort';
		var search = '$search';
		var page = '$page';
		var maxPage = '$max_page';
		var fetchTimeout;
		// Replace the state with the above values
		history.replaceState({
			search: search,
			category: category,
			page: page,
			sort: sort,
			type: type
		}, '', window.location.href)
		window.onload = function() {
			// Close drop down on click
			document.onclick = function() { 
				closeDropDown('categories');
				closeDropDown('sort');
				closeDropDown('type');
			}
		}
		// On popstate, load the state values and do a fetch
		window.onpopstate = function(event) {
            if(event.state) {
                document.getElementById('search').value = event.state.search;
				var resetCategories = false;
				if(type != event.state.type || document.getElementsByClassName('box-content-container')[0].textContent.trim() == '$ajax_error') {
					resetCategories = true;
				}
                changeSearch();
				// If the category is being reset, this will be taken care of
				// once new categories load
				if(!resetCategories && event.state.category != '') {
					changeCategory(event.state.category);
				}
				// if the popped category is nothing, we don't want any visual indication
				// but, we do want to set category to blank
				else if(event.state.category == '') {
					category = '';
				}
                changePage(event.state.page);
                changeSort(event.state.sort);
				if(resetCategories) {
					changeType(event.state.type);
				}
                // No need to replace the url, since we are already there!
                // The point of this fetch is to make the data match the url
                fetch(false, resetCategories, event.state.category);
            }
		}
		// Close the dropdown
		function closeDropDown(dropDown) {
			document.getElementById(dropDown + '-dropdown-arrow').innerHTML = '&#9660;';
			document.getElementById(dropDown + '-dropdown-menu').style.display = 'none';
		}
		// Open the dropdown
		function openDropDown(event, dropDown) {
			// Make sure the dropdown is closed
			if(document.getElementById(dropDown + '-dropdown-menu').style.display != 'inline-block') {
				document.getElementById(dropDown + '-dropdown-arrow').innerHTML = '&#9650;';
				document.getElementById(dropDown + '-dropdown-menu').style.display = 'inline-block';
				document.getElementById('site-search-results-dropdown').style.display = 'none';
				event.stopPropagation();
				if(dropDown == 'sort') {
					closeDropDown('categories');
					closeDropDown('type');
				}
				else if(dropDown == 'type') {
					closeDropDown('categories');
					closeDropDown('sort');
				}
				else {
					closeDropDown('type');
					closeDropDown('sort');
				}
			}
			// Otherwise, the dropdown will close
		}
		// Add to site
		function addToSite(event, urlName) {
			event.preventDefault();
			window.location.href = '/game103games/distribute/' + urlName + '.zip';
			document.getElementById('site-search-results-dropdown').style.display = 'none';
			event.stopPropagation();
		}
		// Fetch another page
		// changeState is a boolean that determines whether or not to change the state
		// Note, the new category doesn't become the category. 
		// It simply highlights the category when new categories are loaded
		function fetch(changeState, resetCategories, newCategory) {
			var actualSearch = search.replace(/\s/g, '+');
			var actualCategory = category.replace(/-/g, '+');
			var paramSearch = '';
			if(actualSearch) {
				paramSearch = '/' + actualSearch;
			}
			if(actualCategory) {
				actualCategory = '/' + actualCategory;
			}
			var xhttp = new XMLHttpRequest();
			var errorText = '$ajax_error';
			var items = document.getElementById(type + '-entries');
			var pagingControls = document.getElementsByClassName('paging-controls')[0];
			items.style.opacity = 0.5;
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4) {
					if(changeState) {
						history.pushState({
							search: search,
							category: category,
							page: page,
							sort: sort,
							type: type
						}, '', '/' + type + actualCategory + paramSearch + '/' + sort + '/' + page);
						// Update the title
						var keepTitle = document.title.split(' - ')[1];
						
						var categoryForTitle = '';
						if(category != 'all' && category != '') {
							categoryForTitle = document.getElementById(category).innerHTML + ' ' + type.charAt(0).toUpperCase() + type.slice(1);
						}
						else {
							categoryForTitle = type.charAt(0).toUpperCase() + type.slice(1);
						}
						document.title = categoryForTitle + ' - ' + keepTitle;
						
						// Update the description
						updateMeta();
					}
					if(xhttp.status == 200) {
						try {
							items.style.opacity = 1;
							var object = JSON.parse(xhttp.responseText);
							var status = object['status'];
							if(resetCategories) {
								updateCategories(object['categories'], newCategory);
								updateMeta();
							}
							// If successful, this should always be shown (unless type is everything)
							if(type != 'everything' && type != 'apps') {
								document.getElementById('categories-dropdown-menu').parentNode.classList.remove('$none_class');
							}
							if(status != 'success') {
								items.innerHTML = object['message'];
								pagingControls.style.display = 'none';
							}
							else {
								var itemsHTML = '';
								var itemsArr = object['items'];
								for(var i = 0; i < itemsArr.length; i++) {
									var ratingWidth;
									var ratingSpan = '';
									var interactionsSpan = '';
									var urlBase;
									var curType;
									var appStoreLogo = '';
									var itemURL;
									var target;
									
									if(type == 'everything') {
										curType = itemsArr[i]['type'] + 's';
									}
									else {
										curType = type;
									}
									if(curType != 'resources' && curType != 'apps') {
										if(itemsArr[i]['rating'] < 0) {
											ratingSpan = \"\";
											interactionsSpan = \"\";
											urlBase = 'download';
										}
										else {
											ratingWidth = itemsArr[i]['rating'] * 22 + 'px';
											ratingSpan = \"<span class='stars entry-stars'><span style='width: \" + ratingWidth + \"'></span></span> \";
											var interactionsVerb;
											if(curType == 'games') {
												urlBase = 'game';
												interactionsVerb = 'play';
											}
											else {
												urlBase = 'video';
												interactionsVerb = 'view';
											}
											var interactionsStr;
											if(itemsArr[i]['interactions'] == 1) {
												interactionsStr = interactionsVerb;
											}
											else {
												interactionsStr = interactionsVerb + 's';
											}
											interactionsSpan = \"<span class = 'entry-plays'> \" + itemsArr[i]['interactions'] + ' ' + interactionsStr + \"</span>\";
											itemURL = '/' + urlBase + '/' +  itemsArr[i]['url_name'];
											target = '_self';
										}
									}
									else if(curType == 'resources') {
										if(type != 'everything') {
											itemURL = itemsArr[i]['url'];
										}
										else {
											itemURL = itemsArr[i]['url_name'];
										}
										target= '_blank';
									}
									// Type must be apps
									else {
										if(!itemsArr[i]['url_name']) {
											itemURL = itemsArr[i]['store_url'];
											target = '_blank';
										}
										else {
											itemURL = '/app/' + itemsArr[i]['url_name'];
											target = '_self';
										}
										if(itemsArr[i]['app_type'] == 'iOS') {
											appStoreLogo = `<span onclick='openURL(event, \"` + itemsArr[i]['store_url'] + `\")' 
											style=\"position:absolute;top:0;right:0;display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/images/badges/en-us/badge_appstore-sm.svg) 
											no-repeat;width:61px;height:15px;\"></span>
											</span>`;
										}
										else if(itemsArr[i]['app_type'] == 'Android') {
											appStoreLogo = `<span onclick='openURL(event, \"` + itemsArr[i]['store_url'] + `\")' style=\"position:absolute;top:0;right:0;display:inline-block;overflow:hidden;width:80px;height:31px;\">
											<img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'
											style ='height:100%;width:100%'/>
											</span>`;
										}
									}
									itemsHTML += \"<a href='\" + itemURL + \"' target='\" + target + \"'class = 'entry-link'> \"
									+ \"<span class = 'entry-item'> \"
									+ \"<img alt = '\" + itemsArr[i]['name'] + \"' src = '\" + itemsArr[i]['image_url'] + \"'> \"
									+ \"<span class = 'entry-title'>\" + itemsArr[i]['name'] + \"</span> \"
									+ ratingSpan
									+ \"<span class = 'entry-description'> \" + itemsArr[i]['description'] + \"</span> \"
									+ appStoreLogo
									+ interactionsSpan;
									if(category == 'distributable') {
										itemsHTML += \"<span class='distribute-button' onclick='addToSite(event, &quot;\" + itemsArr[i]['url_name'] + \"&quot;)'>Add to your site!</span>\";
									}
									itemsHTML += \"</span></a>\";
								}
								generatePagingControls(object['count']);
								// Show everything
								pagingControls.style.display = 'inline-block';
								items.innerHTML = itemsHTML;
								items.style.visibility = 'visible';
							}
						}
						catch(e) {
							items.innerHTML = errorText;
							pagingControls.style.display = 'none';
							console.log(e);
						}
					}
					else {
						items.innerHTML = errorText;
						pagingControls.style.display = 'none';
					}
				}
			};
			xhttp.open('GET', '/' + type + '/ws' + actualCategory + '/' + actualSearch + '/' + sort + '/' + page, true);
			xhttp.send();
		}
		// Update the meta description
		function updateMeta() {
			if(type != 'everything' && type != 'apps') {
				var meta = document.getElementsByTagName('meta');
				var categoryElement = document.getElementById(category);
				if(categoryElement) {
					for (var i=0; i<meta.length; i++) {
						if (meta[i].name.toLowerCase()=='description') {
							meta[i].content = categoryElement.getAttribute('description');
						}
					}
				}
			}
			else {
				var meta = document.getElementsByTagName('meta');
				for (var i=0; i<meta.length; i++) {
						if (meta[i].name.toLowerCase()=='description') {
							if(type == 'everything') {
								meta[i].content = '$everything_description';
							}
							// Type must be apps
							else {
								meta[i].content = '$apps_description';
							}
						}
				}
			}
		}
		// Update the list of categories
		function updateCategories(categoriesObject, selectedCategory) {
			var categoriesMenu = document.getElementById('categories-dropdown-menu');
			var newCategoriesHTML = '';
			var i = 0;
			for (var curCategory in categoriesObject) {
				if (categoriesObject.hasOwnProperty(curCategory)) {
					var selected = '';
					if(curCategory == selectedCategory) {
						selected = 'dropdown-selected-in-list';
						document.getElementById('categories-dropdown-selected-text-no-arrow').innerHTML = categoriesObject[curCategory][0];
					}
					newCategoriesHTML += \"<li class='dropdown-item \" + selected + \"' onclick='categoryAndFetch(\" + '\"' + curCategory + '\"' + \")'>\"
										+ \"<span id='\" + curCategory + \"' class='dropdown-item-text' description='\" + categoriesObject[curCategory][2] + \"'>\"
										+ categoriesObject[curCategory][0]
										+ \"</span>\"
										+ \"</li>\";
					i++;
				}
			}
			categoriesMenu.innerHTML = newCategoriesHTML;
			
		}
		// The search has changed
		// Do a search and fetch
		function searchAndFetch() {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changeSearch();
			// If the categories dropdown menu is invisible, there was either an error fetching categories
			// or we are waiting to load categories. Either way, we want to load new categories.
			var resetCategories = false;
			if(document.getElementById('categories-dropdown-menu').parentNode.classList.contains('$none_class')) {
				resetCategories = true;
			}
			fetchTimeout = setTimeout(function() {fetch(true, resetCategories, 'all')}, 500);
			page = 1;
		}
		// The category has changed
		// Do a fetch
		function categoryAndFetch(newCategory) {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changeCategory(newCategory);
			var resetCategories = false;
			if(document.getElementById('categories-dropdown-menu').parentNode.classList.contains('$none_class')) {
				resetCategories = true;
			}
			fetchTimeout = setTimeout(function() {fetch(true, resetCategories, 'all')}, 500);
			page = 1;
		}
		// The sort has changed
		// Do a fetch
		function sortAndFetch(newSort) {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changeSort(newSort);
			var resetCategories = false;
			if(document.getElementById('categories-dropdown-menu').parentNode.classList.contains('$none_class')) {
				resetCategories = true;
			}
			fetchTimeout = setTimeout(function() {fetch(true, resetCategories, 'all')}, 500);
		}
		// The page has changed
		// Do a fetch
		function pageAndFetch(newPage) {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changePage(newPage);
			var resetCategories = false;
			if(document.getElementById('categories-dropdown-menu').parentNode.classList.contains('$none_class')) {
				resetCategories = true;
			}
			fetchTimeout = setTimeout(function() {fetch(true, resetCategories, 'all')}, 500);
		}
		// The type has changed
		// Do a fetch
		function typeAndFetch(newType) {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changeType(newType);
			fetchTimeout = setTimeout(function() {fetch(true, true, 'all')}, 500);
			page = 1;
		}
		// Change the search
		function changeSearch() {
			search = document.getElementById('search').value;
		}
		// Change the category
		function changeCategory(newCategory) {
			document.getElementById(category).parentNode.classList.remove('$dropdown_selected_in_list_class');
			category = newCategory;
			document.getElementById(category).parentNode.classList.add('$dropdown_selected_in_list_class');
			document.getElementById('categories-dropdown-selected-text-no-arrow').innerHTML = 
			document.getElementById(category).innerHTML;
			if(newCategory == 'game103' && type == 'games') {
				document.getElementById('creation').parentNode.classList.remove('$none_class');
			}
			else {
				document.getElementById('creation').parentNode.classList.add('$none_class');
				if(sort == 'creation') {
					changeSort('date');
				}
			}
		}
		// Change the sort
		function changeSort(newSort) {
			document.getElementById(sort).parentNode.classList.remove('$dropdown_selected_in_list_class');
			sort = newSort;
			document.getElementById(sort).parentNode.classList.add('$dropdown_selected_in_list_class');
			document.getElementById('sort-dropdown-selected-text-no-arrow').innerHTML = 
			document.getElementById(sort).innerHTML;
		}
		// Change the type
		function changeType(newType) {
			// Hide the categories since new ones will be loaded
			document.getElementById('categories-dropdown-menu').parentNode.classList.add('$none_class');
			document.getElementById(type).parentNode.classList.remove('$dropdown_selected_in_list_class');
			document.getElementById(type + '-entries').id = newType + '-entries';
			// Switch the category (display will be taken care of on reload)
			category = 'all';
			if(sort == 'creation') {
				changeSort('date');
			}
			type = newType;
			if(type == 'everything' || type == 'apps') {
				category = '';
			}
			document.getElementById(type).parentNode.classList.add('$dropdown_selected_in_list_class');
			document.getElementById('type-dropdown-selected-text-no-arrow').innerHTML = 
			document.getElementById(type).innerHTML;
			if(newType == 'resources' || newType == 'apps') {
				document.getElementById('rating').parentNode.classList.add('$none_class');
				document.getElementById('popularity').parentNode.classList.add('$none_class');
				if(sort == 'rating' || sort == 'popularity') {
					changeSort('date');
				}
			}
			else {
				document.getElementById('rating').parentNode.classList.remove('$none_class');
				document.getElementById('popularity').parentNode.classList.remove('$none_class');
			}
			document.getElementById('creation').parentNode.classList.add('$none_class');
		}
		// Change the page
		function changePage(newPage) {
			var pageElement = document.getElementById('page-num-' + page);
			if(pageElement) {
				pageElement.classList.remove('$paging_selected_page_class');
			}
			page = newPage;
			pageElement = document.getElementById('page-num-' + page);
			if(pageElement) {
				document.getElementById('page-num-' + page).classList.add('$paging_selected_page_class');
			}
		}
		// Update the paging controls
		// (AJAX possible and no js on load)
		function generatePagingControls(count) {
			var pagingControls = '';
			var myPage = parseInt(page);
			if(count > 0) {
				var maxPage = Math.ceil(count / $items_per_page);
				var previousPagingStyle = '';
				if(myPage == 1) {
					document.getElementById('backwards-paging').style.visibility = 'hidden';
				}
				else {
					document.getElementById('backwards-paging').style.visibility = 'visible';
				}
				var prevPage = myPage - 1;
				document.getElementById('first-paging').onclick = function() { pageAndFetch(1); };
				document.getElementById('previous-paging').onclick = function() { pageAndFetch(prevPage) };
				
				var prependCount = 1;
				var appendCount = 1;
				var pages = [];
				var afterPages = [];
				for(var value = $min; value < $max + 1; value ++) {
					var pageNum = myPage + value;
					// If the page number is greater than 0
					if(pageNum > 0) {
						// And the page number is less than the max page
						// Add the page
						if(pageNum <= maxPage) {
							pages.push(pageNum);
						}
						// Otherwise, check if the next up previous value
						// is valid and add that
						else {
							var prevPageNum = myPage + ($min - prependCount);
							if(prevPageNum > 0) {
								pages.unshift(prevPageNum);
								prependCount ++;
							}
							else {
								pages.push(null);
							}
						}
					}
					// The page number is less than 0;
					else {
						// Check to see if the next up next value
						// is valid and add that
						var nextPageNum = myPage + $max + appendCount;
						if(nextPageNum <= maxPage) {
							afterPages.push(nextPageNum);
							appendCount ++;
						}
						else {
							pages.push(null);
						}
					}
				}
				pages = pages.concat(afterPages);
				
				var pageNumElements = document.getElementsByClassName('number-paging');
				var selectedElement;
				var focusOnSelectedElement = false;
				for(var i=0; i<pages.length; i++) {
					if(pageNumElements[i] === document.activeElement) {
						focusOnSelectedElement = true;
					}
					if(!pages[i]) {
						pageNumElements[i].removeAttribute('id');
						pageNumElements[i].style.display = 'none';
						pageNumElements[i].innerHTML = '-';
					}
					else {
						pageNumElements[i].setAttribute('id', 'page-num-' + pages[i]);
						pageNumElements[i].style.display = 'inline';
						pageNumElements[i].innerHTML = pages[i];
						pageNumElements[i].onclick = function() { pageAndFetch(this.innerHTML); };
						if(pages[i] == myPage) {
							pageNumElements[i].classList.add('$paging_selected_page_class');
							selectedElement = pageNumElements[i];
						}
						else {
							pageNumElements[i].classList.remove('$paging_selected_page_class');
						}
					}
				}
				if(focusOnSelectedElement) {
					selectedElement.focus();
				}
				
				var nextPagingStyle = '';
				if(page == maxPage) {
					document.getElementById('forwards-paging').style.visibility = 'hidden';
				}
				else {
					document.getElementById('forwards-paging').style.visibility = 'visible';
				}
				var nextPage = myPage + 1;
				document.getElementById('next-paging').onclick = function() { pageAndFetch(nextPage) };
				document.getElementById('last-paging').onclick = function() { pageAndFetch(maxPage) };
			}
		}
		// Open URL
		function openURL(event, url) {
			event.preventDefault();
			window.open(url, '_blank');
			document.getElementById('site-search-results-dropdown').style.display = 'none';
			event.stopPropagation();
		}
		";
	}
	catch(Exception $e) {
		if(isset($ws)) {
			$return_val = array(
				"status" => "failure",
				"message" => $e->getMessage()
			);
			echo json_encode($return_val);
			die;
		}
		$display_description = "An error has occured.";
		$display_title = 'Error';
		$display_javascript = "";
		$display_page = $e->getMessage();
	}

	// Create a numeric paging control
	function create_page_num($page_num, $page) {
		$paging_class = "";
		if($page_num == null) {
			$id = "";
			$onclick = "";
			$page_num = "-";
			$style = "style='display: none'";
		}
		else {
			$id = "id='page-num-$page_num'";
			$onclick = "onclick='pageAndFetch($page_num)'";
			if($page_num == $page) {
				$paging_class = "paging-selected-page";
			}
			$style = "";
		}
		return "<button $id $onclick class='number-paging $paging_class' $style>$page_num</button>";
	}
	
?>