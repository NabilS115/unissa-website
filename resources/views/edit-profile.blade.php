@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <div class="flex items-center gap-4 mb-6">
        <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-teal-600">
        <div>
            <div class="text-2xl font-bold text-teal-700">{{ Auth::user()->name }}</div>
            <div class="text-gray-600">Role: {{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</div>
        </div>
    </div>
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input name="name" id="name" type="text" value="{{ old('name', Auth::user()->name) }}" required autofocus autocomplete="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" />
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input name="email" id="email" type="email" value="{{ old('email', Auth::user()->email) }}" required autocomplete="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" />
            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div class="mt-2">
                    <span class="text-sm text-yellow-600">Your email address is unverified.</span>
                    <button type="button" formaction="{{ route('verification.send') }}" class="ml-2 text-sm text-teal-700 underline">Click here to re-send the verification email.</button>
                    @if (session('status') === 'verification-link-sent')
                        <span class="block mt-2 font-medium text-green-600">A new verification link has been sent to your email address.</span>
                    @endif
                </div>
            @endif
        </div>
        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition w-full">Save</button>
            @if (session('profile-updated'))
                <span class="me-3 text-green-600 font-medium">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</div>
@endsection
