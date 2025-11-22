<button id="backToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'});" class="fixed bottom-6 right-6 px-4 py-2 rounded bg-[#0d9488] text-white font-semibold border border-white shadow hover:bg-white hover:text-[#0d9488] transition z-50" style="display:none;">
    ↑
</button>
<script src="/js/footer.js"></script>

<footer class="w-full bg-teal-600 text-white py-8 px-4 flex flex-col md:flex-row items-center justify-between gap-8" style="background-color:#0d9488;">
    @php
        $footerContext = session('header_context', 'tijarah');
        $referer = request()->headers->get('referer');
        $isCafePage = request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || request()->is('products/*') || request()->is('product/*') || request()->is('admin/orders*') || request()->is('admin/products*') || request()->is('cart') || request()->is('cart/*') || request()->is('checkout') || request()->is('checkout/*') || request()->is('my/orders*');
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
            $shouldShowUnissaBranding = $isCafePage || ($isProfilePage && $footerContext === 'unissa-cafe');
        }
    @endphp
    
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-white rounded-full p-1 shadow-md flex items-center justify-center">
            @if($shouldShowUnissaBranding)
                <img src="{{ asset('images/UNISSA_CAFE.png') }}" alt="Unissa Cafe Logo" class="w-full h-full object-contain">
            @else
                <img src="{{ asset('images/TIJARAH_CO_SDN_BHD.png') }}" alt="Tijarah Co Sdn Bhd Logo" class="w-full h-full object-contain">
            @endif
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-lg">
                @if($shouldShowUnissaBranding)
                    Unissa Cafe
                @else
                    Tijarah Co Sdn Bhd
                @endif
            </span>
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-8 w-full md:w-auto justify-center">
    <div class="bg-[#007070] bg-opacity-80 rounded-2xl px-8 py-6 flex flex-col justify-center min-w-[160px]">
            @if($shouldShowUnissaBranding)
                <!-- Unissa Cafe Footer Navigation -->
                <a href="{{ route('unissa-cafe.homepage') }}" class="text-white mb-2 hover:underline">Homepage</a>
                <a href="{{ route('unissa-cafe.catalog') }}" class="text-white mb-2 hover:underline">Catalog</a>
                <a href="{{ route('privacy-policy') }}" class="text-white mb-2 hover:underline">Privacy Policy</a>
                <a href="{{ route('terms-of-service') }}" class="text-white mb-2 hover:underline">Terms of Service</a>
                <a href="/" class="text-white hover:underline">← Back to Tijarah</a>
            @else
                <!-- Tijarah Footer Navigation -->
                <a href="/" class="text-white mb-2 hover:underline">Home</a>
                <a href="/company-history" class="text-white mb-2 hover:underline">About</a>
                <a href="/contact" class="text-white mb-2 hover:underline">Contact Us</a>
                <a href="{{ route('privacy-policy') }}" class="text-white mb-2 hover:underline">Privacy Policy</a>
                <a href="{{ route('terms-of-service') }}" class="text-white hover:underline">Terms of Service</a>
            @endif
        </div>
        <div class="bg-[#007070] bg-opacity-80 rounded-2xl px-8 py-6 flex flex-col items-center min-w-[200px]">
            <span class="font-bold mb-2">Follow Us</span>
            @php
                // $isCafe and $isProfile already defined above
            @endphp
            @if($shouldShowUnissaBranding)
                <div class="flex gap-4 mb-4">
                    <a href="https://www.instagram.com/unissacafe/" class="text-white" title="Instagram" target="_blank" rel="noopener">
                                                <!-- Refined Instagram SVG -->
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="2" y="2" width="20" height="20" rx="5" fill="none" stroke="currentColor" stroke-width="2"/>
                                                    <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2" fill="none"/>
                                                    <circle cx="17" cy="7" r="1.2" fill="currentColor"/>
                                                </svg>
                    </a>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span>Jalan Tungku Link, Gadong BE1410, Universiti Islam Sultan Sharif Ali, Brunei Darussalam</span>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span>Jubilee Hall, Gadong Campus</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 3A2.5 2.5 0 004 5.5v13A2.5 2.5 0 006.5 21h11a2.5 2.5 0 002.5-2.5v-13A2.5 2.5 0 0017.5 3h-11zm0 2h11A.5.5 0 0118 5.5v13a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-13A.5.5 0 016.5 5zM12 19a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    <span>+673 860 2877</span>
                </div>
            @else
                <div class="flex gap-4 mb-4">
                    <a href="" class="text-white" title="Instagram" target="_blank" rel="noopener">
                                                <!-- Refined Instagram SVG -->
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="2" y="2" width="20" height="20" rx="5" fill="none" stroke="currentColor" stroke-width="2"/>
                                                    <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2" fill="none"/>
                                                    <circle cx="17" cy="7" r="1.2" fill="currentColor"/>
                                                </svg>
                    </a>
                    <a href="" class="text-white" title="Facebook" target="_blank" rel="noopener">
                        <!-- Facebook Official SVG -->
                        <svg width="24" height="24" viewBox="0 0 320 512" fill="currentColor"><path d="M279.14 288l14.22-92.66h-88.91V127.89c0-25.35 12.42-50.06 52.24-50.06H293V6.26S259.5 0 225.36 0c-73.22 0-121.36 44.38-121.36 124.72V195.3H22.89V288h81.11v224h100.2V288z"/></svg>
                    </a>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span>Jalan Tungku Link, Gadong BE1410, Universiti Islam Sultan Sharif Ali, Brunei Darussalam</span>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm2 0v.01l8 5.99 8-5.99V6H4zm16 2.24l-8 5.99-8-5.99V18h16V8.24z"/></svg>
                    <a href="mailto:info@tijarahco.com" class="underline">tijarahco.unissa.edu.bn</a>
                </div>
                <div class="flex items-center gap-2">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 3A2.5 2.5 0 004 5.5v13A2.5 2.5 0 006.5 21h11a2.5 2.5 0 002.5-2.5v-13A2.5 2.5 0 0017.5 3h-11zm0 2h11A.5.5 0 0118 5.5v13a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-13A.5.5 0 016.5 5zM12 19a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    <span>+673 245 6789</span>
                </div>
            @endif
        </div>
    </div>
</footer>
