@extends('layouts.app')

@section('title', 'Unissa Cafe - Order Details - #' . $order->id)

@section('content')
<!-- Bootstrap + external admin orders show JS -->
<script>
    window.__adminOrder = {
        csrf: '{{ csrf_token() }}',
        updateStatusUrl: '{{ route('admin.orders.update-status', $order->id) }}',
        updatePaymentUrl: '{{ route('admin.orders.update-payment-status', $order->id) }}',
        orderId: {{ $order->id }}
    };
</script>
<script src="/js/admin-orders-show.js"></script>

<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Order #{{ $order->id }}</h1>
                    <p class="text-gray-500 mt-1 text-sm">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border border-gray-200 shadow-sm
                    @if($order->status === 'pending') bg-yellow-50 text-yellow-800
                    @elseif($order->status === 'confirmed') bg-blue-50 text-blue-800
                    @elseif($order->status === 'processing') bg-purple-50 text-purple-800
                    @elseif($order->status === 'ready_for_pickup') bg-orange-50 text-orange-800
                    @elseif($order->status === 'picked_up') bg-green-50 text-green-800
                    @elseif($order->status === 'cancelled') bg-red-50 text-red-800
                    @endif">
                    {{ str_replace('_', ' ', ucwords($order->status, '_')) }}
                </span>
            </div>
        </div>
        <!-- Quick Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-medium text-gray-700">Quick Actions:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach(App\Models\Order::getStatuses() as $status => $label)
                        @if($status !== $order->status)
                            <button onclick="updateOrderStatus('{{ $status }}')"
                                class="px-3 py-1 text-xs font-semibold rounded-lg border transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2
                                    @if($status === 'pending') border-yellow-300 text-yellow-700 hover:bg-yellow-50
                                    @elseif($status === 'confirmed') border-blue-300 text-blue-700 hover:bg-blue-50
                                    @elseif($status === 'processing') border-purple-300 text-purple-700 hover:bg-purple-50
                                    @elseif($status === 'ready_for_pickup') border-orange-300 text-orange-700 hover:bg-orange-50
                                    @elseif($status === 'picked_up') border-green-300 text-green-700 hover:bg-green-50
                                    @elseif($status === 'cancelled') border-red-300 text-red-700 hover:bg-red-50
                                    @endif">
                                Mark as {{ $label }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="text-xs text-gray-400 mt-2 md:mt-0">
                Last updated: {{ $order->updated_at->format('M d, Y g:i A') }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Product Information -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Product Details</h2>
                    <div class="flex items-start gap-6">
                        <img src="{{ $order->product->img }}" alt="{{ $order->product->name }}" 
                             class="w-24 h-24 rounded-xl object-cover border border-gray-200">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $order->product->name }}</h3>
                            <p class="text-gray-600 mt-1">{{ $order->product->category }}</p>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Quantity Ordered:</span>
                                    <p class="font-medium">{{ $order->quantity }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Unit Price:</span>
                                    <p class="font-medium">${{ number_format($order->unit_price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->product->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-2">Product Description</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $order->product->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Special Instructions -->
                @if($order->special_instructions)
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Special Instructions</h2>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-gray-700">{{ $order->special_instructions }}</p>
                        </div>
                    </div>
                @endif

                <!-- Order Timeline -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <h4 class="font-medium text-gray-900">Order Placed</h4>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($order->status !== 'pending')
                            <div class="flex items-start gap-4">
                                <div class="w-3 h-3 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Status: {{ ucfirst($order->status) }}</h4>
                                    <p class="text-sm text-gray-500">Last updated: {{ $order->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Customer Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Customer Information</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500">Name:</span>
                            <p class="font-medium">{{ $order->customer_name }}</p>
                            @if($order->user)
                                <p class="text-xs text-gray-500">Registered user since {{ $order->user->created_at->format('M Y') }}</p>
                            @else
                                <p class="text-xs text-gray-500">Guest customer</p>
                            @endif
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Email:</span>
                            <p class="font-medium">
                                <a href="mailto:{{ $order->customer_email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $order->customer_email }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Phone:</span>
                            <p class="font-medium">
                                <a href="tel:{{ $order->customer_phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $order->customer_phone }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Payment Method:</span>
                            <div class="flex items-center mt-1">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->payment_method === 'cash') text-green-700 bg-green-50
                                    @elseif($order->payment_method === 'bank_transfer') text-blue-700 bg-blue-50
                                    @else text-purple-700 bg-purple-50
                                    @endif">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        @if($order->payment_method === 'cash')
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                        @elseif($order->payment_method === 'bank_transfer')
                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                        @else
                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                                        @endif
                                    </svg>
                                    {{ $order->payment_method_display }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Payment Status: </span>
                            <select id="payment-status-select" 
                                    onchange="updatePaymentStatus()" 
                                    class="payment-status-select border border-gray-200 rounded px-2 py-1 text-xs font-medium
                                        @if($order->payment_status === 'paid') text-green-700 bg-green-100
                                        @elseif($order->payment_status === 'pending') text-yellow-700 bg-yellow-100
                                        @elseif($order->payment_status === 'refunded') text-purple-700 bg-purple-100
                                        @else text-red-700 bg-red-100
                                        @endif"
                                    data-order-id="{{ $order->id }}"
                                    data-current-status="{{ $order->payment_status }}">
                                @foreach(App\Models\Order::getPaymentStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ $order->payment_status == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($order->payment_method === 'cash' && $order->payment_status === 'pending')
                            <p class="text-xs text-gray-500 mt-1">Payment due on pickup</p>
                        @endif
                        @if($order->notes)
                            <div>
                                <span class="text-sm text-gray-500">Order Notes:</span>
                                <p class="font-medium text-sm">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">${{ number_format($order->unit_price * $order->quantity, 2) }}</span>
                        </div>
                        @if($order->delivery_fee > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Fee:</span>
                                <span class="font-medium">${{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                        @endif
                        @if($order->tax_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax:</span>
                                <span class="font-medium">${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        <hr class="border-gray-200">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900">Total:</span>
                            <span class="text-gray-900">${{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Management -->
                @if($order->payment_method === 'cash')
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Management</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">Current Status:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->payment_status === 'paid') text-green-700 bg-green-100
                                    @elseif($order->payment_status === 'pending') text-yellow-700 bg-yellow-100
                                    @else text-red-700 bg-red-100
                                    @endif">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            @if($order->payment_status === 'pending')
                                <p class="text-xs text-gray-500">Customer will pay on pickup</p>
                            @endif
                        </div>
                        
                        @if($order->payment_status !== 'paid')
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Update Payment Status:</label>
                            <select id="payment-status-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                @foreach(App\Models\Order::getPaymentStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ $order->payment_status == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button onclick="updatePaymentStatus()" 
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                Update Payment Status
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button onclick="window.print()" class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors">
                            Print Order
                        </button>
                        <a href="mailto:{{ $order->customer_email }}?subject=Order #{{ $order->id }} Update" 
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center block">
                            Email Customer
                        </a>
                        @if($order->status !== 'cancelled')
                            <button onclick="updateOrderStatus('cancelled')" 
                                    class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors">
                                Cancel Order
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .bg-gray-50,
    .shadow-lg,
    .rounded-2xl {
        background: white !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    
    button,
    .bg-gray-50.rounded-xl,
    .text-blue-600 {
        display: none !important;
    }
}
</style>
@endsection