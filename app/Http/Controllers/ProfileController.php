<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function deletePhoto(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_photo_url) {
            // Remove file from storage if it exists and is not the default
            $path = str_replace('/storage/', '', $user->profile_photo_url);
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            $user->profile_photo_url = null;
            $user->save();
        }
        return \Illuminate\Support\Facades\Redirect::route('edit.profile')->with('profile-photo-deleted', true);
    }
    public function update(Request $request)
    {
        \Log::info('Profile update method called');
        \Log::info('Request data:', $request->all());
        \Log::info('User ID: ' . Auth::id());
        
        $user = Auth::user();
        
        try {

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone' => ['nullable', 'string', 'max:30'],
            ]);

            \Log::info('Validation passed:', $validated);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? null;
            $user->save();

            \Log::info('Profile updated successfully for user: ' . $user->id);

            return Redirect::route('edit.profile')->with('profile-updated', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile. Please try again.')->withInput();
        }
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
        public function updatePassword(Request $request)
    {
        \Log::info('Password update method called');
        \Log::info('Request data (without passwords):', $request->except(['current_password', 'password', 'password_confirmation']));
        
        try {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            \Log::info('Password validation passed');

            $user = $request->user();
            $user->password = Hash::make($request->password);
            $user->save();

            \Log::info('Password updated successfully for user: ' . $user->id);

            return back()->with('password-updated', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Password validation failed:', $e->errors());
            return back()->withErrors($e->errors(), 'updatePassword')->withInput();
        } catch (\Exception $e) {
            \Log::error('Password update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update password. Please try again.');
        }
    }
    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Account deleted successfully.');
    }

    public function updatePayment(Request $request)
    {
        $user = Auth::user();
        try {
            $validated = $request->validate([
                'payment_method' => ['nullable', 'string', 'max:32'],
                'payment_details' => ['nullable', 'string', 'max:255'],
                'cardholder_name' => ['nullable', 'string', 'max:100'],
                'card_number' => ['nullable', 'string', 'max:32'],
                'card_expiry' => ['nullable', 'string', 'max:7'],
                'card_ccv' => ['nullable', 'string', 'max:8'],
                'billing_address' => ['nullable', 'string', 'max:255'],
            ]);

            $user->payment_method = $validated['payment_method'] ?? null;
            $user->payment_details = $validated['payment_details'] ?? null;
            $user->cardholder_name = $validated['cardholder_name'] ?? null;
            $user->card_number = $validated['card_number'] ?? null;
            $user->card_expiry = $validated['card_expiry'] ?? null;
            $user->card_ccv = $validated['card_ccv'] ?? null;
            $user->billing_address = $validated['billing_address'] ?? null;
            $user->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Payment method updated!']);
            }
            return Redirect::route('edit.profile')->with('payment-updated', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update payment method. Please try again.'], 500);
            }
            return back()->with('error', 'Failed to update payment method. Please try again.')->withInput();
        }
    }
}
