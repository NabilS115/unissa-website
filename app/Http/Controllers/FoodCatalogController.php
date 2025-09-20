<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Merchandise;

class FoodCatalogController extends Controller
{
    public function index()
    {
        $categories = Food::query()->distinct()->pluck('category')->toArray();
        array_unshift($categories, 'All');
        $foods = Food::all();
        $merchandise = Merchandise::all();
        return view('catalog', compact('categories', 'foods', 'merchandise'));
    }
}
