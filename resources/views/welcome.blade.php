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
    <script>
        // Sliding background carousel logic for Events section (sliding animation)
        const eventImages = [
            "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80",
            "https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=1600&q=80",
            "https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80"
        ];
        let currentEvent = 0;
        const bgTrack = document.getElementById('event-bg-track');
        const prevBtn = document.getElementById('event-carousel-prev');
        const nextBtn = document.getElementById('event-carousel-next');
        const dotsEl = document.getElementById('event-carousel-dots');
        let eventInterval = null;

        function renderEventBgCarousel() {
            // Render slides
            bgTrack.innerHTML = '';
            eventImages.forEach((src, i) => {
                const slide = document.createElement('div');
                slide.className = "min-w-full h-full";
                slide.style.backgroundImage = `url('${src}')`;
                slide.style.backgroundSize = 'cover';
                slide.style.backgroundPosition = 'center';
                slide.style.backgroundRepeat = 'no-repeat';
                bgTrack.appendChild(slide);
            });
            updateEventBgCarousel();
            // Dots
            dotsEl.innerHTML = '';
            for (let i = 0; i < eventImages.length; i++) {
                const dot = document.createElement('span');
                dot.className = `w-3 h-3 rounded-full inline-block mx-1 ${i === currentEvent ? 'bg-teal-400' : 'bg-teal-200'} cursor-pointer`;
                dot.onclick = () => { 
                    currentEvent = i; 
                    updateEventBgCarousel(); 
                    resetEventInterval();
                };
                dotsEl.appendChild(dot);
            }
        }

        function updateEventBgCarousel() {
            bgTrack.style.transform = `translateX(-${currentEvent * 100}%)`;
        }

        function moveEventCarousel(dir) {
            currentEvent = (currentEvent + dir + eventImages.length) % eventImages.length;
            updateEventBgCarousel();
            updateEventBgDots();
        }

        function resetEventInterval() {
            if (eventInterval) clearInterval(eventInterval);
            eventInterval = setInterval(() => {
                moveEventCarousel(1);
            }, 4000);
        }

        function updateEventBgDots() {
            // Update dots' active state
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