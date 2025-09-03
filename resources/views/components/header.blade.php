<!-- Reusable Header Component -->
<header class="w-full bg-teal-600 text-white py-4 flex items-center justify-between px-6 header-fallback sticky top-0 z-50">
    <div class="flex items-center gap-4 logo-section">
        
            <div class="w-10 h-10 bg-red-600 border-4 border-black flex items-center justify-center mr-2"></div>
        <h1 class="text-3xl font-bold" style="font-size: 1.875rem; font-weight: bold; margin: 0;">Something Company</h1>
    </div>
    <div class="flex items-center gap-6 ml-12">
        <nav>
            <ul class="flex gap-4 nav-list">
                <li><a href="/" class="text-white hover:underline nav-link">Home</a></li>
                <li><a href="/food-list" class="text-white hover:underline nav-link">Catalog</a></li>
                <li><a href="/company-history" class="text-white hover:underline nav-link">About</a></li>
                <li><a href="/contact" class="text-white hover:underline nav-link">Contact</a></li>
            </ul>
        </nav>
        <div class="relative group" id="searchbar-group">
            <button id="searchbar-icon" class="bg-white text-teal-600 rounded-full p-2 flex items-center justify-center shadow" style="width:40px;height:40px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#008080" class="w-6 h-6">
                    <circle cx="11" cy="11" r="8" stroke-width="2" stroke="#008080" fill="none"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2" stroke="#008080" />
                </svg>
            </button>
            <form id="searchbar-dropdown" class="absolute right-0 top-full mt-2 w-96 flex bg-white rounded shadow transition-all duration-300 opacity-0 pointer-events-none z-50" action="{{ route('search') }}" method="GET">
                <input type="text" name="search" id="main-search-input" placeholder="Search..." class="w-full px-4 py-2 rounded-l-md text-black focus:outline-none" autocomplete="off" />
                <button type="submit" class="bg-white text-teal-600 px-4 py-2 rounded-r-md font-semibold">Search</button>
                <div id="search-suggestions" class="absolute left-0 top-full w-full bg-white border border-teal-200 rounded-b shadow-lg z-50 hidden"></div>
            </form>
            <script>
                const searchInput = document.getElementById('main-search-input');
                const suggestionsBox = document.getElementById('search-suggestions');
                const suggestions = [
                    'Margherita Pizza', 'Caesar Salad', 'Beef Burger', 'Greek Salad', 'Sushi Platter', 'Pad Thai', 'Tacos', 'Falafel Wrap', 'Grilled Salmon', 'Chicken Curry', 'Pasta Carbonara', 'BBQ Ribs', 'Vegetable Stir Fry', 'Lobster Bisque', 'Eggs Benedict', 'Chicken Shawarma', 'Veggie Pizza', 'Shrimp Paella', 'Chocolate Cake', 'Ice Cream Sundae'
                ];
                searchInput.addEventListener('input', function() {
                    const value = this.value.toLowerCase();
                    if (!value) {
                        suggestionsBox.style.display = 'none';
                        suggestionsBox.innerHTML = '';
                        return;
                    }
                    const filtered = suggestions.filter(s => s.toLowerCase().includes(value));
                    if (filtered.length) {
                        suggestionsBox.innerHTML = filtered.map(s => `<div class='px-4 py-2 cursor-pointer hover:bg-teal-50 text-gray-800'>${s}</div>`).join('');
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.style.display = 'none';
                        suggestionsBox.innerHTML = '';
                    }
                });
                suggestionsBox.addEventListener('mousedown', function(e) {
                    if (e.target && e.target.textContent) {
                        searchInput.value = e.target.textContent;
                        suggestionsBox.style.display = 'none';
                        suggestionsBox.innerHTML = '';
                    }
                });
            </script>
            <style>
                #searchbar-dropdown {
                    opacity: 0;
                    pointer-events: none;
                }
                #searchbar-group.active #searchbar-dropdown {
                    opacity: 1;
                    pointer-events: auto;
                }
            </style>
            <script>
                document.addEventListener('click', function(e) {
                    var searchIcon = document.getElementById('searchbar-icon');
                    var searchGroup = document.getElementById('searchbar-group');
                    var searchDropdown = document.getElementById('searchbar-dropdown');
                    var profileIcon = document.getElementById('profileMenuButton');
                    var profileGroup = document.getElementById('profile-group');
                    var profileDropdown = document.getElementById('profileDropdown');
                    // Search icon click
                    if (searchIcon && searchGroup && searchIcon.contains(e.target)) {
                        if (profileGroup) profileGroup.classList.remove('active');
                        searchGroup.classList.toggle('active');
                        e.stopPropagation();
                        return;
                    }
                    // Profile icon click
                    if (profileIcon && profileGroup && profileIcon.contains(e.target)) {
                        if (searchGroup) searchGroup.classList.remove('active');
                        profileGroup.classList.toggle('active');
                        e.stopPropagation();
                        return;
                    }
                    // Click inside search dropdown
                    if (searchDropdown && searchDropdown.contains(e.target)) {
                        return;
                    }
                    // Click inside profile dropdown
                    if (profileDropdown && profileDropdown.contains(e.target)) {
                        return;
                    }
                    // Click outside closes both
                    if (searchGroup) searchGroup.classList.remove('active');
                    if (profileGroup) profileGroup.classList.remove('active');
                });
            </script>
        </div>
        <div class="relative group" id="profile-group">
            <button id="profileMenuButton" class="w-10 h-10 rounded-full bg-white flex items-center justify-center focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#008080" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.001 9.001 0 0112 15c2.21 0 4.21.805 5.879 2.146M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
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
    </div>
</header>