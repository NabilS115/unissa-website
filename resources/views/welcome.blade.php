@extends('layouts.app')

@section('title', 'UNISSA - Welcome')

@section('content')
    <!-- Hero Banner Section -->
    <section class="w-full h-80 flex flex-col items-center justify-center mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80" alt="Food Banner" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow-lg mb-4">Business with Barakah</h1>
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
                    <button id="add-gallery-btn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-2xl text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Image
                    </button>
                    <button id="manage-gallery-btn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <style>
                        #add-gallery-btn { background-color: #059669 !important; }
                        #manage-gallery-btn { background-color: #0d9488 !important; }
                    </style>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Manage Images
                    </button>
                </div>
            </div>
        @endif
        
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative group" style="min-height: 420px;">
            <div id="event-bg-carousel" class="absolute inset-0 w-full h-full overflow-hidden z-0">
                <div id="event-bg-track" class="flex w-full h-full transition-transform duration-700">
                    <!-- Slides will be rendered by JS -->
                </div>
            </div>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
                <!-- Admin Controls for Current Image -->
                <div class="absolute top-4 right-4 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <div class="flex gap-2">
                        <button id="edit-current-gallery-btn" class="w-10 h-10 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors" title="Edit Image">
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
            
            <!-- Empty State for Gallery -->
            <div id="gallery-empty-state" class="hidden w-full h-full items-center justify-center p-8" style="min-height: 420px;">
                <div class="text-center max-w-md mx-auto">
                    <div class="mb-6">
                        <svg class="w-20 h-20 mx-auto text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Gallery Coming Soon</h3>
                    <p class="text-gray-500 mb-6">This is where beautiful images will be showcased to give visitors a glimpse of our offerings.</p>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <button onclick="document.getElementById('add-gallery-btn').click()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="background-color:#0d9488;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Your First Image
                        </button>
                    @else
                        <div class="inline-flex items-center px-4 py-2 bg-teal-50 text-teal-600 rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Images will appear here soon
                        </div>
                    @endif
                </div>
            </div>

            <div class="w-full flex items-center justify-center relative bg-transparent py-8 z-10" style="min-height: 420px;">
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
                        <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-teal-700">About Tijarah Co</h2>
                    </div>
                    
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        Welcome to Tijarah Co Sdn Bhd, established under UNISSA, is dedicated to fostering entrepreneurship, innovation, and halal trade. We provide a platform for students, alumni, and the community to develop businesses, showcase products, and grow sustainably in line with Islamic values.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Premium Quality</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Fresh Ingredients</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Expert Curation</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Customer Satisfaction</span>
                        </div>
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
                
                <!-- Image -->
                <div class="relative h-64 lg:h-full">
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=800&q=80" 
                         alt="About UNISSA - Quality Food Experience" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-l from-transparent to-teal-600/20"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Summary Section -->
    <section class="w-full bg-gradient-to-br from-teal-50 to-emerald-50 py-16 mb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-teal-800 mb-4">Get In Touch</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Ready to connect? We're here to help with any questions about our products or services.</p>
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
                    <p class="text-gray-600 text-sm">Universiti Islam Sultan Sharif Ali<br>Simpang 347, Jalan Pasar Gadong<br>Bandar Seri Begawan, Brunei</p>
                </div>

                <!-- Phone -->
                <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 hover:border-teal-200 p-6 text-center hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Phone</h3>
                    <p class="text-gray-600 text-sm">+XXX XXX XXXX</p>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 hover:border-teal-200 p-6 text-center hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600 text-sm">info@somethingcompany.com</p>
                </div>

                <!-- Hours -->
                <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 hover:border-teal-200 p-6 text-center hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Hours</h3>
                    <p class="text-gray-600 text-sm">Mon-Thu & Sat<br>9:00am - 4:30pm</p>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center">
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">Have questions about our products or want to place an order?</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/contact" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="background-color:#0d9488;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.436L3 21l2.436-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                        </svg>
                        Send Us a Message
                    </a>
                    <a href="tel:+XXXXXXX" class="inline-flex items-center px-8 py-3 bg-white hover:bg-gradient-to-r hover:from-teal-50 hover:via-emerald-50 hover:to-cyan-50 text-teal-600 font-semibold rounded-2xl border-2 border-teal-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="background-color:#fff;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Call Now
                    </a>
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
            console.log('Refreshing carousels...');
            
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
                
                console.log('Carousels refreshed successfully');
                
                // Force recalculation of container dimensions  
                const galleryContainer = document.querySelector('.bg-white.rounded-2xl');
                if (galleryContainer) {
                    galleryContainer.style.width = '';
                    galleryContainer.style.height = '';
                    void galleryContainer.offsetHeight; // Trigger reflow
                }
                
            } catch (error) {
                console.log('Error in carousel refresh:', error);
            }
        }

        // Simplified authentication handling - no automatic monitoring
        function checkAuthStateChange() {
            // Disabled to prevent infinite loops
            console.log('Auth state check disabled to prevent loops');
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
                        console.log('Authentication state changed, refreshing carousels...');
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
                    console.log('Login/logout form submitted, checking auth state...');
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
        
        // Debug backend data loading
        console.log('=== BACKEND DATA DEBUG ===');
        console.log('Raw galleryData:', galleryData);
        console.log('Gallery count:', galleryData ? galleryData.length : 0);
        

        
        console.log('Gallery data loaded:', galleryData);
        
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
            console.log('Running renderEventBgCarousel...');
            const galleryContainer = document.querySelector('.bg-white.rounded-2xl');
            const bgTrack = document.getElementById('event-bg-track');
            
            console.log('Gallery render check:', {
                galleryContainer: !!galleryContainer,
                bgTrack: !!bgTrack,
                eventImages: eventImages ? eventImages.length : 'no eventImages'
            });
            
            if (!galleryContainer || !bgTrack) {
                console.error('Gallery elements missing for rendering');
                return;
            }
            
            if (!eventImages || eventImages.length === 0) {
                console.log('No gallery images, showing empty state');
                // Show empty state instead of hiding
                galleryContainer.style.display = 'block';
                showGalleryEmptyState();
                return;
            }
            
            console.log('Rendering gallery with', eventImages.length, 'images');

            galleryContainer.style.display = 'block';
            hideGalleryEmptyState();
            
            bgTrack.innerHTML = '';
            // Clone last slide to the beginning
            const firstClone = document.createElement('div');
            firstClone.className = "min-w-full h-full";
            const lastImageUrl = eventImages[eventImages.length - 1].image;
            firstClone.style.backgroundImage = `url('${lastImageUrl}')`;
            firstClone.style.backgroundSize = 'cover';
            firstClone.style.backgroundPosition = 'center';
            firstClone.style.backgroundRepeat = 'no-repeat';
            bgTrack.appendChild(firstClone);

            // Real slides
            eventImages.forEach((item, index) => {
                const slide = document.createElement('div');
                slide.className = "min-w-full h-full";
                slide.style.backgroundImage = `url('${item.image}')`;
                slide.style.backgroundSize = 'cover';
                slide.style.backgroundPosition = 'center';
                slide.style.backgroundRepeat = 'no-repeat';
                bgTrack.appendChild(slide);
            });

            // Clone first slide to the end
            const lastClone = document.createElement('div');
            lastClone.className = "min-w-full h-full";
            const firstImageUrl = eventImages[0].image;
            lastClone.style.backgroundImage = `url('${firstImageUrl}')`;
            lastClone.style.backgroundSize = 'cover';
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
            const carouselContent = document.querySelector('#event-bg-carousel').parentNode.querySelector('.w-full.flex.items-center');
            const carouselBg = document.getElementById('event-bg-carousel');
            
            if (emptyState) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            }
            if (carouselContent) carouselContent.style.display = 'none';
            if (carouselBg) carouselBg.style.display = 'none';
        }

        function hideGalleryEmptyState() {
            const emptyState = document.getElementById('gallery-empty-state');
            const carouselContent = document.querySelector('#event-bg-carousel').parentNode.querySelector('.w-full.flex.items-center');
            const carouselBg = document.getElementById('event-bg-carousel');
            
            if (emptyState) {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }
            if (carouselContent) carouselContent.style.display = 'flex';
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
            
            console.log('Setting up image upload for type:', type);
            console.log('Looking for elements:', {
                dropZone: dropZoneId,
                imageInput: `${prefix}image-upload`,
                imagePreview: `${prefix}image-preview`,
                previewImg: `${prefix}preview-img`
            });
            
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
            // Admin gallery management functions
            document.getElementById('add-gallery-btn')?.addEventListener('click', () => {
                showGalleryModal();
            });

            document.getElementById('manage-gallery-btn')?.addEventListener('click', () => {
                showGalleryManagementModal();
            });

            document.getElementById('edit-current-gallery-btn')?.addEventListener('click', () => {
                if (eventImages[currentEvent] && eventImages[currentEvent].id) {
                    showGalleryModal(eventImages[currentEvent]);
                }
            });

            document.getElementById('delete-current-gallery-btn')?.addEventListener('click', () => {
                if (eventImages[currentEvent] && eventImages[currentEvent].id) {
                    deleteGalleryImage(eventImages[currentEvent].id);
                }
            });

            function showGalleryModal(gallery = null) {
                const isEdit = gallery !== null;
                
                let nextSortOrder = 0;
                if (!isEdit && galleryData && galleryData.length > 0) {
                    const maxOrder = Math.max(...galleryData.map(item => item.sort_order || 0));
                    nextSortOrder = maxOrder + 1;
                }
                
                const modalHtml = `
                    <div id="gallery-modal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 p-4">
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
                
                setTimeout(() => {
                    setupImageUpload('gallery');
                }, 100);
                
                document.getElementById('gallery-form').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    formData.set('is_active', formData.get('is_active') ? '1' : '0');
                    
                    console.log('Submitting gallery form...');
                    console.log('Form data entries:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }
                    
                    try {
                        const url = isEdit ? `/gallery/${gallery.id}` : '/gallery';
                        if (isEdit) formData.append('_method', 'PUT');
                        
                        console.log('Sending request to:', url);
                        
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);
                        
                        const result = await response.json();
                        console.log('Response data:', result);
                        
                        if (response.ok) {
                            alert(result.message);
                            closeGalleryModal();
                            window.location.reload();
                        } else {
                            console.error('Error response:', result);
                            alert(result.message || 'Failed to save image.');
                        }
                    } catch (error) {
                        console.error('Network error:', error);
                        alert('Network error occurred: ' + error.message);
                    }
                });
            }

            function showGalleryManagementModal() {
                const modalHtml = `
                    <div id="manage-gallery-modal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 p-4">
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
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mx-auto"></div>
                                        <p class="text-gray-600 mt-2">Loading images...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
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

                    if (response.ok) {
                        const galleries = await response.json();
                        displayGalleryForManagement(galleries);
                    } else {
                        document.getElementById('gallery-list').innerHTML = '<p class="text-red-600 text-center">Failed to load images.</p>';
                    }
                } catch (error) {
                    document.getElementById('gallery-list').innerHTML = '<p class="text-red-600 text-center">Network error occurred.</p>';
                }
            }

            function displayGalleryForManagement(galleries) {
                const listContainer = document.getElementById('gallery-list');
                
                if (galleries.length === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-gray-600 mb-4">No images found.</p>
                            <button onclick="closeManageGalleryModal(); showGalleryModal();" 
                                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                                Add Your First Image
                            </button>
                        </div>
                    `;
                    return;
                }

                const galleriesHtml = galleries.map(gallery => `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4 flex-1">
                                <img src="${gallery.image_url}" alt="Gallery image" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="px-2 py-1 text-xs rounded-full ${gallery.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                            ${gallery.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            Order: ${gallery.sort_order}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm truncate">Uploaded image</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <button onclick="toggleGalleryActive(${gallery.id})" 
                                        class="px-3 py-1 text-xs rounded ${gallery.is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200'} transition-colors">
                                    ${gallery.is_active ? 'Hide' : 'Show'}
                                </button>
                                <button onclick="closeManageGalleryModal(); showGalleryModal({id: ${gallery.id}, image: '${gallery.image_url}', active: ${gallery.is_active}, order: ${gallery.sort_order}})" 
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors">
                                    Edit
                                </button>
                                <button onclick="deleteGalleryFromManagement(${gallery.id})" 
                                        class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
                
                listContainer.innerHTML = galleriesHtml;
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
                        loadGalleryForManagement(); // Refresh the list
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
                        window.location.reload();
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

            // Ensure vendorsData is defined to avoid ReferenceError
            window.vendorsData = window.vendorsData || [];
            function showVendorModal(vendor = null) {
                const isEdit = vendor !== null;
                
                // Calculate next sort order for new vendors
                let nextSortOrder = 0;
                if (!isEdit && vendorsData && vendorsData.length > 0) {
                    const maxOrder = Math.max(...vendorsData.map(item => item.sort_order || 0));
                    nextSortOrder = maxOrder + 1;
                }
                
                const modalHtml = `
                    <div id="vendor-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900">${isEdit ? 'Edit Vendor' : 'Add New Vendor'}</h3>
                                    <button onclick="closeVendorModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <form id="vendor-form" class="space-y-6" enctype="multipart/form-data">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Vendor Name</label>
                                            <input type="text" name="name" value="${isEdit ? vendor.name : ''}" required
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                                            <input type="text" name="type" value="${isEdit ? vendor.type : ''}" required
                                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <textarea name="description" rows="3" required
                                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">${isEdit ? vendor.description : ''}</textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor Image</label>
                                        <div id="vendor-drop-zone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="vendor-image-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500">
                                                        <span>${isEdit ? 'Change image' : 'Upload an image'}</span>
                                                        <input id="vendor-image-upload" name="image" type="file" class="sr-only" accept="image/*">
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                            </div>
                                        </div>
                                        <div id="vendor-image-preview" class="mt-4 hidden">
                                            <img id="vendor-preview-img" class="h-32 w-full object-cover rounded-lg" />
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                                            <input type="number" name="sort_order" value="${isEdit ? vendor.sort_order : nextSortOrder}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="0 = first position">
                                        </div>
                                        
                                        <div class="flex items-center pt-6">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="is_active" ${isEdit ? (vendor.is_active ? 'checked' : '') : 'checked'} class="rounded border-gray-300 text-teal-600">
                                                <span class="ml-2 text-sm text-gray-700">Active</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-3 pt-4">
                                        <button type="button" onclick="closeVendorModal()" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Cancel</button>
                                        <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700">${isEdit ? 'Update Vendor' : 'Add Vendor'}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                setTimeout(() => {
                    setupImageUpload('vendor');
                }, 100);
                
                document.getElementById('vendor-form').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    formData.set('is_active', formData.get('is_active') ? '1' : '0');
                    
                    try {
                        const url = isEdit ? `/vendors/${vendor.id}` : '/vendors';
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
                            closeVendorModal();
                            window.location.reload();
                        } else {
                            alert(result.message || 'Failed to save vendor.');
                        }
                    } catch (error) {
                        alert('Network error occurred.');
                    }
                });
            }

            function showVendorManagementModal() {
                const modalHtml = `
                    <div id="manage-vendor-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl relative max-h-[90vh] overflow-y-auto">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900">Manage Vendors</h3>
                                    <button onclick="closeManageVendorModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <div id="vendor-list" class="space-y-4">
                                    <div class="text-center py-8">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600 mx-auto"></div>
                                        <p class="text-gray-600 mt-2">Loading vendors...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                loadVendorsForManagement();
            }

            async function loadVendorsForManagement() {
                try {
                    const response = await fetch('/vendors', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.ok) {
                        const vendors = await response.json();
                        displayVendorsForManagement(vendors);
                    } else {
                        document.getElementById('vendor-list').innerHTML = '<p class="text-red-600 text-center">Failed to load vendors.</p>';
                    }
                } catch (error) {
                    document.getElementById('vendor-list').innerHTML = '<p class="text-red-600 text-center">Network error occurred.</p>';
                }
            }

            function displayVendorsForManagement(vendors) {
                const listContainer = document.getElementById('vendor-list');
                
                if (vendors.length === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-gray-600 mb-4">No vendors found.</p>
                            <button onclick="closeManageVendorModal(); showVendorModal();" 
                                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                                Add Your First Vendor
                            </button>
                        </div>
                    `;
                    return;
                }

                const vendorsHtml = vendors.map(vendor => `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4 flex-1">
                                <img src="${vendor.image_url}" alt="${vendor.name}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-semibold text-gray-900">${vendor.name}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full ${vendor.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                            ${vendor.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            Order: ${vendor.sort_order}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm">${vendor.type}</p>
                                    <p class="text-gray-500 text-sm mt-1">${vendor.description}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <button onclick="toggleVendorActive(${vendor.id})" 
                                        class="px-3 py-1 text-xs rounded ${vendor.is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200'} transition-colors">
                                    ${vendor.is_active ? 'Hide' : 'Show'}
                                </button>
                                <button onclick="closeManageVendorModal(); showVendorModal({id: ${vendor.id}, name: '${vendor.name}', type: '${vendor.type}', description: '${vendor.description}', image_url: '${vendor.image_url}', is_active: ${vendor.is_active}, sort_order: ${vendor.sort_order}})" 
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200 transition-colors">
                                    Edit
                                </button>
                                <button onclick="deleteVendorFromManagement(${vendor.id})" 
                                        class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200 transition-colors">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
                
                listContainer.innerHTML = vendorsHtml;
            }

            async function toggleVendorActive(vendorId) {
                try {
                    const response = await fetch(`/vendors/${vendorId}/toggle-active`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        loadVendorsForManagement(); // Refresh the list
                    } else {
                        alert(result.message || 'Failed to update vendor status.');
                    }
                } catch (error) {
                    alert('Network error occurred.');
                }
            }

            async function deleteVendorFromManagement(vendorId) {
                if (!confirm('Are you sure you want to delete this vendor?')) return;
                
                try {
                    const response = await fetch(`/vendors/${vendorId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        loadVendorsForManagement(); // Refresh the list
                    } else {
                        alert(result.message || 'Failed to delete vendor.');
                    }
                } catch (error) {
                    alert('Network error occurred.');
                }
            }

            window.closeGalleryModal = function() {
                const modal = document.getElementById('gallery-modal');
                if (modal) modal.remove();
            }

            window.closeManageGalleryModal = function() {
                const modal = document.getElementById('manage-gallery-modal');
                if (modal) modal.remove();
            }

            window.closeVendorModal = function() {
                const modal = document.getElementById('vendor-modal');
                if (modal) modal.remove();
            }

            window.closeManageVendorModal = function() {
                const modal = document.getElementById('manage-vendor-modal');
                if (modal) modal.remove();
            }
        @endif

        // Initialize gallery carousel with proper timing
        window.initializeGalleryCarousel = function() {
            console.log('Initializing gallery carousel...');
            
            // Check if required elements exist
            const bgTrack = document.getElementById('event-bg-track');
            const galleryContainer = document.querySelector('.bg-white.rounded-2xl');
            
            console.log('Gallery elements check:', {
                bgTrack: !!bgTrack,
                galleryContainer: !!galleryContainer,
                eventImages: eventImages ? eventImages.length : 'undefined',
                galleryData: galleryData ? galleryData.length : 'undefined'
            });
            
            if (!bgTrack || !galleryContainer) {
                console.error('Gallery carousel elements not found!');
                return;
            }
            
            if (typeof renderEventBgCarousel === 'function') {
                renderEventBgCarousel();
            } else {
                console.error('renderEventBgCarousel function not defined');
            }
            
            if (typeof resetEventInterval === 'function') {
                resetEventInterval();
            } else {
                console.error('resetEventInterval function not defined');
            }
        };
        
        // Vendors carousel functionality - Global scope variables
        let currentVendor = 0;
        let vendorInterval = null;
        const vendorsPerSlide = 1;
        
        // Initialize vendors carousel - Global function
        window.initializeVendorsCarousel = function() {
            console.log('Initializing vendors carousel...');
            
            // Check if required elements exist
            const vendorsTrackElement = document.getElementById('vendors-track');
            const vendorsContainer = document.querySelector('#vendors-track')?.parentNode;
            
            console.log('Vendors elements check:', {
                vendorsTrack: !!vendorsTrackElement,
                vendorsContainer: !!vendorsContainer,
                vendorsData: vendorsData ? vendorsData.length : 'undefined'
            });
            
            if (!vendorsTrackElement) {
                console.error('Vendors carousel elements not found!');
                return;
            }
            
            if (typeof renderVendorsCarousel === 'function') {
                renderVendorsCarousel();
            } else {
                console.error('renderVendorsCarousel function not defined');
            }
            
            if (typeof resetVendorInterval === 'function') {
                resetVendorInterval();
            } else {
                console.error('resetVendorInterval function not defined');
            }
            
            // Set up navigation event handlers
            const vendorsPrev = document.getElementById('vendors-prev');
            const vendorsNext = document.getElementById('vendors-next');
            
            if (vendorsPrev && vendorsNext) {
                // Remove any existing event listeners to avoid duplicates
                vendorsPrev.replaceWith(vendorsPrev.cloneNode(true));
                vendorsNext.replaceWith(vendorsNext.cloneNode(true));
                
                // Get the new elements after cloning
                const newVendorsPrev = document.getElementById('vendors-prev');
                const newVendorsNext = document.getElementById('vendors-next');
                
                newVendorsPrev.addEventListener('click', function() {
                    moveVendorCarousel(-1);
                    resetVendorInterval();
                });
                
                newVendorsNext.addEventListener('click', function() {
                    moveVendorCarousel(1);
                    resetVendorInterval();
                });
                
                console.log('Vendors navigation handlers attached successfully');
            } else {
                console.error('Vendors navigation buttons not found');
            }
        };
        
        // Initialize immediately and also on DOM ready
        window.initializeGalleryCarousel();
        window.initializeVendorsCarousel();
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                window.initializeGalleryCarousel();
                window.initializeVendorsCarousel();
            });
        }

        // Global vendors carousel functions
        function renderVendorsCarousel() {
            console.log('=== RENDERING VENDORS CAROUSEL ===');
            const vendors = vendorsData; // Use the global vendorsData
            console.log('Vendors data for rendering:', vendors);
            const vendorsTrack = document.getElementById('vendors-track');
            const vendorsDots = document.getElementById('vendors-dots');
            
            if (!vendorsTrack || !vendors || vendors.length === 0) {
                console.log('Cannot render vendors carousel - missing elements or data');
                return;
            }
            
            vendorsTrack.innerHTML = '';
            // Calculate number of slides
            const totalSlides = Math.ceil(vendors.length / vendorsPerSlide);
            
            // Clone last slide to the beginning
            const lastVendors = vendors.slice(-vendorsPerSlide);
            vendorsTrack.appendChild(vendorSlideHTML(lastVendors));
            
            // Real slides
            for (let i = 0; i < totalSlides; i++) {
                const slideVendors = vendors.slice(i * vendorsPerSlide, (i + 1) * vendorsPerSlide);
                vendorsTrack.appendChild(vendorSlideHTML(slideVendors));
            }
            
            // Clone first slide to the end
            const firstVendors = vendors.slice(0, vendorsPerSlide);
            vendorsTrack.appendChild(vendorSlideHTML(firstVendors));
            
            // Set initial position
            vendorsTrack.style.transition = 'none';
            vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
            void vendorsTrack.offsetWidth;
            vendorsTrack.style.transition = 'transform 0.7s';
            
            // Dots
            if (vendorsDots) {
                vendorsDots.innerHTML = '';
                for (let i = 0; i < totalSlides; i++) {
                    const dot = document.createElement('div');
                    dot.classList.add('w-3', 'h-3', 'rounded-full', 'cursor-pointer', 'transition-colors');
                    if (i === currentVendor) {
                        dot.classList.add('bg-teal-600');
                    } else {
                        dot.classList.add('bg-gray-300');
                    }
                    dot.addEventListener('click', () => goToVendor(i));
                    vendorsDots.appendChild(dot);
                }
            }
        }
        
        // Global vendor helper functions
        function vendorSlideHTML(vendorArr) {
            const slide = document.createElement('div');
            slide.className = "min-w-full flex justify-center items-center";
            slide.innerHTML = vendorArr.map(v => `
                <div class="bg-teal-50 rounded-xl shadow-lg border p-8 w-80 h-96 flex flex-col items-center justify-between">
                    <div class="flex flex-col items-center gap-4 flex-grow">
                        <img src="${v.image_url}" alt="${v.name}" class="w-24 h-24 rounded-full object-cover">
                        <div class="text-center flex-grow flex flex-col justify-center gap-2">
                            <span class="font-bold text-teal-700 text-lg line-clamp-2">${v.name}</span>
                            <span class="text-gray-600 text-base font-medium">${v.type}</span>
                        </div>
                    </div>
                    <p class="text-gray-700 text-center text-sm leading-relaxed line-clamp-3 mt-4">${v.description}</p>
                </div>
            `).join('');
            return slide;
        }
        
        function goToVendor(idx) {
            currentVendor = idx;
            const vendorsTrack = document.getElementById('vendors-track');
            if (vendorsTrack) {
                vendorsTrack.style.transition = 'transform 0.7s';
                vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                updateVendorDots();
            }
        }
        
        function updateVendorDots() {
            const vendorsDots = document.getElementById('vendors-dots');
            if (!vendorsDots) return;
            
            const dots = vendorsDots.querySelectorAll('div');
            dots.forEach((dot, index) => {
                dot.classList.remove('bg-teal-600', 'bg-gray-300');
                if (index === currentVendor) {
                    dot.classList.add('bg-teal-600');
                } else {
                    dot.classList.add('bg-gray-300');
                }
            });
        }
        
        function resetVendorInterval() {
            if (vendorInterval) {
                clearInterval(vendorInterval);
                vendorInterval = null;
            }
        }
        
        function moveVendorCarousel(direction) {
            const vendors = vendorsData;
            if (!vendors || vendors.length === 0) return;
            
            const vendorsTrack = document.getElementById('vendors-track');
            if (!vendorsTrack) return;
            
            const totalSlides = Math.ceil(vendors.length / vendorsPerSlide);
            
            if (direction === 1) {
                currentVendor++;
                if (currentVendor >= totalSlides) {
                    currentVendor = 0;
                    vendorsTrack.style.transition = 'none';
                    vendorsTrack.style.transform = `translateX(-${totalSlides * 100}%)`;
                    setTimeout(() => {
                        vendorsTrack.style.transition = 'transform 0.7s';
                        vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                        updateVendorDots();
                    }, 10);
                } else {
                    vendorsTrack.style.transition = 'transform 0.7s';
                    vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                    updateVendorDots();
                }
            } else {
                currentVendor--;
                if (currentVendor < 0) {
                    currentVendor = totalSlides - 1;
                    vendorsTrack.style.transition = 'none';
                    vendorsTrack.style.transform = `translateX(0%)`;
                    setTimeout(() => {
                        vendorsTrack.style.transition = 'transform 0.7s';
                        vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                        updateVendorDots();
                    }, 10);
                } else {
                    vendorsTrack.style.transition = 'transform 0.7s';
                    vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                    updateVendorDots();
                }
            }
        }
        
        // Vendors carousel initialization function moved to earlier in the file
        
        // Add vendors navigation event handlers
        document.addEventListener('DOMContentLoaded', function() {
            const vendorsPrev = document.getElementById('vendors-prev');
            const vendorsNext = document.getElementById('vendors-next');
            
            if (vendorsPrev && vendorsNext) {
                vendorsPrev.addEventListener('click', function() {
                    moveVendorCarousel(-1);
                    resetVendorInterval();
                });
                
                vendorsNext.addEventListener('click', function() {
                    moveVendorCarousel(1);
                    resetVendorInterval();
                });
            }
            
            function renderVendorsCarousel() {
                vendorsTrack.innerHTML = '';
                // Calculate number of slides
                const totalSlides = Math.ceil(vendors.length / vendorsPerSlide);
                // Clone last slide to the beginning
                const lastVendors = vendors.slice(-vendorsPerSlide);
                vendorsTrack.appendChild(vendorSlideHTML(lastVendors));
                // Real slides
                for (let i = 0; i < totalSlides; i++) {
                    const slideVendors = vendors.slice(i * vendorsPerSlide, (i + 1) * vendorsPerSlide);
                    vendorsTrack.appendChild(vendorSlideHTML(slideVendors));
                }
                // Clone first slide to the end
                const firstVendors = vendors.slice(0, vendorsPerSlide);
                vendorsTrack.appendChild(vendorSlideHTML(firstVendors));
                // Set initial position
                vendorsTrack.style.transition = 'none';
                vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                void vendorsTrack.offsetWidth;
                vendorsTrack.style.transition = 'transform 0.7s';
                // Dots
                vendorsDots.innerHTML = '';
                for (let i = 0; i < totalSlides; i++) {
                    const dot = document.createElement('span');
                    dot.className = `w-3 h-3 rounded-full inline-block mx-1 ${i === currentVendor ? 'bg-teal-400' : 'bg-teal-200'} cursor-pointer`;
                    dot.onclick = () => { goToVendorSlide(i); resetVendorInterval(); };
                    vendorsDots.appendChild(dot);
                }
            }
            
            function vendorSlideHTML(vendorArr) {
                const slide = document.createElement('div');
                slide.className = "min-w-full flex justify-center gap-8";
                slide.innerHTML = vendorArr.map(v => `
                    <div class="bg-teal-50 rounded-xl shadow-lg border p-6 min-w-[260px] max-w-xs flex flex-col items-center gap-2">
                        <img src="${v.image_url}" alt="${v.name}" class="w-16 h-16 rounded-full object-cover mb-2">
                        <span class="font-semibold text-teal-700">${v.name}</span>
                        <span class="text-gray-500 text-sm">${v.type}</span>
                        <p class="text-gray-600 text-center text-sm mt-2">${v.description}</p>
                    </div>
                `).join('');
                return slide;
            }
            
            function goToVendorSlide(idx) {
                currentVendor = idx;
                vendorsTrack.style.transition = 'transform 0.7s';
                vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                updateVendorDots();
            }
            
            function moveVendorCarousel(dir) {
                const totalSlides = Math.ceil(vendors.length / vendorsPerSlide);
                vendorsTrack.style.transition = 'transform 0.7s';
                if (dir === 1) {
                    currentVendor++;
                    vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                    if (currentVendor === totalSlides) {
                        setTimeout(() => {
                            vendorsTrack.style.transition = 'none';
                            currentVendor = 0;
                            vendorsTrack.style.transform = `translateX(-100%)`;
                            updateVendorDots();
                            void vendorsTrack.offsetWidth;
                            vendorsTrack.style.transition = 'transform 0.7s';
                        }, 700);
                    } else {
                        updateVendorDots();
                    }
                } else {
                    currentVendor--;
                    vendorsTrack.style.transform = `translateX(-${(currentVendor + 1) * 100}%)`;
                    if (currentVendor < 0) {
                        setTimeout(() => {
                            vendorsTrack.style.transition = 'none';
                            currentVendor = totalSlides - 1;
                            vendorsTrack.style.transform = `translateX(-${totalSlides * 100}%)`;
                            updateVendorDots();
                            void vendorsTrack.offsetWidth;
                            vendorsTrack.style.transition = 'transform 0.7s';
                        }, 700);
                    } else {
                        updateVendorDots();
                    }
                }
            }
            
            function resetVendorInterval() {
                if (vendorInterval) clearInterval(vendorInterval);
                vendorInterval = setInterval(() => {
                    moveVendorCarousel(1);
                }, 5000);
            }
            
            function updateVendorDots() {
                const totalSlides = Math.ceil(vendors.length / vendorsPerSlide);
                Array.from(vendorsDots.children).forEach((dot, i) => {
                    dot.className = `w-3 h-3 rounded-full inline-block mx-1 ${i === currentVendor ? 'bg-teal-400' : 'bg-teal-200'} cursor-pointer`;
                });
            }
            
            // Event handlers moved to global initialization functions
            // Vendors carousel initialization function moved to global scope above
            
            // Vendors carousel initialized globally above
            
            // Make refresh function globally available
            window.refreshAllCarousels = refreshAllCarousels;
            
            // Add global event listener for manual carousel refresh
            document.addEventListener('refreshCarousels', refreshAllCarousels);
            
            // Add manual trigger that can be called from anywhere
            window.triggerCarouselRefresh = function() {
                console.log('Manual carousel refresh triggered');
                refreshAllCarousels();
            };
            
                // Add a safe auth state refresh trigger
                window.refreshAfterLogin = function() {
                    console.log('Post-login carousel refresh');
                    setTimeout(() => {
                        currentAuthState = true; // Assume logged in
                        refreshAllCarousels();
                    }, 200);
                };
            });
            
            // Safe initialization backup - only runs once without loops
            let carouselInitialized = false;
            window.addEventListener('load', function() {
                if (!carouselInitialized) {
                    carouselInitialized = true;
                    console.log('Ensuring carousels are properly initialized...');
                    
                    setTimeout(() => {
                        if (typeof window.initializeGalleryCarousel === 'function') {
                            window.initializeGalleryCarousel();
                        }
                        if (typeof window.initializeVendorsCarousel === 'function') {
                            window.initializeVendorsCarousel();
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
    </style>
@endsection
