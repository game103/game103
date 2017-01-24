<?php
	$site_search = "
	<form class='site-search' onsubmit='return siteSearch()'>
		<input onfocus='enableSiteSearchArrows()' onclick='enableSiteSearchArrows()' onblur='disableSiteSearchArrows()' placeholder='Find games and more!' id='site-search-input' autocomplete='off' type='text' oninput='suggest()'><input type='submit' value='Search' class='button' id='site-search-go'></input>
		<div class='dropdown header-dropdown'>
			<ul class='dropdown-menu' id='site-search-results-dropdown'>
			</ul>
		</div>
	</form>";
	
	$site_search_javascript = "
	var siteSearchFetchTimeout;
	var siteSearchSelected = false;
	window.onload = function() {
		// Close drop down on click
		document.onclick = function() {
			if(!siteSearchSelected) {
				document.getElementById('site-search-results-dropdown').style.display = 'none';
			}
		}
		document.onkeydown = navigateSiteSearch;
	}
	// Oninput for the form
	function suggest() {
		if(siteSearchFetchTimeout) {
			clearTimeout(siteSearchFetchTimeout);
		}
		var suggestions = document.getElementById('site-search-results-dropdown');
		suggestions.style.display = 'none';
		suggestions.innerHTML = '';
		siteSearchFetchTimeout = setTimeout(function() {suggestFetch()}, 500);
	}
	// Fetch suggestions and display them
	function suggestFetch() {
		var searchBox = document.getElementById('site-search-input');
		var suggestions = document.getElementById('site-search-results-dropdown');
		if(searchBox.value.length > 2) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4) {
					if(xhttp.status == 200) {
						try {
							var object = JSON.parse(xhttp.responseText);
							var itemsArr = object['items'];
							if(itemsArr) {
								var suggestionsHTML = '';
								var loopLength = itemsArr.length >= 8 ? 8 : itemsArr.length;
								for(var i = 0; i < loopLength; i++) {
									var url;
									var target;
									if(itemsArr[i]['type'] == 'resource') {
										url = itemsArr[i]['url_name'];
										target = '_blank';
									}
									else if(itemsArr[i]['type'] == 'app' && !itemsArr[i]['url_name']) {
										url = itemsArr[i]['store_url'];
										target = '_blank';
									}
									else {
										url = itemsArr[i]['type'] + '/' + itemsArr[i]['url_name'];
										target = '_self';
									}
									suggestionsHTML += '<li class=\"header-dropdown-item\" onclick=\"openSiteSearchURL(`' + url + '`,`' + target + '`)\" '
									+ 'onmouseover=\"addSelected(this)\" onmouseout=\"this.classList.remove(`header-dropdown-item-selected`)\">'
									+ '<span class=\"dropdown-item-text\">'
									+ itemsArr[i]['name'] + ' <span class=\"header-dropdown-item-type\">' + itemsArr[i]['type'] + '</span>'
									+ '</span>'
									+ '</li>';
								}
								suggestions.innerHTML = suggestionsHTML;
								suggestions.style.display = 'block';
							}
						}
						catch(e) {
							console.log(e);
						}
					}
					else {
					}
				}
			};
			xhttp.open('GET', '/everything/ws/' + searchBox.value + '/popularity/1', true);
			xhttp.send();
		}
	}
	// Remove selected
	function addSelected(element) {
		// Unselect everything else
		// This ensures only one item is selected at once
		var dropdowns = document.getElementsByClassName('header-dropdown-item');
		for(var i = 0; i < dropdowns.length; i++) {
			dropdowns[i].classList.remove('header-dropdown-item-selected');
		}
		element.classList.add('header-dropdown-item-selected');
	}
	// Perform a search
	function siteSearch() {
		var searchValue = document.getElementById('site-search-input').value;
		window.location.href = '/everything/' + searchValue + '/popularity/1';
		return false;
	}
	// Open a URL
	function openSiteSearchURL(url, target) {
		console.log(url);
		if(target == '_self') {
			window.location.href = '/' + url;
		}
		else {
			window.open(url, '_blank');
		}
	}
	// Enabled site search arrow key controls
	function enableSiteSearchArrows() {
		siteSearchSelected = true;
		var suggestions = document.getElementById('site-search-results-dropdown');
		// There are suggestions, show them
		if(suggestions.innerHTML.length > 0) {
			suggestions.style.display = 'block';
		}
		// Remove previously selected
		var dropdowns = document.getElementsByClassName('header-dropdown-item');
		for(var i = 0; i < dropdowns.length; i++) {
			dropdowns[i].classList.remove('header-dropdown-item-selected');
		}
	}
	// Disable site search arrow key controls
	function disableSiteSearchArrows() {
		siteSearchSelected = false;
	}
	// Navigate through site search with arrows
	function navigateSiteSearch(e) {
		if(siteSearchSelected) {
			var possibleItems = document.getElementsByClassName('header-dropdown-item');
			if(possibleItems.length > 0) {
				var currentSelectedItem = document.getElementsByClassName('header-dropdown-item-selected')[0];
				e = e || window.event;

				// up arrow
				if (e.keyCode == '38') {
					// an item is currently selected
					if(currentSelectedItem) {
						var upSibling = currentSelectedItem.previousSibling;
						currentSelectedItem.classList.remove('header-dropdown-item-selected');
						if(upSibling) {
							upSibling.classList.add('header-dropdown-item-selected');
						}
						else {
							document.getElementById('site-search-results-dropdown').style.display = 'none';
						}
					}
					e.stopPropagation();
					e.preventDefault();
				}
				// down arrow
				else if (e.keyCode == '40') {
					// an item is currently selected
					if(currentSelectedItem) {
						var downSibling = currentSelectedItem.nextSibling;
						currentSelectedItem.classList.remove('header-dropdown-item-selected');
						if(downSibling) {
							downSibling.classList.add('header-dropdown-item-selected');
						}
						else {
							possibleItems[0].classList.add('header-dropdown-item-selected');
						}
					}
					// no item is selected
					else {
						document.getElementById('site-search-results-dropdown').style.display = 'block';
						possibleItems[0].classList.add('header-dropdown-item-selected');
					}
					e.stopPropagation();
					e.preventDefault();
				}
				// enter
				else if (e.keyCode == '13') {
					// If there is a place to go
					if(currentSelectedItem) {
						currentSelectedItem.click();
						e.stopPropagation();
						e.preventDefault();
					}
				}
			}
		}
	}
	";
?>