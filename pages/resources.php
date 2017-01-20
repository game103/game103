<?php
	try {
		// THIS PAGE CAN ACT AS A WEB SERVICE
		
		if(!isset($routed)) {
			throw new Exception($direct_access_message);
		}
		
		$bad_params_message = "Unable to fetch resources based on the url.";
		$dropdown_selected_in_list_class = "dropdown-selected-in-list";
		$paging_selected_page_class = "paging-selected-page";
		$hide_style = 'style="visibility: hidden"';
		$none_style = 'style="display:none"';
		$resources_per_page = 15;
		$display_page = '';
		// For paging
		$max = 2;
		$min = - $max;
		$max_page = -1;
		
		// Ensure the Page is valid
		if(!is_numeric($page)) {
			throw new Exception($bad_params_message);
		}
		
		// Connect to database
		$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_resources");
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
		
		// Setup the array of categories
		// Keys are id, values are arrays with [0] = display name
		// and [1] = whether or not they are selected
		// NOTE: it is important here that the ids match the database search term
		// save for - filling in for a space.
		// The javascript will use these IDs to perform web service requests to fetch new resources
		$categories_arr = array(
			'all' => array('All Resources', false, 'A listing of links to resources that are useful for developers and used by Game 103.')
		);
		$category_select_str = "SELECT name, url_name, description FROM categories ORDER BY name ASC;";
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
		
		$category_match_ids_str = strtolower($category);
		if(isset($categories_arr[$category_match_ids_str])) {
			$categories_arr[$category_match_ids_str][1] = true;
			$dropdown_selected_str = $categories_arr[$category_match_ids_str][0];
		}
		else {
			throw new Exception($bad_params_message);
		}
		
		// Ensure the sort is valid and construct the sort section of the sql statement
		$alphabetically_class = "";
		$date_class = "";
		if($sort == "alphabetical") {
			$sort_sql = 'name';
			$sort_selected_str = 'Sort alphabetically';
			$alphabetically_class = $dropdown_selected_in_list_class;
		}
		else if($sort == "date") {
			$sort_sql = 'added_date DESC';
			$sort_selected_str = 'Sort by date';
			$date_class = $dropdown_selected_in_list_class;
		}
		else {
			throw new Exception($bad_params_message);
		}
		
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
		}
		else {
			$where_sql = $category_sql;
		}
		
		// Get the offset
		$offset = ($page - 1) * $resources_per_page;
		
		// Create the SQL Statement
		$select_str = "SELECT * FROM (
						SELECT entries.name as name, entries.description, entries.url, entries.image_url
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						JOIN categories ON categories_entries.category_id = categories.id
						$where_sql
						GROUP BY entries.id
						ORDER BY $sort_sql
						LIMIT $resources_per_page
						OFFSET $offset) AS main
						LEFT JOIN (
						SELECT count(distinct entries.id) AS total_count
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						JOIN categories ON categories_entries.category_id = categories.id
						$where_sql
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
			else {
				$select_statement->bind_param("ssss", $category, $search_wildcards, $category, $search_wildcards);
			}
		}
		
		// Exexecute the SQL Statement
		$select_statement->execute();
		if(mysqli_stmt_error($select_statement) != "") {
			throw new Exception($mysql_message);
			$mysqli->close();
			exit();
		}
		$select_statement->bind_result($name, $description, $url, $image_url, $total_count);
		
		// If this is being used as Web Service, respond accordingly
		if(isset($ws)) {
			$resources = array();
			while($select_statement->fetch()) {
				$resource_object = array (
					"name" => htmlentities($name, ENT_QUOTES),
					"description" => $description,
					"url" => $url,
					"image_url" => $image_url
				);
				$resources[] = $resource_object;
			}
			$select_statement->close();
			if(count($resources) > 0) {
				$return_val = array(
					"status" => "success",
					"count" => $total_count,
					"resources" => $resources
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
		// Create the resources
		// This same sort of thing is also done in javascript when an
		// ajax request comes in
		while($select_statement->fetch()) {
			$results = true;
			
			// Escape the quotes in the name of the entry
			$name = htmlentities($name, ENT_QUOTES);
			
			$display_page .= "<a href=\"$url\" class = 'entry-link'>
			<span class = 'entry-item'>
			<img alt = '$name' src = '$image_url'>
			<span class = 'entry-title'>$name</span>";
			$display_page .= "<span class = 'entry-description'> $description</span>
			</span>
			</a>";
		}
		
		$select_statement->close();
		
		$mysqli->close();
		
		if(!$results) {
			$display_page = $no_results_message;
		}
		
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
		
		// Create the paging controls
		if($results) {
			$paging_controls = "<div class='paging-controls'>";
		}
		else {
			$paging_controls = "<div class='paging-controls' style='display:none;'>";
			$total_count = 0;
		}
		$max_page = ceil($total_count / $resources_per_page);
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
		
		if($category != "all") {
			$category_for_title = $categories_arr[$category][0] . " ";
		}
		else {
			$category_for_title = "";
		}
		$display_title = $category_for_title . "Resources";
		
		// Create everything else needed on the page and put it together.		
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
				<div id='resources-entries'>
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
		category = category.toLowerCase();
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
		// Fetch another page
		// changeState is a boolean that determines whether or not to change the state
		function fetch(changeState) {
			var actualSearch = search.replace(/\s/g, '+');
			var paramSearch = '';
			if(actualSearch) {
				paramSearch = '/' + actualSearch;
			}
			var xhttp = new XMLHttpRequest();
			var errorText = 'Sorry, an error occured while trying to fetch more resources. Please try again later.';
			var resources = document.getElementById('resources-entries');
			var pagingControls = document.getElementsByClassName('paging-controls')[0];
			var loading = document.getElementById('loading');
			resources.style.opacity = 0.5;
			loading.style.visibility = 'visible';
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4) {
					if(changeState) {
						history.pushState({
							search: search,
							category: category,
							page: page,
							sort: sort
						}, '', '/resources/' + category + paramSearch + '/' + sort + '/' + page);
						// Update the title
						var keepTitle = document.title.split(' - ')[1];
						var categoryForTitle = '';
						if(category != 'all') {
							categoryForTitle = document.getElementById(category).innerHTML + ' ' + 'Resources';
						}
						else {
							categoryForTitle = 'Resources';
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
							resources.style.opacity = 1;
							var object = JSON.parse(xhttp.responseText);
							var status = object['status'];
							if(status != 'success') {
								resources.innerHTML = object['message'];
								pagingControls.style.display = 'none';
							}
							else {
								var resourcesHTML = '';
								var resourcesArr = object['resources'];
								for(var i = 0; i < resourcesArr.length; i++) {
									var ratingWidth;
									ratingWidth = resourcesArr[i]['rating'] * 22 + 'px';
									var resourceURL = resourcesArr[i]['url'];
									resourcesHTML += \"<a href='\" + resourceURL + \"' class = 'entry-link'> \"
									+ \"<span class = 'entry-item'> \"
									+ \"<img alt = '\" + resourcesArr[i]['name'] + \"' src = '\" + resourcesArr[i]['image_url'] + \"'> \"
									+ \"<span class = 'entry-title'>\" + resourcesArr[i]['name'] + \"</span> \"
									+ \"<span class = 'entry-description'> \" + resourcesArr[i]['description'] + \"</span> \";
									resourcesHTML += \"</span></a>\";
								}
								generatePagingControls(object['count']);
								pagingControls.style.display = 'inline-block';
								resources.innerHTML = resourcesHTML;
								resources.style.visibility = 'visible';
							}
						}
						catch(e) {
							resources.innerHTML = errorText;
							pagingControls.style.display = 'none';
							console.log(e);
						}
					}
					else {
						resources.innerHTML = errorText;
						pagingControls.style.display = 'none';
					}
				}
			};
			xhttp.open('GET', '/resources/ws/' + category + '/' + actualSearch + '/' + sort + '/' + page, true);
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
				var maxPage = Math.ceil(count / $resources_per_page);
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