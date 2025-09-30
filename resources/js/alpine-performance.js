// Alpine.js Performance Optimizations
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