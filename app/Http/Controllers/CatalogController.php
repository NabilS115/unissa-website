<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    public function add(Request $request)
    {
        // Only allow admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        \Log::info('Add product request received', $request->all());

        if (!$request->isMethod('post')) {
            \Log::error('Request method is not POST');
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Invalid request method'], 405);
            }
            return back()->withErrors(['error' => 'Invalid request method'])->withInput();
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'desc' => 'required|string',
                'category' => 'required|string|max:255',
                'img' => 'required|image|max:20480',
                'type' => 'required|in:food,merch',
            ], [
                'img.max' => 'The image must not be greater than 20MB. Please choose a smaller file.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            if (!$request->hasFile('img')) {
                \Log::error('No image file found in request');
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'error' => 'Image file is required'], 422);
                }
                return back()->withErrors(['img' => 'Image file is required'])->withInput();
            }

            $path = $request->file('img')->store('catalog', 'public');
            $imgPath = '/storage/' . $path;
            $validated['img'] = $imgPath;

            \Log::info('Validated data before create', $validated);

            if (empty($validated['img'])) {
                \Log::error('Validated array missing img key', $validated);
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'error' => 'Image upload failed.'], 500);
                }
                return back()->withErrors(['img' => 'Image upload failed.'])->withInput();
            }

            $fillable = (new Product)->getFillable();
            \Log::info('Product model fillable fields', $fillable);

            $missing = array_diff(array_keys($validated), $fillable);
            if ($missing) {
                \Log::warning('Validated keys not in fillable', $missing);
            }

            try {
                $newProduct = Product::create($validated);
            } catch (\Exception $e) {
                \Log::error('SQL/Product::create error', ['error' => $e->getMessage()]);
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'error' => 'SQL error: ' . $e->getMessage()], 500);
                }
                return back()->withErrors(['error' => 'SQL error: ' . $e->getMessage()])->withInput();
            }

            \Log::info('Product::create result', [
                'newProduct' => $newProduct ? $newProduct->toArray() : null,
                'exists' => $newProduct ? $newProduct->exists : false,
                'id' => $newProduct ? $newProduct->id : null,
            ]);

            $check = Product::where('name', $validated['name'])->first();
            \Log::info('Product check after create', [
                'check' => $check ? $check->toArray() : null,
            ]);

            if ($newProduct && $newProduct->id) {
                \Log::info('Product saved to database', $newProduct->toArray());
                if ($request->expectsJson()) {
                    return response()->json(['success' => true, 'product' => $newProduct]);
                }
                return redirect()->route('products.catalog')->with('success', 'Product added!');
            } else {
                \Log::error('Product save failed', [
                    'validated' => $validated,
                    'newProduct' => $newProduct,
                    'db_error' => \DB::connection()->getPdo() ? null : 'No PDO connection'
                ]);
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'error' => 'Failed to save product'], 500);
                }
                return back()->withErrors(['error' => 'Failed to save product'])->withInput();
            }
        } catch (\Exception $e) {
            \Log::error('Product add failed: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Failed to add product: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Failed to add product: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'required|string|max:255',
            'type' => 'required|in:food,merch',
            'img' => 'nullable|image|max:20480',
            'crop_img' => 'nullable',
        ], [
            'img.max' => 'The image must not be greater than 20MB. Please choose a smaller file.',
        ]);

        $product->name = $validated['name'];
        $product->desc = $validated['desc'];
        $product->category = $validated['category'];
        $product->type = $validated['type'];

        if ($request->hasFile('img')) {
            $imgFile = $request->file('img');
            if ($request->has('crop_img')) {
                // Crop image to square (center) using Intervention Image
                $image = \Intervention\Image\Facades\Image::make($imgFile)->fit(400, 400);
                $filename = uniqid('catalog_') . '.' . $imgFile->getClientOriginalExtension();
                $path = storage_path('app/public/catalog/' . $filename);
                $image->save($path);
                $product->img = '/storage/catalog/' . $filename;
            } else {
                $path = $imgFile->store('catalog', 'public');
                $product->img = '/storage/' . $path;
            }
        }

        $product->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully']);
        }

        return redirect()->route('products.catalog')->with('success', 'Product updated!');
    }

    public function upload(\Illuminate\Http\Request $request)
    {
        // Only allow admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

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
        $merchandise = \App\Models\Product::where('type', 'merch')->get();
        $food = \App\Models\Product::where('type', 'food')->get();
        $categories = \App\Models\Product::pluck('category')->unique()->values()->all();
        return view('products.catalog', compact('food', 'merchandise', 'categories'));
    }

    public function destroy($id)
    {
        // Only allow admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.catalog')->with('success', 'Product deleted!');
    }
}