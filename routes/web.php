<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// catalog routes
Route::get('/food-list', function () {
    return view('food-list');
});

// about routes
Route::get('/company-history', function () {
    return view('company-history');
});

require __DIR__.'/auth.php';
