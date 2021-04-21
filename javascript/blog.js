/**
* This JS file is for a blog widget
*/

document.addEventListener('DOMContentLoaded', function(){ 
	var showAllLinks = document.querySelectorAll(".blog-post-contents-show");

	var prevClick = [];
	for( var i=0; i<showAllLinks.length; i++ ) {
		// Allow for multiple on click events
		prevClick[i] = showAllLinks[i].onclick;
		showAllLinks[i].onclick = function() {
            var blogPost = this.closest(".blog-post");
            var blogPostHiddenContents = blogPost.querySelector(".blog-post-contents-hidden");
            // If hidden, show and give option to hide
            if( blogPostHiddenContents.offsetParent === null ) {
                this.innerHTML = "Collapse";
                blogPostHiddenContents.style.display = "inline";
            }
            // Otherwise, hide and give option to show
            else {
                this.innerHTML = "Show all";
                blogPostHiddenContents.style.display = "none";
                window.scrollTo(0, blogPost.offsetTop);
            }
        };
	}
}, false);