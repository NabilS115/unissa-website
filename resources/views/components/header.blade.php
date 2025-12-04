<!-- Reusable Header Component -->
@php
    $headerContext = session('header_context', 'tijarah');
    $referer = request()->headers->get('referer');
    $isCafePage = request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('admin/orders*') || request()->is('admin/products*') || request()->is('cart') || request()->is('cart/*') || request()->is('checkout') || request()->is('checkout/*') || request()->is('my/orders*') || request()->is('unissa-cafe/catalog');
    $isProfilePage = request()->is('profile') || request()->is('admin-profile') || request()->is('edit-profile');
    
    // OVERRIDE: If on profile page and referer suggests Tijarah, force Tijarah branding
    $refererSuggestsTijarah = $referer && (
        str_ends_with($referer, '/') || 
        preg_match('/^https?:\/\/[^\/]+\/?$/', $referer) ||
        str_contains($referer, '/company-history') ||
        str_contains($referer, '/contact') ||
        (str_ends_with($referer, '/profile') && !str_contains($referer, 'context=unissa-cafe')) ||
        (str_ends_with($referer, '/admin-profile') && !str_contains($referer, 'context=unissa-cafe'))
    );
    
    if ($isProfilePage && $refererSuggestsTijarah) {
        $shouldShowUnissaBranding = false; // Force Tijarah branding
    } else {
        $shouldShowUnissaBranding = $isCafePage || ($isProfilePage && $headerContext === 'unissa-cafe');
    }
@endphp

