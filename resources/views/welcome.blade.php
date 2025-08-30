
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

    <main class="flex flex-wrap justify-center gap-6 p-6 flex-1 main-content">
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
@endsection