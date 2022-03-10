const CACHE_NAME = "pwa-v1";
var urlsToCache = [
  "/",
  "header.php",
  "footer.php",
  "koneksi.php",
  "frontend/css/bootstrap.min.css",
  "frontend/css/font-awesome.min.css",
  "frontend/css/nouislider.min.css",
  "frontend/js/bootstrap.min.js",
  "frontend/js/jquery.min.js",
  "frontend/js/jquery.zoom.min.js",
  "frontend/js/main.js",
  "frontend/js/nouislider.min.js",
  "frontend/js/slick.min.js",
];

 
self.addEventListener("install", function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function(cache) {
      return cache.addAll(urlsToCache);
    })
  );
});

self.addEventListener("fetch", function(event) {
    event.respondWith(
      caches
        .match(event.request, { cacheName: CACHE_NAME })
        .then(function(response) {
          if (response) {
            console.log("ServiceWorker: Gunakan aset dari cache: ", response.url);
            return response;
          }
   
          console.log(
            "ServiceWorker: Memuat aset dari server: ",
            event.request.url
          );
          return fetch(event.request);
        })
    );
  });

  self.addEventListener("activate", function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
          return Promise.all(
            cacheNames.map(function(cacheName) {
              if (cacheName !== CACHE_NAME && cacheName.startsWith("pwa-")) {
                return caches.delete(cacheName);
              }
            })
          );
        })
      );
  });