@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<!-- Essential JavaScript functions - loaded immediately -->
<script>
// Define critical functions in global scope immediately
window.testJS = function() {
    alert('JavaScript is working! Delete function should work now.');
};

window.deleteProductSimple = function(productId) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        const form = document.querySelector('.delete-form-' + productId);
        if (form) {
            form.submit();
        } else {
            alert('Could not find delete form. Please refresh the page and try again.');
        }
    }
};

window.deleteProduct = function(productId) {
    console.log('Delete function called with productId:', productId);
    
    if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        console.log('Delete cancelled by user');
        return;
    }
    
    console.log('Delete confirmed, proceeding...');
    
    // Get CSRF token with fallback
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
    
    if (!csrfToken) {
        console.error('CSRF token not found!');
        alert('Security token not found. Please refresh the page and try again.');
        return;
    }
    
    console.log('CSRF token found:', csrfToken ? 'Yes' : 'No');
    
    // Show loading state
    const deleteButton = document.querySelector('.delete-btn-' + productId);
    const row = deleteButton ? deleteButton.closest('tr') : null;
    
    console.log('Delete button found:', deleteButton ? 'Yes' : 'No');
    console.log('Row found:', row ? 'Yes' : 'No');
    
    if (deleteButton) {
        deleteButton.disabled = true;
        deleteButton.textContent = 'Deleting...';
        deleteButton.classList.add('delete-loading');
    }
    
    if (row) {
        row.classList.add('row-deleting');
    }
    
    console.log('Sending delete request to:', '/admin/products/' + productId);
    
    // Use fetch for the delete request
    fetch('/admin/products/' + productId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response received:', response.status, response.statusText);
        if (response.ok) {
            return response.json();
        }
        throw new Error('HTTP ' + response.status + ': ' + response.statusText);
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert(data.message || 'Product deleted successfully!');
            
            // Animate row removal
            if (row) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100%)';
                setTimeout(function() {
                    row.remove();
                    // Check if this was the last row
                    const remainingRows = document.querySelectorAll('tbody tr').length;
                    if (remainingRows === 0) {
                        location.reload();
                    }
                }, 500);
            } else {
                location.reload();
            }
        } else {
            throw new Error(data.message || 'Failed to delete product');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert(error.message || 'Failed to delete product');
        
        // If AJAX fails, fallback to form submission
        console.log('AJAX failed, trying form submission fallback...');
        const form = document.querySelector('.delete-form-' + productId);
        if (form && confirm('AJAX failed. Try traditional form submission?')) {
            form.submit();
            return;
        }
        
        // Reset button state
        if (deleteButton) {
            deleteButton.disabled = false;
            deleteButton.textContent = 'Delete';
            deleteButton.classList.remove('delete-loading');
        }
        
        if (row) {
            row.classList.remove('row-deleting');
        }
    });
};

