@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<!-- Admin products JS extracted to an external file -->
<script>
window.__adminProducts = {
    csrf: '{{ csrf_token() }}'
};
</script>
<script src="/js/admin-products.js"></script>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <!-- Back Button -->
                    <div class="flex items-center gap-3 mb-4">
                        <a href="{{ 
                            request()->headers->get('referer') && 
                            str_contains(request()->headers->get('referer'), url('/admin-profile')) 
                                ? request()->headers->get('referer') 
                                : route('admin.profile') 
                        }}" 
                           class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Admin
                        </a>
                    </div>
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
<div id="stock-modal" data-initial-hidden class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
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
@endpush

<!-- Delete Confirmation Modal -->
<div id="delete-modal" data-initial-hidden class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
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
<div id="importModal" data-initial-hidden class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
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

<style>
/* Comprehensive mobile optimizations for admin products page */
@media (max-width: 768px) {
    /* Page container mobile fixes */
    .max-w-7xl {
        max-width: 100% !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        margin: 0 !important;
    }
    
    /* Header card mobile */
    .admin-header {
        padding: 1rem !important;
        border-radius: 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    /* Header layout mobile - stack vertically */
    .header-layout {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1rem !important;
    }
    
    /* Page title mobile */
    .page-title {
        font-size: 1.5rem !important;
        line-height: 1.2 !important;
    }
    
    /* Info text mobile */
    .info-text {
        font-size: 0.75rem !important;
        gap: 0.5rem !important;
    }
    
    /* Action buttons mobile */
    .header-actions {
        width: 100% !important;
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    
    .action-btn {
        width: 100% !important;
        justify-content: center !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.875rem !important;
    }
    
    /* Back button mobile */
    .back-btn {
        margin-bottom: 1rem !important;
        font-size: 0.875rem !important;
    }
    
    /* Stats grid mobile */
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1rem !important;
    }
    
    .stat-card {
        padding: 1rem !important;
        text-align: center !important;
    }
    
    /* Filters mobile */
    .filters-container {
        flex-direction: column !important;
        gap: 0.75rem !important;
        padding: 1rem !important;
    }
    
    .filter-item {
        width: 100% !important;
    }
    
    .filter-input, .filter-select {
        width: 100% !important;
        font-size: 16px !important; /* Prevent zoom on iOS */
        padding: 0.75rem !important;
    }
    
    /* Bulk actions mobile */
    .bulk-actions {
        flex-direction: column !important;
        gap: 0.75rem !important;
        padding: 1rem !important;
    }
    
    .bulk-select {
        width: 100% !important;
    }
    
    .bulk-btn {
        width: 100% !important;
        padding: 0.75rem !important;
    }
    
    /* Table mobile - convert to cards */
    .products-table {
        display: none !important;
    }
    
    .mobile-products {
        display: block !important;
    }
    
    /* Product card mobile layout */
    .product-card {
        background: white !important;
        border-radius: 0.75rem !important;
        padding: 1rem !important;
        margin-bottom: 1rem !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .product-header {
        display: flex !important;
        gap: 1rem !important;
        margin-bottom: 0.75rem !important;
        padding-bottom: 0.75rem !important;
        border-bottom: 1px solid #f3f4f6 !important;
    }
    
    .product-image {
        width: 60px !important;
        height: 60px !important;
        border-radius: 0.5rem !important;
        object-fit: cover !important;
        flex-shrink: 0 !important;
    }
    
    .product-info {
        flex: 1 !important;
        min-width: 0 !important;
    }
    
    .product-name {
        font-weight: 600 !important;
        font-size: 1rem !important;
        margin-bottom: 0.25rem !important;
        line-height: 1.2 !important;
    }
    
    .product-category {
        font-size: 0.75rem !important;
        color: #6b7280 !important;
    }
    
    .product-details {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 0.75rem !important;
        font-size: 0.875rem !important;
    }
    
    .product-field {
        display: flex !important;
        flex-direction: column !important;
    }
    
    .field-label {
        font-weight: 500 !important;
        color: #6b7280 !important;
        font-size: 0.75rem !important;
        margin-bottom: 0.25rem !important;
    }
    
    .field-value {
        font-weight: 600 !important;
        color: #111827 !important;
    }
    
    /* Status badge mobile */
    .status-badge {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 0.375rem !important;
        display: inline-block !important;
    }
    
    /* Stock indicator mobile */
    .stock-indicator {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
    }
    
    /* Price mobile */
    .product-price {
        font-size: 1.125rem !important;
        font-weight: 700 !important;
        color: #059669 !important;
    }
    
    /* Action buttons mobile */
    .product-actions {
        margin-top: 0.75rem !important;
        display: flex !important;
        gap: 0.5rem !important;
        flex-wrap: wrap !important;
    }
    
    .product-action-btn {
        flex: 1 !important;
        min-width: 0 !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.75rem !important;
        border-radius: 0.375rem !important;
    }
    
    /* Pagination mobile */
    .pagination {
        flex-direction: column !important;
        gap: 1rem !important;
        padding: 1rem !important;
        text-align: center !important;
    }
    
    .pagination-info {
        order: 1 !important;
        font-size: 0.875rem !important;
    }
    
    .pagination-controls {
        order: 2 !important;
        justify-content: center !important;
        gap: 0.5rem !important;
    }
    
    .pagination-btn {
        min-width: 44px !important;
        height: 44px !important;
        font-size: 0.875rem !important;
    }
    
    /* Modal mobile optimization */
    .modal-overlay {
        padding: 1rem !important;
    }
    
    .modal-content {
        width: 100% !important;
        max-width: 100% !important;
        max-height: 90vh !important;
        overflow-y: auto !important;
        margin: 0 !important;
    }
    
    .modal-header {
        padding: 1rem !important;
        font-size: 1.125rem !important;
    }
    
    .modal-body {
        padding: 1rem !important;
    }
    
    .modal-footer {
        padding: 1rem !important;
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
    
    .modal-btn {
        width: 100% !important;
        padding: 0.75rem !important;
    }
    
    /* Form elements mobile */
    .form-input {
        font-size: 16px !important; /* Prevent zoom on iOS */
        padding: 0.75rem !important;
    }
    
    /* File upload mobile */
    .file-upload {
        padding: 1rem !important;
        border-radius: 0.75rem !important;
        text-align: center !important;
    }
    
    /* Search mobile */
    .search-container {
        width: 100% !important;
        margin-bottom: 1rem !important;
    }
    
    .search-input {
        width: 100% !important;
        font-size: 16px !important;
        padding: 0.875rem !important;
    }
    
    /* Test button mobile */
    .test-btn {
        display: none !important;
    }
}
</style>

@endsection