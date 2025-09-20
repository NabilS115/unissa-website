@extends('layouts.app')

@section('title', 'Food Catalog - List')

@section('content')
@php
    $categories = $categories ?? ['All'];
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
                        <template x-for="food in foods" :key="food.name">
                            <template x-if="food.name.toLowerCase().includes(foodSearchInput.toLowerCase())">
                                <li @mousedown.prevent="foodSearchInput = food.name; showFoodPredictions = false" class="px-4 py-2 hover:bg-teal-100 cursor-pointer text-sm" x-text="food.name"></li>
                            </template>
                        </template>
                    </ul>
                </template>
                <template x-if="tab === 'merch' && merchSearchInput && showMerchPredictions">
                    <ul class="absolute left-0 right-0 mt-2 bg-white border border-teal-200 rounded-b-lg shadow z-20 max-h-48 overflow-y-auto">
                        <template x-for="item in merchandise" :key="item.name">
                            <template x-if="item.name.toLowerCase().includes(merchSearchInput.toLowerCase())">
                                <li @mousedown.prevent="merchSearchInput = item.name; showMerchPredictions = false" class="px-4 py-2 hover:bg-indigo-100 cursor-pointer text-sm" x-text="item.name"></li>
                            </template>
                        </template>
                    </ul>
                </template>
            </div>
            <div class="flex-1 flex flex-wrap gap-2 items-center justify-center sm:justify-start overflow-x-auto py-1">
                @foreach ($categories as $cat)
                <button type="button" @click="tab === 'food' ? foodFilter = '{{ $cat }}' : merchFilter = '{{ $cat }}'" :class="(tab === 'food' ? foodFilter : merchFilter) === '{{ $cat }}' ? (tab === 'food' ? 'bg-teal-600 text-white' : 'bg-indigo-600 text-white') : 'bg-gray-100 text-teal-700'" class="px-4 py-1 rounded-full font-semibold text-sm hover:bg-teal-100 transition">{{ $cat }}</button>
                @endforeach
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-teal-700">Sort by:</label>
                <div class="rounded-full border-2 border-teal-300 px-2 py-1 flex items-center bg-white">
                    <select x-model="tab === 'food' ? foodSort : merchSort"
                        class="bg-transparent outline-none px-4 py-2 rounded-full focus:ring-0 border-none"
                        style="box-shadow:none;">
                        <option value="">Sort By</option>
                        <option value="name">Name</option>
                        <option value="category">Category</option>
                        <option value="rating">Rating</option>
                    </select>
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
        <form method="POST" action="{{ route('catalog.add') }}" enctype="multipart/form-data"
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
              class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
            @csrf
            <button type="button" @click="showEditModal = false"
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Edit Product</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" :value="editProduct.name" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="desc" required class="border rounded px-3 py-2 w-full" x-text="editProduct.desc"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Category</label>
                <input type="text" name="category" :value="editProduct.category" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Image</label>
                <input type="file" name="img" class="border rounded px-3 py-2 w-full" />
            </div>
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded font-semibold hover:bg-teal-700">
                Save
            </button>
        </form>
    </div>
    @endif

    <!-- Food Cards -->
    <template x-if="tab === 'food'">
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-8 mb-20">
                <template x-for="food in pagedFoods" :key="food.name">
                    <div class="rounded overflow-hidden shadow-lg bg-white food-card flex flex-col">
                        <div class="w-full h-48 relative food-image">
                            <img :src="food.img" :alt="food.name" class="absolute inset-0 w-full h-full object-cover rounded-t bg-white" />
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute top-2 right-2 z-20 flex flex-col gap-1">
                                <!-- Fix: Use only JS for route parameter, not Blade -->
                                <button @click="openEditModal(food, '/catalog/edit/' + food.id)"
                                    class="bg-teal-600 text-white px-2 py-1 rounded shadow text-xs font-semibold hover:bg-teal-700">
                                    Edit
                                </button>
                                <form method="POST" :action="'/catalog/delete/' + food.id" onsubmit="return confirm('Delete this product?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded shadow text-xs font-semibold hover:bg-red-700 mt-1">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <div class="px-6 py-4 card-content flex-1 flex flex-col justify-between">
                            <div>
                                <div class="font-bold text-xl mb-2 card-title" x-text="food.name"></div>
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-4 h-4 text-yellow-400 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                    <span class="text-sm text-gray-700 font-semibold" x-text="food.rating"></span>
                                </div>
                                <p class="text-gray-700 text-base card-description" x-text="food.desc"></p>
                            </div>
                            <div class="px-0 pt-2 pb-1 tags-section">
                                <template x-for="tag in food.tags" :key="tag">
                                    <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag" x-text="'#' + tag"></span>
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
                <template x-for="item in pagedMerch" :key="item.name">
                    <div class="rounded overflow-hidden shadow-lg bg-white merch-card flex flex-col">
                        <div class="w-full h-48 relative merch-image">
                            <img :src="item.img" :alt="item.name" class="absolute inset-0 w-full h-full object-cover rounded-t bg-white" />
                            @if(auth()->user()?->role === 'admin')
                            <div class="absolute top-2 right-2 z-20 flex flex-col gap-1">
                                <button @click="openEditModal(item, '/catalog/edit/' + item.id)"
                                    class="bg-indigo-600 text-white px-2 py-1 rounded shadow text-xs font-semibold hover:bg-indigo-700">
                                    Edit
                                </button>
                                <form method="POST" :action="'/catalog/delete/' + item.id" onsubmit="return confirm('Delete this product?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded shadow text-xs font-semibold hover:bg-red-700 mt-1">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        <div class="px-6 py-4 card-content flex-1 flex flex-col justify-between">
                            <div>
                                <div class="font-bold text-xl mb-2 card-title" x-text="item.name"></div>
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-4 h-4 text-yellow-400 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                    <span class="text-sm text-gray-700 font-semibold" x-text="item.rating"></span>
                                </div>
                                <p class="text-gray-700 text-base card-description" x-text="item.desc"></p>
                            </div>
                            <div class="px-0 pt-2 pb-1 tags-section">
                                <template x-for="tag in item.tags" :key="tag">
                                    <span class="inline-block bg-indigo-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag" x-text="'#' + tag"></span>
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
                <input type="file" name="image" required class="border rounded px-3 py-2 w-full" />
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
</div>
@endsection

