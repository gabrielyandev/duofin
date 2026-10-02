

import Alpine from 'alpinejs';
import { registerPwaComponents } from './pwa';

window.Alpine = Alpine;

registerPwaComponents(Alpine);

Alpine.start();
