// If the movie is small enough that the desktop view is possible
var detailResponsive = true;
// The current scaling factor of the game
var detailCurValue = 1;
// If the mouse is down
var detailMouseDown = 0;
// The original width of the game
var detailOriginalWidth;
// The original height of the game
var detailOriginalHeight;

document.addEventListener('DOMContentLoaded', function() {
	try {
		// Always check for mouse down
		document.body.onmousedown = function() { 
			++detailMouseDown;
		}
		document.body.onmouseup = function() {
			--detailMouseDown;
		}
		
		// log interaction
		var path = window.location.pathname.split('/');
		if( path[1] != 'download' ) {
			// path[1] is type, path[2] is url_name
			logInteraction( path[1], path[2] );
		}
		else {
			// Download interactions will be logged server-side
			// There is nothing else we need to do for a download,
			// so we can return
			return;
		}
		
		// download will fail here
		// See if range is supported
		if(document.getElementById('zoom-slider').type != 'range') {
			document.getElementById('zoom-slider').style.display = 'none';
			document.getElementById('full').style.display = 'none';
			document.getElementById('default').style.display = 'none';
			document.getElementById('shrink').style.display = 'inline-block';
			document.getElementById('grow').style.display = 'inline-block';
		}
		// Get the original dimensions
		var movie = document.getElementById('movie');
		detailOriginalWidth = parseInt(movie.style.width);
		detailOriginalHeight = parseInt(movie.style.height);
		
		// Zoom
		var zoom = document.getElementById('zoom-slider');
		zoom.onmousedown = detailHideGame;
		zoom.onmouseup = function() { detailChangeZoom(); };
		zoom.onchange = detailEnsureValue;
		zoom.oninput = detailPreview;
		window.addEventListener("resize", function() { detailChangeZoom(); detailEnsureValue();});
		document.getElementById('full').onclick = detailFullscreen;
		document.getElementById('default').onclick = detailDefault;
		document.getElementById('shrink').onclick = detailShrink;
		document.getElementById('grow').onclick = detailGrow;

		// Rating
		var stars = document.querySelectorAll('.detail-star');
		for( var i=0; i<stars.length; i++ ) {
			stars[i].onclick = function() {
				detailRate( this.getAttribute('data-value'), path[1], this.parentNode.getAttribute('data-id') );
			}
		}
		
		// Service Worker availability
		if ('serviceWorker' in navigator && window.navigator.userAgent.indexOf("Edge") == -1) {
			if ( document.getElementById('detail-side-box-offline-available') ) {
				document.getElementById("detail-side-box-offline-available").style.display = "block";
			}
		}

		// Check if flash is enabled if necessary
		checkFlashEnabled();
		
		// Similar items load
		var xhttp = new XMLHttpRequest();
		var placeholder = document.querySelector('.detail-similar-items-placeholder');
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4) {
				try {
					var response = JSON.parse(xhttp.responseText);
					// Nothing will be displayed if content is not defined
					placeholder.innerHTML = response.content;
				}
				catch(e) {
					// Just don't show similar games
				}
			}
		};
		xhttp.open('GET', "/ws/load_similar.php?type=" + path[1] + "&id=" + placeholder.getAttribute('data-id'), true);
		xhttp.send();

		detailChangeZoom();
		detailEnsureValue();

		// Responsive elements will now be controlled by JavaScript after initial page load
		// to deal properly with changes in the movie zoom
		/* Make sure to update the Browser widget and detail-video.css if updating */
		var css = ".detail-left-side-box.responsive{float:left;}.detail-right-side-box.responsive{float:right;}.detail-side-boxes.responsive{display:inline;background-color:transparent;}.detail-side-box.responsive{/*Thismarginreplacesthemarginthatwasonside-boxesbeforeside-boxesbecameinline*/margin-top:10px;display:inline-block;}.detail-side-box-item.responsive{margin-bottom:10px;display:block;}.detail-separator.responsive{display:none;}";
		var head = document.head;
		var style = document.createElement('style');
		head.appendChild(style);
		style.type = "text/css";
		style.appendChild(document.createTextNode(css));
	}
	catch(e) {
		// OK
	}
	
}, false );

