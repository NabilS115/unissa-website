<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('unissa-cafe.homepage') }}" class="flex items-center">
                    <img src="{{ asset('images/UNISSA_CAFE.png') }}" alt="UNISSA Cafe Logo" class="h-10 w-auto">
                    <span class="ml-3 text-xl font-bold text-gray-900">UNISSA Cafe</span>
                </a>
            </div>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="{{ route('unissa-cafe.homepage') }}" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ Request::is('/') ? 'text-teal-600 bg-teal-50' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('unissa-cafe.catalog') }}" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ Request::is('unissa-cafe/catalog') ? 'text-teal-600 bg-teal-50' : '' }}">
                        Catalog
                    </a>
                    <a href="{{ route('printing.index') }}" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ Request::is('printing*') ? 'text-teal-600 bg-teal-50' : '' }}">
                        🖨️ Printing Services
                    </a>
                    @auth
                        <a href="{{ route('profile') }}" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Profile
                        </a>
                        <a href="{{ route('cart.index') }}" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium transition-colors relative">
                            Cart
                            @if(auth()->check() && \App\Models\Cart::where('user_id', auth()->id())->count() > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') }}
                                </span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            Register
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="bg-gray-900 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" aria-controls="mobile-menu" aria-expanded="false" onclick="toggleMobileMenu()">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu (hidden by default) -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50 border-t">
            <a href="{{ route('unissa-cafe.homepage') }}" class="text-gray-900 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                Home
            </a>
            <a href="{{ route('unissa-cafe.catalog') }}" class="text-gray-900 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                Catalog
            </a>
            <a href="{{ route('printing.index') }}" class="text-gray-900 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                🖨️ Printing Services
            </a>
            @auth
                <a href="{{ route('profile') }}" class="text-gray-900 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Profile
                </a>
                <a href="{{ route('cart.index') }}" class="text-gray-900 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Cart
                    @if(\App\Models\Cart::where('user_id', auth()->id())->count() > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                            {{ \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') }}
                        </span>
                    @endif
                </a>
            @else
                <a href="{{ route('login') }}" class="text-gray-900 hover:text-teal-600 block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-teal-600 hover:bg-teal-700 text-white block px-3 py-2 rounded-md text-base font-medium transition-colors">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>