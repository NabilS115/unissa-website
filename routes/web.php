<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Image;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryController;

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
Route::post('/catalog/upload', [CatalogController::class, 'upload'])->name('catalog.upload');
Route::delete('/catalog/delete/{id}', [\AppHttp\Controllers\CatalogController::class, 'destroy'])->name('catalog.delete');

// about routes
Route::get('/company-history', function () {
    return view('company-history');
});

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::view('/review', 'review');

Route::get('/user/photo/{id}', function ($id) {
    $user = User::findOrFail($id);
    if ($user->photo) {
        return Response::make($user->photo, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'inline; filename="profile.jpg"'
        ]);
    }
    abort(404);
});

Route::get('/image/{name}', function ($name) {
    $image = Image::where('name', $name)->firstOrFail();
    return Response::make($image->data, 200, [
        'Content-Type' => $image->mime_type,
        'Content-Disposition' => 'inline; filename="'.$image->name.'"'
    ]);
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // User Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Content Management (example for posts)
    Route::get('/posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/posts/{post}/edit', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/posts/{post}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('admin.posts.destroy');

    // Site Settings (example)
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
});

// Admin Catalog routes
Route::middleware(['auth'])->prefix('admin/catalog')->group(function () {
    Route::post('/add', [AdminCatalogController::class, 'add'])->name('admin.catalog.add');
    Route::post('/edit/{id}', [AdminCatalogController::class, 'edit'])->name('admin.catalog.edit');
    Route::post('/upload', [AdminCatalogController::class, 'upload'])->name('admin.catalog.upload');
});

// Featured products management (Admin only)
Route::middleware('auth')->group(function () {
    Route::get('/admin/featured', [HomeController::class, 'manageFeatured'])->name('featured.manage');
    Route::post('/admin/featured', [HomeController::class, 'addFeatured'])->name('featured.add');
    Route::delete('/admin/featured/{id}', [HomeController::class, 'removeFeatured'])->name('featured.remove');
    Route::post('/admin/featured/order', [HomeController::class, 'updateFeaturedOrder'])->name('featured.order');
});

Route::get('/review/{id}', [ReviewController::class, 'show'])->name('review.show');
Route::post('/review/{id}/add', [ReviewController::class, 'add'])->name('review.add');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('review.delete')->middleware('auth');
Route::post('/reviews/{id}/helpful', [ReviewController::class, 'helpful'])->name('review.helpful');

// Gallery management routes (admin only)
Route::middleware(['auth'])->group(function () {
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::put('/gallery/{gallery}', [GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::patch('/gallery/{gallery}/toggle-active', [GalleryController::class, 'toggleActive'])->name('gallery.toggle-active');
});

require __DIR__.'/auth.php';
require __DIR__.'/auth.php';
