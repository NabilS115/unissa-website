<!-- Reusable Header Component -->
<header class="w-full bg-teal-600 text-white py-4 flex items-center justify-between px-6 header-fallback z-50">
    <div class="flex items-center gap-4 logo-section">
        <div class="w-10 h-10 bg-red-600 border-4 border-black flex items-center justify-center mr-2"></div>
        <h1 class="text-3xl font-bold" style="font-size: 1.875rem; font-weight: bold; margin: 0;">
            @if(request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('admin/orders*') || request()->is('admin/products*'))
                Unissa Cafe
            @else
                Tijarah Co Sdn Bhd
            @endif
        </h1>
    </div>
    <div class="flex items-center gap-6 ml-12">
        <nav>
            <ul class="flex gap-4 nav-list">
                @if(request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('admin/orders*') || request()->is('admin/products*'))
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
            
            <script>
                let searchTimeout;
                const searchInput = document.getElementById('main-search-input');
                const searchScope = document.getElementById('search-scope');
                const suggestionsBox = document.getElementById('search-suggestions');
                
                // Function to highlight search terms
                function highlightText(text, searchTerm) {
                    if (!searchTerm || searchTerm.length < 2) return text;
                    
                    const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                    return text.replace(regex, '<mark class="bg-yellow-200 text-gray-900 font-medium px-1 rounded">$1</mark>');
                }
                
                // Function to fetch suggestions
                async function fetchSuggestions(query, scope) {
                    if (query.length < 2) {
                        suggestionsBox.style.display = 'none';
                        return;
                    }
                    
                    try {
                        const response = await fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}&scope=${scope}`);
                        const suggestions = await response.json();
                        
                        if (suggestions.length > 0) {
                            displaySuggestions(suggestions, query);
                        } else {
                            suggestionsBox.style.display = 'none';
                        }
                    } catch (error) {
                        console.error('Error fetching suggestions:', error);
                        suggestionsBox.style.display = 'none';
                    }
                }
                
                // Function to display suggestions with highlighting
                function displaySuggestions(suggestions, searchTerm) {
                    const html = suggestions.map(suggestion => {
                        const typeIcon = getTypeIcon(suggestion.type);
                        const typeColor = getTypeColor(suggestion.type);
                        
                        // Highlight search terms in title and subtitle
                        const highlightedTitle = highlightText(suggestion.title, searchTerm);
                        const highlightedSubtitle = highlightText(suggestion.subtitle, searchTerm);
                        
                        return `
                            <div class="suggestion-item px-4 py-3 cursor-pointer hover:bg-gray-50 border-b border-gray-100 last:border-b-0" 
                                 data-url="${suggestion.url}">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-6 h-6 ${typeColor} rounded-full flex items-center justify-center">
                                        ${typeIcon}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">${highlightedTitle}</div>
                                        <div class="text-xs text-gray-500 truncate">${highlightedSubtitle}</div>
                                    </div>
                                    <div class="text-xs text-gray-400 capitalize">${suggestion.type}</div>
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    suggestionsBox.innerHTML = html;
                    suggestionsBox.style.display = 'block';
                    
                    // Add click handlers to suggestions
                    document.querySelectorAll('.suggestion-item').forEach(item => {
                        item.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            const url = this.getAttribute('data-url');
                            if (url && url !== '#') {
                                window.location.href = url;
                            }
                        });
                    });
                }
                
                // Helper functions for suggestion display
                function getTypeIcon(type) {
                    switch(type) {
                        case 'product':
                            return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>';
                        case 'review':
                            return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>';
                        case 'user':
                            return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>';
                        default:
                            return '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>';
                    }
                }
                
                function getTypeColor(type) {
                    switch(type) {
                        case 'product': return 'bg-teal-500';
                        case 'review': return 'bg-yellow-500';
                        case 'user': return 'bg-blue-500';
                        default: return 'bg-gray-500';
                    }
                }
                
                // Event listeners
                if (searchInput && searchScope) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        const query = this.value.trim();
                        const scope = searchScope.value;
                        
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
                        // Delay hiding suggestions to allow for clicks
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
            </script>
            
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
            
            <script>
                function initializeHeaderInteractions() {
                    const searchIcon = document.getElementById('searchbar-icon');
                    const searchGroup = document.getElementById('searchbar-group');
                    const searchDropdown = document.getElementById('searchbar-dropdown');
                    const profileIcon = document.getElementById('profileMenuButton');
                    const profileGroup = document.getElementById('profile-group');
                    const profileDropdown = document.getElementById('profileDropdown');

                    // Check if elements exist before proceeding
                    if (!searchIcon || !profileIcon) {
                        console.log('Header elements not ready, retrying...');
                        setTimeout(initializeHeaderInteractions, 100);
                        return;
                    }

                    // Prevent double initialization
                    if (searchIcon.hasAttribute('data-initialized') || profileIcon.hasAttribute('data-initialized')) {
                        return;
                    }

                    // Mark as initialized
                    searchIcon.setAttribute('data-initialized', 'true');
                    profileIcon.setAttribute('data-initialized', 'true');
                    
                    // Search icon click handler
                    if (searchIcon && searchGroup) {
                        searchIcon.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Close profile dropdown if open
                            if (profileGroup) {
                                profileGroup.classList.remove('active');
                            }
                            // Toggle search dropdown
                            searchGroup.classList.toggle('active');
                            
                            // Focus on search input when opened
                            if (searchGroup.classList.contains('active')) {
                                setTimeout(() => {
                                    const searchInput = document.getElementById('main-search-input');
                                    if (searchInput) {
                                        searchInput.focus();
                                    }
                                }, 100);
                            }
                        });
                    }
                    
                    // Profile icon click handler
                    if (profileIcon && profileGroup) {
                        profileIcon.addEventListener('click', function(e) {
                            e.stopPropagation();
                            // Close search dropdown if open
                            if (searchGroup) {
                                searchGroup.classList.remove('active');
                            }
                            // Toggle profile dropdown
                            profileGroup.classList.toggle('active');
                        });
                    }
                    
                    // Click outside to close dropdowns
                    document.addEventListener('click', function(e) {
                        // Check if click is inside search dropdown
                        if (searchDropdown && searchDropdown.contains(e.target)) {
                            return;
                        }
                        
                        // Check if click is inside profile dropdown
                        if (profileDropdown && profileDropdown.contains(e.target)) {
                            return;
                        }
                        
                        // Close both dropdowns if clicking outside
                        if (searchGroup) {
                            searchGroup.classList.remove('active');
                        }
                        if (profileGroup) {
                            profileGroup.classList.remove('active');
                        }
                    });
                    
                    // Prevent search dropdown from closing when clicking inside it
                    if (searchDropdown) {
                        searchDropdown.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    }
                    
                    // Prevent profile dropdown from closing when clicking inside it
                    if (profileDropdown) {
                        profileDropdown.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    }

                    console.log('Header interactions initialized successfully');
                }

                // Initialize on DOM ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initializeHeaderInteractions);
                } else {
                    // DOM is already loaded
                    initializeHeaderInteractions();
                }

                // Also initialize after a short delay to handle dynamic loading
                setTimeout(initializeHeaderInteractions, 500);
            </script>
        </div>
        
        <!-- Cart Icon (only show on cafe pages and for authenticated users) -->
        @if((request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*')) && auth()->check())
        <div class="relative" id="cart-group">
            <a href="{{ route('cart.index') }}" class="bg-white text-teal-600 rounded-full p-2 flex items-center justify-center shadow hover:bg-gray-50 transition-colors relative" style="width:40px;height:40px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                </svg>
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full min-w-[1.5rem] h-6 flex items-center justify-center" style="display: none;">0</span>
            </a>
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
                    <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">My Orders</a>
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
        
        @if((request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*')) && auth()->check())
        <script>
            // Load cart count on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadCartCount();
            });

            function loadCartCount() {
                fetch('/api/cart/count', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count;
                        cartCount.style.display = data.count > 0 ? 'flex' : 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
            }
        </script>
        @endif
    </div>
    </div>
</header>