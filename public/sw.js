// Service Worker for MAM Tours PWA - DISABLED FOR DEVELOPMENT
// This file is intentionally minimal to avoid caching issues during development

const CACHE_NAME = 'mam-tours-v1';

// Install event - skip precaching
self.addEventListener('install', (event) => {
  console.log('[SW] Install event - skipping precache');
  self.skipWaiting();
});

// Activate event - delete all caches
self.addEventListener('activate', (event) => {
  console.log('[SW] Activate event - clearing all caches');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          console.log('[SW] Deleting cache:', cacheName);
          return caches.delete(cacheName);
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch event - ALWAYS fetch from network, never cache
self.addEventListener('fetch', (event) => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') return;

  // Skip chrome extensions and other non-http requests
  if (!event.request.url.startsWith('http')) return;

  // Always fetch from network, never use cache
  event.respondWith(
    fetch(event.request)
      .then((response) => {
        return response;
      })
      .catch(() => {
        // Network failed, return offline page for navigation requests
        if (event.request.mode === 'navigate') {
          return caches.match('/offline.html');
        }
      })
  );
});
