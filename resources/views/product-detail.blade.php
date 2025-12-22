
<!-- Edit Review Modal (now outside reviews loop) -->
<div id="edit-review-modal" data-initial-hidden class="fixed top-0 left-0 w-full h-full backdrop-blur-sm flex items-center justify-center" style="display: none; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 my-4 relative max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <button id="close-edit-review-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Edit Your Review</h3>
                <p class="text-gray-600">Update your experience for this product</p>
            </div>
            <form id="edit-review-form" class="space-y-6">
                <input type="hidden" name="review_id" id="edit-review-id" value="">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Your Rating</label>
                    <div class="flex gap-1 justify-center mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="edit-star-btn w-10 h-10 text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="edit-rating-input" value="5">
                    <p class="text-center text-sm text-gray-500" id="edit-rating-text">Excellent</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Your Review</label>
                    <textarea name="review" id="edit-review-textarea" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-yellow-400 focus:border-transparent resize-none" rows="4" required></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" id="cancel-edit-review" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl hover:from-yellow-500 hover:to-yellow-600 font-semibold transition-all duration-200 shadow-lg">
                        Update Review
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

@push('scripts')
@endpush
    @if(session('error'))
        <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-xs flex justify-center">
            <div class="bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 border-2 border-red-600 animate-fade-in-up">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
        <style>
        .animate-fade-in-up { animation: fade-in-up 0.4s cubic-bezier(0.4,0,0.2,1); }
        </style>
    @endif
@extends('layouts.app')

@section('title', 'UNISSA Cafe - Product Details')

{{-- Preload critical product image to prevent flash --}}
@section('head')
<link rel="preload" href="{{ $product->img ?? '' }}" as="image">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0d9488">
@endsection

@push('head')
<!-- Preload critical product image to prevent flash -->
<link rel="preload" href="{{ $product->img ?? '' }}" as="image">
@endpush