// Update product status function
window.updateProductStatus = async function(productId, newStatus) {
    try {
        const response = await fetch(`/admin/products/${productId}/update-status`, {
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
            showNotification(data.message, 'success');
            location.reload();
        } else {
            showNotification(data.message || 'Failed to update status', 'error');
        }
    } catch (error) {
        console.error('Update status error:', error);
        showNotification('Error updating status', 'error');
    }
};

// Notification function
window.showNotification = function(message, type) {
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
</script>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Product Management</h1>
                    <p class="text-gray-600 mt-2">Manage inventory, stock levels, and product availability</p>
                    <div class="flex items-center gap-2 mt-3">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-blue-600">
                            <strong>Smart Status:</strong> Products with stock tracking automatically manage "Available" ↔ "Out of Stock" status. You can still manually set "Inactive" or "Discontinued" as needed.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="testJS()" class="inline-flex items-center gap-2 px-3 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700 transition-colors">
                        Test JS
                    </button>
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Product
                    </a>
                    <a href="{{ route('admin.products.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-8">
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="text-2xl font-bold text-blue-700">{{ $stats['total_products'] }}</div>
                    <div class="text-sm text-blue-600">Total Products</div>
                </div>
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="text-2xl font-bold text-green-700">{{ $stats['available_products'] }}</div>
                    <div class="text-sm text-green-600">Available</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <div class="text-2xl font-bold text-gray-700">{{ $stats['inactive_products'] }}</div>
                    <div class="text-sm text-gray-600">Inactive</div>
                </div>
                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                    <div class="text-2xl font-bold text-red-700">{{ $stats['out_of_stock'] }}</div>
                    <div class="text-sm text-red-600">Out of Stock</div>
                </div>
                <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                    <div class="text-2xl font-bold text-yellow-700">{{ $stats['low_stock'] }}</div>
                    <div class="text-sm text-yellow-600">Low Stock</div>
                </div>
                <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
                    <div class="text-2xl font-bold text-purple-700">{{ $stats['discontinued'] }}</div>
                    <div class="text-sm text-purple-600">Discontinued</div>
                </div>
                <div class="bg-teal-50 rounded-xl p-4 border border-teal-200">
                    <div class="text-lg font-bold text-teal-700">{{ $stats['recent_products'] }}</div>
                    <div class="text-sm text-teal-600">This Week</div>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" class="bg-gray-50 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Product name, category..." 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            @foreach(App\Models\Product::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            <option value="food" {{ request('type') == 'food' ? 'selected' : '' }}>Food</option>
                            <option value="merch" {{ request('type') == 'merch' ? 'selected' : '' }}>Merchandise</option>
                        </select>
                    </div>
                    <div>
                        <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                        <select name="stock_status" id="stock_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Stock Levels</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort_by" id="sort_by" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                            <option value="stock_quantity" {{ request('sort_by') == 'stock_quantity' ? 'selected' : '' }}>Stock Level</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
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
                    <select id="bulk-action" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Select Action</option>
                        <option value="activate">Activate Products</option>
                        <option value="deactivate">Deactivate Products</option>
                        <option value="update_status">Update Status</option>
                        <option value="delete">Delete Products</option>
                    </select>
                    <select id="bulk-status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm hidden">
                        @foreach(App\Models\Product::getStatuses() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
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

        <!-- Products Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @if($products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Product</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Category</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Price</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Stock</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover mr-3">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                                <div class="text-sm text-gray-500">{{ ucfirst($product->type) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                            {{ $product->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">${{ number_format($product->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->track_stock)
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium {{ $product->isLowStock() ? 'text-yellow-600' : ($product->stock_quantity <= 0 ? 'text-red-600' : 'text-green-600') }}">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                                <div class="flex gap-1">
                                                    <button onclick="updateStock({{ $product->id }}, 'decrease', 1)" class="text-red-600 hover:text-red-700">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                                        </svg>
                                                    </button>
                                                    <button onclick="showStockModal({{ $product->id }}, {{ $product->stock_quantity }})" class="text-blue-600 hover:text-blue-700">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                                                        </svg>
                                                    </button>
                                                    <button onclick="updateStock({{ $product->id }}, 'increase', 1)" class="text-green-600 hover:text-green-700">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-sm">Not tracked</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1">
                                            <select onchange="updateProductStatus({{ $product->id }}, this.value)" 
                                                    class="text-xs px-2 py-1 rounded border-0 font-medium focus:ring-2 focus:ring-blue-500
                                                    @if($product->status === 'active' && $product->isInStock()) bg-green-100 text-green-800
                                                    @elseif($product->status === 'out_of_stock' || ($product->status === 'active' && !$product->isInStock())) bg-red-100 text-red-800
                                                    @elseif($product->status === 'inactive') bg-gray-100 text-gray-800
                                                    @elseif($product->status === 'discontinued') bg-red-100 text-red-800
                                                    @endif">
                                                @foreach(\App\Models\Product::getStatuses() as $value => $label)
                                                    @php
                                                        $isDisabled = false;
                                                        $tooltip = '';
                                                        
                                                        // For stock-tracked products, disable conflicting options
                                                        if ($product->track_stock) {
                                                            if ($value === 'out_of_stock' && $product->stock_quantity > 0) {
                                                                $isDisabled = true;
                                                                $tooltip = 'Auto-managed based on stock levels';
                                                            } elseif ($value === 'active' && $product->stock_quantity <= 0) {
                                                                $isDisabled = true;
                                                                $tooltip = 'Stock required to set as available';
                                                            }
                                                        }
                                                    @endphp
                                                    <option value="{{ $value }}" 
                                                            {{ $product->status == $value ? 'selected' : '' }}
                                                            {{ $isDisabled ? 'disabled' : '' }}
                                                            title="{{ $tooltip }}">
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if($product->track_stock)
                                                <span class="text-xs text-blue-600" title="Available/Out of Stock status is automatically managed based on inventory levels. You can still set Inactive or Discontinued manually.">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-block delete-form-{{ $product->id }}" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium delete-btn delete-btn-{{ $product->id }}"
                                                        data-product-id="{{ $product->id }}"
                                                        onclick="window.deleteProduct && window.deleteProduct({{ $product->id }}) || window.deleteProductSimple({{ $product->id }})"
                                                        title="Click to delete product">
                                                    Delete
                                                </button>
                                                <noscript>
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                        Delete (No JS)
                                                    </button>
                                                </noscript>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mt-4">No products found</h3>
                    <p class="text-gray-500 mt-2">No products match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stock-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Update Stock</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select id="stock-action" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="set">Set to specific amount</option>
                    <option value="increase">Increase by</option>
                    <option value="decrease">Decrease by</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" id="stock-quantity" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="text-sm text-gray-500">
                Current stock: <span id="current-stock"></span>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="applyStockUpdate()" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Update
            </button>
            <button onclick="closeStockModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Essential Stock Management Functions - Loaded Immediately -->
<script>
// Global variables
let currentProductId = null;

// Stock management functions
async function updateStock(productId, action, quantity) {
    console.log('updateStock called:', { productId, action, quantity });
    
    try {
        // Add visual feedback
        const button = event?.target?.closest('button');
        if (button) {
            button.disabled = true;
            button.style.opacity = '0.5';
        }
        
        const url = `/admin/products/${productId}/stock`;
        console.log('Sending request to:', url);
        
        const response = await fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ action, quantity })
        });

        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            showNotification(data.message, 'success');
            location.reload();
        } else {
            showNotification(data.message || 'Failed to update stock', 'error');
        }
    } catch (error) {
        console.error('Stock update error:', error);
        showNotification(`Error: ${error.message}`, 'error');
    }
}

function showStockModal(productId, currentStock) {
    console.log('showStockModal called:', { productId, currentStock });
    currentProductId = productId;
    document.getElementById('current-stock').textContent = currentStock || '0';
    document.getElementById('stock-quantity').value = '';
    document.getElementById('stock-action').value = 'set';
    document.getElementById('stock-modal').classList.remove('hidden');
}

function closeStockModal() {
    document.getElementById('stock-modal').classList.add('hidden');
    currentProductId = null;
}

async function applyStockUpdate() {
    const action = document.getElementById('stock-action').value;
    const quantity = parseInt(document.getElementById('stock-quantity').value);
    
    if (!quantity || quantity < 0) {
        showNotification('Please enter a valid quantity', 'error');
        return;
    }
    
    await updateStock(currentProductId, action, quantity);
    closeStockModal();
}

function showNotification(message, type) {
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
}

// Test function - you can call this from browser console
function testStockButtons() {
    console.log('Testing stock button functionality...');
    showNotification('Stock buttons JavaScript is loaded!', 'success');
    return 'Stock button functions are available';
}

console.log('Stock management functions loaded successfully');
</script>

@push('styles')
<style>
.delete-loading {
    opacity: 0.6;
    cursor: not-allowed;
}
.row-deleting {
    opacity: 0.5;
    transition: opacity 0.5s ease;
}
</style>
@endpush

@push('scripts')
<script>
// Functions are now defined at the top of the page for immediate availability

// Additional page functionality starts here
    
    console.log('Delete confirmed, proceeding...');
    
    // Get CSRF token with fallback
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
    
    if (!csrfToken) {
        console.error('CSRF token not found!');
        alert('Security token not found. Please refresh the page and try again.');
        return;
    }
    
    console.log('CSRF token found:', csrfToken ? 'Yes' : 'No');
    
    // Show loading state
    const deleteButton = document.querySelector('.delete-btn-' + productId);
    const row = deleteButton ? deleteButton.closest('tr') : null;
    
    console.log('Delete button found:', deleteButton ? 'Yes' : 'No');
    console.log('Row found:', row ? 'Yes' : 'No');
    
    if (deleteButton) {
        deleteButton.disabled = true;
        deleteButton.textContent = 'Deleting...';
        deleteButton.classList.add('delete-loading');
    }
    
    if (row) {
        row.classList.add('row-deleting');
    }
    
    console.log('Sending delete request to:', '/admin/products/' + productId);
    
    // Use fetch for the delete request
    fetch('/admin/products/' + productId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(function(response) {
        console.log('Response received:', response.status, response.statusText);
        if (response.ok) {
            return response.json();
        }
        throw new Error('HTTP ' + response.status + ': ' + response.statusText);
    })
    .then(function(data) {
        console.log('Response data:', data);
        if (data.success) {
            alert('Product deleted successfully!');
            // Animate row removal
            if (row) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100%)';
                setTimeout(function() {
                    row.remove();
                    // Check if this was the last row
                    const remainingRows = document.querySelectorAll('tbody tr').length;
                    if (remainingRows === 0) {
                        location.reload();
                    }
                }, 500);
            } else {
                location.reload();
            }
        } else {
            throw new Error(data.message || 'Failed to delete product');
        }
    })
    .catch(function(error) {
        console.error('Delete error:', error);
        alert('Error: ' + (error.message || 'Failed to delete product'));
        
        // If AJAX fails, fallback to form submission
        console.log('AJAX failed, trying form submission fallback...');
        const form = document.querySelector('.delete-form-' + productId);
        if (form && confirm('AJAX failed. Try traditional form submission?')) {
            form.submit();
            return;
        }
        
        // Reset button state
        if (deleteButton) {
            deleteButton.disabled = false;
            deleteButton.textContent = 'Delete';
            deleteButton.classList.remove('delete-loading');
        }
        
        if (row) {
            row.classList.remove('row-deleting');
        }
    });
};

