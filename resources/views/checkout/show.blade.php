@extends('layouts.app')

@section('title', 'Checkout - ' . $product->name)

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
                                <span class="font-semibold text-gray-800">${{ number_format($unitPrice, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Quantity:</span>
                                <span class="font-semibold text-gray-800">{{ $quantity }}</span>
                            </div>
                            <div class="border-t border-teal-200 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-gray-800">Total:</span>
                                    <span class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">${{ number_format($totalPrice, 2) }}</span>
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
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pickup Information
                        </h2>

                        <!-- Pickup Location Info -->
                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-6">
                            <h3 class="font-semibold text-purple-800 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Pickup Location
                            </h3>
                            <p class="text-purple-700 text-sm">
                                <strong>Unissa Café</strong><br>
                                123 Main Street<br>
                                City Center, State 12345<br>
                                <span class="text-purple-600 font-medium">📞 Phone: (555) 123-4567</span>
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
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Payment Method
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Payment Method Selection -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                                <!-- Online Payment Option -->
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="online" 
                                           class="sr-only peer" onclick="updatePaymentMethod()">
                                    <div class="w-full p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">Pay Online</h4>
                                                <p class="text-sm text-gray-600 mt-1">Credit/Debit Card, PayPal</p>
                                                <div class="flex items-center gap-1 mt-2">
                                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs text-blue-600 font-medium">Secure & Fast</span>
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

                            <!-- Online Payment Info -->
                            <div id="online-payment-info" class="bg-blue-50 border border-blue-200 rounded-xl p-4" style="display: none;">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-blue-800 mb-1">Online Payment Details</h4>
                                        <ul class="text-sm text-blue-700 space-y-1">
                                            <li>• Secure SSL encrypted payment processing</li>
                                            <li>• Accept all major credit/debit cards</li>
                                            <li>• PayPal and digital wallet options available</li>
                                            <li>• Payment processed immediately upon order confirmation</li>
                                            <li>• Email receipt sent instantly</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Card Form -->
                            <div id="credit-card-form" class="bg-white border border-gray-200 rounded-xl p-6" style="display: none;">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Payment Information
                                </h4>
                                
                                <div class="space-y-4">
                                    <!-- Card Number -->
                                    <div>
                                        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                        <div class="relative">
                                            <input type="text" id="card_number" name="card_number" 
                                                   placeholder="1234 5678 9012 3456" maxlength="19"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-12">
                                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Expiry Date -->
                                        <div>
                                            <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                            <input type="text" id="card_expiry" name="card_expiry" 
                                                   placeholder="MM/YY" maxlength="5"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <!-- CVV -->
                                        <div>
                                            <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                            <input type="text" id="card_cvv" name="card_cvv" 
                                                   placeholder="123" maxlength="4"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Cardholder Name -->
                                    <div>
                                        <label for="cardholder_name" class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name</label>
                                        <input type="text" id="cardholder_name" name="cardholder_name" 
                                               placeholder="John Doe"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <!-- Billing Address Toggle -->
                                    <div class="pt-2">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" id="same_as_customer" name="same_as_customer" checked 
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Billing address same as customer information</span>
                                        </label>
                                    </div>

                                    <!-- Security Notice -->
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mt-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-gray-600">Your payment information is secure and encrypted</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <button type="submit" class="w-full bg-gradient-to-r from-green-500 via-green-600 to-emerald-600 text-white font-bold py-4 px-8 rounded-xl hover:from-green-600 hover:via-green-700 hover:to-emerald-700 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 text-lg">
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
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
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
                    submitButton.textContent = 'Complete Order';
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
                    submitButton.textContent = 'Pay Now & Complete Order';
                }
            }
        });
    }

    // Add event listeners
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

        // Initialize payment method display
        updatePaymentMethod();
    });

    // Form submission handling
    document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
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

            // Show processing message
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
</script>
@endsection