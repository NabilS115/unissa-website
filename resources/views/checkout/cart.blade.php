@extends('layouts.app')

@section('title', 'Checkout - Unissa Cafe')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-yellow-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Checkout</h1>
            <p class="text-gray-600">Review your order and complete payment</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <img src="{{ asset('storage/' . $item->product->img) }}" 
                                 alt="{{ $item->product->name }}"
                                 class="w-12 h-12 object-cover rounded-lg">
                            <div class="flex-grow">
                                <h4 class="font-medium text-gray-800">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->quantity }}x ${{ number_format($item->product->price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">${{ number_format($item->total_price, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal ({{ $totalItems }} items)</span>
                            <span class="font-medium">${{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery</span>
                            <span class="font-medium text-green-600">Free</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2">
                            <div class="flex justify-between">
                                <span class="text-xl font-semibold text-gray-800">Total</span>
                                <span class="text-xl font-bold text-orange-600">${{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="lg:col-span-2 order-1 lg:order-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <form action="{{ route('checkout.process.cart') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <!-- Customer Information -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Customer Information</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="customer_name" id="customer_name" 
                                           value="{{ old('customer_name', Auth::user()->name) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                           required>
                                    @error('customer_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="customer_email" id="customer_email" 
                                           value="{{ old('customer_email', Auth::user()->email) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                           required>
                                    @error('customer_email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <input type="tel" name="customer_phone" id="customer_phone" 
                                           value="{{ old('customer_phone') }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                           required>
                                    @error('customer_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Payment Method</h3>
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cash" 
                                           class="text-orange-600 focus:ring-orange-500" 
                                           {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-800">Cash on Pickup</div>
                                        <div class="text-sm text-gray-600">Pay when you collect your order</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="online" 
                                           class="text-orange-600 focus:ring-orange-500" 
                                           {{ old('payment_method') === 'online' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-800">Credit/Debit Card</div>
                                        <div class="text-sm text-gray-600">Pay securely online now</div>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cash Payment Info -->
                        <div id="cash-payment-info" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="font-medium text-blue-800">Cash Payment Instructions</h4>
                            </div>
                            <p class="text-sm text-blue-700">
                                Please bring the exact amount (${{ number_format($totalPrice, 2) }}) when collecting your order. 
                                We'll prepare your order and notify you when it's ready for pickup.
                            </p>
                        </div>

                        <!-- Credit Card Form -->
                        <div id="credit-card-form" class="mb-6 space-y-4" style="display: none;">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number" 
                                           placeholder="1234 5678 9012 3456"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    @error('card_number')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                    <input type="text" name="card_expiry" id="card_expiry" 
                                           placeholder="MM/YY"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    @error('card_expiry')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                    <input type="text" name="card_cvv" id="card_cvv" 
                                           placeholder="123"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    @error('card_cvv')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="cardholder_name" class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name</label>
                                    <input type="text" name="cardholder_name" id="cardholder_name" 
                                           placeholder="John Doe"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    @error('cardholder_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-pink-600 text-white font-bold py-4 px-8 rounded-xl hover:from-orange-600 hover:to-pink-700 transition-all duration-200 shadow-lg">
                            <span>Place Order Now</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Payment method toggle
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cashPaymentInfo = document.getElementById('cash-payment-info');
    const creditCardForm = document.getElementById('credit-card-form');
    const submitButton = document.querySelector('button[type="submit"]');
    
    function togglePaymentMethod() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (selectedMethod && selectedMethod.value === 'cash') {
            cashPaymentInfo.style.display = 'block';
            creditCardForm.style.display = 'none';
            submitButton.querySelector('span').textContent = 'Place Order Now';
        } else if (selectedMethod && selectedMethod.value === 'online') {
            cashPaymentInfo.style.display = 'none';
            creditCardForm.style.display = 'block';
            submitButton.querySelector('span').textContent = 'Pay Now & Place Order';
        }
    }
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', togglePaymentMethod);
    });
    
    // Initialize on page load
    togglePaymentMethod();
});
</script>
@endsection