@extends('layouts.app')

@section('title', 'Order Details - #' . $order->id)

@section('content')
<!-- Essential JavaScript functions - loaded immediately -->
<script>
// Define status update functions in global scope immediately
window.updateOrderStatus = async function(newStatus) {
    console.log('updateOrderStatus called with status:', newStatus);
    
    if (newStatus === 'cancelled' && !confirm('Are you sure you want to cancel this order?')) {
        console.log('Cancel confirmation declined');
        return;
    }

    console.log('Proceeding with status update...');
    
    try {
        const updateUrl = '{{ route('admin.orders.update-status', $order->id) }}';
        console.log('Order ID:', {{ $order->id }});
        console.log('Update URL:', updateUrl);
        console.log('About to make PATCH request...');
        
        const response = await fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ 
                status: newStatus,
                _method: 'PATCH'
            })
        });

        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        const data = await response.json();
        console.log('Response data:', data);
        console.log('data.success:', data.success);
        
        if (response.ok && data.success) {
            showNotification(data.message || 'Order status updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to update status', 'error');
        }
    } catch (error) {
        console.error('Status update error:', error);
        showNotification('Network error occurred', 'error');
    }
};

window.showNotification = function(message, type) {
    console.log('showNotification:', { message, type });
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
};

// Payment status update function
window.updatePaymentStatus = async function() {
    const paymentStatusSelect = document.getElementById('payment-status-select');
    const newPaymentStatus = paymentStatusSelect.value;
    
    console.log('updatePaymentStatus called with status:', newPaymentStatus);
    
    try {
        const updateUrl = '{{ route('admin.orders.update-payment-status', $order->id) }}';
        console.log('Payment status update URL:', updateUrl);
        
        const response = await fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ 
                payment_status: newPaymentStatus,
                _method: 'PATCH'
            })
        });

        console.log('Payment status response status:', response.status);
        const data = await response.json();
        console.log('Payment status response data:', data);
        
        if (response.ok && data.success) {
            showNotification(data.message || 'Payment status updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to update payment status', 'error');
        }
    } catch (error) {
        console.error('Payment status update error:', error);
        showNotification('Network error occurred', 'error');
    }
};

// Test function - you can call this from browser console
window.testOrderStatusFunctions = function() {
    console.log('Testing order status functionality...');
    showNotification('Order status functions are loaded!', 'success');
    return 'Order status functions are available';
};
</script>

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
                            <span class="text-sm text-gray-500">Pickup Notes:</span>
                            <p class="font-medium text-sm">{{ $order->pickup_notes ?: 'No special pickup instructions' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Payment Method:</span>
                            <div class="flex items-center mt-1">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->payment_method === 'cash') text-green-700 bg-green-50
                                    @else text-blue-700 bg-blue-50
                                    @endif">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        @if($order->payment_method === 'cash')
                                            <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 2v3H9V6h7z"/>
                                            <path d="M7 10v3H4v-3h3z"/>
                                        @else
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        @endif
                                    </svg>
                                    {{ ucfirst($order->payment_method) }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Payment Status:</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->payment_status === 'paid') text-green-700 bg-green-100
                                    @elseif($order->payment_status === 'pending') text-yellow-700 bg-yellow-100
                                    @else text-red-700 bg-red-100
                                    @endif">
                                    @if($order->payment_status === 'paid')
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($order->payment_status === 'pending')
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                                @if($order->payment_method === 'cash' && $order->payment_status === 'pending')
                                    <p class="text-xs text-gray-500 mt-1">Payment due on pickup</p>
                                @endif
                            </div>
                        </div>
                        @if($order->notes)
                            <div>
                                <span class="text-sm text-gray-500">Order Notes:</span>
                                <p class="font-medium text-sm">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    @if($order->user)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="text-sm text-gray-500">Registered User:</span>
                            <p class="font-medium">{{ $order->user->name }}</p>
                            <p class="text-sm text-gray-500">Member since {{ $order->user->created_at->format('M Y') }}</p>
                        </div>
                    @endif
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

@push('scripts')
<script>
// Order status functions have been moved to inline script at the top of the page for immediate loading
</script>
@endpush

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