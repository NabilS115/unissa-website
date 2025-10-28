<!-- Reusable Header Component -->
<header class="w-full bg-teal-600 text-white py-4 flex items-center justify-between px-6 header-fallback z-50">
    <div class="flex items-center gap-4 logo-section">
        <div class="w-10 h-10 bg-red-600 border-4 border-black flex items-center justify-center mr-2"></div>
        <h1 class="text-3xl font-bold" style="font-size: 1.875rem; font-weight: bold; margin: 0;">
            @php
                $headerContext = session('header_context');
                $isCafe = request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('admin/orders*') || request()->is('admin/products*') || request()->is('cart') || request()->is('cart/*') || request()->is('checkout') || request()->is('checkout/*') || request()->is('my/orders*');
                $isProfile = request()->is('profile') || request()->is('admin-profile') || request()->is('edit-profile');
            @endphp
            @if($isCafe || ($isProfile && $headerContext === 'unissa-cafe'))
                Unissa Cafe
            @else
                Tijarah Co Sdn Bhd
            @endif
        </h1>
    </div>
    <div class="flex items-center gap-6 ml-12">
        <nav>
            <ul class="flex gap-4 nav-list">
                @if($isCafe || ($isProfile && $headerContext === 'unissa-cafe'))
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
        <div class="relative group" id="searchbar-group">
            <button id="searchbar-icon" class="bg-white text-teal-600 rounded-full p-2 flex items-center justify-center shadow" style="width:40px;height:40px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#008080" class="w-6 h-6">
                    <circle cx="11" cy="11" r="8" stroke-width="2" stroke="#008080" fill="none"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2" stroke="#008080" />
                </svg>
            </button>
            <form id="searchbar-dropdown" class="absolute right-0 top-full mt-2 w-96 bg-white rounded shadow transition-all duration-300 opacity-0 pointer-events-none z-50" action="{{ route('search') }}" method="GET">
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
                <div id="search-suggestions" class="w-full bg-white border-t border-gray-200 rounded-b shadow-lg z-50 hidden max-h-64 overflow-y-auto">
                    <!-- Dynamic suggestions will be loaded here -->
                </div>
            </form>
            
            <style>
                #searchbar-dropdown {
                    opacity: 0;
                    pointer-events: none;
                    transition: all 0.3s ease;
                }
                #searchbar-group.active #searchbar-dropdown {
                    opacity: 1;
                    pointer-events: auto;
                }
            </style>
            
            <!-- header scripts extracted to public/js/header.js -->
        </div>
        
        <!-- Cart Icon (only show on cafe pages and for authenticated users) -->

    @if($isCafe || ($isProfile && $headerContext === 'unissa-cafe'))
            @auth
            <div class="relative mr-4 z-50 overflow-visible" id="cart-group">
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
            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 opacity-0 pointer-events-none z-50">
                @auth
                    <div class="px-4 py-2 text-black">
                        <div class="font-bold">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                    </div>
                    <hr class="my-2">
                    @php
                        $headerContext = session('header_context');
                        $isCafe = request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('cart') || request()->is('cart/*') || request()->is('checkout') || request()->is('checkout/*') || request()->is('my/orders*');
                        $profileUrl = ($isCafe || $headerContext === 'unissa-cafe') ? '/profile?context=unissa-cafe' : '/profile';
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
                }
                #profile-group.active #profileDropdown {
                    opacity: 1;
                    pointer-events: auto;
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
        
        <!-- header bootstrap + external script (load for all users so search/profile toggles work for guests) -->
        <script>
            window.__searchSuggestionsUrl = '{{ route('search.suggestions') }}';
            @auth
                window.__cartCountUrl = '{{ route('cart.count') }}';
            @else
                window.__cartCountUrl = null;
            @endauth
        </script>
        <script src="/js/header.js"></script>
    </div>
    </div>
</header>