@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                    <p class="text-gray-600 mt-2">Track and manage your product orders</p>
                </div>
                <a href="{{ route('unissa-cafe.homepage') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    Continue Shopping
                </a>
            </div>
        </div>

        @if($orders->count() > 0)
            <!-- Orders List -->
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Order #{{ $order->id }}</h3>
                                    <p class="text-gray-600 text-sm">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 mb-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ $order->product->img }}" alt="{{ $order->product->name }}" 
                                         class="w-16 h-16 object-cover rounded-xl border-2 border-gray-200">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $order->product->name }}</h4>
                                    <p class="text-gray-600 text-sm">{{ $order->product->category }}</p>
                                    <div class="flex items-center gap-4 mt-1 text-sm text-gray-500">
                                        <span>Quantity: {{ $order->quantity }}</span>
                                        <span>•</span>
                                        <span>Unit Price: ${{ number_format($order->unit_price, 2) }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">${{ number_format($order->total_price, 2) }}</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">Pickup at:</span> 
                                    Unissa Café - {{ $order->pickup_notes ? Str::limit($order->pickup_notes, 40) : 'Standard pickup' }}
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($order->canBeCancelled())
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Cancel Order
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="inline-flex items-center gap-1 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="mb-6">
                    <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Orders Yet</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">You haven't placed any orders yet. Explore our catalog and find something you love!</p>
                <a href="{{ route('unissa-cafe.homepage') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13l-1.5-1.5M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
@endsection