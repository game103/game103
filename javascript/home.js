document.addEventListener('DOMContentLoaded', function() {
	
	// Load random on load
	homeLoadRandom();
	
	// Random games on click
	document.querySelector('.box-content-button:nth-child(6)').onclick = function() {
		if( document.querySelector('.box-content-button:nth-child(6)').classList.contains('box-content-button-selected') ) {
			homeLoadRandom();
		}
	}
	
	// Earlier content on click
	document.querySelector('#load-earlier-content').onclick = homeLoadNew;
	
}, false );

// Load random games
function homeLoadRandom() {
	try {
		// Random items load
		var xhttp = new XMLHttpRequest();
		var placeholder = document.querySelector('.home-random-items-placeholder');
		placeholder.style.opacity = 0.5;
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4) {
				try {
					var response = JSON.parse(xhttp.responseText);
					// Nothing will be displayed if content is not defined
					placeholder.innerHTML = response.content;
				}
				catch(e) {
					console.log(e);
					// Just don't show similar games
				}
				placeholder.style.opacity = 1;
			}
		};
		xhttp.open('GET', "/ws/load_similar.php?type=game&no_box=true", true);
		xhttp.send();
		
	}
	catch(e) {
		// OK
	}
}

var homePage = 1;

// Load new content
function homeLoadNew() {
	var errorText = 'Sorry, there was an error loading similar items. Please try again later.';
	var content = document.querySelector(".box-content:last-child .box-content-tab");
	// Don'y load if we are already loading
	if(content.style.opacity != 0.5) {
		var xhttp = new XMLHttpRequest();
		content.style.opacity = 0.5;
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4) {
				if(xhttp.status == 200) {
					try {
						content.style.opacity = 1;
						
						var response = JSON.parse(xhttp.responseText);
						// Nothing will be displayed if content is not defined
						var newContent = response.content;
						
						// Get the last date box and its title
						var dateBoxes = document.getElementsByClassName('find-date-box');
						var dateBoxTitles = document.getElementsByClassName('find-date-box-title');
						var previousDateBoxCount = dateBoxes.length;
						var lastDateBox = dateBoxes[previousDateBoxCount - 1];
						var lastDateBoxTitle = dateBoxTitles[dateBoxTitles.length - 1];
						
						// Add the new data and get the first newly added game box and its title
						var previousContent = content.innerHTML;
						content.innerHTML = content.innerHTML + newContent;
						dateBoxes = document.getElementsByClassName('find-date-box');
						dateBoxTitles = document.getElementsByClassName('find-date-box-title');
						var newestLoadedDateBox = dateBoxes[previousDateBoxCount];
						
						if(newestLoadedDateBox) {
							var newestLoadedDateBoxTitle = dateBoxTitles[previousDateBoxCount];
							
							// If the previous last and newest loaded boxes have the same title (date)
							// Combine them
							if(lastDateBoxTitle.innerHTML == newestLoadedDateBoxTitle.innerHTML) {
								var innerContent = newestLoadedDateBox.childNodes;
								var keptInnerContent = '';
								for(var i = 0; i < innerContent.length; i++) {
									if(innerContent[i].tagName == 'A') {
										keptInnerContent += innerContent[i].outerHTML;
									}
								}
								lastDateBox = document.getElementsByClassName('find-date-box')[previousDateBoxCount-1];
								lastDateBox.innerHTML = lastDateBox.innerHTML + keptInnerContent;
								newestLoadedDateBox.parentNode.removeChild(newestLoadedDateBox);
							}
							
							// Make entry links work
							entrySetLinks();
						}
						// We didn't load any new content, so hide
						// Also, hide the error message by going back to previous content
						else {
							content.innerHTML = previousContent;
							document.querySelector('.box-content:last-child .box-content-footer').style.display = 'none';
						}
					}
					catch(e) {
						content.innerHTML = errorText;
						content.style.opacity = 1;
					}
				}
				else {
					content.innerHTML = errorText;
				}
			}
		}
		var gameOffset = document.getElementsByClassName('game-entry').length;
		var videoOffset = document.getElementsByClassName('video-entry').length;
		homePage ++;
		xhttp.open('GET', '/ws/load_new.php?page=' + homePage, true);
		xhttp.send();
	};
}