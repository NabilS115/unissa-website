{{--
    LEGACY FILE - ARCHIVED
    
    This is the original monolithic catalog page (1,797 lines) that was used before 
    the catalog was restructured into separate pages:
    - featured.blade.php (now unissa-cafe/homepage)  
    - browse.blade.php (now unissa-cafe/menu)
    
    This file is kept for reference but is no longer used in the application.
    All routes now redirect to the new structure.
    
    Date archived: October 4, 2025
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unissa Cafe - Catalog - LEGACY</title>
    <meta name="theme-color" content="#0d9488">
    {{-- Tailwind CDN removed for production. Use main layout for styles. --}}
    <style>
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
        .main-content {
            flex: 1 0 auto;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
@php
    $food = $food ?? [];
    $merchandise = $merchandise ?? [];
    
    // Convert both food and merchandise to consistent objects format
    if ($food instanceof \Illuminate\Support\Collection) {
        $food = $food->map(function($item) { 
            return is_array($item) ? (object)$item : $item; 
        })->toArray();
    } else {
        // Handle regular arrays
        $food = array_map(function($item) {
            return is_array($item) ? (object)$item : $item;
        }, $food);
    }
    
    if ($merchandise instanceof \Illuminate\Support\Collection) {
        $merchandise = $merchandise->map(function($item) { 
            return is_array($item) ? (object)$item : $item; 
        })->toArray();
    } else {
        // Handle regular arrays
        $merchandise = array_map(function($item) {
            return is_array($item) ? (object)$item : $item;
        }, $merchandise);
    }
    
    // Calculate average ratings for each product using the same logic as review page
    foreach ($food as &$foodItem) {
        $productId = null;
        
        // Get product ID properly from both arrays and objects
        if (is_object($foodItem) && isset($foodItem->id)) {
            $productId = $foodItem->id;
        } elseif (is_array($foodItem) && isset($foodItem['id'])) {
            $productId = $foodItem['id'];
        }
        
        if ($productId) {
            $reviews = \App\Models\Review::where('product_id', $productId)->get();
            $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            
            foreach ($reviews as $review) {
                $rating = (int) $review->rating;
                if ($rating >= 1 && $rating <= 5) {
                    $ratings[$rating]++;
                }
            }
            
            $totalRatings = array_sum($ratings);
            $averageRating = 0;
            
            if ($totalRatings > 0) {
                $weightedSum = 0;
                foreach ($ratings as $star => $count) {
                    $weightedSum += $star * $count;
                }
                $averageRating = $weightedSum / $totalRatings;
            }
            
            // Set the calculated rating
            if (is_object($foodItem)) {
                $foodItem->calculated_rating = number_format($averageRating, 1);
            } else {
                $foodItem['calculated_rating'] = number_format($averageRating, 1);
                // Convert array to object to ensure consistent structure
                $foodItem = (object)$foodItem;
            }
        }
    }
    
    foreach ($merchandise as &$merchItem) {
        $productId = null;
        
        // Get product ID properly from both arrays and objects
        if (is_object($merchItem) && isset($merchItem->id)) {
            $productId = $merchItem->id;
        } elseif (is_array($merchItem) && isset($merchItem['id'])) {
            $productId = $merchItem['id'];
        }
        
        if ($productId) {
            $reviews = \App\Models\Review::where('product_id', $productId)->get();
            $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            
            foreach ($reviews as $review) {
                $rating = (int) $review->rating;
                if ($rating >= 1 && $rating <= 5) {
                    $ratings[$rating]++;
                }
            }
            
            $totalRatings = array_sum($ratings);
            $averageRating = 0;
            
            if ($totalRatings > 0) {
                $weightedSum = 0;
                foreach ($ratings as $star => $count) {
                    $weightedSum += $star * $count;
                }
                $averageRating = $weightedSum / $totalRatings;
            }
            
            // Set the calculated rating
            if (is_object($merchItem)) {
                $merchItem->calculated_rating = number_format($averageRating, 1);
            } else {
                $merchItem['calculated_rating'] = number_format($averageRating, 1);
                // Convert array to object to ensure consistent structure
                $merchItem = (object)$merchItem;
            }
        }
    }
    
    // Separate categories for food and merch
    $foodCategories = \App\Models\Product::where('type', 'food')->pluck('category')->unique()->values()->all();
    $merchCategories = \App\Models\Product::where('type', 'merch')->pluck('category')->unique()->values()->all();
@endphp

<div x-data="foodMerchComponent()" x-cloak>
<!-- Catalog Header -->
<header class="w-full bg-teal-600 text-white py-4 flex items-center justify-between px-6 header-fallback sticky top-0 z-50">
    <div class="flex items-center gap-4 logo-section">
        <div class="w-16 h-16 bg-white rounded-full p-1 shadow-md flex items-center justify-center">
            <img src="{{ asset('images/UNISSA_CAFE.png') }}" alt="Unissa Cafe Logo" class="w-full h-full object-contain">
        </div>
        <h1 class="text-3xl font-bold" style="font-size: 1.875rem; font-weight: bold; margin: 0;">TIJARAH CO SDN BHD</h1>
    </div>
    <div class="flex items-center gap-6 ml-12">
        <nav>
            <ul class="flex gap-4 nav-list">
                <li><a href="/" class="text-white hover:underline nav-link">Home</a></li>
                <li><a href="{{ route('products.catalog') }}" class="text-white hover:underline nav-link font-semibold underline">Catalog</a></li>
                <li><a href="/company-history" class="text-white hover:underline nav-link">About</a></li>
                <li><a href="/contact" class="text-white hover:underline nav-link">Contact Us</a></li>
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
        </div>
        
        @if(auth()->user()?->role === 'admin')
        <div class="relative">
            <button @click="showAddModal = true" class="bg-white text-teal-600 rounded-full p-2 flex items-center justify-center shadow" style="width:40px;height:40px;" title="Add Product">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>
        @endif
        
        <div class="relative group" id="profile-group">
            <button id="profileMenuButton" class="w-10 h-10 rounded-full bg-white flex items-center justify-center focus:outline-none overflow-hidden">
                @if(Auth::check() && Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Picture" class="w-10 h-10 rounded-full object-cover pointer-events-none">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="24" height="24" class="pointer-events-none">
                        <circle cx="20" cy="20" r="18" fill="#fff" stroke="#0d9488" stroke-width="2" />
                        <circle cx="20" cy="16" r="5" fill="none" stroke="#0d9488" stroke-width="2" />
                        <path d="M12 30c0-4 8-4 8-4s8 0 8 4" fill="none" stroke="#0d9488" stroke-width="2" />
                    </svg>
                @endif
            </button>
            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 opacity-0 pointer-events-none z-50">
                @auth
                    <div class="px-4 py-2 text-black">
                        <div class="font-bold">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                    </div>
                    <hr class="my-2">
                    <a href="/profile" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">Profile</a>
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
            </style>
        </div>
    </div>
</header>

<script>
    function initializeHeaderInteractions() {
        const searchIcon = document.getElementById('searchbar-icon');
        const searchGroup = document.getElementById('searchbar-group');
        const searchDropdown = document.getElementById('searchbar-dropdown');
        const profileIcon = document.getElementById('profileMenuButton');
        const profileGroup = document.getElementById('profile-group');
        const profileDropdown = document.getElementById('profileDropdown');

        if (!searchIcon || !profileIcon) {
            return;
        }

        if (searchIcon.hasAttribute('data-initialized') || profileIcon.hasAttribute('data-initialized')) {
            return;
        }

        searchIcon.setAttribute('data-initialized', 'true');
        profileIcon.setAttribute('data-initialized', 'true');

        // Search icon click handler
        searchIcon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close profile dropdown if open
            if (profileGroup) {
                profileGroup.classList.remove('active');
            }
            
            if (searchGroup) {
                searchGroup.classList.toggle('active');
                if (searchGroup.classList.contains('active')) {
                    setTimeout(() => {
                        const searchInput = document.getElementById('main-search-input');
                        if (searchInput) {
                            searchInput.focus();
                        }
                    }, 100);
                }
            }
        });

        // Profile icon click handler
        if (profileIcon && profileGroup) {
            profileIcon.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close search dropdown if open
                if (searchGroup) {
                    searchGroup.classList.remove('active');
                }
                
                profileGroup.classList.toggle('active');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (searchGroup && !searchGroup.contains(e.target)) {
                searchGroup.classList.remove('active');
            }
            if (profileGroup && !profileGroup.contains(e.target)) {
                profileGroup.classList.remove('active');
            }
        });

        // Initialize search functionality
        const searchInput = document.getElementById('main-search-input');
        const searchScope = document.getElementById('search-scope');
        const suggestionsBox = document.getElementById('search-suggestions');

        if (searchInput && searchScope) {
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                const scope = searchScope.value;
                
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetchSuggestions(query, scope);
                }, 300);
            });
            
            searchScope.addEventListener('change', function() {
                const query = searchInput.value.trim();
                const scope = this.value;
                
                if (query.length >= 2) {
                    fetchSuggestions(query, scope);
                }
            });
            
            searchInput.addEventListener('blur', function() {
                setTimeout(() => {
                    if (suggestionsBox) {
                        suggestionsBox.style.display = 'none';
                    }
                }, 200);
            });
            
            searchInput.addEventListener('focus', function() {
                const query = this.value.trim();
                if (query.length >= 2) {
                    const scope = searchScope.value;
                    fetchSuggestions(query, scope);
                }
            });
        }

        async function fetchSuggestions(query, scope) {
            if (query.length < 2) {
                if (suggestionsBox) {
                    suggestionsBox.style.display = 'none';
                }
                return;
            }
            
            try {
                // You might need to implement this route or modify as needed
                // const response = await fetch(`/search/suggestions?q=${encodeURIComponent(query)}&scope=${scope}`);
                // const suggestions = await response.json();
                // displaySuggestions(suggestions, query);
            } catch (error) {
                console.log('Search suggestions not implemented yet');
            }
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeHeaderInteractions);
    } else {
        initializeHeaderInteractions();
    }

    // Also initialize after a short delay to handle dynamic loading
    setTimeout(initializeHeaderInteractions, 500);
