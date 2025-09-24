@extends('layouts.app')

@section('title', 'UNISSA - Food Catalog')

@section('content')
    <!-- Hero Banner Section -->
    <section class="w-full h-80 flex flex-col items-center justify-center mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80" alt="Food Banner" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow-lg mb-4">Taste the World, One Bite at a Time</h1>
            <p class="text-lg md:text-xl text-white drop-shadow-md mb-6">Discover flavors, savor moments, and enjoy every meal.</p>
            <a href="/catalog" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
                Explore Catalog
                <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Events Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden relative" style="min-height: 420px;">
            <div id="event-bg-carousel" class="absolute inset-0 w-full h-full overflow-hidden z-0">
                <div id="event-bg-track" class="flex w-full h-full transition-transform duration-700">
                    <!-- Slides will be rendered by JS -->
                </div>
            </div>
            <div class="w-full flex items-center justify-center relative bg-transparent py-8 z-10" style="min-height: 420px;">
                <!-- Carousel Controls (right) -->
                <button id="event-carousel-next"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none rounded bg-white/60 shadow p-2 z-20">
                    &#8250;
                </button>
                <!-- Carousel Controls (left) -->
                <button id="event-carousel-prev"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none rounded bg-white/60 shadow p-2 z-20">
                    &#8249;
                </button>
                <!-- Carousel Dots -->
                <div id="event-carousel-dots" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-20">
                    <!-- Dots will be rendered by JS -->
                </div>
            </div>
        </div>
    </section>

    <!-- Food Cards Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-teal-700 mb-2">
                    <a href="/catalog" class="hover:underline">Top-Rated Foods & Beverages</a>
                </h2>
                <p class="text-gray-600">Discover our customers' favorite culinary experiences</p>
            </div>
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="mt-4 lg:mt-0">
                    <a href="{{ route('featured.manage') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium transition-colors">
                        View Featured Products
                    </a>
                </div>
            @endif
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($featuredFood && $featuredFood->count() > 0)
                @foreach($featuredFood as $product)
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer border border-gray-100"
                         onclick="navigateToReview({{ $product->id }})">
                        <div class="w-full h-48 relative bg-gradient-to-br from-teal-50 to-green-50 overflow-hidden">
                            <img src="{{ $product->img }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-xs font-bold text-white bg-green-600 px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm bg-opacity-90">{{ $product->category }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2 text-gray-800 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-lg">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                    <span class="text-sm text-yellow-700 font-semibold">{{ $product->calculated_rating }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $product->review_count }} reviews</span>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4">{{ Str::limit($product->desc, 120) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="inline-block bg-teal-100 text-teal-700 rounded-full px-3 py-1 text-xs font-medium">#{{ ucfirst($product->type) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Featured Foods Yet</h3>
                    <p class="text-gray-600 mb-4">Be the first to review our amazing products!</p>
                    <a href="/catalog" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">Browse Catalog</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Merchandise Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-teal-700 mb-2">Premium Merchandise</h2>
            <p class="text-gray-600">Exclusive items loved by our community</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if($featuredMerch && $featuredMerch->count() > 0)
                @foreach($featuredMerch as $product)
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer border border-gray-100"
                         onclick="navigateToReview({{ $product->id }})">
                        <div class="w-full h-48 relative bg-gradient-to-br from-indigo-50 to-purple-50 overflow-hidden">
                            <img src="{{ $product->img }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-xs font-bold text-white bg-purple-600 px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm bg-opacity-90">{{ $product->category }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-2 text-gray-800 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-lg">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                    <span class="text-sm text-yellow-700 font-semibold">{{ $product->calculated_rating }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $product->review_count }} reviews</span>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4">{{ Str::limit($product->desc, 120) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="inline-block bg-indigo-100 text-indigo-700 rounded-full px-3 py-1 text-xs font-medium">#{{ ucfirst($product->type) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Featured Merchandise Yet</h3>
                    <p class="text-gray-600 mb-4">Check out our amazing merchandise collection!</p>
                    <a href="/catalog" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">Browse Catalog</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Reviews Section -->
    <section class="w-full bg-gradient-to-br from-teal-50 to-green-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-teal-700 mb-4">What Our Customers Say</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Real experiences from our valued community members</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Danish Naufal" class="w-12 h-12 rounded-full object-cover border-2 border-yellow-400">
                        <div>
                            <h4 class="font-semibold text-gray-900">Danish Naufal</h4>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">4.9</span>
                            </div>
                        </div>
                    </div>
                    <blockquote class="text-gray-700 italic mb-3">"The best pancake that I ever eaten for the past 10 years"</blockquote>
                    <p class="text-gray-500 text-sm">Product: Homemade Pancakes</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Aisyah Rahman" class="w-12 h-12 rounded-full object-cover border-2 border-yellow-400">
                        <div>
                            <h4 class="font-semibold text-gray-900">Aisyah Rahman</h4>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">5.0</span>
                            </div>
                        </div>
                    </div>
                    <blockquote class="text-gray-700 italic mb-3">"Absolutely delicious! The pancakes are fluffy and taste just like home."</blockquote>
                    <p class="text-gray-500 text-sm">Product: Homemade Pancakes</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://randomuser.me/api/portraits/men/33.jpg" alt="Hitstonecold Ayeeee" class="w-12 h-12 rounded-full object-cover border-2 border-yellow-400">
                        <div>
                            <h4 class="font-semibold text-gray-900">Hitstonecold Ayeeee</h4>
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 4; $i++)
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                @endfor
                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                </svg>
                                <span class="ml-2 text-sm text-gray-600">4.1</span>
                            </div>
                        </div>
                    </div>
                    <blockquote class="text-gray-700 italic mb-3">"It is worth the price. Not much too say."</blockquote>
                    <p class="text-gray-500 text-sm">Product: Homemade Pancakes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Vendors Section -->
    <section class="w-full py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-teal-700 mb-4">Our Trusted Partners</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Meet the exceptional vendors who make our culinary journey possible</p>
            </div>
            
            <div class="relative">
                <div class="overflow-hidden">
                    <div id="vendors-track" class="flex transition-transform duration-700">
                        <!-- Vendor slides will be rendered by JS -->
                    </div>
                </div>
                
                <button id="vendors-prev" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white rounded-full shadow p-2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none">&#8249;</button>
                <button id="vendors-next" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white rounded-full shadow p-2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none">&#8250;</button>
                
                <div id="vendors-dots" class="flex justify-center gap-2 mt-6"></div>
            </div>
        </div>
    </section>

    <script>
        // Sliding background carousel logic for Events section (infinite looping animation)
        const eventImages = [
            "/images/nightSky.avif",
            "/images/foods.avif",
            "/images/mountains.avif",
            "/images/mountainSunset.avif",
            "/images/chair.avif"
        ];
        let currentEvent = 0;
        const bgTrack = document.getElementById('event-bg-track');
        const prevBtn = document.getElementById('event-carousel-prev');
        const nextBtn = document.getElementById('event-carousel-next');
        const dotsEl = document.getElementById('event-carousel-dots');
        let eventInterval = null;

        function renderEventBgCarousel() {
            // Render slides with clones for infinite loop
            bgTrack.innerHTML = '';
            // Clone last slide to the beginning
            const firstClone = document.createElement('div');
            firstClone.className = "min-w-full h-full";
            firstClone.style.backgroundImage = `url('${eventImages[eventImages.length - 1]}')`;
            firstClone.style.backgroundSize = 'cover';
            firstClone.style.backgroundPosition = 'center';
            firstClone.style.backgroundRepeat = 'no-repeat';
            bgTrack.appendChild(firstClone);

            // Real slides
            eventImages.forEach((src) => {
                const slide = document.createElement('div');
                slide.className = "min-w-full h-full";
                slide.style.backgroundImage = `url('${src}')`;
                slide.style.backgroundSize = 'cover';
                slide.style.backgroundPosition = 'center';
                slide.style.backgroundRepeat = 'no-repeat';
                bgTrack.appendChild(slide);
            });

            // Clone first slide to the end
            const lastClone = document.createElement('div');
            lastClone.className = "min-w-full h-full";
            lastClone.style.backgroundImage = `url('${eventImages[0]}')`;
            lastClone.style.backgroundSize = 'cover';
            lastClone.style.backgroundPosition = 'center';
            lastClone.style.backgroundRepeat = 'no-repeat';
            bgTrack.appendChild(lastClone);

            // Set initial position (translateX(-100%))
            bgTrack.style.transition = 'none';
            bgTrack.style.transform = `translateX(-${(currentEvent + 1) * 100}%)`;
            void bgTrack.offsetWidth; // force reflow
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

        prevBtn.onclick = function() {
            moveEventCarousel(-1);
            resetEventInterval();
        };
        nextBtn.onclick = function() {
            moveEventCarousel(1);
            resetEventInterval();
        };

        renderEventBgCarousel();
        resetEventInterval();

        // Navigation function for featured products
        function navigateToReview(productId) {
            // Save homepage state
            sessionStorage.setItem('catalogState', JSON.stringify({
                source: 'homepage',
                filters: {},
                search: '',
                currentTab: 'food',
                page: 1
            }));
            
            window.location.href = `/review/${productId}`;
        }
    </script>

    <style>
        /* Enhanced text clamping utilities */
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