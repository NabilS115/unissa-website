@extends('layouts.app')

@section('title', 'Food Catalog')

@section('content')
    <!-- Banner Section -->
    <section class="w-full h-72 flex flex-col items-center justify-center mb-8 relative">
    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80" alt="Food Banner" class="absolute inset-0 w-full h-full object-cover opacity-70">
        <div class="relative z-10 text-center">
            <h2 class="text-5xl font-extrabold text-white drop-shadow-lg mb-4">Taste the World, One Bite at a Time</h2>
            <p class="text-xl text-white drop-shadow-md">Discover flavors, savor moments, and enjoy every meal.</p>
        </div>
    </section>

    <!-- Events Section -->
    <section class="w-full flex items-center justify-center py-10 mb-8">
        <div class="flex w-4/5 bg-white rounded-xl border border-teal-200 shadow-sm overflow-hidden relative" style="min-height: 420px;">
            <div id="event-bg-carousel" class="absolute inset-0 w-full h-full overflow-hidden z-0">
                <div id="event-bg-track" class="flex w-full h-full transition-transform duration-700">
                    <!-- Slides will be rendered by JS -->
                </div>
            </div>
            <div class="w-full flex items-center justify-center relative bg-transparent py-8 z-10" style="min-height: 420px;">
                <!-- Carousel Controls (right) -->
                <button id="event-carousel-next"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-teal-600 text-2x1 hover:text-teal-800 transition-colors focus:outline-none rounded bg-white/60 shadow p-2 z-20">
                    &#8250;
                </button>
                <!-- Carousel Controls (left) -->
                <button id="event-carousel-prev"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-teal-600 text-2x1 hover:text-teal-800 transition-colors focus:outline-none rounded bg-white/60 shadow p-2 z-20">
                    &#8249;
                </button>
                <!-- Carousel Dots -->
                <div id="event-carousel-dots" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-20">
                    <!-- Dots will be rendered by JS -->
                </div>
            </div>
        </div>
    </section>

    <main class="flex flex-wrap justify-center gap-6 p-6 flex-1 main-content mb-16">
        <!-- Food Cards -->
        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white food-card">
            <div class="w-full h-48 flex items-center justify-center food-image pizza-bg">
                <span>🍕</span>
            </div>
            <div class="px-6 py-4 card-content">
                <div class="font-bold text-xl mb-2 card-title">Delicious Pizza</div>
                <p class="text-gray-700 text-base card-description">
                    A tasty pizza topped with fresh ingredients and melted cheese.
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 tags-section">
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Pizza</span>
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Cheese</span>
            </div>
        </div>

        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white food-card">
            <div class="w-full h-48 flex items-center justify-center food-image salad-bg">
                <span>🥗</span>
            </div>
            <div class="px-6 py-4 card-content">
                <div class="font-bold text-xl mb-2 card-title">Fresh Salad</div>
                <p class="text-gray-700 text-base card-description">
                    A healthy mix of fresh vegetables and a light dressing.
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 tags-section">
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Salad</span>
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Healthy</span>
            </div>
        </div>

        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white food-card">
            <div class="w-full h-48 flex items-center justify-center food-image burger-bg">
                <span>🍔</span>
            </div>
            <div class="px-6 py-4 card-content">
                <div class="font-bold text-xl mb-2 card-title">Gourmet Burger</div>
                <p class="text-gray-700 text-base card-description">
                    A juicy burger with premium ingredients and special sauce.
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 tags-section">
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Burger</span>
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Meat</span>
            </div>
        </div>
    </main>

    <!-- Featured Reviews Section -->
    <section class="w-full flex flex-col items-center justify-center py-12 bg-teal-50">
        <h2 class="text-3xl font-bold text-teal-700 mb-6">Featured Reviews</h2>
        <div class="flex flex-row flex-wrap justify-center gap-8 w-full max-w-5xl px-2 overflow-x-hidden">
            <div class="bg-white rounded-xl shadow-lg border p-6 min-w-[320px] max-w-sm flex flex-col gap-2">
                <div class="flex items-center gap-3 mb-2">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Danish Naufal" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <span class="font-semibold text-teal-700">Danish Naufal</span>
                        <span class="text-yellow-400 ml-2 text-sm">★ 4.9</span>
                    </div>
                </div>
                <div class="text-gray-700 mb-1">"The best pancake that I ever eaten for the past 10 years"</div>
                <div class="text-gray-500 text-xs">Product: Homemade Pancakes</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg border p-6 min-w-[320px] max-w-sm flex flex-col gap-2">
                <div class="flex items-center gap-3 mb-2">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Aisyah Rahman" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <span class="font-semibold text-teal-700">Aisyah Rahman</span>
                        <span class="text-yellow-400 ml-2 text-sm">★ 5.0</span>
                    </div>
                </div>
                <div class="text-gray-700 mb-1">"Absolutely delicious! The pancakes are fluffy and taste just like home."</div>
                <div class="text-gray-500 text-xs">Product: Homemade Pancakes</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg border p-6 min-w-[320px] max-w-sm flex flex-col gap-2">
                <div class="flex items-center gap-3 mb-2">
                    <img src="https://randomuser.me/api/portraits/men/33.jpg" alt="Hitstonecold Ayeeee" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <span class="font-semibold text-teal-700">Hitstonecold Ayeeee</span>
                        <span class="text-yellow-400 ml-2 text-sm">★ 4.1</span>
                    </div>
                </div>
                <div class="text-gray-700 mb-1">"It is worth the price. Not much too say."</div>
                <div class="text-gray-500 text-xs">Product: Homemade Pancakes</div>
            </div>
        </div>
    </section>

    <script>
        // Sliding background carousel logic for Events section (infinite looping animation)
        const eventImages = [
            "https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=1600&q=80",
            "https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=1600&q=80",
            "https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80",
            "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80",
            "https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1600&q=80"
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
    </script>
@endsection