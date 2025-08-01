const CACHE_NAME = 'fruto-cache-v2';
const OFFLINE_URL = '/offline.html'; // debe existir este archivo HTML

const ASSETS_TO_CACHE = [ 
  OFFLINE_URL,  
  '/build/assets/offline-C2TtRujc.js',     
  '/build/assets/offline-BP5HB3ti.css',     
  '/favicon.ico',   
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', 
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' 
]; 
 
self.addEventListener('install', event => {
  console.log('[SW] Instalar');   
  event.waitUntil( 
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(ASSETS_TO_CACHE); 
    }) 
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  console.log('[SW] Activado');
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.map(key => {
          if (key !== CACHE_NAME) return caches.delete(key);
        })
      )
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  if (event.request.mode === 'navigate') {
    // navegación de páginas (ej. recargar área offline)
    event.respondWith(
      fetch(event.request).catch(() => caches.match(OFFLINE_URL))
    );
    return;
  }

  event.respondWith(
    caches.match(event.request).then(response => response || fetch(event.request))
  );
});
