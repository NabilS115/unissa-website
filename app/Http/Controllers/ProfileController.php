<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

    return Redirect::route('profile')->with('profile-updated', true);
    }
    
        public function updatePhoto(Request $request)
        {
            $request->validate([
                'profile_photo' => ['required', 'image', 'max:2048'],
            ]);

            $user = Auth::user();
            $file = $request->file('profile_photo');
            $path = $file->store('profile-photos', 'public');
            $user->profile_photo_url = \Illuminate\Support\Facades\Storage::url($path);
            $user->save();

            return Redirect::route('edit.profile')->with('profile-photo-updated', true);
        }
}
