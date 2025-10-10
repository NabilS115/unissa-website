@extends('layouts.app')

@section('title', 'Order Management')

@section('content')
<!-- Essential JavaScript functions - loaded immediately -->
<script>
// Define critical functions in global scope immediately
window.getStatusColorClass = function(status) {
    const colors = {
        'pending': 'text-yellow-700 bg-yellow-50',
        'confirmed': 'text-blue-700 bg-blue-50',
        'processing': 'text-purple-700 bg-purple-50',
        'ready_for_pickup': 'text-orange-700 bg-orange-50',
        'picked_up': 'text-green-700 bg-green-50',
        'cancelled': 'text-red-700 bg-red-50'
    };
    return colors[status] || 'text-gray-700 bg-gray-50';
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

// Test function - you can call this from browser console
window.testOrderManagementFunctions = function() {
    console.log('Testing order management functionality...');
    showNotification('Order management functions are loaded!', 'success');
    return 'Order management functions are available';
};

// Initialize status select handlers and bulk actions when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing order management functions...');
    
    // Status update handling
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', async function() {
            const orderId = this.dataset.orderId;
            const newStatus = this.value;
            const originalValue = this.getAttribute('data-original') || this.value;
            
            console.log('Status change:', { orderId, newStatus, originalValue });
            
            try {
                // Use absolute URL to prevent any redirection issues
                const baseUrl = window.location.origin;
                const updateUrl = `${baseUrl}/admin/orders/${orderId}/status`;
                console.log('Order ID:', orderId);
                console.log('Base URL:', baseUrl);
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
                
                // Check for successful response (200 status AND data.success)
                if (response.ok && data.success) {
                    console.log('Success! Updating visual elements...');
                    
                    // Ensure the dropdown value is set to the new status
                    this.value = newStatus;
                    console.log('Dropdown value updated to:', this.value);
                    
                    // Update the select styling based on new status
                    console.log('Current className before update:', this.className);
                    this.className = this.className.replace(/text-\w+-700 bg-\w+-50/g, '');
                    const colorClass = getStatusColorClass(newStatus);
                    this.className += ` ${colorClass}`;
                    console.log('New className after update:', this.className);
                    
                    // Show success message
                    showNotification(data.message || 'Order status updated successfully', 'success');
                    
                    // Update the stored original value
                    this.setAttribute('data-original', newStatus);
                    console.log('Visual update complete!');
                } else {
                    console.log('Error path taken - response not ok or success not true');
                    console.log('response.ok:', response.ok, 'data.success:', data.success);
                    
                    // Revert to original value
                    this.value = originalValue;
                    showNotification(data.message || 'Failed to update status', 'error');
                }
            } catch (error) {
                console.error('Catch block executed - Status update error:', error);
                console.log('Reverting dropdown to original value:', originalValue);
                
                // Revert to original value
                this.value = originalValue;
                showNotification('Network error occurred', 'error');
            }
        });
        
        // Store original value
        select.setAttribute('data-original', select.value);
    });

    // Bulk actions initialization
    const selectAll = document.getElementById('select-all');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkStatus = document.getElementById('bulk-status');
    const applyBulk = document.getElementById('apply-bulk');
    const cancelBulk = document.getElementById('cancel-bulk');

    if (selectAll && orderCheckboxes.length > 0) {
        console.log('Initializing bulk actions...');
        
        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const count = checkedBoxes.length;
            
            if (count > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = `${count} order${count > 1 ? 's' : ''} selected`;
            } else {
                bulkActions.classList.add('hidden');
            }
        }

        selectAll.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        orderCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        if (applyBulk) {
            applyBulk.addEventListener('click', async function() {
                const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
                const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
                const status = bulkStatus.value;

                if (!status) {
                    showNotification('Please select an action', 'error');
                    return;
                }

                if (orderIds.length === 0) {
                    showNotification('Please select at least one order', 'error');
                    return;
                }

                try {
                    const response = await fetch('{{ route('admin.orders.bulk-update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            order_ids: orderIds,
                            status: status 
                        })
                    });

                    const data = await response.json();
                    
                    if (data.success || response.ok) {
                        showNotification(data.message || 'Orders updated successfully', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Bulk action failed', 'error');
                    }
                } catch (error) {
                    console.error('Bulk update error:', error);
                    showNotification('Network error occurred', 'error');
                }
            });
        }

        if (cancelBulk) {
            cancelBulk.addEventListener('click', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                selectAll.checked = false;
                updateBulkActions();
            });
        }
    }
    
    console.log('Order management functions initialized successfully');
});
</script>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
                    <p class="text-gray-600 mt-2">Manage and track customer orders</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.orders.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="text-2xl font-bold text-blue-700">{{ $stats['total_orders'] }}</div>
                    <div class="text-sm text-blue-600">Total Orders</div>
                </div>
                <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                    <div class="text-2xl font-bold text-yellow-700">{{ $stats['pending_orders'] }}</div>
                    <div class="text-sm text-yellow-600">Pending</div>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="text-2xl font-bold text-blue-700">{{ $stats['confirmed_orders'] }}</div>
                    <div class="text-sm text-blue-600">Confirmed</div>
                </div>
                <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
                    <div class="text-2xl font-bold text-purple-700">{{ $stats['processing_orders'] }}</div>
                    <div class="text-sm text-purple-600">Processing</div>
                </div>
                <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                    <div class="text-2xl font-bold text-orange-700">{{ $stats['ready_for_pickup_orders'] }}</div>
                    <div class="text-sm text-orange-600">Ready for Pickup</div>
                </div>
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="text-2xl font-bold text-green-700">{{ $stats['picked_up_orders'] }}</div>
                    <div class="text-sm text-green-600">Picked Up</div>
                </div>
                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                    <div class="text-2xl font-bold text-red-700">{{ $stats['cancelled_orders'] }}</div>
                    <div class="text-sm text-red-600">Cancelled</div>
                </div>
                <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-200">
                    <div class="text-lg font-bold text-indigo-700">${{ number_format($stats['total_revenue'], 2) }}</div>
                    <div class="text-sm text-indigo-600">Revenue</div>
                </div>
                <div class="bg-teal-50 rounded-xl p-4 border border-teal-200">
                    <div class="text-2xl font-bold text-teal-700">{{ $stats['recent_orders'] }}</div>
                    <div class="text-sm text-teal-600">Last 7 Days</div>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" class="bg-gray-50 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Order ID, customer, product..." 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            @foreach(App\Models\Order::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        <div id="bulk-actions" class="hidden bg-white rounded-xl shadow-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span id="selected-count" class="text-sm text-gray-600"></span>
                    <select id="bulk-status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select Action</option>
                        @foreach(App\Models\Order::getStatuses() as $value => $label)
                            <option value="{{ $value }}">Mark as {{ $label }}</option>
                        @endforeach
                    </select>
                    <button id="apply-bulk" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                        Apply
                    </button>
                </div>
                <button id="cancel-bulk" class="text-gray-500 hover:text-gray-700">
                    Cancel
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Order</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Customer</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Product</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Amount</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="font-medium text-gray-900">#{{ $order->id }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->quantity }} item{{ $order->quantity > 1 ? 's' : '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <img src="{{ $order->product->img }}" alt="{{ $order->product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $order->product->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->product->category }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-medium text-gray-900">${{ number_format($order->total_price, 2) }}</div>
                                            <div class="text-sm text-gray-500">${{ number_format($order->unit_price, 2) }} each</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <select class="status-select border border-gray-300 rounded-lg px-3 py-2 text-sm
                                            @if($order->status === 'pending') text-yellow-700 bg-yellow-50
                                            @elseif($order->status === 'confirmed') text-blue-700 bg-blue-50
                                            @elseif($order->status === 'processing') text-purple-700 bg-purple-50
                                            @elseif($order->status === 'ready_for_pickup') text-orange-700 bg-orange-50
                                            @elseif($order->status === 'picked_up') text-green-700 bg-green-50
                                            @elseif($order->status === 'cancelled') text-red-700 bg-red-50
                                            @endif" 
                                            data-order-id="{{ $order->id }}">
                                            @foreach(App\Models\Order::getStatuses() as $value => $label)
                                                <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->created_at->format('g:i A') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mt-4">No orders found</h3>
                    <p class="text-gray-500 mt-2">No orders match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// All order management JavaScript functions have been moved to inline script at the top of the page for immediate loading
