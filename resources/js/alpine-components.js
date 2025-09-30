// Alpine.js Components for Event Management System
// This file contains reusable components for animations, alerts, modals, and UI behaviors

document.addEventListener('alpine:init', () => {
    // Global state management
    Alpine.store('app', {
        loading: false,
        sidebarOpen: false,
        alerts: [],
        
        // Loading state management
        setLoading(state) {
            this.loading = state;
        },
        
        // Alert system
        addAlert(type, message, duration = 5000) {
            const id = Date.now();
            const alert = { id, type, message, show: true };
            this.alerts.push(alert);
            
            if (duration > 0) {
                setTimeout(() => {
                    this.removeAlert(id);
                }, duration);
            }
            
            return id;
        },
        
        removeAlert(id) {
            const index = this.alerts.findIndex(alert => alert.id === id);
            if (index > -1) {
                this.alerts[index].show = false;
                setTimeout(() => {
                    this.alerts.splice(index, 1);
                }, 300);
            }
        },
        
        // Sidebar management
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        
        closeSidebar() {
            this.sidebarOpen = false;
        }
    });

    // Alert Component
    Alpine.data('alertSystem', () => ({
        init() {
            // Auto-show Laravel session messages
            this.checkSessionMessages();
        },
        
        checkSessionMessages() {
            // Check for Laravel session messages
            const successMessage = document.querySelector('[data-success-message]');
            const errorMessage = document.querySelector('[data-error-message]');
            const warningMessage = document.querySelector('[data-warning-message]');
            const infoMessage = document.querySelector('[data-info-message]');
            
            if (successMessage) {
                this.$store.app.addAlert('success', successMessage.textContent.trim());
                successMessage.remove();
            }
            if (errorMessage) {
                this.$store.app.addAlert('error', errorMessage.textContent.trim());
                errorMessage.remove();
            }
            if (warningMessage) {
                this.$store.app.addAlert('warning', warningMessage.textContent.trim());
                warningMessage.remove();
            }
            if (infoMessage) {
                this.$store.app.addAlert('info', infoMessage.textContent.trim());
                infoMessage.remove();
            }
        }
    }));

    // Form Component with validation and loading states
    Alpine.data('enhancedForm', (options = {}) => ({
        loading: false,
        errors: {},
        
        init() {
            this.options = {
                validateOnSubmit: true,
                showLoader: true,
                autoFocus: true,
                ...options
            };
            
            if (this.options.autoFocus) {
                this.$nextTick(() => {
                    const firstInput = this.$el.querySelector('input, select, textarea');
                    if (firstInput) firstInput.focus();
                });
            }
        },
        
        async submitForm() {
            if (this.options.showLoader) {
                this.loading = true;
                this.$store.app.setLoading(true);
            }
            
            try {
                // Let the form submit normally, but with enhanced UX
                this.$el.submit();
            } catch (error) {
                console.error('Form submission error:', error);
                this.$store.app.addAlert('error', 'Terjadi kesalahan saat mengirim form');
            }
        },
        
        clearError(field) {
            if (this.errors[field]) {
                delete this.errors[field];
            }
        }
    }));

    // Modal Component
    Alpine.data('modal', (initialOpen = false) => ({
        open: initialOpen,
        
        show() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        
        hide() {
            this.open = false;
            document.body.style.overflow = 'auto';
        },
        
        toggle() {
            this.open ? this.hide() : this.show();
        }
    }));

    // Dropdown Component
    Alpine.data('dropdown', () => ({
        open: false,
        
        toggle() {
            this.open = !this.open;
        },
        
        close() {
            this.open = false;
        }
    }));

    // Tab Component
    Alpine.data('tabs', (initialTab = 0) => ({
        activeTab: initialTab,
        
        setTab(index) {
            this.activeTab = index;
        },
        
        isActive(index) {
            return this.activeTab === index;
        }
    }));

    // Data Table Component
    Alpine.data('dataTable', () => ({
        search: '',
        sortBy: '',
        sortDirection: 'asc',
        currentPage: 1,
        itemsPerPage: 10,
        
        sort(column) {
            if (this.sortBy === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDirection = 'asc';
            }
            this.currentPage = 1;
        }
    }));

    // Animation helpers
    Alpine.data('fadeIn', (delay = 0) => ({
        init() {
            setTimeout(() => {
                this.$el.classList.add('animate-fade-in');
            }, delay);
        }
    }));

    Alpine.data('slideIn', (direction = 'left', delay = 0) => ({
        init() {
            setTimeout(() => {
                this.$el.classList.add(`animate-slide-in-${direction}`);
            }, delay);
        }
    }));

    // QR Scanner Component (for scan page)
    Alpine.data('qrScanner', () => ({
        scanning: false,
        result: '',
        error: '',
        
        async startScan() {
            this.scanning = true;
            this.error = '';
            
            try {
                // Integration with html5-qrcode library would go here
                // This is a placeholder for the QR scanning functionality
                console.log('QR Scanner started');
            } catch (error) {
                this.error = 'Tidak dapat mengakses kamera';
                this.scanning = false;
            }
        },
        
        stopScan() {
            this.scanning = false;
        }
    }));

    // Statistics Counter Animation
    Alpine.data('counter', (target, duration = 2000) => ({
        current: 0,
        
        init() {
            this.animateCounter(target, duration);
        },
        
        animateCounter(target, duration) {
            const increment = target / (duration / 16);
            const timer = setInterval(() => {
                this.current += increment;
                if (this.current >= target) {
                    this.current = target;
                    clearInterval(timer);
                }
            }, 16);
        }
    }));

    // Image lazy loading
    Alpine.data('lazyImage', () => ({
        loaded: false,
        error: false,
        
        init() {
            const img = this.$el;
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    this.loadImage();
                    observer.disconnect();
                }
            });
            observer.observe(img);
        },
        
        loadImage() {
            const img = this.$el;
            const src = img.dataset.src;
            
            if (src) {
                img.src = src;
                img.onload = () => {
                    this.loaded = true;
                };
                img.onerror = () => {
                    this.error = true;
                };
            }
        }
    }));

    // Copy to clipboard functionality
    Alpine.data('clipboard', () => ({
        copied: false,
        
        async copy(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.copied = true;
                this.$store.app.addAlert('success', 'Berhasil disalin ke clipboard');
                
                setTimeout(() => {
                    this.copied = false;
                }, 2000);
            } catch (error) {
                this.$store.app.addAlert('error', 'Gagal menyalin ke clipboard');
            }
        }
    }));
});

