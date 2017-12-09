// No name append needed for functions in base

// Toggle the display of the menu
function toggleMobileMenuDisplay() {
	var navElements = document.getElementsByClassName('nav-item');
	// menu is closed
	if(!navElements[1].classList.contains('mobile-visible')) {
		for(var i = 1; i < navElements.length; i++) {
			navElements[i].classList.add('mobile-visible');
		}
		document.getElementById('nav-dropdown-arrow').innerHTML = '&#9650;';
	}
	// menu is open
	else {
		for(var i = 1; i < navElements.length; i++) {
			navElements[i].classList.remove('mobile-visible');
		}
		document.getElementById('nav-dropdown-arrow').innerHTML = '&#9660;';
	}
}

// Log interaction
function logInteraction(type, url) {
	var xhttp = new XMLHttpRequest();
	// TODO -> update this service
	xhttp.open('GET', '/ws/log_interaction.php?type='+type+'&url_name='+url, true);
	xhttp.send();
}

// Site search
var siteSearchFetchTimeout;
var siteSearchSelected = false;
document.addEventListener('DOMContentLoaded', function() {
	// Close drop down on click
	document.onclick = function() {
		if(!siteSearchSelected) {
			document.getElementById('site-search-results-dropdown').style.display = 'none';
		}
	}
	document.onkeydown = function(event) {
		navigateSiteSearch(event);
	};
	
	// Input for site search
	var siteSearchInput = document.querySelector('.site-search').querySelector('input');
	siteSearchInput.onfocus = enableSiteSearchArrows;
	siteSearchInput.onclick = enableSiteSearchArrows;
	siteSearchInput.onblur = disableSiteSearchArrows;
	siteSearchInput.oninput = suggest;
}, false );

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
									url = '/' + itemsArr[i]['type'] + '/' + itemsArr[i]['url_name'];
									target = '_self';
								}
								
								// Create the suggestion
								var suggestion = document.createElement("li");
								suggestion.classList.add('header-dropdown-item');
								suggestion.onmouseover = function() { addSelected(this) };
								suggestion.onmouseout = function() { this.classList.remove('header-dropdown-item-selected'); };
								var suggestionLink = document.createElement("a");
								suggestionLink.setAttribute("href", url);
								suggestionLink.setAttribute("target", target);
								if( itemsArr[i]['type'] == 'resource' ) {
									suggestionLink.onclick = function() { logInteraction('resource', this.getAttribute('href')); };
								}
								suggestionLink.classList.add('header-dropdown-item-text');
								suggestionLink.innerHTML = itemsArr[i]['title'] + ' <span class=\"header-dropdown-item-type\">' + itemsArr[i]['type'] + '</span>';
								suggestion.appendChild(suggestionLink);
								suggestions.appendChild(suggestion);
							}
							//suggestions.innerHTML = suggestionsHTML;
							suggestions.style.display = 'block';
						}
					}
					catch(e) {
						// OK
					}
				}
				else {
					//OK
				}
			}
		};
		xhttp.open('GET', '/ws/site_search.php?search=' + searchBox.value, true);
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
					currentSelectedItem.querySelector("a").click();
					e.stopPropagation();
					e.preventDefault();
				}
			}
		}
	}
}