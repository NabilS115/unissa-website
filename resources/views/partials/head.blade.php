<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<!-- SEO Meta Tags -->
<meta name="description" content="{{ $description ?? 'UNISSA - Promoting halal, ethical, and impactful entrepreneurship through Tijarah Co.' }}">
<meta name="keywords" content="UNISSA, Tijarah, Islamic business, halal food, ethical entrepreneurship, Brunei">
<meta name="author" content="UNISSA">

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="{{ $title ?? config('app.name') }}">
<meta property="og:description" content="{{ $description ?? 'Business with Barakah - Promoting halal, ethical, and impactful entrepreneurship' }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $description ?? 'Business with Barakah - Promoting halal, ethical, and impactful entrepreneurship' }}">

@php
    // Basic favicon detection for components using head partial
    $isUnissaCafe = str_contains(request()->fullUrl(), 'unissa-cafe') || 
                   str_contains(request()->path(), 'unissa-cafe');
    $faviconFile = $isUnissaCafe ? 'unissa-favicon.ico' : 'favicon.ico';
@endphp

<!-- Basic favicon fallback -->
<link rel="icon" href="/{{ $faviconFile }}" type="image/png">
<link rel="shortcut icon" href="/{{ $faviconFile }}" type="image/png">

<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
<link rel="dns-prefetch" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
