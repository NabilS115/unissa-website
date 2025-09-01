<?php
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/contact', function () {
    return view('contact');
});


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/food-list', function () {
    return view('food-list');
});

Route::get('/company-history', function () {
    return view('company-history');
});

require __DIR__.'/auth.php';
