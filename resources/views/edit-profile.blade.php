@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <!-- Removed top Back to Profile button -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Edit Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500 relative">
                <a href="{{ route('profile') }}" class="absolute top-4 right-4 inline-flex items-center px-4 py-2 bg-white border border-teal-200 text-teal-700 font-semibold rounded-xl shadow hover:bg-teal-50 hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-400 transition-all duration-200 z-10">
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
                                <img id="profile-photo" src="{{ Auth::user()->profile_photo_url }}" alt="Profile Picture" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white cursor-pointer">
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
                <button class="tab-btn px-4 py-2 text-teal-700 font-semibold border-b-2 border-transparent focus:outline-none transition-all duration-200" data-tab="payment">Payment</button>
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
                               class="w-full px-4 py-3 border {{ $errors->has('department') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" placeholder="e.g. Faculty of Usuluddin" />
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
                                <label for="bank_name" class="block text-sm font-semibold text-[#0d9488] mb-2">Bank Name</label>
                                <select name="bank_name" id="bank_name" class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] bg-white">
                                    <option value="">Select a bank</option>
                                    <option value="BIBD" {{ old('bank_name', Auth::user()->bank_name) == 'BIBD' ? 'selected' : '' }}>BIBD</option>
                                    <option value="Baiduri" {{ old('bank_name', Auth::user()->bank_name) == 'Baiduri' ? 'selected' : '' }}>Baiduri</option>
                                    <option value="Standard Chartered" {{ old('bank_name', Auth::user()->bank_name) == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                    <option value="TAIB" {{ old('bank_name', Auth::user()->bank_name) == 'TAIB' ? 'selected' : '' }}>TAIB</option>
                                </select>
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
                    </div>

                    <!-- Profile JS bootstrap injected later to avoid load-order issues -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 w-full">
                        <!-- ...existing code... -->
                    </div>
                    <!-- Move button to its own row at the bottom of the card, checked text removed -->
                    <div class="flex justify-end w-full pt-6">
                        <button type="submit" id="save-payment-btn" class="px-6 py-3 bg-[#0d9488] hover:bg-[#007070] text-white font-semibold rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2 transition-all duration-200" autocomplete="off">
                            <span class="whitespace-nowrap">Save Payment Method</span>
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

                <!-- Photo modal (used by /js/profile.js) -->
                <div id="photo-modal" class="hidden fixed inset-0 z-50 flex items-start md:items-center justify-center bg-black/60 px-4 py-8 overflow-auto" role="dialog" aria-modal="true" aria-labelledby="photo-modal-title">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-auto overflow-visible border border-teal-100 animate-fade-in max-h-[calc(100vh-4rem)]">
                        <div class="flex items-start justify-between p-6 border-b border-teal-100">
                            <h3 id="photo-modal-title" class="text-xl font-semibold text-teal-800">Update profile photo</h3>
                            <button id="photo-modal-close" type="button" class="text-teal-600 hover:text-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-500 rounded-md" aria-label="Close dialog">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-6 md:p-8">
                            <div class="md:flex md:items-start md:gap-8">
                                <div class="md:w-1/2 flex flex-col items-center">
                                    <div class="w-48 h-48 rounded-2xl overflow-hidden border border-teal-100 shadow-lg flex items-center justify-center bg-white">
                                        <img id="modal-photo-preview" src="{{ Auth::user()->profile_photo_url }}" alt="Preview" class="w-full h-full object-cover" />
                                    </div>
                                    <p class="mt-3 text-sm text-teal-700 text-center">Preview — how your photo appears across the site.</p>
                                </div>

                                <div class="md:flex-1 mt-6 md:mt-0">
                                    <p class="text-sm text-teal-700 mb-4">Select an image file. You can crop and adjust before saving. Recommended size: square, at least 400×400px.</p>

                                    <div class="flex flex-wrap gap-3 items-center">
                                        <button id="upload-btn" type="button" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 font-medium shadow">Upload</button>
                                        <button id="change-btn" type="button" class="px-4 py-2 bg-white border border-teal-100 text-teal-700 rounded-xl hidden">Change</button>
                                        <button id="delete-btn" type="button" class="px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl hidden">Delete</button>
                                        <input id="photo-file-input" type="file" accept="image/*" class="hidden" />
                                    </div>

                                    <div id="photo-modal-spinner" class="hidden mt-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full border-4 border-teal-200 border-t-teal-600 animate-spin"></div>
                                            <div class="text-sm text-teal-700 spinner-message">Uploading…</div>
                                        </div>
                                    </div>

                                    <!-- cropper area (initially hidden) -->
                                    <div id="cropper-area" class="hidden mt-6">
                                        <div class="mx-auto cropper-wrapper relative w-full max-w-[420px] min-h-[200px] overflow-visible rounded-xl border border-teal-100 bg-white shadow-sm flex items-center justify-center">
                                            <img id="cropper-image" src="" alt="Crop" class="max-w-full max-h-[50vh] object-contain" />
                                        </div>
                                        <div class="mt-4 flex items-center gap-3">
                                            <button id="crop-upload-btn" type="button" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-medium">Apply crop & upload</button>
                                            <button id="crop-reset-btn" type="button" class="px-4 py-2 bg-white border border-teal-100 rounded-xl">Reset</button>
                                            <button id="crop-cancel-btn" type="button" class="px-4 py-2 bg-white border border-gray-200 rounded-xl">Cancel</button>
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
                <script src="/js/profile.js"></script>
                <!-- Toast container for AJAX save notifications (top-center to avoid back-to-top overlap) -->
                <div id="profile-toast" class="hidden fixed top-6 left-1/2 z-50 items-center gap-3 px-4 py-3 rounded-lg text-white shadow-lg opacity-0" style="display:none; transform: translateX(-50%);">
                    <div class="toast-message">Saved</div>
                </div>
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
