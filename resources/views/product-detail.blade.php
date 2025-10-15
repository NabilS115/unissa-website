@extends('layouts.app')

@section('title', 'Product Details')

@push('styles')
<style>
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
}

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
<div class="min-h-screen bg-gray-50 py-8">
    @if(session('success'))
        <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-xs flex justify-center">
            <div class="bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3 border-2 border-green-600 animate-fade-in-up">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb and Back Button -->
        <div class="mb-8">
            <button onclick="goBack()" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200 transition-all duration-200 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                <span id="back-button-text">Back</span>
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left: Product Information -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-8">
                    <!-- Product Image -->
                    <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 p-8">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover rounded-xl shadow-md" />
                    </div>
                    
                    <!-- Product Details -->
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-teal-500 to-green-500 rounded-full">
                                {{ ucfirst($product->type ?? 'Product') }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                        <p class="text-gray-600 leading-relaxed mb-6">{{ $product->desc }}</p>
                        
                        <!-- Category Badge -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-green-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Add to Cart</h2>
                                    <p class="text-gray-600 text-sm mt-1">Select quantity and add to your cart</p>
                                </div>
                            </div>
                            <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border 
                                @if($product->isAvailable()) border-green-200
                                @elseif($product->status === 'out_of_stock') border-red-200
                                @elseif($product->status === 'inactive') border-gray-200
                                @else border-yellow-200
                                @endif">
                                <div class="w-2 h-2 rounded-full
                                    @if($product->isAvailable()) bg-green-500 animate-pulse
                                    @elseif($product->status === 'out_of_stock') bg-red-500
                                    @elseif($product->status === 'inactive') bg-gray-500
                                    @else bg-yellow-500
                                    @endif"></div>
                                <span class="text-sm font-medium
                                    @if($product->isAvailable()) text-green-700
                                    @elseif($product->status === 'out_of_stock') text-red-700
                                    @elseif($product->status === 'inactive') text-gray-700
                                    @else text-yellow-700
                                    @endif">
                                    {{ $product->availability_status }}
                                    @if($product->track_stock && $product->isInStock())
                                        ({{ $product->stock_quantity }} left)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Order Content -->
                    <div class="p-8">
                        <!-- Pricing & Quantity Section -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                Select Quantity & Proceed
                            </h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Price Display -->
                                <div class="space-y-4">
                                    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-600">Unit Price</span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-2xl font-bold text-green-600" id="unit-price">${{ number_format($product->price ?? 0, 2) }}</span>
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">each</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl p-5 text-white shadow-lg">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-blue-100">Total Amount</span>
                                                <div class="flex items-baseline gap-2 mt-1">
                                                    <span class="text-3xl font-bold" id="total-price">${{ number_format($product->price ?? 0, 2) }}</span>
                                                    <span class="text-sm text-blue-200">USD</span>
                                                </div>
                                            </div>
                                            <!-- Removed decorative white circle -->
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quantity Controls -->
                                <div class="space-y-4">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700">Select Quantity</label>
                                    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                                        <div class="flex items-center justify-center gap-4">
                                            <button type="button" id="decrease-qty" 
                                                {{ !$product->isAvailable() ? 'disabled' : '' }}
                                                class="w-12 h-12 rounded-xl border-2 flex items-center justify-center font-bold transition-all duration-200
                                                {{ $product->isAvailable() ? 'border-gray-300 text-gray-600 hover:border-green-500 hover:bg-green-50 hover:text-green-600' : 'border-gray-200 text-gray-400 cursor-not-allowed' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <div class="flex-1 max-w-24">
                                                <input type="text" inputmode="numeric" pattern="[0-9]*" id="quantity" name="quantity" value="1"
                                                       {{ !$product->isAvailable() ? 'disabled' : '' }}
                                                       class="w-full text-center text-2xl font-bold border-2 rounded-xl px-4 py-3 transition-all quantity-input
                                                       {{ $product->isAvailable() ? 'border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500' : 'border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed' }}">
                                            </div>
                                            <button type="button" id="increase-qty" 
                                                {{ !$product->isAvailable() ? 'disabled' : '' }}
                                                class="w-12 h-12 rounded-xl border-2 flex items-center justify-center font-bold transition-all duration-200
                                                {{ $product->isAvailable() ? 'border-gray-300 text-gray-600 hover:border-green-500 hover:bg-green-50 hover:text-green-600' : 'border-gray-200 text-gray-400 cursor-not-allowed' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
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
                                <button type="button" id="checkout-btn" class="w-full bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white font-bold py-4 px-8 rounded-xl hover:from-green-600 hover:via-green-700 hover:to-emerald-700 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 text-lg">
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
                </div>
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
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
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

                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Customer Reviews</h2>
                            <p class="text-gray-600">Based on {{ $totalRatings }} {{ $totalRatings === 1 ? 'review' : 'reviews' }}</p>
                        </div>
                        @auth
                            <button id="write-review-btn" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold rounded-xl shadow-lg hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                                Write a Review
                            </button>
                        @else
                            <div class="text-center">
                                <p class="text-gray-600 mb-3">Want to share your experience?</p>
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Login to Review
                                </a>
                            </div>
                        @endauth
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                                                    <span class="review-full-{{ $review->id }} hidden block">{{ $reviewText }}</span>
                                                    <button class="read-more-btn text-blue-600 hover:text-blue-800 font-medium ml-1 mt-2" 
                                                            data-review-id="{{ $review->id }}">Read more</button>
                                                @else
                                                    <span class="block">{{ $reviewText }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                @if(Auth::user())
                                                    @if(Auth::user()->id === $review->user_id)
                                                        <button class="delete-review-btn text-red-600 hover:text-red-800 font-medium transition-colors" data-id="{{ $review->id }}">
                                                            Delete Review
                                                        </button>
                                                    @else
                                                        <button class="helpful-btn flex items-center gap-1 hover:text-gray-700 transition-colors {{ Auth::user() && $review->isHelpfulBy(Auth::user()) ? 'text-blue-600' : 'text-gray-500' }}" data-review-id="{{ $review->id }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                                            </svg>
                                                            Helpful <span class="helpful-count">({{ $review->helpful_count ?? 0 }})</span>
                                                        </button>
                                                    @endif
                                                @else
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
<div id="review-modal" class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
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

<script>
    // Simple back to catalog functionality
    window.goBack = function() {
        // Just go back to the previous page (catalog with correct tab)
        if (window.history.length > 1) {
            window.history.back();
        } else {
            // Fallback if no history
            window.location.href = '/catalog';
        }
    };

    // Set back button text
    document.addEventListener('DOMContentLoaded', function() {
        const backButtonText = document.getElementById('back-button-text');
        if (backButtonText) {
            backButtonText.textContent = 'Back';
        }
    });

    // Modal logic - Add null checks
    const modal = document.getElementById('review-modal');
    const writeReviewBtn = document.getElementById('write-review-btn');
    
    if (writeReviewBtn && modal) {
        writeReviewBtn.onclick = () => { modal.classList.remove('hidden'); };
    }
    
    const closeReviewModal = document.getElementById('close-review-modal');
    const cancelReview = document.getElementById('cancel-review');
    
    if (closeReviewModal && modal) {
        closeReviewModal.onclick = () => { modal.classList.add('hidden'); };
    }
    
    if (cancelReview && modal) {
        cancelReview.onclick = () => { modal.classList.add('hidden'); };
    }

    // Add null check for review form
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.onsubmit = async function(e) {
            e.preventDefault();
            const rating = this.rating.value;
            const reviewText = this.review.value;
            
            // Validate required fields
            if (!rating || !reviewText.trim()) {
                alert('Please provide both a rating and review text.');
                return;
            }
            
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;
            
            try {
                // Create form data for submission
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('rating', parseInt(rating));
                formData.append('review', reviewText.trim());
                
                const response = await fetch(`/product/{{ $product->id }}/add-review`, {
                    method: "POST",
                    body: formData
                });
                
                if (response.ok) {
                    // If response is successful, close modal and reload page
                    const modal = document.getElementById('review-modal');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                    window.location.reload();
                } else {
                    // Try to get error message from response
                    const text = await response.text();
                    console.error('Server response:', text);
                    alert("Failed to submit review. Please try again.");
                }
            } catch (error) {
                console.error('Network error:', error);
                alert("Network error. Please check your connection and try again.");
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        };
    }

    // Add error handling for delete review buttons
    document.querySelectorAll('.delete-review-btn').forEach(btn => {
        btn.onclick = async function(e) {
            e.preventDefault();
            if (!confirm('Delete this review?')) return;
            
            const reviewId = this.getAttribute('data-id');
            if (!reviewId) {
                alert('Review ID not found.');
                return;
            }
            
            try {
                const res = await fetch(`/reviews/${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (res.ok) {
                    const reviewElement = document.querySelector(`[data-review-id="${reviewId}"]`);
                    if (reviewElement) {
                        reviewElement.remove();
                    }
                    // Reload page to update ratings statistics
                    window.location.reload();
                } else {
                    const errorData = await res.json().catch(() => ({}));
                    alert(errorData.message || 'Failed to delete review.');
                }
            } catch (error) {
                console.error('Delete review error:', error);
                alert('Network error occurred.');
            }
        };
    });

    // Read more functionality
    document.querySelectorAll('.read-more-btn').forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            const reviewId = this.getAttribute('data-review-id');
            const truncatedText = document.querySelector(`.review-text-${reviewId}`);
            const fullText = document.querySelector(`.review-full-${reviewId}`);
            
            if (this.textContent === 'Read more') {
                truncatedText.classList.add('hidden');
                fullText.classList.remove('hidden');
                this.textContent = 'Read less';
            } else {
                truncatedText.classList.remove('hidden');
                fullText.classList.add('hidden');
                this.textContent = 'Read more';
            }
        };
    });
    
    // Star rating functionality
    const starBtns = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const ratingTexts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
    
    if (starBtns.length > 0 && ratingInput && ratingText) {
        starBtns.forEach((btn, index) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const rating = index + 1;
                ratingInput.value = rating;
                ratingText.textContent = ratingTexts[index];
                
                starBtns.forEach((star, i) => {
                    if (i < rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            });
            
            btn.addEventListener('mouseenter', () => {
                const rating = index + 1;
                starBtns.forEach((star, i) => {
                    if (i < rating) {
                        star.classList.add('text-yellow-400');
                        star.classList.remove('text-gray-300');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            });
            
            btn.addEventListener('mouseleave', () => {
                const currentRating = parseInt(ratingInput.value) || 5;
                starBtns.forEach((star, i) => {
                    if (i < currentRating) {
                        star.classList.add('text-yellow-400');
                        star.classList.remove('text-gray-300');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            });
        });
        
        // Set initial 5-star rating
        starBtns.forEach((star, i) => {
            if (i < 5) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
            }
        });
    }

    // Fix helpful button functionality with better error handling
    document.querySelectorAll('.helpful-btn').forEach(btn => {
        btn.onclick = async function(e) {
            e.preventDefault();
            
            @auth
                const reviewId = this.getAttribute('data-review-id');
                if (!reviewId) {
                    console.warn('Review ID not found');
                    return;
                }
                
                const countSpan = this.querySelector('.helpful-count');
                if (!countSpan) {
                    console.warn('Helpful count span not found');
                    return;
                }
                
                try {
                    const res = await fetch(`/reviews/${reviewId}/helpful`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await res.json();
                    
                    if (res.ok) {
                        countSpan.textContent = `(${data.helpful_count || 0})`;
                        
                        if (data.action === 'added') {
                            this.classList.add('text-blue-600');
                            this.classList.remove('text-gray-500');
                        } else {
                            this.classList.remove('text-blue-600');
                            this.classList.add('text-gray-500');
                        }
                    } else {
                        console.error('Helpful button error:', data);
                        alert(data.message || 'Failed to mark as helpful');
                    }
                } catch (error) {
                    console.error('Helpful button network error:', error);
                    alert('Network error. Please try again.');
                }
            @else
                alert('Please login to mark reviews as helpful');
            @endauth
        };
    });

    @auth
    // Order form quantity controls and price calculation
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const unitPriceElement = document.getElementById('unit-price');
    const totalPriceElement = document.getElementById('total-price');
    const unitPrice = {{ $product->price ?? 0 }};
    let inputTimeout; // Declare timeout variable in broader scope

    function updateTotalPrice() {
        // Handle partial input gracefully
        let quantity = parseInt(quantityInput.value);
        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
        }
        if (quantity > 100) {
            quantity = 100;
        }
        
        const total = unitPrice * quantity;
        if (totalPriceElement) {
            totalPriceElement.textContent = '$' + total.toFixed(2);
        }
    }

    if (decreaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function() {
            // Clear any pending input validation
            clearTimeout(inputTimeout);
            
            let currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotalPrice();
            }
        });
    }

    if (increaseBtn && quantityInput) {
        increaseBtn.addEventListener('click', function() {
            // Clear any pending input validation
            clearTimeout(inputTimeout);
            
            let currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue < 100) {
                quantityInput.value = currentValue + 1;
                updateTotalPrice();
            }
        });
    }

    if (quantityInput) {
        // Minimal interference - let user type freely
        quantityInput.addEventListener('keyup', function() {
            // Only update price display, don't modify input value
            updateTotalPrice();
        });
        
        // Clean up only when user is done editing
        quantityInput.addEventListener('blur', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value === '' || value === '0') value = '1';
            
            let numValue = parseInt(value);
            if (isNaN(numValue) || numValue < 1) numValue = 1;
            if (numValue > 100) numValue = 100;
            
            this.value = numValue;
            updateTotalPrice();
        });
        
        // Prevent pasting non-numeric content
        quantityInput.addEventListener('paste', function(e) {
            e.preventDefault();
            let paste = (e.clipboardData || window.clipboardData).getData('text');
            let numericValue = paste.replace(/[^0-9]/g, '');
            if (numericValue) {
                let value = parseInt(numericValue);
                if (value < 1) value = 1;
                if (value > 100) value = 100;
                this.value = value;
                updateTotalPrice();
            }
        });
        
        // Prevent non-numeric key presses
        quantityInput.addEventListener('keypress', function(e) {
            // Allow backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    // Order form validation and submission enhancement
    const orderForm = document.getElementById('order-form');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            const customerName = document.getElementById('customer_name');
            const customerEmail = document.getElementById('customer_email');
            const customerPhone = document.getElementById('customer_phone');
            const quantity = document.getElementById('quantity');

            // Basic validation
            if (!customerName.value.trim()) {
                e.preventDefault();
                alert('Please enter your full name.');
                customerName.focus();
                return;
            }

            if (!customerEmail.value.trim()) {
                e.preventDefault();
                alert('Please enter your email address.');
                customerEmail.focus();
                return;
            }

            if (!customerPhone.value.trim()) {
                e.preventDefault();
                alert('Please enter your phone number.');
                customerPhone.focus();
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(customerEmail.value)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                customerEmail.focus();
                return;
            }

            // Quantity validation
            const qty = parseInt(quantity.value);
            if (qty < 1 || qty > 100) {
                e.preventDefault();
                alert('Quantity must be between 1 and 100.');
                quantity.focus();
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing Order...';
                submitBtn.disabled = true;
                
                // Re-enable if form submission fails for some reason
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 10000);
            }
        });
    }

    // Payment method handling
    function updatePaymentMethod() {
        const cashPaymentInfo = document.getElementById('cash-payment-info');
        const onlinePaymentInfo = document.getElementById('online-payment-info');
        const creditCardForm = document.getElementById('credit-card-form');
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const submitButton = document.querySelector('button[type="submit"] span');
        
        paymentMethods.forEach(method => {
            if (method.checked && method.value === 'cash') {
                if (cashPaymentInfo) {
                    cashPaymentInfo.style.display = 'block';
                }
                if (onlinePaymentInfo) {
                    onlinePaymentInfo.style.display = 'none';
                }
                if (creditCardForm) {
                    creditCardForm.style.display = 'none';
                }
                if (submitButton) {
                    submitButton.textContent = 'Place Order Now';
                }
            } else if (method.checked && method.value === 'online') {
                if (cashPaymentInfo) {
                    cashPaymentInfo.style.display = 'none';
                }
                if (onlinePaymentInfo) {
                    onlinePaymentInfo.style.display = 'block';
                }
                if (creditCardForm) {
                    creditCardForm.style.display = 'block';
                }
                if (submitButton) {
                    submitButton.textContent = 'Pay Now & Place Order';
                }
            }
        });
    }

    // Credit card input formatting
    function formatCardNumber(input) {
        let value = input.value.replace(/\D/g, ''); // Remove non-digits
        value = value.substring(0, 16); // Limit to 16 digits
        value = value.replace(/(.{4})/g, '$1 '); // Add space every 4 digits
        input.value = value.trim();
    }

    function formatExpiryDate(input) {
        let value = input.value.replace(/\D/g, ''); // Remove non-digits
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        input.value = value;
    }

    function formatCVV(input) {
        input.value = input.value.replace(/\D/g, '').substring(0, 4); // Only digits, max 4
    }

    @auth
    // Add event listeners for card formatting
    document.addEventListener('DOMContentLoaded', function() {
        const cardNumberInput = document.getElementById('card_number');
        const expiryInput = document.getElementById('card_expiry');
        const cvvInput = document.getElementById('card_cvv');

        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function() {
                formatCardNumber(this);
            });
        }

        if (expiryInput) {
            expiryInput.addEventListener('input', function() {
                formatExpiryDate(this);
            });
        }

        if (cvvInput) {
            cvvInput.addEventListener('input', function() {
                formatCVV(this);
            });
        }
    });

    // Form submission handling
    const orderFormElement = document.getElementById('order-form');
    if (orderFormElement) {
        orderFormElement.addEventListener('submit', function(e) {
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (selectedPaymentMethod && selectedPaymentMethod.value === 'online') {
            // Validate credit card fields
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            const cardExpiry = document.getElementById('card_expiry').value;
            const cardCVV = document.getElementById('card_cvv').value;
            const cardholderName = document.getElementById('cardholder_name').value;

            if (!cardNumber || cardNumber.length < 13) {
                alert('Please enter a valid card number');
                e.preventDefault();
                return;
            }

            if (!cardExpiry || !cardExpiry.match(/^\d{2}\/\d{2}$/)) {
                alert('Please enter a valid expiry date (MM/YY)');
                e.preventDefault();
                return;
            }

            if (!cardCVV || cardCVV.length < 3) {
                alert('Please enter a valid CVV');
                e.preventDefault();
                return;
            }

            if (!cardholderName.trim()) {
                alert('Please enter the cardholder name');
                e.preventDefault();
                return;
            }

            // For online payment, show processing message
            const submitButton = this.querySelector('button[type="submit"]');
            const buttonText = submitButton.querySelector('span');
            const originalText = buttonText.textContent;
            
            buttonText.textContent = 'Processing Payment...';
            submitButton.disabled = true;
            
            // Re-enable after 10 seconds if something goes wrong
            setTimeout(() => {
                buttonText.textContent = originalText;
                submitButton.disabled = false;
            }, 10000);
        }
        });
    }
    @endauth
    @endauth

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded. Auth status: {{ Auth::check() ? "logged in" : "guest" }}');
        console.log('User ID: {{ Auth::id() ?? "none" }}');
        
        // Only setup checkout button if user is authenticated
        @auth
        // Checkout button functionality - prevent duplicate setup
        if (window.checkoutButtonSetup) {
            console.log('Checkout button already set up, skipping...');
            return;
        }
        window.checkoutButtonSetup = true;
        
        console.log('Setting up checkout button listener...');
        const checkoutBtn = document.getElementById('checkout-btn');
        console.log('Checkout button found:', checkoutBtn);
        
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Prevent multiple rapid clicks
                if (this.disabled) {
                    console.log('Button already clicked, ignoring...');
                    return;
                }
                
                console.log('Order button clicked!');
                
                const quantityInput = document.getElementById('quantity');
                const quantity = quantityInput ? quantityInput.value : '1';
                const productId = {{ $product->id ?? 'null' }};
                
                console.log('Quantity:', quantity, 'Product ID:', productId);
                console.log('Product ID type:', typeof productId);
                
                if (!productId || productId === 'null') {
                    console.error('Error: Product ID not found!');
                    alert('Error: Product ID is missing!');
                    return;
                }
                
                console.log('About to send AJAX request to:', `/cart/add/${productId}`);
                
                // Disable button to prevent multiple clicks
                this.disabled = true;
                
                // Add visual feedback
                this.style.opacity = '0.7';
                this.innerHTML = '<span>Adding to Cart...</span>';
                
                // Add to cart via AJAX
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', parseInt(quantity));
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                fetch('/cart/add-simple', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        this.innerHTML = '<span>✓ Added to Cart!</span>';
                        this.style.backgroundColor = '#10b981';
                        
                        // Update cart count with enhanced animation
                        if (window.updateCartCount) {
                            window.updateCartCount(data.cart_count);
                        } else {
                            // Fallback for older implementation
                            const cartCount = document.getElementById('cart-count');
                            if (cartCount) {
                                cartCount.textContent = data.cart_count;
                                cartCount.style.display = data.cart_count > 0 ? 'flex' : 'none';
                            }
                        }
                        
                        // Show success toast/notification
                        showNotification(data.message, 'success');
                        
                        // Reset button after 3 seconds
                        setTimeout(() => {
                            this.disabled = false;
                            this.style.opacity = '1';
                            this.style.backgroundColor = '';
                            this.innerHTML = '<span>Add to Cart</span>';
                        }, 3000);
                    } else {
                        throw new Error(data.message || 'Failed to add to cart');
                    }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    this.innerHTML = '<span>Error - Try Again</span>';
                    this.style.backgroundColor = '#ef4444';
                    
                    // More detailed error message
                    let errorMessage = 'Failed to add item to cart. Please try again.';
                    if (error.message.includes('HTTP error')) {
                        errorMessage = 'Server error. Please check if you are logged in.';
                    } else if (error.message.includes('JSON')) {
                        errorMessage = 'Server response error. Please try refreshing the page.';
                    }
                    
                    showNotification(errorMessage, 'error');
                    
                    // Reset button after 3 seconds
                    setTimeout(() => {
                        this.disabled = false;
                        this.style.opacity = '1';
                        this.style.backgroundColor = '';
                        this.innerHTML = '<span>Add to Cart</span>';
                    }, 3000);
                });
            });
        } else {
            console.error('Checkout button not found in DOM!');
        }
        @else
        console.log('User not authenticated - skipping all order controls setup');
        @endauth
    });

    // Notification function
    function showNotification(message, type = 'success') {
        // Remove existing notifications
        const existingNotification = document.getElementById('cart-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.id = 'cart-notification';
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    ${type === 'success' ? 
                        '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' :
                        '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>'
                    }
                </div>
                <span class="font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto-hide after 4 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }
</script>
@endsection
