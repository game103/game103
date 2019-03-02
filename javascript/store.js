/**
* This JS file is for a store widget
*/

document.addEventListener('DOMContentLoaded', function(){ 
	var storePictures = document.querySelectorAll(".store-item picture");

    storePictures.forEach( function(storePicture) {
        storePicture.addEventListener( "mouseover", function() {
            var storeImages = this.querySelectorAll("img, source");
            storeImages.forEach( function(storeImage) {
                var backSrc = storeImage.getAttribute('data-back-src');
                if( backSrc ) {
                    if( storeImage.src ) {
                        storeImage.setAttribute("data-src", storeImage.src);
                        storeImage.src = backSrc;
                    }   
                    else {
                        storeImage.setAttribute("data-src", storeImage.srcset);
                        storeImage.srcset = backSrc;
                    }
                }
            } );
        } ); 
        storePicture.addEventListener( "mouseout", function() {
            var storeImages = this.querySelectorAll("img, source");
            storeImages.forEach( function(storeImage) {
                var frontSrc = storeImage.getAttribute('data-src');
                if( frontSrc ) {
                    if( storeImage.src ) {
                        storeImage.src = frontSrc;
                    }   
                    else {
                        storeImage.srcset = frontSrc;
                    }
                }
            } );
        } );            
    });

}, false);