let currentProductId = null;

// Initialize all event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing event listeners...');
    
    // Delete button event listeners
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            deleteProduct(productId);
        });
        
        // Double-click fallback
        button.addEventListener('dblclick', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            const form = document.querySelector(`.delete-form-${productId}`);
            if (form && confirm('Are you sure you want to delete this product? (Form submission)')) {
                form.submit();
            }
        });
    });
    
    // Bulk actions
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
    }

    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    // Bulk action selector
    const bulkAction = document.getElementById('bulk-action');
    if (bulkAction) {
        bulkAction.addEventListener('change', function() {
            const statusSelect = document.getElementById('bulk-status');
            if (statusSelect) {
                statusSelect.classList.toggle('hidden', this.value !== 'update_status');
            }
        });
    }

    // Apply bulk action
    const applyBulk = document.getElementById('apply-bulk');
    if (applyBulk) {
        applyBulk.addEventListener('click', applyBulkAction);
    }

    // Cancel bulk action
    const cancelBulk = document.getElementById('cancel-bulk');
    if (cancelBulk) {
        cancelBulk.addEventListener('click', function() {
            const bulkActions = document.getElementById('bulk-actions');
            if (bulkActions) {
                bulkActions.classList.add('hidden');
            }
            const selectAllBox = document.getElementById('select-all');
            if (selectAllBox) {
                selectAllBox.checked = false;
            }
            document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = false);
        });
    }
});

