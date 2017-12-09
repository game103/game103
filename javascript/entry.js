/**
* This JS file is for an entry widget and all its subclasses
* The hope is that this is small enough that we can afford to load it all
* and then we will be all set with it being cached for whatever subclass we
* come across.
*/

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
	var apps = document.querySelectorAll('.entry-link');
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