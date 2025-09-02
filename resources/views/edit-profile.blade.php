@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <div class="flex items-center gap-4 mb-6">
        <div class="relative group" id="profile-photo-group">
            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-teal-600 cursor-pointer" id="profile-photo-trigger">
            <button type="button" id="profile-photo-icon" class="absolute inset-0 flex items-center justify-center w-full h-full bg-transparent rounded-full focus:outline-none" style="z-index:2;">
                <svg xmlns="http://www.w3.org/2000/svg" class="opacity-0 group-hover:opacity-80 transition" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white">
                    <circle cx="12" cy="13" r="3.2" stroke="white" stroke-width="2" fill="none" />
                    <rect x="4" y="7" width="16" height="12" rx="3" stroke="white" stroke-width="2" fill="none" />
                    <rect x="9" y="3" width="6" height="4" rx="2" stroke="white" stroke-width="2" fill="none" />
                </svg>
            </button>
            <div id="profile-photo-menu" class="absolute left-1/2 top-full mt-2 w-28 bg-white border border-teal-600 rounded-lg shadow-lg py-1 opacity-0 pointer-events-none z-50 transform -translate-x-1/2 transition text-sm">
                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <label for="profile_photo" class="block px-4 py-2 text-teal-700 hover:bg-teal-50 hover:text-teal-900 cursor-pointer transition rounded">
                        Upload New Photo
                        <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                    </label>
                </form>
                @if(Auth::user()->profile_photo_url)
                <form method="POST" action="{{ route('profile.photo.delete') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 hover:text-red-800 transition rounded">Delete Photo</button>
                </form>
                @endif
            </div>
            <script>
                const trigger = document.getElementById('profile-photo-trigger');
                const icon = document.getElementById('profile-photo-icon');
                const menu = document.getElementById('profile-photo-menu');
                function toggleMenu() {
                    menu.classList.toggle('opacity-0');
                    menu.classList.toggle('pointer-events-none');
                }
                trigger.addEventListener('click', toggleMenu);
                icon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMenu();
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !trigger.contains(e.target) && !icon.contains(e.target)) {
                        menu.classList.add('opacity-0');
                        menu.classList.add('pointer-events-none');
                    }
                });
            </script>
        </div>
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
