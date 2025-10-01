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
Route::get('/contact', function () {
    return view('contact');
});

//homepage routes
Route::get('/', [HomeController::class, 'index'])->name('home');

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

// catalog routes
// Make sure your form uses method="POST" and enctype="multipart/form-data"
// Example form:
// <form action="{{ route('catalog.add') }}" method="POST" enctype="multipart/form-data">
//     @csrf
//     <!-- fields -->
// </form>
Route::get('/catalog', [\App\Http\Controllers\CatalogController::class, 'index'])->name('products.catalog');
Route::post('/catalog/add', [CatalogController::class, 'add'])->name('catalog.add');
Route::put('/catalog/edit/{id}', [\App\Http\Controllers\CatalogController::class, 'edit'])->name('catalog.edit');
Route::delete('/catalog/delete/{id}', [\App\Http\Controllers\CatalogController::class, 'destroy'])->name('catalog.delete');

// Catalog data endpoint
Route::get('/catalog/data', [CatalogController::class, 'getData'])->name('catalog.data');

// about routes
Route::get('/company-history', function () {
    return view('company-history');
});

// Admin routes with proper middleware class
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
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
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/bulk-update', [\App\Http\Controllers\Admin\AdminOrderController::class, 'bulkUpdate'])->name('orders.bulk-update');
    Route::get('/orders/statistics', [\App\Http\Controllers\Admin\AdminOrderController::class, 'statistics'])->name('orders.statistics');
    Route::get('/orders/export', [\App\Http\Controllers\Admin\AdminOrderController::class, 'export'])->name('orders.export');

    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\AdminProductController::class);
    Route::patch('/products/{product}/toggle-status', [\App\Http\Controllers\Admin\AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::patch('/products/{product}/stock', [\App\Http\Controllers\Admin\AdminProductController::class, 'updateStock'])->name('products.update-stock');
    Route::post('/products/bulk-update', [\App\Http\Controllers\Admin\AdminProductController::class, 'bulkUpdate'])->name('products.bulk-update');
    Route::get('/products/export', [\App\Http\Controllers\Admin\AdminProductController::class, 'export'])->name('products.export');
});

// Admin Catalog routes - Commented out until AdminCatalogController is created
// Route::middleware(['auth'])->prefix('admin/catalog')->group(function () {
//     Route::post('/add', [AdminCatalogController::class, 'add'])->name('admin.catalog.add');
//     Route::post('/edit/{id}', [AdminCatalogController::class, 'edit'])->name('admin.catalog.edit');
//     Route::post('/upload', [AdminCatalogController::class, 'upload'])->name('admin.catalog.upload');
// });

// Featured products management (Admin only)
Route::middleware('auth')->group(function () {
    Route::get('/admin/featured', [HomeController::class, 'manageFeatured'])->name('featured.manage');
    Route::post('/admin/featured', [HomeController::class, 'addFeatured'])->name('featured.add');
    Route::delete('/admin/featured/{id}', [HomeController::class, 'removeFeatured'])->name('featured.remove');
    Route::post('/admin/featured/order', [HomeController::class, 'updateFeaturedOrder'])->name('featured.order');
});

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
