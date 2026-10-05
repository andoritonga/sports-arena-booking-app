const CACHE_NAME = 'sportkuy-pwa-v2.1';
const OFFLINE_URL = 'offline.html';

const PRECACHE_ASSETS = [
  '/',
  'index.php',
  'offline.html',
  'frontend/css/bootstrap.css',
  'frontend/css/bootstrap.min.css',
  'frontend/css/font-awesome.min.css',
  'frontend/css/style.css?v=5.0',
  'frontend/css/modern-custom.css?v=5.0',
  'frontend/css/pwa-app.css?v=1.0',
  'frontend/js/jquery.min.js',
  'frontend/js/bootstrap.min.js',
  'frontend/js/pwa-app.js?v=1.0',
  'frontend/img/icons/icon-192x192.png',
  'frontend/img/icons/icon-512x512.png',
  'frontend/img/icons/apple-touch-icon.png'
];

// Install Event
self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[SW] Precaching app shell & assets');
      return cache.addAll(PRECACHE_ASSETS).catch((err) => {
        console.warn('[SW] Some precache assets failed:', err);
      });
    })
  );
});

// Activate Event
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((name) => {
          if (name !== CACHE_NAME) {
            console.log('[SW] Deleting old cache:', name);
            return caches.delete(name);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
  const request = event.request;

  // Ignore non-GET requests (e.g. POST booking/login)
  if (request.method !== 'GET') {
    return;
  }

  const url = new URL(request.url);

  // Strategy for HTML Page Navigations: Network First with Offline Fallback
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          // If network fetch succeeds, cache a clone for offline
          if (response && response.status === 200) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
          }
          return response;
        })
        .catch(async () => {
          // If offline, check cache for the specific URL
          const cachedResponse = await caches.match(request);
          if (cachedResponse) {
            return cachedResponse;
          }
          // Otherwise fallback to offline.html
          return caches.match(OFFLINE_URL);
        })
    );
    return;
  }

  // Strategy for Static Assets (CSS, JS, Fonts, Images): Cache First with Network Fallback
  if (
    url.pathname.endsWith('.css') ||
    url.pathname.endsWith('.js') ||
    url.pathname.endsWith('.woff') ||
    url.pathname.endsWith('.woff2') ||
    url.pathname.endsWith('.ttf') ||
    url.pathname.endsWith('.png') ||
    url.pathname.endsWith('.jpg') ||
    url.pathname.endsWith('.jpeg') ||
    url.pathname.endsWith('.svg') ||
    url.pathname.endsWith('.gif')
  ) {
    event.respondWith(
      caches.match(request).then((cachedResponse) => {
        if (cachedResponse) {
          // Return cached response immediately, update cache in background
          fetch(request).then((networkResponse) => {
            if (networkResponse && networkResponse.status === 200) {
              caches.open(CACHE_NAME).then((cache) => cache.put(request, networkResponse));
            }
          }).catch(() => {});
          return cachedResponse;
        }

        // Otherwise fetch from network and cache
        return fetch(request).then((networkResponse) => {
          if (networkResponse && networkResponse.status === 200) {
            const copy = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
          }
          return networkResponse;
        });
      })
    );
    return;
  }

  // Default: Network with Cache Fallback
  event.respondWith(
    fetch(request).catch(() => caches.match(request))
  );
});