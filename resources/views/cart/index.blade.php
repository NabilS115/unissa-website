@extends('layouts.app')

@section('title', 'Shopping Cart - Unissa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-yellow-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Your Shopping Cart</h1>
            <p class="text-gray-600">Review your items and proceed to checkout</p>
        </div>

        @if($cartItems->isEmpty())
            <!-- Empty Cart -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13h10m-10 0v6a2 2 0 002 2h6a2 2 0 002-2v-6"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('unissa-cafe.catalog') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-orange-500 to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:from-orange-600 hover:to-pink-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    Start Shopping
                </a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Cart Items ({{ $cartItems->count() }})</h2>
                        
                        <div class="space-y-6">
                            @foreach($cartItems as $item)
                            <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:shadow-md transition-shadow">
                                <!-- Product Image -->
                                <div class="w-20 h-20 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item->product->img) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover rounded-lg">
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                    <p class="text-sm text-gray-600 capitalize">{{ $item->product->category }}</p>
                                    <p class="text-sm text-orange-600 font-medium">${{ number_format($item->product->price, 2) }} each</p>
                                </div>
                                
                                <!-- Quantity Controls -->
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="updateQuantity({{ $item->id }}, -1)" 
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                               min="1" max="100" 
                                               class="w-16 text-center border border-gray-200 rounded-lg py-1"
                                               onchange="this.form.submit()">
                                        
                                        <button type="button" onclick="updateQuantity({{ $item->id }}, 1)" 
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Item Total -->
                                <div class="text-right">
                                    <p class="font-semibold text-gray-800">${{ number_format($item->total_price, 2) }}</p>
                                </div>
                                
                                <!-- Remove Button -->
                                <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Remove this item from cart?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Clear Cart -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire cart?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                    Clear All Items
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Order Summary</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                                <span class="font-medium">${{ number_format($totalPrice, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-green-600">Free</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-xl font-semibold text-gray-800">Total</span>
                                    <span class="text-xl font-bold text-orange-600">${{ number_format($totalPrice, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <a href="{{ route('checkout.cart') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-gradient-to-r from-orange-500 to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:from-orange-600 hover:to-pink-700 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Proceed to Checkout
                        </a>
                        
                        <!-- Continue Shopping -->
                        <a href="{{ route('unissa-cafe.catalog') }}" 
                           class="w-full mt-4 inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-200 text-gray-700 font-medium rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function updateQuantity(itemId, change) {
    const quantityInput = document.querySelector(`input[name="quantity"][form*="${itemId}"]`);
    if (!quantityInput) return;
    
    let newQuantity = parseInt(quantityInput.value) + change;
    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > 100) newQuantity = 100;
    
    quantityInput.value = newQuantity;
    quantityInput.form.submit();
}
</script>
@endsection