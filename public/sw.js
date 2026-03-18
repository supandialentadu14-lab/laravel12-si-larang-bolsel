const CACHE_NAME = 'silarang-cache-v2';
const OFFLINE_URL = '/offline.html';

const urlsToCache = [
    OFFLINE_URL,
    '/manifest.json',
    '/images/silarang-logo.webp',
    '/images/silarang-logo.png',
    '/images/icons/icon-192x192.png'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // If valid response, clone and cache it for static assets
                if (response && response.status === 200 && response.type === 'basic') {
                    const url = new URL(event.request.url);
                    if (url.pathname.match(/\.(js|css|webp|png|jpg|woff2)$/)) {
                        const responseToCache = response.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                    }
                }
                return response;
            })
            .catch(() => {
                // Fallback to cache
                return caches.match(event.request).then(response => {
                    if (response) return response;

                    // If navigation request fails, show offline page
                    if (event.request.mode === 'navigate') {
                        return caches.match(OFFLINE_URL);
                    }
                });
            })
    );
});
