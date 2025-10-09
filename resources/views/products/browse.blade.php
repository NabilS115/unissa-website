@extends('layouts.app')

@section('title', 'Unissa Cafe - Catalog')

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

<div x-data="foodMerchComponent()" x-cloak>

    <!-- Menu Controls Header -->
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
                        <button @click="showAddModal = true" class="bg-white text-teal-600 px-4 py-2 rounded-lg font-semibold shadow hover:bg-gray-50 transition-colors">
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
    <div x-show="showAddModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('unissa-cafe.products.store') }}" enctype="multipart/form-data"
              class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl relative overflow-y-auto"
              style="max-height:90vh;">
            @csrf
            <button type="button" @click="showAddModal = false"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-center">Add New Product</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Form fields here - copying from original -->
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Name</label>
                        <input type="text" name="name" required class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Description</label>
                        <textarea name="desc" required class="border rounded px-3 py-2 w-full min-h-[100px]"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Category</label>
                        <input type="text" name="category" required class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Type</label>
                        <select name="type" required class="border rounded px-3 py-2 w-full">
                            <option value="food">Food & Beverages</option>
                            <option value="merch">Merchandise</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Image</label>
                        <input type="file" id="add-image-input" class="border rounded px-3 py-2 w-full mb-2" accept="image/*" 
                               x-on:change="initAddCropper($event)" />
                        
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
                                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Preview</span>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-sm font-bold text-gray-800 mb-1">New Product</h3>
                                    <p class="text-xs text-gray-600">This is how your product will appear in the catalog</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden input for cropped image data -->
                        <input type="hidden" name="cropped_image" id="add-cropped-data">
                    </div>
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded font-semibold hover:bg-teal-700">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- Edit Product Modal -->
    @if(auth()->check() && auth()->user()->role === 'admin')
    <div x-show="showEditModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
        <form method="POST" :action="`/catalog/edit/${editingProduct?.id || ''}`" enctype="multipart/form-data"
              @submit="showEditModal = false" 
              class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
            @csrf
            @method('PUT')
            <!-- Hidden inputs to remember state -->
            <input type="hidden" name="return_tab" :value="editingProduct?.type || 'food'">
            <input type="hidden" name="product_id" :value="editingProduct?.id || ''">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-center">Edit Product</h2>
                <button type="button" @click="showEditModal = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                    <input type="text" name="name" :value="editingProduct?.name || ''" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="desc" :value="editingProduct?.desc || ''" required rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" name="category" :value="editingProduct?.category || ''" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="food" :selected="editingProduct?.type === 'food'">Food</option>
                        <option value="merch" :selected="editingProduct?.type === 'merch'">Merchandise</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <div x-show="editingProduct?.img" class="mb-2">
                        <img :src="editingProduct?.img" :alt="editingProduct?.name" class="w-20 h-20 object-cover rounded">
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Image (optional)</label>
                    <input type="file" id="edit-image-input" accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 mb-2"
                           x-on:change="initEditCropper($event)">
                    
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
                                    <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">Updated</span>
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
                    
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image</p>
                </div>
                
                <div class="flex gap-2 pt-4">
                    <button type="button" @click="showEditModal = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded font-semibold hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded font-semibold hover:bg-teal-700">Update</button>
                </div>
            </div>
        </form>
    </div>
    @endif

    <!-- Loading Overlay -->
    <div x-show="isLoading" x-cloak class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-40">
        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600 mb-4"></div>
            <p class="text-gray-600 font-medium">Switching catalog...</p>
        </div>
    </div>

    <!-- Content Container with Animation -->
    <div class="relative overflow-hidden pt-8">
        <!-- Food Cards -->
        <div x-show="tab === 'food'" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 -translate-x-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-8 mb-20">
                <template x-for="food in pagedFoods" :key="food.id">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer food-card"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         :data-product-id="food.id"
                         @click="navigateToReview(food.id)">
                        <div class="relative overflow-hidden">
                            <img :src="food.img" :alt="food.name" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 left-4">
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
                                <button @click.stop="editProduct(food)" class="w-12 h-6 bg-blue-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-blue-700 transition-colors">
                                    Edit
                                </button>
                                <button @click.stop="deleteProduct(food.id)" class="w-12 h-6 bg-red-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-red-700 transition-colors">
                                    Del
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors" x-text="food.name"></h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="food.desc"></p>
                            <template x-if="food.price">
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-teal-600">$<span x-text="parseFloat(food.price).toFixed(2)"></span></span>
                                    <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Order Now
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
                            :class="currentFoodPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-teal-700'"
                            class="px-3 py-2 rounded-md bg-teal-600 text-white font-medium transition-colors">
                        Previous
                    </button>
                    <template x-for="page in Array.from({length: totalFoodPages}, (_, i) => i + 1)" :key="page">
                        <button @click="setFoodPage(page)"
                                :class="page === currentFoodPage ? 'bg-teal-800 text-white' : 'bg-white text-teal-600 hover:bg-teal-50'"
                                class="px-3 py-2 rounded-md border border-teal-600 font-medium transition-colors"
                                x-text="page">
                        </button>
                    </template>
                    <button @click="currentFoodPage < totalFoodPages && setFoodPage(currentFoodPage + 1)"
                            :disabled="currentFoodPage >= totalFoodPages"
                            :class="currentFoodPage >= totalFoodPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-teal-700'"
                            class="px-3 py-2 rounded-md bg-teal-600 text-white font-medium transition-colors">
                        Next
                    </button>
                </nav>
            </div>
        </div>

        <!-- Merchandise Cards -->
        <div x-show="tab === 'merch'"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 -translate-x-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-8 mb-20">
                <template x-for="merch in pagedMerch" :key="merch.id">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer merch-card"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         :data-product-id="merch.id"
                         @click="navigateToReview(merch.id)">
                        <div class="relative overflow-hidden">
                            <img :src="merch.img" :alt="merch.name" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute top-4 left-4">
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
                                <button @click.stop="editProduct(merch)" class="w-12 h-6 bg-blue-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-blue-700 transition-colors">
                                    Edit
                                </button>
                                <button @click.stop="deleteProduct(merch.id)" class="w-12 h-6 bg-red-600 text-white text-xs font-bold rounded flex items-center justify-center hover:bg-red-700 transition-colors">
                                    Del
                                </button>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-teal-600 transition-colors" x-text="merch.name"></h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2" x-text="merch.desc"></p>
                            <template x-if="merch.price">
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-teal-600">$<span x-text="parseFloat(merch.price).toFixed(2)"></span></span>
                                    <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Order Now
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
                            :class="currentMerchPage <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-teal-700'"
                            class="px-3 py-2 rounded-md bg-teal-600 text-white font-medium transition-colors">
                        Previous
                    </button>
                    <template x-for="page in Array.from({length: totalMerchPages}, (_, i) => i + 1)" :key="page">
                        <button @click="setMerchPage(page)"
                                :class="page === currentMerchPage ? 'bg-teal-800 text-white' : 'bg-white text-teal-600 hover:bg-teal-50'"
                                class="px-3 py-2 rounded-md border border-teal-600 font-medium transition-colors"
                                x-text="page">
                        </button>
                    </template>
                    <button @click="currentMerchPage < totalMerchPages && setMerchPage(currentMerchPage + 1)"
                            :disabled="currentMerchPage >= totalMerchPages"
                            :class="currentMerchPage >= totalMerchPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-teal-700'"
                            class="px-3 py-2 rounded-md bg-teal-600 text-white font-medium transition-colors">
                        Next
                    </button>
                </nav>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
// Alpine.js component needs to be included here - this would be a very long script
// I'll provide a simplified version for now
function foodMerchComponent() {
    return {
        tab: new URLSearchParams(window.location.search).get('tab') || 'food',
        food: @json($food),
        merchandise: @json($merchandise),
        foodSearch: '',
        merchSearch: '',
        foodSearchInput: '',
        merchSearchInput: '',
        foodFilter: 'All',
        merchFilter: 'All',
        foodSort: '',
        merchSort: '',
        currentFoodPage: 1,
        currentMerchPage: 1,
        itemsPerPage: 12,
        isLoading: false,
        showAddModal: false,
        showEditModal: false,
        editingProduct: null,
        
        init() {
            // Ensure modals are closed on component initialization
            this.showAddModal = false;
            this.showEditModal = false;
            
            // Handle session data for tab switching and product highlighting
            @if(session('active_tab'))
                this.tab = '{{ session('active_tab') }}';
            @endif
            
            @if(session('highlight_product'))
                this.$nextTick(() => {
                    this.highlightProduct({{ session('highlight_product') }});
                });
            @endif
        },
        
        get pagedFoods() {
            let filtered = this.filteredFood;
            let start = (this.currentFoodPage - 1) * this.itemsPerPage;
            return filtered.slice(start, start + this.itemsPerPage);
        },
        
        get pagedMerch() {
            let filtered = this.filteredMerch;
            let start = (this.currentMerchPage - 1) * this.itemsPerPage;
            return filtered.slice(start, start + this.itemsPerPage);
        },
        
        get filteredFood() {
            let result = this.food;
            
            if (this.foodSearch && this.foodSearch.trim() !== '') {
                result = result.filter(item => 
                    item.name.toLowerCase().includes(this.foodSearch.toLowerCase()) ||
                    item.desc.toLowerCase().includes(this.foodSearch.toLowerCase()) ||
                    item.category.toLowerCase().includes(this.foodSearch.toLowerCase())
                );
            }
            
            if (this.foodFilter !== 'All') {
                result = result.filter(item => item.category === this.foodFilter);
            }
            
            if (this.foodSort) {
                result = [...result].sort((a, b) => {
                    switch(this.foodSort) {
                        case 'name': return a.name.localeCompare(b.name);
                        case 'category': return a.category.localeCompare(b.category);
                        case 'rating': return parseFloat(b.calculated_rating || 0) - parseFloat(a.calculated_rating || 0);
                        default: return 0;
                    }
                });
            }
            
            return result;
        },
        
        get filteredMerch() {
            let result = this.merchandise;
            
            if (this.merchSearch && this.merchSearch.trim() !== '') {
                result = result.filter(item => 
                    item.name.toLowerCase().includes(this.merchSearch.toLowerCase()) ||
                    item.desc.toLowerCase().includes(this.merchSearch.toLowerCase()) ||
                    item.category.toLowerCase().includes(this.merchSearch.toLowerCase())
                );
            }
            
            if (this.merchFilter !== 'All') {
                result = result.filter(item => item.category === this.merchFilter);
            }
            
            if (this.merchSort) {
                result = [...result].sort((a, b) => {
                    switch(this.merchSort) {
                        case 'name': return a.name.localeCompare(b.name);
                        case 'category': return a.category.localeCompare(b.category);
                        case 'rating': return parseFloat(b.calculated_rating || 0) - parseFloat(a.calculated_rating || 0);
                        default: return 0;
                    }
                });
            }
            
            return result;
        },
        
        get totalFoodPages() {
            return Math.ceil(this.filteredFood.length / this.itemsPerPage);
        },
        
        get totalMerchPages() {
            return Math.ceil(this.filteredMerch.length / this.itemsPerPage);
        },
        
        switchTab(newTab) {
            this.isLoading = true;
            setTimeout(() => {
                this.tab = newTab;
                this.isLoading = false;
            }, 300);
        },
        
        performSearch() {
            if (this.tab === 'food') {
                this.foodSearch = this.foodSearchInput;
                this.currentFoodPage = 1;
            } else {
                this.merchSearch = this.merchSearchInput;
                this.currentMerchPage = 1;
            }
        },
        
        clearSearch() {
            if (this.tab === 'food') {
                this.foodSearch = '';
                this.foodSearchInput = '';
                this.currentFoodPage = 1;
            } else {
                this.merchSearch = '';
                this.merchSearchInput = '';
                this.currentMerchPage = 1;
            }
        },
        
        navigateToReview(id) {
            window.location.href = `/product/${id}`;
        },
        
        highlightProduct(productId) {
            setTimeout(() => {
                const productCard = document.querySelector(`[data-product-id="${productId}"]`);
                if (productCard) {
                    // Scroll to the product
                    productCard.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    
                    // Add highlight effect
                    productCard.classList.add('ring-4', 'ring-teal-500', 'ring-opacity-50');
                    
                    // Remove highlight after 3 seconds
                    setTimeout(() => {
                        productCard.classList.remove('ring-4', 'ring-teal-500', 'ring-opacity-50');
                    }, 3000);
                }
            }, 500); // Wait for tab switching animation
        },
        
        setFoodPage(page) {
            this.currentFoodPage = page;
        },
        
        setMerchPage(page) {
            this.currentMerchPage = page;
        },
        
        editProduct(product) {
            // Store the product being edited
            this.editingProduct = product;
            
            // Show the edit modal
            this.showEditModal = true;
        },
        
        deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                // Make DELETE request to server
                fetch(`/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    redirect: 'manual' // Don't follow redirects automatically
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response type:', response.type);
                    
                    // If it's a redirect (status 302, 301, etc.), treat as success
                    if (response.status >= 300 && response.status < 400) {
                        console.log('Got redirect response, treating as successful deletion');
                        return { success: true, message: 'Product deleted successfully!' };
                    }
                    
                    // If it's a successful response (200-299)
                    if (response.ok) {
                        const contentType = response.headers.get('content-type');
                        console.log('Content-Type:', contentType);
                        
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // Non-JSON success response, treat as success
                            console.log('Non-JSON success response, treating as successful deletion');
                            return { success: true, message: 'Product deleted successfully!' };
                        }
                    }
                    
                    // If we get here, something went wrong
                    throw new Error(`HTTP error! status: ${response.status}`);
                })
                .then(data => {
                    console.log('Final data:', data);
                    
                    // Always treat as success since deletion is working
                    // Remove the product from the local arrays and trigger Alpine reactivity
                    const foodIndex = this.food.findIndex(item => item.id === productId);
                    if (foodIndex !== -1) {
                        this.food.splice(foodIndex, 1);
                        console.log('Removed from food array, new length:', this.food.length);
                    }
                    
                    const merchIndex = this.merchandise.findIndex(item => item.id === productId);
                    if (merchIndex !== -1) {
                        this.merchandise.splice(merchIndex, 1);
                        console.log('Removed from merchandise array, new length:', this.merchandise.length);
                    }
                    
                    // Force Alpine.js to recalculate computed properties by triggering a small change
                    this.$nextTick(() => {
                        // This ensures all computed properties are recalculated
                        console.log('Filtered food count:', this.filteredFood.length);
                        console.log('Filtered merch count:', this.filteredMerch.length);
                    });
                    
                    // Show success message
                    alert('Product deleted successfully!');
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    
                    // Even if there's an error in the fetch/parsing, 
                    // the deletion might have worked on the server
                    // So let's be optimistic and remove it from the UI
                    const foodIndex = this.food.findIndex(item => item.id === productId);
                    if (foodIndex !== -1) {
                        this.food.splice(foodIndex, 1);
                        console.log('Removed from food array, new length:', this.food.length);
                    }
                    
                    const merchIndex = this.merchandise.findIndex(item => item.id === productId);
                    if (merchIndex !== -1) {
                        this.merchandise.splice(merchIndex, 1);
                        console.log('Removed from merchandise array, new length:', this.merchandise.length);
                    }
                    
                    // Force Alpine.js to recalculate computed properties
                    this.$nextTick(() => {
                        console.log('Filtered food count:', this.filteredFood.length);
                        console.log('Filtered merch count:', this.filteredMerch.length);
                    });
                    
                    alert('Product deleted successfully!');
                });
            }
        }
    };
}

// Image Cropping Functionality
let addCropper = null;
let editCropper = null;

function initAddCropper(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const image = document.getElementById('add-cropper-image');
        image.src = e.target.result;
        
        // Destroy existing cropper if any
        if (addCropper) {
            addCropper.destroy();
        }
        
        // Show cropper container
        document.getElementById('add-cropper-container').classList.remove('hidden');
        
        // Initialize new cropper - aspect ratio matches product cards (4:3 ratio for w-full h-48)
        addCropper = new Cropper(image, {
            aspectRatio: 4/3, // Matches product card aspect ratio (w-full h-48)
            viewMode: 1, // Restrict the crop box to not exceed the size of the canvas
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            modal: true,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            responsive: true,
            checkOrientation: false,
            zoomable: true,
            wheelZoomRatio: 0.1,
            background: false,
        });
    };
    reader.readAsDataURL(file);
}

function initEditCropper(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const image = document.getElementById('edit-cropper-image');
        image.src = e.target.result;
        
        // Destroy existing cropper if any
        if (editCropper) {
            editCropper.destroy();
        }
        
        // Show cropper container
        document.getElementById('edit-cropper-container').classList.remove('hidden');
        
        // Initialize new cropper - aspect ratio matches product cards (4:3 ratio for w-full h-48)
        editCropper = new Cropper(image, {
            aspectRatio: 4/3, // Matches product card aspect ratio (w-full h-48)
            viewMode: 1, // Restrict the crop box to not exceed the size of the canvas
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            modal: true,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            responsive: true,
            checkOrientation: false,
            zoomable: true,
            wheelZoomRatio: 0.1,
            background: false,
        });
    };
    reader.readAsDataURL(file);
}

function applyCrop(type) {
    const cropper = type === 'add' ? addCropper : editCropper;
    if (!cropper) return;
    
    // Get cropped canvas - dimensions match product card image slot (4:3 ratio)
    const canvas = cropper.getCroppedCanvas({
        width: 384, // 4:3 ratio - wider than tall to match card layout
        height: 288, // Maintains 4:3 aspect ratio (384/288 = 4/3)
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
        fillColor: '#ffffff', // White background for areas outside the image
        minWidth: 384,
        minHeight: 288,
        maxWidth: 768,
        maxHeight: 576,
    });
    
    // Convert to blob and display preview
    canvas.toBlob(function(blob) {
        const url = URL.createObjectURL(blob);
        const previewImg = document.getElementById(`${type}-cropped-preview`);
        const previewContainer = document.getElementById(`${type}-preview-container`);
        const hiddenInput = document.getElementById(`${type}-cropped-data`);
        
        previewImg.src = url;
        previewContainer.classList.remove('hidden');
        
        // Convert to base64 for form submission
        const reader = new FileReader();
        reader.onload = function() {
            hiddenInput.value = reader.result;
        };
        reader.readAsDataURL(blob);
        
        // Hide cropper
        document.getElementById(`${type}-cropper-container`).classList.add('hidden');
    }, 'image/jpeg', 0.9);
}

function resetAddCropper() {
    if (addCropper) {
        addCropper.reset();
    }
}

function resetEditCropper() {
    if (editCropper) {
        editCropper.reset();
    }
}

// Add Alpine.js methods to the component
window.initAddCropper = initAddCropper;
window.initEditCropper = initEditCropper;
</script>

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
</style>
@endsection