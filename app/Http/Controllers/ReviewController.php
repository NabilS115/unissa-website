<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ReviewController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('review', compact('product'));
    }
}
