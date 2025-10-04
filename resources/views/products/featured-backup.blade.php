@extends('layouts.app')

@section('title', 'Unissa Cafe - Homepage')

@section('content')
@php
    // Use data passed from controller
    // Controller already passes $food and $merchandise
    
    // Debug output
    echo "<!-- DEBUG: View received Food count: " . (isset($food) ? count($food) : 'not set') . " -->";
    echo "<!-- DEBUG: View received Merchandise count: " . (isset($merchandise) ? count($merchandise) : 'not set') . " -->";
    
    // Calculate ratings for products (same logic as browse page)
    foreach ($food as $foodItem) {
        $reviews = \App\Models\Review::where('product_id', $foodItem->id)->get();
        $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            if ($rating >= 1 && $rating <= 5) {
                $ratings[$rating]++;
            }
        }
        
        $totalRatings = array_sum($ratings);
        $averageRating = 0;
        
        if ($totalRatings > 0) {
            $weightedSum = 0;
            foreach ($ratings as $star => $count) {
                $weightedSum += $star * $count;
            }
            $averageRating = $weightedSum / $totalRatings;
        }
        
        $foodItem->calculated_rating = number_format($averageRating, 1);
    }
    
    foreach ($merchandise as $merchItem) {
        $reviews = \App\Models\Review::where('product_id', $merchItem->id)->get();
        $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            if ($rating >= 1 && $rating <= 5) {
                $ratings[$rating]++;
            }
        }
        
        $totalRatings = array_sum($ratings);
        $averageRating = 0;
        
        if ($totalRatings > 0) {
            $weightedSum = 0;
            foreach ($ratings as $star => $count) {
                $weightedSum += $star * $count;
            }
            $averageRating = $weightedSum / $totalRatings;
        }
        
        $merchItem->calculated_rating = number_format($averageRating, 1);
    }
@endphp

<div x-data="{
    switchTab(tab) {
        // Navigate to menu page with selected tab
        window.location.href = '{{ route('unissa-cafe.menu') }}?tab=' + tab;
    }
}" x-cloak>
    


    <!-- Cafe Navigation -->
    <div class="w-full bg-teal-600 text-white sticky top-[72px] z-40 border-t border-teal-500">
        <div class="max-w-7xl mx-auto px-6 py-3">
            <div class="flex justify-center gap-6">
                <a href="{{ route('unissa-cafe.homepage') }}" class="px-4 py-2 rounded-lg font-semibold bg-white text-teal-700 transition-colors">
                    Homepage
                </a>
                <a href="{{ route('unissa-cafe.menu') }}" class="px-4 py-2 rounded-lg font-semibold bg-teal-700 text-white hover:bg-white hover:text-teal-700 transition-colors">
                    Catalog
                </a>
            </div>
        </div>
    </div>



    <!-- Hero Section -->
    <section class="w-full h-80 flex flex-col items-center justify-center mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80" alt="Unissa Cafe Banner" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow-lg mb-4">Welcome to Unissa Cafe</h1>
            <p class="text-lg md:text-xl text-white drop-shadow-md mb-6">Discover our signature dishes and handcrafted beverages, carefully selected for quality and customer satisfaction.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button @click="switchTab('food')" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Browse Food & Beverages
                </button>
                <button @click="switchTab('merch')" class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-50 text-teal-600 font-semibold rounded-lg border-2 border-white transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                    </svg>
                    Browse Merchandise
                </button>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <!-- Featured Food Products -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                    Featured Food & Beverages
                </h3>
                <button @click="switchTab('food')" class="text-teal-600 hover:text-teal-800 font-semibold text-sm flex items-center">
                    View All Food
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($food->take(3) as $product)

                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer" onclick="window.location.href='/product/{{ $product->id }}'">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Featured</span>
                        </div>
                        @if($product->calculated_rating && $product->calculated_rating > 0)
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-800">{{ $product->calculated_rating }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">{{ $product->category }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->desc }}</p>
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
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                    </svg>
                    Featured Merchandise
                </h3>
                <button @click="switchTab('merch')" class="text-teal-600 hover:text-teal-800 font-semibold text-sm flex items-center">
                    View All Merchandise
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($merchandise->take(3) as $product)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer" onclick="window.location.href='/product/{{ $product->id }}'">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 left-4">
                            <span class="bg-teal-500 text-white text-xs font-bold px-2 py-1 rounded-full">Featured</span>
                        </div>
                        @if($product->calculated_rating && $product->calculated_rating > 0)
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-800">{{ $product->calculated_rating }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-teal-700 bg-teal-100 rounded-full">{{ $product->category }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->desc }}</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Featured Merchandise</h3>
                    <p class="text-gray-500">Add some merchandise items to showcase here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection