<?php
	try {
		// THIS PAGE CAN ACT AS A WEB SERVICE
		
		if(!isset($routed)) {
			throw new Exception($direct_access_message);
		}
		
		$bad_params_message = "Unable to fetch games based on the url.";
		$dropdown_selected_in_list_class = "dropdown-selected-in-list";
		$paging_selected_page_class = "paging-selected-page";
		$hide_style = 'style="visibility:hidden"';
		$none_style = 'style="display:none"';
		$games_per_page = 15;
		$display_page = '';
		// For paging
		$max = 2;
		$min = - $max;
		$max_page = -1;
		$game103 = false;
		$distributable = false;
		$game103_extra_select = "";
		
		// Ensure the Page is valid
		if(!is_numeric($page)) {
			throw new Exception($bad_params_message);
		}
		
		// Connect to database
		$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
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
		$categories_arr = array(
			'all' => array('All Games', false, 'A collection of family-friendly, entertaining, and quality games that are playable directly in your browser.')
		);
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
		
		// *************************
		// ***** FOR DOWNLOADS *****
		// *************************
		$union_sql = "";
		$union_count_sql = "";
		$union_sum_sql = "";
		// Make use of an already case insensitive variable here
		if($category_match_ids_str == 'game103') {
			$game103 = true;
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
		// End for downloads
		
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
			$sort_sql = 'added_date DESC, rating DESC';
			$sort_selected_str = 'Sort by date';
			$date_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "rating") {
			$sort_sql = 'rating DESC, numeric_plays DESC';
			$sort_selected_str = 'Sort by rating';
			$rating_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "popularity") {
			$sort_sql = 'numeric_plays DESC, rating DESC';
			$sort_selected_str = 'Sort by popularity';
			$popularity_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "creation" && $game103) {
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
		if($category != "" && $category != 'all') {
			$category_sql = "WHERE categories.url_name = ?";
		}
		else {
			$category_sql = "";
		}
		
		// Construct the search and where section of the SQL statement
		if($search != '') {
			$search_wildcards = '%' . $search . '%';
			$search_sql = "entries.name LIKE ?";
			if($category_sql == "") {
				$where_sql = "WHERE $search_sql";
			}
			else {
				$where_sql = $category_sql . " AND $search_sql";
			}
			if($game103) {
				$union_sql .= " WHERE downloads.name LIKE ?";
				$union_count_sql .= " WHERE downloads.name LIKE ?";
			}
		}
		else {
			$where_sql = $category_sql;
		}
		
		// This must be here
		if($game103) {
			$union_count_sql .= ') AS inner_count';
		}
		
		// Get the offset
		$offset = ($page - 1) * $games_per_page;
		
		// Create the SQL Statement
		$select_str = "SELECT * FROM (
						SELECT entries.name as name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.plays, 0) as plays, entries.plays as numeric_plays $game103_extra_select
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						JOIN categories ON categories_entries.category_id = categories.id
						$where_sql
						GROUP BY entries.id
						$union_sql
						ORDER BY $sort_sql
						LIMIT $games_per_page
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
				
		$select_statement = $mysqli->prepare($select_str);
		if(!$where_sql == "") {
			// Only Category SQL is defined
			if($where_sql == $category_sql) {
				$select_statement->bind_param("ss", $category, $category);
			}
			// Only Search SQL is defined
			else if($category_sql == '') {
				$select_statement->bind_param("ss", $search_wildcards, $search_wildcards);
			}
			// Category and search SQL is defined
			else {
				if(!$game103) {
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
		if($game103) {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $plays, $numeric_plays, $creation_date, $date_unused, $creation_unused, $total_count);
		}
		else {
			$select_statement->bind_result($name, $description, $url_name, $image_url, $rating, $plays, $numeric_plays, $total_count);
		}
		
		// *************************
		// ******** RUN WS *********
		// *************************
		// If this is being used as Web Service, respond accordingly
		if(isset($ws)) {
			$games = array();
			while($select_statement->fetch()) {
				$game_object = array (
					"name" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"url_name" => $url_name,
					"image_url" => $image_url,
					"rating" => $rating,
					"plays" => $plays,
				);
				if($game103) {
					// Add the creation date to description
					$game_object["description"] .= " ($creation_date)";
				}
				$games[] = $game_object;
			}
			$select_statement->close();
			if(count($games) > 0) {
				$return_val = array(
					"status" => "success",
					"count" => $total_count,
					"games" => $games
				);
			}
			else {
				$return_val = array(
					"status" => "failure",
					"message" => $no_results_message
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
			
			// This is if it is a downloadable game
			if($rating < 0) {
				$rating_span = "";
				$plays_span = "";
				$url_base = "download";
			}
			else {
				$rating_width = ($rating * 22) . 'px';
				$url_base = "game";
				$rating_span = "<span class='stars entry-stars'><span style='width: $rating_width'></span></span>";
				if($plays == 1) {
					$plays_str = 'play';
				}
				else {
					$plays_str = 'plays';
				}
				$plays_span = "<span class = 'entry-plays'> $plays $plays_str</span>";
			}
			
			// This doesn't need to be done in the JS since it is done in the ws
			if($game103) {
				$description .= " ($creation_date)";
			}
			// This does though
			if($distributable) {
				$add_to_site = "<span class='distribute-button' onclick=\"addToSite(event, '$url_name')\">Add to your site!</span>";
			}
			else {
				$add_to_site = "";
			}
			
			$display_page .= "<a href=\"/$url_base/$url_name\" class = 'entry-link'>
			<span class = 'entry-item'>
			<img alt = '$name' src = '$image_url'>
			<span class = 'entry-title'>$name</span>
			$rating_span";
			$display_page .= "<span class = 'entry-description'> $description</span>
			$plays_span
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
		$max_page = ceil($total_count / $games_per_page);
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
		
		if($game103) {
			$creation_style = "";
		}
		else {
			$creation_style = $none_style;
		}
		
		if($category != "all") {
			$category_for_title = $categories_arr[$category][0] . " ";
		}
		else {
			$category_for_title = "";
		}
		$display_title = $category_for_title . "Games";
		
		$display_page = "
		<div class='box-content'>
			<div class='box-content-title'>$display_title</div>
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
						<li class='dropdown-item $popularity_class' onclick='sortAndFetch(\"popularity\")'>
							<span id='popularity' class='dropdown-item-text'>
								Sort by popularity
							</span>
						</li>
						<li class='dropdown-item $rating_class' onclick='sortAndFetch(\"rating\")'>
							<span id='rating' class='dropdown-item-text'>
								Sort by rating
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
						<li $creation_style class='dropdown-item $creation_class' onclick='sortAndFetch(\"creation\")'>
							<span id='creation' class='dropdown-item-text'>
								Sort by creation
							</span>
						</li>
					</ul>
				</div>
				<div class='dropdown'>
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
				<div class='loading-wrapper'>
					<span id='loading'>Loading...</span>
				</div>
			</div>
			<div class='box-content-container'>
				<div id='games-entries'>
					$display_page
				</div>
			</div>
			$paging_controls
		</div>
		";
		
		$display_description = $categories_arr[$category][2];
		$display_javascript = "
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
			sort: sort
		}, '', window.location.href)
		window.onload = function() {
			// Close drop down on click
			document.onclick = function() { 
				closeDropDown('categories');
				closeDropDown('sort');
			}
		}
		// On popstate, load the state values and do a fetch
		window.onpopstate = function(event) {
            if(event.state) {
                document.getElementById('search').value = event.state.search;
                changeSearch();
                changeCategory(event.state.category);
                changePage(event.state.page);
                changeSort(event.state.sort);
                // No need to replace the url, since we are already there!
                // The point of this fetch is to make the data match the url
                fetch(false);
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
				event.stopPropagation();
				if(dropDown == 'sort') {
					closeDropDown('categories');
				}
				else {
					closeDropDown('sort');
				}
			}
			// Otherwise, the dropdown will close
		}
		// Add to site
		function addToSite(event, urlName) {
			event.preventDefault();
			window.location.href = '/game103games/distribute/' + urlName + '.zip';
			event.stopPropagation();
		}
		// Fetch another page
		// changeState is a boolean that determines whether or not to change the state
		function fetch(changeState) {
			var actualSearch = search.replace(/\s/g, '+');
			var actualCategory = category.replace(/-/g, '+');
			var paramSearch = '';
			if(actualSearch) {
				paramSearch = '/' + actualSearch;
			}
			var xhttp = new XMLHttpRequest();
			var errorText = 'Sorry, an error occured while trying to fetch more games. Please try again later.';
			var games = document.getElementById('games-entries');
			var pagingControls = document.getElementsByClassName('paging-controls')[0];
			var loading = document.getElementById('loading');
			games.style.opacity = 0.5;
			loading.style.visibility = 'visible';
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4) {
					if(changeState) {
						history.pushState({
							search: search,
							category: category,
							page: page,
							sort: sort
						}, '', '/games/' + actualCategory + paramSearch + '/' + sort + '/' + page);
						// Update the title
						var keepTitle = document.title.split(' - ')[1];
						var categoryForTitle = '';
						if(category != 'all') {
							categoryForTitle = document.getElementById(category).innerHTML + ' ' + 'Games';
						}
						else {
							categoryForTitle = 'Games';
						}
						document.title = categoryForTitle + ' - ' + keepTitle;
						// Update the displayed title
						document.getElementsByClassName('box-content-title')[0].innerHTML = categoryForTitle;
						// Update the description
						var meta = document.getElementsByTagName('meta');
						for (var i=0; i<meta.length; i++) {
							if (meta[i].name.toLowerCase()=='description') {
								meta[i].content = document.getElementById(category).getAttribute('description');
							}
						}
					}
					if(xhttp.status == 200) {
						try {
							loading.style.visibility = 'hidden';
							games.style.opacity = 1;
							var object = JSON.parse(xhttp.responseText);
							var status = object['status'];
							if(status != 'success') {
								games.innerHTML = object['message'];
								pagingControls.style.display = 'none';
							}
							else {
								var gamesHTML = '';
								var gamesArr = object['games'];
								for(var i = 0; i < gamesArr.length; i++) {
									var ratingWidth;
									var ratingSpan;
									var playsSpan;
									var urlBase;
									if(gamesArr[i]['rating'] < 0) {
										ratingSpan = \"\";
										playsSpan = \"\";
										urlBase = 'download';
									}
									else {
										ratingWidth = gamesArr[i]['rating'] * 22 + 'px';
										ratingSpan = \"<span class='stars entry-stars'><span style='width: \" + ratingWidth + \"'></span></span> \";
										urlBase = 'game';
										var playsStr;
										if(gamesArr[i]['plays'] == 1) {
											playsStr = 'play';
										}
										else {
											playsStr = 'plays';
										}
										playsSpan = \"<span class = 'entry-plays'> \" + gamesArr[i]['plays'] + ' ' + playsStr + \"</span>\";
									}
									var gameURL = '/' + urlBase + '/' +  gamesArr[i]['url_name'];
									gamesHTML += \"<a href='\" + gameURL + \"' class = 'entry-link'> \"
									+ \"<span class = 'entry-item'> \"
									+ \"<img alt = '\" + gamesArr[i]['name'] + \"' src = '\" + gamesArr[i]['image_url'] + \"'> \"
									+ \"<span class = 'entry-title'>\" + gamesArr[i]['name'] + \"</span> \"
									+ ratingSpan
									+ \"<span class = 'entry-description'> \" + gamesArr[i]['description'] + \"</span> \"
									+ playsSpan;
									if(category == 'distributable') {
										gamesHTML += \"<span class='distribute-button' onclick='addToSite(event, &quot;\" + gamesArr[i]['url_name'] + \"&quot;)'>Add to your site!</span>\";
									}
									gamesHTML += \"</span></a>\";
								}
								generatePagingControls(object['count']);
								// Show everything
								pagingControls.style.display = 'inline-block';
								games.innerHTML = gamesHTML;
								games.style.visibility = 'visible';
							}
						}
						catch(e) {
							games.innerHTML = errorText;
							pagingControls.style.display = 'none';
							console.log(e);
						}
					}
					else {
						games.innerHTML = errorText;
						pagingControls.style.display = 'none';
					}
				}
			};
			xhttp.open('GET', '/games/ws/' + actualCategory + '/' + actualSearch + '/' + sort + '/' + page, true);
			xhttp.send();
		}
		// The search has changed
		// Do a search and fetch
		function searchAndFetch() {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changeSearch();
			fetchTimeout = setTimeout(function() {fetch(true)}, 500);
			page = 1;
		}
		// The category has changed
		// Do a fetch
		function categoryAndFetch(newCategory) {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changeCategory(newCategory);
			fetchTimeout = setTimeout(function() {fetch(true)}, 500);
			page = 1;
		}
		// The sort has changed
		// Do a fetch
		function sortAndFetch(newSort) {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changeSort(newSort);
			fetchTimeout = setTimeout(function() {fetch(true)}, 500);
		}
		// The page has changed
		// Do a fetch
		function pageAndFetch(newPage) {
			if(fetchTimeout) {
				clearTimeout(fetchTimeout);
			}
			changePage(newPage);
			fetchTimeout = setTimeout(function() {fetch(true)}, 500);
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
			if(newCategory == 'game103') {
				document.getElementById('creation').parentNode.style.display = 'block';
			}
			else {
				document.getElementById('creation').parentNode.style.display = 'none';
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
				var maxPage = Math.ceil(count / $games_per_page);
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