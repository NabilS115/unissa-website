<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Image;
use App\Http\Controllers\FoodCatalogController;
use App\Http\Controllers\Admin\CatalogController;

// contact routes
Route::get('/contact', function () {
    return view('contact');
});

//homepage routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

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
Route::get('/food-list', [FoodCatalogController::class, 'index'])->name('food-list');
Route::post('/catalog/add', [CatalogController::class, 'add'])->name('catalog.add');
Route::post('/catalog/edit/{id}', [CatalogController::class, 'edit'])->name('catalog.edit');
Route::post('/catalog/upload', [CatalogController::class, 'upload'])->name('catalog.upload');

// about routes
Route::get('/company-history', function () {
    return view('company-history');
});

Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');
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
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Content Management (example for posts)
    Route::get('/posts', [App\Http\Controllers\Admin\PostController::class, 'index'])->name('admin.posts.index');
    Route::get('/posts/{post}/edit', [App\Http\Controllers\Admin\PostController::class, 'edit'])->name('admin.posts.edit');
    Route::put('/posts/{post}', [App\Http\Controllers\Admin\PostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/posts/{post}', [App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('admin.posts.destroy');

    // Site Settings (example)
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
});

require __DIR__.'/auth.php';
require __DIR__.'/auth.php';
