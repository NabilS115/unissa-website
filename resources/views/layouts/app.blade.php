<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Unissa Cafe')</title>
    <meta name="theme-color" content="#0d9488">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Unissa Cafe">
    <meta name="msapplication-navbutton-color" content="#fdfdfc">
    <meta name="apple-mobile-web-app-status-bar-style" content="#fdfdfc">
    
    <!-- Cache optimization meta tags -->
    <meta http-equiv="Cache-Control" content="public, max-age=31536000">
    <meta http-equiv="Expires" content="Thu, 31 Dec 2026 23:59:59 GMT">

    @php
        // Simple, clean favicon system
        $isUnissaCafe = str_contains(request()->fullUrl(), 'unissa-cafe') || 
                   str_contains(request()->path(), 'unissa-cafe') ||
                   str_contains(request()->path(), 'product/') ||
                   str_contains(request()->path(), 'products/') ||
                   str_contains(request()->path(), 'cart') ||
                   str_contains(request()->path(), 'orders');
        $faviconFile = $isUnissaCafe ? 'unissa-favicon.ico' : 'tijarahco_sdn_bhd_logo.ico';
        $brandContext = $isUnissaCafe ? 'UNISSA' : 'TIJARAH';
    @endphp

    <!-- Clean favicon implementation -->
    <link rel="icon" href="/{{ $faviconFile }}?v={{ time() }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="/{{ $faviconFile }}?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="/{{ $faviconFile }}?v={{ time() }}">

    {{-- Tailwind is built with Vite or npm for all environments. CDN is not used. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- DNS prefetch for external resources -->
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    
    <!-- Preload critical resources for better caching -->
    <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style">
    <link rel="preload" href="{{ Vite::asset('resources/js/app.js') }}" as="script">
    <link rel="preload" href="/js/performance-optimizer.js" as="script">
    <link rel="preload" href="/js/alpine-optimizer.js" as="script">
    
    <!-- Global Error Handler -->
    <script src="/js/error-handler.js"></script>
    <!-- Performance Optimizer -->
    <script src="/js/performance-optimizer.js"></script>
    <!-- Alpine.js Optimizer -->
    <script src="/js/alpine-optimizer.js"></script>
    <!-- Livewire Navigation Optimizer -->
    <script src="/js/livewire-optimizer.js"></script>
    <!-- Cropper.js CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <style>
        /* Critical styles to prevent FOUC and layout shifts */
        html, body { 
            background-color: #fdfdfc; 
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, sans-serif;
        }
        
        /* Table styling for CKEditor content */
        .text-gray-700 table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d1d5db;
            margin-bottom: 1rem;
        }
        .text-gray-700 table th {
            border: 1px solid #d1d5db;
            background-color: #f3f4f6;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
        }
        .text-gray-700 table td {
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
        }
        .text-gray-700 table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-gray-700 table tr:hover {
            background-color: #f3f4f6;
        }
        
        /* Prevent full-screen overlays from briefly displaying before JS/CSS runs */
        [data-initial-hidden] { display: none !important; }
        [x-cloak] { display: none !important; }
        
        /* Critical layout structure to prevent shifts */
        .header-fallback {
            min-height: 56px;
            background-color: #0d9488;
            display: flex;
            align-items: center;
        }
        
        /* Mobile header adjustments */
        @media (max-width: 768px) {
            .header-fallback {
                min-height: 48px;
                padding: 0.5rem 1rem;
            }
        }
        
        /* Skeleton loading for smooth transitions */
        .content-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Livewire loading states */
        .livewire-loading {
            cursor: wait;
        }
        
        .livewire-loading * {
            pointer-events: none;
        }
        
        /* Smooth page transitions - but don't conflict with Alpine */
        body {
            background-color: #fdfdfc;
        }
        
        /* Fix for Alpine.js flashing */
        [x-cloak] { display: none !important; }
        
        /* Fix for Alpine.js flashing */
        [x-cloak] { display: none !important; }
        
        /* Ensure Alpine components become visible once initialized */
        [x-data] {
            opacity: 1 !important;
            display: block !important;
        }
        
        /* Always show alpine-component class immediately */
        .alpine-component {
            opacity: 1 !important;
        }

        /* Fallback styles in case Tailwind doesn't load */
        .header-fallback {
            width: 100%;
            background-color: #0d9488;
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logo-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-list {
            display: flex;
            gap: 1rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-link {
            color: white;
            text-decoration: none;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #fdfdfc;
            color: #1b1b18;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Alpine.js x-cloak directive */
        [x-cloak] {
            display: none !important;
        }
        
        /* Mobile-specific CSS for viewport and overflow */
        @media (max-width: 768px) {
            html, body {
                overflow-x: hidden;
                max-width: 100vw;
                margin: 0;
                padding: 0;
            }
            
            body {
                position: relative;
            }
            
            /* Fix container spacing and alignment */
            .container, .max-w-7xl, .max-w-6xl, .max-w-5xl, .max-w-4xl {
                max-width: 100vw;
                overflow-x: hidden;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Content area spacing */
            main, .main-content, [role="main"] {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                margin: 0 !important;
            }
            
            /* Enhanced image sizing for mobile */
            img {
                max-width: 100%;
                height: auto;
                object-fit: cover;
            }
            
            /* Product card images - prevent stretching */
            .food-card img, .merch-card img {
                width: 100% !important;
                height: 200px !important;
                object-fit: cover !important;
                object-position: center !important;
            }
            
            /* Grid layout improvements for mobile */
            .grid-cols-1.sm\\:grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4 {
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
                gap: 1rem !important;
            }
            
            .grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
                gap: 1rem !important;
            }
            
            /* Prevent horizontal scrolling on mobile */
            * {
                max-width: 100vw;
                box-sizing: border-box;
            }
            
            /* Mobile card adjustments */
            .food-card, .merch-card {
                max-width: 100% !important;
                margin: 0 !important;
            }
            
            /* Mobile padding adjustments */
            .px-8 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .px-6 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            /* Mobile gap adjustments */
            .gap-8 {
                gap: 1rem !important;
            }
            
            .gap-6 {
                gap: 1rem !important;
            }
            
            /* Mobile form improvements */
            input, select, textarea {
                font-size: 16px; /* Prevents zoom on iOS */
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }
            
            /* Touch targets should be at least 44px */
            button, .btn, a[role="button"] {
                min-height: 44px;
                min-width: 44px;
            }
            
            /* Banner image mobile fixes */
            .hero-banner img, [class*="banner"] img {
                height: 300px !important;
                width: 100% !important;
                object-fit: cover !important;
                object-position: center !important;
            }
            
            /* About section image mobile fixes */
            .h-64 img {
                height: 200px !important;
                width: 100% !important;
                object-fit: cover !important;
            }
            
            /* Aspect ratio fixes for mobile */
            .aspect-video, .aspect-square {
                position: relative;
                overflow: hidden;
            }
            
            .aspect-video img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            /* Text adjustments for mobile */
            .text-5xl {
                font-size: 2.5rem !important;
            }
            
            .text-4xl {
                font-size: 2rem !important;
            }
            
            .text-3xl {
                font-size: 1.875rem !important;
            }
            
            /* Overall mobile layout fixes */
            body {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            
            /* Section spacing normalization */
            section {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            
            /* Full-width sections (like hero) */
            .w-full {
                max-width: 100vw !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            /* Hero section specific fixes */
            .h-80 {
                height: 280px !important;
                margin-bottom: 1.5rem !important;
            }
            
            /* Remove excessive container constraints */
            .max-w-4xl, .max-w-5xl, .max-w-6xl {
                max-width: 100% !important;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            /* Fix mobile menu positioning and animations */
            #mobile-menu {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 9998;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                background-color: rgba(255, 255, 255, 0.1);
            }
            
            #mobile-menu-panel {
                transition: transform 0.3s ease-in-out;
                transform: translateX(100%);
                height: 100vh;
                max-height: 100vh;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.3);
            }
            
            #mobile-menu.show #mobile-menu-panel {
                transform: translateX(0);
            }
            
            /* Ensure mobile menu content doesn't overflow */
            #mobile-menu-panel .overflow-y-auto {
                max-height: calc(100vh - 80px); /* Account for header */
                scrollbar-width: thin;
            }
            
            /* CSS-only close button fallback */
            #mobile-close-btn:active {
                transform: scale(0.9);
            }
            
            /* Make close button more prominent and easier to click */
            #mobile-close-btn {
                min-width: 44px !important;
                min-height: 44px !important;
                cursor: pointer;
                user-select: none;
                -webkit-tap-highlight-color: transparent;
            }
            
            /* Mobile menu button should be above content */
            #mobile-menu-btn {
                z-index: 9999;
                position: relative;
            }
        }
        .main-content {
            flex: 1 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            padding: 1.5rem;
        }
        .food-card {
            max-width: 320px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .food-image {
            width: 100%;
            height: 192px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
        }
        .pizza-bg { background: linear-gradient(to bottom right, #f87171, #fbbf24); }
        .salad-bg { background: linear-gradient(to bottom right, #4ade80, #16a34a); }
        .burger-bg { background: linear-gradient(to bottom right, #fb923c, #ef4444); }
        .card-content {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .card-description {
            color: #374151;
            line-height: 1.5;
        }
        .tags-section {
            padding: 0 1.5rem 1rem 1.5rem;
        }
        .tag {
            display: inline-block;
            background-color: #0d9488;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        .footer-section {
            width: 100%;
            background-color: #0d9488;
            color: white;
            padding: 1rem;
            text-align: center;
        }
    </style>
    @yield('head')
</head>
<body class="min-h-screen flex flex-col">
    @include('components.header')
    <main class="flex-1 w-full">
        @yield('content')
    </main>
    @include('components.footer')
    @stack('scripts')
    {{-- Alpine.js should only be included once. --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Immediate cart count update function fallback -->
    <script>
    // Provide immediate updateCartCount function before header.js loads
    window.updateCartCount = window.updateCartCount || function(newCount) {
        console.log('Fallback updateCartCount called with:', newCount);
        const cartBadge = document.getElementById('cart-count');
        const mobileCartBadge = document.getElementById('cart-count-mobile');
        
        if (cartBadge) {
            cartBadge.textContent = newCount || 0;
            cartBadge.style.display = (newCount && newCount > 0) ? 'flex' : 'none';
            console.log('Fallback updated desktop cart badge to:', newCount);
        }
        if (mobileCartBadge) {
            mobileCartBadge.textContent = newCount || 0;
            mobileCartBadge.style.display = (newCount && newCount > 0) ? 'flex' : 'none';
            console.log('Fallback updated mobile cart badge to:', newCount);
        }
    };
    </script>
    
    <!-- Externalized: layout overlay unguard logic -->
    <script src="/js/layout-unguard.js"></script>
    
    <!-- Optimized navigation handler -->
    <script>
        // Prevent page flash during navigation
        let navigationInProgress = false;
        
        // Faster page showing and Alpine.js initialization
        function initializePage() {
            // Remove x-cloak from all elements
            document.querySelectorAll('[x-cloak]').forEach(el => {
                el.removeAttribute('x-cloak');
                el.style.display = 'block';
                el.style.opacity = '1';
            });
            
            // Mark Alpine components as initialized
            document.querySelectorAll('[x-data]').forEach(el => {
                el.setAttribute('data-alpine-initialized', 'true');
                el.style.opacity = '1';
                el.style.display = 'block';
            });
            
            // Mark page as loaded for any systems that need it
            document.body.classList.add('loaded');
        }
        
        // Initialize immediately if possible
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializePage);
        } else {
            initializePage();
        }
        
        // Also initialize when Alpine is ready
        document.addEventListener('alpine:init', initializePage);
        
        // Fallback - force initialization after 1 second
        setTimeout(initializePage, 1000);
        
        // Optimized navigation handling
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            if (link && 
                !link.target && 
                !link.download && 
                !link.href.includes('#') &&
                link.href.startsWith(window.location.origin) &&
                link.href !== window.location.href) {
                
                navigationInProgress = true;
                
                // Let browser handle navigation naturally - no opacity changes
                // The preloaded resources will make this faster
            }
        });
        
        // Handle browser navigation (back/forward)
        window.addEventListener('pageshow', function(e) {
            navigationInProgress = false;
            initializePage();
        });
        
        // Reset state on page unload
        window.addEventListener('beforeunload', function() {
            navigationInProgress = false;
        });
    </script>
</body>
</html>
