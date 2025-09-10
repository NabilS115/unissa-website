@extends('layouts.app')

@section('title', 'Food Catalog - List')

@section('content')
<div x-data="{
    tab: 'food',
    foodFilter: 'All',
    merchFilter: 'All',
}" x-cloak>
    <template x-if="tab === 'food'">
        <div>
            @php
                $categories = ['All', 'Pizza', 'Salad', 'Meat', 'Seafood', 'Vegetarian', 'Dessert'];
                $groups = [
                    'Pizza' => ['Margherita Pizza', 'Veggie Pizza'],
                    'Salad' => ['Caesar Salad', 'Greek Salad'],
                    'Meat' => ['Beef Burger', 'BBQ Ribs', 'Chicken Curry', 'Chicken Shawarma'],
                    'Seafood' => ['Sushi Platter', 'Grilled Salmon', 'Shrimp Paella', 'Lobster Bisque'],
                    'Vegetarian' => ['Falafel Wrap', 'Vegetable Stir Fry', 'Pad Thai'],
                    'Dessert' => ['Chocolate Cake', 'Ice Cream Sundae', 'Eggs Benedict', 'Tacos', 'Pasta Carbonara'],
                ];
                $foods = [
                    ['name' => 'Margherita Pizza', 'desc' => 'Classic pizza with tomato, mozzarella, and basil.', 'tags' => ['Pizza', 'Vegetarian'], 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Caesar Salad', 'desc' => 'Crisp romaine, parmesan, and creamy dressing.', 'tags' => ['Salad', 'Healthy'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Beef Burger', 'desc' => 'Juicy beef patty with fresh toppings.', 'tags' => ['Burger', 'Meat'], 'img' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Sushi Platter', 'desc' => 'Assorted sushi rolls and sashimi.', 'tags' => ['Sushi', 'Seafood'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Pad Thai', 'desc' => 'Stir-fried noodles with shrimp and peanuts.', 'tags' => ['Noodles', 'Thai'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Tacos', 'desc' => 'Corn tortillas filled with spiced meat.', 'tags' => ['Tacos', 'Mexican'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Falafel Wrap', 'desc' => 'Chickpea balls wrapped with veggies.', 'tags' => ['Wrap', 'Vegetarian'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Grilled Salmon', 'desc' => 'Fresh salmon fillet grilled to perfection.', 'tags' => ['Fish', 'Healthy'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Chicken Curry', 'desc' => 'Spicy chicken curry with rice.', 'tags' => ['Curry', 'Spicy'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Pasta Carbonara', 'desc' => 'Creamy pasta with bacon and cheese.', 'tags' => ['Pasta', 'Italian'], 'img' => 'https://images.unsplash.com/photo-1523987355523-c7b5b0723c6b?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Greek Salad', 'desc' => 'Tomatoes, cucumbers, feta, and olives.', 'tags' => ['Salad', 'Greek'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'BBQ Ribs', 'desc' => 'Tender ribs with smoky BBQ sauce.', 'tags' => ['BBQ', 'Meat'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Vegetable Stir Fry', 'desc' => 'Mixed veggies sautéed in soy sauce.', 'tags' => ['Vegetarian', 'Asian'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Lobster Bisque', 'desc' => 'Rich and creamy lobster soup.', 'tags' => ['Soup', 'Seafood'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Eggs Benedict', 'desc' => 'Poached eggs on English muffin.', 'tags' => ['Breakfast', 'Eggs'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Chicken Shawarma', 'desc' => 'Spiced chicken wrapped in pita.', 'tags' => ['Wrap', 'Middle Eastern'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Veggie Pizza', 'desc' => 'Loaded with fresh vegetables.', 'tags' => ['Pizza', 'Vegetarian'], 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Shrimp Paella', 'desc' => 'Spanish rice dish with shrimp.', 'tags' => ['Rice', 'Seafood'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Chocolate Cake', 'desc' => 'Rich chocolate cake with ganache.', 'tags' => ['Dessert', 'Cake'], 'img' => 'https://images.unsplash.com/photo-1519864600265-abb224b9bfa5?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'Ice Cream Sundae', 'desc' => 'Vanilla ice cream with toppings.', 'tags' => ['Dessert', 'Ice Cream'], 'img' => 'https://images.unsplash.com/photo-1464306076886-debede1a7b8a?auto=format&fit=crop&w=400&q=80'],
                ];
            @endphp
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
                    <button @click="tab = 'food'" :class="tab === 'food' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Food & Beverages</button>
                    <button @click="tab = 'merch'" :class="tab === 'merch' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Merchandise</button>
                </div>
            </div>
            <!-- ...existing filter section... -->
            <section class="w-full flex flex-col gap-3 px-8 py-4 mb-8">
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
                    <input type="text" placeholder="Search food..." class="w-full sm:w-1/3 border border-teal-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
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
            <div class="space-y-12">
                @foreach ($groups as $group => $names)
                    <template x-if="foodFilter === 'All' || foodFilter === '{{ $group }}'">
                    <div>
                        <div class="flex items-center mb-6">
                            <h3 class="ml-2 pr-4 text-lg font-semibold text-teal-700 tracking-wide uppercase bg-white">{{ $group }}</h3>
                            <div class="flex-1 border-b border-teal-300 ml-2"></div>
                        </div>
                        <div class="flex flex-wrap justify-start gap-6 ml-8">
                            @foreach ($foods as $food)
                                @if (in_array($food['name'], $names))
                                    @if ($food['name'] === 'Margherita Pizza')
                                        <a href="/review" class="max-w-sm w-80 rounded overflow-hidden shadow-lg bg-white food-card flex flex-col" style="min-height: 210px; text-decoration: none;">
                                            <div class="w-full h-48 food-image relative flex-shrink-0">
                                                <img src="{{ $food['img'] }}" alt="{{ $food['name'] }}" class="absolute inset-0 w-full h-full object-cover opacity-80" style="height: 185px;">
                                            </div>
                                            <div class="px-3 py-1 card-content flex-1 flex flex-col justify-between" style="min-height: 32px; height: 32px;">
                                                <div>
                                                    <div class="font-bold text-base mb-1 card-title">{{ $food['name'] }}</div>
                                                    <p class="text-gray-700 text-xs card-description line-clamp-2">
                                                        {{ $food['desc'] }}
                                                    </p>
                                                </div>
                                                <div class="px-0 pt-2 pb-1 tags-section">
                                                    @foreach ($food['tags'] as $tag)
                                                        <span class="inline-block bg-teal-600 rounded-full px-2 py-0.5 text-xs font-semibold text-white mr-1 tag">#{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <div class="max-w-sm w-80 rounded overflow-hidden shadow-lg bg-white food-card flex flex-col" style="min-height: 210px;">
                                            <div class="w-full h-48 food-image relative flex-shrink-0">
                                                <img src="{{ $food['img'] }}" alt="{{ $food['name'] }}" class="absolute inset-0 w-full h-full object-cover opacity-80" style="height: 185px;">
                                            </div>
                                            <div class="px-3 py-1 card-content flex-1 flex flex-col justify-between" style="min-height: 32px; height: 32px;">
                                                <div>
                                                    <div class="font-bold text-base mb-1 card-title">{{ $food['name'] }}</div>
                                                    <p class="text-gray-700 text-xs card-description line-clamp-2">
                                                        {{ $food['desc'] }}
                                                    </p>
                                                </div>
                                                <div class="px-0 pt-2 pb-1 tags-section">
                                                    @foreach ($food['tags'] as $tag)
                                                        <span class="inline-block bg-teal-600 rounded-full px-2 py-0.5 text-xs font-semibold text-white mr-1 tag">#{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                    </template>
                @endforeach
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
                $merchandise = [
                    ['name' => 'UNISSA T-Shirt', 'desc' => 'Comfortable cotton t-shirt with UNISSA logo. Available in all sizes.', 'tags' => ['TShirt', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Polo Shirt', 'desc' => 'Smart polo shirt with embroidered UNISSA crest.', 'tags' => ['TShirt', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Long Sleeve', 'desc' => 'Long sleeve shirt for cooler days, UNISSA branding.', 'tags' => ['TShirt', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1526178613658-3f1622045557?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Mug', 'desc' => 'Ceramic mug with UNISSA branding. Perfect for your morning coffee.', 'tags' => ['Mug', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1517685352821-92cf88aee5a5?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Travel Mug', 'desc' => 'Insulated travel mug for hot and cold drinks.', 'tags' => ['Mug', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Classic Cup', 'desc' => 'Classic white cup with UNISSA logo.', 'tags' => ['Mug', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Tote Bag', 'desc' => 'Eco-friendly tote bag for everyday use, featuring the UNISSA logo.', 'tags' => ['ToteBag', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Canvas Bag', 'desc' => 'Durable canvas bag with large capacity.', 'tags' => ['ToteBag', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=400&q=80'],
                    ['name' => 'UNISSA Eco Bag', 'desc' => 'Reusable eco bag with green UNISSA print.', 'tags' => ['ToteBag', 'Merch'], 'img' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80'],
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
                    <img src="/merch-banner.png" alt="Merchandise" class="object-cover rounded-full z-10" style="height: 360px; width: 360px; position: absolute; right: 30px; top: 50%; transform: translateY(-50%); object-fit: cover;" />
                </div>
            </section>
            <div class="w-full flex justify-center mb-8">
                <div class="inline-flex rounded-lg bg-gray-100 p-1 shadow">
                    <button @click="tab = 'food'" :class="tab === 'food' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Food & Beverages</button>
                    <button @click="tab = 'merch'" :class="tab === 'merch' ? 'bg-teal-600 text-white' : 'bg-transparent text-teal-700'" class="px-6 py-2 rounded-lg font-semibold focus:outline-none transition">Merchandise</button>
                </div>
            </div>
            <section class="w-full flex flex-col gap-3 px-8 py-4 mb-8">
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between w-full">
                    <input type="text" placeholder="Search merchandise..." class="w-full sm:w-1/3 border border-indigo-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
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
            <div class="space-y-12">
                @foreach ($merchGroups as $group => $names)
                    <template x-if="merchFilter === 'All' || merchFilter === '{{ $group }}'">
                    <div>
                        <div class="flex items-center mb-6">
                            <h3 class="ml-2 pr-4 text-lg font-semibold text-indigo-700 tracking-wide uppercase bg-white">{{ $group }}</h3>
                            <div class="flex-1 border-b border-indigo-300 ml-2"></div>
                        </div>
                        <div class="flex flex-wrap justify-start gap-6 ml-8">
                            @foreach ($merchandise as $item)
                                @if (in_array($item['name'], $names))
                                    <div class="max-w-sm w-80 rounded overflow-hidden shadow-lg bg-white merch-card flex flex-col" style="min-height: 210px;">
                                        <div class="w-full h-48 merch-image relative flex-shrink-0">
                                            <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="absolute inset-0 w-full h-full object-cover opacity-80" style="height: 185px;">
                                        </div>
                                        <div class="px-3 py-1 card-content flex-1 flex flex-col justify-between" style="min-height: 32px; height: 32px;">
                                            <div>
                                                <div class="font-bold text-base mb-1 card-title">{{ $item['name'] }}</div>
                                                <p class="text-gray-700 text-xs card-description line-clamp-2">
                                                    {{ $item['desc'] }}
                                                </p>
                                            </div>
                                            <div class="px-0 pt-2 pb-1 tags-section">
                                                @foreach ($item['tags'] as $tag)
                                                    <span class="inline-block bg-indigo-600 rounded-full px-2 py-0.5 text-xs font-semibold text-white mr-1 tag">#{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    </template>
                @endforeach
            </div>
        </div>
    </template>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
