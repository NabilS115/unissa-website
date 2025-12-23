@extends('layouts.app')

@php
    $context = session('header_context', 'tijarah');
    $pageTitle = $context === 'unissa-cafe' ? 'UNISSA Cafe - Edit Profile' : 'Tijarah Co - Edit Profile';
@endphp

@section('title', $pageTitle)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <!-- Removed top Back to Profile button -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Edit Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500 relative">
                @php
                    $currentContext = session('header_context', 'tijarah');
                    $referer = request()->headers->get('referer');
                    $isAdmin = Auth::user()->role === 'admin';
                    
                    // OVERRIDE: Check if we came from a Tijarah context profile page
                    $refererSuggestsTijarah = $referer && (
                        str_ends_with($referer, '/') || 
                        preg_match('/^https?:\/\/[^\/]+\/?$/', $referer) ||
                        str_contains($referer, '/company-history') ||
                        str_contains($referer, '/contact') ||
                        (str_ends_with($referer, '/profile') && !str_contains($referer, 'context=unissa-cafe')) ||
                        (str_ends_with($referer, '/admin-profile') && !str_contains($referer, 'context=unissa-cafe'))
                    );
                    
                    // Override context if referer suggests Tijarah
                    if ($refererSuggestsTijarah) {
                        $currentContext = 'tijarah';
                    }
                    
                    if ($isAdmin) {
                        $backUrl = $currentContext === 'unissa-cafe' ? '/admin-profile?context=unissa-cafe' : '/admin-profile';
                    } else {
                        $backUrl = $currentContext === 'unissa-cafe' ? '/profile?context=unissa-cafe' : '/profile';
                    }
                @endphp
                <a href="{{ $backUrl }}" class="absolute top-4 right-4 inline-flex items-center px-4 py-2 bg-white border border-teal-200 text-teal-700 font-semibold rounded-xl shadow hover:bg-teal-50 hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-400 transition-all duration-200 z-10">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Profile
                </a>
            </div>
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6 w-full">
                        <div class="relative group">
                            @if(Auth::check())
                                <img id="profile-photo" src="{{ Auth::user()->profile_photo_url }}" alt="Profile Picture" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white cursor-pointer" loading="lazy" decoding="async"
                                     onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTI4IiBoZWlnaHQ9IjEyOCIgdmlld0JveD0iMCAwIDEyOCAxMjgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEyOCIgaGVpZ2h0PSIxMjgiIHJ4PSIxNiIgZmlsbD0iI2Y5ZmFmYiIvPjxjaXJjbGUgY3g9IjY0IiBjeT0iNDgiIHI9IjE2IiBmaWxsPSIjOWNhM2FmIi8+PHBhdGggZD0iTTI0IDk2YzAtMTYgMTYtMzIgNDAtMzJzNDAgMTYgNDAgMzJaIiBmaWxsPSIjOWNhM2FmIi8+PC9zdmc+'">
                            @endif
                            @php
                                $userPhoto = Auth::user()->profile_photo_url;
                                // If the photo is a data URI (SVG fallback), it's not a custom upload
                                $hasCustomPhoto = $userPhoto && !str_starts_with($userPhoto, 'data:image/svg+xml');
                            @endphp
                       <button id="profile-photo-overlay" type="button" aria-label="Change profile photo" title="Change profile photo" class="absolute inset-0 bg-black/40 rounded-2xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity duration-200 cursor-pointer select-none focus:outline-none focus:ring-4 focus:ring-teal-300/30">
                                <svg class="w-12 h-12 text-white/95" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <circle cx="12" cy="14" r="3.5" stroke="currentColor" stroke-width="1.5" fill="none" />
                                </svg>
                                <span class="mt-2 text-sm font-medium text-white/90">Change photo</span>
                            </button>
                        </div>
                        
                        <div class="lg:mb-4 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2 w-full">
                                <div class="flex flex-col gap-0 mb-2">
                                    <div class="flex items-center gap-3">
                                        <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name ?? 'Dr. Ahmad bin Ali' }}</h1>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600 mt-2">
                                        <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                        </svg>
                                        <span class="font-medium">{{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</span>
                                    </div>
                                </div>
                                <!-- removed Back to Profile button from here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8">
            <div class="flex space-x-2 md:space-x-6 border-b border-teal-200">
                <button class="tab-btn px-4 py-2 text-teal-700 font-semibold border-b-2 border-transparent focus:outline-none transition-all duration-200" data-tab="profile">Profile</button>
                @if(Auth::user()->role !== 'admin')
                    <button class="tab-btn px-4 py-2 text-teal-700 font-semibold border-b-2 border-transparent focus:outline-none transition-all duration-200" data-tab="payment">Payment</button>
                @endif
                <button class="tab-btn px-4 py-2 text-teal-700 font-semibold border-b-2 border-transparent focus:outline-none transition-all duration-200" data-tab="password">Password</button>
            </div>
        </div>
        <div id="tab-content-profile" class="tab-content">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form id="profile-form" method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('put')
                    <div class="border-l-4 border-teal-400 pl-4">
                        <label for="name" class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                        <input name="name" id="name" type="text" required autocomplete="name" 
                               value="{{ old('name', Auth::user()->name) }}"
                               class="w-full px-4 py-3 border {{ $errors->has('name') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" oninput="validateName()" />
                        <p id="name-validation" class="mt-2 text-sm"></p>
                        @if ($errors->has('name'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                    <div class="border-l-4 border-blue-400 pl-4">
                        <label for="email" class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                        <input name="email" id="email" type="email" required autocomplete="username" 
                               value="{{ old('email', Auth::user()->email) }}"
                               class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" oninput="validateEmail()" />
                        <p id="email-validation" class="mt-2 text-sm"></p>
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    <div class="border-l-4 border-green-400 pl-4">
                        <label for="phone" class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                        <input name="phone" id="phone" type="text" autocomplete="tel" 
                               value="{{ old('phone', Auth::user()->phone) }}"
                               class="w-full px-4 py-3 border {{ $errors->has('phone') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" placeholder="e.g. +673 1234567" oninput="validatePhone()" />
                        <p id="phone-validation" class="mt-2 text-sm"></p>
                        @if ($errors->has('phone'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('phone') }}</p>
                        @endif
                    </div>
                    @if(Auth::user()->role !== 'admin')
                    <div class="border-l-4 border-purple-400 pl-4">
                        <label for="department" class="block text-sm font-medium text-gray-500 mb-1">Faculty / Department</label>
                        <input name="department" id="department" type="text" autocomplete="organization" 
                               value="{{ old('department', Auth::user()->department) }}"
                               class="w-full px-4 py-3 border {{ $errors->has('department') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" placeholder="e.g. Faculty of Usuluddin" oninput="validateDepartment()" />
                        <p id="department-validation" class="mt-2 text-sm"></p>
                        @if ($errors->has('department'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('department') }}</p>
                        @endif
                    </div>
                    @endif
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        @if (session('error'))
                            <div class="flex items-center gap-2 text-red-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('error') }}
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
        </div>
        
        <!-- Password Tab Content -->
        <div id="tab-content-password" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form id="password-form" method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('put')
                    <div class="border-l-4 border-red-400 pl-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-500 mb-1">Current Password</label>
                        <div class="relative">
                            <input name="current_password" id="current_password" type="password" required autocomplete="current-password"
                                   class="w-full px-4 py-3 border {{ $errors->updatePassword->has('current_password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" />
                            <button type="button" data-target="current_password" class="password-toggle absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none" title="Show password">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                    <!-- refined slash for closed-eye -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @if (optional($errors->updatePassword)->has('current_password'))
                            <p class="mt-2 text-sm text-red-600">{{ optional($errors->updatePassword)->first('current_password') }}</p>
                        @endif
                    </div>

                    <div class="border-l-4 border-yellow-400 pl-4">
                        <label for="password" class="block text-sm font-medium text-gray-500 mb-1">New Password</label>
                        <div class="relative">
                            <input name="password" id="password" type="password" required autocomplete="new-password"
                                   class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" />
                            <button type="button" data-target="password" class="password-toggle absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none" title="Show password">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                    <!-- refined slash for closed-eye -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @if (optional($errors->updatePassword)->has('password'))
                            <p class="mt-2 text-sm text-red-600">{{ optional($errors->updatePassword)->first('password') }}</p>
                        @endif
                    </div>

                    <div class="border-l-4 border-green-400 pl-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-500 mb-1">Confirm New Password</label>
                        <div class="relative">
                            <input name="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all duration-200" />
                            <button type="button" data-target="password_confirmation" class="password-toggle absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none" title="Show password">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                    <!-- refined slash for closed-eye -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                        @if (session('password-updated'))
                            <div class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Password updated successfully!
                            </div>
                        @endif
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        @if(Auth::user()->role !== 'admin')
            <div id="tab-content-payment" class="tab-content hidden">
                <!-- Payment Method Details Card (Payment Tab) -->
                <div class="bg-[#f8fafc] border border-[#0d9488] rounded-2xl shadow-lg p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-[#0d9488] rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-[#0d9488]">Payment Method Details</h2>
                            <p class="text-[#007070]">Save your preferred payment method for faster checkout</p>
                        </div>
                    </div>
                <form id="payment-method-form" method="POST" action="{{ route('profile.payment') }}" class="space-y-6">
                    @csrf
                    @method('put')
                    
                    @if(Auth::user()->role !== 'admin')
                        <!-- Personal Payment Methods (Non-Admin Users Only) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="payment_method" class="block text-sm font-semibold text-[#0d9488] mb-2">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] bg-white" onchange="togglePaymentFields()">
                                    <option value="">Select a payment method</option>
                                    <option value="credit_card" {{ old('payment_method', Auth::user()->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit/Debit Card</option>
                                    <option value="bank_transfer" {{ old('payment_method', Auth::user()->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                            </div>
                            <div>
                            <div id="card-fields" style="display:none;">
                                <label for="cardholder_name" class="block text-sm font-semibold text-[#0d9488] mb-2">Cardholder Name</label>
                                <input name="cardholder_name" id="cardholder_name" type="text" autocomplete="cc-name"
                                    value="{{ old('cardholder_name', Auth::user()->cardholder_name) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Name on card" oninput="validateCardholderName()" />
                                <p id="cardholder-name-validation" class="mt-2 text-sm"></p>
                                <label for="card_number" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Card Number</label>
                                <input name="card_number" id="card_number" type="text" autocomplete="cc-number"
                                    value="{{ old('card_number', Auth::user()->card_number) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Card number" oninput="validateCardNumber()" />
                                <p id="card-number-validation" class="mt-2 text-sm"></p>
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="card_expiry" class="block text-sm font-semibold text-[#0d9488] mb-2">Expiry (MM/YY)</label>
                                        <input name="card_expiry" id="card_expiry" type="text" autocomplete="cc-exp"
                                            value="{{ old('card_expiry', Auth::user()->card_expiry) }}"
                                            class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="MM/YY" oninput="validateCardExpiry()" />
                                        <p id="card-expiry-validation" class="mt-2 text-sm"></p>
                                    </div>
                                    <div>
                                        <label for="card_ccv" class="block text-sm font-semibold text-[#0d9488] mb-2">CCV</label>
                                        <input name="card_ccv" id="card_ccv" type="text" autocomplete="cc-csc"
                                            value="{{ old('card_ccv', Auth::user()->card_ccv) }}"
                                            class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="CCV" oninput="validateCardCCV()" />
                                        <p id="card-ccv-validation" class="mt-2 text-sm"></p>
                                    </div>
                                </div>
                                <label for="billing_address" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Billing Address</label>
                                <input name="billing_address" id="billing_address" type="text" autocomplete="street-address"
                                    value="{{ old('billing_address', Auth::user()->billing_address) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Billing address" />
                            </div>
                            <div id="bank-fields" style="display:none;">
                                <label for="bank_name" class="block text-sm font-semibold text-[#0d9488] mb-2">Bank</label>
                                <div class="w-full px-4 py-3 border border-[#0d9488] rounded-xl bg-gray-50 text-[#0d9488] font-medium">
                                    BIBD (Bank Islam Brunei Darussalam)
                                </div>
                                <input type="hidden" name="bank_name" value="BIBD" />
                                <p class="text-xs text-[#0d9488] mt-1">We only accept BIBD transfers for faster processing</p>
                                
                                <label for="bank_account" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Account Number</label>
                                <input name="bank_account" id="bank_account" type="text" autocomplete="off"
                                    value="{{ old('bank_account', Auth::user()->bank_account) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Account number" maxlength="16" pattern="^[0-9]{16}$" />
                                <p id="bank-account-validation" class="mt-2 text-sm"></p>

                                <label for="bank_reference" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Reference</label>
                                <input name="bank_reference" id="bank_reference" type="text" autocomplete="off"
                                    value="{{ old('bank_reference', Auth::user()->bank_reference) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Reference (optional)" />
                            </div>
                        </div>
                        
                        <!-- Save Personal Payment Method Button (Non-Admin Users Only) -->
                        <div class="flex justify-end w-full pt-6">
                            <button type="submit" id="save-payment-btn" class="px-6 py-3 bg-[#0d9488] hover:bg-[#007070] text-white font-semibold rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2 transition-all duration-200" autocomplete="off">
                                <span class="whitespace-nowrap">Save Payment Method</span>
                            </button>
                        </div>
                    @else
                        <!-- Admin Bank Transfer Settings (Admin Users Only) -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-6 h-6 text-[#0d9488]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <h4 class="text-xl font-bold text-[#0d9488]">UNISSA Bank Transfer Settings</h4>
                            </div>
                            <p class="text-gray-600 mb-6">Configure the bank account details that customers will see during BIBD bank transfer checkout</p>
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="admin_bank_account_name" class="block text-sm font-semibold text-[#0d9488] mb-2">UNISSA Account Name</label>
                                    <input type="text" id="admin_bank_account_name"
                                        value="{{ \App\Models\ContentBlock::get('bank_account_name', 'UNISSA Café', 'text', 'bank-transfer') }}"
                                        class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" 
                                        placeholder="e.g., UNISSA Café"
                                        onchange="updateBankTransferSetting('bank_account_name', this.value)" />
                                    <p class="text-xs text-gray-500 mt-1">The exact name on your business bank account</p>
                                </div>
                                
                                <div>
                                    <label for="admin_bank_account_number" class="block text-sm font-semibold text-[#0d9488] mb-2">UNISSA Account Number</label>
                                    <input type="text" id="admin_bank_account_number"
                                        value="{{ \App\Models\ContentBlock::get('bank_account_number', '', 'text', 'bank-transfer') }}"
                                        class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white font-mono" 
                                        placeholder="e.g., 1234567890123456"
                                        onchange="updateBankTransferSetting('bank_account_number', this.value)" />
                                    <p class="text-xs text-gray-500 mt-1">The BIBD account number where customers will transfer money</p>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <strong>Admin Only:</strong> These settings control what customers see during BIBD bank transfer checkout. Changes apply immediately to all new orders.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </form>
            </div>
        </div>
        @endif

        <!-- Photo modal (used by /js/profile.js) -->
        <div id="photo-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 p-4 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="photo-modal-title">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-auto overflow-hidden border border-teal-100 max-h-[90vh] flex flex-col">
                                <div class="relative">
                                    <input name="current_password" id="current_password" type="password" required autocomplete="current-password"
                                           class="w-full px-4 py-3 border {{ $errors->updatePassword->has('current_password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" />
                                    <button type="button" data-target="current_password" class="password-toggle absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none" title="Show password">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                            <!-- refined slash for closed-eye -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                @if (optional($errors->updatePassword)->has('current_password'))
                                    <p class="mt-2 text-sm text-red-600">{{ optional($errors->updatePassword)->first('current_password') }}</p>
                                @endif
                            </div>

                            <div class="border-l-4 border-yellow-400 pl-4">
                                <label for="password" class="block text-sm font-medium text-gray-500 mb-1">New Password</label>
                                <div class="relative">
                                    <input name="password" id="password" type="password" required autocomplete="new-password"
                                           class="w-full px-4 py-3 border {{ $errors->updatePassword->has('password') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" />
                                    <button type="button" data-target="password" class="password-toggle absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none" title="Show password">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                            <!-- refined slash for closed-eye -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                @if (optional($errors->updatePassword)->has('password'))
                                    <p class="mt-2 text-sm text-red-600">{{ optional($errors->updatePassword)->first('password') }}</p>
                                @endif
                            </div>

                            <div class="border-l-4 border-green-400 pl-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-500 mb-1">Confirm New Password</label>
                                <div class="relative">
                                    <input name="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all duration-200" />
                                    <button type="button" data-target="password_confirmation" class="password-toggle absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 focus:outline-none" title="Show password">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                            <!-- refined slash for closed-eye -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                                @if (session('password-updated'))
                                    <div class="flex items-center gap-2 text-green-600 font-medium">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Password updated successfully!
                                    </div>
                                @endif
                                <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Photo modal (used by /js/profile.js) -->
                <div id="photo-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/60 p-4 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="photo-modal-title">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-auto overflow-hidden border border-teal-100 max-h-[90vh] flex flex-col">
                        <div class="flex items-start justify-between p-4 md:p-6 border-b border-teal-100 flex-shrink-0">
                            <h3 id="photo-modal-title" class="text-xl font-semibold text-teal-800">Update profile photo</h3>
                            <button id="photo-modal-close" type="button" class="text-teal-600 hover:text-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 rounded-md" aria-label="Close dialog">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-4 md:p-6 overflow-y-auto flex-1">
                            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                                <div class="lg:w-1/3 flex flex-col items-center flex-shrink-0">
                                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-2xl overflow-hidden border border-teal-100 shadow-lg flex items-center justify-center bg-white">
                                        <img id="modal-photo-preview" src="{{ Auth::user()->profile_photo_url }}" alt="Preview" class="w-full h-full object-cover" />
                                    </div>
                                    <p class="mt-3 text-sm text-teal-700 text-center">Preview — how your photo appears across the site.</p>
                                </div>

                                <div class="lg:flex-1">
                                    <!-- Initial state - file selection -->
                                    <div id="upload-section" class="">
                                        <p class="text-sm text-teal-700 mb-4">Select an image file to upload as your profile photo. Recommended size: square, at least 400×400px.</p>
                                        
                                        <div class="flex flex-wrap gap-3 items-center">
                                            <button id="upload-btn" type="button" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium shadow">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                Choose Image
                                            </button>
                                            <button id="change-btn" type="button" class="px-4 py-2 bg-white border border-teal-100 text-teal-700 rounded-xl hidden">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Change Image
                                            </button>
                                            <button id="delete-btn" type="button" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors hidden">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete Photo
                                            </button>
                                            <input id="photo-file-input" type="file" accept="image/*" class="hidden" />
                                        </div>
                                    </div>

                                    <!-- Loading state -->
                                    <div id="photo-modal-spinner" class="hidden mt-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full border-4 border-teal-200 border-t-teal-600"></div>
                                            <div class="text-sm text-teal-700 spinner-message">Processing image...</div>
                                        </div>
                                    </div>

                                    <!-- Cropper state -->
                                    <div id="cropper-area" class="hidden mt-6">
                                        <div class="mb-4">
                                            <h4 class="text-sm font-medium text-teal-800 mb-2">Crop & Adjust Your Photo</h4>
                                            <p class="text-sm text-teal-600">Drag to reposition and use the corners to resize the crop area.</p>
                                        </div>
                                        
                                        <div class="mx-auto cropper-wrapper relative w-full max-w-md overflow-hidden rounded-xl border border-teal-100 bg-white shadow-sm">
                                            <img id="cropper-image" src="" alt="Crop" class="w-full h-auto max-h-[40vh] object-contain" />
                                        </div>
                                        
                                        <div class="mt-4 flex flex-wrap items-center gap-3 pt-4 border-t border-teal-100">
                                            <button id="crop-upload-btn" type="button" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-medium shadow focus:outline-none focus:ring-2 focus:ring-teal-500">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Save Photo
                                            </button>
                                            <button id="crop-reset-btn" type="button" class="px-4 py-2 bg-white border border-teal-200 text-teal-700 rounded-xl hover:bg-teal-50 transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Reset
                                            </button>
                                            <button id="crop-cancel-btn" type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- profile script extracted to /js/profile.js -->
                <script>
                    window.__profile = {
                        csrf: '{{ csrf_token() }}',
                        routes: {
                            photoUpload: '{{ route('profile.photo') }}',
                            photoDelete: '{{ route('profile.photo.delete') }}'
                        },
                        defaultProfileUrl: '{{ asset('images/default-profile.svg') }}',
                        hasCustomPhoto: @json($hasCustomPhoto)
                    };
                </script>
                <script src="/js/profile.js?v={{ time() }}"></script>
                
                <!-- Robust tab switching functionality -->
                <script>
                (function() {
                    console.log('Edit profile tabs initializing...');
                    
                    function initTabSwitching() {
                        // Function to switch tabs with robust error handling
                        function switchToTab(tabName) {
                            console.log('Switching to tab:', tabName);
                            
                            try {
                                // Hide all tab contents first
                                const allTabs = document.querySelectorAll('.tab-content');
                                console.log('Found tabs:', allTabs.length);
                                
                                allTabs.forEach(function(tab) {
                                    tab.style.display = 'none';
                                    tab.classList.add('hidden');
                                    tab.classList.remove('tab-visible');
                                });
                                
                                // Show the target tab
                                const targetTab = document.getElementById('tab-content-' + tabName);
                                console.log('Target tab element:', targetTab);
                                
                                if (targetTab) {
                                    targetTab.style.display = 'block';
                                    targetTab.classList.remove('hidden');
                                    targetTab.classList.add('tab-visible');
                                    console.log('Successfully showed tab:', tabName);
                                } else {
                                    console.error('Tab not found:', 'tab-content-' + tabName);
                                }
                                
                                // Update button styles
                                const allButtons = document.querySelectorAll('.tab-btn');
                                allButtons.forEach(function(btn) {
                                    btn.classList.remove('border-teal-500', 'text-teal-900');
                                });
                                
                                const activeButton = document.querySelector('.tab-btn[data-tab="' + tabName + '"]');
                                if (activeButton) {
                                    activeButton.classList.add('border-teal-500', 'text-teal-900');
                                    console.log('Updated button styles for:', tabName);
                                }
                                
                            } catch (error) {
                                console.error('Error switching tabs:', error);
                            }
                        }
                        
                        // Add event listeners to tab buttons
                        const tabButtons = document.querySelectorAll('.tab-btn');
                        console.log('Found tab buttons:', tabButtons.length);
                        
                        tabButtons.forEach(function(button) {
                            const tabName = button.getAttribute('data-tab');
                            console.log('Setting up button for tab:', tabName);
                            
                            button.addEventListener('click', function(e) {
                                e.preventDefault();
                                console.log('Button clicked for tab:', tabName);
                                switchToTab(tabName);
                            });
                        });
                        
                        // Initialize with profile tab
                        console.log('Initializing with profile tab');
                        switchToTab('profile');
                        
                        // Make function globally available for debugging
                        window.switchToTab = switchToTab;
                    }
                    
                    // Initialize when DOM is ready
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initTabSwitching);
                    } else {
                        initTabSwitching();
                    }
                    
                    // Also initialize after a short delay as backup
                    setTimeout(initTabSwitching, 100);
                })();
                </script>
                
                <!-- Payment Method Toggle Function -->
                <script>
                    function togglePaymentFields() {
                        const paymentMethod = document.getElementById('payment_method').value;
                        const cardFields = document.getElementById('card-fields');
                        const bankFields = document.getElementById('bank-fields');
                        
                        // Hide all payment fields first
                        if (cardFields) cardFields.style.display = 'none';
                        if (bankFields) bankFields.style.display = 'none';
                        
                        // Show relevant fields based on selection
                        if (paymentMethod === 'credit_card' && cardFields) {
                            cardFields.style.display = 'block';
                        } else if (paymentMethod === 'bank_transfer' && bankFields) {
                            bankFields.style.display = 'block';
                        }
                    }
                    
                    // Initialize payment fields on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        togglePaymentFields();
                    });
                    
                    // Admin bank transfer settings update
                    async function updateBankTransferSetting(field, value) {
                        try {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]');
                            if (!csrfToken) {
                                showToast('CSRF token not found', 'error');
                                return;
                            }

                            const response = await fetch('/admin/content/bank-transfer', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    content: {
                                        [field]: value
                                    }
                                })
                            });
                            
                            const result = await response.json();
                            
                            if (result.success) {
                                showToast('Bank transfer setting updated successfully!', 'success');
                            } else {
                                console.error('Update failed:', result);
                                showToast(result.message || 'Error updating bank transfer setting', 'error');
                            }
                        } catch (error) {
                            console.error('Network error:', error);
                            showToast('Network error updating bank transfer setting', 'error');
                        }
                    }
                    
                    function showToast(message, type) {
                        const toast = document.getElementById('profile-toast');
                        const messageElement = toast.querySelector('.toast-message');
                        
                        // Set message and styling
                        messageElement.textContent = message;
                        toast.className = `fixed top-6 left-1/2 z-50 flex items-center gap-3 px-4 py-3 rounded-lg text-white shadow-lg transition-all duration-300`;
                        
                        if (type === 'success') {
                            toast.classList.add('bg-green-500');
                        } else {
                            toast.classList.add('bg-red-500');
                        }
                        
                        // Show toast
                        toast.style.display = 'flex';
                        toast.style.transform = 'translateX(-50%)';
                        toast.classList.remove('opacity-0');
                        
                        // Hide after 3 seconds
                        setTimeout(() => {
                            toast.classList.add('opacity-0');
                            setTimeout(() => {
                                toast.style.display = 'none';
                                toast.classList.remove('bg-green-500', 'bg-red-500');
                            }, 300);
                        }, 3000);
                    }
                </script>
                
                <!-- Toast container for AJAX save notifications (top-center to avoid back-to-top overlap) -->
                <div id="profile-toast" class="hidden fixed top-6 left-1/2 z-50 items-center gap-3 px-4 py-3 rounded-lg text-white shadow-lg opacity-0" style="display:none; transform: translateX(-50%);">
                    <div class="toast-message">Saved</div>
                </div>
    <style>
    /* Tab content visibility rules */
    .tab-content {
        display: block;
    }
    .tab-content.hidden {
        display: none !important;
    }
    .tab-content.tab-visible {
        display: block !important;
    }
    
    /* Enhanced form focus states */
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    /* Smooth transitions for all interactive elements */
    button, input, .transition-all {
        transition: all 0.2s ease-in-out;
    }
    
    /* Comprehensive mobile optimizations for edit profile page */
    @media (max-width: 768px) {
        /* Page container mobile fixes */
        .max-w-7xl {
            max-width: 100% !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
            margin: 0 !important;
        }
        
        /* Profile card mobile optimization */
        .profile-card {
            border-radius: 1rem !important;
            margin-bottom: 1rem !important;
        }
        
        /* Header gradient mobile */
        .profile-header {
            height: 120px !important;
        }
        
        /* Back button mobile */
        .back-btn {
            position: static !important;
            margin: 1rem !important;
            width: auto !important;
        }
        
        /* Profile photo mobile */
        .profile-photo {
            width: 100px !important;
            height: 100px !important;
            margin-top: -50px !important;
        }
        
        /* Layout mobile - stack vertically */
        .lg\\:flex-row {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        
        .lg\\:items-end {
            align-items: flex-start !important;
        }
        
        /* Form grid mobile */
        .grid.md\\:grid-cols-2 {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
        
        /* Form inputs mobile */
        .form-input {
            font-size: 16px !important; /* Prevent zoom on iOS */
            padding: 0.875rem !important;
            border-radius: 0.75rem !important;
        }
        
        /* Textarea mobile */
        .form-textarea {
            min-height: 120px !important;
            font-size: 16px !important;
            padding: 0.875rem !important;
        }
        
        /* Button mobile sizing */
        .profile-btn {
            width: 100% !important;
            padding: 0.875rem 1.5rem !important;
            font-size: 1rem !important;
            min-height: 48px !important;
        }
        
        /* Photo upload mobile */
        .photo-upload {
            width: 100% !important;
            margin-bottom: 1rem !important;
        }
        
        .photo-btn {
            width: 100% !important;
            min-height: 44px !important;
            font-size: 0.875rem !important;
        }
        
        /* Modal mobile optimization */
        .photo-modal {
            width: 95vw !important;
            max-width: 95vw !important;
            margin: 2rem auto !important;
            max-height: 90vh !important;
            overflow-y: auto !important;
        }
        
        /* Modal content mobile */
        .modal-content {
            padding: 1rem !important;
        }
        
        /* Form sections mobile spacing */
        .form-section {
            margin-bottom: 1.5rem !important;
        }
        
        .section-title {
            font-size: 1.25rem !important;
            margin-bottom: 1rem !important;
        }
        
        /* Input groups mobile */
        .input-group {
            margin-bottom: 1rem !important;
        }
        
        /* Label mobile */
        .field-label {
            font-size: 0.875rem !important;
            margin-bottom: 0.5rem !important;
        }
        
        /* Error messages mobile */
        .error-message {
            font-size: 0.875rem !important;
            margin-top: 0.25rem !important;
        }
        
        /* Success messages mobile */
        .success-message {
            font-size: 0.875rem !important;
            padding: 0.75rem !important;
            border-radius: 0.75rem !important;
        }
        
        /* Profile info mobile layout */
        .profile-info {
            margin-top: 1rem !important;
            text-align: left !important;
        }
        
        .profile-name {
            font-size: 1.5rem !important;
            line-height: 1.2 !important;
        }
        
        .profile-email {
            font-size: 1rem !important;
        }
        
        /* Card padding mobile */
        .card-content {
            padding: 1rem !important;
        }
        
        /* File upload mobile */
        .file-upload {
            padding: 1rem !important;
            border-radius: 0.75rem !important;
        }
        
        /* Cropper mobile optimization */
        .cropper-container {
            max-width: 100% !important;
            max-height: 300px !important;
        }
        
        /* Form validation mobile */
        .validation-error {
            font-size: 0.875rem !important;
        }
        
        /* Loading states mobile */
        .loading-spinner {
            margin: 1rem auto !important;
        }
        
        /* Profile stats mobile (if any) */
        .profile-stats {
            flex-direction: column !important;
            gap: 0.75rem !important;
        }
        
        .stat-item {
            text-align: center !important;
            padding: 0.75rem !important;
        }
    }
</style>
@endsection
