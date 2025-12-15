@extends('layouts.app')

@section('title', 'Unissa Cafe - Catalog')

@push('styles')
<style>
/* Ensure main container is visible immediately to prevent flash */
#browse-container {
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
}

/* Hide modals by default with CSS, not x-cloak */
[data-initial-hidden] {
    display: none !important;
}

/* PROFESSIONAL MOBILE OPTIMIZATION */
@media (max-width: 768px) {
    .food-card, .merch-card {
        margin-bottom: 16px !important;
        min-height: 320px !important;
        display: flex !important;
        flex-direction: column !important;
        height: auto !important;
    }
    
    .food-card img, .merch-card img {
        height: 180px !important;
        max-height: 180px !important;
        object-fit: cover !important;
        width: 100% !important;
        object-position: center !important;
    }
    
    .food-card .p-2\.5, .merch-card .p-2\.5 {
        padding: 16px !important;
        flex-grow: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
    }
    
    .food-card button, .merch-card button {
        padding: 8px 16px !important;
        font-size: 13px !important;
        border-radius: 8px !important;
        min-width: 70px !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
        margin-top: auto !important;
    }
    
    .food-card h4, .merch-card h4 {
        font-size: 16px !important;
        line-height: 1.3 !important;
        margin-bottom: 8px !important;
    }
    
    .food-card p, .merch-card p {
        font-size: 13px !important;
        line-height: 1.4 !important;
        margin-bottom: 12px !important;
        flex-grow: 1 !important;
    }
    
    /* Better grid spacing */
    .grid {
        gap: 16px !important;
        padding: 16px !important;
    }
    
    /* Ensure equal card heights */
    .max-w-6xl.mx-auto.grid {
        align-items: stretch !important;
    }
    
    .max-w-6xl.mx-auto.grid > * {
        height: 100% !important;
    }
}
</style>
@endpush

@section('content')
@php
    $food = $food ?? [];
    $merchandise = $merchandise ?? [];
    
    // Convert both food and merchandise to consistent objects format
    if ($food instanceof \Illuminate\Support\Collection) {
        $food = $food->map(function($item) { 
            return is_array($item) ? (object)$item : $item; 
        })->toArray();
    } else {
        // Handle regular arrays
        $food = array_map(function($item) {
            return is_array($item) ? (object)$item : $item;
        }, $food);
    }
    
    if ($merchandise instanceof \Illuminate\Support\Collection) {
        $merchandise = $merchandise->map(function($item) { 
            return is_array($item) ? (object)$item : $item; 
        })->toArray();
    } else {
        // Handle regular arrays
        $merchandise = array_map(function($item) {
            return is_array($item) ? (object)$item : $item;
        }, $merchandise);
    }
    
    // Calculate average ratings for each product
    foreach ($food as &$foodItem) {
        $productId = null;
        
        if (is_object($foodItem) && isset($foodItem->id)) {
            $productId = $foodItem->id;
        } elseif (is_array($foodItem) && isset($foodItem['id'])) {
            $productId = $foodItem['id'];
        }
        
        if ($productId) {
            $reviews = \App\Models\Review::where('product_id', $productId)->get();
            $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

            foreach ($reviews as $review) {
                $rating = (int) $review->rating;
                if ($rating >= 1 && $rating <= 5) {
                    $ratings[$rating]++;
                }
            }
            
            $totalRatings = array_sum($ratings);
            $averageRating = 0;
            
            if ($totalRatings > 0) {
                $weightedSum = 0;
                foreach ($ratings as $star => $count) {
                    $weightedSum += $star * $count;
                }
                $averageRating = $weightedSum / $totalRatings;
            }
            
            if (is_object($foodItem)) {
                $foodItem->calculated_rating = number_format($averageRating, 1);
            } else {
                $foodItem['calculated_rating'] = number_format($averageRating, 1);
                $foodItem = (object)$foodItem;
            }
        }
    }
    
    // Calculate average ratings for merchandise items
    foreach ($merchandise as &$merchItem) {
        $productId = null;
        
        if (is_object($merchItem) && isset($merchItem->id)) {
            $productId = $merchItem->id;
        } elseif (is_array($merchItem) && isset($merchItem['id'])) {
            $productId = $merchItem['id'];
        }
        
        if ($productId) {
            $reviews = \App\Models\Review::where('product_id', $productId)->get();
            $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

            foreach ($reviews as $review) {
                $rating = (int) $review->rating;
                if ($rating >= 1 && $rating <= 5) {
                    $ratings[$rating]++;
                }
            }
            
            $totalRatings = array_sum($ratings);
            $averageRating = 0;
            
            if ($totalRatings > 0) {
                $weightedSum = 0;
                foreach ($ratings as $star => $count) {
                    $weightedSum += $star * $count;
                }
                $averageRating = $weightedSum / $totalRatings;
            }
            
            if (is_object($merchItem)) {
                $merchItem->calculated_rating = number_format($averageRating, 1);
            } else {
                $merchItem['calculated_rating'] = number_format($averageRating, 1);
                $merchItem = (object)$merchItem;
            }
        }
    }
    
    // Separate categories for food and merch
    $foodCategories = \App\Models\Product::where('type', 'food')->pluck('category')->unique()->values()->all();
    $merchCategories = \App\Models\Product::where('type', 'merch')->pluck('category')->unique()->values()->all();
