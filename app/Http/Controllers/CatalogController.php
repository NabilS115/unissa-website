<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

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
                'type' => 'required|in:food,merch,others',
                'price' => 'required|numeric|min:0',
                'img' => 'nullable|image|max:20480',
                'cropped_image' => 'nullable|string',
                'is_active' => 'boolean',
                'status' => 'required|in:active,inactive,out_of_stock,discontinued',
                'track_stock' => 'boolean',
                'stock_quantity' => 'nullable|integer|min:0',
                'low_stock_threshold' => 'required|integer|min:0',
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

        // Set boolean fields (checkboxes) - they only come through if checked
        $validated['is_active'] = $request->has('is_active');
        $validated['track_stock'] = $request->has('track_stock');
        
        // Set stock quantity if provided, otherwise default to 0
        if (isset($validated['stock_quantity']) && $validated['stock_quantity'] !== null) {
            $validated['stock_quantity'] = $validated['stock_quantity'];
        } else {
            $validated['stock_quantity'] = 0;
        }

        try {
            // Handle cropped image data
            if ($request->filled('cropped_image')) {
                $croppedData = $request->input('cropped_image');
                
                // Remove data:image/jpeg;base64, prefix
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $croppedData);
                $imageData = base64_decode($imageData);
                
                if ($imageData === false) {
                    \Log::error('Failed to decode base64 image data');
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'error' => 'Invalid image data'], 422);
                    }
                    return back()->withErrors(['img' => 'Invalid image data'])->withInput();
                }
                
                // Generate unique filename
                $filename = uniqid('catalog_') . '.jpg';
                $path = 'catalog/' . $filename;
                
                // Save the file
                \Storage::disk('public')->put($path, $imageData);
                
                $validated['img'] = '/storage/' . $path;
            } elseif ($request->hasFile('img')) {
                // Handle regular file upload as fallback
                $path = $request->file('img')->store('catalog', 'public');
                $validated['img'] = '/storage/' . $path;
            } else {
                \Log::error('No image data provided');
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'error' => 'Image is required'], 422);
                }
                return back()->withErrors(['img' => 'Image is required'])->withInput();
            }

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
                
                // Immediately update stock status based on quantity (consistent with admin panel)
                if ($newProduct && $newProduct->id) {
                    $newProduct->updateStockStatus();
                }
                
                // Clear related caches when new product is created
                $this->clearProductCaches($newProduct->type);
                
            } catch (\Exception $e) {
                \Log::error('SQL/Product::create error', ['error' => $e->getMessage()]);
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'error' => 'SQL error: ' . $e->getMessage()], 500);
                }
                return back()->withErrors(['error' => 'SQL error: ' . $e->getMessage()])->withInput();
            }

            \Log::info('Catalog Product Creation - Product created successfully with automatic stock status', [
                'product_id' => $newProduct ? $newProduct->id : null,
                'name' => $newProduct ? $newProduct->name : null,
                'stock_quantity' => $newProduct ? $newProduct->stock_quantity : null,
                'auto_status' => $newProduct ? $newProduct->status : null,
                'exists' => $newProduct ? $newProduct->exists : false,
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
                return redirect()->route('unissa-cafe.catalog')
                    ->with('success', 'Product added!')
                    ->with('active_tab', $newProduct->type)
                    ->with('highlight_product', $newProduct->id);
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
            'type' => 'required|in:food,merch,others',
            'price' => 'required|numeric|min:0',
            'img' => 'nullable|image|max:20480',
            'cropped_image' => 'nullable|string',
            'is_active' => 'boolean',
            'status' => 'required|in:active,inactive,out_of_stock,discontinued',
            'track_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ], [
            'img.max' => 'The image must not be greater than 20MB. Please choose a smaller file.',
        ]);

        // Update basic product information
        $product->name = $validated['name'];
        $product->desc = $validated['desc'];
        $product->category = $validated['category'];
        $product->type = $validated['type'];
        $product->price = $validated['price'];
        $product->status = $validated['status'];
        $product->low_stock_threshold = $validated['low_stock_threshold'];
        
        // Set boolean fields (checkboxes)
        $product->is_active = $request->has('is_active');
        $product->track_stock = $request->has('track_stock');
        
        // Set stock quantity if provided
        if (isset($validated['stock_quantity']) && $validated['stock_quantity'] !== null) {
            // If stock quantity is changed, update last_restocked_at
            if ($product->stock_quantity != $validated['stock_quantity']) {
                $product->last_restocked_at = now();
            }
            $product->stock_quantity = $validated['stock_quantity'];
        }

        // Handle image update
        if ($request->filled('cropped_image')) {
            $croppedData = $request->input('cropped_image');
            
            // Remove data:image/jpeg;base64, prefix
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $croppedData);
            $imageData = base64_decode($imageData);
            
            if ($imageData !== false) {
                // Generate unique filename
                $filename = uniqid('catalog_') . '.jpg';
                $path = 'catalog/' . $filename;
                
                // Save the file
                Storage::disk('public')->put($path, $imageData);
                
                $product->img = '/storage/' . $path;
            }
        } elseif ($request->hasFile('img')) {
            // Handle regular file upload as fallback
            $path = $request->file('img')->store('catalog', 'public');
            $product->img = '/storage/' . $path;
        }

        $product->save();
        
        // Update stock status automatically (consistent with admin panel)
        $product->updateStockStatus();
        
        // Clear related caches when product is updated
        $this->clearProductCaches($product->type, $product->id);
        
        \Log::info('Catalog Product Edit - Product updated successfully with automatic stock status', [
            'product_id' => $product->id,
            'name' => $product->name,
            'stock_quantity' => $product->stock_quantity,
            'auto_status' => $product->status
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully', 'product' => $product]);
        }

        // Redirect back to the appropriate tab and highlight the updated product
        $returnTab = $request->input('return_tab', $product->type);
        $productId = $product->id;
        
        return redirect()->route('unissa-cafe.catalog')
            ->with('success', 'Product updated!')
            ->with('active_tab', $returnTab)
            ->with('highlight_product', $productId);
    }

    public function index()
    {
        // Redirect to unissa-cafe homepage (current main featured products page)
        return redirect()->route('unissa-cafe.homepage');
    }

    public function featured()
    {
        // Cache featured products for 1 hour
        $merchandise = Cache::remember('products.featured.merch', now()->addHour(), function () {
            // First try to get products with reviews (rating-based)
            $withReviews = \App\Models\Product::where('type', 'merch')
                ->where('is_active', true)
                ->with(['reviews' => function ($query) {
                    $query->select('product_id', 'rating');
                }])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->having('reviews_count', '>', 0)
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->limit(6)
                ->get();
            
            // If we don't have enough products with reviews, fill with newest products
            if ($withReviews->count() < 6) {
                $needed = 6 - $withReviews->count();
                $excludeIds = $withReviews->pluck('id')->toArray();
                
                $newest = \App\Models\Product::where('type', 'merch')
                    ->where('is_active', true)
                    ->whereNotIn('id', $excludeIds)
                    ->with(['reviews' => function ($query) {
                        $query->select('product_id', 'rating');
                    }])
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->latest()
                    ->limit($needed)
                    ->get();
                
                return $withReviews->merge($newest);
            }
            
            return $withReviews;
        });
        
        $food = Cache::remember('products.featured.food', now()->addHour(), function () {
            // First try to get products with reviews (rating-based)
            $withReviews = \App\Models\Product::where('type', 'food')
                ->where('is_active', true)
                ->with(['reviews' => function ($query) {
                    $query->select('product_id', 'rating');
                }])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->having('reviews_count', '>', 0)
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->limit(6)
                ->get();
            
            // If we don't have enough products with reviews, fill with newest products
            if ($withReviews->count() < 6) {
                $needed = 6 - $withReviews->count();
                $excludeIds = $withReviews->pluck('id')->toArray();
                
                $newest = \App\Models\Product::where('type', 'food')
                    ->where('is_active', true)
                    ->whereNotIn('id', $excludeIds)
                    ->with(['reviews' => function ($query) {
                        $query->select('product_id', 'rating');
                    }])
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->latest()
                    ->limit($needed)
                    ->get();
                
                return $withReviews->merge($newest);
            }
            
            return $withReviews;
        });
        
        $others = Cache::remember('products.featured.others', now()->addHour(), function () {
            // First try to get products with reviews (rating-based)
            $withReviews = \App\Models\Product::where('type', 'others')
                ->where('is_active', true)
                ->with(['reviews' => function ($query) {
                    $query->select('product_id', 'rating');
                }])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->having('reviews_count', '>', 0)
                ->orderByDesc('reviews_avg_rating')
                ->orderByDesc('reviews_count')
                ->limit(6)
                ->get();
            
            // If we don't have enough products with reviews, fill with newest products
            if ($withReviews->count() < 6) {
                $needed = 6 - $withReviews->count();
                $excludeIds = $withReviews->pluck('id')->toArray();
                
                $newest = \App\Models\Product::where('type', 'others')
                    ->where('is_active', true)
                    ->whereNotIn('id', $excludeIds)
                    ->with(['reviews' => function ($query) {
                        $query->select('product_id', 'rating');
                    }])
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->latest()
                    ->limit($needed)
                    ->get();
                
                return $withReviews->merge($newest);
            }
            
            return $withReviews;
        });
        
        // Get recent reviews for testimonials (latest 3 reviews with rating 4 or 5)
        $reviews = Cache::remember('reviews.testimonials', now()->addMinutes(20), function () {
            return Review::with(['user', 'product'])
                ->where('rating', '>=', 4)
                ->latest()
                ->limit(3)
                ->get();
        });
        
        return view('products.featured', compact('food', 'merchandise', 'others', 'reviews'));
    }

    public function browse()
    {
        // Cache product listings for 30 minutes
        $merchandise = Cache::remember('products.browse.merch', now()->addMinutes(30), function () {
            return \App\Models\Product::where('type', 'merch')
                ->where('is_active', true)
                ->with(['reviews' => function ($query) {
                    $query->select('product_id', 'rating');
                }])
                ->get();
        });
        
        $food = Cache::remember('products.browse.food', now()->addMinutes(30), function () {
            return \App\Models\Product::where('type', 'food')
                ->where('is_active', true)
                ->with(['reviews' => function ($query) {
                    $query->select('product_id', 'rating');
                }])
                ->get();
        });
        
        $others = Cache::remember('products.browse.others', now()->addMinutes(30), function () {
            return \App\Models\Product::where('type', 'others')
                ->where('is_active', true)
                ->with(['reviews' => function ($query) {
                    $query->select('product_id', 'rating');
                }])
                ->get();
        });
        
        // Ensure others is never null or empty when products exist
        if (is_null($others)) {
            $others = \App\Models\Product::where('type', 'others')
                ->where('is_active', true)
                ->with(['reviews' => function ($query) {
                    $query->select('product_id', 'rating');
                }])
                ->get();
        }
        
        $categories = Cache::remember('products.categories', now()->addHour(), function () {
            return \App\Models\Product::where('is_active', true)
                ->pluck('category')
                ->unique()
                ->values()
                ->all();
        });
        
        return view('products.browse', compact('food', 'merchandise', 'others', 'categories'));
    }

    public function destroy($id)
    {
        // Only allow admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }
        
        try {
            $product = Product::findOrFail($id);
            $productType = $product->type;
            $productId = $product->id;
            
            $product->delete();
            
            // Clear related caches when product is deleted
            $this->clearProductCaches($productType, $productId);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
            }
            
            return redirect()->route('unissa-cafe.catalog')->with('success', 'Product deleted!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error deleting product: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Error deleting product: ' . $e->getMessage()]);
        }
    }

    /**
     * Get catalog data as JSON (for AJAX requests)
     */
    public function getData()
    {
        $food = Product::where('type', 'food')->get();
        $merchandise = Product::where('type', 'merch')->get();
        
        // Calculate ratings for each product
        foreach ($food as $product) {
            $this->calculateProductRating($product);
        }
        
        foreach ($merchandise as $product) {
            $this->calculateProductRating($product);
        }
        
        return response()->json([
            'food' => $food,
            'merchandise' => $merchandise
        ]);
    }

    /**
     * Calculate average rating for a product
     */
    private function calculateProductRating($product)
    {
        $reviews = Review::where('product_id', $product->id)->get();
        $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            if ($rating >= 1 && $rating <= 5) {
                $ratings[$rating]++;
            }
        }
        
        $totalRatings = array_sum($ratings);
        $averageRating = 0;
        
        if ($totalRatings > 0) {
            $weightedSum = 0;
            foreach ($ratings as $star => $count) {
                $weightedSum += $star * $count;
            }
            $averageRating = $weightedSum / $totalRatings;
        }
        
        $product->calculated_rating = number_format($averageRating, 1);
        $product->review_count = $totalRatings;
    }

    public function store(Request $request)
    {
        return $this->add($request);
    }

    public function update(Request $request, $id)
    {
        return $this->edit($request, $id);
    }
    
    /**
     * Clear product-related caches when products are modified
     * Improved cache management with categorized clearing
     */
    private function clearProductCaches($productType = null, $productId = null)
    {
        try {
            // Clear browse page caches
            Cache::forget('products.browse.food');
            Cache::forget('products.browse.merch');
            Cache::forget('products.browse.others');
            Cache::forget('products.categories');
            
            // Clear featured products caches
            Cache::forget('products.featured.food');
            Cache::forget('products.featured.merch');
            Cache::forget('products.featured.others');
            
            // Clear admin statistics cache
            Cache::forget('admin.product.stats');
            
            // Clear specific product detail cache if ID provided
            if ($productId) {
                Cache::forget("product.detail.{$productId}");
            }
            
            // Clear search-related caches
            $this->clearSearchCaches();
            
            // Clear testimonials cache
            Cache::forget('reviews.testimonials');
            
            // Log cache clearing for debugging
            \Log::info('Product cache cleared successfully', [
                'type' => $productType,
                'product_id' => $productId
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Cache clearing failed', [
                'error' => $e->getMessage(),
                'type' => $productType,
                'product_id' => $productId
            ]);
        }
    }
    
    /**
     * Clear search-related caches
     * Improved cache clearing with better organization
     */
    private function clearSearchCaches()
    {
        try {
            // Clear common search patterns
            $searchTypes = ['food', 'merch', 'others'];
            $commonQueries = ['', 'popular', 'new', 'active'];
            
            foreach ($searchTypes as $type) {
                foreach ($commonQueries as $query) {
                    Cache::forget("search.{$type}.{$query}");
                }
            }
            
            // Clear global search caches
            Cache::forget('search.suggestions');
            Cache::forget('search.categories');
            Cache::forget('search.filters');
            
            \Log::info('Search caches cleared successfully');
            
        } catch (\Exception $e) {
            \Log::error('Search cache clearing failed', ['error' => $e->getMessage()]);
        }
    }
}
