@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<!-- Essential JavaScript functions -->
<script>
// Global variables for delete functionality
let deleteProductId = null;

// Modern delete modal functions
window.openDeleteModal = function(productId, productName) {
    deleteProductId = productId;
    document.getElementById('delete-product-name').textContent = productName;
    document.getElementById('delete-modal').classList.remove('hidden');
    document.getElementById('delete-modal').classList.add('flex');
};

window.closeDeleteModal = function() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.getElementById('delete-modal').classList.remove('flex');
    deleteProductId = null;
    
    // Reset button state
    const confirmBtn = document.getElementById('confirm-delete-btn');
    confirmBtn.disabled = false;
    confirmBtn.innerHTML = 'Delete Product';
    confirmBtn.className = 'px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium';
};

window.confirmDelete = async function() {
    if (!deleteProductId) return;
    
    const confirmBtn = document.getElementById('confirm-delete-btn');
    const row = document.querySelector(`tr:has(.delete-btn-${deleteProductId})`);
    
    // Show loading state
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = `
        <svg class="animate-spin h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Deleting...
    `;
    confirmBtn.className = 'px-6 py-2.5 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium';
    
    try {
        const response = await fetch(`/admin/products/${deleteProductId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show success notification
            showNotification(data.message || 'Product deleted successfully!', 'success');
            
            // Close modal
            closeDeleteModal();
            
            // Animate row removal
            if (row) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100%)';
                setTimeout(() => {
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
    } catch (error) {
        console.error('Delete error:', error);
        showNotification(error.message || 'Failed to delete product', 'error');
        closeDeleteModal();
    }
};

// Modern notification system
window.showNotification = function(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    
    // Set colors based on type
    switch(type) {
        case 'success':
            notification.className += ' bg-green-500 text-white';
            break;
        case 'error':
            notification.className += ' bg-red-500 text-white';
            break;
        case 'warning':
            notification.className += ' bg-yellow-500 text-white';
            break;
        default:
            notification.className += ' bg-blue-500 text-white';
    }
    
    // Create content with icon
    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : type === 'warning' ? '⚠' : 'ℹ';
    notification.innerHTML = `
        <div class="flex items-center gap-2">
            <span class="text-lg">${icon}</span>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 4000);
};

// Test function for debugging
window.testJS = function() {
    showNotification('JavaScript is working! Delete function is ready.', 'success');
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

// Dropdown functions - defined immediately for onclick access
window.toggleDropdown = function(id) {
    const dropdown = document.getElementById(id);
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
};

// Import Modal Functions - defined immediately for onclick access
window.openImportModal = function(type) {
    document.getElementById('importModal').classList.remove('hidden');
    document.getElementById('importType').value = type;
    document.getElementById('modalTitle').textContent = `Import ${type.charAt(0).toUpperCase() + type.slice(1)}`;
};

window.closeImportModal = function() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importFile').value = '';
    document.getElementById('importForm').reset();
};

window.handleImport = async function() {
    const form = document.getElementById('importForm');
    const fileInput = document.getElementById('importFile');
    const type = document.getElementById('importType').value;
    
    if (!fileInput.files[0]) {
        window.showNotification('Please select a CSV file', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('csv_file', fileInput.files[0]);
    formData.append('_token', '{{ csrf_token() }}');
    
    try {
        const response = await fetch(`/admin/${type}/import`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok) {
            window.showNotification(result.message || 'Import completed successfully', 'success');
            window.closeImportModal();
            // Reload the page to show imported data
            window.location.reload();
        } else {
            window.showNotification(result.message || 'Import failed', 'error');
        }
    } catch (error) {
        console.error('Import error:', error);
        window.showNotification('Import failed. Please try again.', 'error');
    }
};

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown-menu');
    const button = event.target.closest('[onclick*="toggleDropdown"]');
    
    if (!button && dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
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
                    <!-- Import/Export Dropdown -->
                    <div class="relative">
                        <button onclick="toggleDropdown('dropdown-menu')" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            Import/Export
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div id="dropdown-menu" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                            <div class="py-2">
                                <a href="{{ route('admin.products.export', request()->query()) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3"/>
                                    </svg>
                                    Export Products CSV
                                </a>
                                <button onclick="openImportModal('products')" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors text-left">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m0 0l3-3m-3 3l-3-3"/>
                                    </svg>
                                    Import Products CSV
                                </button>
                            </div>
                        </div>
                    </div>
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
                                            <a href="{{ route('admin.products.show', $product) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 text-sm font-medium rounded-lg border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-600 text-sm font-medium rounded-lg border border-indigo-200 hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <button type="button" 
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-sm font-medium rounded-lg border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all duration-200 delete-btn-{{ $product->id }}"
                                                    onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                    title="Delete product">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
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
<div id="stock-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
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
    const modal = document.getElementById('stock-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeStockModal() {
    const modal = document.getElementById('stock-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
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
// All JavaScript functions have been moved to the inline script at the top of the page for immediate loading
console.log('Admin products page additional scripts loaded');
</script>
@endpush

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 relative">
        <!-- Close button -->
        <button onclick="closeDeleteModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Modal content -->
        <div class="text-center">
            <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            
            <h3 class="text-lg font-bold text-gray-900 mb-2">Delete Product</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete "<span id="delete-product-name" class="font-semibold"></span>"? This action cannot be undone.</p>
            
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" 
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="confirmDelete()" id="confirm-delete-btn"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                    Delete Product
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-medium text-gray-900">Import Data</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="importForm" class="space-y-4">
                <input type="hidden" id="importType" value="">
                
                <div>
                    <label for="importFile" class="block text-sm font-medium text-gray-700 mb-2">
                        Select CSV File
                    </label>
                    <input type="file" id="importFile" accept=".csv" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-sm text-blue-700">
                        <strong>CSV Format Requirements:</strong><br>
                        • First row should contain column headers<br>
                        • Use comma (,) as field separator<br>
                        • Enclose text fields in quotes if they contain commas<br>
                        • Maximum file size: 10MB
                    </p>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeImportModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="button" onclick="handleImport()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Import CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection