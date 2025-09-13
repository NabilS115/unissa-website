@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <div>
            <h2 class="text-2xl font-extrabold text-center text-gray-700">
                Create an Account
            </h2>
        </div>
        <form action="/register" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" required autofocus
                    class="block w-full px-3 py-2 mt-1 text-gray-900 bg-gray-50 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent"
                    placeholder="Enter your name">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="block w-full px-3 py-2 mt-1 text-gray-900 bg-gray-50 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent"
                    placeholder="Enter your email">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="block w-full px-3 py-2 mt-1 text-gray-900 bg-gray-50 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent"
                    placeholder="Create a password">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="block w-full px-3 py-2 mt-1 text-gray-900 bg-gray-50 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent"
                    placeholder="Confirm your password">
            </div>
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700">Profile Photo</label>
                <input id="photo" type="file" name="photo" accept="image/*"
                    class="block w-full px-3 py-2 mt-1 text-gray-900 bg-gray-50 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
                @error('photo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <button type="submit"
                    class="flex items-center justify-center w-full px-4 py-2 text-sm font-semibold text-white bg-teal-600 rounded-md shadow-md hover:bg-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-opacity-50">
                    Register
                </button>
            </div>
        </form>
        <div class="flex items-center justify-between">
            <div class="text-sm">
                <a href="/login" class="font-medium text-teal-600 hover:text-teal-500">
                    Already have an account? Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection