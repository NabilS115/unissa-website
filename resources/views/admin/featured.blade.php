@extends('layouts.app')

@section('title', 'Featured Products Overview')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Featured Products Overview</h1>
        <p class="text-gray-600 mb-8">Products are automatically featured based on their average review ratings. Here are the current top-rated products displayed on the homepage.</p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Featured Food Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-teal-700 mb-6">Top Rated Food & Beverages</h2>
                
                <div class="space-y-4">
                    @forelse($featuredFood as $index => $product)
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg {{ $index < 3 ? 'border-l-4 border-teal-500' : '' }}">
                            <div class="flex items-center gap-4">
                                <div class="text-lg font-bold text-gray-500 w-8">{{ $index + 1 }}</div>
                                <img src="{{ $product->img }}" alt="" class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $product->category }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                    <span class="font-semibold text-gray-900">{{ $product->calculated_rating }}</span>
                                </div>
                                <div class="text-xs text-gray-500">{{ $product->review_count }} reviews</div>
                                @if($index < 3)
                                    <div class="text-xs text-teal-600 font-medium mt-1">Featured on Homepage</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>No food products with reviews found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Featured Merchandise Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-teal-700 mb-6">Top Rated Merchandise</h2>
                
                <div class="space-y-4">
                    @forelse($featuredMerch as $index => $product)
                        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg {{ $index < 3 ? 'border-l-4 border-indigo-500' : '' }}">
                            <div class="flex items-center gap-4">
                                <div class="text-lg font-bold text-gray-500 w-8">{{ $index + 1 }}</div>
                                <img src="{{ $product->img }}" alt="" class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $product->category }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                    <span class="font-semibold text-gray-900">{{ $product->calculated_rating }}</span>
                                </div>
                                <div class="text-xs text-gray-500">{{ $product->review_count }} reviews</div>
                                @if($index < 3)
                                    <div class="text-xs text-indigo-600 font-medium mt-1">Featured on Homepage</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>No merchandise products with reviews found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">How Featured Products Work</h3>
            <ul class="text-blue-800 space-y-1 text-sm">
                <li>• Products are automatically ranked by their average review rating</li>
                <li>• Only products with at least one review are eligible to be featured</li>
                <li>• The top 3 highest-rated products in each category are displayed on the homepage</li>
                <li>• Products with higher review counts are prioritized when ratings are tied</li>
                <li>• Featured products update automatically when new reviews are added</li>
            </ul>
        </div>
    </div>
</div>
@endsection
