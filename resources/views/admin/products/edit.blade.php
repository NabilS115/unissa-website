@extends('layouts.app')

@section('title', 'Unissa Cafe - Edit Product - ' . $product->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modal-style Container -->
        <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-600 via-teal-700 to-emerald-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Product
                        </h1>
                        <p class="text-teal-100 mt-1">Update {{ $product->name }} details</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-all duration-200 border border-white/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-all duration-200 border border-white/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Product Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-semibold text-gray-800">Product Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 @error('name') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">
                            @error('name')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="space-y-2">
                            <label for="category" class="block text-sm font-semibold text-gray-800">Category *</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $product->category) }}" required list="categories"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 @error('category') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">
                            <datalist id="categories">
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">
                                @endforeach
                            </datalist>
                            @error('category')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="space-y-2">
                            <label for="type" class="block text-sm font-semibold text-gray-800">Type *</label>
                            <select name="type" id="type" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 @error('type') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">
                                <option value="">Select Type</option>
                                <option value="food" {{ old('type', $product->type) == 'food' ? 'selected' : '' }}>Food</option>
                                <option value="merch" {{ old('type', $product->type) == 'merch' ? 'selected' : '' }}>Merchandise</option>
                            </select>
                            @error('type')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="space-y-2">
                            <label for="price" class="block text-sm font-semibold text-gray-800">Price *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-lg font-medium">$</span>
                                </div>
                                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                                       class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 @error('price') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">
                            </div>
                            @error('price')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-gray-800">Status *</label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 @error('status') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">
                                @foreach(App\Models\Product::getStatuses() as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $product->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Low Stock Threshold -->
                        <div class="space-y-2">
                            <label for="low_stock_threshold" class="block text-sm font-semibold text-gray-800">Low Stock Threshold *</label>
                            <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0" required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 @error('low_stock_threshold') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">
                            @error('low_stock_threshold')
                                <p class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="lg:col-span-2 space-y-2">
                        <label for="desc" class="block text-sm font-semibold text-gray-800">Description *</label>
                        <textarea name="desc" id="desc" rows="4" required
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 resize-none @error('desc') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">{{ old('desc', $product->desc) }}</textarea>
                        @error('desc')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Product Image -->
                    <div class="lg:col-span-2 space-y-4">
                        <label class="block text-sm font-semibold text-gray-800">Product Image</label>
                        
                        @if($product->img)
                            <div class="bg-gradient-to-br from-teal-50 to-emerald-50 p-4 rounded-xl border border-teal-200">
                                <p class="text-sm font-medium text-teal-800 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                    Current Image:
                                </p>
                                <img src="{{ $product->img }}" alt="{{ $product->name }}" class="w-40 h-40 object-cover rounded-xl border-2 border-white shadow-lg">
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-teal-300 rounded-xl p-8 text-center hover:border-teal-400 transition-colors duration-200 bg-teal-50/50">
                            <div class="space-y-4">
                                <div class="mx-auto w-16 h-16 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <label for="img" class="cursor-pointer">
                                        <span class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-teal-700 hover:to-emerald-700 transition-all duration-200 inline-block">
                                            {{ $product->img ? 'Replace Image' : 'Choose Image' }}
                                        </span>
                                        <input id="img" name="img" type="file" accept="image/*" class="hidden @error('img') border-red-500 @enderror">
                                    </label>
                                    <p class="text-sm text-gray-600 mt-2">or drag and drop your image here</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        @error('img')
                            <p class="text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Stock Management Options -->
                    <div class="lg:col-span-2 border-t-2 border-teal-100 pt-8 mt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            Stock Management
                        </h3>
                        
                        <div class="space-y-6">
                            <!-- Active Status -->
                            <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-200">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="is_active" class="text-sm font-semibold text-gray-900">Product is active</label>
                            </div>

                            <!-- Track Stock -->
                            <div class="flex items-center space-x-3 p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-200">
                                <input type="checkbox" name="track_stock" id="track_stock" value="1" {{ old('track_stock', $product->track_stock) ? 'checked' : '' }}
                                       class="w-5 h-5 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="track_stock" class="text-sm font-semibold text-gray-900">Track stock quantity</label>
                            </div>

                            <!-- Current Stock Information -->
                            @if($product->track_stock)
                                <div class="bg-gradient-to-br from-teal-100 to-emerald-100 border border-teal-300 rounded-xl p-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-lg font-bold text-teal-900 flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd"/>
                                                </svg>
                                                Current Stock: {{ $product->stock_quantity }}
                                            </p>
                                            <p class="text-sm text-teal-700 mt-1">Last updated: {{ $product->last_restocked_at ? $product->last_restocked_at->format('M j, Y g:i A') : 'Never' }}</p>
                                        </div>
                                        @if($product->isLowStock())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                ⚠️ Low Stock
                                            </span>
                                        @elseif($product->stock_quantity <= 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-300">
                                                ❌ Out of Stock
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-300">
                                                ✅ In Stock
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Stock Quantity (only shows if tracking is enabled) -->
                            <div id="stock_quantity_field" class="{{ old('track_stock', $product->track_stock) ? '' : 'hidden' }} space-y-2">
                                <label for="stock_quantity" class="block text-sm font-semibold text-gray-800">Stock Quantity</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 transition-all duration-200 @error('stock_quantity') border-red-400 focus:border-red-500 focus:ring-red-500/20 @enderror">
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    💡 <strong>Tip:</strong> Use the stock management tools on the products list for detailed stock operations.
                                </p>
                                @error('stock_quantity')
                                    <p class="text-sm text-red-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl hover:from-teal-700 hover:to-emerald-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="/js/admin-product-edit.js"></script>
@endpush
@endsection