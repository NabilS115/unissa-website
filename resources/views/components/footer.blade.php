<button id="backToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'});" class="fixed bottom-6 right-6 px-4 py-2 rounded bg-[#0d9488] text-white font-semibold border border-white shadow hover:bg-white hover:text-[#0d9488] transition z-50" style="display:none;">
    ↑
</button>

@if(auth()->check() && auth()->user()->role === 'admin')
    <!-- Admin Edit Footer Button -->
    <div class="fixed bottom-6 left-6 z-50">
        <a href="{{ route('content.footer') }}" 
           class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 opacity-100"
           style="background-color: #0d9488 !important; opacity: 1 !important; z-index: 9999;">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Footer
        </a>
    </div>
@endif

<script src="/js/footer.js"></script>

<footer class="w-full bg-teal-600 text-white py-8 px-4 flex flex-col md:flex-row items-center justify-between gap-8" style="background-color:#0d9488;">
    @php
        // Simple logic: Check current URL patterns to determine branding
        $isUnissaPage = request()->is('unissa-cafe') || request()->is('unissa-cafe/*') || 
                       request()->is('products/*') || request()->is('product/*') || 
                       request()->is('admin/orders*') || request()->is('admin/products*') || 
                       request()->is('cart') || request()->is('cart/*') || 
                       request()->is('checkout') || request()->is('checkout/*') || 
                       request()->is('my/orders*') ||
                       request()->is('printing') || request()->is('printing/*');
        
        // For profile pages, check session context (set by navigation)
        $isProfilePage = request()->is('profile') || request()->is('admin-profile') || request()->is('edit-profile');
        $sessionContext = session('header_context', 'tijarah');
        
        // Final decision: Unissa branding if on Unissa pages OR profile with Unissa context
        $shouldShowUnissaBranding = $isUnissaPage || ($isProfilePage && $sessionContext === 'unissa-cafe');
    @endphp
    
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-white rounded-full p-2 shadow-md flex items-center justify-center overflow-hidden">
            @if($shouldShowUnissaBranding)
                <img src="{{ asset('images/UNISSA_CAFE.png') }}" alt="UNISSA Cafe Logo" class="w-full h-full object-contain" loading="lazy" onerror="this.style.display='none'">
            @else
                <img src="{{ asset('images/tijarahco_sdn_bhd_logo.png') }}" alt="TIJARAH CO SDN BHD Logo" class="w-full h-full object-contain" loading="lazy" onerror="this.style.display='none'">
            @endif
        </div>
        <div class="flex flex-col">
            <span class="font-bold text-lg">
                @if($shouldShowUnissaBranding)
                    UNISSA Cafe
                @else
                    TIJARAH CO SDN BHD
                @endif
            </span>
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-8 w-full md:w-auto justify-center">
    <div class="bg-[#007070] bg-opacity-80 rounded-2xl px-8 py-6 flex flex-col justify-center min-w-[160px]">
            @if($shouldShowUnissaBranding)
                <!-- UNISSA Cafe Footer Navigation -->
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
            <span class="font-bold mb-2">{{ \App\Models\ContentBlock::get('footer_follow_us_title', 'Follow Us', 'text', $shouldShowUnissaBranding ? 'unissa-footer' : 'tijarah-footer') }}</span>
            @php
                // $isCafe and $isProfile already defined above
            @endphp
            @if($shouldShowUnissaBranding)
                <div class="flex gap-4 mb-4">
                    @if(\App\Models\ContentBlock::get('footer_instagram_url', 'https://www.instagram.com/unissacafe/', 'text', 'unissa-footer'))
                        <a href="{{ \App\Models\ContentBlock::get('footer_instagram_url', 'https://www.instagram.com/unissacafe/', 'text', 'unissa-footer') }}" class="text-white" title="Instagram" target="_blank" rel="noopener">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="2" width="20" height="20" rx="5" fill="none" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <circle cx="17" cy="7" r="1.2" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if(\App\Models\ContentBlock::get('footer_facebook_url', '', 'text', 'unissa-footer'))
                        <a href="{{ \App\Models\ContentBlock::get('footer_facebook_url', '', 'text', 'unissa-footer') }}" class="text-white" title="Facebook" target="_blank" rel="noopener">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span>{{ \App\Models\ContentBlock::get('footer_address_line1', 'Jalan Tungku Link, Gadong BE1410, Universiti Islam Sultan Sharif Ali, Brunei Darussalam', 'text', 'unissa-footer') }}</span>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span>{{ \App\Models\ContentBlock::get('footer_address_line2', 'Jubilee Hall, Gadong Campus', 'text', 'unissa-footer') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 3A2.5 2.5 0 004 5.5v13A2.5 2.5 0 006.5 21h11a2.5 2.5 0 002.5-2.5v-13A2.5 2.5 0 0017.5 3h-11zm0 2h11A.5.5 0 0118 5.5v13a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-13A.5.5 0 016.5 5zM12 19a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    <span>{{ \App\Models\ContentBlock::get('footer_phone', '+673 860 2877', 'text', 'unissa-footer') }}</span>
                </div>
            @else
                <div class="flex gap-4 mb-4">
                    @if(\App\Models\ContentBlock::get('footer_tijarah_instagram_url', 'https://www.instagram.com/tijarahco.bn/', 'text', 'tijarah-footer'))
                        <a href="{{ \App\Models\ContentBlock::get('footer_tijarah_instagram_url', 'https://www.instagram.com/tijarahco.bn/', 'text', 'tijarah-footer') }}" class="text-white hover:text-gray-200" title="Instagram" target="_blank" rel="noopener">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="2" width="20" height="20" rx="5" fill="none" stroke="currentColor" stroke-width="2"/>
                                <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <circle cx="17" cy="7" r="1.2" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if(\App\Models\ContentBlock::get('footer_tijarah_facebook_url', '', 'text', 'tijarah-footer'))
                        <a href="{{ \App\Models\ContentBlock::get('footer_tijarah_facebook_url', '', 'text', 'tijarah-footer') }}" class="text-white hover:text-gray-200" title="Facebook" target="_blank" rel="noopener">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span>{{ \App\Models\ContentBlock::get('footer_tijarah_address_line1', 'Jalan Tungku Link, Gadong BE1410, Universiti Islam Sultan Sharif Ali, Brunei Darussalam', 'text', 'tijarah-footer') }}</span>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M2 6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm2 0v.01l8 5.99 8-5.99V6H4zm16 2.24l-8 5.99-8-5.99V18h16V8.24z"/></svg>
                    <a href="mailto:{{ \App\Models\ContentBlock::get('footer_tijarah_email', 'tijarahco.unissa.edu.bn', 'text', 'tijarah-footer') }}" class="underline">{{ \App\Models\ContentBlock::get('footer_tijarah_email', 'tijarahco.unissa.edu.bn', 'text', 'tijarah-footer') }}</a>
                </div>
                <div class="flex items-center gap-2">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 3A2.5 2.5 0 004 5.5v13A2.5 2.5 0 006.5 21h11a2.5 2.5 0 002.5-2.5v-13A2.5 2.5 0 0017.5 3h-11zm0 2h11A.5.5 0 0118 5.5v13a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-13A.5.5 0 016.5 5zM12 19a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    <span>{{ \App\Models\ContentBlock::get('footer_tijarah_phone', '+673 245 6789', 'text', 'tijarah-footer') }}</span>
                </div>
            @endif
        </div>
    </div>
</footer>
