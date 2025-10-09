<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Unissa Cafe')</title>
    <meta name="theme-color" content="#0d9488">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Cropper.js CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <style>
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
</body>
</html>
