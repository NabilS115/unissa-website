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
    {{-- Tailwind is built with Vite or npm for all environments. CDN is not used. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Cropper.js CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <style>
        /* Critical minimal styles to avoid flashes before Tailwind CSS loads */
        /* These are intentionally tiny and safe to include inline */
        html, body { background-color: #fdfdfc; }
        /* Prevent full-screen overlays from briefly displaying before JS/CSS runs */
        [data-initial-hidden] { display: none !important; }

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
            }
            
            body {
                position: relative;
            }
            
            .container, .max-w-7xl, .max-w-6xl, .max-w-5xl, .max-w-4xl {
                max-width: 100vw;
                overflow-x: hidden;
            }
            
            img {
                max-width: 100%;
                height: auto;
            }
            
            /* Prevent horizontal scrolling on mobile */
            * {
                max-width: 100vw;
                box-sizing: border-box;
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
            
            /* Fix mobile menu positioning and animations */
            #mobile-menu {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 9998;
            }
            
            #mobile-menu-panel {
                transition: transform 0.3s ease-in-out;
                transform: translateX(100%);
            }
            
            #mobile-menu.show #mobile-menu-panel {
                transform: translateX(0);
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
    <main class="flex-1">
        @yield('content')
    </main>
    @include('components.footer')
    @stack('scripts')
    {{-- Alpine.js should only be included once. --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Externalized: layout overlay unguard logic -->
    <script src="/js/layout-unguard.js"></script>
</body>
</html>
