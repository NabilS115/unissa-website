<?php
use Illuminate\Support\Facades\Auth;
// Universal logout route (POST)
Route::post('logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Dynamic Favicon Routes
Route::get('/favicon.ico', function () {
    $referer = request()->headers->get('referer');
    $isUnissaContext = str_contains(request()->fullUrl(), 'unissa-cafe') ||
                      str_contains($referer ?? '', 'unissa-cafe') ||
                      session('current_context') === 'unissa-cafe';
    
    $faviconPath = $isUnissaContext ? 
        public_path('images/UNISSA_CAFE.png') : 
        public_path('images/TIJARAH_CO_SDN_BHD.png');
    
    return response()->file($faviconPath, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0'
    ]);
})->name('dynamic.favicon');

Route::get('/unissa-favicon.ico', function () {
    return response()->file(public_path('images/UNISSA_CAFE.png'), [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'no-cache, no-store, must-revalidate'
    ]);
});

Route::get('/tijarah-favicon.ico', function () {
    return response()->file(public_path('images/TIJARAH_CO_SDN_BHD.png'), [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'no-cache, no-store, must-revalidate'
    ]);
});

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProfileController;
use Illuminate\Suppor\Response;
use App\Models\User;
use App\Models\Image;
use App\Http\Controllers\CatalogController;

// Simplified context detection helper
$setContextForAllPages = function ($request) {
    $referer = $request->headers->get('referer');
    
    // Simple rule: If coming from or going to Unissa pages, set Unissa context
    $isUnissaRequest = $request->query('context') === 'unissa-cafe' || 
                      str_contains($request->path(), 'unissa-cafe') ||
                      str_contains($request->path(), 'products') ||
                      str_contains($request->path(), 'cart') ||
                      str_contains($request->path(), 'checkout') ||
                      str_contains($request->path(), 'admin/orders') ||
                      str_contains($request->path(), 'admin/products');
    
    $isUnissaReferer = $referer && (
        str_contains($referer, 'unissa-cafe') || 
        str_contains($referer, '/products/') || 
        str_contains($referer, '/cart') || 
        str_contains($referer, '/checkout') || 
        str_contains($referer, '/admin/orders') || 
        str_contains($referer, '/admin/products')
    );
    
    $context = ($isUnissaRequest || $isUnissaReferer) ? 'unissa-cafe' : 'tijarah';
    
    // Set context in session for consistency
    session(['header_context' => $context]);
    
    return $context;
};
// use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;

// contact routes
Route::get('/contact', function (\Illuminate\Http\Request $request) use ($setContextForAllPages) {
    $setContextForAllPages($request);
    return app(\App\Http\Controllers\ContactController::class)->index();
})->name('contact.index');
Route::post('/contact', function (\Illuminate\Http\Request $request) use ($setContextForAllPages) {
    $setContextForAllPages($request);
    return app(\App\Http\Controllers\ContactController::class)->store($request);
})->name('contact.store');

