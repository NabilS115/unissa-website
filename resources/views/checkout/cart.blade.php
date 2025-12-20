@extends('layouts.app')

@section('title', 'Unissa Cafe - Checkout')

@push('styles')
<style>
/* Comprehensive mobile optimizations for cart checkout page */
@media (max-width: 768px) {
    /* Page container mobile fixes */
    .max-w-6xl {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        margin: 0 !important;
    }
    
    /* Grid layout mobile - single column, reorder sections */
    .grid.lg\\:grid-cols-3 {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    
    /* Reorder sections on mobile - form first, summary second */
    .lg\\:col-span-1 {
        order: 2 !important;
    }
    
    .lg\\:col-span-2 {
        order: 1 !important;
    }
    
    /* Remove sticky positioning on mobile */
    .sticky {
        position: static !important;
    }
    
    /* Card padding mobile adjustments */
    .checkout-card {
        padding: 1rem !important;
        border-radius: 1rem !important;
    }
    
    /* Order summary mobile optimization */
    .order-summary-card {
        margin-bottom: 1.5rem !important;
        padding: 1rem !important;
    }
    
    /* Cart item mobile layout */
    .cart-item {
        padding: 0.75rem !important;
        gap: 0.75rem !important;
    }
    
    .cart-item img {
        width: 3rem !important;
        height: 3rem !important;
    }
    
    /* Form elements mobile optimization */
    .form-input {
        font-size: 16px !important; /* Prevent zoom on iOS */
        padding: 0.875rem !important;
        border-radius: 0.75rem !important;
    }
    
    /* Grid inputs mobile - stack vertically */
    .md\\:grid-cols-2 {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    /* Button mobile sizing */
    .checkout-btn, .place-order-btn {
        width: 100% !important;
        padding: 1rem 1.5rem !important;
        font-size: 1rem !important;
        min-height: 48px !important;
    }
    
    /* Page header mobile */
    .page-header {
        margin-bottom: 1.5rem !important;
        text-align: center !important;
    }
    
    .page-title {
        font-size: 2rem !important;
        line-height: 1.2 !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Section titles mobile */
    .section-title {
        font-size: 1.25rem !important;
        margin-bottom: 1rem !important;
    }
    
    /* Form sections mobile spacing */
    .form-section {
        margin-bottom: 1.5rem !important;
    }
    
    /* Input groups mobile */
    .input-group {
        margin-bottom: 1rem !important;
    }
    
    /* Total display mobile */
    .total-display {
        font-size: 1.25rem !important;
        padding: 0.75rem !important;
    }
    
    /* Payment method cards mobile */
    .payment-method {
        padding: 0.875rem !important;
        margin-bottom: 0.75rem !important;
        min-height: 44px !important;
    }
    
    /* Radio button mobile sizing */
    .radio-input {
        min-width: 20px !important;
        min-height: 20px !important;
    }
    
    /* Error messages mobile */
    .error-message {
        font-size: 0.875rem !important;
        margin-top: 0.25rem !important;
    }
    
    /* Empty cart mobile */
    .empty-cart {
        padding: 2rem 1rem !important;
        text-align: center !important;
    }
    
    /* Icon sizing mobile */
    .section-icon {
        width: 1.5rem !important;
        height: 1.5rem !important;
    }
    
    /* Price breakdown mobile */
    .price-row {
        justify-content: space-between !important;
        font-size: 0.875rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    .price-total {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
    }
    
    /* Form validation mobile */
    .form-field {
        margin-bottom: 1rem !important;
    }
    
    .field-label {
        font-size: 0.875rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Loading states mobile */
    .processing-overlay {
        border-radius: 1rem !important;
    }
    
    /* Shop now button mobile */
    .shop-btn {
        width: 100% !important;
        padding: 0.875rem 1.5rem !important;
    }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent mb-2">Checkout</h1>
            <p class="text-gray-600">Review your order and complete payment</p>
        </div>

        <!-- Grid: Order summary (left), Checkout form (right) -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-teal-100 p-6 sticky top-8">
                    @if(count($cartItems) === 0)
                        <div class="flex flex-col items-center justify-center py-12">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Your cart is empty</h2>
                            <p class="text-gray-500 mb-6">Browse our amazing products and start building your order!</p>
                            <a href="{{ url('/products') }}" class="inline-block bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold px-6 py-3 rounded-xl shadow hover:from-teal-600 hover:to-emerald-600 transition-all duration-200">Shop Now</a>
                        </div>
                    @else
                        <div class="flex items-center gap-3 mb-6">
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent">Order Summary</h2>
                        </div>

                        <div class="space-y-4 mb-6">
                            @foreach($cartItems as $item)
                                @php
                                    $img = $item->product->img ?? null;
                                    if ($img && Str::startsWith($img, '/storage/')) {
                                        $img = ltrim($img, '/');
                                        if (Str::startsWith($img, 'storage/')) {
                                            $img = substr($img, strlen('storage/'));
                                        }
                                    }
                                    $itemImageSrc = $img ? (Str::startsWith($img, ['http://', 'https://']) ? $img : asset('storage/' . $img)) : null;
                                @endphp

                                <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-100 hover:shadow-md transition-all duration-200">
                                    <img src="{{ $itemImageSrc ?? '' }}" alt="{{ $item->product->name }}" class="w-14 h-14 object-cover rounded-xl border-2 border-white shadow-sm">
                                    <div class="flex-grow">
                                        <h4 class="font-semibold text-gray-800">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-teal-600 font-medium">{{ $item->quantity }}x B${{ number_format($item->product->price, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-teal-700">B${{ number_format($item->total_price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl p-4 mb-6 border border-teal-100">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Subtotal ({{ $totalItems }} items)</span>
                                    <span class="font-semibold text-gray-800">B${{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="border-t border-teal-200 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-gray-800">Total</span>
                                        <span class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">B${{ number_format($totalPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-teal-100 p-8">
                    <form action="{{ route('checkout.process.cart') }}" method="POST" id="checkout-form">
                        @csrf

                        <!-- Customer Information -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent">Customer Information</h3>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', Auth::user()->name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-300" required>
                                    @error('customer_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', Auth::user()->email) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-300" required>
                                    @error('customer_email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                    <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', Auth::user()->phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-300" required>
                                    @error('customer_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent">Payment Method</h3>
                            </div>

                            <div class="space-y-4">
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-teal-50 hover:border-teal-300 transition-all duration-200">
                                    <input type="radio" name="payment_method" value="cash" class="text-teal-600 focus:ring-teal-500 w-5 h-5" {{ old('payment_method', Auth::user()->payment_method ?? 'cash') === 'cash' ? 'checked' : '' }}>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-800">Cash on Pickup</div>
                                        <div class="text-sm text-gray-600">Pay when you collect your order</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-teal-50 hover:border-teal-300 transition-all duration-200">
                                    <input type="radio" name="payment_method" value="online" class="text-teal-600 focus:ring-teal-500 w-5 h-5" {{ old('payment_method', Auth::user()->payment_method) === 'online' ? 'checked' : '' }}>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-800">Credit/Debit Card</div>
                                        <div class="text-sm text-gray-600">Pay securely online now</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-teal-50 hover:border-teal-300 transition-all duration-200">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="text-teal-600 focus:ring-teal-500 w-5 h-5" {{ old('payment_method', Auth::user()->payment_method) === 'bank_transfer' ? 'checked' : '' }}>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-800">BIBD Bank Transfer</div>
                                        <div class="text-sm text-gray-600">Pay via BIBD online banking or mobile app</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Bank Transfer Fields -->
                            <div id="bank-transfer-fields" class="mt-4 p-6 bg-gradient-to-r from-teal-50 to-emerald-50 border-2 border-teal-200 rounded-xl" style="display:none;">
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold text-teal-800 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h6a1 1 0 001-1v-6a1 1 0 00-1-1h-6z"/>
                                        </svg>
                                        UNISSA Bank Transfer Details
                                    </h4>
                                    
                                    <div class="bg-white rounded-lg p-4 border border-teal-200 mb-4">
                                        <div class="space-y-3 text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-gray-700">Bank:</span>
                                                <span class="text-teal-700 font-medium">BIBD (Bank Islam Brunei Darussalam)</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-gray-700">Account Name:</span>
                                                <span class="text-teal-700 font-medium">UNISSA Café</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-gray-700">Account Number:</span>
                                                <span class="font-mono bg-teal-100 px-3 py-1 rounded text-teal-800 font-bold">[UNISSA's Real Account Number]</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="font-semibold text-gray-700">Amount:</span>
                                                <span class="text-xl font-bold text-green-600">B${{ number_format($totalPrice ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                        <h6 class="font-semibold text-amber-800 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            BIBD Transfer Instructions:
                                        </h6>
                                        <ul class="text-sm text-amber-700 space-y-1">
                                            <li>• Transfer exactly <strong>B${{ number_format($totalPrice ?? 0, 2) }}</strong> from your BIBD account</li>
                                            <li>• Use your <strong>phone number</strong> as transfer reference</li>
                                            <li>• Transfer is instant between BIBD accounts</li>
                                            <li>• Keep your transfer receipt for verification</li>
                                            <li>• Payment confirmation within 1 hour (business hours)</li>
                                            <li>• For urgent matters, WhatsApp us: <strong>+673 8123456</strong></li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="bank_name" class="block text-sm font-semibold text-teal-700 mb-2">Bank</label>
                                        <div class="w-full px-4 py-3 border border-teal-300 rounded-xl bg-gray-50 text-teal-800 font-medium">
                                            BIBD (Bank Islam Brunei Darussalam)
                                        </div>
                                        <input type="hidden" name="bank_name" value="BIBD" />
                                        <p class="text-xs text-teal-600 mt-1">We only accept BIBD transfers for faster processing</p>
                                    </div>
                                    
                                    <div>
                                        <label for="bank_reference" class="block text-sm font-semibold text-teal-700 mb-2">Transfer Reference *</label>
                                        <input name="bank_reference" id="bank_reference" type="text" autocomplete="off" value="{{ old('bank_reference', Auth::user()->phone) }}" class="w-full px-4 py-3 border border-teal-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 text-teal-800 placeholder-teal-400 bg-white" placeholder="Use your phone number (e.g., 8123456)" required />
                                        <p class="text-xs text-teal-600 mt-1">This helps us identify your payment quickly</p>
                                    </div>
                                </div>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cash Payment Info -->
                        <div id="cash-payment-info" class="mb-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl">
                            <h4 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 2v3H9V6h7z"/>
                                    <path d="M7 10v3H4v-3h3z"/>
                                </svg>
                                Cash Payment - Easy & Convenient
                            </h4>
                            
                            <div class="bg-white rounded-lg p-4 border border-green-200 mb-4">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-lg font-semibold text-gray-700">Total Amount:</span>
                                    <span class="text-2xl font-bold text-green-600">B${{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p class="mb-2"><strong>Pickup Location:</strong> UNISSA Café, [Your Address]</p>
                                    <p><strong>Business Hours:</strong> Monday-Sunday, 8:00 AM - 8:00 PM</p>
                                </div>
                            </div>
                            
                            <div class="bg-green-100 border border-green-300 rounded-lg p-4">
                                <h6 class="font-semibold text-green-800 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Cash Payment Instructions:
                                </h6>
                                <ul class="text-sm text-green-700 space-y-1">
                                    <li>• Prepare exact amount: <strong>B${{ number_format($totalPrice, 2) }}</strong></li>
                                    <li>• We'll notify you when your order is ready</li>
                                    <li>• Bring your order confirmation (email/SMS)</li>
                                    <li>• Payment due upon pickup</li>
                                    <li>• Contact us: <strong>+673 8123456</strong> for any questions</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Credit Card Form -->
                        <div id="credit-card-form" class="mb-6 space-y-4" style="display: none;">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" placeholder="1234 5678 9012 3456" value="{{ old('card_number', Auth::user()->card_number) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" oninput="validateCheckoutCardNumber()">
                                    <p id="checkout-card-number-validation" class="mt-2 text-sm"></p>
                                    @error('card_number')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                    <input type="text" name="card_expiry" id="card_expiry" placeholder="MM/YY" value="{{ old('card_expiry', Auth::user()->card_expiry) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" oninput="validateCheckoutCardExpiry()">
                                    <p id="checkout-card-expiry-validation" class="mt-2 text-sm"></p>
                                    @error('card_expiry')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                    <input type="text" name="card_cvv" id="card_cvv" placeholder="123" value="{{ old('card_cvv', Auth::user()->card_cvv) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" oninput="validateCheckoutCardCVV()">
                                    <p id="checkout-card-cvv-validation" class="mt-2 text-sm"></p>
                                    @error('card_cvv')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="cardholder_name" class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name</label>
                                    <input type="text" name="cardholder_name" id="cardholder_name" placeholder="John Doe" value="{{ old('cardholder_name', Auth::user()->cardholder_name) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    @error('cardholder_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Credit Card Payment Info -->
                        <div id="credit-payment-info" class="mb-6 p-6 bg-gradient-to-r from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl" style="display: none;">
                            <h4 class="text-lg font-semibold text-teal-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h16v3H4V6zm0 5h16v7H4v-7z"/>
                                </svg>
                                Credit/Debit Card Payment
                            </h4>
                            
                            <div class="bg-white rounded-lg p-4 border border-purple-200 mb-4">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-lg font-semibold text-gray-700">Total Amount:</span>
                                    <span class="text-2xl font-bold text-teal-600">B${{ number_format($totalPrice, 2) }}</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <svg class="w-8 h-5 mr-3" viewBox="0 0 48 32">
                                            <rect width="48" height="32" rx="4" fill="#1434CB"/>
                                            <text x="24" y="20" text-anchor="middle" fill="white" font-size="8" font-weight="bold">VISA</text>
                                        </svg>
                                        <span class="text-sm text-gray-700">Visa</span>
                                    </div>
                                    
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <svg class="w-8 h-5 mr-3" viewBox="0 0 48 32">
                                            <rect width="48" height="32" rx="4" fill="#EB001B"/>
                                            <circle cx="18" cy="16" r="8" fill="#FF5F00"/>
                                            <circle cx="30" cy="16" r="8" fill="#F79E1B"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">Mastercard</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-teal-100 border border-teal-300 rounded-lg p-4">
                                <h6 class="font-semibold text-teal-800 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Secure Online Payment:
                                </h6>
                                <ul class="text-sm text-teal-700 space-y-1">
                                    <li>• Safe & secure payment processing</li>
                                    <li>• Instant confirmation upon successful payment</li>
                                    <li>• Payment processed immediately</li>
                                    <li>• Order will be prepared after payment verification</li>
                                    <li>• Contact us: <strong>+673 8123456</strong> for payment support</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold py-4 px-8 rounded-xl hover:from-teal-700 hover:to-emerald-700 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-xl hover:shadow-2xl flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Place Order Now</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="/js/checkout.js"></script>
@endpush