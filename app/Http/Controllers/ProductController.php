<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function catalog()
    {
        // Only show available products to customers
        $food = Product::available()->where('type', 'food')->get();
        $merchandise = Product::available()->where('type', 'merch')->get();
        $categories = Product::active()->pluck('category')->unique()->values()->all();
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
            return redirect()->route('unissa-cafe.homepage')->with('success', 'Product added!');
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

    /**
     * Show product details with availability check
     */
    public function show(Product $product)
    {
        // Show product even if not available, but indicate status
        $product->load('reviews');
        return view('product-detail', compact('product'));
    }

    /**
     * Admin catalog view (shows all products regardless of status)
     */
    public function adminCatalog()
    {
        $food = Product::where('type', 'food')->get();
        $merchandise = Product::where('type', 'merch')->get();
        $categories = Product::pluck('category')->unique()->values()->all();
        
        // Add statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'out_of_stock' => Product::outOfStock()->count(),
            'low_stock' => Product::lowStock()->count(),
            'discontinued' => Product::where('status', Product::STATUS_DISCONTINUED)->count(),
        ];

        return view('admin.products.catalog', compact('food', 'merchandise', 'categories', 'stats'));
    }

    // ...other controller methods...
}