@endphp

<div x-data="foodMerchComponent()" class="alpine-component" id="browse-container">

    <!-- Menu Controls Header -->
                <div class="w-full bg-teal-600 text-white sticky top-0 z-40 border-t border-teal-500">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Second Row: Search, Filters, and Controls -->
            <div class="py-3">
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <!-- Left: Tab Switcher -->
                    <div class="flex-shrink-0">
                        <div class="inline-flex rounded-lg bg-teal-700 p-1 shadow-sm">
                            <button type="button" @click="switchTab('food')" :class="tab === 'food' ? 'bg-white text-teal-700' : 'bg-transparent text-white'" class="px-4 py-1.5 rounded-lg font-medium focus:outline-none transition-all duration-200 text-sm">Food</button>
                            <button type="button" @click="switchTab('merch')" :class="tab === 'merch' ? 'bg-white text-teal-700' : 'bg-transparent text-white'" class="px-4 py-1.5 rounded-lg font-medium focus:outline-none transition-all duration-200 text-sm">Merch</button>
                        </div>
                    </div>

                    <!-- Center: Search Bar -->
                    <div class="flex-1 max-w-md relative">
                        <div class="relative">
                            <input x-show="tab === 'food'" type="text" placeholder="Search food..." x-model="foodSearchInput" @focus="showFoodPredictions = true" @input="showFoodPredictions = foodSearchInput.length > 0" @blur="setTimeout(() => { showFoodPredictions = false; }, 100)" @keyup.enter="performSearch()" class="w-full border border-white rounded-lg px-4 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-white text-sm bg-white text-teal-700" />
                            <input x-show="tab === 'merch'" type="text" placeholder="Search merchandise..." x-model="merchSearchInput" @focus="showMerchPredictions = true" @input="showMerchPredictions = merchSearchInput.length > 0" @blur="setTimeout(() => { showMerchPredictions = false; }, 100)" @keyup.enter="performSearch()" class="w-full border border-white rounded-lg px-4 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-white text-sm bg-white text-teal-700" />
                            <button @click="performSearch()" class="absolute right-8 top-1/2 -translate-y-1/2 p-1">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </button>
                            <button x-show="tab === 'food' && (foodSearch || foodSearchInput)" @click="clearSearch()" class="absolute right-1 top-1/2 -translate-y-1/2 p-1" title="Clear search">
                                <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <button x-show="tab === 'merch' && (merchSearch || merchSearchInput)" @click="clearSearch()" class="absolute right-1 top-1/2 -translate-y-1/2 p-1" title="Clear search">
                                <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                        <!-- Search Predictions -->
                        <template x-if="tab === 'food' && foodSearchInput && showFoodPredictions">
                            <ul class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-48 overflow-y-auto">
                                <template x-for="foodItem in food" :key="foodItem.id">
                                    <template x-if="foodItem.name && foodItem.name.toLowerCase().includes(foodSearchInput.toLowerCase())">
                                        <li @mousedown.prevent="foodSearchInput = foodItem.name; showFoodPredictions = false; performSearch()" class="px-4 py-2 hover:bg-teal-50 cursor-pointer text-sm text-teal-700" x-text="foodItem.name"></li>
                                    </template>
                                </template>
                            </ul>
                        </template>
                        <template x-if="tab === 'merch' && merchSearchInput && showMerchPredictions">
                            <ul class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-48 overflow-y-auto">
                                <template x-for="item in merchandise" :key="item.id">
                                    <template x-if="item.name.toLowerCase().includes(merchSearchInput.toLowerCase())">
                                        <li @mousedown.prevent="merchSearchInput = item.name; showMerchPredictions = false; performSearch()" class="px-4 py-2 hover:bg-teal-50 cursor-pointer text-sm text-teal-700" x-text="item.name"></li>
                                    </template>
                                </template>
                            </ul>
                        </template>
                    </div>

                    <!-- Right: Admin Controls & Sort Dropdown -->
                    <div class="flex items-center gap-4 flex-shrink-0">
                        @if(auth()->user()?->role === 'admin')
                        <button @click="showAddModal = true" class="bg-white text-teal-700 px-6 py-2 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-teal-200 hover:border-teal-300">
                            Add Product
                        </button>
                        @endif
                        
                        <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-white">Sort:</label>
                        <div class="rounded border border-white px-3 py-1.5 bg-white">
                            <template x-if="tab === 'food'">
                                <select x-model="foodSort" class="bg-transparent outline-none border-none text-teal-700 font-medium text-sm cursor-pointer">
                                    <option value="">Default</option>
                                    <option value="name">Name (A-Z)</option>
                                    <option value="category">Category</option>
                                    <option value="rating">Rating</option>
                                </select>
                            </template>
                            <template x-if="tab === 'merch'">
                                <select x-model="merchSort" class="bg-transparent outline-none border-none text-teal-700 font-medium text-sm cursor-pointer">
                                    <option value="">Default</option>
                                    <option value="name">Name (A-Z)</option>
                                    <option value="category">Category</option>
                                    <option value="rating">Rating</option>
                                </select>
                            </template>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Third Row: Category Filters -->
                <div class="mt-3 pt-3 border-t border-teal-500">
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <template x-if="tab === 'food'">
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="foodFilter = 'All'" :class="foodFilter === 'All' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">All</button>
                                @foreach ($foodCategories as $cat)
                                <button type="button" @click="foodFilter = '{{ $cat }}'" :class="foodFilter === '{{ $cat }}' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">{{ $cat }}</button>
                                @endforeach
                            </div>
                        </template>
                        <template x-if="tab === 'merch'">
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="merchFilter = 'All'" :class="merchFilter === 'All' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">All</button>
                                @foreach ($merchCategories as $cat)
                                <button type="button" @click="merchFilter = '{{ $cat }}'" :class="merchFilter === '{{ $cat }}' ? 'bg-white text-teal-700' : 'bg-teal-700 text-white border border-white'" class="px-3 py-1 rounded-full font-medium text-sm hover:bg-white hover:text-teal-700 transition">{{ $cat }}</button>
                                @endforeach
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Admin Modals and Loading Overlays -->
    @if(auth()->user()?->role === 'admin')
    <!-- Add Product Modal -->
    <div x-show="showAddModal" data-initial-hidden class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 px-4" style="display: none;">
        <form method="POST" action="{{ route('unissa-cafe.products.store') }}" enctype="multipart/form-data"
              class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-6 max-h-[90vh] overflow-y-auto">
            @csrf
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Add New Product</h2>
                    <p class="text-gray-600 mt-1">Create a new product for the catalog</p>
                </div>
                <button type="button" @click="showAddModal = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Form Content -->
            <div class="space-y-6">
                <!-- Two Column Grid for Main Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <input type="text" name="category" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Type</option>
                            <option value="food">Food</option>
                            <option value="merch">Merchandise</option>
                        </select>
                    </div>
                    
                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="price" step="0.01" min="0" required
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="active">Available</option>
                            <option value="inactive">Inactive</option>
                            <option value="out_of_stock">Out of Stock</option>
                            <option value="discontinued">Discontinued</option>
                        </select>
                    </div>
                    
                    <!-- Low Stock Threshold -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Low Stock Threshold *</label>
                        <input type="number" name="low_stock_threshold" value="10" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>
                
                <!-- Description - Full Width -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="desc" required rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"></textarea>
                </div>
                
                <!-- Image Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Product Image *</label>
                    
                    <!-- Image Upload -->
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="add-image-input" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                    <span>Upload an image</span>
                                    <input id="add-image-input" type="file" accept="image/*" class="sr-only" required
                                           x-on:change="initAddCropper($event)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    
                    <!-- Image Cropper Container -->
                    <div id="add-cropper-container" class="hidden mb-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                            <div class="cropper-wrapper" style="max-height: 400px; overflow: hidden;">
                                <img id="add-cropper-image" class="max-w-full block mx-auto" style="max-height: 350px;">
                            </div>
                        </div>
                        <div class="flex justify-between mt-3">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition-colors" 
                                    onclick="resetAddCropper()">Reset</button>
                            <button type="button" class="bg-teal-600 text-white px-4 py-2 rounded text-sm hover:bg-teal-700 transition-colors" 
                                    onclick="applyCrop('add')">Apply Crop</button>
                        </div>
                    </div>
                    
                    <!-- Preview Container -->
                    <div id="add-preview-container" class="hidden mb-4">
                        <label class="block text-sm font-medium mb-2">Preview as Product Card:</label>
                        <!-- Mini Product Card Preview -->
                        <div class="w-48 bg-white rounded-xl shadow-md overflow-hidden border">
                            <div class="relative overflow-hidden">
                                <img id="add-cropped-preview" class="w-full h-36 object-cover">
                                <div class="absolute top-2 left-2">
                                    <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">New</span>
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-bold text-gray-800 mb-1">New Product</h3>
                                <p class="text-xs text-gray-600">Product preview</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden input for cropped image data -->
                    <input type="hidden" name="cropped_image" id="add-cropped-data">
                </div>
                
                <!-- Stock Management Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Management</h3>
                    
                    <div class="space-y-4">
                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="add_is_active" value="1" checked
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="add_is_active" class="ml-2 block text-sm text-gray-900">Product is active</label>
                        </div>

                        <!-- Track Stock -->
                        <div class="flex items-center">
                            <input type="checkbox" name="track_stock" id="add_track_stock" value="1" checked
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                                   onchange="toggleAddStockField()">
                            <label for="add_track_stock" class="ml-2 block text-sm text-gray-900">Track stock quantity</label>
                        </div>

                        <!-- Stock Quantity (shows by default since track_stock is checked) -->
                        <div id="add_stock_quantity_field">
                            <label for="add_stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Initial Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="add_stock_quantity" 
                                   value="0" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <p class="mt-1 text-sm text-gray-600">Set the initial stock quantity for this product.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <button type="button" @click="showAddModal = false" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Add Product
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- Edit Product Modal -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <div x-show="showEditModal" data-initial-hidden class="fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 px-4" style="display: none;">
        <form method="POST" :action="`/catalog/edit/${editingProduct?.id || ''}`" enctype="multipart/form-data"
              @submit="showEditModal = false" 
              class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-6 max-h-[90vh] overflow-y-auto">
            @csrf
            @method('PUT')
            <!-- Hidden inputs to remember state -->
            <input type="hidden" name="return_tab" :value="editingProduct?.type || 'food'">
            <input type="hidden" name="product_id" :value="editingProduct?.id || ''">
            
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Product</h2>
                    <p class="text-gray-600 mt-1" x-text="`Update ${editingProduct?.name || 'product'} details`"></p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Form Content -->
            <div class="space-y-6">
                <!-- Two Column Grid for Main Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="name" :value="editingProduct?.name || ''" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <input type="text" name="category" :value="editingProduct?.category || ''" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Type</option>
                            <option value="food" :selected="editingProduct?.type === 'food'">Food</option>
                            <option value="merch" :selected="editingProduct?.type === 'merch'">Merchandise</option>
                        </select>
                    </div>
                    
                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="price" :value="editingProduct?.price || ''" step="0.01" min="0" required
                                   class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="active" :selected="editingProduct?.status === 'active'">Available</option>
                            <option value="inactive" :selected="editingProduct?.status === 'inactive'">Inactive</option>
                            <option value="out_of_stock" :selected="editingProduct?.status === 'out_of_stock'">Out of Stock</option>
                            <option value="discontinued" :selected="editingProduct?.status === 'discontinued'">Discontinued</option>
                        </select>
                    </div>
                    
                    <!-- Low Stock Threshold -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Low Stock Threshold *</label>
                        <input type="number" name="low_stock_threshold" :value="editingProduct?.low_stock_threshold || '10'" min="0" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>
                
                <!-- Description - Full Width -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="desc" :value="editingProduct?.desc || ''" required rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"></textarea>
                </div>
                
                <!-- Image Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Product Image</label>
                    
                    <!-- Current Image Display -->
                    <div x-show="editingProduct?.img" class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img :src="editingProduct?.img" :alt="editingProduct?.name" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                    </div>
                    
                    <!-- New Image Upload -->
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="edit-image-input" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                    <span>Replace image</span>
                                    <input id="edit-image-input" type="file" accept="image/*" class="sr-only"
                                           x-on:change="initEditCropper($event)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    
                    <!-- Image Cropper Container -->
                    <div id="edit-cropper-container" class="hidden mb-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                            <div class="cropper-wrapper" style="max-height: 400px; overflow: hidden;">
                                <img id="edit-cropper-image" class="max-w-full block mx-auto" style="max-height: 350px;">
                            </div>
                        </div>
                        <div class="flex justify-between mt-3">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition-colors" 
                                    onclick="resetEditCropper()">Reset</button>
                            <button type="button" class="bg-teal-600 text-white px-4 py-2 rounded text-sm hover:bg-teal-700 transition-colors" 
                                    onclick="applyCrop('edit')">Apply Crop</button>
                        </div>
                    </div>
                    
                    <!-- Preview Container -->
                    <div id="edit-preview-container" class="hidden mb-4">
                        <label class="block text-sm font-medium mb-2">Preview as Product Card:</label>
                        <!-- Mini Product Card Preview -->
                        <div class="w-48 bg-white rounded-xl shadow-md overflow-hidden border">
                            <div class="relative overflow-hidden">
                                <img id="edit-cropped-preview" class="w-full h-36 object-cover">
                                <div class="absolute top-2 left-2">
                                    <span class="bg-teal-500 text-white text-xs font-bold px-2 py-1 rounded-full">Updated</span>
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-bold text-gray-800 mb-1" x-text="editingProduct?.name || 'Product Name'">Product Name</h3>
                                <p class="text-xs text-gray-600">Updated product preview</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden input for cropped image data -->
                    <input type="hidden" name="cropped_image" id="edit-cropped-data">
                </div>
                
                <!-- Stock Management Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Management</h3>
                    
                    <div class="space-y-4">
                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" 
                                   :checked="editingProduct?.is_active == 1"
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="edit_is_active" class="ml-2 block text-sm text-gray-900">Product is active</label>
                        </div>

                        <!-- Track Stock -->
                        <div class="flex items-center">
                            <input type="checkbox" name="track_stock" id="edit_track_stock" value="1"
                                   :checked="editingProduct?.track_stock == 1"
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded"
                                   onchange="toggleEditStockField()">
                            <label for="edit_track_stock" class="ml-2 block text-sm text-gray-900">Track stock quantity</label>
                        </div>

                        <!-- Current Stock Information (if tracking is enabled) -->
                        <div x-show="editingProduct?.track_stock == 1" class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-teal-900" x-text="`Current Stock: ${editingProduct?.stock_quantity || 0}`"></p>
                                    <p class="text-xs text-teal-700" x-text="`Last updated: ${editingProduct?.last_restocked_at || 'Never'}`"></p>
                                </div>
                                <div>
                                    <template x-if="editingProduct?.stock_quantity <= editingProduct?.low_stock_threshold && editingProduct?.stock_quantity > 0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Low Stock
                                        </span>
                                    </template>
                                    <template x-if="editingProduct?.stock_quantity <= 0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Quantity (only shows if tracking is enabled) -->
                        <div id="edit_stock_quantity_field" :class="editingProduct?.track_stock == 1 ? '' : 'hidden'">
                            <label for="edit_stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity</label>
                            <input type="number" name="stock_quantity" id="edit_stock_quantity" 
                                   :value="editingProduct?.stock_quantity || '0'" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <p class="mt-1 text-sm text-gray-600">Use the stock management tools for detailed stock operations.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <button type="button" @click="showEditModal = false" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Update Product
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- Loading Overlay -->
    <div x-show="isLoading" data-initial-hidden class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-40" style="display: none;">
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
            <div class="rounded-full h-12 w-12 border-4 border-teal-200 border-t-teal-600 mb-4"></div>
            <p class="text-gray-600 font-medium">Switching catalog...</p>
        </div>
    </div>

    <!-- Content Container with Animation -->
    <div class="relative min-h-screen bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-50 pt-8">
        <!-- Food Cards -->
        <template x-if="tab === 'food'">
            <div class="tab-content animate-fade-in">
            <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 px-4 md:px-8 mb-20">
                <template x-for="food in pagedFoods" :key="food.id">
                    <div class="bg-white rounded-xl md:rounded-3xl shadow-md md:shadow-2xl hover:shadow-lg md:hover:shadow-3xl border border-teal-100 hover:border-teal-200 transition-all duration-300 overflow-hidden group cursor-pointer food-card transform hover:-translate-y-1 md:hover:-translate-y-2 flex flex-col h-full" style="margin-bottom: 12px !important;"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         :data-product-id="food.id"
                         @click="navigateToReview(food.id)">
                        <div class="relative overflow-hidden">
                            <img :src="food.img" :alt="food.name" class="w-full object-cover md:object-cover object-contain group-hover:scale-110 transition-transform duration-300" style="height: 180px !important; max-height: 180px !important; object-position: center !important;">
                            <div class="absolute top-2 md:top-4 left-2 md:left-4">
                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full" x-text="food.category"></span>
                            </div>
                            <template x-if="food.calculated_rating && food.calculated_rating > 0">
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-800" x-text="food.calculated_rating">0</span>
                                </div>
                            </template>
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute bottom-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button @click.stop="editProduct(food)" class="w-12 h-6 bg-teal-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-teal-700 transition-colors">
                                    Edit
                                </button>
                                <button @click.stop="deleteProduct(food.id)" class="w-12 h-6 bg-red-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-red-700 transition-colors">
                                    Del
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="p-2.5 md:p-6 flex flex-col flex-grow">
                            <h4 class="text-sm md:text-lg font-bold text-gray-900 mb-1 md:mb-2 group-hover:text-teal-600 transition-colors" x-text="food.name"></h4>
                            <p class="text-gray-600 text-xs md:text-sm mb-2 md:mb-4 line-clamp-1 md:line-clamp-2 flex-grow" x-text="food.desc"></p>
                            <template x-if="food.price">
                                <div class="mt-auto">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-lg font-bold text-teal-600">$<span x-text="parseFloat(food.price).toFixed(2)"></span></span>
                                    </div>
                                    <button @click.stop="addToCart(food.id, food.name, food.price)" class="w-full bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl" style="padding: 10px !important; font-size: 14px !important; border-radius: 8px !important; font-weight: 600 !important;">
                                        Add to Cart
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <!-- Food Pagination -->
            <div class="flex justify-center mb-12" x-show="totalFoodPages > 1">
                <nav class="flex items-center space-x-2">
                    <button @click="currentFoodPage > 1 && setFoodPage(currentFoodPage - 1)"
                            :disabled="currentFoodPage <= 1"
                            :class="currentFoodPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700'"
                            class="px-4 py-2 rounded-2xl bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 text-white font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        Previous
                    </button>
                    <template x-for="page in Array.from({length: totalFoodPages}, (_, i) => i + 1)" :key="page">
                        <button @click="setFoodPage(page)"
                                :class="page === currentFoodPage ? 'bg-gradient-to-r from-teal-700 via-emerald-700 to-cyan-700 text-white shadow-xl' : 'bg-white text-teal-600 border border-teal-200 hover:bg-gradient-to-r hover:from-teal-50 hover:via-emerald-50 hover:to-cyan-50'"
                                class="px-4 py-2 rounded-2xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                x-text="page">
                        </button>
                    </template>
                    <button @click="currentFoodPage < totalFoodPages && setFoodPage(currentFoodPage + 1)"
                            :disabled="currentFoodPage >= totalFoodPages"
                            :class="currentFoodPage >= totalFoodPages ? 'opacity-50 cursor-not-allowed' : 'hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700'"
                            class="px-4 py-2 rounded-2xl bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 text-white font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        Next
                    </button>
                </nav>
            </div>
            </div>
        </template>

        <!-- Merchandise Cards -->
        <template x-if="tab === 'merch'">
            <div class="tab-content animate-fade-in">
            <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 px-4 md:px-8 mb-20">
                <template x-for="merch in pagedMerch" :key="merch.id">
                    <div class="bg-white rounded-xl md:rounded-3xl shadow-md md:shadow-2xl hover:shadow-lg md:hover:shadow-3xl border border-teal-100 hover:border-teal-200 transition-all duration-300 overflow-hidden group cursor-pointer merch-card transform hover:-translate-y-1 md:hover:-translate-y-2 flex flex-col h-full" style="margin-bottom: 12px !important;"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         :data-product-id="merch.id"
                         @click="navigateToReview(merch.id)">
                        <div class="relative overflow-hidden">
                            <img :src="merch.img" :alt="merch.name" class="w-full object-cover md:object-cover object-contain group-hover:scale-110 transition-transform duration-300" style="height: 180px !important; max-height: 180px !important; object-position: center !important;">
                            <div class="absolute top-2 md:top-4 left-2 md:left-4">
                                <span class="bg-teal-500 text-white text-xs font-bold px-2 py-1 rounded-full" x-text="merch.category"></span>
                            </div>
                            <template x-if="merch.calculated_rating && merch.calculated_rating > 0">
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-800" x-text="merch.calculated_rating">0</span>
                                </div>
                            </template>
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute bottom-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button @click.stop="editProduct(merch)" class="w-12 h-6 bg-teal-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-teal-700 transition-colors">
                                    Edit
                                </button>
                                <button @click.stop="deleteProduct(merch.id)" class="w-12 h-6 bg-red-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-red-700 transition-colors">
                                    Del
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="p-2.5 md:p-6 flex flex-col flex-grow">
                            <h4 class="text-sm md:text-lg font-bold text-gray-900 mb-1 md:mb-2 group-hover:text-teal-600 transition-colors" x-text="merch.name"></h4>
                            <p class="text-gray-600 text-xs md:text-sm mb-2 md:mb-4 line-clamp-1 md:line-clamp-2 flex-grow" x-text="merch.desc"></p>
                            <template x-if="merch.price">
                                <div class="mt-auto">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-lg font-bold text-teal-600">$<span x-text="parseFloat(merch.price).toFixed(2)"></span></span>
                                    </div>
                                    <button @click.stop="addToCart(merch.id, merch.name, merch.price)" class="w-full bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl" style="padding: 10px !important; font-size: 14px !important; border-radius: 8px !important; font-weight: 600 !important;">
                                        Add to Cart
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <!-- Merchandise Pagination -->
            <div class="flex justify-center mb-12" x-show="totalMerchPages > 1">
                <nav class="flex items-center space-x-2">
                    <button @click="currentMerchPage > 1 && setMerchPage(currentMerchPage - 1)"
                            :disabled="currentMerchPage <= 1"
                            :class="currentMerchPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700'"
                            class="px-4 py-2 rounded-2xl bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 text-white font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        Previous
                    </button>
                    <template x-for="page in Array.from({length: totalMerchPages}, (_, i) => i + 1)" :key="page">
                        <button @click="setMerchPage(page)"
                                :class="page === currentMerchPage ? 'bg-gradient-to-r from-teal-700 via-emerald-700 to-cyan-700 text-white shadow-xl' : 'bg-white text-teal-600 border border-teal-200 hover:bg-gradient-to-r hover:from-teal-50 hover:via-emerald-50 hover:to-cyan-50'"
                                class="px-4 py-2 rounded-2xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                                x-text="page">
                        </button>
                    </template>
                    <button @click="currentMerchPage < totalMerchPages && setMerchPage(currentMerchPage + 1)"
                            :disabled="currentMerchPage >= totalMerchPages"
                            :class="currentMerchPage >= totalMerchPages ? 'opacity-50 cursor-not-allowed' : 'hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700'"
                            class="px-4 py-2 rounded-2xl bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 text-white font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        Next
                    </button>
                </nav>
            </div>
            </div>
        </template>
    </div>
</div>

{{-- Alpine.js CDN removed. Use main layout for scripts. --}}

@push('scripts')
<script>
window.__productBrowse = {
    food: @json($food),
    merchandise: @json($merchandise),
    activeTab: @json(session('active_tab') ?? null),
    highlightProduct: @json(session('highlight_product') ?? null)
};

// Ensure Alpine component is visible after initialization
document.addEventListener('alpine:init', () => {
});

document.addEventListener('alpine:initialized', () => {
    console.log('🚀 Alpine fully initialized');
    
    // Make sure the browse component is visible
    const browseComponent = document.querySelector('.alpine-component');
    if (browseComponent) {
        browseComponent.classList.add('alpine-initialized');
        browseComponent.style.opacity = '1';
        browseComponent.style.display = 'block';
    }
});

// Ensure immediate visibility - no waiting for Alpine
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 Browse page DOM loaded');
    
    // Show content immediately
    const browseComponent = document.getElementById('browse-container');
    if (browseComponent) {
        browseComponent.style.opacity = '1';
        browseComponent.style.display = 'block';
        browseComponent.classList.add('alpine-initialized');
    }
});
</script>
<script src="/js/product-list.js"></script>
@endpush

<style>
/* Custom Cropper.js styling */
.cropper-wrapper .cropper-container {
    max-height: 350px !important;
}

