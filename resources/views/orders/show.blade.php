@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200 transition-all duration-200 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to My Orders
            </a>
        </div>

        <!-- Order Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600">Placed on {{ $order->created_at->format('l, F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    <div class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($order->total_price, 2) }}</div>
                </div>
            </div>

            @if($order->canBeCancelled())
                <div class="flex justify-end">
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel Order
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Information -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Product Details
                </h2>

                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <img src="{{ $order->product->img }}" alt="{{ $order->product->name }}" 
                             class="w-24 h-24 object-cover rounded-xl border-2 border-gray-200 shadow-sm">
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $order->product->name }}</h3>
                        <p class="text-gray-600 mb-3">{{ $order->product->desc }}</p>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $order->product->category }}
                            </span>
                            <span class="inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                {{ ucfirst($order->product->type) }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Unit Price:</span>
                                <span class="font-semibold">${{ number_format($order->unit_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Quantity:</span>
                                <span class="font-semibold">{{ $order->quantity }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t">
                                <span>Total:</span>
                                <span>${{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('product.detail', $order->product) }}" 
                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Product Details
                    </a>
                </div>
            </div>

            <!-- Customer & Delivery Information -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Customer Information
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-gray-900">{{ $order->customer_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-gray-900">{{ $order->customer_email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <p class="text-gray-900">{{ $order->customer_phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Payment Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <div class="flex items-center gap-2">
                                @if($order->payment_method === 'cash')
                                    <div class="flex items-center gap-2 px-3 py-2 bg-green-50 text-green-800 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        <span class="font-medium">Cash Payment</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-800 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span class="font-medium">Online Payment</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                            <span class="inline-block px-3 py-2 text-sm font-semibold rounded-lg
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    @if($order->payment_method === 'cash')
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-amber-800 mb-1">Cash Payment Instructions</h4>
                                    <p class="text-sm text-amber-700">Payment will be collected when you pick up your order. Please bring exact change when possible.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($order->payment_method === 'online' && $order->payment_status === 'paid')
                        <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-green-800 mb-1">Payment Confirmed</h4>
                                    <p class="text-sm text-green-700">Your online payment has been successfully processed. No additional payment required at pickup.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($order->payment_method === 'online' && $order->payment_status === 'pending')
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-yellow-800 mb-1">Payment Processing</h4>
                                    <p class="text-sm text-yellow-700">Your online payment is being processed. You'll receive a confirmation once payment is complete.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($order->payment_method === 'online' && $order->payment_status === 'failed')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 11.414l1.293-1.293a1 1 0 00-1.414-1.414z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-medium text-red-800 mb-1">Payment Failed</h4>
                                    <p class="text-sm text-red-700">Your online payment could not be processed. Please contact us to arrange alternative payment.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Pickup Information
                    </h2>

                    <div class="space-y-4">
                        <!-- Pickup Location -->
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <h3 class="font-semibold text-green-800 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Pickup Location
                            </h3>
                            <p class="text-green-700 text-sm">
                                <strong>Unissa Café</strong><br>
                                123 Main Street<br>
                                City Center, State 12345<br>
                                <span class="text-green-600 font-medium">📞 Phone: (555) 123-4567</span>
                            </p>
                        </div>

                        @if($order->pickup_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pickup Notes</label>
                                <p class="text-gray-900 whitespace-pre-line">{{ $order->pickup_notes }}</p>
                            </div>
                        @endif
                        @if($order->notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                                <p class="text-gray-900 whitespace-pre-line">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Information -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Order Status Information
            </h2>

            <div class="bg-gray-50 rounded-xl p-6">
                @if($order->status === 'pending')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Order Pending</h3>
                            <p class="text-gray-600">Your order has been received and is awaiting confirmation. You will receive an email update soon.</p>
                        </div>
                    </div>
                @elseif($order->status === 'confirmed')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Order Confirmed</h3>
                            <p class="text-gray-600">Your order has been confirmed and will be processed soon. Thank you for your order!</p>
                        </div>
                    </div>
                @elseif($order->status === 'processing')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Order Processing</h3>
                            <p class="text-gray-600">Your order is currently being prepared for delivery. We'll notify you when it's ready to ship.</p>
                        </div>
                    </div>
                @elseif($order->status === 'completed')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Order Completed</h3>
                            <p class="text-gray-600">Your order has been successfully delivered! Thank you for your business.</p>
                        </div>
                    </div>
                @elseif($order->status === 'cancelled')
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Order Cancelled</h3>
                            <p class="text-gray-600">This order has been cancelled. If you have any questions, please contact our support team.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <strong>Order ID:</strong> {{ $order->id }} • 
                    <strong>Last Updated:</strong> {{ $order->updated_at->format('M d, Y \a\t g:i A') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection