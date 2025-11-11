@extends('layouts.app')

@section('title', 'Order Details - Unissa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.orders.index') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent mb-3">Order Details</h1>
            <p class="text-lg text-gray-600">Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y at g:i A') }}</p>
        </div>

        <!-- Order Status Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Order Status</h2>
                
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'processing' => 'bg-purple-100 text-purple-800 border-purple-200',
                        'ready_for_pickup' => 'bg-orange-100 text-orange-800 border-orange-200',
                        'picked_up' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                    ];
                    
                    $paymentStatusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-purple-100 text-purple-800',
                    ];
                @endphp
                
                <div class="flex gap-3">
                    <span class="px-4 py-2 text-lg font-semibold rounded-2xl border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                        {{ \App\Models\Order::getStatuses()[$order->status] ?? ucfirst($order->status) }}
                    </span>
                    <span class="px-4 py-2 text-sm font-medium rounded-2xl {{ $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                        Payment: {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <!-- Status Progress -->
            <div class="relative">
                @php
                    $statuses = ['pending', 'confirmed', 'processing', 'ready_for_pickup', 'picked_up'];
                    $currentIndex = array_search($order->status, $statuses);
                    $isCancelled = $order->status === 'cancelled';
                @endphp
                
                @if(!$isCancelled)
                    <div class="flex items-center justify-between mb-4">
                        @foreach(['pending', 'confirmed', 'processing', 'ready_for_pickup', 'picked_up'] as $index => $status)
                            <div class="flex flex-col items-center {{ $index <= $currentIndex ? 'text-teal-600' : 'text-gray-400' }}">
                                <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center mb-2 {{ $index <= $currentIndex ? 'bg-teal-600 border-teal-600 text-white' : 'border-gray-300' }}">
                                    @if($index < $currentIndex)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <span class="text-sm font-medium">{{ \App\Models\Order::getStatuses()[$status] ?? ucfirst($status) }}</span>
                            </div>
                            
                            @if($index < 4)
                                <div class="flex-1 h-0.5 mx-2 {{ $index < $currentIndex ? 'bg-teal-600' : 'bg-gray-300' }}"></div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-red-600 mb-2">Order Cancelled</h3>
                        <p class="text-gray-600">This order has been cancelled and any stock has been restored.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Product Details</h2>
            
            <div class="flex items-start gap-8">
                <!-- Product Image -->
                <div class="w-32 h-32 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-3xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if($order->product && $order->product->img)
                        <img src="{{ $order->product->img }}" 
                             alt="{{ $order->product->name }}" 
                             class="w-full h-full object-cover rounded-3xl">
                    @else
                        <svg class="w-16 h-16 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $order->product ? $order->product->name : 'Product Not Found' }}</h3>
                    
                    @if($order->product && $order->product->description)
                        <p class="text-gray-600 mb-4">{{ $order->product->description }}</p>
                    @endif
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Quantity</p>
                            <p class="text-lg font-bold text-gray-800">{{ $order->quantity }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Unit Price</p>
                            <p class="text-lg font-bold text-gray-800">${{ number_format($order->unit_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Total Price</p>
                            <p class="text-2xl font-bold text-teal-600">${{ number_format($order->total_price, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Payment Info -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <!-- Customer Information -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Customer Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Name</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $order->customer_name }}</p>
                    </div>
                    
                    @if($order->customer_email)
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Email</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $order->customer_email }}</p>
                        </div>
                    @endif
                    
                    @if($order->customer_phone)
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Phone</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $order->customer_phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Payment Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Payment Method</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $order->payment_method_display }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Payment Status</p>
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Total Amount</p>
                        <p class="text-2xl font-bold text-teal-600">${{ number_format($order->total_price, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($order->notes || $order->pickup_notes)
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Additional Information</h2>
                
                @if($order->pickup_notes)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">Pickup Notes</p>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-xl">{{ $order->pickup_notes }}</p>
                    </div>
                @endif
                
                @if($order->notes)
                    <div>
                        <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">Order Notes</p>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-xl">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Actions -->
        <div class="text-center">
            
            @if($order->canBeCancelled())
                <form action="{{ route('user.orders.cancel', $order) }}" method="POST" class="inline-block mr-4">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-8 py-4 bg-red-600 text-white font-semibold rounded-2xl hover:bg-red-700 transition-colors shadow-lg hover:shadow-xl"
                            onclick="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Order
                    </button>
                </form>
            @endif
            
            <a href="{{ route('user.orders.index') }}" 
               class="inline-flex items-center gap-2 px-8 py-4 bg-teal-600 text-white font-semibold rounded-2xl hover:bg-teal-700 transition-colors shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>
    </div>
</div>
@endsection