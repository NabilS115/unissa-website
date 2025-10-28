@extends('layouts.app')

@section('title', 'Checkout - Unissa Cafe')

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
                                        <p class="text-sm text-teal-600 font-medium">{{ $item->quantity }}x ${{ number_format($item->product->price, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-teal-700">${{ number_format($item->total_price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl p-4 mb-6 border border-teal-100">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 font-medium">Subtotal ({{ $totalItems }} items)</span>
                                    <span class="font-semibold text-gray-800">${{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="border-t border-teal-200 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-gray-800">Total</span>
                                        <span class="text-2xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">${{ number_format($totalPrice, 2) }}</span>
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
                                        <div class="font-semibold text-gray-800">Bank Transfer</div>
                                        <div class="text-sm text-gray-600">Pay via local bank transfer</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Bank Transfer Fields -->
                            <div id="bank-transfer-fields" class="mt-4 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 border-2 border-teal-200 rounded-xl" style="display:none;">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="bank_name" class="block text-sm font-semibold text-[#0d9488] mb-2">Bank Name</label>
                                        <select name="bank_name" id="bank_name" class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] bg-white">
                                            <option value="">Select a bank</option>
                                            <option value="BIBD" {{ old('bank_name', Auth::user()->bank_name) == 'BIBD' ? 'selected' : '' }}>BIBD</option>
                                            <option value="Baiduri" {{ old('bank_name', Auth::user()->bank_name) == 'Baiduri' ? 'selected' : '' }}>Baiduri</option>
                                            <option value="Standard Chartered" {{ old('bank_name', Auth::user()->bank_name) == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                            <option value="TAIB" {{ old('bank_name', Auth::user()->bank_name) == 'TAIB' ? 'selected' : '' }}>TAIB</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="bank_account" class="block text-sm font-semibold text-[#0d9488] mb-2">Account Number</label>
                                        <input name="bank_account" id="bank_account" type="text" autocomplete="off" value="{{ old('bank_account', Auth::user()->bank_account) }}" class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Account number" maxlength="16" pattern="^[0-9]{16}$" oninput="validateBankAccount(this)" />
                                        <p id="bank-account-validation" class="mt-2 text-sm"></p>
                                    </div>
                                </div>

                                <label for="bank_reference" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Reference</label>
                                <input name="bank_reference" id="bank_reference" type="text" autocomplete="off" value="{{ old('bank_reference', Auth::user()->bank_reference) }}" class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Reference (optional)" />
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cash Payment Info -->
                        <div id="cash-payment-info" class="mb-6 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 border-2 border-teal-200 rounded-xl">
                            <p class="text-sm text-teal-700">Please bring the exact amount (${{ number_format($totalPrice, 2) }}) when collecting your order. We'll prepare your order and notify you when it's ready for pickup.</p>
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