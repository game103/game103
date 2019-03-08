/**
 * Service worker to serve Game 103 while offline
 * This must be stored in the root directory
 */

// Our two cache stores: one for precache (always cached)
// and one to be dynamically updated as the user browsers
// the site more and more
var precache = "game103-precache";
var runtime = "game103-runtime";


// The urls to precache during installation
var precacheUrls = [
  "/" // always cache the homepage
];

// Install handler
// This will run on PWA installation and make sure the precache is populated
// "Installation is attempted when the downloaded file (fetched every 24 hours at least) is found to be new — either different to an existing service worker (byte-wise compared), or the first service worker encountered for this page/site."
// If there is no current PWA, installation will immediately move to activation
// If there is, activation will be attempted after the page is closed (you must actually close the tab)
// https://developers.google.com/web/fundamentals/primers/service-workers/lifecycle#waiting
// https://stackoverflow.com/questions/41000874/service-worker-expiration
// https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API
// Basically "Downloads while running, but restart to finish installing updates"
self.addEventListener("install", function(event) {
  // Once the promise below is finished, the browser will know we are done with installation
  event.waitUntil(
    caches.open(precache)
      .then( function(cache) { return cache.addAll(precacheUrls); } )
  );
});

// Fetch handler
// Takes care of intercepting requests along with
// fetching data for pages and storing them in the
// dynamic cache
self.addEventListener('fetch', function(event) {
  // Don't cache GA requests
  event.respondWith(
    // Note how it is looking in any cache for a match.
    // This is why we must clear out the old caches on update.
    caches.match(event.request).then( function(cachedResponse) {
      // We are actually going to fetch from the network each time.
      // If we fail, we are going to simply going to return the cached response.
      return caches.open(runtime).then( function(cache) {
        return fetch(event.request).then( function(response) {
          // Put a copy of the response in the runtime cache.
          return cache.put(event.request, response.clone()).then( function() {
            return response;
          });
        })
        .catch( function(error) { console.log("Failed to fetch: " + event.request.url + " -- " + error); return cachedResponse; } );
      });
    })
  );
});