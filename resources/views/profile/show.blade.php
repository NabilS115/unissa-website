<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $photoData = null;
        if ($request->hasFile('photo')) {
            $photoData = file_get_contents($request->file('photo')->getRealPath());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoData,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to homepage instead of dashboard
        return redirect('/');
    }
}
?>
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-6">Profile</h2>

        <!-- Display user information -->
        <div class="mb-4">
            <strong>Name:</strong> {{ Auth::user()->name }}
        </div>
        <div class="mb-4">
            <strong>Email:</strong> {{ Auth::user()->email }}
        </div>

        <!-- Display user photo if available -->
        @if (Auth::user()->photo)
            <div class="mb-4">
                <strong>Photo:</strong><br>
                <img src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->photo) }}" alt="User Photo" class="w-32 h-32 object-cover rounded-full">
            </div>
        @endif

        <!-- Edit Profile button -->
        <div class="mb-4">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Edit Profile
            </a>
        </div>

        <!-- Delete Account form -->
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Delete Account
            </button>
        </form>
    </div>
</div>
@endsection