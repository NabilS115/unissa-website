@extends('layouts.app')

@section('title', 'Unissa Cafe - Homepage')

@section('content')
<div class="container mx-auto px-6 py-8">



    <!-- Enhanced Hero Section -->
    <div class="relative bg-gradient-to-br from-teal-600 via-emerald-600 to-cyan-600 py-20 mb-12 rounded-3xl overflow-hidden shadow-2xl">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white/10 rounded-full"></div>
    <!-- Removed the right-side white circle -->
    {{-- <div class="absolute top-1/2 right-20 w-16 h-16 bg-white/10 rounded-full animate-bounce" style="animation-delay: 1s;"></div> --}}
    <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white/10 rounded-full"></div>
        
        <div class="relative z-10 text-center px-4">
            <div class="mb-6 animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 drop-shadow-lg">
                    Welcome to 
                    <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                        Unissa Cafe
                    </span>
                </h1>
            </div>
            
            <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-3xl mx-auto leading-relaxed drop-shadow-md">
                Experience the perfect blend of <strong>delicious cuisine</strong> and <strong>premium quality</strong>. 
                Where every bite tells a story and every product reflects excellence.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                <a href="{{ route('unissa-cafe.catalog') }}?tab=food" 
                   class="group inline-flex items-center gap-3 bg-white text-teal-600 hover:bg-gray-50 px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    Browse Food Menu
                </a>
                <a href="{{ route('unissa-cafe.catalog') }}?tab=merch" 
                   class="group inline-flex items-center gap-3 bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 border-2 border-white/30 hover:border-white/50 transform hover:-translate-y-1">
                    Shop Merchandise
                </a>
            </div>
            
            <!-- Trust Indicators -->
            <div class="flex flex-wrap justify-center gap-8 text-white/80 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Fresh Daily
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Premium Quality
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Fast Service
                </div>
            </div>
        </div>
    </div>

    <!-- About Unissa Cafe Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-gradient-to-br from-teal-50 to-green-50 rounded-2xl shadow-lg overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <!-- Content -->
                <div class="p-8 lg:p-12">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-teal-700">About Unissa Cafe</h2>
                    </div>
                    
                    <p class="text-gray-700 text-lg leading-relaxed mb-6">
                        Discover our carefully curated selection of mouth-watering food and high-quality merchandise. From artisan pizzas and fresh salads to exclusive branded items, Unissa Cafe offers an unforgettable experience that combines great taste with premium quality.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Fresh Daily</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Quality Items</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Premium Selection</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium">Great Experience</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('unissa-cafe.catalog') }}?tab=food" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
                            Browse Food Menu
                            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="{{ route('unissa-cafe.catalog') }}?tab=merch" class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-teal-600 font-semibold rounded-lg border-2 border-teal-600 transition-colors">
                            Shop Merchandise
                        </a>
                    </div>
                </div>
                
                <!-- Image -->
                <div class="relative h-64 lg:h-full">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" 
                         alt="Unissa Cafe - Delicious Food & Premium Merchandise" 
                         class="w-full h-full object-cover"
                         onerror="this.src='https://via.placeholder.com/800x400/0d9488/ffffff?text=Unissa+Cafe+Food'; this.onerror=null;">
                    <div class="absolute inset-0 bg-gradient-to-l from-transparent to-teal-600/20"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
            <p class="text-xl text-gray-600">Real reviews from our satisfied customers</p>
        </div>
        
        @if($reviews->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-{{ min($reviews->count(), 3) }} gap-8">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <!-- Star Rating -->
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" style="color: #e5e7eb;">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-500">{{ $review->rating }}/5</span>
                        </div>
                        
                        <!-- Review Text -->
                        <p class="text-gray-700 mb-6 leading-relaxed italic">"{{ Str::limit($review->review, 150) }}"</p>
                        
                        <!-- Product Info -->
                        @if($review->product)
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Product:</span>
                                    <a href="{{ route('product.detail', $review->product->id) }}" class="text-teal-600 hover:text-teal-700">
                                        {{ $review->product->name }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        
                        <!-- User Info -->
                        <div class="flex items-center">
                            @php
                                $colors = [
                                    'from-teal-400 to-emerald-400',
                                    'from-blue-400 to-indigo-400',
                                    'from-purple-400 to-pink-400',
                                    'from-orange-400 to-red-400',
                                    'from-green-400 to-teal-400',
                                ];
                                $color = $colors[$loop->index % count($colors)];
                                $initial = strtoupper(substr($review->user->name ?? 'Anonymous', 0, 1));
                            @endphp
                            <div class="w-12 h-12 bg-gradient-to-br {{ $color }} rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                {{ $initial }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                <p class="text-gray-500 text-sm">
                                    {{ $review->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            
            <!-- View All Reviews Link -->
            <div class="text-center mt-12">
                <a href="{{ route('unissa-cafe.catalog') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-teal-600 text-white font-semibold rounded-2xl hover:bg-teal-700 transition-colors shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View All Products & Reviews
                </a>
            </div>
        @else
            <!-- No Reviews Fallback -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-8 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.418 8-9.843 8-1.082 0-2.102-.168-3.063-.477L3 21l1.477-5.094C4.168 14.945 4 13.925 4 12.843 4 7.582 8.582 3 15 3s9 4.582 9 9z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">No Reviews Yet</h3>
                <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">Be the first to share your experience! Try our amazing products and leave a review.</p>
                <a href="{{ route('unissa-cafe.catalog') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-teal-600 text-white font-semibold rounded-2xl hover:bg-teal-700 transition-colors shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Browse Our Products
                </a>
            </div>
        @endif
    </section>

    <!-- Statistics/Highlights Section removed per request -->

    <!-- Featured Food Products -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-center mb-8">Featured Food & Beverages</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($food->take(3) as $product)
                    <div class="bg-white rounded-3xl shadow-2xl hover:shadow-3xl border border-teal-100 hover:border-teal-200 transition-all duration-300 overflow-hidden group cursor-pointer transform hover:-translate-y-2" onclick="window.location.href='/product/{{ $product->id }}'">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                Featured
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-3">
                            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-green-700 bg-green-50 rounded-full border border-green-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                {{ $product->category }}
                            </span>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-teal-600 transition-colors leading-tight">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-5 leading-relaxed line-clamp-2">{{ $product->desc }}</p>
                        @if($product->price)
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-teal-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500 ml-1">each</span>
                            </div>
                            <button onclick="event.stopPropagation(); addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" class="group bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                Add to Cart
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Featured Food Items</h3>
                    <p class="text-gray-500">Add some delicious food items to showcase here.</p>
                </div>
                @endforelse
        </div>
    </div>

    <!-- Featured Merchandise -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-center mb-8">Featured Merchandise</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($merchandise->take(3) as $product)
                <div class="bg-white rounded-3xl shadow-2xl hover:shadow-3xl border border-teal-100 hover:border-teal-200 transition-all duration-300 overflow-hidden group cursor-pointer transform hover:-translate-y-2" onclick="window.location.href='/product/{{ $product->id }}'">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                Featured
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-3">
                            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-50 rounded-full border border-blue-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                {{ $product->category }}
                            </span>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors leading-tight">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-5 leading-relaxed line-clamp-2">{{ $product->desc }}</p>
                        @if($product->price)
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-teal-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500 ml-1">each</span>
                            </div>
                            <button onclick="event.stopPropagation(); addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" class="group bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                Add to Cart
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Featured Merchandise</h3>
                    <p class="text-gray-500">Add some premium merchandise to showcase here.</p>
                </div>
                @endforelse
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-teal-600 text-white py-12 rounded-lg text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Experience Unissa Cafe?</h2>
        <p class="text-lg mb-6">
            Browse our full catalog and discover what makes us special.
        </p>
        <a href="{{ route('unissa-cafe.catalog') }}" class="bg-white text-teal-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-colors inline-block">
            Explore Full Catalog
        </a>
    </div>
</div>

@push('scripts')
<script src="/js/product-featured.js"></script>
@endpush

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 1s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth scrolling for better UX */
html {
    scroll-behavior: smooth;
}

/* Custom hover effects for cards */
.group:hover .transform {
    transform: translateY(-8px);
}

/* Enhanced mobile optimizations */
@media (max-width: 768px) {
    /* Container spacing */
    .container.mx-auto {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    /* Hero section mobile optimization */
    .hero-section {
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
        margin-bottom: 2rem !important;
        border-radius: 1rem !important;
    }
    
    .py-20 {
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
    }
    
    /* Typography adjustments */
    .text-5xl {
        font-size: 2rem !important;
        line-height: 1.2 !important;
    }
    
    .text-7xl {
        font-size: 2.5rem !important;
        line-height: 1.1 !important;
    }
    
    .text-xl, .text-2xl {
        font-size: 1.125rem !important;
        line-height: 1.5 !important;
    }
    
    /* Floating elements mobile adjustments */
    .floating-element {
        display: none !important;
    }
    
    /* Button mobile optimization */
    .hero-buttons {
        flex-direction: column !important;
        gap: 1rem !important;
        width: 100% !important;
    }
    
    .hero-button {
        width: 100% !important;
        justify-content: center !important;
        padding: 1rem 2rem !important;
        font-size: 1rem !important;
    }
    
    /* Featured products grid mobile */
    .featured-grid {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
        padding: 0 !important;
    }
    
    /* Product card mobile optimization */
    .product-card {
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    .product-image {
        height: 200px !important;
        object-fit: cover !important;
    }
    
    /* Stats section mobile */
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1rem !important;
    }
    
    .stat-card {
        padding: 1rem !important;
        text-align: center !important;
    }
    
    .stat-number {
        font-size: 1.5rem !important;
    }
    
    .stat-label {
        font-size: 0.875rem !important;
    }
    
    /* Section spacing mobile */
    .section-spacing {
        margin-bottom: 2rem !important;
    }
    
    /* Icon sizing mobile */
    .hero-icon {
        width: 12px !important;
        height: 12px !important;
    }
    
    .w-16.h-16 {
        width: 3rem !important;
        height: 3rem !important;
    }
    
    /* Background pattern mobile */
    .bg-pattern {
        opacity: 0.05 !important;
    }
    
    /* Navigation buttons mobile */
    .nav-buttons {
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    
    /* Content max-width mobile */
    .max-w-3xl {
        max-width: 100% !important;
        padding: 0 1rem !important;
    }
    
    /* Hero content spacing */
    .hero-content {
        padding: 0 1rem !important;
    }
    
    /* Margin adjustments */
    .mb-12 {
        margin-bottom: 2rem !important;
    }
    
    .mb-10 {
        margin-bottom: 1.5rem !important;
    }
    
    .mb-8 {
        margin-bottom: 1.25rem !important;
    }
    
    .mb-6 {
        margin-bottom: 1rem !important;
    }
}
</style>

<script>
function addToCart(productId, productName, productPrice) {
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!token) {
        console.error('CSRF token not found');
        alert('Error: Security token not found. Please refresh the page.');
        return;
    }

    // Find the button that was clicked by looking for the button in the current product card
    const productCard = event.target.closest('.group');
    const addToCartButton = productCard?.querySelector('button');
    
    // Store original button state
    let originalText = '';
    let originalClasses = '';
    
    if (addToCartButton) {
        originalText = addToCartButton.textContent;
        originalClasses = addToCartButton.className;
        
        // Change button to "Adding..." state
        addToCartButton.textContent = 'Adding...';
        addToCartButton.disabled = true;
        addToCartButton.className = originalClasses.replace(/from-teal-600 to-emerald-600/, 'from-gray-500 to-gray-600');
    }

    // Check if user is logged in
    @auth
        // Make AJAX request to add to cart
        fetch('/cart/add-simple', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success notification
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: data.message,
                        text: `${productName} - $${parseFloat(productPrice).toFixed(2)}`
                    });
                } else {
                    alert(data.message);
                }
                
                // Update cart count if cart badge exists
                if (data.cart_count !== undefined) {
                    console.log('Cart count received:', data.cart_count);
                    console.log('updateCartCount function available:', typeof window.updateCartCount);
                    
                    // Use the global updateCartCount function if available
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount(data.cart_count);
                    } else {
                        // Fallback: update cart badges directly
                        console.log('Using fallback cart count update');
                        const cartBadge = document.getElementById('cart-count');
                        const mobileCartBadge = document.getElementById('cart-count-mobile');
                        
                        console.log('Cart badges found:', { desktop: !!cartBadge, mobile: !!mobileCartBadge });
                        
                        if (cartBadge) {
                            cartBadge.textContent = data.cart_count;
                            cartBadge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                            console.log('Updated desktop cart badge to:', data.cart_count);
                        }
                        if (mobileCartBadge) {
                            mobileCartBadge.textContent = data.cart_count;
                            mobileCartBadge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                            console.log('Updated mobile cart badge to:', data.cart_count);
                        }
                        
                        // Try again after a short delay in case the function loads later
                        setTimeout(() => {
                            if (typeof window.updateCartCount === 'function') {
                                console.log('updateCartCount now available, using it');
                                window.updateCartCount(data.cart_count);
                            }
                        }, 100);
                    }
                }
                
                // Update button to success state
                if (addToCartButton) {
                    addToCartButton.textContent = 'Added to Cart!';
                    addToCartButton.className = originalClasses.replace(/from-teal-600 to-emerald-600/, 'from-green-500 to-emerald-500');
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        addToCartButton.textContent = originalText;
                        addToCartButton.className = originalClasses;
                        addToCartButton.disabled = false;
                    }, 2000);
                }
            } else {
                // Show error notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to add item to cart'
                    });
                } else {
                    alert(data.message || 'Failed to add item to cart');
                }
            }
            
            // Reset button on error
            if (addToCartButton) {
                addToCartButton.textContent = originalText;
                addToCartButton.className = originalClasses;
                addToCartButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add item to cart. Please try again.'
                });
            } else {
                alert('Failed to add item to cart. Please try again.');
            }
            
            // Reset button on error
            if (addToCartButton) {
                addToCartButton.textContent = originalText;
                addToCartButton.className = originalClasses;
                addToCartButton.disabled = false;
            }
        });
    @else
        // User not logged in - redirect to login
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Login Required',
                text: 'Please log in to add items to your cart.',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
        } else {
            if (confirm('Please log in to add items to your cart. Go to login page?')) {
                window.location.href = '/login';
            }
        }
    @endauth
}
</script>

@endsection