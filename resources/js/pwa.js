// Register Service Worker in production or local development
if ('serviceWorker' in navigator && (window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/sw.js')
      .then((registration) => {
        // Check for updates
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;
          if (newWorker) {
            newWorker.addEventListener('statechange', () => {
              if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                console.log('Nova versão do DuoFin disponível.');
              }
            });
          }
        });
      })
      .catch((error) => {
        console.warn('Registro do ServiceWorker não foi concluído:', error);
      });
  });
}

// Alpine.js components for PWA install prompt and network status
export function registerPwaComponents(Alpine) {
  Alpine.data('pwaInstallPrompt', () => ({
    deferredPrompt: null,
    canInstall: false,
    dismissed: false,

    init() {
      // Check if user already dismissed recently
      const dismissedUntil = localStorage.getItem('duofin_pwa_dismissed_until');
      if (dismissedUntil && Date.now() < Number(dismissedUntil)) {
        this.dismissed = true;
      }

      window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        this.deferredPrompt = e;
        if (!this.dismissed) {
          this.canInstall = true;
        }
      });

      window.addEventListener('appinstalled', () => {
        this.canInstall = false;
        this.deferredPrompt = null;
      });
    },

    async install() {
      if (!this.deferredPrompt) {
        return;
      }
      this.deferredPrompt.prompt();
      const choice = await this.deferredPrompt.userChoice;
      if (choice.outcome === 'accepted') {
        this.canInstall = false;
      }
      this.deferredPrompt = null;
    },

    dismiss() {
      this.canInstall = false;
      this.dismissed = true;
      // Snooze for 7 days
      localStorage.setItem('duofin_pwa_dismissed_until', (Date.now() + 7 * 24 * 60 * 60 * 1000).toString());
    }
  }));

  Alpine.data('networkStatus', () => ({
    isOnline: navigator.onLine,
    showOnlineAlert: false,

    init() {
      window.addEventListener('online', () => {
        this.isOnline = true;
        this.showOnlineAlert = true;
        setTimeout(() => {
          this.showOnlineAlert = false;
        }, 3500);
      });

      window.addEventListener('offline', () => {
        this.isOnline = false;
      });
    }
  }));
}
