<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $results = [];
        // Example: Search users by name or email
        if ($query) {
            $results['users'] = \App\Models\User::where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->get();
            // Search foods from the static array in food-list.blade.php
            $foods = [
                ['name' => 'Margherita Pizza', 'desc' => 'Classic pizza with tomato, mozzarella, and basil.', 'tags' => ['Pizza', 'Vegetarian'], 'img' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?auto=format&fit=crop&w=400&q=80'],
                ['name' => 'Caesar Salad', 'desc' => 'Crisp romaine, parmesan, and creamy dressing.', 'tags' => ['Salad', 'Healthy'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                ['name' => 'Beef Burger', 'desc' => 'Juicy beef patty with fresh toppings.', 'tags' => ['Burger', 'Meat'], 'img' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=400&q=80'],
                ['name' => 'Greek Salad', 'desc' => 'Tomatoes, cucumbers, feta, and olives.', 'tags' => ['Salad', 'Greek'], 'img' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80'],
                // ...add other foods here as needed...
            ];
            $results['foods'] = collect($foods)->filter(function($food) use ($query) {
                return stripos($food['name'], $query) !== false || stripos($food['desc'], $query) !== false || collect($food['tags'])->contains(function($tag) use ($query) {
                    return stripos($tag, $query) !== false;
                });
            });
        }
        return view('search-results', compact('query', 'results'));
    }
}