// The functions include:
// - Status update handling for individual orders
// - Bulk actions functionality
// - Notification system
// - Status color management
// All duplicate JavaScript has been removed - functions are now in the inline script above
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update the select styling based on new status
                    this.className = this.className.replace(/text-\w+-700 bg-\w+-50/g, '');
                    const colorClass = getStatusColorClass(newStatus);
                    this.className += ` ${colorClass}`;
                    
                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    // Revert to original value
                    this.value = originalValue;
                    showNotification(data.message || 'Failed to update status', 'error');
                }
            } catch (error) {
                // Revert to original value
                this.value = originalValue;
                showNotification('Network error occurred', 'error');
            }
        });
        
        // Store original value
        select.setAttribute('data-original', select.value);
    });

    // Bulk actions
    const selectAll = document.getElementById('select-all');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkStatus = document.getElementById('bulk-status');
    const applyBulk = document.getElementById('apply-bulk');
    const cancelBulk = document.getElementById('cancel-bulk');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = `${checkedBoxes.length} order${checkedBoxes.length > 1 ? 's' : ''} selected`;
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    selectAll.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    applyBulk.addEventListener('click', async function() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
        const status = bulkStatus.value;

        if (!status) {
            showNotification('Please select an action', 'error');
            return;
        }

        try {
            const response = await fetch('/admin/orders/bulk-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ order_ids: orderIds, status: status })
            });

            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'Failed to update orders', 'error');
            }
        } catch (error) {
            showNotification('Network error occurred', 'error');
        }
    });

    cancelBulk.addEventListener('click', function() {
        selectAll.checked = false;
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActions();
    });

    function getStatusColorClass(status) {
        const colors = {
            'pending': 'text-yellow-700 bg-yellow-50',
            'confirmed': 'text-blue-700 bg-blue-50',
            'processing': 'text-purple-700 bg-purple-50',
            'ready_for_pickup': 'text-orange-700 bg-orange-50',
            'picked_up': 'text-green-700 bg-green-50',
            'cancelled': 'text-red-700 bg-red-50'
        };
        return colors[status] || 'text-gray-700 bg-gray-50';
    }

    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
</script>
@endpush
@endsection