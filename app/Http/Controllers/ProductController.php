<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function catalog()
    {
        $food = Product::where('type', 'food')->get();
        $merchandise = Product::where('type', 'merch')->get();
        $categories = Product::pluck('category')->unique()->values()->all();
        return view('products.catalog', compact('food', 'merchandise', 'categories'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'desc' => 'required|string',
                'category' => 'required|string|max:255',
                'type' => 'required|in:food,merch',
                'img' => 'required|image|max:2048',
            ]);

            // Handle image upload
            $imgPath = $request->file('img')->store('products', 'public');

            $product = Product::create([
                'name' => $validated['name'],
                'desc' => $validated['desc'],
                'category' => $validated['category'],
                'type' => $validated['type'],
                'img' => '/storage/' . $imgPath,
                // Add other fields as needed
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'product' => $product]);
            }
            return redirect()->route('catalog')->with('success', 'Product added!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500);
            }
            throw $e;
        }
    }

    // ...other controller methods...
}