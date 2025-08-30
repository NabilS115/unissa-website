<!-- Reusable Header Component -->
<header class="w-full bg-teal-600 text-white py-4 flex items-center justify-between px-6 header-fallback sticky top-0 z-50">
    <div class="flex items-center gap-4 logo-section">
        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center logo-circle">
            <span class="text-teal-600 font-bold text-xl" style="color: #0d9488; font-size: 1.25rem; font-weight: bold;">🍽️</span>
        </div>
        <h1 class="text-3xl font-bold" style="font-size: 1.875rem; font-weight: bold; margin: 0;">Welcome to the Food Catalog</h1>
    </div>
    <div class="flex-1 flex justify-center items-center">
        <form class="w-full max-w-md flex" action="#" method="GET">
            <input type="text" name="search" placeholder="Search..." class="w-full px-4 py-2 rounded-l-md text-black focus:outline-none" />
            <button type="submit" class="bg-white text-teal-600 px-4 py-2 rounded-r-md font-semibold">Search</button>
        </form>
    </div>
    <nav>
        <ul class="flex gap-4 nav-list">
            <li><a href="/" class="text-white hover:underline nav-link">Home</a></li>
            <li><a href="/food-list" class="text-white hover:underline nav-link">Catalog</a></li>
            <li><a href="#" class="text-white hover:underline nav-link">About</a></li>
            <li><a href="#" class="text-white hover:underline nav-link">Contact</a></li>
        </ul>
    </nav>
    <div class="relative ml-12">
            <button id="profileMenuButton" class="w-10 h-10 rounded-full bg-white flex items-center justify-center focus:outline-none" onclick="document.getElementById('profileDropdown').classList.toggle('hidden')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#008080" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9.001 9.001 0 0112 15c2.21 0 4.21.805 5.879 2.146M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
            <div id="profileDropdown" class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg py-2 hidden z-50">
                <a href="/login" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">Login</a>
                <a href="/register" class="block px-4 py-2 text-teal-600 hover:bg-teal-50">Register</a>
            </div>
        </div>
    </div>
</header>