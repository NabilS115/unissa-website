@extends('layouts.app')

@section('title', 'UNISSA Cafe - My Orders')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent mb-3">My Orders</h1>
            <p class="text-lg text-gray-600">Track your order history and status</p>
        </div>

        @if($orders->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-16 text-center">
                <div class="w-32 h-32 mx-auto mb-8 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">No orders yet</h2>
                <p class="text-lg text-gray-600 mb-10 max-w-md mx-auto">Start exploring our amazing products and place your first order!</p>
                <a href="{{ route('unissa-cafe.catalog') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-teal-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Start Shopping
                </a>
            </div>
        @else
            <!-- Orders List -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Order History <span class="text-2xl text-teal-600">({{ $orders->total() }} total)</span></h2>
                    <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Your order history
                    </div>
                </div>
                
                <div class="space-y-6">
                    @foreach($orders as $order)
                    <div class="group relative bg-gradient-to-r from-gray-50 to-white p-6 border border-gray-200 rounded-2xl hover:shadow-lg hover:border-teal-200 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-6 flex-1">
                                <!-- Product Image -->
                                <div class="w-20 h-20 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    @if($order->product && $order->product->img)
                                        <img src="{{ $order->product->img }}" 
                                             alt="{{ $order->product->name }}" 
                                             class="w-full h-full object-cover rounded-2xl" loading="lazy" decoding="async"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI2Y0ZjRmNSIvPgo8cGF0aCBkPSJNMTYgMTFWN2E0IDQgMCAwMC04IDB2NE01IDloMTRsMSAxMkg0TDUgOXoiIHN0cm9rZT0iIzljYTNhZiIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+'; this.classList.add('opacity-50');">
                                    @else
                                        <svg class="w-10 h-10 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    @endif
                                </div>
                                
                                <!-- Order Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $order->product ? $order->product->name : 'Product Not Found' }}</h3>
                                            <p class="text-sm text-gray-500 mb-2">Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y at g:i A') }}</p>
                                        </div>
                                        
                                        <!-- Order Status -->
                                        <div class="flex flex-col items-end gap-2">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'confirmed' => 'bg-teal-100 text-teal-800 border-teal-200',
                                                    'processing' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'ready_for_pickup' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                    'picked_up' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                                ];
                                                
                                                $paymentStatusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'failed' => 'bg-red-100 text-red-800',
                                                    'refunded' => 'bg-gray-100 text-gray-800',
                                                ];
                                            @endphp
                                            
                                            <span class="flex items-center gap-2">
                                                <span class="px-3 py-1 text-sm font-medium rounded-full border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                                    <span class="font-semibold">Order Status:</span> {{ \App\Models\Order::getStatuses()[$order->status] ?? ucfirst($order->status) }}
                                                </span>
                                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    <span class="font-semibold">Payment Status:</span> {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Info -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Quantity</p>
                                            <p class="text-sm font-semibold text-gray-800">{{ $order->quantity }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Unit Price</p>
                                            <p class="text-sm font-semibold text-gray-800">B${{ number_format($order->unit_price, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                                            <p class="text-lg font-bold text-teal-600">B${{ number_format($order->total_price, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Payment</p>
                                            <p class="text-sm font-semibold text-gray-800">{{ $order->payment_method_display }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Customer Info (if different from user) -->
                                    @if($order->customer_name)
                                        <div class="text-sm text-gray-600 mb-4">
                                            <strong>Customer:</strong> {{ $order->customer_name }}
                                            @if($order->customer_phone)
                                                • {{ $order->customer_phone }}
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('user.orders.show', $order) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white font-medium rounded-xl hover:bg-teal-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Details
                                        </a>
                                        
                                        @if($order->canBeCancelled())
                                            <form action="{{ route('user.orders.cancel', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors"
                                                        onclick="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Cancel Order
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection