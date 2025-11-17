@extends('layouts.app')

@section('title', 'Unissa Cafe - Product Details - ' . $product->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="text-gray-600 mt-2">Product ID: #{{ $product->id }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                        </svg>
                        Edit Product
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Product Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Details -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Product Information</h2>
                    
                    <div class="flex items-start gap-8">
                        <div class="flex-shrink-0">
                            <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-48 h-48 object-cover rounded-xl border border-gray-300">
                        </div>
                        
                        <div class="flex-1 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Name</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Category</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ $product->category }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Type</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($product->type) }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Price</h3>
                                    <p class="text-lg font-semibold text-gray-900">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                                <p class="text-gray-900 leading-relaxed">{{ $product->desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock & Availability -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Stock & Availability</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                    {{ $product->status_color === 'green' ? 'bg-green-100 text-green-800' : 
                                       ($product->status_color === 'red' ? 'bg-red-100 text-red-800' : 
                                       ($product->status_color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $product->availability_status }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Active</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Track Stock</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $product->track_stock ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->track_stock ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($product->track_stock)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <span class="text-sm font-medium text-blue-700">Current Stock</span>
                                <span class="text-2xl font-bold {{ $product->isLowStock() ? 'text-yellow-600' : ($product->stock_quantity <= 0 ? 'text-red-600' : 'text-green-600') }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Low Stock Threshold</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $product->low_stock_threshold }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Last Restocked</span>
                                <span class="text-sm text-gray-900">
                                    {{ $product->last_restocked_at ? $product->last_restocked_at->format('M j, Y g:i A') : 'Never' }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @if($product->track_stock)
                    <!-- Quick Stock Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stock Actions</h3>
                        <div class="flex items-center gap-4">
                            <button onclick="updateStock({{ $product->id }}, 'decrease', 1)" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                                </svg>
                                Decrease (-1)
                            </button>
                            <button onclick="showStockModal({{ $product->id }}, {{ $product->stock_quantity }})" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                                </svg>
                                Set Stock
                            </button>
                            <button onclick="updateStock({{ $product->id }}, 'increase', 1)" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Increase (+1)
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Orders History -->
                @if($product->orders && $product->orders->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Recent Orders</h2>
                    
                    <div class="overflow-hidden border border-gray-200 rounded-lg">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($product->orders->take(5) as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->user->name ?? 'Guest' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->pivot->quantity ?? 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($product->orders->count() > 5)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">Showing 5 of {{ $product->orders->count() }} orders</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Reviews -->
                @if($product->reviews && $product->reviews->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Customer Reviews</h2>
                    
                    <div class="space-y-4">
                        @foreach($product->reviews->take(3) as $review)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</span>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('M j, Y') }}</span>
                            </div>
                            <p class="text-gray-700">{{ $review->review }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($product->reviews->count() > 3)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">Showing 3 of {{ $product->reviews->count() }} reviews</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <button onclick="toggleStatus({{ $product->id }})" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 {{ $product->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            {{ $product->is_active ? 'Deactivate' : 'Activate' }} Product
                        </button>
                        
                        <a href="{{ route('admin.products.edit', $product) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z"/>
                            </svg>
                            Edit Product
                        </a>
                        
                        <button onclick="deleteProduct({{ $product->id }})" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Product
                        </button>
                    </div>
                </div>

                <!-- Product Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Product Statistics</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Orders</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $product->orders ? $product->orders->count() : 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Reviews</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $product->reviews ? $product->reviews->count() : 0 }}</span>
                        </div>
                        @if($product->reviews && $product->reviews->count() > 0)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Average Rating</span>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($product->reviews->avg('rating'), 1) }}</span>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $product->reviews->avg('rating') ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm text-gray-900">{{ $product->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm text-gray-900">{{ $product->updated_at->format('M j, Y g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stock-modal" data-initial-hidden class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
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

@push('scripts')
    <script>
        // Bootstrap for admin product show page
        window.__adminProduct = {
            csrf: '{{ csrf_token() }}',
            redirectIndex: '{{ route("admin.products.index") }}',
            productId: {{ $product->id }}
        };
    </script>
    <script src="/js/admin-product-show.js"></script>
@endpush
@endsection