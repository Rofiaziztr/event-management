// Alpine.js Performance Optimizations and Enhanced Animations
// This script contains optimizations for smooth animations and better performance

document.addEventListener('alpine:init', () => {
    // Performance optimizations
    Alpine.store('performance', {
        // Debounce function for search inputs
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // Throttle function for scroll events
        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },
        
        // Lazy loading observer
        createLazyObserver(callback) {
            return new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        callback(entry.target);
                    }
                });
            }, {
                rootMargin: '50px',
                threshold: 0.1
            });
        },
        
        // Add animations registry for tracking and disabling if needed
        animations: {
            active: true,
            registry: new Set(),
            register(id) {
                this.registry.add(id);
                return this.active;
            },
            disable() {
                this.active = false;
            },
            enable() {
                this.active = true;
            }
        }
    });

    // Optimize animations for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    
    Alpine.store('motion', {
        reduced: prefersReducedMotion.matches,
        
        init() {
            prefersReducedMotion.addEventListener('change', (e) => {
                this.reduced = e.matches;
                this.updateAnimations();
            });
        },
        
        updateAnimations() {
            const animatedElements = document.querySelectorAll('[class*="animate-"], [x-transition]');
            animatedElements.forEach(el => {
                if (this.reduced) {
                    el.style.animation = 'none';
                    el.style.transition = 'none';
                } else {
                    el.style.animation = '';
                    el.style.transition = '';
                }
            });
        }
    });

    // Memory management for large lists
    Alpine.data('virtualList', (items = []) => ({
        items: items,
        visibleItems: [],
        itemHeight: 60,
        containerHeight: 400,
        scrollTop: 0,
        
        init() {
            this.updateVisibleItems();
        },
        
        updateVisibleItems() {
            const startIndex = Math.floor(this.scrollTop / this.itemHeight);
            const visibleCount = Math.ceil(this.containerHeight / this.itemHeight);
            const endIndex = Math.min(startIndex + visibleCount + 2, this.items.length);
            
            this.visibleItems = this.items.slice(startIndex, endIndex).map((item, index) => ({
                ...item,
                index: startIndex + index,
                top: (startIndex + index) * this.itemHeight
            }));
        },
        
        onScroll(event) {
            this.scrollTop = event.target.scrollTop;
            this.updateVisibleItems();
        }
    }));

    // Optimized form handling
    Alpine.data('optimizedForm', () => ({
        formData: {},
        isValid: true,
        errors: {},
        
        validateField(field, value, rules = {}) {
            let isFieldValid = true;
            const fieldErrors = [];
            
            if (rules.required && (!value || value.trim() === '')) {
                isFieldValid = false;
                fieldErrors.push(`${field} wajib diisi`);
            }
            
            if (rules.email && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                isFieldValid = false;
                fieldErrors.push(`${field} harus berupa email yang valid`);
            }
            
            if (rules.minLength && value && value.length < rules.minLength) {
                isFieldValid = false;
                fieldErrors.push(`${field} minimal ${rules.minLength} karakter`);
            }
            
            if (fieldErrors.length > 0) {
                this.errors[field] = fieldErrors;
            } else {
                delete this.errors[field];
            }
            
            this.isValid = Object.keys(this.errors).length === 0;
            return isFieldValid;
        }
    }));
});

// Utility functions for better performance
window.AlpineUtils = {
    // Efficient DOM updates
    batchUpdate(updates) {
        requestAnimationFrame(() => {
            updates.forEach(update => update());
        });
    },
    
    // Optimized event handling
    addEventListeners(element, events) {
        events.forEach(({ event, handler, options = {} }) => {
            element.addEventListener(event, handler, {
                passive: true,
                ...options
            });
        });
    },
    
    // Memory-efficient image loading
    lazyLoadImages(container = document) {
        const images = container.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    },
    
    // Smooth scrolling utility
    smoothScrollTo(element, options = {}) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
            ...options
        });
    }
};

// Additional unique animation components
Alpine.data('bounce', () => ({
    init() {
        if (Alpine.store('motion').reduced) return;
        
        this.$el.classList.add('bounce-animation');
        
        if (!document.querySelector('#bounce-keyframes')) {
            const style = document.createElement('style');
            style.id = 'bounce-keyframes';
            style.textContent = `
                .bounce-animation {
                    animation: bounce-in-custom 0.8s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
                    transform-origin: center bottom;
                }
                @keyframes bounce-in-custom {
                    0% { transform: scale(0); }
                    50% { transform: scale(1.1); }
                    70% { transform: scale(0.95); }
                    100% { transform: scale(1); }
                }
            `;
            document.head.appendChild(style);
        }
    }
}));

// 3D hover effect
Alpine.data('hover3D', () => ({
    init() {
        if (Alpine.store('motion').reduced) return;
        
        const card = this.$el;
        const strength = parseInt(card.dataset.strength || 25);
        
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateY = ((x - centerX) / centerX) * strength / 2;
            const rotateX = ((centerY - y) / centerY) * strength / 2;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            card.style.transition = 'all 0.5s ease';
        });
    }
}));

// Pulse border animation
Alpine.data('pulseBorder', (color = 'yellow') => ({
    init() {
        if (Alpine.store('motion').reduced) return;
        
        const colors = {
            yellow: 'rgba(245, 158, 11, 0.5)',
            blue: 'rgba(59, 130, 246, 0.5)',
            green: 'rgba(16, 185, 129, 0.5)',
            red: 'rgba(239, 68, 68, 0.5)'
        };
        
        const borderColor = colors[color] || colors.yellow;
        this.$el.style.setProperty('--pulse-color', borderColor);
        this.$el.classList.add('pulse-border-animation');
        
        if (!document.querySelector('#pulse-border-keyframes')) {
            const style = document.createElement('style');
            style.id = 'pulse-border-keyframes';
            style.textContent = `
                .pulse-border-animation {
                    animation: pulse-border 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }
                @keyframes pulse-border {
                    0%, 100% { box-shadow: 0 0 0 0 var(--pulse-color); }
                    50% { box-shadow: 0 0 0 4px var(--pulse-color); }
                }
            `;
            document.head.appendChild(style);
        }
    }
}));

// Initialize performance optimizations when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Lazy load images
    AlpineUtils.lazyLoadImages();
    
    // Add passive event listeners for better scroll performance
    const scrollElements = document.querySelectorAll('[x-data*="scroll"]');
    scrollElements.forEach(el => {
        el.addEventListener('scroll', (e) => {
            // Scroll handling logic here
        }, { passive: true });
    });
    
    // Optimize font loading
    if ('fonts' in document) {
        document.fonts.ready.then(() => {
            document.body.classList.add('fonts-loaded');
        });
    }
    
    // Add intersection observers for animations
    const animatedElements = document.querySelectorAll('[x-data*="fadeIn"], [x-data*="slideIn"]');
    const animationObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                animationObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });
    
    animatedElements.forEach(el => animationObserver.observe(el));
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AlpineUtils };
}