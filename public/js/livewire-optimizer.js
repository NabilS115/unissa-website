// Livewire navigation optimization for UNISSA website
// Prevents flashing during Livewire navigation

document.addEventListener('DOMContentLoaded', function() {
    // Handle Livewire navigation events
    document.addEventListener('livewire:navigate', function() {
        console.log('🔄 Livewire navigation started');
        document.body.style.opacity = '0.9';
        document.body.style.transition = 'opacity 0.2s ease';
    });
    
    document.addEventListener('livewire:navigated', function() {
        console.log('✅ Livewire navigation completed');
        
        // Reset opacity
        document.body.style.opacity = '1';
        
        // Re-initialize any components that might have been replaced
        if (window.Alpine) {
            Alpine.initTree(document.body);
        }
        
        // Re-run any initialization scripts
        if (typeof initializePageComponents === 'function') {
            initializePageComponents();
        }
    });
    
    // Handle Livewire loading states
    document.addEventListener('livewire:loading', function() {
        document.body.classList.add('livewire-loading');
    });
    
    document.addEventListener('livewire:loaded', function() {
        document.body.classList.remove('livewire-loading');
    });
    
    // Preload critical resources on hover (for better perceived performance)
    document.addEventListener('mouseover', function(e) {
        const link = e.target.closest('a[wire\\:navigate]');
        if (link && !link.dataset.preloaded) {
            link.dataset.preloaded = 'true';
            
            // Create invisible link to trigger browser preload
            const preloadLink = document.createElement('link');
            preloadLink.rel = 'prefetch';
            preloadLink.href = link.href;
            document.head.appendChild(preloadLink);
        }
    });
});

// Global function to initialize page-specific components
window.initializePageComponents = function() {
    // Re-initialize any page-specific JavaScript
    if (typeof window.refreshAllCarousels === 'function') {
        setTimeout(window.refreshAllCarousels, 100);
    }
    
    // Re-initialize any scroll positions
    if (sessionStorage.getItem('scrollPosition')) {
        const position = parseInt(sessionStorage.getItem('scrollPosition'));
        window.scrollTo(0, position);
        sessionStorage.removeItem('scrollPosition');
    }
    
    // Re-initialize error handlers
    if (window.ErrorHandler && typeof window.ErrorHandler.init === 'function') {
        window.ErrorHandler.init();
    }
};