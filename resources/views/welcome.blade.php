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
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
            <div class="text-center lg:text-left mb-6 lg:mb-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Gallery</h2>
                <p class="text-gray-600 max-w-2xl">Browse through our featured images and moments</p>
            </div>
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="flex gap-2">
                    <button id="add-gallery-btn" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Image
                    </button>
                    <button id="manage-gallery-btn" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Manage Images
                    </button>
                </div>
            @endif
        </div>
        
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
                @if($featuredReviews && $featuredReviews->count() > 0)
                    @foreach($featuredReviews->take(3) as $review)
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center gap-3 mb-4">
                                <img src="{{ $review->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name ?? 'User') . '&background=f59e0b&color=fff' }}" 
                                     alt="{{ $review->user->name ?? 'User' }}" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-yellow-400">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $review->user->name ?? 'User' }}</h4>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ number_format($review->rating, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            <blockquote class="text-gray-700 italic mb-3">
                                "{{ Str::limit($review->review, 120) }}"
                            </blockquote>
                            <p class="text-gray-500 text-sm">
                                Product: 
                                @if($review->product)
                                    <a href="{{ route('review.show', $review->product->id) }}" class="text-teal-600 hover:text-teal-700 font-medium">
                                        {{ $review->product->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Product no longer available</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback content when no reviews exist -->
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-teal-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">UNISSA Team</h4>
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
                        <blockquote class="text-gray-700 italic mb-3">"We're committed to providing the best culinary experiences. Be the first to share your review!"</blockquote>
                        <p class="text-gray-500 text-sm">Join our community of food enthusiasts</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-green-400 to-teal-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Quality Promise</h4>
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
                        <blockquote class="text-gray-700 italic mb-3">"Every product is carefully selected and tested to ensure the highest quality standards."</blockquote>
                        <p class="text-gray-500 text-sm">Our commitment to excellence</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Community Focus</h4>
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
                        <blockquote class="text-gray-700 italic mb-3">"Building a community where food lovers can discover, share, and celebrate amazing culinary experiences."</blockquote>
                        <p class="text-gray-500 text-sm">Building connections through food</p>
                    </div>
                @endif
            </div>

            @if($featuredReviews && $featuredReviews->count() > 3)
                <div class="text-center mt-12">
                    <a href="/catalog" class="inline-flex items-center px-6 py-3 bg-white text-teal-600 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 border border-teal-200 hover:border-teal-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        View More Reviews
                    </a>
                </div>
            @endif
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
        // Enhanced gallery data from database or default
        let galleryData = @json($galleryImages ?? []);
        
        console.log('Gallery data loaded:', galleryData); // Debug log
        
        // Map gallery data to the expected format
        let eventImages = [];
        if (galleryData && galleryData.length > 0) {
            eventImages = galleryData.map(item => {
                console.log('Processing gallery item:', item); // Debug log
                return {
                    id: item.id,
                    image: item.image_url,
                    active: item.is_active,
                    order: item.sort_order
                };
            });
            console.log('Mapped event images:', eventImages); // Debug log
        } else {
            console.log('Using default images'); // Debug log
            // Default images if no database images exist
            eventImages = [
                { id: null, image: "/images/nightSky.avif" },
                { id: null, image: "/images/foods.avif" },
                { id: null, image: "/images/mountains.avif" },
                { id: null, image: "/images/mountainSunset.avif" },
                { id: null, image: "/images/chair.avif" }
            ];
        }
        
        let currentEvent = 0;
        const bgTrack = document.getElementById('event-bg-track');
        const prevBtn = document.getElementById('event-carousel-prev');
        const nextBtn = document.getElementById('event-carousel-next');
        const dotsEl = document.getElementById('event-carousel-dots');
        let eventInterval = null;

        function renderEventBgCarousel() {
            console.log('Rendering carousel with images:', eventImages); // Debug log
            
            if (!eventImages || eventImages.length === 0) {
                console.log('No images to display, hiding carousel'); // Debug log
                document.querySelector('.bg-white.rounded-2xl').style.display = 'none';
                return;
            }

            document.querySelector('.bg-white.rounded-2xl').style.display = 'block';
            
            // Render slides with clones for infinite loop
            bgTrack.innerHTML = '';
            // Clone last slide to the beginning
            const firstClone = document.createElement('div');
            firstClone.className = "min-w-full h-full";
            const lastImageUrl = eventImages[eventImages.length - 1].image;
            console.log('Setting background for first clone:', lastImageUrl); // Debug log
            firstClone.style.backgroundImage = `url('${lastImageUrl}')`;
            firstClone.style.backgroundSize = 'cover';
            firstClone.style.backgroundPosition = 'center';
            firstClone.style.backgroundRepeat = 'no-repeat';
            bgTrack.appendChild(firstClone);

            // Real slides
            eventImages.forEach((item, index) => {
                const slide = document.createElement('div');
                slide.className = "min-w-full h-full";
                console.log(`Setting background for slide ${index}:`, item.image); // Debug log
                slide.style.backgroundImage = `url('${item.image}')`;
                slide.style.backgroundSize = 'cover';
                slide.style.backgroundPosition = 'center';
                slide.style.backgroundRepeat = 'no-repeat';
                
                // Add error handling for failed image loads
                slide.onerror = function() {
                    console.error('Failed to load image:', item.image);
                };
                
                bgTrack.appendChild(slide);
            });

            // Clone first slide to the end
            const lastClone = document.createElement('div');
            lastClone.className = "min-w-full h-full";
            const firstImageUrl = eventImages[0].image;
            console.log('Setting background for last clone:', firstImageUrl); // Debug log
            lastClone.style.backgroundImage = `url('${firstImageUrl}')`;
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
            const modalHtml = `
                <div id="gallery-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
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
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
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
                                        <input type="number" name="sort_order" value="${isEdit ? gallery.order : 0}" min="0"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    </div>
                                    
                                    <div class="flex items-center pt-6">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_active" ${isEdit ? (gallery.active ? 'checked' : '') : 'checked'}
                                                   class="rounded border-gray-300 text-teal-600">
                                            <span class="ml-2 text-sm text-gray-700">Active</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="flex gap-3 pt-4">
                                    <button type="button" onclick="closeGalleryModal()" 
                                            class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="flex-1 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-medium transition-colors">
                                        ${isEdit ? 'Update Image' : 'Add Image'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Add image preview functionality
            const imageInput = document.getElementById('image-upload');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            document.getElementById('gallery-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);
                
                // Convert checkbox to boolean
                formData.set('is_active', formData.get('is_active') ? '1' : '0');
                
                try {
                    const url = isEdit ? `/gallery/${gallery.id}` : '/gallery';
                    const method = 'POST';
                    
                    if (isEdit) {
                        formData.append('_method', 'PUT');
                    }
                    
                    const response = await fetch(url, {
                        method: method,
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
                        window.location.reload();
                    } else {
                        alert(result.message || 'Failed to save image.');
                    }
                } catch (error) {
                    alert('Network error occurred.');
                }
            });
        }

        function showGalleryManagementModal() {
            const modalHtml = `
                <div id="manage-gallery-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
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

        window.closeGalleryModal = function() {
            const modal = document.getElementById('gallery-modal');
            if (modal) modal.remove();
        }

        window.closeManageGalleryModal = function() {
            const modal = document.getElementById('manage-gallery-modal');
            if (modal) modal.remove();
        }
        @endif

        // Initialize carousel
        renderEventBgCarousel();
        resetEventInterval();

        // Add missing vendors carousel functionality
        document.addEventListener('DOMContentLoaded', function() {
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
                    <div class="bg-teal-50 rounded-xl shadow-lg border p-6 min-w-[260px] max-w-xs flex flex-col items-center gap-2">
                        <img src="${v.img}" alt="${v.name}" class="w-16 h-16 rounded-full object-cover mb-2">
                        <span class="font-semibold text-teal-700">${v.name}</span>
                        <span class="text-gray-500 text-sm">${v.type}</span>
                        <p class="text-gray-600 text-center text-sm mt-2">${v.desc}</p>
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
        });

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