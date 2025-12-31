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
            // 'role' => ['required', 'in:user,admin'], // Removed role validation
        ]);

        $photoData = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            
            // Security validation
            if (!$file->isValid()) {
                throw new \Exception('Invalid file uploaded');
            }
            
            // Additional MIME type validation
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                throw new \Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.');
            }
            
            // Size validation (5MB max)
            if ($file->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('File size must be less than 5MB');
            }
            
            $photoData = $file->getContent();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoData,
            'role' => 'user', // Always set role to user
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to homepage after registration
        return redirect('/');
    }
}