@push('styles')
<style>
/* Mobile-first responsive optimizations */
@media (max-width: 640px) {
    .xl\:sticky {
        position: static !important;
    }
    
    .sticky {
        position: static !important;
    }
    
    .aspect-square {
        aspect-ratio: 4/3;
    }
    
    .min-h-screen {
        min-height: 100vh;
    }
    
    .bg-gray-50 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    /* Product image mobile fixes */
    .product-image-container {
        height: 400px !important;
        max-height: 400px !important;
        position: relative;
    }
    
    .product-image {
        width: 100% !important;
        height: 400px !important;
        object-fit: contain !important;
        object-position: center !important;
        background: white;
    }
    
    #product-image {
        transition: opacity 0.3s ease;
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        image-rendering: pixelated;
        image-rendering: auto;
    }
    
    @media (min-width: 640px) {
        .product-image-container {
            height: 500px !important;
            max-height: 500px !important;
        }
        
        .product-image {
            height: 500px !important;
        }
    }
    
    @media (min-width: 1024px) {
        .product-image-container {
            height: auto !important;
            max-height: none !important;
            aspect-ratio: 1 / 1;
        }
        
        .product-image {
            height: 100% !important;
        }
    }
    
    /* Product layout mobile fixes */
    .lg\\:grid-cols-2 {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    
    /* Container padding adjustments */
    .px-8 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .px-6 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    /* Review modal mobile fixes */
    .review-modal {
        width: 95vw !important;
        max-width: 95vw !important;
        margin: 2rem auto !important;
    }
    
    /* Enhanced mobile layout for entire page */
    .product-container {
        padding: 1rem !important;
        margin: 0 !important;
    }
    
    /* Product info section mobile spacing */
    .product-info {
        padding: 1rem !important;
        gap: 1rem !important;
    }
    
    /* Reviews section mobile optimization */
    .reviews-section {
        padding: 1rem !important;
        margin-top: 2rem !important;
    }
    
    /* Review cards mobile spacing */
    .review-card {
        padding: 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    /* Star ratings mobile sizing */
    .star-rating {
        gap: 0.25rem !important;
    }
    
    .star-btn {
        width: 32px !important;
        height: 32px !important;
    }
    
    /* Form elements mobile optimization */
    .form-textarea {
        min-height: 120px !important;
        padding: 0.75rem !important;
        font-size: 1rem !important;
    }
    
    /* Button spacing for mobile */
    .btn-group {
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    
    .btn-mobile {
        width: 100% !important;
        padding: 0.875rem 1rem !important;
        font-size: 1rem !important;
    }
    
    /* Price display mobile optimization */
    .price-large {
        font-size: 1.75rem !important;
    }
    
    /* Quantity controls mobile sizing */
    .quantity-control {
        width: 36px !important;
        height: 36px !important;
    }
    
    .quantity-input {
        width: 60px !important;
        height: 36px !important;
        font-size: 1rem !important;
    }
    
    /* Modal backdrop mobile fixes */
    .modal-backdrop {
        padding: 1rem !important;
    }
    
    /* Success toast mobile positioning */
    .success-toast {
        top: 1rem !important;
        left: 1rem !important;
        right: 1rem !important;
        width: auto !important;
    }
}

/* Touch-friendly tap targets */
@media (hover: none) {
    button, .cursor-pointer {
        min-height: 44px;
        min-width: 44px;
    }
}

/* Improved text contrast for mobile */
@media (max-width: 640px) {
    .text-gray-600 {
        color: rgb(75 85 99);
    }
    
    .text-sm {
        font-size: 0.9rem;
        line-height: 1.4;
    }
}

/* Aggressively remove ALL spinner arrows from number inputs */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    background: none !important;
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
}

/* Firefox - completely remove spinners */
input[type="number"] {
    -moz-appearance: textfield !important;
}

/* Target by class with maximum specificity */
.quantity-input,
input.quantity-input,
#quantity.quantity-input {
    -webkit-appearance: none !important;
    -moz-appearance: textfield !important;
    appearance: none !important;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button,
input.quantity-input::-webkit-outer-spin-button,
input.quantity-input::-webkit-inner-spin-button,
#quantity::-webkit-outer-spin-button,
#quantity::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
    position: absolute !important;
    left: -9999px !important;
}

/* IE/Edge fallback */
input[type="number"]::-ms-clear {
    display: none !important;
}
    pointer-events: none;


.quantity-input[type=number] {
    -moz-appearance: textfield !important;
    appearance: none !important;
}

/* Ensure proper centering and remove any default styling */
.quantity-input {
    text-align: center !important;
    -webkit-appearance: none !important;
    -moz-appearance: textfield !important;
    appearance: none !important;
}

/* Hide spinners in Internet Explorer */
.quantity-input::-ms-clear {
    display: none;
}

.quantity-input::-ms-reveal {
    display: none;
}
</style>
@endpush

@section('content')

<!-- Image Zoom Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-90 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
    <div class="relative max-w-7xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modal-image" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        <p class="absolute -bottom-12 left-0 text-white text-sm opacity-75">Click outside image to close</p>
    </div>
</div>

