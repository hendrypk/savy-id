self.addEventListener('install', (event) => {
    console.log('Savy SW: Installed');
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('Savy SW: Activated');
});

self.addEventListener('fetch', (event) => {
});