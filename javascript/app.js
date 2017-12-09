// Author: ricocheting.com
// Description: slideshow that allows visitors to flip through a series of images on your website
var x=0;
function appRotate(num){
	fs=document.getElementById('tutorial-slide');
	x=num%fs.length;
	if(x<0) x=fs.length-1;
	document.images.show.src=fs.options[x].value;
	fs.selectedIndex=x;
}

document.addEventListener('DOMContentLoaded', function() {
	
	var tutorialSlide = document.getElementById('tutorial-slide');
	if( tutorialSlide ) {
		 tutorialSlide.onChange= function() { appRotate(this.selectedIndex) };
		 document.getElementById('tutorial-first').onclick = function() { appRotate(0) };
		 document.getElementById('tutorial-previous').onclick = function() { appRotate(x-1) };
		 document.getElementById('tutorial-next').onclick = function() { appRotate(x+1) };
		 document.getElementById('tutorial-last').onclick = function() { appRotate(document.getElementById('tutorial-slide').length-1) };
	}
	
	var storeLinks = document.getElementsByClassName('app-store-link');
	for(var i =0; i<storeLinks.length; i++ ){
		storeLinks[i].onclick= function() {
			logInteraction('app', this.getAttribute('data-store-url') ? this.getAttribute('data-store-url') : this.getAttribute('href') );
		}
	}
}, false );