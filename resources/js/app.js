/**
 * Modern JavaScript setup with Alpine.js
 * Alpine.js provides reactive, declarative JavaScript without the overhead
 */

import './bootstrap';
import Alpine from 'alpinejs';
import './modal';

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();