</script>

<!-- Catalog Controls Header -->
<div class="w-full bg-teal-600 text-white sticky top-[72px] z-40 border-t border-teal-500">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Second Row: Search, Filters, and Controls -->
        <div class="border-t border-teal-500 py-3">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Left: Tab Switcher -->
                <div class="flex-shrink-0">
                    <div class="inline-flex rounded-lg bg-teal-700 p-1 shadow-sm">
                        <button type="button" @click="switchTab('food')" :class="tab === 'food' ? 'bg-white text-teal-700' : 'bg-transparent text-white'" class="px-4 py-1.5 rounded-lg font-medium focus:outline-none transition-all duration-200 text-sm">Food</button>
                        <button type="button" @click="switchTab('merch')" :class="tab === 'merch' ? 'bg-white text-teal-700' : 'bg-transparent text-white'" class="px-4 py-1.5 rounded-lg font-medium focus:outline-none transition-all duration-200 text-sm">Merch</button>
                    </div>
                </div>

                <!-- Center: Search Bar -->
                <div class="flex-1 max-w-md relative">
                    <div class="relative">
                        <input x-show="tab === 'food'" type="text" placeholder="Search food..." x-model="foodSearchInput" @focus="showFoodPredictions = true" @input="showFoodPredictions = foodSearchInput.length > 0" @blur="setTimeout(() => { showFoodPredictions = false; }, 100)" @keyup.enter="performSearch()" class="w-full border border-white rounded-lg px-4 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-white text-sm bg-white text-teal-700" />
                        <input x-show="tab === 'merch'" type="text" placeholder="Search merchandise..." x-model="merchSearchInput" @focus="showMerchPredictions = true" @input="showMerchPredictions = merchSearchInput.length > 0" @blur="setTimeout(() => { showMerchPredictions = false; }, 100)" @keyup.enter="performSearch()" class="w-full border border-white rounded-lg px-4 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-white text-sm bg-white text-teal-700" />
                        <button @click="performSearch()" class="absolute right-8 top-1/2 -translate-y-1/2 p-1">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </button>
                        <button x-show="tab === 'food' && (foodSearch || foodSearchInput)" @click="clearSearch()" class="absolute right-1 top-1/2 -translate-y-1/2 p-1" title="Clear search">
                            <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                        <button x-show="tab === 'merch' && (merchSearch || merchSearchInput)" @click="clearSearch()" class="absolute right-1 top-1/2 -translate-y-1/2 p-1" title="Clear search">
                            <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <!-- Search Predictions -->
                    <template x-if="tab === 'food' && foodSearchInput && showFoodPredictions">
                        <ul class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-48 overflow-y-auto">
                            <template x-for="foodItem in food" :key="foodItem.id">
                                <template x-if="foodItem.name && foodItem.name.toLowerCase().includes(foodSearchInput.toLowerCase())">
                                    <li @mousedown.prevent="foodSearchInput = foodItem.name; showFoodPredictions = false; performSearch()" class="px-4 py-2 hover:bg-teal-50 cursor-pointer text-sm text-teal-700" x-text="foodItem.name"></li>
                                </template>
                            </template>
                        </ul>
                    </template>
                    <template x-if="tab === 'merch' && merchSearchInput && showMerchPredictions">
                        <ul class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-48 overflow-y-auto">
                            <template x-for="item in merchandise" :key="item.id">
                                <template x-if="item.name.toLowerCase().includes(merchSearchInput.toLowerCase())">
                                    <li @mousedown.prevent="merchSearchInput = item.name; showMerchPredictions = false; performSearch()" class="px-4 py-2 hover:bg-teal-50 cursor-pointer text-sm text-teal-700" x-text="item.name"></li>
                                </template>
                            </template>
                        </ul>
                    </template>
                </div>

                <!-- Right: Sort Dropdown -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <label class="text-sm font-medium text-white">Sort:</label>
                    <div class="rounded border border-white px-3 py-1.5 bg-white">
                        <template x-if="tab === 'food'">
                            <select x-model="foodSort" class="bg-transparent outline-none border-none text-teal-700 font-medium text-sm cursor-pointer">
                                <option value="">Default</option>
                                <option value="name">Name (A-Z)</option>
                                <option value="category">Category</option>
                                <option value="rating">Rating</option>
                            </select>
                        </template>
                        <template x-if="tab === 'merch'">
                            <select x-model="merchSort" class="bg-transparent outline-none border-none text-teal-700 font-medium text-sm cursor-pointer">
                                <option value="">Default</option>
                                <option value="name">Name (A-Z)</option>
                                <option value="category">Category</option>
                                <option value="rating">Rating</option>
                            </select>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Third Row: Category Filters -->
            <div class="mt-3 pt-3 border-t border-teal-500">
                <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                    <template x-if="tab === 'food'">
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="foodFilter = 'All'" :class="foodFilter === 'All' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">All</button>
                            @foreach ($foodCategories as $cat)
                            <button type="button" @click="foodFilter = '{{ $cat }}'" :class="foodFilter === '{{ $cat }}' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">{{ $cat }}</button>
                            @endforeach
                        </div>
                    </template>
                    <template x-if="tab === 'merch'">
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="merchFilter = 'All'" :class="merchFilter === 'All' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">All</button>
                            @foreach ($merchCategories as $cat)
                            <button type="button" @click="merchFilter = '{{ $cat }}'" :class="merchFilter === '{{ $cat }}' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">{{ $cat }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Hero Section -->
    <section class="w-full h-80 flex flex-col items-center justify-center mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80" alt="Catalog Banner" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow-lg mb-4">Product Catalog</h1>
            <p class="text-lg md:text-xl text-white drop-shadow-md mb-6">Discover halal, ethical, and quality products through UNISSA's Tijarah Co.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button @click="switchTab('food')" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Food & Beverages
                </button>
                <button @click="switchTab('merch')" class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-teal-600 font-semibold rounded-lg border-2 border-white transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                    </svg>
                    Browse Merchandise
                </button>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-teal-800 mb-4">Featured Products</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover our most popular and highly-rated products, carefully selected for their quality and customer satisfaction.</p>
        </div>

        <!-- Featured Food Products -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Featured Food & Beverages
                </h3>
                <button @click="switchTab('food')" class="text-teal-600 hover:text-teal-800 font-semibold text-sm flex items-center">
                    View All Food
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(product, index) in food.slice(0, 3)" :key="product.id">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer" @click="storeScrollPositionAndNavigate(`/product/${product.id}`)"
                        <div class="relative overflow-hidden">
                            <img :src="product.img" :alt="product.name" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 left-4">
                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Featured</span>
                            </div>
                            <template x-if="product.calculated_rating && product.calculated_rating > 0">
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-800" x-text="product.calculated_rating">0</span>
                                </div>
                            </template>
                        </div>
                        <div class="p-6">
                            <div class="mb-2">
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full" x-text="product.category"></span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors" x-text="product.name"></h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="product.desc"></p>
                            <template x-if="product.price">
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-teal-600">$<span x-text="parseFloat(product.price).toFixed(2)"></span></span>
                                    <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Order Now
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Featured Merchandise -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                    </svg>
                    Featured Merchandise
                </h3>
                <button @click="switchTab('merch')" class="text-teal-600 hover:text-teal-800 font-semibold text-sm flex items-center">
                    View All Merchandise
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(product, index) in merchandise.slice(0, 3)" :key="product.id">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer" @click="storeScrollPositionAndNavigate(`/product/${product.id}`)"
                        <div class="relative overflow-hidden">
                            <img :src="product.img" :alt="product.name" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 left-4">
                                <span class="bg-teal-500 text-white text-xs font-bold px-2 py-1 rounded-full">Featured</span>
                            </div>
                            <template x-if="product.calculated_rating && product.calculated_rating > 0">
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-800" x-text="product.calculated_rating">0</span>
                                </div>
                            </template>
                        </div>
                        <div class="p-6">
                            <div class="mb-2">
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-teal-700 bg-teal-100 rounded-full" x-text="product.category"></span>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors" x-text="product.name"></h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="product.desc"></p>
                            <template x-if="product.price">
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-teal-600">$<span x-text="parseFloat(product.price).toFixed(2)"></span></span>
                                    <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Order Now
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Merchandise/Food Banner Section -->
    <div class="w-full flex items-center justify-center mb-8" style="height: 250px;">
        <div class="relative w-full h-full flex items-stretch shadow-lg overflow-hidden"
             style="height: 250px; border-radius: 0 125px 125px 0; margin-right: 32px;">
            <div class="absolute inset-0 w-full h-full"
                 :style="tab === 'merch' 
                    ? 'background: linear-gradient(90deg, #6a7fd1 10%, #e17fc2 50%, #fbbf24 100%); pointer-events:none;' 
                    : 'background: linear-gradient(90deg, #ffbe2f 10%, #6adf7b 100%); pointer-events:none;'">
            </div>
            <div class="flex-1 flex items-center pl-12 z-10" style="width: 55%;">
                <div class="text-left">
                    <h1 class="text-5xl font-extrabold text-white mb-2 drop-shadow-lg" x-show="tab === 'merch'" x-text="'Merchandise'"></h1>
                    <h1 class="text-4xl font-extrabold text-black mb-2 drop-shadow-lg" x-show="tab === 'food'" style="line-height: 1.1;">
                        FOODS & BEVERAGES
                    </h1>
                    <h2 class="text-2xl font-bold text-white mb-2 drop-shadow" x-show="tab === 'merch'" x-text="'Discover Unique Merchandise'"></h2>
                </div>
            </div>
            <img :src="tab === 'merch' ? '/images/merch-banner2.png' : '/nasii-lemak.png'"
                 :alt="tab === 'merch' ? 'Merchandise Banner' : 'Nasi Lemak'"
                 class="object-cover rounded-full z-10"
                 style="height: 200px; width: 200px; position: absolute; right: 30px; top: 50%; transform: translateY(-50%); object-fit: cover;" />
        </div>
    </div>



    {{-- Admin features --}}
    @if(auth()->check() && auth()->user()->role === 'admin')
    <!-- Add Product Button -->
    <div class="w-full flex justify-end px-8 mb-4">
        <button @click="showAddModal = true"
            class="bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-teal-700 transition">
            Add Product
        </button>
    </div>
    <!-- Add Product Modal -->
    <div x-show="showAddModal" x-cloak data-initial-hidden class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50">
        <form x-ref="addForm" @submit.prevent="submitAddProduct" enctype="multipart/form-data"
              class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
            @csrf
            <button type="button" @click="showAddModal = false"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Add Product</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="desc" required class="border rounded px-3 py-2 w-full"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Category</label>
                <input type="text" name="category" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Image</label>
                <input type="file" name="img" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Type</label>
                <select name="type" class="border rounded px-3 py-2 w-full">
                    <option value="food">Food & Beverages</option>
                    <option value="merch">Merchandise</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded font-semibold hover:bg-teal-700">
                Add
            </button>
        </form>
    </div>
    <!-- Edit Product Modal -->
    <div x-show="showEditModal" x-cloak data-initial-hidden class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <form method="POST" :action="editFormAction" enctype="multipart/form-data"
              class="bg-white rounded-lg shadow-lg p-8 w-full max-w-6xl relative overflow-y-auto"
              style="max-height:90vh;"
              @submit.prevent="handleEditSubmit">
            @csrf
            @method('PUT')
            <button type="button" @click="showEditModal = false"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-center">Edit Product</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Image Section -->
                <div class="space-y-6">
                    <!-- Current Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <img :src="editProduct.img" :alt="editProduct.name" id="edit-current-img"
                             class="w-full h-64 object-cover rounded-lg bg-gray-100 border border-gray-300" />
                    </div>
                    
                    <!-- Change Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Change Image 
                            <span class="text-xs text-gray-500">(click & drag to crop)</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="edit-img-input" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Replace image</span>
                                        <input type="file" name="img" id="edit-img-input" class="sr-only" accept="image/*"
                                            @change="event => startCrop(event)" />
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 20MB</p>
                            </div>
                        </div>
                        
                        <!-- Cropper Preview -->
                        <div x-show="showCropper" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-4 bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                            
                            <div class="flex flex-col items-center justify-center">
                                <div id="edit-cropper-preview" class="w-full max-w-lg h-64 bg-white border border-gray-300 rounded-lg overflow-hidden">
                                    <img id="edit-cropper-img" class="w-full h-full object-contain" />
                                </div>
                                
                                <button type="button" 
                                        @click="finishCrop"
                                        class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                    Crop & Preview
                                </button>
                            </div>
                        </div>
                        
                        <!-- Cropped Preview -->
                        <div x-show="croppedUrl" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-4 bg-white border border-gray-200 rounded-lg p-4">
                            
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cropped Preview:</label>
                            <div class="flex justify-center">
                                <img :src="croppedUrl" 
                                     class="w-64 h-48 object-cover rounded-lg border border-gray-300 shadow-sm" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Product Details -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Product Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                            <input type="text" name="name" :value="editProduct.name" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <input type="text" name="category" :value="editProduct.category" required 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        
                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                            <select name="type" :value="editProduct.type" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Type</option>
                                <option value="food">Food & Beverages</option>
                                <option value="merch">Merchandise</option>
                            </select>
                        </div>
                        
                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="price" :value="editProduct.price" step="0.01" min="0" required
                                       class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" :value="editProduct.status" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="active">Available</option>
                                <option value="inactive">Inactive</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="discontinued">Discontinued</option>
                            </select>
                        </div>
                        
                        <!-- Low Stock Threshold -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Low Stock Threshold *</label>
                            <input type="number" name="low_stock_threshold" :value="editProduct.low_stock_threshold" min="0" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="desc" required rows="4" x-text="editProduct.desc"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <!-- Stock Management Options -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Management</h3>
                        
                        <div class="space-y-4">
                            <!-- Active Status -->
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="edit-is-active" value="1" 
                                       :checked="editProduct.is_active"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="edit-is-active" class="ml-2 block text-sm text-gray-900">Product is active</label>
                            </div>

                            <!-- Track Stock -->
                            <div class="flex items-center">
                                <input type="checkbox" name="track_stock" id="edit-track-stock" value="1" 
                                       :checked="editProduct.track_stock"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="edit-track-stock" class="ml-2 block text-sm text-gray-900">Track stock quantity</label>
                            </div>

                            <!-- Current Stock Information -->
                            <div x-show="editProduct.track_stock" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Current Stock: <span x-text="editProduct.stock_quantity || 0"></span></p>
                                        <p class="text-xs text-blue-700">Last updated: <span x-text="editProduct.last_restocked_at || 'Never'"></span></p>
                                    </div>
                                    <template x-if="editProduct.stock_quantity <= editProduct.low_stock_threshold && editProduct.stock_quantity > 0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Low Stock
                                        </span>
                                    </template>
                                    <template x-if="editProduct.stock_quantity <= 0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <!-- Stock Quantity -->
                            <div x-show="editProduct.track_stock">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                                <input type="number" name="stock_quantity" :value="editProduct.stock_quantity" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-600">Use the stock management tools on the products list for detailed stock operations.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <button type="button" @click="showEditModal = false" 
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- Loading Overlay -->
    <div x-show="isLoading" x-cloak data-initial-hidden class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-40">
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
            <div class="rounded-full h-12 w-12 border-4 border-teal-200 border-t-teal-600 mb-4"></div>
            <p class="text-gray-600 font-medium">Switching catalog...</p>
        </div>
    </div>

    <!-- Content Container with Animation -->
    <div class="relative overflow-hidden">
        <!-- Food Cards -->
        <div x-show="tab === 'food'" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 -translate-x-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-8 mb-20">
                <template x-for="food in pagedFoods" :key="food.id">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer food-card"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         @click="window.location.href = `/product/${food.id}`">
                        <div class="relative overflow-hidden">
                            <img :src="food.img" :alt="food.name" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 left-4">
                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full" x-text="food.category"></span>
                            </div>
                            <template x-if="food.calculated_rating && food.calculated_rating > 0">
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-800" x-text="food.calculated_rating">0</span>
                                </div>
                            </template>
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute top-1/2 right-2 -translate-y-1/2 z-20 flex flex-col gap-1 items-center">
                                <button @click.stop="openEditModal(food, '/catalog/edit/' + food.id)"
                                    class="bg-teal-600 text-white w-12 h-6 rounded text-xs font-normal hover:bg-teal-700 transition-colors backdrop-blur-sm bg-opacity-90 flex items-center justify-center">
                                    Edit
                                </button>
                                <form method="POST" :action="'/catalog/delete/' + food.id" onsubmit="event.stopPropagation(); return confirm('Delete this product?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.stop class="bg-red-600 text-white w-12 h-6 rounded text-xs font-normal hover:bg-red-700 transition-colors backdrop-blur-sm bg-opacity-90 flex items-center justify-center">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors" x-text="food.name"></h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="food.desc"></p>
                            <template x-if="food.price">
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-teal-600">$<span x-text="parseFloat(food.price).toFixed(2)"></span></span>
                                    <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Order Now
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-center items-center gap-4 mb-8">
                <button @click="foodPage > 1 && foodPage--" :disabled="foodPage === 1" class="px-3 py-1 rounded bg-teal-600 text-white hover:bg-teal-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <span x-text="'Page ' + foodPage + ' of ' + foodTotalPages"></span>
                <button @click="foodPage < foodTotalPages && foodPage++" :disabled="foodPage === foodTotalPages" class="px-3 py-1 rounded bg-teal-600 text-white hover:bg-teal-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>

        <!-- Merchandise Cards -->
        <div x-show="tab === 'merch'" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 -translate-x-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-8 mb-20">
                <template x-for="item in pagedMerch" :key="item.id">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer merch-card"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         @click="window.location.href = `/product/${item.id}`">
                        <div class="relative overflow-hidden">
                            <img :src="item.img" :alt="item.name" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 left-4">
                                <span class="bg-teal-500 text-white text-xs font-bold px-2 py-1 rounded-full" x-text="item.category"></span>
                            </div>
                            <template x-if="item.calculated_rating && item.calculated_rating > 0">
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-800" x-text="item.calculated_rating">0</span>
                                </div>
                            </template>
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute top-1/2 right-2 -translate-y-1/2 z-20 flex flex-col gap-1 items-center">
                                <button @click.stop="openEditModal(item, '/catalog/edit/' + item.id)"
                                    class="bg-teal-600 text-white w-12 h-6 rounded text-xs font-normal hover:bg-teal-700 transition-colors backdrop-blur-sm bg-opacity-90 flex items-center justify-center">
                                    Edit
                                </button>
                                <form method="POST" :action="'/catalog/delete/' + item.id" onsubmit="event.stopPropagation(); return confirm('Delete this product?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.stop class="bg-red-600 text-white w-12 h-6 rounded text-xs font-normal hover:bg-red-700 transition-colors backdrop-blur-sm bg-opacity-90 flex items-center justify-center">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors" x-text="item.name"></h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="item.desc"></p>
                            <template x-if="item.price">
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-teal-600">$<span x-text="parseFloat(item.price).toFixed(2)"></span></span>
                                    <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Order Now
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-center items-center gap-4 mb-8">
                <button @click="merchPage > 1 && merchPage--" :disabled="merchPage === 1" class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <span x-text="'Page ' + merchPage + ' of ' + merchTotalPages"></span>
                <button @click="merchPage < merchTotalPages && merchPage++" :disabled="merchPage === merchTotalPages" class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    </div>



    <!-- No Products Message -->
    <div class="w-full text-center py-8" x-show="(tab === 'food' ? sortedFoods : sortedMerch).length === 0">
        <p class="text-gray-500 text-lg">No products found.</p>
    </div>
</div>
{{-- End catalog content --}}

<head>
    <!-- ...existing code... -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Text clamping utilities */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Fade-in animation for cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        /* Loading animation */
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(20, 184, 166, 0);
            }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }
        
        /* Smooth tab transitions */
        .tab-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Remove all shadows from select elements and their dropdown arrows */
        select,
        select:focus,
        select:hover,
        select:active {
            box-shadow: none !important;
            outline: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }
        
        /* Specifically target the sort dropdown container and select */
        .sort-dropdown-container,
        .sort-dropdown-container select,
        .sort-dropdown-container select:focus,
        .sort-dropdown-container select:hover,
        .sort-dropdown-container select:active {
            box-shadow: none !important;
            outline: none !important;
        }
        
        /* Remove default dropdown arrow and add custom one */
        .sort-dropdown-container select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        /* Ensure no shadow on the container either */
        .rounded-md.border-2.border-teal-300 {
            box-shadow: none !important;
        }
        
        .rounded-md.border-2.border-teal-300:hover {
            box-shadow: none !important;
        }
    </style>
    <!-- Add Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
