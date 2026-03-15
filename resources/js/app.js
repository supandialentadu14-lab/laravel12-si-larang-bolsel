import './bootstrap';

// Register Service Worker for SI-LARANG PWA
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => {
                console.log('SI-LARANG PWA: Service Worker Aktif', reg.scope);
            })
            .catch(err => {
                console.error('SI-LARANG PWA: Gagal Register', err);
            });
    });
}
