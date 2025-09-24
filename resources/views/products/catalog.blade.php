@extends('layouts.app')

@section('title', 'Catalog')

@section('content')
@php
    $food = $food ?? [];
    $merchandise = $merchandise ?? [];
    
    // Convert merchandise to objects if they're arrays
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
        if ((is_object($foodItem) || is_array($foodItem)) && isset($foodItem->id)) {
            $productId = is_object($foodItem) ? $foodItem->id : $foodItem['id'];
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
            }
        }
    }
    
    foreach ($merchandise as &$merchItem) {
        if ((is_object($merchItem) || is_array($merchItem)) && (isset($merchItem->id) || isset($merchItem['id']))) {
            $productId = is_object($merchItem) ? $merchItem->id : $merchItem['id'];
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
                // Convert to object after adding calculated_rating
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
            <button type="button" @click="blurActive(); tab = 'food'" :class="tab === 'food' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Food & Beverages</button>
            <button type="button" @click="blurActive(); tab = 'merch'" :class="tab === 'merch' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Merchandise</button>
        </div>
    </div>
    <section class="w-full flex flex-col gap-3 px-8 py-4 mb-8">
        <div class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
            <div class="w-full sm:w-1/3 relative">
                <div class="relative">
                    <input type="text" :placeholder="tab === 'food' ? 'Search food...' : 'Search merchandise...'" x-model="tab === 'food' ? foodSearchInput : merchSearchInput" @focus="tab === 'food' ? showFoodPredictions = true : showMerchPredictions = true" @blur="setTimeout(() => { showFoodPredictions = false; showMerchPredictions = false; }, 100)" class="w-full border border-teal-300 rounded-full px-4 py-2 pr-16 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                    <button @click="tab === 'food' ? foodSearch = foodSearchInput : merchSearch = merchSearchInput" class="absolute right-8 top-1/2 -translate-y-1/2 p-0 m-0 bg-transparent border-none outline-none flex items-center justify-center" style="height:28px;width:28px;">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </button>
                    <button @click="tab === 'food' ? (foodSearchInput = '', foodSearch = '') : (merchSearchInput = '', merchSearch = '')" x-show="tab === 'food' ? (foodSearch || foodSearchInput) : (merchSearch || merchSearchInput)" class="absolute right-1 top-1/2 -translate-y-1/2 p-0 m-0 bg-transparent border-none outline-none flex items-center justify-center" style="height:24px;width:24px;" title="Clear search">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <template x-if="tab === 'food' && foodSearchInput && showFoodPredictions">
                    <ul class="absolute left-0 right-0 mt-2 bg-white border border-teal-200 rounded-b-lg shadow z-20 max-h-48 overflow-y-auto">
                        <template x-for="food in food" :key="food.id">
                            <template x-if="food.name.toLowerCase().includes(foodSearchInput.toLowerCase())">
                                <li @mousedown.prevent="foodSearchInput = food.name; showFoodPredictions = false" class="px-4 py-2 hover:bg-teal-100 cursor-pointer text-sm" x-text="food.name"></li>
                            </template>
                        </template>
                    </ul>
                </template>
                <template x-if="tab === 'merch' && merchSearchInput && showMerchPredictions">
                    <ul class="absolute left-0 right-0 mt-2 bg-white border border-teal-200 rounded-b-lg shadow z-20 max-h-48 overflow-y-auto">
                        <template x-for="item in merchandise" :key="item.id">
                            <template x-if="item.name.toLowerCase().includes(merchSearchInput.toLowerCase())">
                                <li @mousedown.prevent="merchSearchInput = item.name; showMerchPredictions = false" class="px-4 py-2 hover:bg-indigo-100 cursor-pointer text-sm" x-text="item.name"></li>
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
                <div class="rounded-full border-2 border-teal-300 px-3 py-1 flex items-center bg-white shadow-sm hover:shadow-md transition-shadow">
                    <template x-if="tab === 'food'">
                        <select x-model="foodSort"
                            class="bg-transparent outline-none px-2 py-1 rounded-full focus:ring-0 border-none text-teal-700 font-medium cursor-pointer"
                            style="box-shadow:none;">
                            <option value="">Default</option>
                            <option value="name">Name (A-Z)</option>
                            <option value="category">Category</option>
                            <option value="rating">Rating (High to Low)</option>
                        </select>
                    </template>
                    <template x-if="tab === 'merch'">
                        <select x-model="merchSort"
                            class="bg-transparent outline-none px-2 py-1 rounded-full focus:ring-0 border-none text-teal-700 font-medium cursor-pointer"
                            style="box-shadow:none;">
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

    <!-- Food Cards -->
    <template x-if="tab === 'food'">
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-8 mb-20">
                <template x-for="food in pagedFoods" :key="food.id">
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white food-card flex flex-col cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-100 min-h-[400px]"
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
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-md text-xs font-semibold hover:bg-red-700 transition-colors backdrop-blur-sm bg-opacity-90">
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
    </template>
    <!-- Merchandise Cards -->
    <template x-if="tab === 'merch'">
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-8 mb-20">
                <template x-for="item in pagedMerch" :key="item.id">
                    <div class="rounded-xl overflow-hidden shadow-lg bg-white merch-card flex flex-col cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-100 min-h-[400px]"
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
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-lg shadow-md text-xs font-semibold hover:bg-red-700 transition-colors backdrop-blur-sm bg-opacity-90">
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
    </template>

    @if(auth()->user()?->role === 'admin')
    <div class="w-full flex justify-end px-8 mb-4">
        <button @click="showUpload = true"
            class="bg-teal-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-teal-700 transition">
            Upload Image
        </button>
    </div>
    <div x-show="showUpload" x-cloak class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('catalog.upload') }}" enctype="multipart/form-data"
              class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
            @csrf
            <button type="button" @click="showUpload = false"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Upload Catalog Image</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Image File</label>
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
                Upload
            </button>
        </form>
    </div>
    @endif

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
    </style>
    <!-- Add Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