//homepage routes
Route::get('/', function (\Illuminate\Http\Request $request) use ($setContextForAllPages) {
    $setContextForAllPages($request);
    return app(HomeController::class)->index();
})->name('home');

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
        // Enhanced context detection for profile pages
        $referer = $request->headers->get('referer');
        $context = 'tijarah'; // Default context - bias towards Tijarah
        
        // Priority 1: FORCE Tijarah for homepage/company pages - highest priority
        if ($referer && (
            str_ends_with($referer, '/') || 
            preg_match('/^https?:\/\/[^\/]+\/?$/', $referer) ||
            str_contains($referer, '/company-history') ||
            str_contains($referer, '/contact')
        )) {
            $context = 'tijarah';
        }
        // Priority 2: Explicit context parameter (only if not from Tijarah pages)
        elseif ($request->query('context') === 'unissa-cafe') {
            $context = 'unissa-cafe';
        }
        // Priority 3: Detect UNISSA CAFE from referer URL patterns
        elseif ($referer && (
            str_contains($referer, 'unissa-cafe') || 
            str_contains($referer, '/products/') || 
            str_contains($referer, '/product/') ||
            str_contains($referer, '/cart') || 
            str_contains($referer, '/checkout') || 
            str_contains($referer, '/my/orders') ||
            str_contains($referer, '/admin/orders') || 
            str_contains($referer, '/admin/products')
        )) {
            $context = 'unissa-cafe';
        }
        // Priority 4: Default remains tijarah
        
        // Clear any stale session context when we have a clear Tijarah detection
        if ($context === 'tijarah' && $referer) {
            // Clear session to prevent stale unissa-cafe context from persisting
            session()->forget('header_context');
        }
        
        // Always set the determined context in session
        session(['header_context' => $context]);
        
        if (auth()->user()->role === 'admin') {
            // Redirect to admin profile (clean URL without context parameter)
            return redirect('/admin-profile');
        }
        return view('profile');
    })->name('profile');
    Route::get('/admin-profile', function (\Illuminate\Http\Request $request) {
        // Enhanced context detection for admin profile pages
        $referer = $request->headers->get('referer');
        $context = 'tijarah'; // Default context - bias towards Tijarah
        

        
        // Priority 1: Explicit context parameter
        if ($request->query('context') === 'unissa-cafe') {
            $context = 'unissa-cafe';
        }
        // Priority 2: Detect UNISSA CAFE from referer URL patterns
        elseif ($referer && (
            str_contains($referer, 'unissa-cafe') || 
            str_contains($referer, '/products/') || 
            str_contains($referer, '/product/') ||
            str_contains($referer, '/cart') || 
            str_contains($referer, '/checkout') || 
            str_contains($referer, '/my/orders') ||
            str_contains($referer, '/admin/orders') || 
            str_contains($referer, '/admin/products')
        )) {
            $context = 'unissa-cafe';
        }
        // Priority 3: Explicitly detect TIJARAH from referer (ensure Tijarah pages stay Tijarah)
        elseif ($referer && (
            str_contains($referer, '/company-history') ||
            str_contains($referer, '/contact') ||
            preg_match('/\/$/', $referer) || // Ends with just slash (homepage)
            // Also check if referer is just the base domain (homepage)
            preg_match('/^https?:\/\/[^\/]+\/?$/', $referer)
        )) {
            $context = 'tijarah';
        }
        // Priority 4: Default remains tijarah (removed session fallback to prevent stale context)
        
        // Clear any stale session context when we have a clear Tijarah detection
        if ($context === 'tijarah' && $referer) {
            // Clear session to prevent stale unissa-cafe context from persisting
            session()->forget('header_context');
        }
        
        // FINAL OVERRIDE: Force Tijarah for Tijarah pages regardless of what happened above
        if ($referer && (
            str_ends_with($referer, '/') || 
            preg_match('/^https?:\/\/[^\/]+\/?$/', $referer) ||
            str_contains($referer, '/company-history') ||
            str_contains($referer, '/contact') ||
            (str_ends_with($referer, '/profile') && !str_contains($referer, 'context=unissa-cafe'))
        )) {
            $context = 'tijarah';
        }
        
        // Always set the determined context in session
        session(['header_context' => $context]);
        
        return view('admin-profile');
    })->name('admin.profile');
    Route::get('/edit-profile', function (\Illuminate\Http\Request $request) {
        // Enhanced context detection for edit profile pages
        $referer = $request->headers->get('referer');
        $context = 'tijarah'; // Default context - bias towards Tijarah
        
        // Priority 1: Explicit context parameter
        if ($request->query('context') === 'unissa-cafe') {
            $context = 'unissa-cafe';
        }
        // Priority 2: Detect UNISSA CAFE from referer URL patterns
        elseif ($referer && (
            str_contains($referer, 'unissa-cafe') || 
            str_contains($referer, '/products/') || 
            str_contains($referer, '/product/') ||
            str_contains($referer, '/cart') || 
            str_contains($referer, '/checkout') || 
            str_contains($referer, '/my/orders') ||
            str_contains($referer, '/admin/orders') || 
            str_contains($referer, '/admin/products') ||
            (str_contains($referer, '/profile?context=unissa-cafe') || str_contains($referer, '/admin-profile?context=unissa-cafe'))
        )) {
            $context = 'unissa-cafe';
        }
        // Priority 3: Explicitly detect TIJARAH from referer (ensure Tijarah pages stay Tijarah)
        elseif ($referer && (
            str_contains($referer, '/company-history') ||
            str_contains($referer, '/contact') ||
            preg_match('/\/$/', $referer) || // Ends with just slash (homepage)
            // Also check if referer is just the base domain (homepage)
            preg_match('/^https?:\/\/[^\/]+\/?$/', $referer) ||
            (str_ends_with($referer, '/profile') && !str_contains($referer, 'context=unissa-cafe')) ||
            (str_ends_with($referer, '/admin-profile') && !str_contains($referer, 'context=unissa-cafe'))
        )) {
            $context = 'tijarah';
        }
        // Priority 4: Default remains tijarah (removed session fallback to prevent stale context)
        
        // Clear any stale session context when we have a clear Tijarah detection
        if ($context === 'tijarah' && $referer) {
            // Clear session to prevent stale unissa-cafe context from persisting
            session()->forget('header_context');
        }
        
        // Always set the determined context in session
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
Route::get('/unissa-cafe', function(\Illuminate\Http\Request $request) use ($setContextForAllPages) {
    $context = $setContextForAllPages($request);
    // Force unissa-cafe context for this specific route
    session(['header_context' => 'unissa-cafe']);
    return redirect()->route('unissa-cafe.homepage');
})->name('unissa-cafe.main');

// Legacy catalog route redirect for backward compatibility
Route::get('/catalog', function() {
    return redirect()->route('unissa-cafe.homepage');
})->name('products.catalog');

// Unissa Cafe homepage (formerly featured products)
Route::get('/unissa-cafe/homepage', function(\Illuminate\Http\Request $request) use ($setContextForAllPages) {
    // Force unissa-cafe context for this page
    session(['header_context' => 'unissa-cafe']);
    return app(\App\Http\Controllers\CatalogController::class)->featured();
})->name('unissa-cafe.homepage');

// Legacy featured products route for backward compatibility
Route::get('/products/featured', [\App\Http\Controllers\CatalogController::class, 'featured'])->name('products.featured');

// Unissa Cafe catalog page (browse all products with tabs, search, filters)
Route::get('/unissa-cafe/catalog', function(\Illuminate\Http\Request $request) {
    // Force unissa-cafe context for this page
    session(['header_context' => 'unissa-cafe']);
    return app(\App\Http\Controllers\CatalogController::class)->browse();
})->name('unissa-cafe.catalog');

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
Route::get('/about', function (\Illuminate\Http\Request $request) use ($setContextForAllPages) {
    $setContextForAllPages($request);
    return view('company-history');
});

Route::get('/company-history', function (\Illuminate\Http\Request $request) use ($setContextForAllPages) {
    $setContextForAllPages($request);
    return view('company-history');
});

// Admin routes with proper middleware alias
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Use simple context detection for admin pages - default to Tijarah

    Route::get('/users', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->index($request);
    })->name('users.index');
    Route::get('/users/api', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->api($request);
    })->name('users.api');
    Route::get('/users/export', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->export($request);
    })->name('users.export');
    Route::get('/users/{user}', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->show($user);
    })->name('users.show');
    Route::post('/users', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->store($request);
    })->name('users.store');
    Route::put('/users/{user}', function (\Illuminate\Http\Request $request, $user) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->update($request, $user);
    })->name('users.update');
    Route::delete('/users/{user}', function (\Illuminate\Http\Request $request, $user) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->destroy($request, $user);
    })->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', function (\Illuminate\Http\Request $request, $user) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\UserController::class)->toggleStatus($request, $user);
    })->name('users.toggle-status');

    // Order Management
    Route::get('/orders', function (\Illuminate\Http\Request $request) {
        // Simple context detection for admin pages
        session(['header_context' => 'tijarah']);
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->index($request);
    })->name('orders.index');
    Route::get('/orders/export', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->export($request);
    })->name('orders.export');
    Route::post('/orders/import', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->import($request);
    })->name('orders.import');
    Route::get('/orders/statistics', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->statistics($request);
    })->name('orders.statistics');
    Route::post('/orders/bulk-update', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->bulkUpdate($request);
    })->name('orders.bulk-update');
    Route::get('/orders/{order}', function (\Illuminate\Http\Request $request, \App\Models\Order $order) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->show($order);
    })->name('orders.show');
    Route::patch('/orders/{order}/status', function (\Illuminate\Http\Request $request, \App\Models\Order $order) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->updateStatus($request, $order);
    })->name('orders.update-status');
    Route::patch('/orders/{order}/payment-status', function (\Illuminate\Http\Request $request, \App\Models\Order $order) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminOrderController::class)->updatePaymentStatus($request, $order);
    })->name('orders.update-payment-status');

    // Product Management
    Route::get('/products/export', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->export($request);
    })->name('products.export');
    Route::post('/products/import', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->import($request);
    })->name('products.import');
    Route::post('/products/bulk-update', function (\Illuminate\Http\Request $request) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->bulkUpdate($request);
    })->name('products.bulk-update');
    Route::patch('/products/{product}/update-status', function (\Illuminate\Http\Request $request, $product) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
        return app(\App\Http\Controllers\Admin\AdminProductController::class)->updateStatus($request, $product);
    })->name('products.update-status');
    Route::patch('/products/{product}/stock', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
        session(['header_context' => 'tijarah']); // Admin pages default to Tijarah
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

// Content management routes (admin only)
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/admin/content/homepage', [App\Http\Controllers\ContentController::class, 'homepage'])->name('content.homepage');
    Route::post('/admin/content/homepage', [App\Http\Controllers\ContentController::class, 'updateHomepage'])->name('content.homepage.update');
    Route::get('/admin/content/contact', [App\Http\Controllers\ContentController::class, 'contact'])->name('content.contact');
    Route::post('/admin/content/contact', [App\Http\Controllers\ContentController::class, 'updateContact'])->name('content.contact.update');
    Route::get('/admin/content/about', [App\Http\Controllers\ContentController::class, 'about'])->name('content.about');
    Route::post('/admin/content/about', [App\Http\Controllers\ContentController::class, 'updateAbout'])->name('content.about.update');
    Route::get('/admin/content/privacy', [App\Http\Controllers\ContentController::class, 'privacy'])->name('content.privacy');
    Route::post('/admin/content/privacy', [App\Http\Controllers\ContentController::class, 'updatePrivacy'])->name('content.privacy.update');
    Route::get('/admin/content/terms', [App\Http\Controllers\ContentController::class, 'terms'])->name('content.terms');
    Route::post('/admin/content/terms', [App\Http\Controllers\ContentController::class, 'updateTerms'])->name('content.terms.update');
    Route::get('/admin/content/unissa-cafe', [App\Http\Controllers\ContentController::class, 'unissaCafeHomepage'])->name('content.unissa-cafe');
    Route::post('/admin/content/unissa-cafe', [App\Http\Controllers\ContentController::class, 'updateUnissaCafeHomepage'])->name('content.unissa-cafe.update');
    Route::post('/admin/content/upload-image', [App\Http\Controllers\ContentController::class, 'uploadImage'])->name('content.upload.image');
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

// Legal Pages
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms-of-service', function () {
    return view('terms-of-service');
})->name('terms-of-service');

require __DIR__.'/auth.php';
