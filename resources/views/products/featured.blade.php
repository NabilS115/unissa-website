@extends('layouts.app')

@section('title', 'Unissa Cafe - Homepage')

@section('content')
<div class="container mx-auto px-6 py-8">



    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-teal-50 to-teal-100 py-16 mb-8 rounded-lg">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-gray-900 mb-6">Welcome to Unissa Cafe</h1>
            <p class="text-xl text-gray-600 mb-8">
                Discover our carefully curated selection of delicious food and premium merchandise.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('unissa-cafe.catalog') }}?tab=food" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Browse Food Menu
                </a>
                <a href="{{ route('unissa-cafe.catalog') }}?tab=merch" class="border-2 border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Shop Merchandise
                </a>
            </div>
        </div>
    </div>

    <!-- About Unissa Cafe Section -->
    <section class="w-full mb-16">
        <div class="bg-gradient-to-br from-teal-600 to-teal-700 rounded-2xl shadow-lg overflow-hidden text-white">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <!-- Content -->
                <div class="p-8 lg:p-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold">About Unissa Cafe</h2>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Delicious Food & Premium Merchandise</h3>
                    <p class="text-teal-100 mb-8 leading-relaxed">
                        Discover our carefully curated selection of mouth-watering food and high-quality merchandise. 
                        From artisan pizzas and fresh salads to exclusive branded items, Unissa Cafe offers an 
                        unforgettable experience that combines great taste with premium quality.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('unissa-cafe.catalog') }}?tab=food" class="inline-flex items-center justify-center px-6 py-3 bg-white text-teal-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors shadow-lg">
                            Browse Food Menu
                            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="{{ route('unissa-cafe.catalog') }}?tab=merch" class="inline-flex items-center justify-center px-6 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-teal-600 transition-colors">
                            Shop Merchandise
                        </a>
                    </div>
                </div>
                <!-- Image -->
                <div class="p-8 lg:p-12">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white bg-opacity-10 rounded-2xl transform rotate-3"></div>
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?auto=format&fit=crop&w=800&q=80" 
                             alt="Unissa Cafe Food" 
                             class="relative w-full h-64 lg:h-80 object-cover rounded-2xl shadow-xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Food Products -->
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-center mb-8">Featured Food & Beverages</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($food->take(3) as $product)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer" onclick="window.location.href='/product/{{ $product->id }}'">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Featured</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">{{ $product->category }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-4">{{ $product->desc }}</p>
                        @if($product->price)
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-teal-600">${{ number_format($product->price, 2) }}</span>
                            <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                Order Now
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
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer" onclick="window.location.href='/product/{{ $product->id }}'">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 left-4">
                            <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">Featured</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">{{ $product->category }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-4">{{ $product->desc }}</p>
                        @if($product->price)
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-teal-600">${{ number_format($product->price, 2) }}</span>
                            <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                Buy Now
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
            Browse our full menu and discover what makes us special.
        </p>
        <a href="{{ route('unissa-cafe.catalog') }}" class="bg-white text-teal-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition-colors inline-block">
            Explore Full Menu
        </a>
    </div>
</div>
@endsection