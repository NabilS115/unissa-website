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

    <!-- Food Cards Section -->
    <section class="flex flex-wrap justify-center gap-6 p-6 flex-1 main-content mb-16">
        <h2 class="w-full text-3xl font-bold text-teal-700 mb-6 text-center">
            <a href="/catalog" class="hover:underline">Foods & Beverages</a>
        </h2>
        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white food-card">
            <div class="w-full h-48 relative food-image pizza-bg">
                <img src="/images/pizza.jpg" alt="Delicious Pizza" class="absolute inset-0 w-full h-full object-cover rounded-t bg-white" />
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
            <div class="w-full h-48 relative food-image salad-bg">
                <img src="/images/salad.jpg" alt="Fresh Salad" class="absolute inset-0 w-full h-full object-cover rounded-t bg-white" />
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
            <div class="w-full h-48 relative food-image burger-bg">
                <img src="/images/burger.jpg" alt="Gourmet Burger" class="absolute inset-0 w-full h-full object-cover rounded-t bg-white" />
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
    </section>

    <!-- Merchandise Section -->
    <section class="flex flex-wrap justify-center gap-6 p-6 flex-1 main-content mb-16">
        <h2 class="w-full text-3xl font-bold text-teal-700 mb-6 text-center">Merchandise</h2>
        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white merch-card">
            <div class="w-full h-48 relative rounded-b-xl bg-teal-100">
                <img src="/images/tshirt.jpg" alt="UNISSA T-Shirt" class="absolute inset-0 w-full h-full object-cover rounded-t bg-teal-100" />
            </div>
            <div class="px-6 py-4 card-content">
                <div class="font-bold text-xl mb-2 card-title">UNISSA T-Shirt</div>
                <p class="text-gray-700 text-base card-description">
                    Comfortable cotton t-shirt with UNISSA logo. Available in all sizes.
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 tags-section">
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#TShirt</span>
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Merch</span>
            </div>
        </div>
        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white merch-card">
            <div class="w-full h-48 relative rounded-b-xl bg-yellow-100">
                <img src="/images/mug.jpg" alt="UNISSA Mug" class="absolute inset-0 w-full h-full object-cover rounded-t bg-yellow-100" />
            </div>
            <div class="px-6 py-4 card-content">
                <div class="font-bold text-xl mb-2 card-title">UNISSA Mug</div>
                <p class="text-gray-700 text-base card-description">
                    Ceramic mug with UNISSA branding. Perfect for your morning coffee.
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 tags-section">
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Mug</span>
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Merch</span>
            </div>
        </div>
        <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white merch-card">
            <div class="w-full h-48 relative rounded-b-xl bg-pink-100">
                <img src="/images/totebag.jpg" alt="UNISSA Tote Bag" class="absolute inset-0 w-full h-full object-cover rounded-t bg-pink-100" />
            </div>
            <div class="px-6 py-4 card-content">
                <div class="font-bold text-xl mb-2 card-title">UNISSA Tote Bag</div>
                <p class="text-gray-700 text-base card-description">
                    Eco-friendly tote bag for everyday use, featuring the UNISSA logo.
                </p>
            </div>
            <div class="px-6 pt-4 pb-2 tags-section">
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#ToteBag</span>
                <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#Merch</span>
            </div>
        </div>
    </section>

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

    <!-- Vendors Section -->
    <section class="w-full flex flex-col items-center justify-center py-12 bg-white">
        <h2 class="text-3xl font-bold text-teal-700 mb-6 text-center">Our Vendors</h2>
        <div class="relative w-full max-w-5xl flex items-center justify-center">
            <button id="vendors-prev" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/60 rounded shadow p-2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none">&#8249;</button>
            <div class="overflow-hidden w-full">
                <div id="vendors-track" class="flex transition-transform duration-700">
                    <!-- Vendor slides will be rendered by JS -->
                </div>
            </div>
            <button id="vendors-next" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/60 rounded shadow p-2 text-teal-600 text-2xl hover:text-teal-800 transition-colors focus:outline-none">&#8250;</button>
            <div id="vendors-dots" class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-2 z-20"></div>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Featured Reviews Carousel
            const featuredReviews = [
                {
                    img: "https://randomuser.me/api/portraits/men/32.jpg",
                    name: "Danish Naufal",
                    rating: 4.9,
                    text: "The best pancake that I ever eaten for the past 10 years",
                    product: "Homemade Pancakes"
                },
                {
                    img: "https://randomuser.me/api/portraits/women/44.jpg",
                    name: "Aisyah Rahman",
                    rating: 5.0,
                    text: "Absolutely delicious! The pancakes are fluffy and taste just like home.",
                    product: "Homemade Pancakes"
                },
                {
                    img: "https://randomuser.me/api/portraits/men/33.jpg",
                    name: "Hitstonecold Ayeeee",
                    rating: 4.1,
                    text: "It is worth the price. Not much too say.",
                    product: "Homemade Pancakes"
                }
            ];
            let currentReview = 0;
            const reviewsTrack = document.getElementById('featured-reviews-track');
            const reviewsPrev = document.getElementById('featured-reviews-prev');
            const reviewsNext = document.getElementById('featured-reviews-next');
            const reviewsDots = document.getElementById('featured-reviews-dots');
            let reviewInterval = null;
            function renderFeaturedReviewsCarousel() {
                reviewsTrack.innerHTML = '';
                // Clone last slide to the beginning
                const firstClone = document.createElement('div');
                firstClone.className = "min-w-full flex justify-center";
                firstClone.innerHTML = reviewCardHTML(featuredReviews[featuredReviews.length - 1]);
                reviewsTrack.appendChild(firstClone);
                // Real slides
                featuredReviews.forEach(r => {
                    const slide = document.createElement('div');
                    slide.className = "min-w-full flex justify-center";
                    slide.innerHTML = reviewCardHTML(r);
                    reviewsTrack.appendChild(slide);
                });
                // Clone first slide to the end
                const lastClone = document.createElement('div');
                lastClone.className = "min-w-full flex justify-center";
                lastClone.innerHTML = reviewCardHTML(featuredReviews[0]);
                reviewsTrack.appendChild(lastClone);
                // Set initial position
                reviewsTrack.style.transition = 'none';
                reviewsTrack.style.transform = `translateX(-${(currentReview + 1) * 100}%)`;
                void reviewsTrack.offsetWidth;
                reviewsTrack.style.transition = 'transform 0.7s';
                // Dots
                reviewsDots.innerHTML = '';
                for (let i = 0; i < featuredReviews.length; i++) {
                    const dot = document.createElement('span');
                    dot.className = `w-3 h-3 rounded-full inline-block mx-1 ${i === currentReview ? 'bg-teal-400' : 'bg-teal-200'} cursor-pointer`;
                    dot.onclick = () => { goToReviewSlide(i); resetReviewInterval(); };
                    reviewsDots.appendChild(dot);
                }
            }
            function reviewCardHTML(r) {
                return `<div class=\"bg-white rounded-xl shadow-lg border p-6 min-w-[320px] max-w-sm flex flex-col gap-2\">
                    <div class=\"flex items-center gap-3 mb-2\">
                        <img src=\"${r.img}\" alt=\"${r.name}\" class=\"w-12 h-12 rounded-full object-cover\">
                        <div>
                            <span class=\"font-semibold text-teal-700\">${r.name}</span>
                            <span class=\"text-yellow-400 ml-2 text-sm\">★ ${r.rating}</span>
                        </div>
                    </div>
                    <div class=\"text-gray-700 mb-1\">\"${r.text}\"</div>
                    <div class=\"text-gray-500 text-xs\">Product: ${r.product}</div>
                </div>`;
            }
            function goToReviewSlide(idx) {
                currentReview = idx;
                reviewsTrack.style.transition = 'transform 0.7s';
                reviewsTrack.style.transform = `translateX(-${(currentReview + 1) * 100}%)`;
                updateReviewDots();
            }
            function moveReviewCarousel(dir) {
                reviewsTrack.style.transition = 'transform 0.7s';
                if (dir === 1) {
                    currentReview++;
                    reviewsTrack.style.transform = `translateX(-${(currentReview + 1) * 100}%)`;
                    if (currentReview === featuredReviews.length) {
                        setTimeout(() => {
                            reviewsTrack.style.transition = 'none';
                            currentReview = 0;
                            reviewsTrack.style.transform = `translateX(-100%)`;
                            updateReviewDots();
                            void reviewsTrack.offsetWidth;
                            reviewsTrack.style.transition = 'transform 0.7s';
                        }, 700);
                    } else {
                        updateReviewDots();
                    }
                } else {
                    currentReview--;
                    reviewsTrack.style.transform = `translateX(-${(currentReview + 1) * 100}%)`;
                    if (currentReview < 0) {
                        setTimeout(() => {
                            reviewsTrack.style.transition = 'none';
                            currentReview = featuredReviews.length - 1;
                            reviewsTrack.style.transform = `translateX(-${featuredReviews.length * 100}%)`;
                            updateReviewDots();
                            void reviewsTrack.offsetWidth;
                            reviewsTrack.style.transition = 'transform 0.7s';
                        }, 700);
                    } else {
                        updateReviewDots();
                    }
                }
            }
            function resetReviewInterval() {
                if (reviewInterval) clearInterval(reviewInterval);
                reviewInterval = setInterval(() => {
                    moveReviewCarousel(1);
                }, 5000);
            }
            function updateReviewDots() {
                Array.from(reviewsDots.children).forEach((dot, i) => {
                    dot.className = `w-3 h-3 rounded-full inline-block mx-1 ${i === currentReview ? 'bg-teal-400' : 'bg-teal-200'} cursor-pointer`;
                });
            }
            reviewsPrev.onclick = function() { moveReviewCarousel(-1); resetReviewInterval(); };
            reviewsNext.onclick = function() { moveReviewCarousel(1); resetReviewInterval(); };
            renderFeaturedReviewsCarousel();
            resetReviewInterval();
        });

        // Vendors Carousel (3 at a time)
        const vendors = [
            {
                img: "https://randomuser.me/api/portraits/men/21.jpg",
                name: "Ahmad's Bakery",
                type: "Baked Goods",
                desc: "Freshly baked breads, cakes, and pastries every day."
            },
            {
                img: "https://randomuser.me/api/portraits/women/22.jpg",
                name: "Siti's Organics",
                type: "Organic Produce",
                desc: "Locally grown organic fruits and vegetables."
            },
            {
                img: "https://randomuser.me/api/portraits/men/23.jpg",
                name: "Joe's Grill",
                type: "Grilled Specialties",
                desc: "Delicious grilled meats and seafood, cooked to perfection."
            },
            {
                img: "https://randomuser.me/api/portraits/women/24.jpg",
                name: "Maya's Sweets",
                type: "Desserts",
                desc: "Handmade cakes, cookies, and sweet treats."
            },
            {
                img: "https://randomuser.me/api/portraits/men/25.jpg",
                name: "Ali's Seafood",
                type: "Seafood",
                desc: "Fresh seafood delivered daily from the coast."
            },
            {
                img: "https://randomuser.me/api/portraits/women/30.jpg",
                name: "Lina's Juice Bar",
                type: "Beverages",
                desc: "Freshly squeezed juices and smoothies made to order."
            }
        ];
        let currentVendor = 0;
        const vendorsTrack = document.getElementById('vendors-track');
        const vendorsPrev = document.getElementById('vendors-prev');
        const vendorsNext = document.getElementById('vendors-next');
        const vendorsDots = document.getElementById('vendors-dots');
        let vendorInterval = null;
        const vendorsPerSlide = 3;
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
                <div class=\"bg-teal-50 rounded-xl shadow-lg border p-6 min-w-[260px] max-w-xs flex flex-col items-center gap-2\">
                    <img src=\"${v.img}\" alt=\"${v.name}\" class=\"w-16 h-16 rounded-full object-cover mb-2\">
                    <span class=\"font-semibold text-teal-700\">${v.name}</span>
                    <span class=\"text-gray-500 text-sm\">${v.type}</span>
                    <p class=\"text-gray-600 text-center text-sm mt-2\">${v.desc}</p>
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
        vendorsPrev.onclick = function() { moveVendorCarousel(-1); resetVendorInterval(); };
        vendorsNext.onclick = function() { moveVendorCarousel(1); resetVendorInterval(); };
        renderVendorsCarousel();
        resetVendorInterval();
    </script>
@endsection