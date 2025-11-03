@extends('layouts.app')

@section('title', 'Shopping Cart - Unissa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Clean Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-emerald-600 mb-4 leading-tight">Your Shopping Cart</h1>
            <p class="text-lg text-gray-600">Review your selected items and proceed to secure checkout</p>
        </div>

        @if($cartItems->isEmpty())
            <!-- Elegant Empty Cart -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-16 text-center">
                <!-- Empty cart SVG icon removed as requested -->
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Your cart is empty</h2>
                <p class="text-lg text-gray-600 mb-10 max-w-md mx-auto">Discover our amazing products and start building your perfect order</p>
                <a href="{{ route('unissa-cafe.catalog') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Start Shopping
                </a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-10">
                <!-- Modern Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-3xl font-bold text-gray-800">Cart Items <span class="text-2xl text-teal-600">({{ $cartItems->count() }})</span></h2>
                            <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Secure checkout
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach($cartItems as $item)
                            <div class="group relative bg-gradient-to-r from-gray-50 to-white p-6 border border-gray-200 rounded-2xl hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                                <!-- Product Image -->
                                <div class="flex items-start gap-6">
                                    @php
                                        $imgPath = $item->product->img;
                                        $isStorage = $imgPath && (str_starts_with($imgPath, 'products/') || str_starts_with($imgPath, 'catalog/') || str_starts_with($imgPath, 'gallery/'));
                                        $validImage = false;
                                        if ($imgPath && $isStorage && \Illuminate\Support\Facades\Storage::disk('public')->exists($imgPath)) {
                                            $ext = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
                                            $validImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                        } elseif ($imgPath && !$isStorage) {
                                            $ext = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
                                            $validImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                        }
                                    @endphp
                                    @if(($imgPath && $isStorage && $validImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($imgPath)))
                                        <img src="{{ Storage::url($imgPath) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-24 h-24 object-cover group-hover:scale-105 transition-transform duration-300 bg-white rounded-xl"
                                             style="background-color: #fff;">
                                    @elseif($imgPath && !$isStorage && $validImage)
                                        <img src="{{ asset($imgPath) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-24 h-24 object-cover group-hover:scale-105 transition-transform duration-300 bg-white rounded-xl"
                                             style="background-color: #fff;">
                                    @else
                                        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='96' height='96' viewBox='0 0 96 96'><rect width='96' height='96' rx='16' fill='%23f3f4f6'/><text x='50%' y='54%' text-anchor='middle' fill='%239ca3af' font-size='18' font-family='Arial' dy='.3em'>No Image</text></svg>" alt="No Image" class="w-24 h-24 object-contain opacity-80 bg-white rounded-xl" />
                                    @endif
                                    
                                    <!-- Product Details -->
                                    <div class="flex-grow min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                            <div class="flex-grow">
                                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->product->name }}</h3>
                                                <div class="flex items-center gap-3 mb-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800 capitalize">
                                                        {{ $item->product->category }}
                                                    </span>
                                                    <span class="text-lg font-semibold text-teal-600">${{ number_format($item->product->price, 2) }}</span>
                                                </div>
                                                
                                                <!-- Mobile quantity controls -->
                                                <div class="sm:hidden">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            <form id="cart-form-mobile-{{ $item->id }}" action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="button" onclick="updateQuantity('mobile-{{ $item->id }}', -1)" 
                                                                        class="w-10 h-10 flex items-center justify-center bg-teal-100 hover:bg-teal-200 text-teal-700 rounded-xl transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                                    </svg>
                                                                </button>
                                                                
                                                                <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                                       min="1" max="100" 
                                                                       class="w-20 text-center text-lg font-semibold border-2 border-gray-200 rounded-xl py-2 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 appearance-none"
                                                                       style="-webkit-appearance: none; -moz-appearance: textfield;"
                                                                       onchange="this.form.submit()">
                                                                
                                                                <button type="button" onclick="updateQuantity('mobile-{{ $item->id }}', 1)" 
                                                                        class="w-10 h-10 flex items-center justify-center bg-teal-100 hover:bg-teal-200 text-teal-700 rounded-xl transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="text-2xl font-bold text-gray-800" data-item-total>${{ number_format($item->total_price, 2) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                            <!-- Desktop Quantity Controls & Price -->
                            <div class="hidden sm:flex items-center gap-6">
                                <div class="flex items-center gap-3">
                                    <form id="cart-form-desktop-{{ $item->id }}" action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="updateQuantity('desktop-{{ $item->id }}', -1)" 
                                                class="w-10 h-10 flex items-center justify-center bg-teal-100 hover:bg-teal-200 text-teal-700 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                               min="1" max="100" 
                                               class="w-20 text-center text-lg font-semibold border-2 border-gray-200 rounded-xl py-2 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 appearance-none"
                                               style="-webkit-appearance: none; -moz-appearance: textfield;"
                                               onchange="this.form.submit()">
                                        
                                        <button type="button" onclick="updateQuantity('desktop-{{ $item->id }}', 1)" 
                                                class="w-10 h-10 flex items-center justify-center bg-teal-100 hover:bg-teal-200 text-teal-700 rounded-xl transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>                                                <div class="text-right min-w-0">
                                                    <p class="text-2xl font-bold text-gray-800" data-item-total>${{ number_format($item->total_price, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Remove this item from cart?')" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center text-red-500 hover:text-white hover:bg-red-500 rounded-xl transition-all duration-200 group">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Clear Cart -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <form action="{{ route('user.cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire cart?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-red-600 hover:text-red-700 hover:bg-red-50 font-medium rounded-xl transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Clear All Items
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Enhanced Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sticky top-8">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-800 mb-2">Order Summary</h2>
                            <p class="text-gray-600">Ready to checkout?</p>
                        </div>
                        
                        <div class="space-y-6 mb-8">
                            <div class="bg-gradient-to-r from-teal-50 to-emerald-50 rounded-2xl p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-gray-700 font-medium">Subtotal</span>
                                    <span class="text-xl font-bold text-gray-800" data-subtotal>${{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Items</span>
                                    <span class="text-teal-600 font-semibold" data-item-count>{{ $cartItems->sum('quantity') }} items</span>
                                </div>
                            </div>
                            <!-- Removed floating white circle (decorative div with svg) here -->
                            <div class="border-t-2 border-dashed border-gray-300 pt-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-gray-800">Total</span>
                                    <span class="text-3xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent" data-subtotal>${{ number_format($totalPrice, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Enhanced Checkout Button -->
                        <a href="{{ route('checkout.cart') }}" 
                           class="w-full inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-bold text-lg rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Proceed to Checkout
                        </a>
                        
                        <!-- Continue Shopping -->
                        <a href="{{ route('unissa-cafe.catalog') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-teal-200 text-teal-700 font-semibold rounded-2xl hover:border-teal-300 hover:bg-teal-50 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            Continue Shopping
                        </a>
                        
                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center gap-4 text-sm text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <span>Secure Payment</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                    </svg>
                                    <span>Quality Guarantee</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Hide number input spinners completely */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
    appearance: none;
}

/* Mobile layout optimizations for cart page */
@media (max-width: 768px) {
    /* Cart container spacing */
    .max-w-6xl {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        margin: 0 !important;
    }
    
    /* Cart item cards mobile optimization */
    .cart-item-card {
        padding: 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    /* Product image sizing */
    .w-24.h-24 {
        width: 80px !important;
        height: 80px !important;
        flex-shrink: 0;
    }
    
    /* Mobile quantity controls */
    .quantity-controls {
        gap: 0.5rem !important;
    }
    
    .quantity-btn {
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        min-height: 40px !important;
    }
    
    .quantity-input {
        width: 60px !important;
        padding: 0.5rem !important;
        font-size: 1rem !important;
    }
    
    /* Cart summary mobile fixes */
    .cart-summary {
        padding: 1rem !important;
        margin: 1rem 0 !important;
    }
    
    /* Button sizing for mobile */
    .checkout-btn, .continue-shopping-btn {
        width: 100% !important;
        padding: 1rem !important;
        font-size: 1rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Price display mobile optimization */
    .price-display {
        font-size: 1.25rem !important;
    }
    
    /* Remove desktop-only elements */
    .hidden.sm\\:flex {
        display: none !important;
    }
    
    /* Mobile-specific layout adjustments */
    .flex-col.gap-4 {
        gap: 1rem !important;
    }
    
    /* Cart header mobile spacing */
    .cart-header {
        padding: 1rem !important;
        margin-bottom: 1rem !important;
    }
}

</style>

<script src="/js/cart.js"></script>
@endsection