.cropper-wrapper .cropper-canvas {
    max-height: 350px !important;
}

.cropper-view-box {
    outline: 2px solid #0d9488 !important;
    outline-color: rgba(13, 148, 136, 0.7) !important;
}

.cropper-face {
    background-color: rgba(13, 148, 136, 0.1) !important;
}

.cropper-line,
.cropper-point {
    background-color: #0d9488 !important;
}

.cropper-point {
    width: 8px !important;
    height: 8px !important;
    border-radius: 50% !important;
    border: 2px solid #ffffff !important;
}

.cropper-dashed {
    border-color: rgba(13, 148, 136, 0.5) !important;
}

.cropper-modal {
    background-color: rgba(0, 0, 0, 0.4) !important;
}

/* Tab content transition fixes */
.tab-content {
    will-change: opacity;
    backface-visibility: hidden;
    transform: translateZ(0);
}

/* Fade-in animation for tab content */
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Fix for Alpine.js component display */
.alpine-component[x-cloak] {
    display: none !important;
}

.alpine-component {
    opacity: 1 !important;
}

/* Override global Alpine CSS that might be hiding content */
[x-data].alpine-component {
    opacity: 1 !important;
}

.alpine-component.alpine-initialized {
    opacity: 1 !important;
}

/* Mobile-specific fixes for browse page */
@media (max-width: 768px) {
    /* Page container mobile optimization */
    .browse-container {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    /* Force single column layout on mobile */
    .max-w-6xl.mx-auto.grid {
        grid-template-columns: 1fr !important;
        padding: 0 1rem !important;
        gap: 1.5rem !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
    
    /* Product card mobile optimization */
    .food-card, .merch-card {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        display: flex !important;
        flex-direction: column !important;
        height: 100% !important;
    }
    
    /* Image container fixes */
    .food-card .relative.overflow-hidden,
    .merch-card .relative.overflow-hidden {
        height: 220px !important;
        overflow: hidden !important;
        border-radius: 0.75rem 0.75rem 0 0 !important;
    }
    
    .food-card img, .merch-card img {
        width: 100% !important;
        height: 220px !important;
        object-fit: cover !important;
        object-position: center !important;
    }
    
    /* Card content padding adjustments */
    .food-card .p-6, .merch-card .p-6 {
        padding: 1rem !important;
        display: flex !important;
        flex-direction: column !important;
        flex-grow: 1 !important;
    }
    
    /* Price and button spacing */
    .food-card .space-y-4, .merch-card .space-y-4 {
        gap: 1rem !important;
    }
    
    /* Hero banner mobile fixes */
    .hero-banner {
        height: 250px !important;
        margin-bottom: 1.5rem !important;
    }
    
    .hero-banner img {
        height: 250px !important;
        width: 100% !important;
        object-fit: cover !important;
        object-position: center !important;
    }
    
    /* Tab navigation mobile optimization */
    .tab-navigation {
        padding: 0 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    .tab-button {
        padding: 0.75rem 1rem !important;
        font-size: 0.875rem !important;
        min-height: 44px !important;
    }
    
    /* Tab content spacing */
    .tab-content {
        padding: 1rem 0 !important;
    }
    
    /* Filter and search mobile improvements */
    .filter-controls {
        flex-direction: column !important;
        gap: 0.5rem !important;
        padding: 0 1rem !important;
        margin-bottom: 1rem !important;
    }
    
    .search-bar {
        width: 100% !important;
        margin-bottom: 0.5rem !important;
    }
    
    .filter-buttons {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
        justify-content: center !important;
    }
    
    /* Button sizing for mobile */
    .food-card button, .merch-card button {
        min-height: 48px !important;
        font-size: 1rem !important;
        padding: 0.75rem 1rem !important;
        width: 100% !important;
    }
    
    /* Rating display mobile optimization */
    .rating-display {
        font-size: 0.875rem !important;
    }
    
    /* Category badge mobile sizing */
    .category-badge {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
    }
    
    /* Price display mobile optimization */
    .price-display {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
    }
    
    /* Pagination mobile fixes */
    .pagination-container {
        padding: 1rem !important;
        justify-content: center !important;
    }
    
    .pagination-btn {
        min-height: 44px !important;
        min-width: 44px !important;
        font-size: 0.875rem !important;
    }
    
    /* Admin buttons mobile optimization */
    .admin-controls {
        gap: 0.5rem !important;
    }
    
    .admin-btn {
        width: 36px !important;
        height: 36px !important;
    }
    
    /* Loading states mobile optimization */
    .loading-spinner {
        margin: 2rem auto !important;
    }
    
    /* Empty state mobile fixes */
    .empty-state {
        padding: 2rem 1rem !important;
        text-align: center !important;
    }
    
    /* Force mobile product card styles */
    @media screen and (max-width: 767px) {
        .food-card, .merch-card {
            margin-bottom: 0.75rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            border-radius: 0.75rem !important;
            transform: none !important;
            display: flex !important;
            flex-direction: column !important;
            height: auto !important;
        }
        
        .food-card:hover, .merch-card:hover {
            transform: translateY(-2px) !important;
        }
        
        .food-card img, .merch-card img {
            height: 9rem !important; /* h-36 = 144px */
        }
        
        .food-card > div:last-child, .merch-card > div:last-child {
            padding: 0.625rem !important;
            display: flex !important;
            flex-direction: column !important;
            flex-grow: 1 !important;
        }
        
        .food-card h4, .merch-card h4 {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            margin-bottom: 0.25rem !important;
        }
        
        .food-card p, .merch-card p {
            font-size: 0.75rem !important;
            line-height: 1rem !important;
            margin-bottom: 0.5rem !important;
            overflow: hidden !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 1 !important;
            -webkit-box-orient: vertical !important;
            flex-grow: 1 !important;
        }
        
        .food-card button, .merch-card button {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.375rem !important;
            flex-shrink: 0 !important;
            margin-top: auto !important;
        }
        
        .food-card span[class*="text-"], .merch-card span[class*="text-"] {
            font-size: 1rem !important;
        }
        
        /* Grid adjustments */
        .max-w-6xl.mx-auto.grid {
            gap: 0.75rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
    }
}
</style>
@endsection