<div class="bg-gray-50 py-8">

    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 relative">
        @if(session('success'))
            <div id="success-toast" class="absolute top-0 left-2 sm:left-8 mt-4 sm:mt-8 z-50 max-w-xs w-fit pointer-events-none flex">
                <div class="bg-green-500 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl shadow-xl flex items-center gap-2 sm:gap-3 border-2 border-green-600 animate-fade-in-up pointer-events-auto relative w-fit text-sm sm:text-base">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                    <button id="close-success-toast" class="absolute top-1 sm:top-2 right-1 sm:right-2 text-white/70 hover:text-white transition-colors text-lg leading-none px-1 py-0.5 rounded focus:outline-none" aria-label="Close notification">&times;</button>
                </div>
            </div>
            <style>
            @keyframes fade-in-up {
                0% { opacity: 0; transform: translateY(20px) scale(0.95); }
                100% { opacity: 1; transform: translateY(0) scale(1); }
            }
            .animate-fade-in-up { animation: fade-in-up 0.4s cubic-bezier(0.4,0,0.2,1); }
            </style>
            
        @endif
        <!-- Breadcrumb and Back Button -->
        <div class="mb-4 sm:mb-6 lg:mb-8">
            <button onclick="goBack()" class="inline-flex items-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200 transition-all duration-200 font-medium text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                <span id="back-button-text" class="hidden sm:inline">Back</span>
                <span class="sm:hidden">← Back</span>
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
            <!-- Left: Product Information -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
                    <!-- Product Image -->
                    <div class="aspect-square bg-white p-4 sm:p-6 lg:p-8 product-image-container" style="border: 1px solid #f3f4f6;">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}"
                             class="w-full h-full object-contain rounded-lg sm:rounded-xl shadow-md cursor-pointer hover:shadow-lg transition-all duration-300"
                             style="background: white;"
                             onclick="openImageModal()"
                             title="Click to view larger image" />
                    </div>
                    
                    <!-- Product Details -->
                    <div class="p-4 sm:p-6">
                        <div class="mb-2">
                            <span class="inline-block px-2 sm:px-3 py-1 text-xs font-semibold text-white bg-teal-600 rounded-full" style="background-color:#0d9488;">
                                {{ ucfirst($product->type ?? 'Product') }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 leading-tight">{{ $product->name }}</h1>
                        <p class="text-gray-600 leading-relaxed mb-4 sm:mb-6 text-sm sm:text-base">{{ $product->desc }}</p>
                        
                        <!-- Category Badge -->
                        <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1 bg-gray-100 rounded-full text-xs sm:text-sm text-gray-700">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                            </svg>
                            {{ $product->category }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Form and Reviews -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Quick Order Section -->
                @auth
                    @if(Auth::user()->role !== 'admin')
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 border-b border-green-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Add to Cart</h2>
                                    <p class="text-gray-600 text-xs sm:text-sm mt-1 hidden sm:block">Select quantity and add to your cart</p>
                                </div>
                            </div>
                            <div @class([
                                'hidden sm:flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm',
                                'border-green-200' => $product->isAvailable(),
                                'border-red-200' => $product->status === 'out_of_stock',
                                'border-gray-200' => $product->status === 'inactive',
                                'border-yellow-200' => !$product->isAvailable() && $product->status !== 'out_of_stock' && $product->status !== 'inactive',
                            ])>
                                <div @class([
                                    'w-2 h-2 rounded-full',
                                    'bg-green-500' => $product->isAvailable(),
                                    'bg-red-500' => $product->status === 'out_of_stock',
                                    'bg-gray-500' => $product->status === 'inactive',
                                    'bg-yellow-500' => !$product->isAvailable() && $product->status !== 'out_of_stock' && $product->status !== 'inactive',
                                ])></div>
                                <span @class([
                                    'text-sm font-medium',
                                    'text-green-700' => $product->isAvailable(),
                                    'text-red-700' => $product->status === 'out_of_stock',
                                    'text-gray-700' => $product->status === 'inactive',
                                    'text-yellow-700' => !$product->isAvailable() && $product->status !== 'out_of_stock' && $product->status !== 'inactive',
                                ])>
                                    {{ $product->availability_status }}
                                    @if($product->track_stock && $product->isInStock())
                                        ({{ $product->stock_quantity }} left)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Order Content -->
                    <div class="p-4 sm:p-6 lg:p-8">
                        <!-- Pricing & Quantity Section -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span class="hidden sm:inline">Select Quantity & Proceed</span>
                                <span class="sm:hidden">Order Details</span>
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Price Display -->
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-5 border border-gray-200 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs sm:text-sm font-medium text-gray-600">Unit Price</span>
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <span class="text-lg sm:text-2xl font-bold text-green-600" id="unit-price">B${{ number_format($product->price ?? 0, 2) }}</span>
                                                <span class="text-xs text-gray-500 bg-gray-100 px-1 sm:px-2 py-1 rounded-full">each</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gradient-to-r from-teal-500 to-emerald-500 rounded-xl p-5 text-white shadow-lg">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-teal-100">Total Amount</span>
                                                <div class="flex items-baseline gap-2 mt-1">
                                                    <span class="text-3xl font-bold" id="total-price">B${{ number_format($product->price ?? 0, 2) }}</span>
                                                    <span class="text-sm text-teal-200">BND</span>
                                                </div>
                                            </div>
                                            <!-- Removed decorative white circle -->
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quantity Controls -->
                                <div class="space-y-3 sm:space-y-4">
                                    <label for="quantity" class="block text-xs sm:text-sm font-medium text-gray-700">Select Quantity</label>
                                    <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-5 border border-gray-200 shadow-sm">
                                        <div class="flex items-center justify-center gap-2 sm:gap-4">
                                            <button type="button" id="decrease-qty" 
                                                {{ !$product->isAvailable() ? 'disabled' : '' }}
                                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl border-2 flex items-center justify-center font-bold transition-all duration-200
                                                {{ $product->isAvailable() ? 'border-gray-300 text-gray-600 hover:border-green-500 hover:bg-green-50 hover:text-green-600' : 'border-gray-200 text-gray-400 cursor-not-allowed' }}">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <div class="flex-1 max-w-16 sm:max-w-24">
                                                <input type="text" inputmode="numeric" pattern="[0-9]*" id="quantity" name="quantity" value="1"
                                                       {{ !$product->isAvailable() ? 'disabled' : '' }}
                                                       class="w-full text-center text-lg sm:text-2xl font-bold border-2 rounded-lg sm:rounded-xl px-2 sm:px-4 py-2 sm:py-3 transition-all quantity-input
                                                       {{ $product->isAvailable() ? 'border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500' : 'border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed' }}">
                                            </div>
                                            <button type="button" id="increase-qty" 
                                                {{ !$product->isAvailable() ? 'disabled' : '' }}
                                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl border-2 flex items-center justify-center font-bold transition-all duration-200
                                                {{ $product->isAvailable() ? 'border-gray-300 text-gray-600 hover:border-green-500 hover:bg-green-50 hover:text-green-600' : 'border-gray-200 text-gray-400 cursor-not-allowed' }}">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-center mt-3">
                                            <span class="text-xs text-gray-500">Min: 1 • Max: 100</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="border-t border-gray-200 pt-8">
                            @if($product->isAvailable())
                                <button type="button" id="checkout-btn" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-4 px-8 rounded-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 text-lg" style="background-color:#0d9488;">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                    <span>Add to Cart</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </button>
                            @else
                                <div class="w-full bg-gray-400 text-white font-bold py-4 px-8 rounded-xl cursor-not-allowed flex items-center justify-center gap-3 text-lg opacity-60">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                    </svg>
                                    <span>
                                        @if($product->status === 'out_of_stock')
                                            Currently Out of Stock
                                        @elseif($product->status === 'inactive')
                                            Product Not Available
                                        @elseif($product->status === 'discontinued')
                                            Product Discontinued
                                        @else
                                            Not Available for Order
                                        @endif
                                    </span>
                                </div>
                            @endif
                            <p class="text-center text-sm text-gray-500 mt-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Complete your order details on the next page
                            </p>
                        </div>
                    </div>
                    @else
                        <!-- Admin View Message -->
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                            <div class="p-6 sm:p-8 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Admin View</h3>
                                <p class="text-gray-600">You're viewing this product as an administrator. Cart functionality is not available for admin accounts.</p>
                            </div>
                        </div>
                    @endif
                @endauth
                @guest
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 text-center">
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Ready to Order?</h3>
                        <p class="text-gray-600 max-w-md mx-auto leading-relaxed">
                            Sign in to your account to place an order for this product. It only takes a moment!
                        </p>
                    </div>
                    <div class="space-y-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl" style="background-color:#0d9488;">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Sign In to Order
                        </a>
                        <div class="text-gray-500 text-sm">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 font-medium">Create one here</a>
                        </div>
                    </div>
                </div>
                @endguest
                <!-- Rating Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    @php
                        // Calculate actual ratings distribution from reviews
                        $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                        if (isset($reviews) && $reviews->count() > 0) {
                            foreach ($reviews as $review) {
                                $rating = (int) $review->rating;
                                if ($rating >= 1 && $rating <= 5) {
                                    $ratings[$rating]++;
                                }
                            }
                        }
                        $totalRatings = array_sum($ratings);
                        
                        // Calculate average rating from actual reviews
                        $averageRating = 0;
                        if ($totalRatings > 0) {
                            $weightedSum = 0;
                            foreach ($ratings as $star => $count) {
                                $weightedSum += $star * $count;
                            }
                            $averageRating = $weightedSum / $totalRatings;
                        }
                    @endphp

                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 sm:gap-0 mb-6 sm:mb-8">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Customer Reviews</h2>
                            <p class="text-gray-600 text-sm sm:text-base">Based on {{ $totalRatings }} {{ $totalRatings === 1 ? 'review' : 'reviews' }}</p>
                        </div>
                        @auth
                            @if(Auth::user()->role !== 'admin')
                                <button id="write-review-btn" class="inline-flex items-center gap-1 sm:gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200 transform hover:scale-105 text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                    Write a Review
                                </button>
                            @else
                                <div class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold rounded-lg sm:rounded-xl text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span>Admin View - {{ $totalRatings }} Reviews</span>
                                </div>
                            @endif
                        @else
                            <div class="text-center sm:text-right">
                                <p class="text-gray-600 mb-2 sm:mb-3 text-sm sm:text-base">Want to share your experience?</p>
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-1 sm:gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-gray-900 text-white font-semibold rounded-lg sm:rounded-xl hover:bg-gray-800 transition-colors text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Login to Review
                                </a>
                            </div>
                        @endauth
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                        <!-- Overall Rating -->
                        <div class="text-center">
                            <div class="text-6xl font-bold text-gray-900 mb-2">
                                {{ $totalRatings > 0 ? number_format($averageRating, 1) : '0.0' }}
                            </div>
                            <div class="flex justify-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-gray-600">out of 5 stars</p>
                        </div>

                        <!-- Rating Breakdown -->
                        <div class="space-y-3">
                            @for($star = 5; $star >= 1; $star--)
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700 w-6">{{ $star }}</span>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                    <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full transition-all duration-700"
                                             style="width: {{ $totalRatings ? round($ratings[$star]/$totalRatings*100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-8 text-right">{{ $ratings[$star] }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                        </svg>
                        All Reviews ({{ isset($reviews) ? $reviews->count() : 0 }})
                    </h3>

                    <div class="space-y-6" id="reviews-list">
                        @if(isset($reviews) && $reviews->count() > 0)
                            @foreach($reviews as $review)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow duration-200" data-review-id="{{ $review->id }}">
                                    <div class="flex gap-4">
                                        <img src="{{ $review->user->profile_photo_url ?? asset('images/default-profile.svg') }}"
                                             alt="{{ $review->user->name ?? 'User' }}"
                                             class="w-12 h-12 rounded-full object-cover border-2 border-yellow-400 shadow-sm flex-shrink-0">
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-3">
                                                    <h4 class="font-semibold text-gray-900">{{ $review->user->name ?? 'User' }}</h4>
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
                                            
                                            <div class="text-gray-700 leading-relaxed mb-4 break-words">
                                                @php
                                                    $reviewText = $review->review;
                                                    $isLongReview = strlen($reviewText) > 300;
                                                    $truncatedText = $isLongReview ? substr($reviewText, 0, 300) : $reviewText;
                                                @endphp
                                                
                                                @if($isLongReview)
                                                    <span class="review-text-{{ $review->id }} block">{{ $truncatedText }}...</span>
                                                    <span class="review-full-{{ $review->id }} hidden">{{ $reviewText }}</span>
                                                    <button class="read-more-btn text-teal-600 hover:text-teal-800 font-medium ml-1 mt-2" 
                                                            data-review-id="{{ $review->id }}">Read more</button>
                                                @else
                                                    <span class="block">{{ $reviewText }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                @if(Auth::user())
                                                    @if(Auth::user()->id === $review->user_id)
                                                        <button class="edit-review-btn text-teal-600 hover:text-teal-800 font-medium transition-colors mr-3" data-review-id="{{ $review->id }}" data-rating="{{ $review->rating }}" data-review="{{ e($review->review) }}">
                                                            Edit Review
                                                        </button>
                                                        <button class="delete-review-btn text-red-600 hover:text-red-800 font-medium transition-colors" data-id="{{ $review->id }}">
                                                            Delete Review
                                                        </button>
                                                    @else
                                                        <button class="helpful-btn flex items-center gap-1 hover:text-gray-700 transition-colors {{ Auth::user() && $review->isHelpfulBy(Auth::user()) ? 'text-teal-600' : 'text-gray-500' }}" data-review-id="{{ $review->id }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                                            </svg>
                                                            Helpful <span class="helpful-count">({{ $review->helpful_count ?? 0 }})</span>
                                                        </button>
                                                    @endif
                                                @else
                                            </div>


                                                    <span class="flex items-center gap-1 text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.20-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                                        </svg>
                                                        Helpful <span class="helpful-count">({{ $review->helpful_count ?? 0 }})</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-12">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No reviews yet</h3>
                                <p class="text-gray-600 mb-6">Be the first to share your thoughts about this {{ strtolower($product->category ?? 'product') }}!</p>
                                @auth
                                    <button onclick="document.getElementById('write-review-btn').click()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold rounded-xl shadow-lg hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Write the First Review
                                    </button>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Write Review Modal -->
<div id="review-modal" data-initial-hidden class="fixed top-0 left-0 w-full h-full backdrop-blur-sm flex items-center justify-center" style="display: none; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 my-4 relative max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <button id="close-review-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Write a Review</h3>
                <p class="text-gray-600">Share your experience with {{ $product->name }}</p>
            </div>

            <form id="review-form" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">How would you rate this product?</label>
                    <div class="flex gap-1 justify-center mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn w-10 h-10 text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="5">
                    <p class="text-center text-sm text-gray-500" id="rating-text">Excellent</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Tell us about your experience</label>
                    <textarea name="review" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-yellow-400 focus:border-transparent resize-none" rows="4" placeholder="What did you like or dislike? What did you use this product for?" required></textarea>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" id="cancel-review" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl hover:from-yellow-500 hover:to-yellow-600 font-semibold transition-all duration-200 shadow-lg">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Image zoom modal functions
function openImageModal() {
    const productImage = document.querySelector('img[alt="{{ $product->name }}"]');
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    
    if (productImage && modal && modalImage) {
        modalImage.src = productImage.src;
        modalImage.alt = productImage.alt;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeImageModal() {
    const modal = document.getElementById('image-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside the image
document.addEventListener('click', function(e) {
    const modal = document.getElementById('image-modal');
    if (modal && e.target === modal) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

window.__productDetail = {
    csrfToken: '{{ csrf_token() }}',
    productId: {{ $product->id ?? 'null' }},
    unitPrice: {{ $product->price ?? 0 }},
    auth: {{ Auth::check() ? 'true' : 'false' }},
    userId: {{ Auth::id() ?? 'null' }}
};
</script>
<script src="/js/product-detail.js"></script>
@endpush

@endsection
