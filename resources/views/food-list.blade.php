@extends('layouts.app')

@section('title', 'Food Catalog - List')

@section('content')
    
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

    <!-- Search, Filter, Sort Section -->
    <section class="w-full flex flex-wrap items-center justify-between gap-4 px-8 py-4 mb-8">
        <div class="flex-1 min-w-[200px]">
            <input type="text" placeholder="Search food..." class="w-full border border-teal-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
        </div>
        <div class="flex items-center gap-4">
                <label class="text-sm font-medium text-teal-700 mr-1">Category:</label>
            <select class="border border-teal-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">All Categories</option>
                <option value="Pizza">Pizza</option>
                <option value="Salad">Salad</option>
                <option value="Meat">Meat</option>
                <option value="Seafood">Seafood</option>
                <option value="Vegetarian">Vegetarian</option>
                <option value="Dessert">Dessert</option>
            </select>
                <label class="text-sm font-medium text-teal-700 ml-2 mr-1">Sort by:</label>
            <select class="border border-teal-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">Sort By</option>
                <option value="name">Name</option>
                <option value="category">Category</option>
            </select>
        </div>
    </section>

    @php
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
    <div class="space-y-12">
        @foreach ($groups as $group => $names)
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
        @endforeach
    </div>
@endsection
