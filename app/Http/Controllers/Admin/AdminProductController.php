<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'required|string|max:255',
            'type' => 'required|in:food,merch',
            'price' => 'required|numeric|min:0',
            'img' => 'required|image|max:2048',
            'is_active' => 'boolean',
            'status' => 'required|in:active,inactive,out_of_stock,discontinued',
            'track_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('products', 'public');
            $validated['img'] = '/storage/' . $imgPath;
        }

        // Set defaults
        $validated['is_active'] = $request->has('is_active');
        $validated['track_stock'] = $request->has('track_stock');

        Product::create($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product created successfully!');
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
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active
        ]);

        $status = $product->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Product {$status} successfully!",
            'is_active' => $product->is_active
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
                if ($validated['quantity'] > 0 && $product->status === Product::STATUS_OUT_OF_STOCK) {
                    $product->update(['status' => Product::STATUS_ACTIVE]);
                }
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
                $products->update(['is_active' => true]);
                $message = 'Products activated successfully!';
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $message = 'Products deactivated successfully!';
                break;
            case 'update_status':
                if (!$validated['status']) {
                    return response()->json(['success' => false, 'message' => 'Status is required.'], 400);
                }
                $products->update(['status' => $validated['status']]);
                $message = 'Product statuses updated successfully!';
                break;
            case 'delete':
                // Delete images
                foreach ($products->get() as $product) {
                    if ($product->img && Storage::disk('public')->exists(str_replace('/storage/', '', $product->img))) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $product->img));
                    }
                }
                $products->delete();
                $message = 'Products deleted successfully!';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Export products to CSV.
     */
    public function export(Request $request)
    {
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
                'Status', 'Active', 'Track Stock', 'Stock Quantity', 
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
                    $product->is_active ? 'Yes' : 'No',
                    $product->track_stock ? 'Yes' : 'No',
                    $product->stock_quantity,
                    $product->low_stock_threshold,
                    $product->created_at,
                    $product->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get product statistics for dashboard.
     */
    private function getProductStatistics(): array
    {
        return [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'inactive_products' => Product::where('is_active', false)->count(),
            'out_of_stock' => Product::outOfStock()->count(),
            'low_stock' => Product::lowStock()->count(),
            'discontinued' => Product::where('status', Product::STATUS_DISCONTINUED)->count(),
            'recent_products' => Product::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }
}
