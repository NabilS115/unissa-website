// Alpine.js optimization for UNISSA website
// Prevents Alpine.js flashing by initializing components properly

document.addEventListener('alpine:init', () => {
    // Add global Alpine store for smooth transitions
    Alpine.store('pageTransition', {
        isNavigating: false,
        
        startNavigation() {
            this.isNavigating = true;
            document.body.style.opacity = '0.9';
        },
        
        endNavigation() {
            this.isNavigating = false;
            document.body.style.opacity = '1';
        }
    });
    
    // Global Alpine magic for smooth page transitions
    Alpine.magic('smoothTransition', () => {
        return (callback) => {
            Alpine.store('pageTransition').startNavigation();
            requestAnimationFrame(() => {
                callback();
                setTimeout(() => {
                    Alpine.store('pageTransition').endNavigation();
                }, 150);
            });
        };
    });
});

// Optimize Alpine.js component initialization
document.addEventListener('alpine:initialized', () => {
    console.log('✅ Alpine.js fully initialized');
    
    // Remove any remaining x-cloak elements
    document.querySelectorAll('[x-cloak]').forEach(el => {
        el.removeAttribute('x-cloak');
    });
    
    // Ensure body is visible
    document.body.classList.add('loaded');
    
    // Add smooth transitions for dynamic content
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1 && node.hasAttribute && node.hasAttribute('x-data')) {
                        // Add fade-in animation for new Alpine components
                        node.style.opacity = '0';
                        requestAnimationFrame(() => {
                            node.style.transition = 'opacity 0.3s ease';
                            node.style.opacity = '1';
                        });
                    }
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});