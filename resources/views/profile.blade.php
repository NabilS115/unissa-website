@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <h2 class="text-3xl font-bold text-teal-700 mb-4">Profile</h2>
    <div class="flex flex-col items-center mb-6">
    <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Default profile icon" class="w-32 h-32 rounded-full object-cover mb-2 border-4 border-teal-600">
        <form action="/settings/profile/photo" method="POST" enctype="multipart/form-data" class="flex flex-col items-center gap-2">
            @csrf
            <label for="profile_photo" class="bg-teal-600 text-white px-3 py-1 rounded cursor-pointer hover:bg-teal-700">Change Photo</label>
            <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
        </form>
        @if(Auth::user()->profile_photo_url)
        <form action="/settings/profile/photo/delete" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete Photo</button>
        </form>
        @endif
    </div>
    <div class="mb-6">
        <div class="font-semibold text-lg">Name:</div>
        <div class="text-gray-700">{{ Auth::user()->name }}</div>
    </div>
    <div class="mb-6">
        <div class="font-semibold text-lg">Email:</div>
        <div class="text-gray-700">{{ Auth::user()->email }}</div>
    </div>
    <a href="/settings/profile" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">Edit Profile</a>
</div>
@endsection