<head>
    <!-- ...existing code... -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <!-- ...existing code... -->
</head>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function foodMerchComponent() {
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
        foods: @json($foods),
        merchandise: @json($merchandise),
        foodPage: 1,
        foodPerPage: 8,
        merchPage: 1,
        merchPerPage: 8,
        blurActive() {
            if (document.activeElement) document.activeElement.blur();
        },
        get sortedFoods() {
            let search = this.foodSearch.toLowerCase();
            let filtered = this.foods.filter(f =>
                (this.foodFilter === 'All' || f.category === this.foodFilter) &&
                (
                    !search ||
                    f.name.toLowerCase().includes(search) ||
                    f.desc.toLowerCase().includes(search)
                )
            );
            if (this.foodSort === 'name') {
                filtered.sort((a, b) => a.name.localeCompare(b.name));
            } else if (this.foodSort === 'category') {
                filtered.sort((a, b) => a.category.localeCompare(b.category));
            } else if (this.foodSort === 'rating') {
                filtered.sort((a, b) => b.rating - a.rating);
            }
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
            let search = this.merchSearch.toLowerCase();
            let filtered = this.merchandise.filter(m =>
                (this.merchFilter === 'All' || m.category === this.merchFilter) &&
                (
                    !search ||
                    m.name.toLowerCase().includes(search) ||
                    m.desc.toLowerCase().includes(search)
                )
            );
            if (this.merchSort === 'name') {
                filtered.sort((a, b) => a.name.localeCompare(b.name));
            } else if (this.merchSort === 'category') {
                filtered.sort((a, b) => a.category.localeCompare(b.category));
            } else if (this.merchSort === 'rating') {
                filtered.sort((a, b) => b.rating - a.rating);
            }
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
        $watch: {
            sortedFoods() { this.foodPage = 1; },
            sortedMerch() { this.merchPage = 1; }
        }
    }
}
</script>