</head>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('foodMerchComponent', function() {
        return {
            tab: 'food',
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
            showUpload: false,
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
            blurActive() {
                if (document.activeElement) document.activeElement.blur();
            },
            get sortedFoods() {
                console.log('Sorting foods by:', this.foodSort); // Debug log
                let search = this.foodSearch.toLowerCase();
                let filtered = this.food.filter(f =>
                    (this.foodFilter === 'All' || f.category === this.foodFilter) &&
                    (
                        !search ||
                        f.name.toLowerCase().includes(search) ||
                        f.desc.toLowerCase().includes(search)
                    )
                );
                
                // Apply sorting
                if (this.foodSort === 'name') {
                    console.log('Sorting by name');
                    filtered.sort((a, b) => a.name.localeCompare(b.name));
                } else if (this.foodSort === 'category') {
                    console.log('Sorting by category');
                    filtered.sort((a, b) => a.category.localeCompare(b.category));
                } else if (this.foodSort === 'rating') {
                    console.log('Sorting by rating');
                    filtered.sort((a, b) => {
                        const ratingA = parseFloat(a.calculated_rating || '0');
                        const ratingB = parseFloat(b.calculated_rating || '0');
                        console.log(`Rating A: ${ratingA}, Rating B: ${ratingB}`);
                        return ratingB - ratingA; // High to low
                    });
                }
                
                console.log('Filtered results:', filtered.length);
                return filtered;
            },
            get pagedFoods() {
                const start = (this.foodPage - 1) * this.foodPerPage;
                return this.sortedFoods.slice(start, start + this.foodPerPage);
            },
            get foodTotalPages() {
                return Math.max(1, Math.ceil(this.sortedFoods.length / this.foodPerPage));
            },
            get sortedMerch() {
                console.log('Sorting merch by:', this.merchSort); // Debug log
                let search = this.merchSearch.toLowerCase();
                let filtered = this.merchandise.filter(m =>
                    (this.merchFilter === 'All' || m.category === this.merchFilter) &&
                    (
                        !search ||
                        m.name.toLowerCase().includes(search) ||
                        m.desc.toLowerCase().includes(search)
                    )
                );
                
                // Apply sorting
                if (this.merchSort === 'name') {
                    console.log('Sorting by name');
                    filtered.sort((a, b) => a.name.localeCompare(b.name));
                } else if (this.merchSort === 'category') {
                    console.log('Sorting by category');
                    filtered.sort((a, b) => a.category.localeCompare(b.category));
                } else if (this.merchSort === 'rating') {
                    console.log('Sorting by rating');
                    filtered.sort((a, b) => {
                        const ratingA = parseFloat(a.calculated_rating || '0');
                        const ratingB = parseFloat(b.calculated_rating || '0');
                        console.log(`Rating A: ${ratingA}, Rating B: ${ratingB}`);
                        return ratingB - ratingA; // High to low
                    });
                }
                
                console.log('Filtered results:', filtered.length);
                return filtered;
            },
            get pagedMerch() {
                const start = (this.merchPage - 1) * this.merchPerPage;
                return this.sortedMerch.slice(start, start + this.merchPerPage);
            },
            get merchTotalPages() {
                return Math.max(1, Math.ceil(this.sortedMerch.length / this.merchPerPage));
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
                    console.log('Added product:', data.product);
                    if (data.success && data.product) {
                        if (data.product.type === 'food') {
                            this.food.push(data.product);
                        } else if (data.product.type === 'merch') {
                            this.merchandise.push(data.product);
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
                            viewMode: 0, // allow cropping outside image boundary
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
                // Remove any img_position from FormData (no column check needed in frontend)
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
            // Add navigation method to preserve state
            navigateToReview(productId) {
                if (!productId) return;
                
                // Store current page state in sessionStorage
                const currentState = {
                    tab: this.tab,
                    foodFilter: this.foodFilter,
                    merchFilter: this.merchFilter,
                    foodSort: this.foodSort,
                    merchSort: this.merchSort,
                    foodSearch: this.foodSearch,
                    merchSearch: this.merchSearch,
                    foodPage: this.foodPage,
                    merchPage: this.merchPage,
                    scrollPosition: window.scrollY
                };
                
                sessionStorage.setItem('catalogState', JSON.stringify(currentState));
                window.location.href = '/review/' + productId;
            },
            // Add init method to restore state
            init() {
                // Check if we need to restore state from review page
                const restoreState = sessionStorage.getItem('restoreCatalogState');
                if (restoreState) {
                    try {
                        const state = JSON.parse(restoreState);
                        this.tab = state.tab || 'food';
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
                        
                        // Restore scroll position after a brief delay
                        setTimeout(() => {
                            if (state.scrollPosition) {
                                window.scrollTo(0, state.scrollPosition);
                            }
                        }, 100);
                        
                        // Clear the restore state
                        sessionStorage.removeItem('restoreCatalogState');
                    } catch (e) {
                        console.error('Error restoring catalog state:', e);
                    }
                }
            },
            // Simplified method to use pre-calculated rating
            getAverageRating(product) {
                // Use the calculated rating from backend
                if (product.calculated_rating !== undefined) {
                    return product.calculated_rating;
                }
                
                // Fallback to "0.0" if no rating calculated
                return '0.0';
            },
            $watch: {
                foodSort: {
                    handler(newVal) {
                        console.log('Food sort changed to:', newVal);
                        this.foodPage = 1;
                    }
                },
                merchSort: {
                    handler(newVal) {
                        console.log('Merch sort changed to:', newVal);
                        this.merchPage = 1;
                    }
                },
                sortedFoods() { 
                    console.log('sortedFoods changed');
                    this.foodPage = 1; 
                },
                sortedMerch() { 
                    console.log('sortedMerch changed');
                    this.merchPage = 1; 
                }
            }
        }
    });
});
</script>


