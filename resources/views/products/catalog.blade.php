@extends('layouts.app')

@section('title', 'Catalog')

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
    
    // Calculate average ratings for each product using the same logic as review page
    foreach ($food as &$foodItem) {
        $productId = null;
        
        // Get product ID properly from both arrays and objects
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
            
            // Set the calculated rating
            if (is_object($foodItem)) {
                $foodItem->calculated_rating = number_format($averageRating, 1);
            } else {
                $foodItem['calculated_rating'] = number_format($averageRating, 1);
                // Convert array to object to ensure consistent structure
                $foodItem = (object)$foodItem;
            }
        }
    }
    
    foreach ($merchandise as &$merchItem) {
        $productId = null;
        
        // Get product ID properly from both arrays and objects
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
            
            // Set the calculated rating
            if (is_object($merchItem)) {
                $merchItem->calculated_rating = number_format($averageRating, 1);
            } else {
                $merchItem['calculated_rating'] = number_format($averageRating, 1);
                // Convert array to object to ensure consistent structure
                $merchItem = (object)$merchItem;
            }
        }
    }
    
    // Separate categories for food and merch
    $foodCategories = \App\Models\Product::where('type', 'food')->pluck('category')->unique()->values()->all();
    $merchCategories = \App\Models\Product::where('type', 'merch')->pluck('category')->unique()->values()->all();
@endphp

<div x-data="foodMerchComponent()" x-cloak>
    <!-- Merchandise/Food Banner Section -->
    <div class="w-full flex items-center justify-center mb-8" style="height: 250px;">
        <div class="relative w-full h-full flex items-stretch shadow-lg overflow-hidden"
             style="height: 250px; border-radius: 0 125px 125px 0; margin-right: 32px;">
            <div class="absolute inset-0 w-full h-full"
                 :style="tab === 'merch' 
                    ? 'background: linear-gradient(90deg, #6a7fd1 10%, #e17fc2 50%, #fbbf24 100%); pointer-events:none;' 
                    : 'background: linear-gradient(90deg, #ffbe2f 10%, #6adf7b 100%); pointer-events:none;'">
            </div>
            <div class="flex-1 flex items-center pl-12 z-10" style="width: 55%;">
                <div class="text-left">
                    <h1 class="text-5xl font-extrabold text-white mb-2 drop-shadow-lg" x-show="tab === 'merch'" x-text="'Merchandise'"></h1>
                    <h1 class="text-4xl font-extrabold text-black mb-2 drop-shadow-lg" x-show="tab === 'food'" style="line-height: 1.1;">
                        FOODS & BEVERAGES
                    </h1>
                    <h2 class="text-2xl font-bold text-white mb-2 drop-shadow" x-show="tab === 'merch'" x-text="'Discover Unique Merchandise'"></h2>
                </div>
            </div>
            <img :src="tab === 'merch' ? '/images/merch-banner2.png' : '/nasii-lemak.png'"
                 :alt="tab === 'merch' ? 'Merchandise Banner' : 'Nasi Lemak'"
                 class="object-cover rounded-full z-10"
                 style="height: 200px; width: 200px; position: absolute; right: 30px; top: 50%; transform: translateY(-50%); object-fit: cover;" />
        </div>
    </div>

    <div class="w-full flex justify-center mb-8">
        <div class="inline-flex rounded-lg bg-gray-100 p-1 shadow">
            <button type="button" @click="switchTab('food')" :class="tab === 'food' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition-all duration-200">Food & Beverages</button>
            <button type="button" @click="switchTab('merch')" :class="tab === 'merch' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition-all duration-200">Merchandise</button>
        </div>
    </div>
    <section class="w-full flex flex-col gap-3 px-8 py-4 mb-8">
        <div class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
            <div class="w-full sm:w-1/3 relative">
                <div class="relative">
                    <!-- Food search input -->
                    <input x-show="tab === 'food'" type="text" placeholder="Search food..." x-model="foodSearchInput" @focus="showFoodPredictions = true" @input="showFoodPredictions = foodSearchInput.length > 0" @blur="setTimeout(() => { showFoodPredictions = false; }, 100)" @keyup.enter="performSearch()" class="w-full border border-teal-300 rounded-full px-4 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                    <!-- Merch search input -->
                    <input x-show="tab === 'merch'" type="text" placeholder="Search merchandise..." x-model="merchSearchInput" @focus="showMerchPredictions = true" @input="showMerchPredictions = merchSearchInput.length > 0" @blur="setTimeout(() => { showMerchPredictions = false; }, 100)" @keyup.enter="performSearch()" class="w-full border border-teal-300 rounded-full px-4 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                    <button @click="performSearch()" class="absolute right-8 top-1/2 -translate-y-1/2 p-0 m-0 bg-transparent border-none outline-none flex items-center justify-center" style="height:28px;width:28px;">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                    <!-- Clear button for food tab -->
                    <button x-show="tab === 'food' && (foodSearch || foodSearchInput)" @click="clearSearch()" class="absolute right-1 top-1/2 -translate-y-1/2 p-0 m-0 bg-transparent border-none outline-none flex items-center justify-center" style="height:24px;width:24px;" title="Clear search">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                    <!-- Clear button for merch tab -->
                    <button x-show="tab === 'merch' && (merchSearch || merchSearchInput)" @click="clearSearch()" class="absolute right-1 top-1/2 -translate-y-1/2 p-0 m-0 bg-transparent border-none outline-none flex items-center justify-center" style="height:24px;width:24px;" title="Clear search">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <template x-if="tab === 'food' && foodSearchInput && showFoodPredictions">
                    <ul class="absolute left-0 right-0 mt-2 bg-white border border-teal-200 rounded-b-lg shadow z-20 max-h-48 overflow-y-auto">
                        <template x-for="foodItem in food" :key="foodItem.id">
                            <template x-if="foodItem.name && foodItem.name.toLowerCase().includes(foodSearchInput.toLowerCase())">
                                <li @mousedown.prevent="foodSearchInput = foodItem.name; showFoodPredictions = false; performSearch()" class="px-4 py-2 hover:bg-teal-100 cursor-pointer text-sm" x-text="foodItem.name"></li>
                            </template>
                        </template>
                    </ul>
                </template>
                <template x-if="tab === 'merch' && merchSearchInput && showMerchPredictions">
                    <ul class="absolute left-0 right-0 mt-2 bg-white border border-teal-200 rounded-b-lg shadow z-20 max-h-48 overflow-y-auto">
                        <template x-for="item in merchandise" :key="item.id">
                            <template x-if="item.name.toLowerCase().includes(merchSearchInput.toLowerCase())">
                                <li @mousedown.prevent="merchSearchInput = item.name; showMerchPredictions = false; performSearch()" class="px-4 py-2 hover:bg-indigo-100 cursor-pointer text-sm" x-text="item.name"></li>
                            </template>
                        </template>
                    </ul>
                </template>
            </div>
            <div class="flex-1 flex flex-wrap gap-2 items-center justify-center sm:justify-start overflow-x-auto py-1">
                {{-- Category filter buttons, separated by tab --}}
                <template x-if="tab === 'food'">
                    <div>
                        <!-- Move "All" button to the left -->
                        <button type="button" @click="foodFilter = 'All'"
                            :class="foodFilter === 'All' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-teal-700'"
                            class="px-4 py-1 rounded-full font-semibold text-sm hover:bg-teal-100 transition">All</button>
                        @foreach ($foodCategories as $cat)
                        <button type="button" @click="foodFilter = '{{ $cat }}'"
                            :class="foodFilter === '{{ $cat }}' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-teal-700'"
                            class="px-4 py-1 rounded-full font-semibold text-sm hover:bg-teal-100 transition">{{ $cat }}</button>
                        @endforeach
                    </div>
                </template>
                <template x-if="tab === 'merch'">
                    <div>
                        <!-- Move "All" button to the left -->
                        <button type="button" @click="merchFilter = 'All'"
                            :class="merchFilter === 'All' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-teal-700'"
                            class="px-4 py-1 rounded-full font-semibold text-sm hover:bg-indigo-100 transition">All</button>
                        @foreach ($merchCategories as $cat)
                        <button type="button" @click="merchFilter = '{{ $cat }}'"
                            :class="merchFilter === '{{ $cat }}' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-teal-700'"
                            class="px-4 py-1 rounded-full font-semibold text-sm hover:bg-indigo-100 transition">{{ $cat }}</button>
                        @endforeach
                    </div>
                </template>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-teal-700">Sort by:</label>
                <div class="rounded-md border-2 border-teal-300 px-3 py-1 flex items-center bg-white sort-dropdown-container">
                    <template x-if="tab === 'food'">
                        <select x-model="foodSort"
                            class="bg-transparent outline-none px-2 py-1 rounded-md focus:ring-0 border-none text-teal-700 font-medium cursor-pointer focus:outline-none focus:shadow-none hover:shadow-none"
                            style="box-shadow:none !important; -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important;">
                            <option value="">Default</option>
                            <option value="name">Name (A-Z)</option>
                            <option value="category">Category</option>
                            <option value="rating">Rating (High to Low)</option>
                        </select>
                    </template>
                    <template x-if="tab === 'merch'">
                        <select x-model="merchSort"
                            class="bg-transparent outline-none px-2 py-1 rounded-md focus:ring-0 border-none text-teal-700 font-medium cursor-pointer focus:outline-none focus:shadow-none hover:shadow-none"
                            style="box-shadow:none !important; -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important;">
                            <option value="">Default</option>
                            <option value="name">Name (A-Z)</option>
                            <option value="category">Category</option>
                            <option value="rating">Rating (High to Low)</option>
                        </select>
                    </template>
                </div>
            </div>
        </div>
    </section>
    {{-- Admin features --}}
    @if(auth()->check() && auth()->user()->role === 'admin')
    <!-- Add Product Button -->
    <div class="w-full flex justify-end px-8 mb-4">
        <button @click="showAddModal = true"
            class="bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-teal-700 transition">
            Add Product
        </button>
    </div>
    <!-- Add Product Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <form x-ref="addForm" @submit.prevent="submitAddProduct" enctype="multipart/form-data"
              class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
            @csrf
            <button type="button" @click="showAddModal = false"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Add Product</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="desc" required class="border rounded px-3 py-2 w-full"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Category</label>
                <input type="text" name="category" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Image</label>
                <input type="file" name="img" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Type</label>
                <select name="type" class="border rounded px-3 py-2 w-full">
                    <option value="food">Food & Beverages</option>
                    <option value="merch">Merchandise</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded font-semibold hover:bg-teal-700">
                Add
            </button>
        </form>
    </div>
    <!-- Edit Product Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <form method="POST" :action="editFormAction" enctype="multipart/form-data"
              class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl relative overflow-y-auto"
              style="max-height:90vh;"
              @submit.prevent="handleEditSubmit">
            @csrf
            @method('PUT')
            <button type="button" @click="showEditModal = false"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-center">Edit Product</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left: Image Section -->
                <div class="flex flex-col items-center justify-start">
                    <div class="mb-4 w-full">
                        <label class="block text-sm font-medium mb-2">Current Image</label>
                        <img :src="editProduct.img" :alt="editProduct.name" id="edit-current-img"
                             class="w-full h-48 object-contain rounded bg-gray-100 border" />
                    </div>
                    <div class="mb-4 w-full">
                        <label class="block text-sm font-medium mb-2">Change Image <span class="text-xs text-gray-500">(click & drag to crop)</span></label>
                        <input type="file" name="img" id="edit-img-input" class="border rounded px-3 py-2 w-full" accept="image/*"
                            @change="event => startCrop(event)" />
                        <div x-show="showCropper" id="edit-cropper-preview"
                             class="mt-4 flex flex-col items-center justify-center"
                             style="width:100%;max-width:100%;height:192px;background:#f9fafb;border:2px solid #e2e8f0;border-radius:0.75rem;position:relative;z-index:10;">
                            <div style="width:100%;height:192px;display:flex;align-items:center;justify-content:center;">
                                <img id="edit-cropper-img"
                                     style="max-width:100%;max-height:192px;object-fit:cover;display:block;margin:auto;border-radius:0.75rem;border:1px solid #e2e8f0;background:#fff;" />
                            </div>
                        </div>
                        <div x-show="showCropper" class="flex flex-col items-center justify-center mt-2">
                            <button type="button"
                                    class="mt-2 px-6 py-2 bg-teal-600 text-white rounded font-semibold hover:bg-teal-700 block mx-auto"
                                    style="width:fit-content;"
                                    @click="finishCrop">Crop & Preview</button>
                            <template x-if="croppedUrl">
                                <div class="mt-4 w-full flex flex-col items-center justify-center"
                                     style="background:#fff;border:1px solid #e2e8f0;border-radius:0.75rem;padding:1rem;">
                                    <label class="block text-xs text-gray-500 mb-2">Cropped Preview:</label>
                                    <img :src="croppedUrl"
                                         style="width:100%;height:192px;object-fit:cover;display:block;margin:auto;border-radius:0.75rem;border:1px solid #e2e8f0;background:#f9fafb;box-shadow:0 2px 8px 0 rgba(0,0,0,0.04);" />
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Right: Fields Section -->
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Name</label>
                        <input type="text" name="name" :value="editProduct.name" required class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Description</label>
                        <textarea name="desc" required class="border rounded px-3 py-2 w-full min-h-[100px]" x-text="editProduct.desc"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Category</label>
                        <input type="text" name="category" :value="editProduct.category" required class="border rounded px-3 py-2 w-full" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Type</label>
                        <select name="type" :value="editProduct.type" class="border rounded px-3 py-2 w-full">
                            <option value="food">Food & Beverages</option>
                            <option value="merch">Merchandise</option>
                        </select>
                    </div>
                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded font-semibold hover:bg-teal-700">
                            Save Changes
                        </button>
                    </div>
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
    <div class="relative overflow-hidden">
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
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white food-card flex flex-col cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-100 min-h-[400px] opacity-0 animate-fade-in"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         @click="navigateToReview(food.id)">
                        <div class="w-full h-52 relative food-image flex items-center justify-center bg-gradient-to-br from-teal-50 to-green-50 flex-shrink-0">
                            <img :src="food.img" :alt="food.name"
                                 class="w-full h-full object-cover"
                                 style="display:block;" />
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute top-3 right-3 z-20 flex flex-col gap-2">
                                <button @click.stop="openEditModal(food, '/catalog/edit/' + food.id)"
                                    class="bg-teal-600 text-white px-3 py-1.5 rounded-lg shadow-md text-xs font-semibold hover:bg-teal-700 transition-colors backdrop-blur-sm bg-opacity-90">
                                    Edit
                                </button>
                                <form method="POST" :action="'/catalog/delete/' + food.id" onsubmit="event.stopPropagation(); return confirm('Delete this product?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.stop class="bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-md text-xs font-semibold hover:bg-red-700 transition-colors backdrop-blur-sm bg-opacity-90">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                            <!-- Category Badge on Image -->
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-xs font-bold text-white bg-green-600 px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm bg-opacity-90" x-text="food.category"></span>
                            </div>
                        </div>
                        <div class="px-6 py-5 card-content flex-1 flex flex-col justify-between min-h-[200px]">
                            <div class="flex-1">
                                <div class="font-bold text-xl mb-3 card-title text-gray-800 line-clamp-2" x-text="food.name"></div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-lg">
                                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                        <span class="text-sm text-yellow-700 font-semibold" x-text="getAverageRating(food)"></span>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm card-description leading-relaxed line-clamp-3" x-text="food.desc"></p>
                            </div>
                            <div class="pt-4 tags-section">
                                <template x-for="tag in food.tags" :key="tag">
                                    <span class="inline-block bg-teal-100 text-teal-700 rounded-full px-3 py-1 text-xs font-medium mr-2 mb-2 tag" x-text="'#' + tag"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-center items-center gap-4 mb-8">
                <button @click="foodPage > 1 && foodPage--" :disabled="foodPage === 1" class="px-3 py-1 rounded bg-teal-600 text-white hover:bg-teal-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <span x-text="'Page ' + foodPage + ' of ' + foodTotalPages"></span>
                <button @click="foodPage < foodTotalPages && foodPage++" :disabled="foodPage === foodTotalPages" class="px-3 py-1 rounded bg-teal-600 text-white hover:bg-teal-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
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
                <template x-for="item in pagedMerch" :key="item.id">
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white merch-card flex flex-col cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-100 min-h-[400px] opacity-0 animate-fade-in"
                         :style="`animation-delay: ${$el.parentElement.children ? Array.from($el.parentElement.children).indexOf($el) * 50 : 0}ms`"
                         @click="navigateToReview(item.id)">
                        <div class="w-full h-52 relative merch-image flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 flex-shrink-0">
                            <img :src="item.img" :alt="item.name"
                                 class="w-full h-full object-cover"
                                 style="display:block;" />
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute top-3 right-3 z-20 flex flex-col gap-2">
                                <button @click.stop="openEditModal(item, '/catalog/edit/' + item.id)"
                                    class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg shadow-md text-xs font-semibold hover:bg-indigo-700 transition-colors backdrop-blur-sm bg-opacity-90">
                                    Edit
                                </button>
                                <form method="POST" :action="'/catalog/delete/' + item.id" onsubmit="event.stopPropagation(); return confirm('Delete this product?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" @click.stop class="bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-md text-xs font-semibold hover:bg-red-700 transition-colors backdrop-blur-sm bg-opacity-90">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                            <!-- Category Badge on Image -->
                            <div class="absolute top-3 left-3 z-10">
                                <span class="text-xs font-bold text-white bg-purple-600 px-3 py-1.5 rounded-full shadow-lg backdrop-blur-sm bg-opacity-90" x-text="item.category"></span>
                            </div>
                        </div>
                        <div class="px-6 py-5 card-content flex-1 flex flex-col justify-between min-h-[200px]">
                            <div class="flex-1">
                                <div class="font-bold text-xl mb-3 card-title text-gray-800 line-clamp-2" x-text="item.name"></div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-lg">
                                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                        <span class="text-sm text-yellow-700 font-semibold" x-text="getAverageRating(item)"></span>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm card-description leading-relaxed line-clamp-3" x-text="item.desc"></p>
                            </div>
                            <div class="pt-4 tags-section">
                                <template x-for="tag in item.tags" :key="tag">
                                    <span class="inline-block bg-indigo-100 text-indigo-700 rounded-full px-3 py-1 text-xs font-medium mr-2 mb-2 tag" x_text="'#' + tag"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-center items-center gap-4 mb-8">
                <button @click="merchPage > 1 && merchPage--" :disabled="merchPage === 1" class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <span x-text="'Page ' + merchPage + ' of ' + merchTotalPages"></span>
                <button @click="merchPage < merchTotalPages && merchPage++" :disabled="merchPage === merchTotalPages" class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    </div>



    <!-- No Products Message -->
    <div class="w-full text-center py-8" x-show="(tab === 'food' ? sortedFoods : sortedMerch).length === 0">
        <p class="text-gray-500 text-lg">No products found.</p>
    </div>

    {{-- Debug output for merchandise --}}
    {{-- <pre>{{ print_r($merchandise, true) }}</pre> --}}
    {{-- Remove after confirming data is present --}}
</div>
{{-- End section content --}}
@endsection

<head>
    <!-- ...existing code... -->
    <style>
        [x-cloak] { display: none !important; }
        
        /* Text clamping utilities */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Fade-in animation for cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        /* Loading animation */
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(20, 184, 166, 0);
            }
        }
        
        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }
        
        /* Smooth tab transitions */
        .tab-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Remove all shadows from select elements and their dropdown arrows */
        select,
        select:focus,
        select:hover,
        select:active {
            box-shadow: none !important;
            outline: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }
        
        /* Specifically target the sort dropdown container and select */
        .sort-dropdown-container,
        .sort-dropdown-container select,
        .sort-dropdown-container select:focus,
        .sort-dropdown-container select:hover,
        .sort-dropdown-container select:active {
            box-shadow: none !important;
            outline: none !important;
        }
        
        /* Remove default dropdown arrow and add custom one */
        .sort-dropdown-container select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        /* Ensure no shadow on the container either */
        .rounded-md.border-2.border-teal-300 {
            box-shadow: none !important;
        }
        
        .rounded-md.border-2.border-teal-300:hover {
            box-shadow: none !important;
        }
    </style>
    <!-- Add Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
</head>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('foodMerchComponent', function() {
        // Check for restore state to set initial tab
        let initialTab = 'food';
        try {
            const restoreState = sessionStorage.getItem('restoreCatalogState');
            if (restoreState) {
                const state = JSON.parse(restoreState);
                initialTab = state.tab || 'food';
                console.log('Setting initial tab from restore state:', initialTab);
            }
        } catch (e) {
            console.error('Error reading restore state for initial tab:', e);
        }
        
        return {
            tab: initialTab,
            isLoading: false,
            foodFilter: 'All',
            merchFilter: 'All',
            foodSort: '',
            merchSort: '',
            foodSearch: '',
            foodSearchInput: '',
            merchSearch: '',
            merchSearchInput: '',
            showFoodPredictions: false,
            showMerchPredictions: false,
            showAddModal: false,
            showEditModal: false,
            editProduct: {},
            editFormAction: '',
            food: @json($food),
            merchandise: @json($merchandise),
            foodPage: 1,
            foodPerPage: 8,
            merchPage: 1,
            merchPerPage: 8,
            // Force refresh trigger
            refreshTrigger: 0,
            // Enhanced caching system
            _cachedSortedFoods: null,
            _cachedSortedMerch: null,
            _cachedPagedFoods: new Map(),
            _cachedPagedMerch: new Map(),
            _lastFoodCacheKey: '',
            _lastMerchCacheKey: '',
            _preloadedTabs: new Set(),
            useBackendSearch: false, // Toggle for backend vs frontend search
            isSearching: false,
            
            blurActive() {
                if (document.activeElement) document.activeElement.blur();
            },
            
            // Helper methods
            getFoodCacheKey() {
                return `${this.foodFilter}-${this.foodSort}-${this.foodSearch}`;
            },
            
            getMerchCacheKey() {
                return `${this.merchFilter}-${this.merchSort}-${this.merchSearch}`;
            },
            
            // Optimized sorting with memoization
            get sortedFoods() {
                const cacheKey = this.getFoodCacheKey();
                
                if (this._cachedSortedFoods && this._lastFoodCacheKey === cacheKey) {
                    return this._cachedSortedFoods;
                }
                
                const sort = () => {
                    let search = this.foodSearch.toLowerCase();
                    let filtered = this.food.filter(f => {
                        const categoryMatch = this.foodFilter === 'All' || f.category === this.foodFilter;
                        const searchMatch = !search || 
                            f.name.toLowerCase().includes(search) || 
                            f.desc.toLowerCase().includes(search);
                        
                        return categoryMatch && searchMatch;
                    });
                    
                    if (this.foodSort === 'name') {
                        filtered.sort((a, b) => a.name.localeCompare(b.name));
                    } else if (this.foodSort === 'category') {
                        filtered.sort((a, b) => a.category.localeCompare(b.category));
                    } else if (this.foodSort === 'rating') {
                        filtered.sort((a, b) => {
                            const ratingA = parseFloat(a.calculated_rating || '0');
                            const ratingB = parseFloat(b.calculated_rating || '0');
                            return ratingB - ratingA;
                        });
                    }
                    
                    this._cachedSortedFoods = filtered;
                    this._lastFoodCacheKey = cacheKey;
                    return filtered;
                };
                
                return sort();
            },
            
            get pagedFoods() {
                const pageKey = `${this.getFoodCacheKey()}-${this.foodPage}`;
                
                if (this._cachedPagedFoods.has(pageKey)) {
                    return this._cachedPagedFoods.get(pageKey);
                }
                
                const start = (this.foodPage - 1) * this.foodPerPage;
                const result = this.sortedFoods.slice(start, start + this.foodPerPage);
                
                // Cache this page but limit cache size
                if (this._cachedPagedFoods.size > 20) {
                    const firstKey = this._cachedPagedFoods.keys().next().value;
                    this._cachedPagedFoods.delete(firstKey);
                }
                this._cachedPagedFoods.set(pageKey, result);
                
                return result;
            },
            
            get foodTotalPages() {
                return Math.max(1, Math.ceil(this.sortedFoods.length / this.foodPerPage));
            },
            
            get sortedMerch() {
                const cacheKey = this.getMerchCacheKey();
                
                if (this._cachedSortedMerch && this._lastMerchCacheKey === cacheKey) {
                    return this._cachedSortedMerch;
                }
                
                const sort = () => {
                    let search = this.merchSearch.toLowerCase();
                    let filtered = this.merchandise.filter(m => {
                        const categoryMatch = this.merchFilter === 'All' || m.category === this.merchFilter;
                        const searchMatch = !search || 
                            m.name.toLowerCase().includes(search) || 
                            m.desc.toLowerCase().includes(search);
                        
                        return categoryMatch && searchMatch;
                    });
                    
                    if (this.merchSort === 'name') {
                        filtered.sort((a, b) => a.name.localeCompare(b.name));
                    } else if (this.merchSort === 'category') {
                        filtered.sort((a, b) => a.category.localeCompare(b.category));
                    } else if (this.merchSort === 'rating') {
                        filtered.sort((a, b) => {
                            const ratingA = parseFloat(a.calculated_rating || '0');
                            const ratingB = parseFloat(b.calculated_rating || '0');
                            return ratingB - ratingA;
                        });
                    }
                    
                    this._cachedSortedMerch = filtered;
                    this._lastMerchCacheKey = cacheKey;
                    return filtered;
                };
                
                return sort();
            },
            
            get pagedMerch() {
                const pageKey = `${this.getMerchCacheKey()}-${this.merchPage}`;
                
                if (this._cachedPagedMerch.has(pageKey)) {
                    return this._cachedPagedMerch.get(pageKey);
                }
                
                const start = (this.merchPage - 1) * this.merchPerPage;
                const result = this.sortedMerch.slice(start, start + this.merchPerPage);
                
                if (this._cachedPagedMerch.size > 20) {
                    const firstKey = this._cachedPagedMerch.keys().next().value;
                    this._cachedPagedMerch.delete(firstKey);
                }
                this._cachedPagedMerch.set(pageKey, result);
                
                return result;
            },
            
            get merchTotalPages() {
                return Math.max(1, Math.ceil(this.sortedMerch.length / this.merchPerPage));
            },
            
            // Aggressive cache invalidation
            invalidateCache(type = 'both') {
                if (type === 'food' || type === 'both') {
                    this._cachedSortedFoods = null;
                    this._lastFoodCacheKey = '';
                    this._cachedPagedFoods.clear();
                }
                if (type === 'merch' || type === 'both') {
                    this._cachedSortedMerch = null;
                    this._lastMerchCacheKey = '';
                    this._cachedPagedMerch.clear();
                }
            },
            
            // Preload data for tabs
            async preloadTab(tabName) {
                if (this._preloadedTabs.has(tabName)) return;
                
                return new Promise((resolve) => {
                    requestAnimationFrame(() => {
                        try {
                            if (tabName === 'food') {
                                // Force computation
                                this.sortedFoods;
                                this.pagedFoods;
                            } else if (tabName === 'merch') {
                                // Force computation
                                this.sortedMerch;
                                this.pagedMerch;
                            }
                            this._preloadedTabs.add(tabName);
                            resolve();
                        } catch (e) {
                            console.error('Preload error:', e);
                            resolve();
                        }
                    });
                });
            },
            
            // Enhanced tab switching with better UX
            async switchTab(newTab) {
                if (this.tab === newTab) return;
                
                // Start loading state immediately
                this.isLoading = true;
                this.blurActive();
                
                // Use shorter delay for better perceived performance
                await new Promise(resolve => setTimeout(resolve, 50));
                
                // Switch tab
                this.tab = newTab;
                
                // Preload the new tab data in parallel
                const preloadPromise = this.preloadTab(newTab);
                
                // Animate cards preparation
                const animationPromise = new Promise(resolve => {
                    this.$nextTick(() => {
                        this.animateCards();
                        resolve();
                    });
                });
                
                // Wait for both operations
                await Promise.all([preloadPromise, animationPromise]);
                
                // End loading state
                this.isLoading = false;
            },
            
            // Optimized card animation with staggering
            animateCards() {
                requestAnimationFrame(() => {
                    const selector = this.tab === 'food' ? '.food-card' : '.merch-card';
                    const cards = document.querySelectorAll(selector);
                    
                    // Use DocumentFragment for better performance if many cards
                    cards.forEach((card, index) => {
                        if (card) {
                            // Reset animation
                            card.style.animation = 'none';
                            card.style.animationDelay = '0ms';
                            
                            // Force reflow
                            card.offsetHeight;
                            
                            // Apply staggered animation
                            const delay = Math.min(index * 30, 300); // Cap max delay
                            card.style.animationDelay = `${delay}ms`;
                            card.style.animation = 'fadeInUp 0.4s ease-out forwards';
                        }
                    });
                });
            },
            
            // Debounced search to reduce lag
            debounceSearch(searchFn, delay = 300) {
                let timeoutId;
                return function(...args) {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => searchFn.apply(this, args), delay);
                };
            },
            
            openEditModal(product, action) {
                this.editProduct = product;
                this.editFormAction = action;
                this.showEditModal = true;
            },
            
            submitAddProduct() {
                const form = this.$refs.addForm;
                const formData = new FormData(form);
                fetch('{{ route('catalog.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.product) {
                        if (data.product.type === 'food') {
                            this.food.push(data.product);
                            this.invalidateCache('food');
                        } else if (data.product.type === 'merch') {
                            this.merchandise.push(data.product);
                            this.invalidateCache('merch');
                        }
                        this.showAddModal = false;
                        form.reset();
                    } else {
                        alert('Failed to add product.');
                    }
                })
                .catch(() => alert('Error adding product.'));
            },
            
            showCropper: false,
            cropper: null,
            croppedBlob: null,
            croppedUrl: '',
            startCrop(event) {
                this.showCropper = true;
                this.croppedBlob = null;
                this.croppedUrl = '';
                let img = document.getElementById('edit-cropper-img');
                if (this.cropper) this.cropper.destroy();
                const file = event.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    this.$nextTick(() => {
                        this.cropper = new Cropper(img, {
                            aspectRatio: 4/3,
                            viewMode: 0,
                            autoCropArea: 1,
                            zoomOnWheel: true,
                        });
                    });
                };
                reader.readAsDataURL(file);
            },
            
            finishCrop() {
                if (this.cropper) {
                    this.cropper.getCroppedCanvas().toBlob(blob => {
                        this.croppedBlob = blob;
                        this.croppedUrl = URL.createObjectURL(blob);
                    });
                }
            },
            
            async handleEditSubmit(e) {
                const form = e.target;
                const formData = new FormData(form);
                if (this.croppedBlob) {
                    formData.set('img', this.croppedBlob, 'cropped.png');
                }
                formData.delete('img_position');
                try {
                    const res = await fetch(this.editFormAction, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    if (res.ok) {
                        window.location.reload();
                    } else {
                        let msg = 'Failed to update product.';
                        try {
                            const data = await res.json();
                            if (data && data.message) msg = data.message;
                        } catch {}
                        alert(msg);
                    }
                } catch (err) {
                    alert('Network error: Failed to update product.');
                }
            },
            
            navigateToReview(productId) {
                if (!productId) return;
                
                const currentState = {
                    source: 'catalog',
                    sourcePage: '/catalog',
                    tab: this.tab,
                    foodFilter: this.foodFilter,
                    merchFilter: this.merchFilter,
                    foodSort: this.foodSort,
                    merchSort: this.merchSort,
                    foodSearch: this.foodSearch,
                    merchSearch: this.merchSearch,
                    foodPage: this.foodPage,
                    merchPage: this.merchPage,
                    scrollPosition: window.scrollY,
                    timestamp: Date.now()
                };
                
                sessionStorage.setItem('catalogState', JSON.stringify(currentState));
                window.location.href = '/review/' + productId;
            },
            
            async init() {
                // Check for state restoration first, before any other initialization
                const restoreState = sessionStorage.getItem('restoreCatalogState');
                if (restoreState) {
                    try {
                        const state = JSON.parse(restoreState);
                        console.log('Restoring catalog state:', state);
                        
                        // Set tab first, before other properties
                        this.tab = state.tab || 'food';
                        console.log('Setting tab to:', this.tab);
                        
                        this.foodFilter = state.foodFilter || 'All';
                        this.merchFilter = state.merchFilter || 'All';
                        this.foodSort = state.foodSort || '';
                        this.merchSort = state.merchSort || '';
                        this.foodSearch = state.foodSearch || '';
                        this.merchSearch = state.merchSearch || '';
                        this.foodSearchInput = state.foodSearch || '';
                        this.merchSearchInput = state.merchSearch || '';
                        this.foodPage = state.foodPage || 1;
                        this.merchPage = state.merchPage || 1;
                        
                        // Force a DOM update to ensure tab change is applied
                        await this.$nextTick();
                        
                        // Restore scroll position after a longer delay
                        setTimeout(() => {
                            if (state.scrollPosition) {
                                window.scrollTo(0, state.scrollPosition);
                            }
                        }, 300);
                        
                        sessionStorage.removeItem('restoreCatalogState');
                        console.log('State restoration completed. Current tab:', this.tab);
                    } catch (e) {
                        console.error('Error restoring catalog state:', e);
                    }
                }
                
                // Aggressive preloading
                await this.$nextTick();
                
                // Preload current tab first
                await this.preloadTab(this.tab);
                
                // Then preload the other tab in the background
                requestIdleCallback(() => {
                    const otherTab = this.tab === 'food' ? 'merch' : 'food';
                    this.preloadTab(otherTab);
                });
                
                // Initial animation
                this.$nextTick(() => {
                    this.animateCards();
                });
            },
            
            getAverageRating(product) {
                if (product.calculated_rating !== undefined) {
                    return product.calculated_rating;
                }
                return '0.0';
            },

            // Debug method - call this from browser console: window.debugCatalog()
            debugState() {
                return {
                    tab: this.tab,
                    foodDataLength: this.food?.length || 0,
                    merchDataLength: this.merchandise?.length || 0,
                    foodSearchInput: this.foodSearchInput,
                    foodSearch: this.foodSearch,
                    showFoodPredictions: this.showFoodPredictions,
                    sortedFoodsLength: this.sortedFoods?.length || 0,
                    pagedFoodsLength: this.pagedFoods?.length || 0
                };
            },
            
            // Enhanced search method with backend option
            async performSearch() {
                // Always use frontend search for now to prevent reload issues
                this.performFrontendSearch();
            },
            
            // Original frontend search (existing implementation)
            performFrontendSearch() {
                if (this.tab === 'food') {
                    this.invalidateCache('food');
                    this.foodSearch = this.foodSearchInput.trim();
                    this.foodPage = 1;
                    
                    this.$nextTick(() => {
                        this.animateCards();
                    });
                } else {
                    this.invalidateCache('merch');
                    this.merchSearch = this.merchSearchInput.trim();
                    this.merchPage = 1;
                    
                    this.$nextTick(() => {
                        this.animateCards();
                    });
                }
                
                this.showFoodPredictions = false;
                this.showMerchPredictions = false;
            },
            
            // New backend search implementation
            async performBackendSearch() {
                this.isSearching = true;
                
                try {
                    const searchParams = new URLSearchParams({
                        query: this.tab === 'food' ? this.foodSearchInput.trim() : this.merchSearchInput.trim(),
                        type: this.tab === 'food' ? 'food' : 'merch',
                        category: this.tab === 'food' ? this.foodFilter : this.merchFilter,
                        sort: this.tab === 'food' ? this.foodSort : this.merchSort,
                        page: 1,
                        per_page: this.tab === 'food' ? this.foodPerPage : this.merchPerPage
                    });
                    
                    const response = await fetch(`/search/catalog?${searchParams}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        
                        // Update the data
                        if (this.tab === 'food') {
                            this.food = data.products;
                            this.foodSearch = this.foodSearchInput.trim();
                            this.foodPage = data.pagination.current_page;
                        } else {
                            this.merchandise = data.products;
                            this.merchSearch = this.merchSearchInput.trim();
                            this.merchPage = data.pagination.current_page;
                        }
                        
                        // Clear caches since we have new data
                        this.invalidateCache();
                        
                        console.log('Backend search completed:', data.products.length, 'results');
                        
                        this.$nextTick(() => {
                            this.animateCards();
                        });
                    } else {
                        console.error('Backend search failed:', response.status);
                        // Fallback to frontend search
                        this.performFrontendSearch();
                    }
                } catch (error) {
                    console.error('Backend search error:', error);
                    // Fallback to frontend search
                    this.performFrontendSearch();
                } finally {
                    this.isSearching = false;
                    this.showFoodPredictions = false;
                    this.showMerchPredictions = false;
                }
            },
            
            // Enhanced clear search with backend support
            async clearSearch() {
                console.log('=== CLEAR SEARCH ===');
                console.log('Current tab:', this.tab);
                
                if (this.tab === 'food') {
                    this.foodSearchInput = '';
                    this.foodSearch = '';
                    this.foodPage = 1;
                } else {
                    this.merchSearchInput = '';
                    this.merchSearch = '';
                    this.merchPage = 1;
                }
                
                // If we were using backend search, reload the original data
                if (this.useBackendSearch) {
                    await this.loadOriginalData();
                } else {
                    this.invalidateCache();
                    this.$nextTick(() => {
                        this.animateCards();
                    });
                }
                
                this.showFoodPredictions = false;
                this.showMerchPredictions = false;
            },
            
            // Load original data from backend
            async loadOriginalData() {
                this.isSearching = true;
                
                try {
                    const response = await fetch('/catalog/data', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.food = data.food || [];
                        this.merchandise = data.merchandise || [];
                        this.invalidateCache();
                        
                        this.$nextTick(() => {
                            this.animateCards();
                        });
                    }
                } catch (error) {
                    console.error('Failed to load original data:', error);
                } finally {
                    this.isSearching = false;
                }
            },
            
            // Method to toggle between frontend and backend search
            toggleSearchMode() {
                this.useBackendSearch = !this.useBackendSearch;
                console.log('Search mode:', this.useBackendSearch ? 'Backend' : 'Frontend');
            },

            // Expose debug method globally
            init() {
                window.debugCatalog = () => this.debugState();
            },
            
            // Optimized watchers with debouncing
            $watch: {
                foodSort: {
                    handler() {
                        this.invalidateCache('food');
                        this.foodPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                foodFilter: {
                    handler() {
                        this.invalidateCache('food');
                        this.foodPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                foodSearch: {
                    handler() {
                        this.invalidateCache('food');
                        this.foodPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                merchSort: {
                    handler() {
                        this.invalidateCache('merch');
                        this.merchPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                merchFilter: {
                    handler() {
                        this.invalidateCache('merch');
                        this.merchPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                merchSearch: {
                    handler() {
                        this.invalidateCache('merch');
                        this.merchPage = 1;
                        this.$nextTick(() => this.animateCards());
                    }
                },
                foodPage: {
                    handler() {
                        if (this.tab === 'food') {
                            this.$nextTick(() => this.animateCards());
                        }
                    }
                },
                merchPage: {
                    handler() {
                        if (this.tab === 'merch') {
                            this.$nextTick(() => this.animateCards());
                        }
                    }
                }
            }
        }
    });
});
</script>

<!-- Add search mode indicator for admins -->
@if(auth()->check() && auth()->user()->role === 'admin')
    <div class="fixed bottom-4 left-4 z-40">
        <button @click="toggleSearchMode()" 
                :class="useBackendSearch ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-600 hover:bg-gray-700'"
                class="px-3 py-2 text-white text-xs rounded-lg shadow-lg transition-colors">
            <span x-text="useBackendSearch ? 'Backend Search' : 'Frontend Search'"></span>
        </button>
    </div>
@endif

<!-- Loading overlay for backend search -->
<div x-show="isSearching" x-cloak class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-40">
    <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-600 mb-4"></div>
        <p class="text-gray-600 font-medium">Searching...</p>
    </div>
</div>


