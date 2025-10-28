@extends('layouts.app')

@section('title', 'Product Review')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb and Back Button -->
        <div class="mb-8">
            <button onclick="goBack()" id="back-button" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200 transition-all duration-200 font-medium">
                <!-- icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                <span id="back-button-text">Back</span>
            </button>
        </div>

        {{-- The rest of the review page markup (reviews list, review modal, forms) remains unchanged. --}}

        {{-- --- Bootstrapped data for external review.js --- --}}
        <script>
            window.__review = {
                csrf: '{{ csrf_token() }}',
                productId: {{ $product->id }},
                isAuthenticated: @json(auth()->check()),
                currentUserId: @json(auth()->check() ? auth()->id() : null)
            };
        </script>
        <script src="/js/review.js"></script>

    </div>
</div>

@endsection
