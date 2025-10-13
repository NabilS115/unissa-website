@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Unified Profile Header (admin and regular) -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- Cover Background -->
            @if(Auth::user()->role === 'admin')
                <div class="h-32 bg-gradient-to-r from-teal-500 via-teal-600 to-emerald-600 relative"></div>
            @else
                <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500 relative"></div>
            @endif

            <!-- Profile Content -->
            <div class="relative px-8 pb-8">
                <div class="flex items-start justify-between -mt-16 relative">
                    <!-- Profile Picture (positioned absolutely to break out of flow) -->
                    <div class="relative group flex-shrink-0 z-10" id="profile-photo-group">
                        <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" 
                             alt="Profile Picture" 
                             class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-xl bg-white cursor-pointer" 
                             id="profile-photo-trigger">
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-teal-500 rounded-full border-2 border-white shadow-lg flex items-center justify-center z-30">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </div>

                        <button type="button" id="profile-photo-icon" class="absolute inset-0 flex items-center justify-center w-full h-full bg-black bg-opacity-0 hover:bg-opacity-50 rounded-2xl focus:outline-none transition-all duration-200 group-hover:bg-opacity-30" style="z-index:2;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="opacity-0 group-hover:opacity-100 transition-opacity duration-200" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                <circle cx="12" cy="13" r="3.2" stroke="white" stroke-width="2" fill="none" />
                                <rect x="4" y="7" width="16" height="12" rx="3" stroke="white" stroke-width="2" fill="none" />
                                <rect x="9" y="3" width="6" height="4" rx="2" stroke="white" stroke-width="2" fill="none" />
                            </svg>
                        </button>

                        <div id="profile-photo-menu" class="absolute left-1/2 top-full mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-xl py-2 opacity-0 pointer-events-none z-50 transform -translate-x-1/2 transition-all duration-200">
                            <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                                @csrf
                                <label for="profile_photo" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-teal-50 hover:text-teal-700 cursor-pointer transition-colors rounded-lg mx-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Upload New Photo
                                    <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                                </label>
                            </form>
                            @if(Auth::user()->profile_photo_url)
                            <form method="POST" action="{{ route('profile.photo.delete') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors rounded-lg mx-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Photo
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Center Content: Profile Info -->
                    <div class="flex-1 ml-8 mt-8">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-lg font-semibold text-teal-700">{{ ucfirst(Auth::user()->role ?? 'User') }}</span>
                        </div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ Auth::user()->name }}</h1>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span>{{ Auth::user()->email }}</span>
                        </div>
                    </div>

                    <!-- Right Side: Back Button -->
                    <div class="mt-8">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.profile') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 border border-gray-200 hover:border-gray-300 transition-all duration-200 font-medium shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back to Profile
                            </a>
                        @else
                            <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 border border-gray-200 hover:border-gray-300 transition-all duration-200 font-medium shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back to Profile
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Width Form Sections -->
        <div class="space-y-8">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center gap-3 mb-6">
                    @if(Auth::user()->role === 'admin')
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Administrator Details</h2>
                            <p class="text-gray-600">Update your administrative profile and contact information</p>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Profile Details</h2>
                            <p class="text-gray-600">Update your profile and contact information</p>
                        </div>
                    @endif
                </div>

                <!-- Form Fields -->
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6" onsubmit="console.log('Profile form submitted');">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Full Name</label>
                            <input name="name" id="name" type="text" required autocomplete="name" 
                                   value="{{ old('name', Auth::user()->name) }}"
                                   class="w-full px-4 py-3 border {{ $errors->has('name') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" />
                            @if ($errors->has('name'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                            <input name="email" id="email" type="email" required autocomplete="username" 
                                   value="{{ old('email', Auth::user()->email) }}"
                                   class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" />
                            @if ($errors->has('email'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        @if (session('error'))
                            <div class="flex items-center gap-2 text-red-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('profile-updated') || session('status') === 'profile-updated')
                            <div class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Profile updated successfully!
                            </div>
                        @endif
                        <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105" onclick="console.log('Update Profile button clicked');">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Update Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Update Password</h2>
                        <p class="text-gray-600">Change your account password for security</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-6" onsubmit="console.log('Password form submitted');">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 gap-6">
                        <div class="relative">
                            <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">Current Password</label>
                            <div class="relative">
                                <input name="current_password" id="current_password" type="password" required autocomplete="current-password" 
                                       class="w-full px-4 py-3 border {{ $errors->updatePassword->has('current_password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 pr-12" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('current_password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->updatePassword->has('current_password'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div class="relative">
                            <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">New Password</label>
                            <div class="relative">
                                <input name="password" id="password" type="password" required autocomplete="new-password" 
                                       class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 pr-12" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->updatePassword->has('password'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                            <div class="mt-2">
                                <p class="text-xs text-gray-500">Password must be at least 8 characters long.</p>
                            </div>
                        </div>
                        <div class="relative">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <input name="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password" 
                                       class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 pr-12" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('password_confirmation', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        @if (session('error'))
                            <div class="flex items-center gap-2 text-red-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('password-updated') || session('status') === 'password-updated')
                            <div class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Password updated successfully!
                            </div>
                        @endif
                        <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105" onclick="console.log('Update Password button clicked');">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast Messages -->
@if (session('profile-updated') || session('status') === 'profile-updated')
    <div id="profile-toast" class="fixed top-6 right-6 bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg z-50 animate-fade-in flex items-center gap-3 max-w-md">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>Profile updated successfully!</span>
    </div>
@endif

@if (session('password-updated'))
    <div id="password-toast" class="fixed top-6 right-6 bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg z-50 animate-fade-in flex items-center gap-3 max-w-md">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>Password updated successfully!</span>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Profile photo menu functionality
        const trigger = document.getElementById('profile-photo-trigger');
        const icon = document.getElementById('profile-photo-icon');
        const menu = document.getElementById('profile-photo-menu');
        
        function toggleMenu() {
            if (menu) {
                menu.classList.toggle('opacity-0');
                menu.classList.toggle('pointer-events-none');
            }
        }
        
        if (trigger && menu) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMenu();
            });
            
            if (icon) {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMenu();
                });
            }
            
            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && !trigger.contains(e.target) && (!icon || !icon.contains(e.target))) {
                    menu.classList.add('opacity-0');
                    menu.classList.add('pointer-events-none');
                }
            });
        }

        // Auto-hide toast messages
        const toasts = document.querySelectorAll('[id$="-toast"]');
        toasts.forEach(toast => {
            setTimeout(() => {
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        });
    });

    // Password toggle functionality
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        if (!input || !btn) return;
        
        const svg = btn.querySelector('svg');
        if (!svg) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>';
            btn.title = 'Hide password';
        } else {
            input.type = 'password';
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            btn.title = 'Show password';
        }
    }
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    /* Enhanced form focus states */
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Smooth transitions for all interactive elements */
    button, input, .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection
