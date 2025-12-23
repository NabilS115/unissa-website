// UNISSA Website Performance Optimizer
// Additional performance optimizations for faster loading

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Performance optimizer initialized');
    
    // 1. Optimize image loading with Intersection Observer
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    }, {
        rootMargin: '50px 0px'
    });
    
    // Observe images with data-src attribute
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
    
    // 2. Preload critical resources
    function preloadCriticalResources() {
        const criticalImages = [
            '/images/tijarah-logo.png',
            '/images/unissa-logo.png'
        ];
        
        criticalImages.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }
    
    // 3. Optimize font loading
    function optimizeFonts() {
        if ('fonts' in document) {
            document.fonts.ready.then(() => {
                console.log('✅ Fonts loaded');
                document.body.classList.add('fonts-loaded');
            });
        }
    }
    
    // 4. Reduce layout shifts with image placeholders
    function addImagePlaceholders() {
        document.querySelectorAll('img:not([width]):not([height])').forEach(img => {
            if (img.complete) return;
            
            // Add loading placeholder
            img.style.backgroundColor = '#f3f4f6';
            img.style.minHeight = '100px';
            
            img.addEventListener('load', function() {
                img.style.backgroundColor = 'transparent';
                img.style.minHeight = 'auto';
            }, { once: true });
        });
    }
    
    // 5. Optimize scroll performance
    let ticking = false;
    function optimizeScroll() {
        if (!ticking) {
            requestAnimationFrame(() => {
                // Throttled scroll optimizations can go here
                ticking = false;
            });
            ticking = true;
        }
    }
    
    // 6. Cache DOM queries
    const cache = new Map();
    window.optimizedQuery = function(selector) {
        if (!cache.has(selector)) {
            cache.set(selector, document.querySelector(selector));
        }
        return cache.get(selector);
    };
    
    // 7. Prefetch next page resources on hover
    function setupPrefetching() {
        let prefetchTimer;
        
        document.addEventListener('mouseover', function(e) {
            const link = e.target.closest('a[href]');
            if (!link || !link.href || link.href.startsWith('javascript:')) return;
            
            clearTimeout(prefetchTimer);
            prefetchTimer = setTimeout(() => {
                const prefetchLink = document.createElement('link');
                prefetchLink.rel = 'prefetch';
                prefetchLink.href = link.href;
                document.head.appendChild(prefetchLink);
            }, 100);
        });
    }
    
    // 8. Optimize Alpine.js performance
    function optimizeAlpine() {
        // Wait for Alpine to be available
        document.addEventListener('alpine:init', () => {
            // Add performance-focused Alpine stores
            if (window.Alpine) {
                Alpine.store('performance', {
                    imageLoadCount: 0,
                    incrementImageLoad() {
                        this.imageLoadCount++;
                        if (this.imageLoadCount % 10 === 0) {
                            console.log(`📊 Loaded ${this.imageLoadCount} images`);
                        }
                    }
                });
            }
        });
    }
    
    // Initialize optimizations
    preloadCriticalResources();
    optimizeFonts();
    addImagePlaceholders();
    setupPrefetching();
    optimizeAlpine();
    
    // Add scroll optimization with throttling
    window.addEventListener('scroll', optimizeScroll, { passive: true });
    
    // Performance monitoring
    if ('performance' in window) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                const timing = performance.timing;
                const loadTime = timing.loadEventEnd - timing.navigationStart;
                console.log(`⚡ Page load time: ${loadTime}ms`);
            }, 100);
        });
    }
});

// Global performance utilities
window.performanceUtils = {
    // Debounce function for expensive operations
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
    
    // Image preloader
    preloadImages(urls) {
        urls.forEach(url => {
            const img = new Image();
            img.src = url;
        });
    }
};