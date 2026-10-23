/**
 * SportKuy PWA Native App Engine
 * Manages Service Worker, Install Prompts, Bottom Nav, and App Shell Interactions
 */

(function () {
  'use strict';

  let deferredPrompt = null;
  const STORAGE_KEY_PROMPT_DISMISSED = 'sportkuy_pwa_dismissed';

  // 1. SERVICE WORKER REGISTRATION
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker
        .register('sw.js')
        .then((registration) => {
          console.log('[PWA] ServiceWorker registered with scope:', registration.scope);
          
          // Check for worker updates
          registration.addEventListener('updatefound', () => {
            const newWorker = registration.installing;
            newWorker.addEventListener('statechange', () => {
              if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                console.log('[PWA] New version available, reloading...');
              }
            });
          });
        })
        .catch((error) => {
          console.error('[PWA] ServiceWorker registration failed:', error);
        });
    });
  }

  // 2. CHECK IF RUNNING AS INSTALLED PWA
  function isStandalone() {
    return (
      window.matchMedia('(display-mode: standalone)').matches ||
      window.navigator.standalone === true ||
      document.referrer.includes('android-app://')
    );
  }

  // 3. LISTEN FOR PWA INSTALL PROMPT
  window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    deferredPrompt = e;
    console.log('[PWA] beforeinstallprompt captured');

    // Show header install button
    const headerInstallBtn = document.getElementById('header-install-btn');
    if (headerInstallBtn) {
      headerInstallBtn.style.display = 'inline-flex';
    }

    // Show bottom floating prompt if not dismissed recently and not standalone
    const lastDismissed = localStorage.getItem(STORAGE_KEY_PROMPT_DISMISSED);
    const now = Date.now();
    const twentyFourHours = 24 * 60 * 60 * 1000;

    if (!isStandalone() && (!lastDismissed || now - parseInt(lastDismissed, 10) > twentyFourHours)) {
      setTimeout(() => {
        const installPromptEl = document.getElementById('pwa-install-prompt');
        if (installPromptEl) {
          installPromptEl.style.display = 'block';
        }
      }, 1500);
    }
  });

  // App installed event
  window.addEventListener('appinstalled', () => {
    console.log('[PWA] Application successfully installed');
    deferredPrompt = null;
    const installPromptEl = document.getElementById('pwa-install-prompt');
    if (installPromptEl) installPromptEl.style.display = 'none';
    const headerInstallBtn = document.getElementById('header-install-btn');
    if (headerInstallBtn) headerInstallBtn.style.display = 'none';
    showToast('Aplikasi SportKuy berhasil terpasang di perangkat Anda!', 'online');
  });

  // 4. TRIGGER INSTALL ACTION
  window.triggerPwaInstall = function () {
    vibrate(20);
    if (!deferredPrompt) {
      alert('Untuk memasang aplikasi, buka menu browser Anda (titik tiga atau tombol bagikan) lalu pilih "Tambahkan ke Layar Utama" / "Install App".');
      return;
    }

    deferredPrompt.prompt();
    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === 'accepted') {
        console.log('[PWA] User accepted the install prompt');
      } else {
        console.log('[PWA] User dismissed the install prompt');
      }
      deferredPrompt = null;
      const installPromptEl = document.getElementById('pwa-install-prompt');
      if (installPromptEl) installPromptEl.style.display = 'none';
    });
  };

  // 5. DISMISS INSTALL PROMPT
  window.dismissPwaInstall = function () {
    vibrate(10);
    const installPromptEl = document.getElementById('pwa-install-prompt');
    if (installPromptEl) {
      installPromptEl.style.display = 'none';
      localStorage.setItem(STORAGE_KEY_PROMPT_DISMISSED, Date.now().toString());
    }
  };

  // 6. BOTTOM SHEET CATEGORIES
  window.openCategorySheet = function (e) {
    if (e) e.preventDefault();
    vibrate(15);
    const overlay = document.getElementById('app-category-sheet-overlay');
    if (overlay) {
      overlay.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  };

  window.closeCategorySheet = function () {
    vibrate(10);
    const overlay = document.getElementById('app-category-sheet-overlay');
    if (overlay) {
      overlay.classList.remove('show');
      document.body.style.overflow = '';
    }
  };

  // 7. ONLINE / OFFLINE TOAST NOTIFICATIONS
  function showToast(message, type) {
    let toast = document.getElementById('app-connection-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'app-connection-toast';
      toast.className = 'app-connection-toast';
      document.body.appendChild(toast);
    }

    toast.className = 'app-connection-toast ' + (type || '');
    toast.innerHTML = `<i class="fa ${type === 'offline' ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i> <span>${message}</span>`;
    toast.classList.add('show');

    setTimeout(() => {
      toast.classList.remove('show');
    }, 3500);
  }

  window.addEventListener('offline', () => {
    showToast('Koneksi internet terputus. Mode offline aktif.', 'offline');
    vibrate(50);
  });

  window.addEventListener('online', () => {
    showToast('Koneksi internet terhubung kembali.', 'online');
    vibrate(20);
  });

  // 8. HAPTIC VIBRATION HELPER
  function vibrate(ms) {
    if ('vibrate' in navigator) {
      try {
        navigator.vibrate(ms);
      } catch (e) {}
    }
  }

  // Close bottom sheet when tapping backdrop
  document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('app-category-sheet-overlay');
    if (overlay) {
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
          closeCategorySheet();
        }
      });
    }

    // Attach click vibration to tab bar items
    document.querySelectorAll('.app-nav-item').forEach((item) => {
      item.addEventListener('click', () => vibrate(15));
    });
  });

})();
