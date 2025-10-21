<?php
use Illuminate\Support\Facades\Auth;
// Universal logout route (POST)
Route::post('logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
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
use App\Http\Controllers\CartController;

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
    Route::get('/profile', function (\Illuminate\Http\Request $request) {
        // Determine previous context from referer or session
        $referer = $request->headers->get('referer');
        if ($request->query('context') === 'unissa-cafe') {
            session(['header_context' => 'unissa-cafe']);
        } else {
            $context = session('header_context', 'tijarah');
            if (
                ($referer && (str_contains($referer, 'unissa-cafe') || str_contains($referer, 'products') || str_contains($referer, 'cart') || str_contains($referer, 'checkout') || str_contains($referer, 'my/orders')))
                || ($referer && str_ends_with($referer, '/edit-profile') && $context === 'unissa-cafe')
                || ($context === 'unissa-cafe')
            ) {
                $context = 'unissa-cafe';
            } else {
                $context = 'tijarah';
            }
            session(['header_context' => $context]);
        }
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.profile');
        }
        return view('profile');
    })->name('profile');
    Route::get('/admin-profile', function (\Illuminate\Http\Request $request) {
        $referer = $request->headers->get('referer');
        $context = 'tijarah';
        if ($referer) {
            if (str_contains($referer, 'unissa-cafe') || str_contains($referer, 'products') || str_contains($referer, 'cart') || str_contains($referer, 'checkout') || str_contains($referer, 'my/orders')) {
                $context = 'unissa-cafe';
            }
        } elseif (session('header_context')) {
            $context = session('header_context');
        }
        session(['header_context' => $context]);
        return view('admin-profile');
    })->name('admin.profile');
    Route::get('/edit-profile', function (\Illuminate\Http\Request $request) {
        $referer = $request->headers->get('referer');
        $context = 'tijarah';
        if (
            ($referer && (str_contains($referer, 'unissa-cafe') || str_contains($referer, 'products') || str_contains($referer, 'cart') || str_contains($referer, 'checkout') || str_contains($referer, 'my/orders')))
            || ($referer && str_ends_with($referer, '/profile') && session('header_context') === 'unissa-cafe')
        ) {
            $context = 'unissa-cafe';
        }
        session(['header_context' => $context]);
        return view('edit-profile');
    })->name('edit.profile');
    Route::post('/profile/photo', [App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/photo/delete', [App\Http\Controllers\ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::put('/edit-profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/payment', [App\Http\Controllers\ProfileController::class, 'updatePayment'])->name('profile.payment');
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
    // Helper closure to set header context for admin pages
    $setHeaderContext = function ($request) {
        $referer = $request->headers->get('referer');
        $context = 'tijarah';
        if ($referer) {
            if (str_contains($referer, 'unissa-cafe') || str_contains($referer, 'products') || str_contains($referer, 'cart') || str_contains($referer, 'checkout') || str_contains($referer, 'my/orders')) {
                $context = 'unissa-cafe';
            }
        } elseif (session('header_context')) {
            $context = session('header_context');
        }
        session(['header_context' => $context]);
    };

    Route::get('/users', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->index($request);
    })->name('users.index');
    Route::get('/users/api', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->api($request);
    })->name('users.api');
    Route::get('/users/export', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->export($request);
    })->name('users.export');
    Route::get('/users/{user}', function (\Illuminate\Http\Request $request, \App\Models\User $user) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->show($user);
    })->name('users.show');
    Route::post('/users', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->store($request);
    })->name('users.store');
    Route::put('/users/{user}', function (\Illuminate\Http\Request $request, $user) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->update($request, $user);
    })->name('users.update');
    Route::delete('/users/{user}', function (\Illuminate\Http\Request $request, $user) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->destroy($request, $user);
    })->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', function (\Illuminate\Http\Request $request, $user) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\UserController::class)->toggleStatus($request, $user);
    })->name('users.toggle-status');

    // Order Management
    Route::get('/orders', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->index($request);
    })->name('orders.index');
    Route::get('/orders/export', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->export($request);
    })->name('orders.export');
    Route::post('/orders/import', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->import($request);
    })->name('orders.import');
    Route::get('/orders/statistics', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->statistics($request);
    })->name('orders.statistics');
    Route::post('/orders/bulk-update', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->bulkUpdate($request);
    })->name('orders.bulk-update');
    Route::get('/orders/{order}', function (\Illuminate\Http\Request $request, \App\Models\Order $order) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->show($order);
    })->name('orders.show');
    Route::patch('/orders/{order}/status', function (\Illuminate\Http\Request $request, \App\Models\Order $order) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->updateStatus($request, $order);
    })->name('orders.update-status');
    Route::patch('/orders/{order}/payment-status', function (\Illuminate\Http\Request $request, \App\Models\Order $order) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->updatePaymentStatus($request, $order);
    })->name('orders.update-payment-status');

    // Product Management
    Route::get('/products/export', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->export($request);
    })->name('products.export');
    Route::post('/products/import', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->import($request);
    })->name('products.import');
    Route::post('/products/bulk-update', function (\Illuminate\Http\Request $request) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->bulkUpdate($request);
    })->name('products.bulk-update');
    Route::patch('/products/{product}/update-status', function (\Illuminate\Http\Request $request, $product) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->updateStatus($request, $product);
    })->name('products.update-status');
    Route::patch('/products/{product}/stock', function (\Illuminate\Http\Request $request, \App\Models\Product $product) use ($setHeaderContext) {
        $setHeaderContext($request);
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->updateStock($request, $product);
    })->name('products.update-stock');
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
Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('review.update')->middleware('auth');

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

// Cart routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/add-simple', [App\Http\Controllers\CartController::class, 'addToCartSimple'])->name('cart.add.simple');
    Route::patch('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'removeItem'])->name('cart.remove');
    Route::get('/api/cart/count', [App\Http\Controllers\CartController::class, 'getCartCount'])->name('cart.count');
});

// User Order routes
Route::middleware(['auth'])->prefix('my')->name('user.')->group(function () {
    Route::get('/orders', [App\Http\Controllers\UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\UserOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [App\Http\Controllers\UserOrderController::class, 'cancel'])->name('orders.cancel');
    Route::delete('/cart', [App\Http\Controllers\CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/api/cart/test', function() {
        return response()->json([
            'authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'message' => 'Cart API test successful'
        ]);
    })->name('cart.test');
});

// Checkout routes  
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{product}', [App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'showCart'])->name('checkout.cart');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/cart', [App\Http\Controllers\CheckoutController::class, 'processCart'])->name('checkout.process.cart');
});

// Order routes removed - non-functional user-facing order system

// API endpoint to check authentication status
Route::get('/api/auth-status', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'role' => auth()->check() ? auth()->user()->role : null
    ]);
});

require __DIR__.'/auth.php';
