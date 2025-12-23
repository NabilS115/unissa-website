@extends('layouts.app')

@section('title', 'UNISSA Cafe - Search Results for "' . $query . '"')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Search Results</h1>
                    <p class="text-gray-600">Results for "<span class="font-semibold">{{ $query }}</span>"</p>
                </div>
                <a href="/catalog" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                    </svg>
                    Browse Catalog
                </a>
            </div>
        </div>

        @if(isset($results['products']) && $results['products']->count() > 0)
            <!-- Products Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Products ({{ $results['products']->count() }})
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($results['products'] as $product)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer"
                             onclick="storeScrollPositionAndNavigate('/product/{{ $product->id }}', 'search')"
                            <div class="h-48 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
                                <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI2Y0ZjRmNSIvPgo8cGF0aCBkPSJNMTYgMTFWN2E0IDQgMCAwMC04IDB2NE01IDloMTRsMSAxMkg0TDUgOXoiIHN0cm9rZT0iIzljYTNhZiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+'; this.classList.add('opacity-50');">
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product->type === 'food' ? 'bg-green-100 text-green-800' : 'bg-teal-100 text-teal-800' }}">
                                        {{ ucfirst($product->type) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                                        {{ $product->category }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-lg mb-2 text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if(isset($results['reviews']) && $results['reviews']->count() > 0)
            <!-- Reviews Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                    </svg>
                    Reviews ({{ $results['reviews']->count() }})
                </h2>
                
                <div class="space-y-4">
                    @foreach($results['reviews'] as $review)
                        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-start gap-4">
                                <img src="{{ $review->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name ?? 'User') . '&background=f59e0b&color=fff' }}" 
                                     alt="{{ $review->user->name ?? 'User' }}" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-yellow-400">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <h4 class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <p class="text-gray-700 mb-3">{{ Str::limit($review->review, 200) }}</p>
                                    @if($review->product)
                                        <a href="/product/{{ $review->product->id }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 text-sm font-medium">
                                            View Product: {{ $review->product->name }}
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if((isset($results['products']) && $results['products']->count() === 0) && 
            (isset($results['reviews']) && $results['reviews']->count() === 0) &&
            (!isset($results['users']) || $results['users']->count() === 0))
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No results found</h3>
                <p class="text-gray-600 mb-6">We couldn't find anything matching "{{ $query }}". Try different keywords or browse our catalog.</p>
                <div class="flex gap-4 justify-center">
                    <a href="/catalog" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Browse Catalog
                    </a>
                    <button onclick="history.back()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Go Back
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script src="/js/search.js"></script>
@endsection