// Change the zoom of the movie
// This is called on input from the slider
function detailChangeZoom(value) {
	var movie = document.getElementById('movie');
	var enableFlash = document.getElementById('enable-flash');
	var preview = document.getElementById('preview-box');
	if(value == null) {
		value = document.getElementById('zoom-slider').value/100;
	}
	detailCurValue = value;
	detailSetSizeFromMovieSize(movie, value);
	if(enableFlash) {
		detailSetSizeFromMovieSize(enableFlash, value);
	}
	preview.style.display = 'none';
	movie.style.visibility = 'visible';
	if(enableFlash) {
		enableFlash.style.visibility = 'visible';
	}
	var dims = calculateDimensions();
	if(detailResponsive) {
		if(movie.offsetWidth + 575 > dims.width || (enableFlash && enableFlash.offsetWidth + 575 > dims.width) ) {
			detailStripResponsiveClasses();
			detailResponsive = false;
		}
	}
	else {
		if( (movie.offsetWidth && movie.offsetWidth + 575 <= dims.width) || (enableFlash && enableFlash.offsetWidth && enableFlash.offsetWidth + 575 <= dims.width) ) {
			detailAddResponsiveClasses();
			detailResponsive = true;
		}
	}
}
// Change the size of the preview box
// This is called on mouse move on the slider
// the mouseDown check ensures that this will only occur when the slider
// is pressed
function detailPreview() {
	if(detailMouseDown) {
		var movie = document.getElementById('movie');
		var preview = document.getElementById('preview-box');
		var value = document.getElementById('zoom-slider').value/100;
		detailSetSizeFromMovieSize(preview, value);
	}
}
// Hide the game and show the preview box
// This is called on mouse down from the slider
function detailHideGame() {
	var movie = document.getElementById('movie');
	var enableFlash = document.getElementById('enable-flash');
	var preview = document.getElementById('preview-box');
	preview.style.display = 'block';
	movie.style.visibility = 'hidden';
	if(enableFlash) {
		enableFlash.style.visibility = 'hidden';
	}
}
// Ensure that the slider displays the correct value
// This is called on change from the slider
// This is mainly to fix a bug where the slider
// displays the wrong value after the view changes due to game size
// The mouse down is solely for Internet Explorer since it calls on change
// When the slider is still pressed
function detailEnsureValue() {
	if(!detailMouseDown) {
		document.getElementById('zoom-slider').value = detailCurValue * 100;
	}
	else {
		// Since IE treats onchange like oninput
		preview();
	}
}
// Strip the classes that make this page responsive
// because the game is too big
function detailStripResponsiveClasses() {
	var responsiveClasses = Array.prototype.slice.call(document.getElementsByClassName('responsive'), 0);
	for (var i = 0; i < responsiveClasses.length; i++) {
		responsiveClasses[i].classList.remove('responsive');
		responsiveClasses[i].classList.add('was-responsive');
	}
}
// Add the responsive classes back
function detailAddResponsiveClasses() {
	var responsiveClasses = Array.prototype.slice.call(document.getElementsByClassName('was-responsive'), 0);
	for (var i = 0; i < responsiveClasses.length; i++) {
		responsiveClasses[i].classList.add('responsive');
		responsiveClasses[i].classList.remove('was-responsive');
	}
}
// Function to shrink the game size for when range is not supported
function detailShrink() {
	var movie = document.getElementById('movie');
	if(detailCurValue - 0.25 > 0.5) {
		detailCurValue -= 0.25;
	}
	else {
		detailCurValue = 0.5;
	}
	detailChangeZoom(detailCurValue);
	detailEnsureValue();
}
// Function to grow the game size for when range is not supported
function detailGrow() {
	var movie = document.getElementById('movie');
	if(detailCurValue + 0.25 < 1.75) {
		detailCurValue += 0.25;
	}
	else {
		detailCurValue = 1.75;
	}
	detailChangeZoom(detailCurValue);
	detailEnsureValue();
}
// Function to make the game full screen
function detailFullscreen() {
	var movie = document.getElementById('movie');
	var gameTop = document.getElementsByClassName("header")[0].offsetHeight + 79;
	//var scrollX = 0;
	var dims = calculateDimensions();
	var sizeToSetGame = dims.height;
	var sizeToSetGameWidth = dims.width;
	var percentToSetGame = sizeToSetGame/detailOriginalHeight;
	var percentToSetGameWidth = (sizeToSetGameWidth-10)/detailOriginalWidth;
	if(percentToSetGameWidth < percentToSetGame) {
		percentToSetGame = percentToSetGameWidth;
	}
	detailChangeZoom(percentToSetGame);
	window.scrollTo(document.getElementById('movie-container').offsetLeft-5, gameTop);
	detailEnsureValue();
}
// Function to make the default its default size
function detailDefault() {
	var movie = document.getElementById('movie');
	detailChangeZoom(1);
	detailEnsureValue();
}
// Calculate the width nad height of the page
function calculateDimensions() {
	var widthCalculator = document.createElement('div');
	widthCalculator.style.position = 'fixed';
	widthCalculator.style.width = '1px';
	widthCalculator.style.height = '1px';
	widthCalculator.style.bottom = '0';
	widthCalculator.style.right = '0';
	widthCalculator.style.visibility = 'hidden';
	// Add to the page as sort of a 'Hack'
	document.getElementsByClassName('page')[0].appendChild(widthCalculator);
	var dims = { "height": widthCalculator.offsetTop + 1 };
	widthCalculator.style.position = 'absolute';
	dims.width = widthCalculator.offsetLeft + 1;
	widthCalculator.parentNode.removeChild(widthCalculator);
	return dims;
}
// Set an element's size based on the offset of the original game size
function detailSetSizeFromMovieSize(element, value) {
	element.style.width = (detailOriginalWidth * value).toString().concat('px');
	element.style.height = (detailOriginalHeight * value).toString().concat('px');
}
// Set the total stars value for an element
function detailSetTotalStars(value, id) {
	var totalStars = document.getElementById(id);
	totalStars.innerHTML = '';
	value = parseFloat(value);
	var size = Math.max(0, (Math.min(5, value))) * 22;
	var ratingDisplay = document.createElement('span');
	ratingDisplay.style.width = size.toString().concat('px');
	totalStars.appendChild(ratingDisplay);
}
// Rate the game
function detailRate(rating, type, id) {
	if(isNaN(Number(rating)) || Number(rating) < 0 || Number(rating) > 5) {
		return;
	}
	var normal = document.getElementById('show-rating-normal');
	var loading = document.getElementById('show-rating-loading');
	loading.style.display = 'block';
	normal.style.display = 'none';
	var xhttp = new XMLHttpRequest();
	var errorText = 'Sorry, an error occured while trying to process your vote. Please try again later.';
	xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			try {
				var object = JSON.parse(xhttp.responseText);
				var status = object['status'];
				if(status == 'failure' && !object.your_rating) {
					loading.innerHTML = object['message'];
				}
				else {
					document.getElementsByClassName('detail-your-rating')[0].innerHTML = "<span class='detail-stars' id='your-stars'></span>";
					loading.style.display= 'none';
					normal.style.display = 'inline';
					
					if(status == 'failure') {
						// Set their previous rating to your stars and don't touch the total rating
						detailSetTotalStars( object.your_rating, 'your-stars' );
						document.getElementsByClassName('detail-thanks-for-voting')[0].innerHTML = object.message;
					}
					else {
						detailSetTotalStars( rating, 'your-stars' );
						detailSetTotalStars( object.rating, 'total-stars');
						voteStr = ' vote';
						if(object.votes != 1) {
							voteStr = ' votes';
						}
						document.getElementsByClassName('detail-total-votes')[0].innerHTML = object.votes + voteStr;
					}
					document.getElementsByClassName('detail-thanks-for-voting')[0].style.display = 'block';
				}
			}
			catch(e) {
				console.log(e);
				loading.innerHTML = errorText;
			}
		}
	};
	xhttp.open('GET', '/ws/rating.php?id=' + id + '&rating=' + rating + '&type=' + type, true);
	xhttp.send();
}
// Check to see if flash is available
function checkFlashEnabled() {
	// The flash enabled element will only be display on Flash games
	var flashEnabledElement = document.getElementById('enable-flash');
	var movieElement = document.getElementById("movie");
	if( flashEnabledElement ) {
		var hasFlash = false;
		try {
			hasFlash = Boolean(new ActiveXObject('ShockwaveFlash.ShockwaveFlash'));
		} 
		catch(exception) {
			hasFlash = ('undefined' != typeof navigator.mimeTypes['application/x-shockwave-flash']);
		}
		if( !hasFlash ) {
			movieElement.style.display = "none";
			movieElement.parentNode.style.overflowY = "auto";
			flashEnabledElement.style.display = "table";
			var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
			var android = navigator.userAgent.toLowerCase().indexOf("android") > -1;
			if( iOS ) {
				flashEnabledElement.querySelector("#enable-flash-message-default").style.display = "none";
				flashEnabledElement.querySelector("#enable-flash-message-ios").style.display = "block";
			}
			else if( android ) {
				flashEnabledElement.querySelector("#enable-flash-message-default").style.display = "none";
				flashEnabledElement.querySelector("#enable-flash-message-android").style.display = "block";
			}
			return;
		}
	}
}