<header class="w-full bg-teal-600 text-white py-2 md:py-4 px-4 md:px-6 header-fallback z-50 relative">
    <div class="flex items-center justify-between w-full max-w-full">
        <!-- Logo Section (Left) -->
        <div class="flex items-center gap-2 md:gap-4 logo-section flex-shrink-0">
            @if($shouldShowUnissaBranding)
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-full p-1 shadow-md flex items-center justify-center">
                    <img src="{{ asset('images/UNISSA_CAFE.png') }}" alt="Unissa Cafe Logo" class="w-full h-full object-contain">
                </div>
            @else
                <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-full p-1 shadow-md flex items-center justify-center">
                    <img src="{{ asset('images/TIJARAH_CO_SDN_BHD.png') }}" alt="Tijarah Co Sdn Bhd Logo" class="w-full h-full object-contain">
                </div>
            @endif
            <h1 class="text-lg md:text-3xl font-bold" style="font-weight: bold; margin: 0;">
                @if($shouldShowUnissaBranding)
                    <span class="hidden sm:inline">Unissa Cafe</span>
                    <span class="sm:hidden">Unissa Cafe</span>
                @else
                    <span class="hidden sm:inline">Tijarah Co Sdn Bhd</span>
                    <span class="sm:hidden">Tijarah</span>
                @endif
            </h1>
        </div>

        <!-- Mobile Menu Button and Cart -->
        <div class="flex items-center gap-3 md:hidden">
            @if($shouldShowUnissaBranding)
                @auth
                <div class="relative" id="cart-group-mobile">
                    <a href="{{ route('cart.index') }}" class="w-10 h-10 bg-white text-teal-600 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 stroke-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </a>
                    <span id="cart-count-mobile" class="absolute bg-red-500 text-white text-xs font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center -top-1 -right-1 shadow-sm" style="display: none; font-size: 9px;">0</span>
                </div>
                @endauth
            @endif
            <button id="mobile-menu-btn" class="w-10 h-10 bg-white text-teal-600 rounded-lg flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-200 active:scale-95">
                <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Desktop Navigation and Icons - All grouped on the right -->
        <div class="hidden md:flex items-center gap-4 ml-auto flex-shrink-0" id="desktop-nav">
            <!-- Navigation Links -->
            <nav>
                <ul class="flex gap-4 nav-list">
                    @if($shouldShowUnissaBranding)
                        <!-- Unissa Cafe Navigation -->
                        <li><a href="{{ route('unissa-cafe.homepage') }}" class="text-white hover:underline nav-link {{ request()->is('unissa-cafe/homepage') || request()->is('unissa-cafe') ? 'font-semibold underline' : '' }}">Home</a></li>
                        <li><a href="{{ route('unissa-cafe.catalog') }}" class="text-white hover:underline nav-link {{ request()->is('unissa-cafe/catalog') ? 'font-semibold underline' : '' }}">Catalog</a></li>
                        <li><a href="/" class="text-white hover:underline nav-link border-l border-teal-400 pl-4 ml-2">← Back to Tijarah</a></li>
                    @else
                        <!-- Tijarah Navigation -->
                        <li><a href="/" class="text-white hover:underline nav-link {{ request()->is('/') ? 'font-semibold underline' : '' }}">Home</a></li>
                        <li><a href="/company-history" class="text-white hover:underline nav-link {{ request()->is('company-history') ? 'font-semibold underline' : '' }}">About</a></li>
                        <li><a href="/contact" class="text-white hover:underline nav-link {{ request()->is('contact') ? 'font-semibold underline' : '' }}">Contact Us</a></li>
                    @endif
                </ul>
            </nav>

            <!-- Search Icon -->
            <div class="relative group" id="searchbar-group">
                <button id="searchbar-icon" class="bg-white text-teal-600 rounded-full p-2 flex items-center justify-center shadow hover:shadow-lg transition-all duration-200" style="width:40px;height:40px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#008080" class="w-5 h-5">
                        <circle cx="11" cy="11" r="8" stroke-width="2" stroke="#008080" fill="none"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2" stroke="#008080" />
                    </svg>
                </button>
                <form id="searchbar-dropdown" class="absolute right-0 top-full mt-2 w-96 bg-white rounded shadow-lg border border-gray-200 transition-all duration-300 opacity-0 pointer-events-none z-[9999]" action="{{ route('search') }}" method="GET">
                    <div class="flex">
                        <input type="text" name="search" id="main-search-input" placeholder="Search products, reviews..." class="flex-1 px-4 py-2 rounded-l-md text-black focus:outline-none" autocomplete="off" />
                        <select name="scope" id="search-scope" class="px-3 py-2 border-l border-gray-300 text-black text-sm focus:outline-none">
                            <option value="all">All</option>
                            <option value="products">Products</option>
                            <option value="reviews">Reviews</option>
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <option value="users">Users</option>
                            @endif
                        </select>
                        <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-r-md font-semibold hover:bg-teal-700 transition-colors">Search</button>
                    </div>
                    <div id="search-suggestions" class="w-full bg-white border-t border-gray-200 rounded-b shadow-lg z-[9999] hidden max-h-64 overflow-y-auto">
                        <!-- Dynamic suggestions will be loaded here -->
                    </div>
                </form>
                
                <style>
                    /* Search dropdown styles */
                    #searchbar-dropdown {
                        opacity: 0;
                        pointer-events: none;
                        transition: all 0.3s ease;
                        transform: translateY(-8px);
                        z-index: 9999;
                    }
                    #searchbar-group.active #searchbar-dropdown {
                        opacity: 1;
                        pointer-events: auto;
                        transform: translateY(0);
                    }
                    
                    /* Ensure search bar is visible on desktop */
                    @media (min-width: 768px) {
                        #desktop-nav {
                            display: flex !important;
                        }
                        #searchbar-group {
                            display: block !important;
                        }
                    }
                </style>
                
                <!-- header scripts extracted to public/js/header.js -->
            </div>

            <!-- Cart Icon (only show on cafe pages and for authenticated users) -->
            @if($shouldShowUnissaBranding)
                @auth
                <div class="relative z-50 overflow-visible" id="cart-group">
                    <a href="{{ route('cart.index') }}" class="w-10 h-10 bg-white text-teal-600 rounded-full flex items-center justify-center shadow hover:shadow-lg hover:bg-gray-50 transition-all duration-300 transform hover:scale-105 overflow-visible">
                        <!-- Enhanced Shopping Cart Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 stroke-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                    </a>
                    <!-- Smaller Notification Bubble -->
                    <span id="cart-count" class="absolute bg-red-600 bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center shadow border border-white -top-1 -right-1 z-50 overflow-visible" style="display: none; font-size: 8px; line-height: 1;">0</span>
                </div>
                <!-- cart/count logic moved to public/js/header.js -->
                @endauth
            @endif

            <!-- Profile Icon -->
            <div class="relative group" id="profile-group">
            <button id="profileMenuButton" class="w-10 h-10 rounded-full bg-white flex items-center justify-center focus:outline-none overflow-hidden shadow hover:shadow-lg transition-all duration-300">
                @if(Auth::check())
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Picture" class="w-10 h-10 rounded-full object-cover pointer-events-none">
                @else
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white">
                        <svg class="w-7 h-7 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 4-7 8-7s8 3 8 7" />
                        </svg>
                    </span>
                @endif
            </button>
            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-2 opacity-0 pointer-events-none z-[9999]">
                @auth
                    <div class="px-4 py-2 text-black">
                        <div class="font-bold">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                    </div>
                    <hr class="my-2">
                    @php
                        $headerContext = session('header_context');
                        $isCafeContext = request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('cart') || request()->is('cart/*') || request()->is('checkout') || request()->is('checkout/*') || request()->is('my/orders*') || request()->is('admin/orders*') || request()->is('admin/products*');
                        
                        // OVERRIDE: Don't add context parameter for profile links if we're on Tijarah pages
                        $currentReferer = request()->headers->get('referer');
                        $isOnTijarahPages = request()->is('/') || 
                                          str_contains(request()->path(), 'company-history') || 
                                          str_contains(request()->path(), 'contact') ||
                                          (!$isCafeContext);
                        
                        $shouldUseUnissaContext = $isCafeContext && !$isOnTijarahPages;
                        
                        if (auth()->user()->role === 'admin') {
                            $profileUrl = $shouldUseUnissaContext ? '/admin-profile?context=unissa-cafe' : '/admin-profile';
                        } else {
                            $profileUrl = $shouldUseUnissaContext ? '/profile?context=unissa-cafe' : '/profile';
                        }
                    @endphp
                    <a href="{{ $profileUrl }}" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">Profile</a>
                    @if(request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('cart') || request()->is('cart/*') || request()->is('checkout') || request()->is('checkout/*') || request()->is('my/*'))
                        <a href="{{ route('user.orders.index') }}" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">My Orders</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                @else
                    <a href="/login" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">Login</a>
                    <a href="/register" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">Register</a>
                @endauth
            </div>
            <style>
                #profileDropdown {
                    opacity: 0;
                    pointer-events: none;
                    transition: all 0.3s ease;
                    transform: translateY(-8px);
                    z-index: 9999;
                }
                #profile-group.active #profileDropdown {
                    opacity: 1;
                    pointer-events: auto;
                    transform: translateY(0);
                }
                
                /* Enhanced Cart Icon Styles */
                #cart-group a {
                    position: relative;
                    overflow: hidden;
                }
                
                #cart-group a::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 0;
                    height: 0;
                    background: rgba(20, 184, 166, 0.1);
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    transition: width 0.6s, height 0.6s;
                }
                
                #cart-group a:hover::before {
                    width: 100px;
                    height: 100px;
                }
                
                /* Cart Count Animation */
                @keyframes bounce-in {
                    0% {
                        transform: translate(4px, -4px) scale(0);
                        opacity: 0;
                    }
                    50% {
                        transform: translate(4px, -4px) scale(1.2);
                        opacity: 1;
                    }
                    100% {
                        transform: translate(4px, -4px) scale(1);
                        opacity: 1;
                    }
                }
                
                @keyframes pulse-glow {
                    0%, 100% {
                        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
                    }
                    50% {
                        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0);
                    }
                }
                
                #cart-count {
                    /* No automatic animation - only animate on updates */
                }
                
                #cart-count.updated {
                    animation: pulse-glow 0.6s ease-out;
                }
                
                /* Cart Icon Hover Effect */
                #cart-group svg {
                    transition: transform 0.3s ease;
                }
                
                #cart-group a:hover svg {
                    transform: translateY(-1px);
                }
            </style>
        </div>
        </div>
    </div>
        
        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu" class="fixed inset-0 backdrop-blur-sm z-[9998] md:hidden hidden" style="background-color: rgba(0, 0, 0, 0.1);">
            <div class="fixed right-0 top-0 h-screen w-80 max-w-[90vw] bg-white shadow-2xl transition-transform duration-300 ease-out flex flex-col" id="mobile-menu-panel" style="transform: translateX(100%)">
                <!-- Mobile Menu Header -->
                <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-4 py-4 flex items-center flex-shrink-0">
                    <div class="flex items-center gap-3">
                        @if($shouldShowUnissaBranding)
                            <div class="w-8 h-8 bg-white rounded-full p-0.5 shadow-sm flex items-center justify-center">
                                <img src="{{ asset('images/UNISSA_CAFE.png') }}" alt="Unissa Cafe Logo" class="w-full h-full object-contain">
                            </div>
                        @else
                            <div class="w-8 h-8 bg-white rounded-full p-0.5 shadow-sm flex items-center justify-center">
                                <img src="{{ asset('images/TIJARAH_CO_SDN_BHD.png') }}" alt="Tijarah Co Logo" class="w-full h-full object-contain">
                            </div>
                        @endif
                        <span class="font-bold text-white text-lg">
                            @if($shouldShowUnissaBranding)
                                Unissa Cafe
                            @else
                                Tijarah Co
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Scrollable Content Area -->
                <div class="flex-1 overflow-y-auto">

                <!-- User Info -->
                @auth
                    <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-teal-100 border-2 border-teal-200 flex items-center justify-center overflow-hidden shadow-sm">
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate text-base">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-teal-600 font-medium">Welcome back! 👋</p>
                            </div>
                        </div>
                    </div>
                @endauth



                <!-- Navigation -->
                <div class="px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        Navigation
                    </h3>
                <nav class="space-y-1">
                    @if($shouldShowUnissaBranding)
                        <a href="{{ route('unissa-cafe.homepage') }}" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->is('unissa-cafe/homepage') || request()->is('unissa-cafe') ? 'bg-teal-100 text-teal-700 font-semibold shadow-sm' : '' }}">
                            <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium">Home</div>
                                <div class="text-xs text-gray-500">Main page</div>
                            </div>
                        </a>
                        <a href="{{ route('unissa-cafe.catalog') }}" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->is('unissa-cafe/catalog') ? 'bg-teal-100 text-teal-700 font-semibold shadow-sm' : '' }}">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium">Catalog</div>
                                <div class="text-xs text-gray-500">Browse products</div>
                            </div>
                        </a>
                        <a href="/" class="flex items-center px-4 py-4 text-gray-600 hover:bg-gray-100 hover:text-gray-800 rounded-xl transition-all duration-200 active:scale-[0.98] border-t border-gray-200 mt-3 pt-4">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium">Back to Tijarah</div>
                                <div class="text-xs text-gray-500">Switch site</div>
                            </div>
                        </a>
                    @else
                        <a href="/" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->is('/') ? 'bg-teal-100 text-teal-700 font-semibold shadow-sm' : '' }}">
                            <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium">Home</div>
                                <div class="text-xs text-gray-500">Tijarah main</div>
                            </div>
                        </a>
                        <a href="/company-history" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->is('company-history') ? 'bg-teal-100 text-teal-700 font-semibold shadow-sm' : '' }}">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium">About</div>
                                <div class="text-xs text-gray-500">Company history</div>
                            </div>
                        </a>
                        <a href="/contact" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98] {{ request()->is('contact') ? 'bg-teal-100 text-teal-700 font-semibold shadow-sm' : '' }}">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium">Contact Us</div>
                                <div class="text-xs text-gray-500">Get in touch</div>
                            </div>
                        </a>
                    @endif

                    @auth
                        @php
                            $profileUrl = Auth::user()->role === 'admin' ? '/admin-profile' : '/profile';
                        @endphp
                        
                        <!-- Account Section -->
                        <div class="px-4 pt-6 pb-2 border-t border-gray-200 mt-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Account</h3>
                            </div>
                        </div>
                        
                        <nav class="px-4 space-y-2">
                            <a href="{{ $profileUrl }}" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98]">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium">Profile</div>
                                    <div class="text-xs text-gray-500">Account settings</div>
                                </div>
                            </a>
                            
                            @if(auth()->user()->role === 'admin')
                                <!-- Admin Section -->
                                <div class="px-4 pt-4 pb-2 border-t border-gray-200 mt-4">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Administration</h3>
                                    </div>
                                </div>
                                
                                <div class="px-4 space-y-2">
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98]">
                                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Manage Users</div>
                                            <div class="text-xs text-gray-500">User accounts</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98]">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Manage Orders</div>
                                            <div class="text-xs text-gray-500">Order management</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98]">
                                        <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Products</div>
                                            <div class="text-xs text-gray-500">Product catalog</div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            
                            @if($shouldShowUnissaBranding)
                                <a href="{{ route('user.orders.index') }}" class="flex items-center px-4 py-4 text-gray-700 hover:bg-teal-50 hover:text-teal-700 rounded-xl transition-all duration-200 active:scale-[0.98]">
                                    <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">My Orders</div>
                                        <div class="text-xs text-gray-500">Order history</div>
                                    </div>
                                </a>
                            @endif
                        </nav>
                        
                        <!-- Logout Section -->
                        <div class="px-4 pt-6 pb-4 border-t border-gray-200 mt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-4 text-red-600 hover:bg-red-50 hover:text-red-700 rounded-xl transition-all duration-200 active:scale-[0.98]">
                                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">Logout</div>
                                        <div class="text-xs text-gray-500">Sign out</div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Auth Section -->
                        <div class="px-4 pt-6 pb-2 border-t border-gray-200 mt-4">
                            <div class="flex items-center mb-3">
                                <svg class="w-4 h-4 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Account Access</h3>
                            </div>
                        </div>
                        
                        <div class="px-4 space-y-3 pb-6">
                            <a href="/login" class="flex items-center px-4 py-4 bg-gradient-to-r from-teal-600 to-teal-700 text-white hover:from-teal-700 hover:to-teal-800 rounded-xl transition-all duration-200 active:scale-[0.98] shadow-md">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center mr-4">
                                    <svg class="w-7 h-7 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="4" />
                                        <path d="M4 20c0-4 4-7 8-7s8 3 8 7" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">Sign In</div>
                                    <div class="text-xs text-teal-100">Access your account</div>
                                </div>
                            </a>
                            <a href="/register" class="flex items-center px-4 py-4 text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all duration-200 active:scale-[0.98] border-2 border-gray-200 hover:border-gray-300">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center mr-4">
                                    <svg class="w-7 h-7 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="8" r="4" />
                                        <path d="M4 20c0-4 4-7 8-7s8 3 8 7" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium">Register</div>
                                    <div class="text-xs text-gray-500">Create new account</div>
                                </div>
                            </a>
                        </div>
                    @endauth
                </nav>
                </div> <!-- End scrollable content -->
            </div>
        </div>
        
        <!-- header bootstrap + external script (load for all users so search/profile toggles work for guests) -->
        <script>
            window.__searchSuggestionsUrl = '{{ route('search.suggestions') }}';
            @auth
                window.__cartCountUrl = '{{ route('cart.count') }}';
                window.__isAuthenticated = true;
                window.__userId = {{ auth()->id() }};
                console.log('🔑 User authenticated in Blade:', {
                    userId: {{ auth()->id() }}, 
                    cartUrl: '{{ route('cart.count') }}'
                });
            @else
                window.__cartCountUrl = null;
                window.__isAuthenticated = false;
                window.__userId = null;
                console.log('🚫 User not authenticated in Blade');
            @endauth
            
            // Global function to close mobile menu (accessible everywhere)
            window.closeMobileMenu = function() {
                console.log('🔴 Closing mobile menu...');
                const mobileMenu = document.getElementById('mobile-menu');
                const mobileMenuPanel = document.getElementById('mobile-menu-panel');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                
                if (mobileMenuPanel) mobileMenuPanel.style.transform = 'translateX(100%)';
                if (menuIcon) menuIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
                document.body.style.overflow = '';
                
                setTimeout(() => {
                    if (mobileMenu) mobileMenu.classList.add('hidden');
                }, 300);
            };
            
            // Setup mobile menu functionality
            document.addEventListener('DOMContentLoaded', function() {
                console.log('🔧 DOM loaded, setting up mobile menu...');
                
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileMenu = document.getElementById('mobile-menu');
                const mobileMenuPanel = document.getElementById('mobile-menu-panel');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                
                console.log('📱 Mobile menu elements found:', {
                    btn: !!mobileMenuBtn,
                    menu: !!mobileMenu,
                    panel: !!mobileMenuPanel,
                    menuIcon: !!menuIcon,
                    closeIcon: !!closeIcon
                });
                
                // Open menu function
                function openMobileMenu() {
                    console.log('📱 Opening mobile menu...');
                    if (mobileMenu) mobileMenu.classList.remove('hidden');
                    if (mobileMenuPanel) mobileMenuPanel.style.transform = 'translateX(0)';
                    if (menuIcon) menuIcon.classList.add('hidden');
                    if (closeIcon) closeIcon.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
                
                // Menu button click handled by header.js
                
                // Note: Close button removed, users can tap backdrop or use ESC key
                
                // Backdrop click handled by header.js
                
                // ESC key to close menu
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        console.log('⌨️ ESC pressed, closing menu...');
                        window.closeMobileMenu();
                    }
                });
            });
        </script>
        <script src="/js/header.js"></script>
    </div>
</header>