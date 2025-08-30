
@extends('layouts.app')

@section('title', 'Food Catalog - List')

@section('content')
    <section class="w-full h-64 flex flex-col items-center justify-center mb-8 relative">
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80" alt="Food Banner" class="absolute inset-0 w-full h-full object-cover opacity-70">
        <div class="relative z-10 text-center">
        <div class="relative z-10 text-center">
            <h2 class="text-4xl font-extrabold text-white drop-shadow-lg mb-2">Explore Our Food Selection</h2>
            <p class="text-lg text-white drop-shadow-md">Browse 20 delicious dishes from around the world.</p>
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
                    <h3 class="ml-20 pr-4 text-lg font-semibold text-teal-700 tracking-wide uppercase bg-white">{{ $group }}</h3>
                    <div class="flex-1 border-b border-teal-300 ml-2"></div>
                </div>
                <div class="flex flex-wrap justify-center gap-6">
                    @foreach ($foods as $food)
                        @if (in_array($food['name'], $names))
                            <div class="max-w-sm w-80 rounded overflow-hidden shadow-lg bg-white food-card flex flex-col" style="min-height: 420px;">
                                <div class="w-full h-48 food-image relative flex-shrink-0">
                                    <img src="{{ $food['img'] }}" alt="{{ $food['name'] }}" class="absolute inset-0 w-full h-full object-cover opacity-80" style="height: 192px;">
                                </div>
                                <div class="px-6 py-4 card-content flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="font-bold text-xl mb-2 card-title">{{ $food['name'] }}</div>
                                        <p class="text-gray-700 text-base card-description line-clamp-3">
                                            {{ $food['desc'] }}
                                        </p>
                                    </div>
                                    <div class="px-0 pt-4 pb-2 tags-section">
                                        @foreach ($food['tags'] as $tag)
                                            <span class="inline-block bg-teal-600 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 tag">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