// CSS Animation Classes (to be added to app.css)
const animationStyles = `
/* Fade animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-20px); }
}

/* Slide animations */
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes slideInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Scale animations */
@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

/* Bounce animation */
@keyframes bounce {
    0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
    40%, 43% { transform: translate3d(0, -30px, 0); }
    70% { transform: translate3d(0, -15px, 0); }
    90% { transform: translate3d(0, -4px, 0); }
}

/* Pulse animation */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Shake animation */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
    20%, 40%, 60%, 80% { transform: translateX(10px); }
}

/* Loading spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Animation utility classes */
.animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
.animate-fade-out { animation: fadeOut 0.3s ease-in forwards; }
.animate-slide-in-left { animation: slideInLeft 0.5s ease-out forwards; }
.animate-slide-in-right { animation: slideInRight 0.5s ease-out forwards; }
.animate-slide-in-up { animation: slideInUp 0.5s ease-out forwards; }
.animate-slide-in-down { animation: slideInDown 0.5s ease-out forwards; }
.animate-scale-in { animation: scaleIn 0.3s ease-out forwards; }
.animate-bounce { animation: bounce 1s ease-in-out; }
.animate-pulse { animation: pulse 2s ease-in-out infinite; }
.animate-shake { animation: shake 0.5s ease-in-out; }
.animate-spin { animation: spin 1s linear infinite; }

/* Hover animations */
.hover-lift { transition: transform 0.3s ease; }
.hover-lift:hover { transform: translateY(-2px); }

.hover-scale { transition: transform 0.3s ease; }
.hover-scale:hover { transform: scale(1.05); }

/* Loading states */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}

/* Responsive utilities */
@media (max-width: 768px) {
    .animate-slide-in-left,
    .animate-slide-in-right {
        animation: slideInUp 0.5s ease-out forwards;
    }
}
`;

// Add styles to document if not already present
if (!document.querySelector('#alpine-animations')) {
    const styleSheet = document.createElement('style');
    styleSheet.id = 'alpine-animations';
    styleSheet.textContent = animationStyles;
    document.head.appendChild(styleSheet);
}