</head>

{{-- Alpine.js CDN removed. Use main layout for scripts. --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('foodMerchComponent', function() {
        // Check URL parameter for tab, or use default
        const urlParams = new URLSearchParams(window.location.search);
        let initialTab = urlParams.get('tab') || 'food';
        
        return {
            tab: initialTab,
            isLoading: false,
            foodFilter: 'All',
            merchFilter: 'All',
            foodSort: '',
            merchSort: '',
            foodSearch: '',
            foodSearchInput: '',
            merchSearch: '',
            merchSearchInput: '',
            showFoodPredictions: false,
            showMerchPredictions: false,
            showAddModal: false,
            showEditModal: false,
            editProduct: {},
            editFormAction: '',
            food: @json($food),
            merchandise: @json($merchandise),
            foodPage: 1,
            foodPerPage: 8,
            merchPage: 1,
            merchPerPage: 8,
            // Force refresh trigger
            refreshTrigger: 0,
            // Enhanced caching system
            _cachedSortedFoods: null,
            _cachedSortedMerch: null,
            _cachedPagedFoods: new Map(),
            _cachedPagedMerch: new Map(),
            _lastFoodCacheKey: '',
            _lastMerchCacheKey: '',
            _preloadedTabs: new Set(),
            useBackendSearch: false, // Toggle for backend vs frontend search
            isSearching: false,
            
            blurActive() {
                if (document.activeElement) document.activeElement.blur();
            },
            
            // Helper methods
            getFoodCacheKey() {
                return `${this.foodFilter}-${this.foodSort}-${this.foodSearch}`;
            },
            
            getMerchCacheKey() {
                return `${this.merchFilter}-${this.merchSort}-${this.merchSearch}`;
            },
            
            // Optimized sorting with memoization
            get sortedFoods() {
                const cacheKey = this.getFoodCacheKey();
                
                if (this._cachedSortedFoods && this._lastFoodCacheKey === cacheKey) {
                    return this._cachedSortedFoods;
                }
                
                const sort = () => {
                    let search = this.foodSearch.toLowerCase();
                    let filtered = this.food.filter(f => {
                        const categoryMatch = this.foodFilter === 'All' || f.category === this.foodFilter;
                        const searchMatch = !search || 
                            f.name.toLowerCase().includes(search) || 
                            f.desc.toLowerCase().includes(search);
                        
                        return categoryMatch && searchMatch;
                    });
                    
                    if (this.foodSort === 'name') {
                        filtered.sort((a, b) => a.name.localeCompare(b.name));
                    } else if (this.foodSort === 'category') {
                        filtered.sort((a, b) => a.category.localeCompare(b.category));
                    } else if (this.foodSort === 'rating') {
                        filtered.sort((a, b) => {
                            const ratingA = parseFloat(a.calculated_rating || '0');
                            const ratingB = parseFloat(b.calculated_rating || '0');
                            return ratingB - ratingA;
                        });
                    }
                    
                    this._cachedSortedFoods = filtered;
                    this._lastFoodCacheKey = cacheKey;
                    return filtered;
                };
                
                return sort();
            },
            
            get pagedFoods() {
                const pageKey = `${this.getFoodCacheKey()}-${this.foodPage}`;
                
                if (this._cachedPagedFoods.has(pageKey)) {
                    return this._cachedPagedFoods.get(pageKey);
                }
                
                const start = (this.foodPage - 1) * this.foodPerPage;
                const result = this.sortedFoods.slice(start, start + this.foodPerPage);
                
                // Cache this page but limit cache size
                if (this._cachedPagedFoods.size > 20) {
                    const firstKey = this._cachedPagedFoods.keys().next().value;
                    this._cachedPagedFoods.delete(firstKey);
                }
                this._cachedPagedFoods.set(pageKey, result);
                
                return result;
            },
            
            get foodTotalPages() {
                return Math.max(1, Math.ceil(this.sortedFoods.length / this.foodPerPage));
            },
            
            get sortedMerch() {
                const cacheKey = this.getMerchCacheKey();
                
                if (this._cachedSortedMerch && this._lastMerchCacheKey === cacheKey) {
                    return this._cachedSortedMerch;
                }
                
                const sort = () => {
                    let search = this.merchSearch.toLowerCase();
                    let filtered = this.merchandise.filter(m => {
                        const categoryMatch = this.merchFilter === 'All' || m.category === this.merchFilter;
                        const searchMatch = !search || 
                            m.name.toLowerCase().includes(search) || 
                            m.desc.toLowerCase().includes(search);
                        
                        return categoryMatch && searchMatch;
                    });
                    
                    if (this.merchSort === 'name') {
                        filtered.sort((a, b) => a.name.localeCompare(b.name));
                    } else if (this.merchSort === 'category') {
                        filtered.sort((a, b) => a.category.localeCompare(b.category));
                    } else if (this.merchSort === 'rating') {
                        filtered.sort((a, b) => {
                            const ratingA = parseFloat(a.calculated_rating || '0');
                            const ratingB = parseFloat(b.calculated_rating || '0');
                            return ratingB - ratingA;
                        });
                    }
                    
                    this._cachedSortedMerch = filtered;
                    this._lastMerchCacheKey = cacheKey;
                    return filtered;
                };
                
                return sort();
            },
            
            get pagedMerch() {
                const pageKey = `${this.getMerchCacheKey()}-${this.merchPage}`;
                
                if (this._cachedPagedMerch.has(pageKey)) {
                    return this._cachedPagedMerch.get(pageKey);
                }
                
                const start = (this.merchPage - 1) * this.merchPerPage;
                const result = this.sortedMerch.slice(start, start + this.merchPerPage);
                
                if (this._cachedPagedMerch.size > 20) {
                    const firstKey = this._cachedPagedMerch.keys().next().value;
                    this._cachedPagedMerch.delete(firstKey);
                }
                this._cachedPagedMerch.set(pageKey, result);
                
                return result;
            },
            
            get merchTotalPages() {
                return Math.max(1, Math.ceil(this.sortedMerch.length / this.merchPerPage));
            },
            
            // Aggressive cache invalidation
            invalidateCache(type = 'both') {
                if (type === 'food' || type === 'both') {
                    this._cachedSortedFoods = null;
                    this._lastFoodCacheKey = '';
                    this._cachedPagedFoods.clear();
                }
                if (type === 'merch' || type === 'both') {
                    this._cachedSortedMerch = null;
                    this._lastMerchCacheKey = '';
                    this._cachedPagedMerch.clear();
                }
            },
            
            // Preload data for tabs
            async preloadTab(tabName) {
                if (this._preloadedTabs.has(tabName)) return;
                
                return new Promise((resolve) => {
                    requestAnimationFrame(() => {
                        try {
                            if (tabName === 'food') {
                                // Force computation
                                this.sortedFoods;
                                this.pagedFoods;
                            } else if (tabName === 'merch') {
                                // Force computation
                                this.sortedMerch;
                                this.pagedMerch;
                            }
                            this._preloadedTabs.add(tabName);
                            resolve();
                        } catch (e) {
                            console.error('Preload error:', e);
                            resolve();
                        }
                    });
                });
            },
            
            // Enhanced tab switching with better UX
            async switchTab(newTab) {
                if (this.tab === newTab) return;
                
                // Update URL to preserve tab state
                const url = new URL(window.location);
                url.searchParams.set('tab', newTab);
                window.history.pushState(null, '', url);
                
                // Start loading state immediately
                this.isLoading = true;
                this.blurActive();
                
                // Use shorter delay for better perceived performance
                await new Promise(resolve => setTimeout(resolve, 50));
                
                // Switch tab
                this.tab = newTab;
                
                // Preload the new tab data in parallel
                const preloadPromise = this.preloadTab(newTab);
                
                // Animate cards preparation
                const animationPromise = new Promise(resolve => {
                    this.$nextTick(() => {
                        this.animateCards();
                        resolve();
                    });
                });
                
                // Wait for both operations
                await Promise.all([preloadPromise, animationPromise]);
                
                // End loading state
                this.isLoading = false;
            },
            
            // Optimized card animation with staggering
            animateCards() {
                requestAnimationFrame(() => {
                    const selector = this.tab === 'food' ? '.food-card' : '.merch-card';
                    const cards = document.querySelectorAll(selector);
                    
                    // Use DocumentFragment for better performance if many cards
                    cards.forEach((card, index) => {
                        if (card) {
                            // Reset animation
                            card.style.animation = 'none';
                            card.style.animationDelay = '0ms';
                            
                            // Force reflow
                            card.offsetHeight;
                            
                            // Apply staggered animation
                            const delay = Math.min(index * 30, 300); // Cap max delay
                            card.style.animationDelay = `${delay}ms`;
                            card.style.animation = 'fadeInUp 0.4s ease-out forwards';
                        }
                    });
                });
            },
            
            // Debounced search to reduce lag
            debounceSearch(searchFn, delay = 300) {
                let timeoutId;
                return function(...args) {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => searchFn.apply(this, args), delay);
                };
            },
            
            openEditModal(product, action) {
                this.editProduct = product;
                this.editFormAction = action;
                this.showEditModal = true;
            },
            
            submitAddProduct() {
                const form = this.$refs.addForm;
                const formData = new FormData(form);
                fetch('{{ route('catalog.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.product) {
                        if (data.product.type === 'food') {
                            this.food.push(data.product);
                            this.invalidateCache('food');
                        } else if (data.product.type === 'merch') {
                            this.merchandise.push(data.product);
                            this.invalidateCache('merch');
                        }
                        this.showAddModal = false;
                        form.reset();
                    } else {
                        alert('Failed to add product.');
                    }
                })
                .catch(() => alert('Error adding product.'));
            },
            
            showCropper: false,
            cropper: null,
            croppedBlob: null,
            croppedUrl: '',
            startCrop(event) {
                this.showCropper = true;
                this.croppedBlob = null;
                this.croppedUrl = '';
                let img = document.getElementById('edit-cropper-img');
                if (this.cropper) this.cropper.destroy();
                const file = event.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    this.$nextTick(() => {
                        this.cropper = new Cropper(img, {
                            aspectRatio: 4/3,
                            viewMode: 0,
                            autoCropArea: 1,
                            zoomOnWheel: true,
                        });
                    });
                };
                reader.readAsDataURL(file);
            },
            
            finishCrop() {
                if (this.cropper) {
                    this.cropper.getCroppedCanvas().toBlob(blob => {
                        this.croppedBlob = blob;
                        this.croppedUrl = URL.createObjectURL(blob);
                    });
                }
            },
            
            async handleEditSubmit(e) {
                const form = e.target;
                const formData = new FormData(form);
                if (this.croppedBlob) {
                    formData.set('img', this.croppedBlob, 'cropped.png');
                }
                formData.delete('img_position');
                try {
                    const res = await fetch(this.editFormAction, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    if (res.ok) {
                        window.location.reload();
                    } else {
                        let msg = 'Failed to update product.';
                        try {
                            const data = await res.json();
                            if (data && data.message) msg = data.message;
                        } catch {}
                        alert(msg);
                    }
                } catch (err) {
                    alert('Network error: Failed to update product.');
                }
            },
            
            navigateToReview(productId) {
                if (!productId) return;
                
                const currentState = {
                    source: 'catalog',
                    sourcePage: '/catalog',
                    tab: this.tab,
                    foodFilter: this.foodFilter,
                    merchFilter: this.merchFilter,
                    foodSort: this.foodSort,
                    merchSort: this.merchSort,
                    foodSearch: this.foodSearch,
                    merchSearch: this.merchSearch,
                    foodPage: this.foodPage,
                    merchPage: this.merchPage,
                    scrollPosition: window.scrollY,
                    timestamp: Date.now()
                };
                
                sessionStorage.setItem('catalogState', JSON.stringify(currentState));
                window.location.href = '/product/' + productId;
            },
            
            storeScrollPositionAndNavigate(productUrl) {
                console.log('=== storeScrollPositionAndNavigate CALLED ===');
                console.log('Current tab:', this.tab);
                console.log('Product URL:', productUrl);
                console.log('this context:', this);
                
                const currentState = {
                    source: 'catalog',
                    sourcePage: '/catalog',
                    tab: this.tab,
                    foodFilter: this.foodFilter,
                    merchFilter: this.merchFilter,
                    foodSort: this.foodSort,
                    merchSort: this.merchSort,
                    foodSearch: this.foodSearch,
                    merchSearch: this.merchSearch,
                    foodPage: this.foodPage,
                    merchPage: this.merchPage,
                    scrollPosition: window.scrollY,
                    timestamp: Date.now()
                };
                
                console.log('Storing catalog state with tab:', this.tab, currentState);
                sessionStorage.setItem('catalogState', JSON.stringify(currentState));
                window.location.href = productUrl;
            },
            
            async init() {
                // Check for state restoration first, before any other initialization
                const restoreState = sessionStorage.getItem('restoreCatalogState');
                if (restoreState) {
                    try {
                        const state = JSON.parse(restoreState);
                        console.log('Restoring catalog state:', state);
                        console.log('Current tab before restoration:', this.tab);
                        
                        // Set tab first, before other properties
                        this.tab = state.tab || 'food';
                        console.log('Setting tab to:', this.tab);
                        console.log('Tab after setting:', this.tab);
                        
                        this.foodFilter = state.foodFilter || 'All';
                        this.merchFilter = state.merchFilter || 'All';
                        this.foodSort = state.foodSort || '';
                        this.merchSort = state.merchSort || '';
                        this.foodSearch = state.foodSearch || '';
                        this.merchSearch = state.merchSearch || '';
                        this.foodSearchInput = state.foodSearch || '';
                        this.merchSearchInput = state.merchSearch || '';
                        this.foodPage = state.foodPage || 1;
                        this.merchPage = state.merchPage || 1;
                        
                        // Force a DOM update to ensure tab change is applied
                        await this.$nextTick();
                        
                        // Restore scroll position after a longer delay
                        setTimeout(() => {
                            if (state.scrollPosition) {
                                window.scrollTo(0, state.scrollPosition);
                            }
                        }, 300);
                        
                        sessionStorage.removeItem('restoreCatalogState');
                        console.log('State restoration completed. Current tab:', this.tab);
                    } catch (e) {
                        console.error('Error restoring catalog state:', e);
                    }
                }
                
                // Aggressive preloading
                await this.$nextTick();
                
                // Preload current tab first
                await this.preloadTab(this.tab);
                
                // Then preload the other tab in the background
                requestIdleCallback(() => {
                    const otherTab = this.tab === 'food' ? 'merch' : 'food';
                    this.preloadTab(otherTab);
                });
                
                // Initial animation
                this.$nextTick(() => {
                    this.animateCards();
                });
            },
            
            getAverageRating(product) {
                if (product.calculated_rating !== undefined) {
                    return product.calculated_rating;
                }
                return '0.0';
            },
            
            // Enhanced search method with backend option
            async performSearch() {
                // Always use frontend search for now to prevent reload issues
                this.performFrontendSearch();
            },
            
            // Original frontend search (existing implementation)
            performFrontendSearch() {
                if (this.tab === 'food') {
                    this.invalidateCache('food');
                    this.foodSearch = this.foodSearchInput.trim();
                    this.foodPage = 1;
                    
                    this.$nextTick(() => {
                        this.animateCards();
                    });
                } else {
                    this.invalidateCache('merch');
                    this.merchSearch = this.merchSearchInput.trim();
                    this.merchPage = 1;
                    
                    this.$nextTick(() => {
                        this.animateCards();
                    });
                }
                
                this.showFoodPredictions = false;
                this.showMerchPredictions = false;
            },
            
            // New backend search implementation
            async performBackendSearch() {
                this.isSearching = true;
                
                try {
                    const searchParams = new URLSearchParams({
                        query: this.tab === 'food' ? this.foodSearchInput.trim() : this.merchSearchInput.trim(),
                        type: this.tab === 'food' ? 'food' : 'merch',
                        category: this.tab === 'food' ? this.foodFilter : this.merchFilter,
                        sort: this.tab === 'food' ? this.foodSort : this.merchSort,
                        page: 1,
                        per_page: this.tab === 'food' ? this.foodPerPage : this.merchPerPage
                    });
                    
                    const response = await fetch(`/search/catalog?${searchParams}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        // Update the data
                        if (this.tab === 'food') {
                            this.food = data.products;
                            this.foodSearch = this.foodSearchInput.trim();
                            this.foodPage = data.pagination.current_page;
                        } else {
                            this.merchandise = data.products;
                            this.merchSearch = this.merchSearchInput.trim();
                            this.merchPage = data.pagination.current_page;
                        }
                        
                        // Clear caches since we have new data
                        this.invalidateCache();
                        
                        console.log('Backend search completed:', data.products.length, 'results');
                        
                        this.$nextTick(() => {
                            this.animateCards();
                        });
                    } else {
                        console.error('Backend search failed:', response.status);
                        // Fallback to frontend search
                        this.performFrontendSearch();
                    }
                } catch (error) {
                    console.error('Backend search error:', error);
                    // Fallback to frontend search
                    this.performFrontendSearch();
                } finally {
                    this.isSearching = false;
                    this.showFoodPredictions = false;
                    this.showMerchPredictions = false;
                }
            },
            
            // Enhanced clear search with backend support
            async clearSearch() {
                console.log('=== CLEAR SEARCH ===');
                console.log('Current tab:', this.tab);
                
                if (this.tab === 'food') {
                    this.foodSearchInput = '';
                    this.foodSearch = '';
                    this.foodPage = 1;
                } else {
                    this.merchSearchInput = '';
                    this.merchSearch = '';
                    this.merchPage = 1;
                }
                
                // If we were using backend search, reload the original data
                if (this.useBackendSearch) {
                    await this.loadOriginalData();
                } else {
                    this.invalidateCache();
                    this.$nextTick(() => {
                        this.animateCards();
                    });
                }
                
                this.showFoodPredictions = false;
                this.showMerchPredictions = false;
            },
            
            // Load original data from backend
            async loadOriginalData() {
                this.isSearching = true;
                
                try {
                    const response = await fetch('/catalog/data', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.food = data.food || [];
                        this.merchandise = data.merchandise || [];
                        this.invalidateCache();
                        
                        this.$nextTick(() => {
                            this.animateCards();
                        });
                    }
                } catch (error) {
                    console.error('Failed to load original data:', error);
                } finally {
                    this.isSearching = false;
                }
            },
            
            // Method to toggle between frontend and backend search
            toggleSearchMode() {
                this.useBackendSearch = !this.useBackendSearch;
                console.log('Search mode:', this.useBackendSearch ? 'Backend' : 'Frontend');
            },
            
            // Optimized watchers with debouncing
            $watch: {
                foodSort: {
                    handler() {
                        this.invalidateCache('food');
                        this.foodPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                foodFilter: {
                    handler() {
                        this.invalidateCache('food');
                        this.foodPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                foodSearch: {
                    handler() {
                        this.invalidateCache('food');
                        this.foodPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                merchSort: {
                    handler() {
                        this.invalidateCache('merch');
                        this.merchPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                merchFilter: {
                    handler() {
                        this.invalidateCache('merch');
                        this.merchPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                merchSearch: {
                    handler() {
                        this.invalidateCache('merch');
                        this.merchPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                foodPage: {
                    handler() {
                        if (this.tab === 'food') {
                            this.$nextTick(() => this.animateCards());
                        }
                    }
                },
                merchPage: {
                    handler() {
                        if (this.tab === 'merch') {
                            this.$nextTick(() => this.animateCards());
                        }
                    }
                }
            }
        }
    });
});
</script>

<!-- Add search mode indicator for admins -->
@if(auth()->check() && auth()->user()->role === 'admin')
    <div class="fixed bottom-4 left-4 z-40">
        <button @click="toggleSearchMode()" 
                :class="useBackendSearch ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-600 hover:bg-gray-700'"
                class="px-3 py-2 text-white text-xs rounded-lg shadow-lg transition-colors">
            <span x-text="useBackendSearch ? 'Backend Search' : 'Frontend Search'"></span>
        </button>
    </div>
@endif

<!-- Loading overlay for backend search -->
<div x-show="isSearching" x-cloak data-initial-hidden class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-40">
    <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
        <div class="rounded-full h-12 w-12 border-4 border-teal-200 border-t-teal-600 mb-4"></div>
        <p class="text-gray-600 font-medium">Searching...</p>
    </div>
</div>

@include('components.footer')
</body>
</html>


