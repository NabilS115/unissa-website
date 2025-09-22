<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    public function add(Request $request)
    {
        \Log::info('Add product request received', $request->all());

        // Log if request method is not POST
        if (!$request->isMethod('post')) {
            \Log::error('Request method is not POST');
            return back()->withErrors(['error' => 'Invalid request method'])->withInput();
        }

        // Log validation errors
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'desc' => 'required|string',
                'category' => 'required|string|max:255',
                // Increase max size to 20MB (20480 KB)
                'img' => 'required|image|max:20480',
                'type' => 'required|in:food,merch',
            ], [
                // Custom error message for image size
                'img.max' => 'The image must not be greater than 20MB. Please choose a smaller file.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            // Optionally, add a flash message for the user
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            if (!$request->hasFile('img')) {
                \Log::error('No image file found in request');
                return back()->withErrors(['img' => 'Image file is required'])->withInput();
            }

            $path = $request->file('img')->store('catalog', 'public');
            $imgPath = '/storage/' . $path;

            $validated['img'] = $imgPath;

            \Log::info('Validated data before create', $validated);

            // Ensure $validated['img'] exists
            if (empty($validated['img'])) {
                \Log::error('Validated array missing img key', $validated);
                return back()->withErrors(['img' => 'Image upload failed.'])->withInput();
            }

            // Log Product model fillable fields
            $fillable = (new Product)->getFillable();
            \Log::info('Product model fillable fields', $fillable);

            // Check for missing fillable fields
            $missing = array_diff(array_keys($validated), $fillable);
            if ($missing) {
                \Log::warning('Validated keys not in fillable', $missing);
            }

            // Try to create product and catch SQL errors
            try {
                $newProduct = Product::create($validated);
            } catch (\Exception $e) {
                \Log::error('SQL/Product::create error', ['error' => $e->getMessage()]);
                return back()->withErrors(['error' => 'SQL error: ' . $e->getMessage()])->withInput();
            }

            \Log::info('Product::create result', [
                'newProduct' => $newProduct ? $newProduct->toArray() : null,
                'exists' => $newProduct ? $newProduct->exists : false,
                'id' => $newProduct ? $newProduct->id : null,
            ]);

            // Check if the product is actually in the database
            $check = Product::where('name', $validated['name'])->first();
            \Log::info('Product check after create', [
                'check' => $check ? $check->toArray() : null,
            ]);

            if ($newProduct && $newProduct->id) {
                \Log::info('Product saved to database', $newProduct->toArray());
                return redirect()->route('products.catalog')->with('success', 'Product added!');
            } else {
                \Log::error('Product save failed', [
                    'validated' => $validated,
                    'newProduct' => $newProduct,
                    'db_error' => \DB::connection()->getPdo() ? null : 'No PDO connection'
                ]);
                return back()->withErrors(['error' => 'Failed to save product'])->withInput();
            }
        } catch (\Exception $e) {
            \Log::error('Product add failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to add product: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'required|string|max:255',
            'img' => 'nullable|image|max:2048',
        ]);
        // Example: $product = Product::findOrFail($id);
        // Update fields
        // $product->name = $request->name;
        // $product->desc = $request->desc;
        // $product->category = $request->category;
        // if ($request->hasFile('img')) {
        //     $path = $request->file('img')->store('catalog', 'public');
        //     $product->img = '/storage/' . $path;
        // }
        // $product->save();
        return back()->with('success', 'Product updated!');
    }

    public function upload(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'type' => 'required|in:food,merch',
        ]);
        $path = $request->file('image')->store('catalog', 'public');
        // Example: Save image path and type to your model/table
        // ProductImage::create([
        //     'img' => '/storage/' . $path,
        //     'type' => $request->type,
        // ]);
        return back()->with('success', 'Image uploaded!');
    }

    public function index()
    {
        $products = Product::all();
        \Log::info('CatalogController@index products SQL', [
            'sql' => Product::query()->toSql(),
            'connection' => (new Product)->getConnectionName(),
            'table' => (new Product)->getTable(),
        ]);
        \Log::info('CatalogController@index products count', ['count' => $products->count()]);
        \Log::info('CatalogController@index products', $products->toArray());
        if ($products->isEmpty()) {
            \Log::warning('No products found in products table');
        }
        $food = $products->where('type', 'food')->values();
        $merchandise = $products->where('type', 'merch')->values();
        $categories = $products->pluck('category')->unique()->values();
        return view('products.catalog', compact('products', 'food', 'merchandise', 'categories'));
    }
}