@extends('layouts.app')

@section('title', 'Food Catalog - List')

@section('content')
@php
    $categories = ['All', 'Pizza', 'Salad', 'Meat', 'Seafood', 'Vegetarian', 'Dessert'];
    $foods = [
        ['name' => 'Margherita Pizza', 'category' => 'Pizza', 'desc' => 'Classic pizza with tomato, mozzarella, and basil.', 'tags' => ['Pizza', 'Vegetarian'], 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Veggie Pizza', 'category' => 'Pizza', 'desc' => 'Loaded with fresh vegetables.', 'tags' => ['Pizza', 'Vegetarian'], 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Caesar Salad', 'category' => 'Salad', 'desc' => 'Crisp romaine, parmesan, and creamy dressing.', 'tags' => ['Salad', 'Healthy'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Greek Salad', 'category' => 'Salad', 'desc' => 'Tomatoes, cucumbers, feta, and olives.', 'tags' => ['Salad', 'Greek'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Beef Burger', 'category' => 'Meat', 'desc' => 'Juicy beef patty with fresh toppings.', 'tags' => ['Burger', 'Meat'], 'img' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'BBQ Ribs', 'category' => 'Meat', 'desc' => 'Tender ribs with smoky BBQ sauce.', 'tags' => ['BBQ', 'Meat'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Chicken Curry', 'category' => 'Meat', 'desc' => 'Spicy chicken curry with rice.', 'tags' => ['Curry', 'Spicy'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Chicken Shawarma', 'category' => 'Meat', 'desc' => 'Spiced chicken wrapped in pita.', 'tags' => ['Wrap', 'Middle Eastern'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Sushi Platter', 'category' => 'Seafood', 'desc' => 'Assorted sushi rolls and sashimi.', 'tags' => ['Sushi', 'Seafood'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Grilled Salmon', 'category' => 'Seafood', 'desc' => 'Fresh salmon fillet grilled to perfection.', 'tags' => ['Fish', 'Healthy'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Shrimp Paella', 'category' => 'Seafood', 'desc' => 'Spanish rice dish with shrimp.', 'tags' => ['Rice', 'Seafood'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Lobster Bisque', 'category' => 'Seafood', 'desc' => 'Rich and creamy lobster soup.', 'tags' => ['Soup', 'Seafood'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Falafel Wrap', 'category' => 'Vegetarian', 'desc' => 'Chickpea balls wrapped with veggies.', 'tags' => ['Wrap', 'Vegetarian'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Vegetable Stir Fry', 'category' => 'Vegetarian', 'desc' => 'Mixed veggies sautéed in soy sauce.', 'tags' => ['Vegetarian', 'Asian'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Pad Thai', 'category' => 'Vegetarian', 'desc' => 'Stir-fried noodles with shrimp and peanuts.', 'tags' => ['Noodles', 'Thai'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Chocolate Cake', 'category' => 'Dessert', 'desc' => 'Rich chocolate cake with ganache.', 'tags' => ['Dessert', 'Cake'], 'img' => 'https://images.unsplash.com/photo-1519864600265-abb224b9bfa5?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Ice Cream Sundae', 'category' => 'Dessert', 'desc' => 'Vanilla ice cream with toppings.', 'tags' => ['Dessert', 'Ice Cream'], 'img' => 'https://images.unsplash.com/photo-1464306076886-debede1a7b8a?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Eggs Benedict', 'category' => 'Dessert', 'desc' => 'Poached eggs on English muffin.', 'tags' => ['Breakfast', 'Eggs'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Tacos', 'category' => 'Dessert', 'desc' => 'Corn tortillas filled with spiced meat.', 'tags' => ['Tacos', 'Mexican'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'Pasta Carbonara', 'category' => 'Dessert', 'desc' => 'Creamy pasta with bacon and cheese.', 'tags' => ['Pasta', 'Italian'], 'img' => 'https://images.unsplash.com/photo-1523987355523-c7b5b0723c6b?auto=format&fit=crop&w=400&q=80'],
    ];
    $merchCategories = ['All', 'T-Shirt', 'Mug', 'Tote Bag'];
    $merchGroups = [
        'T-Shirt' => ['UNISSA T-Shirt', 'UNISSA Polo Shirt', 'UNISSA Long Sleeve'],
        'Mug' => ['UNISSA Mug', 'UNISSA Travel Mug', 'UNISSA Classic Cup'],
        'Tote Bag' => ['UNISSA Tote Bag', 'UNISSA Canvas Bag', 'UNISSA Eco Bag'],
    ];
    $merchandise = [
        ['name' => 'UNISSA T-Shirt', 'category' => 'T-Shirt', 'desc' => 'Comfortable cotton t-shirt with UNISSA logo. Available in all sizes.', 'tags' => ['TShirt', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'UNISSA Polo Shirt', 'category' => 'T-Shirt', 'desc' => 'Smart polo shirt with embroidered UNISSA crest.', 'tags' => ['TShirt', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'UNISSA Long Sleeve', 'category' => 'T-Shirt', 'desc' => 'Long sleeve shirt for cooler days, UNISSA branding.', 'tags' => ['TShirt', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1526178613658-3f1622045557?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'UNISSA Mug', 'category' => 'Mug', 'desc' => 'Ceramic mug with UNISSA branding. Perfect for your morning coffee.', 'tags' => ['Mug', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1517685352821-92cf88aee5a5?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'UNISSA Travel Mug', 'category' => 'Mug', 'desc' => 'Insulated travel mug for hot and cold drinks.', 'tags' => ['Mug', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'UNISSA Classic Cup', 'category' => 'Mug', 'desc' => 'Classic white cup with UNISSA logo.', 'tags' => ['Mug', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'UNISSA Tote Bag', 'category' => 'Tote Bag', 'desc' => 'Eco-friendly tote bag for everyday use, featuring the UNISSA logo.', 'tags' => ['ToteBag', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80'],
        ['name' => 'UNISSA Canvas Bag', 'category' => 'Tote Bag', 'desc' => 'Durable canvas bag with large capacity.', 'tags' => ['ToteBag', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=400&q=80'],
        ['name' => 'UNISSA Eco Bag', 'category' => 'Tote Bag', 'desc' => 'Reusable eco bag with green UNISSA print.', 'tags' => ['ToteBag', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80'],
    ];
@endphp
<div x-data="foodMerchComponent()" x-cloak>
    <template x-if="tab === 'food'">
        <div>
            <section class="w-full flex items-center justify-center mb-8" style="height: 400px;">
                <div class="relative w-full h-full flex items-stretch shadow-lg overflow-hidden" style="height: 400px; border-top-right-radius: 200px; border-bottom-right-radius: 200px; margin-right: 32px;">
                    <div class="absolute inset-0 w-full h-full" style="background: linear-gradient(90deg, #ff5f33ff 10%, #fbbf24 50%, #22c55e 100%); pointer-events:none;"></div>
                    <div class="flex-1 flex items-center pl-12 z-10" style="width: 55%;">
                        <div class="text-left">
                            <h1 class="text-[4rem] font-extrabold text-white mb-2 drop-shadow-lg">Something's Food Catalog</h1>
                            <h2 class="text-4xl font-bold text-white mb-2 drop-shadow">Discover Wonderful Dishes</h2>
                            <p class="text-2xl text-white drop-shadow">From local favorites to global cuisine</p>
                        </div>
                    </div>
                    <img src="/nasii-lemak.png" alt="Nasi Lemak" class="object-cover rounded-full z-10" style="height: 360px; width: 360px; position: absolute; right: 30px; top: 50%; transform: translateY(-50%); object-fit: cover;" />
                </div>
            </section>
            <div class="w-full flex justify-center mb-8">
                <div class="inline-flex rounded-lg bg-gray-100 p-1 shadow">
                    <button type="button" @click="blurActive(); tab = 'food'" :class="tab === 'food' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Food & Beverages</button>
                    <button type="button" @click="blurActive(); tab = 'merch'" :class="tab === 'merch' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Merchandise</button>
                </div>
            </div>
            <!-- ...existing filter section... -->
            <section class="w-full flex flex-col gap-3 px-8 py-4 mb-8">
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
                    <div class="relative w-full sm:w-1/3">
                        <input type="text" placeholder="Search food..." x-model="foodSearch" @focus="showFoodPredictions = true" @blur="setTimeout(() => showFoodPredictions = false, 100)" class="w-full border border-teal-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                        <template x-if="foodSearch && showFoodPredictions">
                            <ul class="absolute left-0 right-0 bg-white border border-teal-200 rounded-b-lg shadow z-20 max-h-48 overflow-y-auto">
                                <template x-for="food in foods" :key="food.name">
                                    <template x-if="food.name.toLowerCase().includes(foodSearch.toLowerCase())">
                                        <li @mousedown.prevent="foodSearch = food.name; showFoodPredictions = false" class="px-4 py-2 hover:bg-teal-100 cursor-pointer text-sm" x-text="food.name"></li>
                                    </template>
                                </template>
                            </ul>
                        </template>
                    </div>
                    <div class="flex-1 flex flex-wrap gap-2 items-center justify-center sm:justify-start overflow-x-auto py-1">
                        @foreach ($categories as $cat)
                        <button type="button" @click="foodFilter = '{{ $cat }}'" :class="foodFilter === '{{ $cat }}' ? 'bg-teal-600 text-white' : 'bg-gray-100 text-teal-700'" class="px-4 py-1 rounded-full font-semibold text-sm hover:bg-teal-100 transition">{{ $cat }}</button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-teal-700">Sort by:</label>
                        <select class="border border-teal-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400">
                            <option value="">Sort By</option>
                            <option value="name">Name</option>
                            <option value="category">Category</option>
                        </select>
                    </div>
                </div>
            </section>
            <div class="flex flex-wrap justify-start gap-6 ml-8 mb-20">
                <template x-for="food in sortedFoods" :key="food.name">
                    <div>
                        <template x-if="food.name === 'Margherita Pizza'">
                            <a :href="'/review'" class="max-w-sm w-80 rounded overflow-hidden shadow-lg bg-white food-card flex flex-col" style="min-height: 210px; text-decoration: none;">
                                <div class="w-full h-48 food-image relative flex-shrink-0">
                                    <img :src="food.img" :alt="food.name" class="absolute inset-0 w-full h-full object-cover opacity-80" style="height: 185px;">
                                </div>
                                <div class="px-3 py-1 card-content flex-1 flex flex-col justify-between" style="min-height: 32px; height: 32px;">
                                    <div>
                                        <div class="font-bold text-base mb-1 card-title" x-text="food.name"></div>
                                        <p class="text-gray-700 text-xs card-description line-clamp-2" x-text="food.desc"></p>
                                    </div>
                                    <div class="px-0 pt-2 pb-1 tags-section">
                                        <template x-for="tag in food.tags" :key="tag">
                                            <span class="inline-block bg-teal-600 rounded-full px-2 py-0.5 text-xs font-semibold text-white mr-1 tag" x-text="'#' + tag"></span>
                                        </template>
                                    </div>
                                </div>
                            </a>
                        </template>
                        <template x-if="food.name !== 'Margherita Pizza'">
                            <div class="max-w-sm w-80 rounded overflow-hidden shadow-lg bg-white food-card flex flex-col" style="min-height: 210px;">
                                <div class="w-full h-48 food-image relative flex-shrink-0">
                                    <img :src="food.img" :alt="food.name" class="absolute inset-0 w-full h-full object-cover opacity-80" style="height: 185px;">
                                </div>
                                <div class="px-3 py-1 card-content flex-1 flex flex-col justify-between" style="min-height: 32px; height: 32px;">
                                    <div>
                                        <div class="font-bold text-base mb-1 card-title" x-text="food.name"></div>
                                        <p class="text-gray-700 text-xs card-description line-clamp-2" x-text="food.desc"></p>
                                    </div>
                                    <div class="px-0 pt-2 pb-1 tags-section">
                                        <template x-for="tag in food.tags" :key="tag">
                                            <span class="inline-block bg-teal-600 rounded-full px-2 py-0.5 text-xs font-semibold text-white mr-1 tag" x-text="'#' + tag"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </template>
    <template x-if="tab === 'merch'">
        <div>
            @php
                $merchCategories = ['All', 'T-Shirt', 'Mug', 'Tote Bag'];
                $merchGroups = [
                    'T-Shirt' => ['UNISSA T-Shirt', 'UNISSA Polo Shirt', 'UNISSA Long Sleeve'],
                    'Mug' => ['UNISSA Mug', 'UNISSA Travel Mug', 'UNISSA Classic Cup'],
                    'Tote Bag' => ['UNISSA Tote Bag', 'UNISSA Canvas Bag', 'UNISSA Eco Bag'],
                ];
            @endphp
            <section class="w-full flex items-center justify-center mb-8" style="height: 400px;">
                <div class="relative w-full h-full flex items-stretch shadow-lg overflow-hidden" style="height: 400px; border-top-right-radius: 200px; border-bottom-right-radius: 200px; margin-right: 32px;">
                    <div class="absolute inset-0 w-full h-full" style="background: linear-gradient(90deg, #6366f1 10%, #f472b6 50%, #fbbf24 100%); pointer-events:none;"></div>
                    <div class="flex-1 flex items-center pl-12 z-10" style="width: 55%;">
                        <div class="text-left">
                            <h1 class="text-[4rem] font-extrabold text-white mb-2 drop-shadow-lg">Something's Merchandise</h1>
                            <h2 class="text-4xl font-bold text-white mb-2 drop-shadow">Discover Unique Merchandise</h2>
                            <p class="text-2xl text-white drop-shadow">T-shirts, mugs, bags, and more</p>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=600&q=80" alt="Merchandise" class="object-cover rounded-full z-10 mb-8" style="height: 360px; width: 360px; position: absolute; right: 30px; top: 50%; transform: translateY(-50%); object-fit: cover;" />
                </div>
            </section>
            <div class="w-full flex justify-center mb-8">
                <div class="inline-flex rounded-lg bg-gray-100 p-1 shadow">
                    <button type="button" @click="blurActive(); tab = 'food'" :class="tab === 'food' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Food & Beverages</button>
                    <button type="button" @click="blurActive(); tab = 'merch'" :class="tab === 'merch' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Merchandise</button>
                </div>
            </div>
            <section class="w-full flex flex-col gap-3 px-8 py-4 mb-8">
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
                    <div class="relative w-full sm:w-1/3">
                        <input type="text" placeholder="Search merchandise..." x-model="merchSearch" @focus="showMerchPredictions = true" @blur="setTimeout(() => showMerchPredictions = false, 100)" class="w-full border border-indigo-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                        <template x-if="merchSearch && showMerchPredictions">
                            <ul class="absolute left-0 right-0 bg-white border border-indigo-200 rounded-b-lg shadow z-20 max-h-48 overflow-y-auto">
                                <template x-for="item in merchandise" :key="item.name">
                                    <template x-if="item.name.toLowerCase().includes(merchSearch.toLowerCase())">
                                        <li @mousedown.prevent="merchSearch = item.name; showMerchPredictions = false" class="px-4 py-2 hover:bg-indigo-100 cursor-pointer text-sm" x-text="item.name"></li>
                                    </template>
                                </template>
                            </ul>
                        </template>
                    </div>
                    <div class="flex-1 flex flex-wrap gap-2 items-center justify-center sm:justify-start overflow-x-auto py-1">
                        @foreach ($merchCategories as $cat)
                        <button type="button" @click="merchFilter = '{{ $cat }}'" :class="merchFilter === '{{ $cat }}' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-indigo-700'" class="px-4 py-1 rounded-full font-semibold text-sm hover:bg-indigo-100 transition">{{ $cat }}</button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-indigo-700">Sort by:</label>
                        <select class="border border-indigo-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">Sort By</option>
                            <option value="name">Name</option>
                            <option value="category">Category</option>
                        </select>
                    </div>
                </div>
            </section>
            <div class="flex flex-wrap justify-start gap-6 ml-8 mb-20">
                <template x-for="item in sortedMerch" :key="item.name">
                    <div class="max-w-sm w-80 rounded overflow-hidden shadow-lg bg-white merch-card flex flex-col" style="min-height: 210px;">
                        <div class="w-full h-48 merch-image relative flex-shrink-0">
                            <img :src="item.img" :alt="item.name" class="absolute inset-0 w-full h-full object-cover opacity-80" style="height: 185px;">
                        </div>
                        <div class="px-3 py-1 card-content flex-1 flex flex-col justify-between" style="min-height: 32px; height: 32px;">
                            <div>
                                <div class="font-bold text-base mb-1 card-title" x-text="item.name"></div>
                                <p class="text-gray-700 text-xs card-description line-clamp-2" x-text="item.desc"></p>
                            </div>
                            <div class="px-0 pt-2 pb-1 tags-section">
                                <template x-for="tag in item.tags" :key="tag">
                                    <span class="inline-block bg-indigo-600 rounded-full px-2 py-0.5 text-xs font-semibold text-white mr-1 tag" x-text="'#' + tag"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function foodMerchComponent() {
    return {
        tab: 'food',
        foodFilter: 'All',
        merchFilter: 'All',
        foodSearch: '',
        merchSearch: '',
        showFoodPredictions: false,
        showMerchPredictions: false,
        foods: @json($foods),
        merchandise: @json($merchandise),
        blurActive() {
            if (document.activeElement) document.activeElement.blur();
        },
        get sortedFoods() {
            let search = this.foodSearch.toLowerCase();
            let filtered = this.foods.filter(f => this.foodFilter === 'All' || f.category === this.foodFilter);
            return filtered.slice().sort((a, b) => {
                let aMatch = a.name.toLowerCase().includes(search) || a.desc.toLowerCase().includes(search);
                let bMatch = b.name.toLowerCase().includes(search) || b.desc.toLowerCase().includes(search);
                return (bMatch ? 1 : 0) - (aMatch ? 1 : 0);
            });
        },
        get sortedMerch() {
            let search = this.merchSearch.toLowerCase();
            let filtered = this.merchandise.filter(m => this.merchFilter === 'All' || m.category === this.merchFilter);
            return filtered.slice().sort((a, b) => {
                let aMatch = a.name.toLowerCase().includes(search) || a.desc.toLowerCase().includes(search);
                let bMatch = b.name.toLowerCase().includes(search) || b.desc.toLowerCase().includes(search);
                return (bMatch ? 1 : 0) - (aMatch ? 1 : 0);
            });
        }
    }
}
</script>
