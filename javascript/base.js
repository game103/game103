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

// Toggle the display of more content
function toggleMoreMenuDisplay() {
	var moreDropDown = document.getElementById("more-drop-down");
	if(moreDropDown.classList.contains('nav-item-dropdown-mobile-visible')) {
		moreDropDown.classList.remove('nav-item-dropdown-mobile-visible');
		document.getElementById('more-dropdown-arrow').innerHTML = '&#9660;';
	}
	else {
		moreDropDown.classList.add('nav-item-dropdown-mobile-visible');
		document.getElementById('more-dropdown-arrow').innerHTML = '&#9650;';
	}
}

// Log interaction
function logInteraction(type, url) {
	var xhttp = new XMLHttpRequest();
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
									url = itemsArr[i]['store_url_android'];
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
								// Apps that go straight to the app store
								else if( itemsArr[i]['type'] == 'app' && !itemsArr[i]['url_name'] ) {
									suggestionLink.onclick = function() { logInteraction('app', this.getAttribute('href')); };
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

// Show a given index of navbar games
function showNavbarGames(index) {
	var navItemPreviews = document.getElementsByClassName("nav-item-preview-category");
	for( var i=0; i<navItemPreviews.length; i++ ) {
		navItemPreviews[i].style.display = 'none';
	}
	document.getElementsByClassName("nav-item-preview-category")[index].style.display = 'block';
}

// functions for entries

// Open URL (This is only used for store links on apps)
function entryOpenURL(event, url) {
	event.preventDefault();
	logInteraction('app', url);
	window.open(url, '_blank');
	//document.getElementById('site-search-results-dropdown').style.display = 'none';
	event.stopPropagation();
}

// Add to site
function entryAddToSite(event, urlName) {
	event.preventDefault();
	window.location.href = '/game103games/distribute/' + urlName + '.zip';
	//document.getElementById('site-search-results-dropdown').style.display = 'none';
	event.stopPropagation();
}

// Set entry links by JS
function entrySetLinks() {
	// call log interaction for certain items
	var resourceLinks = document.querySelectorAll(".entry-link[data-type='resource']");
	for( var i=0; i<resourceLinks.length; i++ ) {
		resourceLinks[i].onclick = function() { 
			logInteraction( this.getAttribute('data-type'), this.getAttribute('data-url-name') );
		};
	}

	// call open url for class entry-ios-link and entry-android-link
	var appLinks = document.querySelectorAll(".entry-ios-link, .entry-android-link");
	for( var i=0; i<appLinks.length; i++ ) {
		appLinks[i].onclick = function(event) { 
			entryOpenURL( event, this.getAttribute('data-store-url') );
		};
	}
	
	// call log interaction for items that go straight to the store
	var apps = document.querySelectorAll('.entry-link[data-type="app"]');
	for( var i=0; i<apps.length; i++ ) {
		if( apps[i].getAttribute('target') == '_blank' ) {
			apps[i].onclick = function(event) { 
				logInteraction('app', this.getAttribute('href') );
			};
		}
	}

	// call add to site for entry-distribute-button
	var distributeButtons = document.querySelectorAll(".entry-distribute-button");
	for( var i=0; i<distributeButtons.length; i++ ) {
		distributeButtons[i].onclick = function(event) { 
			entryAddToSite( event, this.parentNode.parentNode.getAttribute('data-url-name') );
		};
	}
}

document.addEventListener('DOMContentLoaded', entrySetLinks, false );

// Lazy load images
document.addEventListener('DOMContentLoaded', function() {

	var lazyPictureObserver; // Global lazy picture observer
	// We will reset the listener every second to listen for potentially newly added listeners (over ajax)
	function lazyListen() {
		var lazyPictures = [].slice.call(document.querySelectorAll(".lazy"));

		// If we support IntersectionObserver
		if ("IntersectionObserver" in window) {
			if( lazyPictureObserver ) {
				lazyPictureObserver.disconnect();
			}
			lazyPictureObserver = new IntersectionObserver(function(entries, observer) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						var lazyPicture = entry.target;
						var lazyImageSources = lazyPicture.querySelectorAll("source, img");
						lazyImageSources.forEach(function(lazyImage) {
							if( lazyImage.src ) {
								lazyImage.src = lazyImage.dataset.src;
							}
							if( lazyImage.srcset ) {
								lazyImage.srcset = lazyImage.dataset.srcset;
							}
						});
						lazyPicture.classList.remove("lazy");
						lazyPictureObserver.unobserve(lazyPicture);
					}
				});
			});

			lazyPictures.forEach(function(lazyPicture) {
				lazyPictureObserver.observe(lazyPicture);
			});
		} 
		// Fallback for older browsers
		else {
			document.removeEventListener("scroll", lazyLoad);
			window.removeEventListener("resize", lazyLoad);
			window.removeEventListener("orientationchange", lazyLoad);

			var active = false;
		
			var lazyLoad = function() {
				if (active === false) {
					active = true;
		
					// Every 200 ms potentially lazy load
					setTimeout(function() {
						lazyPictures.forEach(function(lazyPicture) {
							if ((lazyPicture.getBoundingClientRect().top <= window.innerHeight && lazyPicture.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyPicture).display !== "none") {
								
								var lazyImageSources = lazyPicture.querySelectorAll("source, img");
								lazyImageSources.forEach(function(lazyImage) {
									if( lazyImage.src ) {
										lazyImage.src = lazyImage.dataset.src;
									}
									if( lazyImage.srcset ) {
										lazyImage.srcset = lazyImage.dataset.srcset;
									}
								});

								lazyPicture.classList.remove("lazy");
								lazyPictures = lazyPictures.filter(function(picture) {
									return picture !== lazyPicture;
								});
			
								if (lazyPictures.length === 0) {
									document.removeEventListener("scroll", lazyLoad);
									window.removeEventListener("resize", lazyLoad);
									window.removeEventListener("orientationchange", lazyLoad);
								}
							}
						});
			
						active = false;
					}, 200);
				}
			};
		
			document.addEventListener("scroll", lazyLoad);
			window.addEventListener("resize", lazyLoad);
			window.addEventListener("orientationchange", lazyLoad);
			lazyLoad(); // Lazy load immediately images in the viewport
		}
	}

	lazyListen();
	setInterval(lazyListen, 400);
});

// Register service worker
navigator.serviceWorker && navigator.serviceWorker.register('/javascript/sw.js').then(function(registration) {
	console.log('Registered with scope: ', registration.scope);
});