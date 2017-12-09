/**
* This JS file is for a box widget and all its subclasses
*/

document.addEventListener('DOMContentLoaded', function(){ 
	var buttons = document.querySelectorAll(".box-content-button");

	// Control tabbing
	var prevClick = [];
	for( var i=0; i<buttons.length; i++ ) {
		// Allow for multiple on click for tabs
		prevClick[i] = buttons[i].onclick;
		buttons[i].onclick = function() {
			var index = Array.prototype.indexOf.call( this.parentNode.querySelectorAll(".box-content-button"), this );
			if( prevClick[index] ) {
				prevClick[index]();
			}
			this.parentNode.querySelector(".box-content-button-selected").classList.remove("box-content-button-selected");
			this.parentNode.querySelectorAll(".box-content-button")[index].classList.add("box-content-button-selected");
			this.parentNode.querySelector(".box-content-container .box-content-tab-selected").classList.remove("box-content-tab-selected");
			this.parentNode.querySelectorAll(".box-content-container .box-content-tab")[index].classList.add("box-content-tab-selected");
		};
	}
}, false);