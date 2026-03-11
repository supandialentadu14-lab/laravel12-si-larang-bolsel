const CACHE_NAME = 'silarang-cache-v1';
const urlsToCache = [
  '/',
  '/offline.html',
  // you can add more static CSS/JS if you want
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
        .then(cache => {
            return cache.addAll(urlsToCache);
        })
    );
});

self.addEventListener('fetch', event => {
    // Basic network-first strategy for dynamic content.
    event.respondWith(
        fetch(event.request)
        .catch(() => {
            return caches.match(event.request).then(response => {
                if (response) {
                    return response;
                } else if (event.request.mode === 'navigate') {
                    // fall back to offline page if available
                    return caches.match('/offline.html');
                }
            });
        })
    );
});

self.addEventListener('activate', event => {
    const cacheWhiteList = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (!cacheWhiteList.includes(cacheName)) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
