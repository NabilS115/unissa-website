<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminProductController extends Controller
{
    // Middleware is handled at route level in web.php

    /**
     * Display a listing of all products for admin management.
     */
    public function index(Request $request)
    {
        $query = Product::with('orders');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('track_stock', false)
                          ->orWhere(function($q) {
                              $q->where('track_stock', true)->where('stock_quantity', '>', 0);
                          });
                    break;
                case 'out_of_stock':
                    $query->outOfStock();
                    break;
                case 'low_stock':
                    $query->lowStock();
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $products = $query->paginate(20);

        // Get statistics
        $stats = $this->getProductStatistics();

        return view('admin.products.index', compact('products', 'stats'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Product::pluck('category')->unique()->filter()->values();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log all incoming request data
        \Log::info('Admin Product Creation - Request received', [
            'all_data' => $request->except(['cropped_image']),
            'has_cropped_image' => $request->filled('cropped_image'),
            'has_img_file' => $request->hasFile('img')
        ]);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'desc' => 'required|string',
                'category' => 'required|string|max:255',
                'type' => 'required|in:food,merch',
                'price' => 'required|numeric|min:0',
                'img' => 'nullable|image|max:20480', // Increased to 20MB like catalog
                'cropped_image' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'status' => 'required|in:active,inactive,out_of_stock,discontinued',
                'track_stock' => 'nullable|boolean',
                'stock_quantity' => 'nullable|integer|min:0',
                'low_stock_threshold' => 'required|integer|min:0',
            ]);
            
            \Log::info('Admin Product Creation - Validation passed', ['validated' => $validated]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Admin Product Creation - Validation failed', ['errors' => $e->errors()]);
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        }

        try {
            // Handle cropped image data first
            if ($request->filled('cropped_image')) {
                $croppedData = $request->input('cropped_image');
                
                // Remove data:image/jpeg;base64, prefix
                $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $croppedData);
                $imageData = base64_decode($imageData);
                
                if ($imageData === false) {
                    \Log::error('Admin Product Creation - Failed to decode base64 image data');
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'error' => 'Invalid image data'], 422);
                    }
                    return back()->withErrors(['img' => 'Invalid image data'])->withInput();
                }
                
                // Generate unique filename
                $filename = uniqid('product_') . '.jpg';
                $path = 'products/' . $filename;
                
                // Save the file
                \Storage::disk('public')->put($path, $imageData);
                
                $validated['img'] = '/storage/' . $path;
            } elseif ($request->hasFile('img')) {
                // Handle regular file upload as fallback
                $path = $request->file('img')->store('products', 'public');
                $validated['img'] = '/storage/' . $path;
            } else {
                \Log::error('Admin Product Creation - No image data provided');
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'error' => 'Product image is required'], 422);
                }
                return back()->withErrors(['img' => 'Product image is required'])->withInput();
            }

            // Set defaults
            $validated['is_active'] = $request->has('is_active');
            $validated['track_stock'] = $request->has('track_stock');
            
            // Set default stock quantity if no quantity provided
            if (!isset($validated['stock_quantity']) || $validated['stock_quantity'] === null) {
                $validated['stock_quantity'] = 0; // Start with 0 stock by default
            }

            \Log::info('Admin Product Creation - About to create product', ['validated_data' => $validated]);

            $product = Product::create($validated);

            \Log::info('Admin Product Creation - Product created, updating stock status', ['product_id' => $product->id]);

            // Immediately update stock status based on quantity (as requested)
            $product->updateStockStatus();
            
            \Log::info('Admin Product Creation - Product created successfully with automatic stock status', [
                'product_id' => $product->id,
                'name' => $product->name,
                'stock_quantity' => $product->stock_quantity,
                'auto_status' => $product->status
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully!',
                    'product' => $product, // Return the product without trying to load non-existent relationships
                    'redirect_url' => route('admin.products.index')
                ]);
            }

            return redirect()->route('admin.products.index')
                            ->with('success', 'Product created successfully!');

        } catch (\Exception $e) {
            \Log::error('Admin Product Creation - Failed to create product', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['cropped_image']), // Exclude large image data
                'validated_data' => $validated ?? null
            ]);

            $errorMessage = 'Failed to create product. Please try again.';
            
            // Provide more specific error messages for common issues
            if (str_contains($e->getMessage(), 'SQLSTATE') || str_contains($e->getMessage(), 'database')) {
                $errorMessage = 'Database error occurred. Please try again.';
            } elseif (str_contains($e->getMessage(), 'storage') || str_contains($e->getMessage(), 'disk')) {
                $errorMessage = 'Image upload failed. Please try again.';
            } elseif (str_contains($e->getMessage(), 'fillable')) {
                $errorMessage = 'Invalid data provided. Please check your input.';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'error' => $errorMessage,
                    'debug' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()->withErrors(['general' => $errorMessage])->withInput();
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('orders', 'reviews');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Product::pluck('category')->unique()->filter()->values();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'required|string|max:255',
            'type' => 'required|in:food,merch',
            'price' => 'required|numeric|min:0',
            'img' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'status' => 'required|in:active,inactive,out_of_stock,discontinued',
            'track_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('img')) {
            // Delete old image
            if ($product->img && Storage::disk('public')->exists(str_replace('/storage/', '', $product->img))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->img));
            }
            
            $imgPath = $request->file('img')->store('products', 'public');
            $validated['img'] = '/storage/' . $imgPath;
        }

        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        $validated['track_stock'] = $request->has('track_stock');

        $product->update($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Check if product has associated orders or reviews
            $hasOrders = $product->orders()->exists();
            $hasReviews = $product->reviews()->exists();

            if ($hasOrders || $hasReviews) {
                $message = 'Cannot delete product with existing ';
                $items = [];
                if ($hasOrders) $items[] = 'orders';
                if ($hasReviews) $items[] = 'reviews';
                $message .= implode(' and ', $items) . '. Consider deactivating instead.';

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return redirect()->route('admin.products.index')
                                ->with('error', $message);
            }

            // Delete image file
            if ($product->img && Storage::disk('public')->exists(str_replace('/storage/', '', $product->img))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->img));
            }

            $product->delete();

            // Return JSON response for AJAX requests
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully!'
                ]);
            }

            return redirect()->route('admin.products.index')
                            ->with('success', 'Product deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Product deletion failed: ' . $e->getMessage());
            
            $errorMessage = 'Failed to delete product. Please try again.';
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->route('admin.products.index')
                            ->with('error', $errorMessage);
        }
    }

    /**
     * Toggle product status.
     */
    public function updateStatus(Request $request, Product $product)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Product::getStatuses()))
        ]);

        $oldStatus = $product->status;
        $newStatus = $request->status;

        // For stock-tracked products, prevent conflicting manual changes
        if ($product->track_stock) {
            // Don't allow manual "Out of Stock" when there's actually stock
            if ($newStatus === Product::STATUS_OUT_OF_STOCK && $product->stock_quantity > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot manually set to "Out of Stock" when stock is available. Status is automatically managed based on inventory levels.'
                ], 400);
            }

            // Don't allow manual "Available" when there's no stock
            if ($newStatus === Product::STATUS_ACTIVE && $product->stock_quantity <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot set to "Available" when stock is 0. Please restock the product first.'
                ], 400);
            }
        }

        $product->update([
            'status' => $newStatus
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "Product status updated from {$oldStatus} to {$newStatus}!",
            'status' => $newStatus
        ]);
    }

    /**
     * Update product stock.
     */
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'action' => 'required|in:increase,decrease,set',
            'quantity' => 'required|integer|min:1',
        ]);

        $currentStock = $product->stock_quantity ?? 0;

        switch ($validated['action']) {
            case 'increase':
                $product->increaseStock($validated['quantity']);
                break;
            case 'decrease':
                if ($currentStock >= $validated['quantity']) {
                    $product->reduceStock($validated['quantity']);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock to decrease by that amount.'
                    ], 400);
                }
                break;
            case 'set':
                $product->update(['stock_quantity' => $validated['quantity']]);
                // Auto-update status based on new stock level
                $product->updateStockStatus();
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully!',
            'new_stock' => $product->fresh()->stock_quantity
        ]);
    }

    /**
     * Bulk update products.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'action' => 'required|in:activate,deactivate,delete,update_status',
            'status' => 'nullable|in:active,inactive,out_of_stock,discontinued',
        ]);

        $products = Product::whereIn('id', $validated['product_ids']);

        switch ($validated['action']) {
            case 'activate':
                $products->update(['status' => Product::STATUS_ACTIVE]);
                $message = 'Products set to available successfully!';
                break;
            case 'deactivate':
                $products->update(['status' => Product::STATUS_INACTIVE]);
                $message = 'Products set to inactive successfully!';
                break;
            case 'update_status':
                if (!$validated['status']) {
                    return response()->json(['success' => false, 'message' => 'Status is required.'], 400);
                }
                $products->update(['status' => $validated['status']]);
                $message = 'Product statuses updated successfully!';
                break;
            case 'delete':
                $productsToDelete = $products->get();
                $cannotDelete = [];
                $deletedCount = 0;

                foreach ($productsToDelete as $product) {
                    // Check if product has associated orders or reviews
                    $hasOrders = $product->orders()->exists();
                    $hasReviews = $product->reviews()->exists();

                    if ($hasOrders || $hasReviews) {
                        $cannotDelete[] = $product->name;
                        continue;
                    }

                    // Delete image file
                    if ($product->img && Storage::disk('public')->exists(str_replace('/storage/', '', $product->img))) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $product->img));
                    }

                    $product->delete();
                    $deletedCount++;
                }

                if (count($cannotDelete) > 0) {
                    $message = "Deleted {$deletedCount} product(s). Cannot delete products with existing orders/reviews: " . implode(', ', $cannotDelete);
                } else {
                    $message = "All {$deletedCount} product(s) deleted successfully!";
                }
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Export products to CSV.
     */
    public function export(Request $request)
    {
        Log::info('Product export accessed', ['request' => $request->all()]);
        
        $query = Product::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $products = $query->get();

        $filename = 'products_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Description', 'Category', 'Type', 'Price', 
                'Status', 'Availability', 'Track Stock', 'Stock Quantity', 
                'Low Stock Threshold', 'Created At', 'Updated At'
            ]);

            // Add data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->desc,
                    $product->category,
                    $product->type,
                    $product->price,
                    $product->status,
                    $product->availability_status,
                    $product->track_stock ? 'Yes' : 'No',
                    $product->stock_quantity,
                    $product->low_stock_threshold,
                    $product->created_at,
                    $product->updated_at,
                ]);
            }

            fclose($file);
        };

        try {
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Product export failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get product statistics for dashboard.
     */
    private function getProductStatistics(): array
    {
        return [
            'total_products' => Product::count(),
            'available_products' => Product::where('status', Product::STATUS_ACTIVE)->count(),
            'inactive_products' => Product::where('status', Product::STATUS_INACTIVE)->count(),
            'out_of_stock' => Product::where('status', Product::STATUS_OUT_OF_STOCK)->count(),
            'low_stock' => Product::lowStock()->count(),
            'discontinued' => Product::where('status', Product::STATUS_DISCONTINUED)->count(),
            'recent_products' => Product::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Import products from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        Log::info('Product import started', ['file' => $request->file('csv_file')->getClientOriginalName()]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            
            $csv = array_map('str_getcsv', file($path));
            $header = array_shift($csv);
            
            $imported = 0;
            $errors = [];
            
            foreach ($csv as $index => $row) {
                try {
                    if (count($row) !== count($header)) {
                        $errors[] = "Row " . ($index + 2) . ": Column count mismatch";
                        continue;
                    }
                    
                    $data = array_combine($header, $row);
                    
                    // Map CSV columns to database fields
                    $productData = [
                        'name' => $data['Name'] ?? $data['name'] ?? '',
                        'desc' => $data['Description'] ?? $data['desc'] ?? '',
                        'category' => $data['Category'] ?? $data['category'] ?? '',
                        'type' => $data['Type'] ?? $data['type'] ?? 'merchandise',
                        'price' => floatval($data['Price'] ?? $data['price'] ?? 0),
                        'status' => $data['Status'] ?? $data['status'] ?? 'active',
                        'stock_quantity' => intval($data['Stock Quantity'] ?? $data['stock_quantity'] ?? 0),
                        'track_stock' => filter_var($data['Track Stock'] ?? $data['track_stock'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
                        'low_stock_threshold' => intval($data['Low Stock Threshold'] ?? $data['low_stock_threshold'] ?? 5),
                    ];
                    
                    // Validate required fields
                    if (empty($productData['name'])) {
                        $errors[] = "Row " . ($index + 2) . ": Product name is required";
                        continue;
                    }
                    
                    // Check if product already exists (by name)
                    $existingProduct = Product::where('name', $productData['name'])->first();
                    
                    if ($existingProduct) {
                        // Update existing product
                        $existingProduct->update($productData);
                    } else {
                        // Create new product
                        Product::create($productData);
                    }
                    
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }
            
            Log::info('Product import completed', [
                'imported' => $imported,
                'errors' => count($errors)
            ]);
            
            $message = "Import completed! {$imported} products imported.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors occurred.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Product import failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