// Global functions (accessible from anywhere)
function testJS() {
    alert('JavaScript is working! Delete function should work now.');
}

window.updateBulkActions = function() {
    const checked = document.querySelectorAll('.product-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (checked.length > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = `${checked.length} product${checked.length > 1 ? 's' : ''} selected`;
    } else {
        bulkActions.classList.add('hidden');
    }
}

// Stock management functions are now defined at the top of the page


// Bulk actions
async function applyBulkAction() {
    const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
    const action = document.getElementById('bulk-action').value;
    const status = document.getElementById('bulk-status').value;

    if (selectedIds.length === 0) {
        showNotification('Please select at least one product', 'error');
        return;
    }

    if (!action) {
        showNotification('Please select an action', 'error');
        return;
    }

    if (action === 'delete' && !confirm(`Are you sure you want to delete ${selectedIds.length} product(s)? This action cannot be undone.`)) {
        return;
    }

    try {
        const response = await fetch('/admin/products/bulk-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_ids: selectedIds,
                action: action,
                status: status
            })
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload();
            } else {
                showNotification(data.message || 'Bulk action failed', 'error');
            }
        } else {
            const errorData = await response.json().catch(() => ({}));
            showNotification(errorData.message || `Error: ${response.status} ${response.statusText}`, 'error');
        }
    } catch (error) {
        console.error('Bulk action error:', error);
        showNotification('Network error occurred', 'error');
    }
}

// All JavaScript functions have been moved to the inline script at the top of the page for immediate loading
</script>
@endpush
@endsection