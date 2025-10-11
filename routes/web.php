<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProfileController;
use Illuminate\Suppor\Response;
use App\Models\User;
use App\Models\Image;
use App\Http\Controllers\CatalogController;
// use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SearchController;

// contact routes
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

//homepage routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Test route for delete functionality
Route::get('/test-delete', function () {
    return view('test-delete');
});

// Debug route to test product deletion without frontend
Route::get('/debug-delete/{product}', function (App\Models\Product $product) {
    try {
        $hasOrders = $product->orders()->exists();
        $hasReviews = $product->reviews()->exists();
        
        return response()->json([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'has_orders' => $hasOrders,
            'orders_count' => $product->orders()->count(),
            'has_reviews' => $hasReviews,
            'reviews_count' => $product->reviews()->count(),
            'can_delete' => !$hasOrders && !$hasReviews,
            'message' => 'Product analysis complete'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Featured products overview (Admin only)
Route::middleware('auth')->group(function () {
    Route::get('/admin/featured', [HomeController::class, 'manageFeatured'])->name('featured.manage');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// profile routes
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('/profile', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.profile');
        }
        return view('profile');
    })->name('profile');
    Route::get('/admin-profile', function () {
        return view('admin-profile');
    })->name('admin.profile');
    Route::get('/edit-profile', function () {
        return view('edit-profile');
    })->name('edit.profile');
    Route::post('/profile/photo', [App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/photo/delete', [App\Http\Controllers\ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::put('/edit-profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// unissa cafe routes
// Main unissa-cafe route redirects to homepage
Route::get('/unissa-cafe', function() {
    return redirect()->route('unissa-cafe.homepage');
})->name('unissa-cafe.main');

// Legacy catalog route redirect for backward compatibility
Route::get('/catalog', function() {
    return redirect()->route('unissa-cafe.homepage');
})->name('products.catalog');

// Unissa Cafe homepage (formerly featured products)
Route::get('/unissa-cafe/homepage', [\App\Http\Controllers\CatalogController::class, 'featured'])->name('unissa-cafe.homepage');

// Legacy featured products route for backward compatibility
Route::get('/products/featured', [\App\Http\Controllers\CatalogController::class, 'featured'])->name('products.featured');

// Unissa Cafe catalog page (browse all products with tabs, search, filters)
Route::get('/unissa-cafe/catalog', [\App\Http\Controllers\CatalogController::class, 'browse'])->name('unissa-cafe.catalog');

// Legacy browse route for backward compatibility
Route::get('/products/browse', [\App\Http\Controllers\CatalogController::class, 'browse'])->name('products.browse');

// Product management routes
Route::post('/unissa-cafe/products', [CatalogController::class, 'store'])->name('unissa-cafe.products.store');
Route::put('/unissa-cafe/products/{id}', [\App\Http\Controllers\CatalogController::class, 'update'])->name('unissa-cafe.products.update');
Route::delete('/unissa-cafe/products/{id}', [\App\Http\Controllers\CatalogController::class, 'destroy'])->name('unissa-cafe.products.destroy');

// Legacy product management routes for backward compatibility
Route::post('/products', [CatalogController::class, 'store'])->name('products.store');
Route::put('/products/{id}', [\App\Http\Controllers\CatalogController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [\App\Http\Controllers\CatalogController::class, 'destroy'])->name('products.destroy');

// Legacy catalog routes for backward compatibility
Route::post('/catalog/add', [CatalogController::class, 'store'])->name('catalog.add');
Route::put('/catalog/edit/{id}', [\App\Http\Controllers\CatalogController::class, 'update'])->name('catalog.edit');
Route::delete('/catalog/delete/{id}', [\App\Http\Controllers\CatalogController::class, 'destroy'])->name('catalog.delete');

// Unissa Cafe data endpoint
Route::get('/unissa-cafe/data', [CatalogController::class, 'getData'])->name('unissa-cafe.data');

// Legacy catalog data endpoint
Route::get('/catalog/data', [CatalogController::class, 'getData'])->name('catalog.data');

// about routes
Route::get('/about', function () {
    return view('company-history');
});

Route::get('/company-history', function () {
    return view('company-history');
});

// Admin routes with proper middleware alias
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/api', [App\Http\Controllers\Admin\UserController::class, 'api'])->name('users.api');
    Route::get('/users/export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Content Management (example for posts) - Commented out until PostController is created
    // Route::get('/posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('posts.index');
    // Route::get('/posts/{post}/edit', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('posts.edit');
    // Route::put('/posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('posts.update');
    // Route::delete('/posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('posts.destroy');

    // Site Settings (example) - Commented out until SettingsController is created
    // Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    // Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

    // Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [\App\Http\Controllers\Admin\AdminOrderController::class, 'export'])->name('orders.export');
    Route::post('/orders/import', [\App\Http\Controllers\Admin\AdminOrderController::class, 'import'])->name('orders.import');
    Route::get('/orders/statistics', [\App\Http\Controllers\Admin\AdminOrderController::class, 'statistics'])->name('orders.statistics');
    Route::post('/orders/bulk-update', [\App\Http\Controllers\Admin\AdminOrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/payment-status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');

    // Product Management
    Route::get('/products/export', [\App\Http\Controllers\Admin\AdminProductController::class, 'export'])->name('products.export');
    Route::post('/products/import', [\App\Http\Controllers\Admin\AdminProductController::class, 'import'])->name('products.import');
    Route::post('/products/bulk-update', [\App\Http\Controllers\Admin\AdminProductController::class, 'bulkUpdate'])->name('products.bulk-update');
    Route::patch('/products/{product}/update-status', [\App\Http\Controllers\Admin\AdminProductController::class, 'updateStatus'])->name('products.update-status');
    Route::patch('/products/{product}/stock', [\App\Http\Controllers\Admin\AdminProductController::class, 'updateStock'])->name('products.update-stock');
    Route::resource('products', \App\Http\Controllers\Admin\AdminProductController::class);
});

// Admin Catalog routes - Commented out until AdminCatalogController is created
// Route::middleware(['auth'])->prefix('admin/catalog')->group(function () {
//     Route::post('/add', [AdminCatalogController::class, 'add'])->name('admin.catalog.add');
//     Route::post('/edit/{id}', [AdminCatalogController::class, 'edit'])->name('admin.catalog.edit');
//     Route::post('/upload', [AdminCatalogController::class, 'upload'])->name('admin.catalog.upload');
// });



Route::get('/product/{id}', [ReviewController::class, 'show'])->name('product.detail');
Route::post('/product/{id}/add-review', [ReviewController::class, 'add'])->name('product.add-review');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('review.delete')->middleware('auth');
Route::post('/reviews/{id}/helpful', [ReviewController::class, 'helpful'])->name('review.helpful');

// Search routes
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/catalog', [SearchController::class, 'catalogSearch'])->name('search.catalog');
Route::get('/search/filters', [SearchController::class, 'getFilters'])->name('search.filters');

// Gallery routes (admin protected)
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/gallery', [App\Http\Controllers\GalleryController::class, 'index']);
    Route::post('/gallery', [App\Http\Controllers\GalleryController::class, 'store']);
    Route::put('/gallery/{gallery}', [App\Http\Controllers\GalleryController::class, 'update']);
    Route::delete('/gallery/{gallery}', [App\Http\Controllers\GalleryController::class, 'destroy']);
    Route::patch('/gallery/{gallery}/toggle-active', [App\Http\Controllers\GalleryController::class, 'toggleActive']);
});


// Gallery management routes (admin only)
Route::middleware(['auth'])->group(function () {
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::put('/gallery/{gallery}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::patch('/gallery/{gallery}/toggle-active', [GalleryController::class, 'toggleActive'])->name('gallery.toggle-active');
});

// Order routes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
});

// API endpoint to check authentication status
Route::get('/api/auth-status', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'role' => auth()->check() ? auth()->user()->role : null
    ]);
});

require __DIR__.'/auth.php';
