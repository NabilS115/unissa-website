@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <div>
            <h2 class="text-2xl font-extrabold text-center text-gray-700">
                Welcome back
            </h2>
            <p class="mt-2 text-center text-gray-600">
                Please sign in to your account
            </p>
        </div>
        <form class="space-y-4" action="/login" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" required autofocus class="block w-full px-3 py-2 text-gray-900 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent" placeholder="you@example.com">
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required class="block w-full px-3 py-2 text-gray-900 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent" placeholder="••••••••">
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-teal-600 hover:text-teal-500">
                        Forgot your password?
                    </a>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                    Sign in
                </button>
            </div>
        </form>
        <div class="flex items-center justify-center">
            <div class="text-sm">
                <span class="text-gray-500">Don't have an account?</span>
                <a href="/register" class="font-medium text-teal-600 hover:text-teal-500">
                    Sign up
                </a>
            </div>
        </div>
    </div>
</div>
@endsection