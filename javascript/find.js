var findSearchTimeout;

// Make everything on the page ajaxy
function findMakeLinksAjax() {
	try {
		// Make links ajax
		var links = document.querySelectorAll(".box-content-footer a, .box-content-title a");
		for( var i=0; i<links.length; i++ ) {
			links[i].onclick = function( event ) {
				event.preventDefault();
				event.stopPropagation();
				var link = this.getAttribute('href');
				findMakeRequest( link, true );
				return false;
			}
		}
		// Make form ajax
		var form = document.querySelector(".find-refine form");
		form.onsubmit = function( event ) {
			event.preventDefault();
			event.stopPropagation();
			return false;
		};
		// Create instant search
		var input = document.querySelector(".find-refine input");
		input.oninput = function( event ) {
			var text = findStrip( this.value );
			
			// Create link from selected page link
			var currentPage = document.querySelector('.find-dropdown-selected-in-list a').getAttribute('href');
			currentPage = currentPage.split('/');
			
			// Use this to find where to plug search into the url
			var withSearchSize = 6;
			if( currentPage[1] == 'everything' || currentPage[1] == 'apps' ) {
				withSearchSize = 5;
			}
			if( currentPage[1] == 'games' ) {
				withSearchSize = 7;
			}
			
			// Remove current search
			if( currentPage.length == withSearchSize ) {
				currentPage.splice( currentPage.length - 3 ,1);
			}
			if( text ) {
				currentPage.splice( currentPage.length - 2,0,text);
			}
			currentPage = currentPage.join('/');
			
			// Clear timeout
			if(findSearchTimeout) {
				clearTimeout(findSearchTimeout);
			}
			
			// Get the current cursor position, so we can set it again after fetch
			var sStart = document.getElementById('search').selectionStart;
			
			// Wait a bit before our request
			findSearchTimeout = setTimeout(function() {
				findMakeRequest( currentPage, true, true, sStart );
			}, 500);
		}
		
		// Make dropdowns close
		var dropdowns = document.querySelectorAll('.find-dropdown');
		for( var i=0; i<dropdowns.length; i++ ) {
			dropdowns[i].onclick = function( event ) {
				var checkbox = document.getElementById( this.getAttribute('for') );
				// Don't close all others unless this checkbox is unchecked
				if( !checkbox.checked ) {
					findCloseMenus();
					checkbox.checked = true;
					event.preventDefault();
					event.stopPropagation();
				}
			}
		}
		
		// Make entries work
		entrySetLinks();
	}
	catch(e) {
		// OK
	}
}

// Make a request with the option to change the state
// focus on the search box, and where to position the cursor
// in the search input
function findMakeRequest ( link, changeState, focusSearch, sStart ) {
	var xhttp = new XMLHttpRequest();
	var items = document.querySelector('.box-content-tab-selected');
	// Make the items half transparent to look like we are loading
	if( items ) {
		items.style.opacity = 0.5;
	}
	var content = document.querySelector('.content');
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4) {
			try {
				
				// Replace the box, title, and description with ajax
				var object = JSON.parse(xhttp.responseText);
				content.innerHTML = object.content;
				document.title = object.title;
				var meta = document.getElementsByTagName('meta');
				for (var i=0; i<meta.length; i++) {
					if (meta[i].name.toLowerCase()=='description') {
						meta[i].content = object.description;
					}
				}
				
				// Change state if necessary
				// It is unecessary on pop state
				if( changeState ) {
					history.pushState('', '', link);
				}
				
				// Focus on the search box in the position the user was
				// on prior to the request being made
				if( focusSearch ) {
					var searchBox = document.getElementById('search');
					searchBox.focus();
					searchBox.setSelectionRange(sStart, sStart);
				}
				
				// Update the links which have now been replaced
				findMakeLinksAjax();
			}
			catch(e) {
				if( items ) {
					items.style.opacity = 1;
				}
				content.innerHTML = "An error has occurred. Please try reloading the page.";
			}
		}
	};
	xhttp.open('GET', link + "?ws=1", true);
	xhttp.send();
}

// https://stackoverflow.com/questions/822452/findStrip-html-from-text-javascript
// Strip html
function findStrip(html)
{
   var tmp = document.implementation.createHTMLDocument("New").body;
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

// Close menus
function findCloseMenus() {
	var checkboxes = document.querySelectorAll( '.find-checkbox' );
	for( var i=0; i<checkboxes.length; i++ ) {
		checkboxes[i].checked = false;
	}
}

// Close menus on click
document.onclick = findCloseMenus;

// On popstate, load the state values and do a fetch
window.onpopstate = function(event) {
	findMakeRequest(window.location.pathname, false);
}

// On load
document.addEventListener('DOMContentLoaded', function() {
	findMakeLinksAjax();
}, false );