@extends('layouts.app')

@section('title', 'Tijarah Co')

@section('content')
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Edit Button -->
        <div class="fixed top-20 right-4 z-50">
            <a href="{{ route('content.homepage') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
               style="background-color: #0d9488 !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Page
            </a>
        </div>
    @endif
    <!-- Hero Banner Section -->
    <section class="w-full h-80 flex flex-col items-center justify-center mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <img src="{!! \App\Models\ContentBlock::get('hero_background_image', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80', 'text', 'homepage') !!}" alt="Hero Banner" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow-lg mb-4">{!! \App\Models\ContentBlock::get('hero_title', 'Business with Barakah', 'text', 'homepage') !!}</h1>
            <p class="text-lg md:text-xl text-white drop-shadow-md mb-6">Promoting halal, ethical, and impactful entrepreneurship through UNISSA’s Tijarah Co.</p>
            <a href="{{ route('unissa-cafe.homepage') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105" style="background-color:#0d9488;">
                Visit Unissa Cafe
                <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="flex justify-end mb-8">
                <div class="flex gap-2">
                    <button id="add-gallery-btn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-2xl text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105" style="background-color: #059669 !important;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Image
                    </button>
                    <button id="manage-gallery-btn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105" style="background-color: #0d9488 !important;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Manage Images
                    </button>
                </div>
            </div>
        @endif
        
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative group" style="min-height: 420px;">
            <div id="event-bg-carousel" class="absolute inset-0 w-full h-full overflow-hidden z-0" style="display: none;">
                <div id="event-bg-track" class="flex w-full h-full transition-transform duration-700">
                    <!-- Slides will be rendered by JS -->
                </div>
            </div>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
                <!-- Admin Controls for Current Image -->
                <div class="absolute top-4 right-4 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <div class="flex gap-2">
                        <button id="edit-current-gallery-btn" class="w-10 h-10 bg-teal-500 hover:bg-teal-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors" title="Edit Image">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button id="delete-current-gallery-btn" class="w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors" title="Delete Image">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            <!-- Empty State for Gallery - Show by default -->
            <div id="gallery-empty-state" class="flex w-full h-full items-center justify-center p-8" style="min-height: 420px;">
                <div class="text-center max-w-md mx-auto">
                    <div class="mb-6">
                        <svg class="w-20 h-20 mx-auto text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Photo Gallery</h3>
                    <p class="text-gray-500 mb-6">This section will showcase beautiful images from our company and activities.</p>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <button onclick="document.getElementById('add-gallery-btn').click()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105" style="background-color:#0d9488;">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Your First Image
                        </button>
                    @else
                        <div class="inline-flex items-center px-4 py-2 bg-teal-50 text-teal-600 rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Gallery coming soon
                        </div>
                    @endif
                </div>
            </div>

            <div id="gallery-carousel-controls" class="w-full flex items-center justify-center relative bg-transparent py-8 z-10" style="min-height: 420px; display: none;">
                <!-- Carousel Controls (right) -->
                <button id="event-carousel-next"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none rounded bg-white shadow p-2 z-20">
                    &#8250;
                </button>
                <!-- Carousel Controls (left) -->
                <button id="event-carousel-prev"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none rounded bg-white shadow p-2 z-20">
                    &#8249;
                </button>
                <!-- Carousel Dots -->
                <div id="event-carousel-dots" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-20">
                    <!-- Dots will be rendered by JS -->
                </div>
            </div>
        </div>
    </section>



    <!-- About Summary Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-gradient-to-br from-teal-50 to-green-50 rounded-2xl shadow-lg overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <!-- Content -->
                <div class="p-8 lg:p-12">
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-3xl font-bold text-teal-700">About Tijarah Co</h2>
                    </div>
                    
                    <div class="text-gray-700 text-lg leading-relaxed mb-6">
                        {!! \App\Models\ContentBlock::get('about_content', '<p>Welcome to TIJARAH CO SDN BHD, established under UNISSA, is dedicated to fostering entrepreneurship, innovation, and halal trade. We provide a platform for students, alumni, and the community to develop businesses, showcase products, and grow sustainably in line with Islamic values.</p>', 'html', 'homepage') !!}
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/about" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            Learn More About Us
                        <style>
                            a[href="/about"] { background-color: #0d9488 !important; }
                        </style>
                            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="/catalog" class="inline-flex items-center px-6 py-3 bg-white hover:bg-gradient-to-r hover:from-teal-50 hover:via-emerald-50 hover:to-cyan-50 text-teal-600 font-semibold rounded-2xl border-2 border-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            View Our Catalog
                        <style>
                            a[href="/catalog"] { background-color: #fff !important; }
                        </style>
                        </a>
                    </div>
                </div>
                
                <!-- Tijarah Logo -->
                <div class="relative h-64 lg:h-full flex items-center justify-center bg-gradient-to-br from-emerald-50 to-teal-100 p-4">
                    <div class="w-full max-w-sm md:max-w-md lg:max-w-lg aspect-square flex items-center justify-center">
                        <img src="{{ asset(\App\Models\ContentBlock::get('about_logo', 'images/tijarahco_sdn_bhd.png', 'text', 'homepage')) }}" alt="TIJARAH CO SDN BHD Logo" class="w-full h-full object-contain" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Summary Section -->
    <section class="w-full bg-gradient-to-br from-teal-50 to-emerald-50 py-16 mb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-teal-800 mb-4">{!! \App\Models\ContentBlock::get('contact_title', 'Get In Touch', 'text', 'homepage') !!}</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Address -->
                <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 hover:border-teal-200 p-6 text-center hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Address</h3>
                    <div class="text-gray-600 text-sm">{!! \App\Models\ContentBlock::get('contact_address', 'Universiti Islam Sultan Sharif Ali<br>Simpang 347, Jalan Pasar Gadong<br>Bandar Seri Begawan, Brunei', 'html', 'homepage') !!}</div>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 hover:border-teal-200 p-6 text-center hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone</h3>
                    <p class="text-gray-600 text-sm">{!! \App\Models\ContentBlock::get('contact_phone', '+673 123 4567', 'text', 'homepage') !!}</p>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 hover:border-teal-200 p-6 text-center hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600 text-sm">{!! \App\Models\ContentBlock::get('contact_email', 'tijarahco@unissa.edu.bn', 'text', 'homepage') !!}</p>
                </div>

                <!-- Hours -->
                <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 hover:border-teal-200 p-6 text-center hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Hours</h3>
                    <div class="text-gray-600 text-sm">{!! \App\Models\ContentBlock::get('contact_hours', 'Mon-Thu & Sat<br>9:00am - 4:30pm', 'html', 'homepage') !!}</div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/contact" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                <!-- Refined Mail/Message Icon -->
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="2" fill="none"/>
                                                    <path d="M3 7l9 6 9-6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                        Send Us a Message
                    </a>
                    <!-- Call Now button removed per request -->
                </div>
            </div>
        </div>
    </section>

    <script>
        // Track authentication state to handle login/logout
        let currentAuthState = @json(auth()->check());
        let currentUserRole = @json(auth()->check() ? auth()->user()->role : null);

        // Function to refresh all carousels after DOM changes
        let isRefreshing = false;
        let refreshTimeout = null;
        
        function refreshAllCarousels() {
            // Clean, simple refresh without triggers that cause loops
            
            try {
                // Reset carousel positions to starting state
                const bgTrack = document.getElementById('event-bg-track');
                
                if (bgTrack) {
                    bgTrack.style.transform = '';
                    bgTrack.style.transition = 'none';
                }
                
                // Reset current positions
                if (typeof currentEvent !== 'undefined') {
                    currentEvent = 0;
                }
                
                // Re-render carousels without triggering events
                if (typeof renderEventBgCarousel === 'function') {
                    renderEventBgCarousel();
                }
                
                if (typeof resetEventInterval === 'function') {
                    resetEventInterval();
                }
                

                
                // Force recalculation of container dimensions  
                const galleryContainer = document.querySelector('.bg-white.rounded-2xl');
                if (galleryContainer) {
                    galleryContainer.style.width = '';
                    galleryContainer.style.height = '';
                    void galleryContainer.offsetHeight; // Trigger reflow
                }
                
                // Ensure gallery management buttons are working after refresh
                setTimeout(() => {
                    if (typeof attachGalleryHandlers === 'function') {
                        attachGalleryHandlers();
                    } else if (typeof ensureGalleryButtons === 'function') {
                        ensureGalleryButtons();
                    }
                }, 100);
                
            } catch (error) {
                // Error handling - continue silently
            }
        }

        // Simplified authentication handling - no automatic monitoring
        function checkAuthStateChange() {
            // Disabled to prevent infinite loops
        }

        // Smart authentication state monitoring - only check when needed
        let lastAuthCheck = Date.now();
        
        function checkAndRefreshOnAuthChange() {
            // Only check every 30 seconds to avoid spam
            if (Date.now() - lastAuthCheck < 30000) {
                return;
            }
            lastAuthCheck = Date.now();
            
            fetch('/api/auth-status')
                .then(response => response.json())
                .then(data => {
                    if (data.authenticated !== currentAuthState || data.role !== currentUserRole) {
                        currentAuthState = data.authenticated;
                        currentUserRole = data.role;
                        
                        // Use clean refresh without problematic triggers
                        setTimeout(refreshAllCarousels, 100);
                    }
                })
                .catch(error => {
                    // Ignore errors silently
                });
        }
        
        // Only check auth state on user interaction (not automatically)
        document.addEventListener('click', checkAndRefreshOnAuthChange);
        document.addEventListener('focus', checkAndRefreshOnAuthChange);

        // Force page reload if user logs in/out during the session
        // This ensures DOM matches the authentication state
        let loginFormSubmitted = false;
        
        // Monitor login/logout form submissions specifically
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.action && (form.action.includes('/login') || form.action.includes('/logout'))) {
                // After login/logout form submission, check auth state and refresh
                setTimeout(() => {
                    checkAndRefreshOnAuthChange();
                }, 1000);
            }
        });

        // DOM observer disabled to prevent infinite refresh loops
        // const observer = new MutationObserver(...) - DISABLED
        
        // DOM observation disabled to prevent loops
        // observer.observe(...) - DISABLED

        // Resize event listener DISABLED to prevent infinite loops
        // let resizeTimeout;
        // window.addEventListener('resize', ...) - DISABLED

        // Header observation disabled to prevent infinite loops
        // All automatic monitoring is disabled

        // Automatic admin element checking disabled to prevent loops

        // Enhanced gallery data from database or default
        let galleryData = @json($galleryImages ?? []);
        
        // Function to reload gallery data from server
        async function loadEventImages() {
            try {
                const response = await fetch('/gallery');
                if (response.ok) {
                    const data = await response.json();
                    galleryData = data;
                    
                    // Update eventImages array
                    if (galleryData && galleryData.length > 0) {
                        eventImages = galleryData.map(item => {
                            return {
                                id: item.id,
                                image: item.image_url,
                                active: item.is_active,
                                order: item.sort_order
                            };
                        });
                    } else {
                        eventImages = [];
                    }
                } else {
                    console.error('Failed to load gallery data');
                }
            } catch (error) {
                console.error('Error loading gallery data:', error);
            }
        }
        
        // Map gallery data to the expected format
        let eventImages = [];
        if (galleryData && galleryData.length > 0) {
            eventImages = galleryData.map(item => {
                return {
                    id: item.id,
                    image: item.image_url,
                    active: item.is_active,
                    order: item.sort_order
                };
            });
        }
        
        let currentEvent = 0;
        const bgTrack = document.getElementById('event-bg-track');
        const prevBtn = document.getElementById('event-carousel-prev');
        const nextBtn = document.getElementById('event-carousel-next');
        const dotsEl = document.getElementById('event-carousel-dots');
        let eventInterval = null;

        // Carousel functions
        function renderEventBgCarousel() {
            const galleryContainer = document.querySelector('.bg-white.rounded-2xl');
            const bgTrack = document.getElementById('event-bg-track');

            
            if (!galleryContainer || !bgTrack) {
                console.error('Gallery elements missing for rendering');
                return;
            }
            
            if (!eventImages || eventImages.length === 0) {
                // Show empty state instead of hiding
                galleryContainer.style.display = 'block';
                showGalleryEmptyState();
                return;
            }


            galleryContainer.style.display = 'block';
            hideGalleryEmptyState();
            
            bgTrack.innerHTML = '';
            // Clone last slide to the beginning
            const firstClone = document.createElement('div');
            firstClone.className = "min-w-full h-full";
            const lastImageUrl = eventImages[eventImages.length - 1].image;
            firstClone.style.backgroundImage = `url('${lastImageUrl}')`;
            firstClone.style.backgroundSize = window.innerWidth < 768 ? 'contain' : 'cover';
            firstClone.style.backgroundPosition = 'center';
            firstClone.style.backgroundRepeat = 'no-repeat';
            bgTrack.appendChild(firstClone);

            // Real slides
            eventImages.forEach((item, index) => {
                const slide = document.createElement('div');
                slide.className = "min-w-full h-full";
                slide.style.backgroundImage = `url('${item.image}')`;
                slide.style.backgroundSize = window.innerWidth < 768 ? 'contain' : 'cover';
                slide.style.backgroundPosition = 'center';
                slide.style.backgroundRepeat = 'no-repeat';
                bgTrack.appendChild(slide);
            });

            // Clone first slide to the end
            const lastClone = document.createElement('div');
            lastClone.className = "min-w-full h-full";
            const firstImageUrl = eventImages[0].image;
            lastClone.style.backgroundImage = `url('${firstImageUrl}')`;
            lastClone.style.backgroundSize = window.innerWidth < 768 ? 'contain' : 'cover';
            lastClone.style.backgroundPosition = 'center';
            lastClone.style.backgroundRepeat = 'no-repeat';
            bgTrack.appendChild(lastClone);

            // Set initial position
            bgTrack.style.transition = 'none';
            bgTrack.style.transform = `translateX(-${(currentEvent + 1) * 100}%)`;
            void bgTrack.offsetWidth;
            bgTrack.style.transition = 'transform 0.7s';

            // Dots
            dotsEl.innerHTML = '';
            for (let i = 0; i < eventImages.length; i++) {
                const dot = document.createElement('span');
                dot.className = `w-3 h-3 rounded-full inline-block mx-1 ${i === currentEvent ? 'bg-teal-400' : 'bg-teal-200'} cursor-pointer`;
                dot.onclick = () => { 
                    goToEventSlide(i);
                    resetEventInterval();
                };
                dotsEl.appendChild(dot);
            }
            
            // Ensure gallery management buttons are working after rendering
            setTimeout(() => {
                if (typeof attachGalleryHandlers === 'function') {
                    attachGalleryHandlers();
                } else if (typeof ensureGalleryButtons === 'function') {
                    ensureGalleryButtons();
                }
            }, 100);
        }

        function goToEventSlide(idx) {
            currentEvent = idx;
            bgTrack.style.transition = 'transform 0.7s';
            bgTrack.style.transform = `translateX(-${(currentEvent + 1) * 100}%)`;
            updateEventBgDots();
        }

        function moveEventCarousel(dir) {
            bgTrack.style.transition = 'transform 0.7s';
            if (dir === 1) {
                currentEvent++;
                bgTrack.style.transform = `translateX(-${(currentEvent + 1) * 100}%)`;
                if (currentEvent === eventImages.length) {
                    setTimeout(() => {
                        bgTrack.style.transition = 'none';
                        currentEvent = 0;
                        bgTrack.style.transform = `translateX(-100%)`;
                        updateEventBgDots();
                        void bgTrack.offsetWidth;
                        bgTrack.style.transition = 'transform 0.7s';
                    }, 700);
                } else {
                    updateEventBgDots();
                }
            } else {
                currentEvent--;
                bgTrack.style.transform = `translateX(-${(currentEvent + 1) * 100}%)`;
                if (currentEvent < 0) {
                    setTimeout(() => {
                        bgTrack.style.transition = 'none';
                        currentEvent = eventImages.length - 1;
                        bgTrack.style.transform = `translateX(-${eventImages.length * 100}%)`;
                        updateEventBgDots();
                        void bgTrack.offsetWidth;
                        bgTrack.style.transition = 'transform 0.7s';
                    }, 700);
                } else {
                    updateEventBgDots();
                }
            }
        }

        // Gallery empty state functions
        function showGalleryEmptyState() {
            const emptyState = document.getElementById('gallery-empty-state');
            const carouselControls = document.getElementById('gallery-carousel-controls');
            const carouselBg = document.getElementById('event-bg-carousel');
            
            if (emptyState) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
                emptyState.style.display = 'flex';
            }
            if (carouselControls) carouselControls.style.display = 'none';
            if (carouselBg) carouselBg.style.display = 'none';
        }

        function hideGalleryEmptyState() {
            const emptyState = document.getElementById('gallery-empty-state');
            const carouselControls = document.getElementById('gallery-carousel-controls');
            const carouselBg = document.getElementById('event-bg-carousel');
            
            if (emptyState) {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
                emptyState.style.display = 'none';
            }
            if (carouselControls) carouselControls.style.display = 'flex';
            if (carouselBg) carouselBg.style.display = 'block';
        }

        function resetEventInterval() {
            if (eventInterval) clearInterval(eventInterval);
            eventInterval = setInterval(() => {
                moveEventCarousel(1);
            }, 5000);
        }

        function updateEventBgDots() {
            Array.from(dotsEl.children).forEach((dot, i) => {
                dot.className = `w-3 h-3 rounded-full inline-block mx-1 ${i === currentEvent ? 'bg-teal-400' : 'bg-teal-200'} cursor-pointer`;
            });
        }

        if (prevBtn) prevBtn.onclick = function() {
            moveEventCarousel(-1);
            resetEventInterval();
        };
        
        if (nextBtn) nextBtn.onclick = function() {
            moveEventCarousel(1);
            resetEventInterval();
        };

        // Utility functions for image upload
        function setupImageUpload(type) {
            const prefix = type === 'gallery' ? '' : `${type}-`;
            const dropZoneId = type === 'gallery' ? 'gallery-drop-zone' : `${type}-drop-zone`;
            const dropZone = document.getElementById(dropZoneId);
            const imageInput = document.getElementById(`${prefix}image-upload`);
            const imagePreview = document.getElementById(`${prefix}image-preview`);
            const previewImg = document.getElementById(`${prefix}preview-img`);

            
            if (!dropZone || !imageInput || !imagePreview || !previewImg) {
                console.error('Required elements not found for image upload setup', {
                    dropZone: !!dropZone,
                    imageInput: !!imageInput,
                    imagePreview: !!imagePreview,
                    previewImg: !!previewImg
                });
                return;
            }
            
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    handleImageFile(file, previewImg, imagePreview);
                }
            });
            
            dropZone.addEventListener('click', function(e) {
                // Only trigger file input if not clicking on the label or its children
                if (
                    e.target === dropZone ||
                    (e.target.closest('.space-y-1') && !e.target.closest('label[for="' + imageInput.id + '"]'))
                ) {
                    imageInput.click();
                }
            });
            
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-teal-500', 'bg-teal-50');
            });
            
            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Only remove highlight if leaving the drop zone entirely
                if (!dropZone.contains(e.relatedTarget)) {
                    dropZone.classList.remove('border-teal-500', 'bg-teal-50');
                }
            });
            
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-teal-500', 'bg-teal-50');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file.');
                        return;
                    }
                    
                    // Validate file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Image size must be less than 2MB.');
                        return;
                    }
                    
                    // Set the file to the input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    imageInput.files = dataTransfer.files;
                    
                    // Show preview
                    handleImageFile(file, previewImg, imagePreview);
                }
            });
            
            // Prevent default drag behaviors on the document
            document.addEventListener('dragover', function(e) {
                e.preventDefault();
            });
            
            document.addEventListener('drop', function(e) {
                e.preventDefault();
            });
        }
        
        function handleImageFile(file, previewImg, imagePreview) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.onerror = function() {
                alert('Error reading file. Please try again.');
            };
            reader.readAsDataURL(file);
        }

        @if(auth()->check() && auth()->user()->role === 'admin')
            // Gallery management button handlers - simple and direct
            function attachGalleryHandlers() {
                console.log('Attaching gallery handlers...');
                
                const addBtn = document.getElementById('add-gallery-btn');
                const manageBtn = document.getElementById('manage-gallery-btn');
                const editBtn = document.getElementById('edit-current-gallery-btn');
                const deleteBtn = document.getElementById('delete-current-gallery-btn');
                
                if (addBtn) {
                    console.log('Found add button, attaching listener');
                    addBtn.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Add button clicked!');
                        showGalleryModal();
                    };
                    addBtn.style.pointerEvents = 'auto';
                    addBtn.style.zIndex = '1000';
                }
                
                if (manageBtn) {
                    console.log('Found manage button, attaching listener');
                    manageBtn.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Manage button clicked!');
                        showGalleryManagementModal();
                    };
                    manageBtn.style.pointerEvents = 'auto';
                    manageBtn.style.zIndex = '1000';
                }
                
                if (editBtn) {
                    editBtn.onclick = function(e) {
                        e.preventDefault();
                        if (eventImages[currentEvent] && eventImages[currentEvent].id) {
                            showGalleryModal(eventImages[currentEvent]);
                        }
                    };
                }
                
                if (deleteBtn) {
                    deleteBtn.onclick = function(e) {
                        e.preventDefault();
                        if (eventImages[currentEvent] && eventImages[currentEvent].id) {
                            deleteGalleryImage(eventImages[currentEvent].id);
                        }
                    };
                }
                
                console.log('Gallery handlers attached!');
            }
            
            // Ensure buttons work on page load
            document.addEventListener('DOMContentLoaded', attachGalleryHandlers);
            setTimeout(attachGalleryHandlers, 100);
            
            // Make this function globally available
            window.ensureGalleryButtons = attachGalleryHandlers;
            function showGalleryModal(gallery = null) {
                const isEdit = gallery !== null;
                
                let nextSortOrder = 0;
                if (!isEdit && galleryData && galleryData.length > 0) {
                    // Sort existing items by order and find the next sequential number
                    const sortedData = galleryData.sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));
                    const maxOrder = Math.max(...sortedData.map(item => item.sort_order || 0));
                    nextSortOrder = maxOrder + 1;
                }
                
                const modalHtml = `
                    <div id="gallery-modal" data-initial-hidden class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900">${isEdit ? 'Edit Gallery Image' : 'Add New Gallery Image'}</h3>
                                    <button onclick="closeGalleryModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <form id="gallery-form" class="space-y-6" enctype="multipart/form-data">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Image</label>
                                        <div id="gallery-drop-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="image-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500">
                                                        <span>${isEdit ? 'Change image' : 'Upload an image'}</span>
                                                        <input id="image-upload" name="image" type="file" class="sr-only" accept="image/*" ${!isEdit ? 'required' : ''}>
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                            </div>
                                        </div>
                                        <div id="image-preview" class="mt-4 hidden">
                                            <img id="preview-img" class="h-32 w-full object-cover rounded-lg" />
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                                            <input type="number" name="sort_order" value="${isEdit ? gallery.order : nextSortOrder}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="0 = first position">
                                        </div>
                                        <div class="flex items-center pt-6">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="is_active" ${isEdit ? (gallery.active ? 'checked' : '') : 'checked'} class="rounded border-gray-300 text-teal-600">
                                                <span class="ml-2 text-sm text-gray-700">Active</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-3 pt-4">
                                        <button type="button" onclick="closeGalleryModal()" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
                                        <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700">${isEdit ? 'Update Image' : 'Add Image'}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);

                // Ensure dynamic overlays are visible (layout may hide [data-initial-hidden])
                const inserted = document.getElementById('gallery-modal');
                if (inserted) {
                    inserted.removeAttribute('data-initial-hidden');
                    inserted.classList.remove('hidden');
                    inserted.style.display = 'flex';
                }

                // Wait for modal DOM nodes to be available before setting up upload handlers and form listener.
                const waitForElements = (ids, timeout = 2000) => {
                    const start = Date.now();
                    return new Promise((resolve, reject) => {
                        const check = () => {
                            const found = ids.every(id => !!document.getElementById(id));
                            if (found) return resolve(true);
                            if (Date.now() - start > timeout) return reject(new Error('Timeout waiting for elements: ' + ids.join(', ')));
                            setTimeout(check, 50);
                        };
                        check();
                    });
                };

                waitForElements(['gallery-drop-zone', 'image-upload', 'image-preview', 'preview-img', 'gallery-form'], 3000)
                    .then(() => {
                        try { setupImageUpload('gallery'); } catch (e) { console.error('setupImageUpload error', e); }

                        const galleryForm = document.getElementById('gallery-form');
                        if (!galleryForm) {
                            console.error('gallery-form not found after wait');
                            return;
                        }

                        galleryForm.addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const formData = new FormData(e.target);
                            formData.set('is_active', formData.get('is_active') ? '1' : '0');

                            
                            try {
                                const url = isEdit ? `/gallery/${gallery.id}` : '/gallery';
                                if (isEdit) formData.append('_method', 'PUT');

                                
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: formData
                                });

                                const result = await response.json();
                                
                                if (response.ok) {
                                    alert(result.message);
                                    closeGalleryModal();
                                    // Auto-refresh gallery data and UI
                                    await loadEventImages();
                                    if (typeof renderEventBgCarousel === 'function') {
                                        renderEventBgCarousel();
                                    }
                                    // Ensure gallery management buttons work after refresh
                                    setTimeout(() => {
                                        if (typeof attachGalleryHandlers === 'function') {
                                            attachGalleryHandlers();
                                        } else if (typeof ensureGalleryButtons === 'function') {
                                            ensureGalleryButtons();
                                        }
                                    }, 200);
                                    // If management modal is open, refresh it too
                                    if (document.getElementById('manage-gallery-modal')) {
                                        loadGalleryForManagement();
                                    }
                                } else {
                                    console.error('Error response:', result);
                                    alert(result.message || 'Failed to save image.');
                                }
                            } catch (error) {
                                console.error('Network error:', error);
                                alert('Network error occurred: ' + error.message);
                            }
                        });
                    })
                    .catch((err) => {
                        console.error('Failed to find gallery modal elements:', err);
                        try { setupImageUpload('gallery'); } catch (e) { console.error('setupImageUpload failed after retry:', e); }
                        const galleryForm = document.getElementById('gallery-form');
                        if (galleryForm && !galleryForm._listenerAttached) {
                            // best-effort attach
                            galleryForm.addEventListener('submit', async (e) => {
                                e.preventDefault();
                                const formData = new FormData(e.target);
                                formData.set('is_active', formData.get('is_active') ? '1' : '0');
                                try {
                                    const url = isEdit ? `/gallery/${gallery.id}` : '/gallery';
                                    if (isEdit) formData.append('_method', 'PUT');
                                    const response = await fetch(url, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: formData
                                    });
                                    const result = await response.json();
                                    if (response.ok) {
                                        alert(result.message);
                                        closeGalleryModal();
                                        // Auto-refresh gallery data and UI
                                        await loadEventImages();
                                        if (typeof renderEventBgCarousel === 'function') {
                                            renderEventBgCarousel();
                                        }
                                        // Ensure gallery management buttons work after refresh
                                        setTimeout(() => {
                                            if (typeof attachGalleryHandlers === 'function') {
                                                attachGalleryHandlers();
                                            } else if (typeof ensureGalleryButtons === 'function') {
                                                ensureGalleryButtons();
                                            }
                                        }, 200);
                                        // If management modal is open, refresh it too
                                        if (document.getElementById('manage-gallery-modal')) {
                                            loadGalleryForManagement();
                                        }
                                    } else {
                                        alert(result.message || 'Failed to save image.');
                                    }
                                } catch (error) {
                                    alert('Network error occurred: ' + error.message);
                                }
                            });
                        }
                    });
            }

            function showGalleryManagementModal() {
                const modalHtml = `
                    <div id="manage-gallery-modal" data-initial-hidden class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl relative max-h-[90vh] overflow-y-auto">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900">Manage Gallery Images</h3>
                                    <button onclick="closeManageGalleryModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div id="gallery-list" class="space-y-4">
                                    <div class="text-center py-8">
                                        <div class="rounded-full h-8 w-8 border-4 border-teal-200 border-t-teal-600 mx-auto"></div>
                                        <p class="text-gray-600 mt-2">Loading images...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                // If overlays are inserted after initial page load they may still have
                // the data-initial-hidden attribute (which our layout CSS hides).
                // Remove it immediately so the modal becomes visible.
                const inserted = document.getElementById('manage-gallery-modal');
                if (inserted) {
                    inserted.removeAttribute('data-initial-hidden');
                    inserted.classList.remove('hidden');
                    // ensure it's on top
                    inserted.style.display = 'flex';
                }
                loadGalleryForManagement();
            }

            async function loadGalleryForManagement() {
                        try {
                            const response = await fetch('/gallery', {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });

                            const text = await response.text();
                            let json = null;
                            try { json = JSON.parse(text); } catch (e) { /* not json */ }

                            if (response.ok) {
                                const galleries = json || [];
                                displayGalleryForManagement(galleries);
                            } else {
                                document.getElementById('gallery-list').innerHTML = '<p class="text-red-600 text-center">Failed to load images.</p>';
                                console.error('Failed to load gallery for management', {status: response.status, body: json ?? text});
                            }
                        } catch (error) {
                            document.getElementById('gallery-list').innerHTML = '<p class="text-red-600 text-center">Network error occurred.</p>';
                            console.error('Network error when loading gallery for management', error);
                        }
            }

            function displayGalleryForManagement(galleries) {
                const listContainer = document.getElementById('gallery-list');
                listContainer.innerHTML = '';

                if (!galleries || galleries.length === 0) {
                    const empty = document.createElement('div');
                    empty.className = 'text-center py-8';

                    const p = document.createElement('p');
                    p.className = 'text-gray-600 mb-4';
                    p.textContent = 'No images found.';

                    const btn = document.createElement('button');
                    btn.className = 'px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700';
                    btn.textContent = 'Add Your First Image';
                    btn.addEventListener('click', function() {
                        closeManageGalleryModal();
                        showGalleryModal();
                    });

                    empty.appendChild(p);
                    empty.appendChild(btn);
                    listContainer.appendChild(empty);
                    return;
                }

                galleries.forEach(gallery => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow';

                    const row = document.createElement('div');
                    row.className = 'flex items-start justify-between';

                    const left = document.createElement('div');
                    left.className = 'flex items-start gap-4 flex-1';

                    const img = document.createElement('img');
                    img.src = gallery.image_url || '';
                    img.alt = 'Gallery image';
                    img.className = 'w-16 h-16 object-cover rounded-lg flex-shrink-0';

                    const info = document.createElement('div');
                    info.className = 'flex-1 min-w-0';

                    const meta = document.createElement('div');
                    meta.className = 'flex items-center gap-3 mb-2';

                    const status = document.createElement('span');
                    status.className = `px-2 py-1 text-xs rounded-full ${gallery.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}`;
                    status.textContent = gallery.is_active ? 'Active' : 'Inactive';

                    const order = document.createElement('span');
                    order.className = 'px-2 py-1 text-xs bg-teal-100 text-teal-800 rounded-full';
                    order.textContent = 'Order: ' + (gallery.sort_order ?? '0');

                    meta.appendChild(status);
                    meta.appendChild(order);

                    const desc = document.createElement('p');
                    desc.className = 'text-gray-600 text-sm truncate';
                    desc.textContent = 'Uploaded image';

                    info.appendChild(meta);
                    info.appendChild(desc);

                    left.appendChild(img);
                    left.appendChild(info);

                    const actions = document.createElement('div');
                    actions.className = 'flex items-center gap-2 ml-4';

                    const toggleBtn = document.createElement('button');
                    toggleBtn.className = `px-3 py-1 text-xs rounded ${gallery.is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200'} transition-colors`;
                    toggleBtn.textContent = gallery.is_active ? 'Hide' : 'Show';
                    toggleBtn.addEventListener('click', function() {
                        toggleGalleryActive(gallery.id);
                    });

                    const editBtn = document.createElement('button');
                    editBtn.className = 'px-3 py-1 text-xs bg-teal-100 text-teal-800 rounded hover:bg-teal-200 transition-colors';
                    editBtn.textContent = 'Edit';
                    editBtn.addEventListener('click', function() {
                        closeManageGalleryModal();
                        // pass a plain object to showGalleryModal
                        showGalleryModal({
                            id: gallery.id,
                            image: gallery.image_url,
                            active: !!gallery.is_active,
                            order: gallery.sort_order
                        });
                    });

                    const delBtn = document.createElement('button');
                    delBtn.className = 'px-3 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors';
                    delBtn.textContent = 'Delete';
                    delBtn.addEventListener('click', function() {
                        deleteGalleryFromManagement(gallery.id);
                    });

                    actions.appendChild(toggleBtn);
                    actions.appendChild(editBtn);
                    actions.appendChild(delBtn);

                    row.appendChild(left);
                    row.appendChild(actions);
                    wrapper.appendChild(row);

                    listContainer.appendChild(wrapper);
                });
            }

            async function toggleGalleryActive(galleryId) {
                try {
                    const response = await fetch(`/gallery/${galleryId}/toggle-active`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        loadGalleryForManagement(); // Refresh the list
                    } else {
                        alert(result.message || 'Failed to update gallery status.');
                    }
                } catch (error) {
                    alert('Network error occurred.');
                }
            }

            async function deleteGalleryFromManagement(galleryId) {
                if (!confirm('Are you sure you want to delete this image?')) return;
                
                try {
                    const response = await fetch(`/gallery/${galleryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        // Refresh gallery data and all UI components
                        await loadEventImages();
                        if (typeof renderEventBgCarousel === 'function') {
                            renderEventBgCarousel();
                        }
                        // Refresh the management list with updated order numbers
                        loadGalleryForManagement();
                    } else {
                        alert(result.message || 'Failed to delete image.');
                    }
                } catch (error) {
                    alert('Network error occurred.');
                }
            }

            async function deleteGalleryImage(galleryId) {
                if (!confirm('Are you sure you want to delete this image?')) return;
                
                try {
                    const response = await fetch(`/gallery/${galleryId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        alert('Image deleted successfully!');
                        // Auto-refresh gallery data and UI
                        await loadEventImages();
                        if (typeof renderEventBgCarousel === 'function') {
                            renderEventBgCarousel();
                        }
                        // Ensure gallery management buttons work after refresh
                        setTimeout(() => {
                            if (typeof attachGalleryHandlers === 'function') {
                                attachGalleryHandlers();
                            } else if (typeof ensureGalleryButtons === 'function') {
                                ensureGalleryButtons();
                            }
                        }, 200);
                        // If management modal is open, refresh it too
                        if (document.getElementById('manage-gallery-modal')) {
                            loadGalleryForManagement();
                        }
                    } else {
                        alert('Failed to delete image.');
                    }
                } catch (error) {
                    alert('Network error occurred.');
                }
            }

            function closeGalleryModal() {
                const modal = document.getElementById('gallery-modal');
                if (modal) modal.remove();
            }

            function closeManageGalleryModal() {
                const modal = document.getElementById('manage-gallery-modal');
                if (modal) modal.remove();
            }

            // Admin gallery management functions

            window.closeGalleryModal = function() {
                const modal = document.getElementById('gallery-modal');
                if (modal) modal.remove();
            }

            window.closeManageGalleryModal = function() {
                const modal = document.getElementById('manage-gallery-modal');
                if (modal) modal.remove();
            }
        @endif

        // Initialize gallery carousel with proper timing
        window.initializeGalleryCarousel = function() {
            // Check if required elements exist
            const bgTrack = document.getElementById('event-bg-track');
            const galleryContainer = document.querySelector('.bg-white.rounded-2xl');

            
            if (!bgTrack || !galleryContainer) {
                console.error('Gallery carousel elements not found!');
                return;
            }
            
            // Always render the carousel to handle empty state properly
            if (typeof renderEventBgCarousel === 'function') {
                renderEventBgCarousel();
            } else {
                console.error('renderEventBgCarousel function not defined');
                // If no render function, show empty state by default
                showGalleryEmptyState();
            }
            
            // Only start interval if there are images
            if (eventImages && eventImages.length > 0) {
                if (typeof resetEventInterval === 'function') {
                    resetEventInterval();
                } else {
                    console.error('resetEventInterval function not defined');
                }
            }
        };
        
        // Vendors carousel removed — not used on this site.
        // Initialize gallery carousel immediately and also on DOM ready
        window.initializeGalleryCarousel();

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                window.initializeGalleryCarousel();
            });
        }

            // Vendors carousel removed from this page - no further vendor helper functions.
            
            // Make refresh function globally available
            window.refreshAllCarousels = refreshAllCarousels;
            
            // Add global event listener for manual carousel refresh
            document.addEventListener('refreshCarousels', refreshAllCarousels);
            
            // Add manual trigger that can be called from anywhere
            window.triggerCarouselRefresh = function() {
                refreshAllCarousels();
            };
            
                // Add a safe auth state refresh trigger
                window.refreshAfterLogin = function() {
                    setTimeout(() => {
                        currentAuthState = true; // Assume logged in
                        refreshAllCarousels();
                    }, 200);
                };
            
            
            // Safe initialization backup - only runs once without loops
            let carouselInitialized = false;
            window.addEventListener('load', function() {
                if (!carouselInitialized) {
                    carouselInitialized = true;
                    
                    setTimeout(() => {
                        if (typeof window.initializeGalleryCarousel === 'function') {
                            window.initializeGalleryCarousel();
                        }
                    }, 100);
                }
            });        // Safety net disabled - all automatic refresh disabled


    </script>

    <style>
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
        
        /* Mobile optimizations for welcome page */
        @media (max-width: 768px) {
            /* Hero banner mobile fixes */
            .hero-banner, .hero-section {
                height: 280px !important;
                min-height: 280px !important;
                margin-bottom: 2rem !important;
            }
            
            .hero-banner img {
                height: 280px !important;
                width: 100% !important;
                object-fit: cover !important;
                object-position: center !important;
            }
            
            /* Hero content mobile adjustments */
            .h-80 {
                height: 280px !important;
            }
            
            /* Remove excessive margins/padding on mobile */
            .mb-12 {
                margin-bottom: 2rem !important;
            }
            
            .mb-16 {
                margin-bottom: 2rem !important;
            }
            
            /* Section spacing adjustments */
            section {
                margin-bottom: 2rem !important;
            }
            
            /* Container padding fixes */
            .max-w-6xl.mx-auto {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: 100% !important;
            }
            
            /* About section image mobile fixes */
            .relative.h-64 {
                height: 200px !important;
            }
            
            .relative.h-64 img {
                height: 200px !important;
                width: 100% !important;
                object-fit: cover !important;
                object-position: center !important;
            }
            
            /* Gallery carousel mobile fixes */
            #event-bg-carousel {
                min-height: 280px !important;
            }
            
            #event-bg-carousel img {
                height: 280px !important;
                width: 100% !important;
                object-fit: cover !important;
                object-position: center !important;
            }
            
            /* Grid layouts on mobile */
            .grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 {
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
                gap: 1.5rem !important;
            }
            
            .grid-cols-1.lg\\:grid-cols-2 {
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
                gap: 1.5rem !important;
            }
            
            /* Contact cards mobile spacing */
            .contact-card {
                margin-bottom: 1.5rem !important;
            }
            
            /* Modal content mobile fixes */
            .modal-content {
                max-width: 95vw !important;
                max-height: 90vh !important;
                margin: 2rem auto !important;
            }
            
            /* Image upload preview mobile fixes */
            #preview-img {
                height: 150px !important;
                width: 100% !important;
                object-fit: cover !important;
            }
            
            /* Text sizing for mobile */
            .hero-text {
                font-size: 2rem !important;
                line-height: 1.2 !important;
            }
            
            .hero-subtitle {
                font-size: 1.125rem !important;
                line-height: 1.4 !important;
            }
            
            /* Padding adjustments for mobile */
            .px-4.sm\\:px-6.lg\\:px-8 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .py-16 {
                padding-top: 3rem !important;
                padding-bottom: 3rem !important;
            }
            
            .py-20 {
                padding-top: 3rem !important;
                padding-bottom: 3rem !important;
            }
        }
    </style>
@endsection