/**
 * Simple Modal System - Vanilla JavaScript
 * No dependencies on Alpine.js events
 */

class ModalManager {
    constructor() {
        this.modals = new Map();
        this.init();
    }

    init() {
        console.log('ModalManager: Initializing...');
        
        // Find all modals and register them
        const modalElements = document.querySelectorAll('[data-modal]');
        console.log('ModalManager: Found', modalElements.length, 'modals');
        
        modalElements.forEach(modal => {
            const id = modal.getAttribute('data-modal');
            console.log('ModalManager: Registering modal:', id);
            this.modals.set(id, modal);
            
            // Set initial state
            modal.style.display = 'none';
            
            // Close button handlers
            modal.querySelectorAll('[data-modal-close]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('ModalManager: Close button clicked for:', id);
                    this.close(id);
                });
            });
        });

        // Backdrop click to close
        document.querySelectorAll('[data-modal]').forEach(modal => {
            const backdrop = modal.querySelector('[data-modal-backdrop]');
            if (backdrop) {
                backdrop.addEventListener('click', (e) => {
                    if (e.target === backdrop) {
                        const id = modal.getAttribute('data-modal');
                        console.log('ModalManager: Backdrop clicked for:', id);
                        this.close(id);
                    }
                });
            }
        });

        // Global event listeners for opening/closing
        document.addEventListener('click', (e) => {
            const openBtn = e.target.closest('[data-modal-open]');
            if (openBtn) {
                e.preventDefault();
                e.stopPropagation();
                const id = openBtn.getAttribute('data-modal-open');
                console.log('ModalManager: Open button clicked for:', id);
                this.open(id);
            }
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                console.log('ModalManager: ESC pressed, closing all modals');
                this.closeAll();
            }
        });
        
        console.log('ModalManager: Initialization complete');
    }

    open(id) {
        console.log('ModalManager: Opening modal:', id);
        const modal = this.modals.get(id);
        if (modal) {
            console.log('ModalManager: Modal found, showing...');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // Trigger custom event
            window.dispatchEvent(new CustomEvent('modal-opened', { detail: { id } }));
            console.log('ModalManager: Modal opened successfully');
        } else {
            console.error('ModalManager: Modal not found:', id);
            console.log('ModalManager: Available modals:', Array.from(this.modals.keys()));
        }
    }

    close(id) {
        const modal = this.modals.get(id);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            // Trigger custom event
            window.dispatchEvent(new CustomEvent('modal-closed', { detail: { id } }));
        }
    }

    closeAll() {
        this.modals.forEach((modal, id) => {
            this.close(id);
        });
    }

    toggle(id) {
        const modal = this.modals.get(id);
        if (modal) {
            if (modal.style.display === 'none' || !modal.style.display) {
                this.open(id);
            } else {
                this.close(id);
            }
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.modalManager = new ModalManager();
    });
} else {
    window.modalManager = new ModalManager();
}

// Also expose simple functions for easy access
window.openModal = (id) => window.modalManager?.open(id);
window.closeModal = (id) => window.modalManager?.close(id);
window.toggleModal = (id) => window.modalManager?.toggle(id);
