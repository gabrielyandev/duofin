

import Alpine from 'alpinejs';
import { registerPwaComponents } from './pwa';

window.Alpine = Alpine;

// Global Haptic Feedback Engine for touch and mobile devices
window.haptic = function(type = 'light') {
  if (typeof navigator !== 'undefined' && 'vibrate' in navigator) {
    try {
      switch (type) {
        case 'light':
          navigator.vibrate(15);
          break;
        case 'medium':
          navigator.vibrate(25);
          break;
        case 'heavy':
          navigator.vibrate(40);
          break;
        case 'success':
          navigator.vibrate([15, 30, 25]);
          break;
        case 'warning':
        case 'error':
          navigator.vibrate([40, 60, 40]);
          break;
        default:
          navigator.vibrate(15);
      }
    } catch (e) {
      // Ignored if browser restricts vibration
    }
  }
};

// Automatically provide pleasant subtle haptic feedback on interactive taps
if (typeof document !== 'undefined') {
  document.addEventListener('click', (e) => {
    const target = e.target.closest('button, a[role="button"], input[type="submit"], input[type="button"], label');
    if (target && !target.dataset.noHaptic) {
      window.haptic('light');
    }
  }, { passive: true });
}

registerPwaComponents(Alpine);

Alpine.start();
