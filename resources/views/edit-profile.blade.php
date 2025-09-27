@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- Cover Background -->
            <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500"></div>
            
            <!-- Profile Content -->
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <!-- Profile Picture -->
                        <div class="relative group" id="profile-photo-group">
                            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" 
                                 alt="Profile Picture" 
                                 class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white cursor-pointer" 
                                 id="profile-photo-trigger">
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-teal-500 rounded-full border-4 border-white shadow-sm flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </div>
                            
                            <button type="button" id="profile-photo-icon" class="absolute inset-0 flex items-center justify-center w-full h-full bg-black bg-opacity-0 hover:bg-opacity-50 rounded-2xl focus:outline-none transition-all duration-200 group-hover:bg-opacity-30" style="z-index:2;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="opacity-0 group-hover:opacity-100 transition-opacity duration-200" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                    <circle cx="12" cy="13" r="3.2" stroke="white" stroke-width="2" fill="none" />
                                    <rect x="4" y="7" width="16" height="12" rx="3" stroke="white" stroke-width="2" fill="none" />
                                    <rect x="9" y="3" width="6" height="4" rx="2" stroke="white" stroke-width="2" fill="none" />
                                </svg>
                            </button>
                            
                            <div id="profile-photo-menu" class="absolute left-1/2 top-full mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-2 opacity-0 pointer-events-none z-50 transform -translate-x-1/2 transition-all duration-200">
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
                        
                        <!-- Profile Info -->
                        <div class="lg:mb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name }}</h1>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="flex items-center gap-4 text-gray-600 mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">{{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Back Button -->
                    <div class="lg:mb-4">
                        <a href="/profile" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 border border-gray-200 hover:border-gray-300 transition-all duration-200 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back to Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Width Form Sections -->
        <div class="space-y-8">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Basic Information</h2>
                        <p class="text-gray-600">Update your personal details and contact information</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Full Name</label>
                            <div class="relative">
                                <input name="name" id="name" type="text" value="{{ old('name', Auth::user()->name) }}" 
                                       required autofocus autocomplete="name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 pl-11" />
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                            <div class="relative">
                                <input name="email" id="email" type="email" value="{{ old('email', Auth::user()->email) }}" 
                                       required autocomplete="email" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 pl-11" />
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm text-yellow-800 font-medium">Email verification required</span>
                                    </div>
                                    @if (session('status') === 'verification-link-sent')
                                        <div class="mt-2 text-sm text-green-700 font-medium">
                                            ✓ A new verification link has been sent to your email.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        @if (session('profile-updated') || session('status') === 'profile-updated')
                            <div class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Profile updated successfully!
                            </div>
                        @endif
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-medium shadow-lg hover:shadow-xl transform hover:scale-105 duration-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Change Password</h2>
                        <p class="text-gray-600">Update your password to keep your account secure</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div class="relative">
                            <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">Current Password</label>
                            <div class="relative">
                                <input name="current_password" id="current_password" type="password" required autocomplete="current-password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 pr-12" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('current_password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">New Password</label>
                            <div class="relative">
                                <input name="password" id="password" type="password" required autocomplete="new-password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 pr-12" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <input name="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 pr-12" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('password_confirmation', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                        @if (session('password-updated') || session('status') === 'password-updated')
                            <div class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium shadow-lg hover:shadow-xl transform hover:scale-105 duration-200">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Profile photo menu functionality
    document.addEventListener('DOMContentLoaded', function() {
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
    });

    // Password toggle functionality
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        if (!input || !btn) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            btn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />';
        } else {
            input.type = 'password';
            btn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
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
</style>
@endsection

@if (session('password-updated'))
    <div id="password-toast" class="fixed top-6 right-6 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 animate-fade-in flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ __('Password updated successfully!') }}
    </div>
    <script>
        setTimeout(function() {
            var toast = document.getElementById('password-toast');
            if (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    </script>
@endif

<script>
    // Profile photo menu functionality
    const trigger = document.getElementById('profile-photo-trigger');
    const icon = document.getElementById('profile-photo-icon');
    const menu = document.getElementById('profile-photo-menu');
    
    function toggleMenu() {
        menu.classList.toggle('opacity-0');
        menu.classList.toggle('pointer-events-none');
    }
    
    if (trigger && menu) {
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
    }

    // Reviews carousel functionality
    let currentReview = 0;
    const totalReviews = {{ Auth::user()->reviews->count() }};
    
    function moveReview(dir) {
        if (totalReviews <= 1) return;
        
        const track = document.getElementById('reviews-track');
        currentReview = (currentReview + dir + totalReviews) % totalReviews;
        track.style.transform = `translateX(-${currentReview * 100}%)`;
        updateReviewDots();
    }
    
    function goToReview(index) {
        if (totalReviews <= 1) return;
        
        const track = document.getElementById('reviews-track');
        currentReview = index;
        track.style.transform = `translateX(-${currentReview * 100}%)`;
        updateReviewDots();
    }
    
    function updateReviewDots() {
        document.querySelectorAll('.review-dot').forEach((dot, index) => {
            if (index === currentReview) {
                dot.classList.add('bg-teal-600');
                dot.classList.remove('bg-gray-300');
            } else {
                dot.classList.remove('bg-teal-600');
                dot.classList.add('bg-gray-300');
            }
        });
    }

    // Read more functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.read-more-btn').forEach(btn => {
            btn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const reviewId = this.getAttribute('data-review-id');
                const truncatedText = document.querySelector(`.review-text-${reviewId}`);
                const fullText = document.querySelector(`.review-full-${reviewId}`);
                
                if (this.textContent === 'Read more') {
                    truncatedText.classList.add('hidden');
                    fullText.classList.remove('hidden');
                    this.textContent = 'Read less';
                } else {
                    truncatedText.classList.remove('hidden');
                    fullText.classList.add('hidden');
                    this.textContent = 'Read more';
                }
            };
        });
    });

    // Password toggle functionality
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            btn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />';
        } else {
            input.type = 'password';
            btn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
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
</style>
