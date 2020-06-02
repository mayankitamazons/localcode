importScripts('/cache-polyfill.js');



self.addEventListener('install', function(e) {
 e.waitUntil(
   caches.open('koofamilies').then(function(cache) {
     return cache.addAll([
       '/',
       '/index.php',
       '/index.php?homescreen=1',
       '/?homescreen=1',
       '/?utm_source=homescreen',
       '/css/style.css',
       '/js/jquery.min.js',
     ]);
   })
 );
});


self.addEventListener('fetch', function(event) {
  if (event.request.method !== 'GET') {
      return;
  }
  event.respondWith(
    caches.open('koofamilies').then(function(cache) {
      return cache.match(event.request).then(function (response) {
        return response || fetch(event.request).then(function(response) {
          cache.put(event.request, response.clone());
          return response;
        });
      });
    })
  );
});