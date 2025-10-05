// Siterians ClubHive - Service Worker (Temporarily Disabled for Debug)
const CACHE_NAME = 'clubhive-v1.0.4-debug';
const OFFLINE_URL = '/offline.html';
const IS_DEVELOPMENT = location.hostname === 'localhost' || location.hostname === '127.0.0.1';

// Files to cache for offline functionality (minimal for development)
const STATIC_CACHE_URLS = IS_DEVELOPMENT ? [
    '/offline.html'
] : [
    '/',
    '/offline.html',
    '/manifest.json',
    '/images/school_logo.png',
    '/images/bg.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/android-icon-192x192.png',
    '/images/icons/apple-icon-180x180.png'
];

// Install event - cache static resources
self.addEventListener('install', (event) => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(async (cache) => {
                console.log('Service Worker: Caching static files');
                
                // Cache files individually to handle missing files gracefully
                const cachePromises = STATIC_CACHE_URLS.map(async (url) => {
                    try {
                        const response = await fetch(url);
                        if (response.ok) {
                            await cache.put(url, response);
                            console.log(`Service Worker: Cached ${url}`);
                        } else {
                            console.warn(`Service Worker: Failed to cache ${url} - ${response.status}`);
                        }
                    } catch (error) {
                        console.warn(`Service Worker: Failed to fetch ${url}`, error);
                    }
                });
                
                await Promise.allSettled(cachePromises);
                console.log('Service Worker: Initial caching completed');
            })
            .catch((error) => {
                console.error('Service Worker: Cache setup failed', error);
            })
    );
    
    // Force the waiting service worker to become the active service worker
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Service Worker: Deleting old cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    
    // Ensure the service worker takes control immediately
    self.clients.claim();
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }
    
    // Skip requests to external domains
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }
    
    // In development, always fetch from network first
    if (IS_DEVELOPMENT) {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    return response;
                })
                .catch(() => {
                    // Only use cache as fallback in development
                    return caches.match(event.request) || caches.match(OFFLINE_URL);
                })
        );
        return;
    }
    
    // Production caching strategy
    event.respondWith(
        caches.match(event.request)
            .then((cachedResponse) => {
                // Return cached version if available
                if (cachedResponse) {
                    return cachedResponse;
                }
                
                // Otherwise, fetch from network
                return fetch(event.request)
                    .then((response) => {
                        // Don't cache non-successful responses
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        
                        // Clone the response for caching
                        const responseToCache = response.clone();
                        
                        // Cache dynamic content (with size limit)
                        caches.open(CACHE_NAME)
                            .then((cache) => {
                                cache.put(event.request, responseToCache);
                            });
                        
                        return response;
                    })
                    .catch(() => {
                        // If network fails, show offline page for navigation requests
                        if (event.request.mode === 'navigate') {
                            return caches.match(OFFLINE_URL);
                        }
                        
                        // For other requests, return a basic offline response
                        return new Response('Offline - Content not available', {
                            status: 503,
                            statusText: 'Service Unavailable',
                            headers: new Headers({
                                'Content-Type': 'text/plain'
                            })
                        });
                    });
            })
    );
});

// Background sync for form submissions (future enhancement)
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        console.log('Service Worker: Background sync triggered');
        // Handle background sync for offline form submissions
    }
});

// Push notification handling (future enhancement)
self.addEventListener('push', (event) => {
    if (event.data) {
        const data = event.data.json();
        console.log('Service Worker: Push notification received', data);
        
        const options = {
            body: data.body,
            icon: '/images/icons/icon-192x192.png',
            badge: '/images/icons/icon-72x72.png',
            vibrate: [200, 100, 200],
            data: {
                url: data.url || '/'
            },
            actions: [
                {
                    action: 'open',
                    title: 'Open App'
                },
                {
                    action: 'close',
                    title: 'Close'
                }
            ]
        };
        
        event.waitUntil(
            self.registration.showNotification(data.title || 'ClubHive Notification', options)
        );
    }
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    if (event.action === 'open' || !event.action) {
        const url = event.notification.data.url || '/';
        
        event.waitUntil(
            clients.matchAll({ type: 'window' }).then((clientList) => {
                // Check if app is already open
                for (const client of clientList) {
                    if (client.url === url && 'focus' in client) {
                        return client.focus();
                    }
                }
                
                // Open new window if app is not open
                if (clients.openWindow) {
                    return clients.openWindow(url);
                }
            })
        );
    }
});
