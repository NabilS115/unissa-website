@extends('layouts.app')

@section('title', 'Search Results - ' . $query)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Search Results</h1>
                    <p class="text-gray-600">
                        @if($total > 0)
                            Found {{ $total }} result{{ $total === 1 ? '' : 's' }} for "<span class="font-semibold text-teal-600">{{ $query }}</span>"
                        @else
                            No results found for "<span class="font-semibold text-gray-800">{{ $query }}</span>"
                        @endif
                    </p>
                </div>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('search') }}" class="flex gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $query }}" 
                               placeholder="Search..." 
                               class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <select name="scope" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="all" {{ $scope === 'all' ? 'selected' : '' }}>All</option>
                        <option value="products" {{ $scope === 'products' ? 'selected' : '' }}>Products</option>
                        <option value="reviews" {{ $scope === 'reviews' ? 'selected' : '' }}>Reviews</option>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <option value="users" {{ $scope === 'users' ? 'selected' : '' }}>Users</option>
                        @endif
                    </select>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Search
                    </button>
                </form>
            </div>
        </div>

        @if($total > 0)
            <!-- Products Results -->
            @if($products->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Products ({{ $products->total() }})</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow cursor-pointer"
                                 onclick="window.location.href='/review/{{ $product->id }}'">
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ $product->img }}" alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover rounded-t-lg">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2">{!! $highlightText($product->name, $searchTerms ?? []) !!}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{!! $highlightText($product->category, $searchTerms ?? []) !!}</p>
                                    <p class="text-sm text-gray-500 line-clamp-2">{!! $highlightText(Str::limit($product->desc, 80), $searchTerms ?? []) !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($scope === 'products' && $products->hasPages())
                        <div class="mt-6">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Reviews Results -->
            @if($reviews->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Reviews ({{ $reviews->total() }})</h2>
                    <div class="space-y-4">
                        @foreach($reviews as $review)
                            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                                <div class="flex items-start gap-4">
                                    <img src="{{ $review->user->profile_photo_url ?? asset('images/default-profile.svg') }}" 
                                         alt="{{ $review->user->name }}" 
                                         class="w-12 h-12 rounded-full object-cover">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $review->user->name }}</h3>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="text-gray-700 mb-2">{!! $highlightText(Str::limit($review->review, 200), $searchTerms ?? []) !!}</p>
                                        @if($review->product)
                                            <a href="/review/{{ $review->product->id }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                                                View product: {!! $highlightText($review->product->name, $searchTerms ?? []) !!}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($scope === 'reviews' && $reviews->hasPages())
                        <div class="mt-6">
                            {{ $reviews->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Users Results (Admin Only) -->
            @if(auth()->check() && auth()->user()->role === 'admin' && $users->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Users ({{ $users->total() }})</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <div class="bg-white rounded-lg shadow p-6">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $user->profile_photo_url ?? asset('images/default-profile.svg') }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-12 h-12 rounded-full object-cover">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{!! $highlightText($user->name, $searchTerms ?? []) !!}</h3>
                                        <p class="text-sm text-gray-600">{!! $highlightText($user->email, $searchTerms ?? []) !!}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ ucfirst($user->role ?? 'user') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($scope === 'users' && $users->hasPages())
                        <div class="mt-6">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No results found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your search terms or scope</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <a href="/catalog" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Browse Products
                    </a>
                    <a href="/" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Back to Home
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@php
// Helper function to highlight search terms
$highlightText = function($text, $searchTerms) {
    if (empty($searchTerms) || empty($text)) {
        return $text;
    }
    
    foreach ($searchTerms as $term) {
        if (strlen($term) >= 2) {
            $pattern = '/(' . preg_quote($term, '/') . ')/i';
            $text = preg_replace($pattern, '<mark class="bg-yellow-200 text-gray-900 font-medium px-1 rounded">$1</mark>', $text);
        }
    }
    
    return $text;
};
@endphp

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Highlight styling */
mark {
    background-color: #fef08a;
    color: #374151;
    font-weight: 600;
    padding: 2px 4px;
    border-radius: 3px;
    box-decoration-break: clone;
}
</style>
@endsection
