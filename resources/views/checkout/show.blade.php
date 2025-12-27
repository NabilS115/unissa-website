@extends('layouts.app')

@section('title', 'Unissa Cafe - Checkout - ' . $product->name)

@push('styles')
<style>
/* Remove spinner arrows from number inputs */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
    display: none !important;
}

input[type="number"] {
    -moz-appearance: textfield !important;
}

/* Comprehensive mobile optimizations for checkout page */
@media (max-width: 768px) {
    /* Page container mobile fixes */
    .max-w-6xl {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        margin: 0 !important;
    }
    
    /* Grid layout mobile - single column */
    .grid.grid-cols-1.lg\\:grid-cols-3 {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    
    /* Order summary mobile optimization */
    .order-summary {
        position: static !important;
        margin-bottom: 2rem !important;
        order: 2 !important;
    }
    
    .lg\\:col-span-1 {
        order: 2 !important;
    }
    
    .lg\\:col-span-2 {
        order: 1 !important;
    }
    
    /* Card padding mobile adjustments */
    .checkout-card {
        padding: 1rem !important;
        border-radius: 1rem !important;
    }
    
    /* Form elements mobile optimization */
    .form-input {
        font-size: 16px !important; /* Prevent zoom on iOS */
        padding: 0.875rem !important;
        border-radius: 0.75rem !important;
    }
    
    /* Button mobile sizing */
    .checkout-btn {
        width: 100% !important;
        padding: 1rem 1.5rem !important;
        font-size: 1rem !important;
        min-height: 48px !important;
    }
    
    /* Back button mobile */
    .back-btn {
        margin-bottom: 1rem !important;
        width: auto !important;
    }
    
    /* Page header mobile */
    .page-header {
        margin-bottom: 1.5rem !important;
        text-align: center !important;
    }
    
    .page-title {
        font-size: 2rem !important;
        line-height: 1.2 !important;
    }
    
    /* Product image in summary mobile */
    .product-image {
        width: 80px !important;
        height: 80px !important;
    }
    
    /* Quantity controls mobile */
    .quantity-control {
        width: 36px !important;
        height: 36px !important;
    }
    
    .quantity-input {
        width: 60px !important;
        height: 36px !important;
        font-size: 1rem !important;
    }
    
    /* Payment method cards mobile */
    .payment-card {
        padding: 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    /* Form sections mobile spacing */
    .form-section {
        margin-bottom: 1.5rem !important;
    }
    
    .section-title {
        font-size: 1.25rem !important;
        margin-bottom: 1rem !important;
    }
    
    /* Input group mobile */
    .input-group {
        margin-bottom: 1rem !important;
    }
    
    /* Total display mobile */
    .total-display {
        font-size: 1.5rem !important;
        padding: 1rem !important;
    }
    
    /* Sticky elements mobile - remove stickiness */
    .sticky {
        position: static !important;
    }
    
    /* Order details mobile */
    .order-details {
        gap: 0.75rem !important;
    }
    
    /* Price breakdown mobile */
    .price-row {
        justify-content: space-between !important;
        font-size: 0.875rem !important;
    }
    
    .price-total {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
    }
    
    /* Form validation mobile */
    .error-message {
        font-size: 0.875rem !important;
        margin-top: 0.25rem !important;
    }
    
    /* Loading states mobile */
    .processing-overlay {
        border-radius: 1rem !important;
    }
    
    /* Radio button groups mobile */
    .radio-group {
        gap: 0.75rem !important;
    }
    
    .radio-card {
        padding: 0.875rem !important;
        min-height: 44px !important;
    }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('product.detail', $product) }}" class="inline-flex items-center gap-2 px-4 py-3 bg-white text-gray-700 rounded-xl shadow-md hover:shadow-lg border border-teal-100 hover:border-teal-300 transition-all duration-200 font-medium hover:bg-teal-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Product
            </a>
        </div>

        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent mb-2">Checkout</h1>
            <p class="text-gray-600">Complete your order information below</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-teal-100 p-8 sticky top-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent">Order Summary</h2>
                    </div>

                    <!-- Product Info -->
                    <div class="mb-6 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-100">
                        <div class="flex items-start gap-4">
                            @php
                                // Normalize image path to avoid double '/storage/storage'
                                $imgPath = $product->img;
                                if ($imgPath && Str::startsWith($imgPath, '/storage/')) {
                                    $imgPath = ltrim($imgPath, '/');
                                    // remove leading storage/ so asset('storage/...') builds correctly
                                    if (Str::startsWith($imgPath, 'storage/')) {
                                        $imgPath = substr($imgPath, strlen('storage/'));
                                    }
                                }
                                $productImageSrc = $imgPath ? (Str::startsWith($imgPath, ['http://', 'https://']) ? $imgPath : asset('storage/' . $imgPath)) : null;
                                // Inline SVG fallback (small transparent placeholder)
                                $svgFallback = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160"><rect width="100%" height="100%" fill="%23e6fffa"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23666" font-size="14">No Image</text></svg>';
                            @endphp
                            <img src="{{ $productImageSrc ?? $svgFallback }}" alt="{{ $product->name }}"
                                 class="w-18 h-18 object-cover rounded-xl border-2 border-white shadow-md" onerror="this.onerror=null;this.src='{{ $svgFallback }}';">
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 mb-1">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($product->desc, 60) }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-teal-100 text-teal-800 rounded-full">
                                        {{ $product->category }}
                                    </span>
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">
                                        {{ ucfirst($product->type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl p-4 mb-6 border border-teal-100">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Unit Price:</span>
                                <span class="font-semibold text-gray-800">B${{ number_format($unitPrice, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Quantity:</span>
                                <span class="font-semibold text-gray-800">{{ $quantity }}</span>
                            </div>
                            <div class="border-t border-teal-200 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-gray-800">Total:</span>
                                    <span class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">B${{ number_format($totalPrice, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="text-center">
                        <div class="inline-flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-teal-50 to-emerald-50 text-teal-700 rounded-xl text-sm font-semibold border border-teal-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Secure Checkout
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Checkout Form -->
            <div class="lg:col-span-2">
                @auth
                <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="{{ $quantity }}">

                    <!-- Customer Information -->
                    <div class="bg-white rounded-2xl shadow-xl border border-teal-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent">Customer Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="customer_name" name="customer_name" required 
                                       value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-300">
                            </div>
                            <div>
                                <label for="customer_email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="customer_email" name="customer_email" required 
                                       value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div class="md:col-span-2">
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" id="customer_phone" name="customer_phone" required 
                                       value="{{ old('customer_phone') }}"
                                       placeholder="(555) 123-4567"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pickup Information
                        </h2>

                        <!-- Pickup Location Info -->
                        <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mb-6">
                            <h3 class="font-semibold text-teal-800 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Pickup Location
                            </h3>
                            <p class="text-teal-700 text-sm">
                                <strong>Unissa Café</strong><br>
                                123 Main Street<br>
                                City Center, State 12345<br>
                                <span class="text-teal-600 font-medium">📞 Phone: (555) 123-4567</span>
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="pickup_notes" class="block text-sm font-medium text-gray-700 mb-2">Pickup Notes (Optional)</label>
                                <textarea id="pickup_notes" name="pickup_notes" rows="3" 
                                          placeholder="Any special instructions for pickup..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('pickup_notes') }}</textarea>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Special Instructions (Optional)</label>
                                <textarea id="notes" name="notes" rows="3" 
                                          placeholder="Any special requests or dietary requirements..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Payment Method
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Payment Method Selection -->
                            <div class="space-y-4">
                                <!-- Cash Payment Option -->
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash" checked 
                                           class="sr-only peer" onclick="updatePaymentMethod()">
                                    <div class="w-full p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">Pay with Cash</h4>
                                                <p class="text-sm text-gray-600 mt-1">Pay when you pick up your order</p>
                                                <div class="flex items-center gap-1 mt-2">
                                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs text-green-600 font-medium">Recommended</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Bank Transfer Option -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Cash Payment Info -->
                            <div id="cash-payment-info" class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-amber-800 mb-1">Cash Payment Instructions</h4>
                                        <ul class="text-sm text-amber-700 space-y-1">
                                            <li>• Please bring exact change when possible</li>
                                            <li>• Payment is due upon pickup</li>
                                            <li>• We accept bills and coins</li>
                                            <li>• Your order will be confirmed immediately</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-4 px-8 rounded-xl transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 text-lg" style="background-color:#0d9488;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Complete Order</span>
                        </button>
                        
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-500 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Your information is secure and encrypted
                            </p>
                        </div>
                    </div>
                </form>
                @else
                <!-- Not Authenticated -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Please Sign In</h2>
                    <p class="text-gray-600 mb-8">You need to be signed in to complete your order</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl" style="background-color:#0d9488;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-gray-300 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Create Account
                        </a>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
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

    // Live validation functions
    function validateCardNumber(value) {
        const digits = value.replace(/\D/g, '');
        return digits.length >= 13 && digits.length <= 16;
    }
    function validateExpiry(value) {
        if (!/^\d{2}\/\d{2}$/.test(value)) return false;
        const [mm, yy] = value.split('/').map(Number);
        if (mm < 1 || mm > 12) return false;
        // Optionally check for expired date
        return true;
    }
    function validateCVV(value) {
        return /^\d{3,4}$/.test(value);
    }
    function validateCardholderName(value) {
        return value.trim().length > 0;
    }

    function showError(input, errorId, valid) {
        const errorSpan = document.getElementById(errorId);
        if (valid) {
            input.classList.remove('border-red-500');
            errorSpan.classList.add('hidden');
        } else {
            input.classList.add('border-red-500');
            errorSpan.classList.remove('hidden');
        }
    }

    // Payment method handling
    function updatePaymentMethod() {
        const cashPaymentInfo = document.getElementById('cash-payment-info');
        const bankTransferInfo = document.getElementById('bank-transfer-info');
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const submitButton = document.querySelector('button[type="submit"] span');
        
        paymentMethods.forEach(method => {
            if (method.checked && method.value === 'cash') {
                if (cashPaymentInfo) cashPaymentInfo.style.display = 'block';
                if (bankTransferInfo) bankTransferInfo.style.display = 'none';
                if (submitButton) submitButton.textContent = 'Complete Order';
            } else if (method.checked && method.value === 'bank_transfer') {
                if (cashPaymentInfo) cashPaymentInfo.style.display = 'none';
                if (bankTransferInfo) bankTransferInfo.style.display = 'block';
                if (submitButton) submitButton.textContent = 'Complete Order';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners for payment method changes
        const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
        paymentMethodInputs.forEach(function(input) {
            input.addEventListener('change', updatePaymentMethod);
        });
        
        // Initialize payment method display
        updatePaymentMethod();
    });
</script>
@endsection