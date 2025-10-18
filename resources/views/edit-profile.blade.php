@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <a href="{{ route('profile') }}" class="inline-flex items-center px-4 py-2 bg-white border border-teal-200 text-teal-700 font-semibold rounded-xl shadow hover:bg-teal-50 hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-400 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Profile
        </a>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Edit Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500"></div>
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <div class="relative">
                            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                        </div>
                        <div class="lg:mb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name ?? 'Dr. Ahmad bin Ali' }}</h1>
                            </div>
                            <div class="flex items-center gap-4 text-gray-600 mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                    </svg>
                                    <span class="font-medium">{{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</span>
                                </div>
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
            <!-- Profile Details Card (Profile Tab) -->
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
                        <div class="md:col-span-2">
                            <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
                            <input name="phone" id="phone" type="text" autocomplete="tel" 
                                   value="{{ old('phone', Auth::user()->phone) }}"
                                   class="w-full px-4 py-3 border {{ $errors->has('phone') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" placeholder="e.g. +673 1234567" />
                            @if ($errors->has('phone'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('phone') }}</p>
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
                        <style>
                        .main-theme-btn {
                            background-color: #0d9488 !important;
                            color: #fff !important;
                            border-radius: 0.75rem !important;
                            font-weight: 600 !important;
                            box-shadow: 0 2px 8px 0 rgba(13,148,136,0.15);
                            padding: 0.75rem 1.5rem;
                            display: inline-flex;
                            align-items: center;
                            gap: 0.5rem;
                            border: none;
                            transition: background 0.2s, box-shadow 0.2s;
                        }
                        .main-theme-btn:hover, .main-theme-btn:focus {
                            background-color: #007070 !important;
                            box-shadow: 0 4px 16px 0 rgba(13,148,136,0.25);
                        }
                        </style>
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
                <form method="POST" action="{{ route('profile.payment') }}" class="space-y-6">
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
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Name on card" />
                                <label for="card_number" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Card Number</label>
                                <input name="card_number" id="card_number" type="text" autocomplete="cc-number"
                                    value="{{ old('card_number', Auth::user()->card_number) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Card number" />
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="card_expiry" class="block text-sm font-semibold text-[#0d9488] mb-2">Expiry (MM/YYYY)</label>
                                        <input name="card_expiry" id="card_expiry" type="text" autocomplete="cc-exp"
                                            value="{{ old('card_expiry', Auth::user()->card_expiry) }}"
                                            class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="MM/YYYY" />
                                    </div>
                                    <div>
                                        <label for="card_ccv" class="block text-sm font-semibold text-[#0d9488] mb-2">CCV</label>
                                        <input name="card_ccv" id="card_ccv" type="text" autocomplete="cc-csc"
                                            value="{{ old('card_ccv', Auth::user()->card_ccv) }}"
                                            class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="CCV" />
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
                                    <option value="Bank Islam Brunei Darussalam" {{ old('bank_name', Auth::user()->bank_name) == 'Bank Islam Brunei Darussalam' ? 'selected' : '' }}>Bank Islam Brunei Darussalam</option>
                                    <option value="Maybank" {{ old('bank_name', Auth::user()->bank_name) == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                                    <option value="RHB" {{ old('bank_name', Auth::user()->bank_name) == 'RHB' ? 'selected' : '' }}>RHB</option>
                                </select>
                                <label for="bank_account" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Account Number</label>
                                <input name="bank_account" id="bank_account" type="text" autocomplete="off"
                                    value="{{ old('bank_account', Auth::user()->bank_account) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Account number" />
                                <label for="bank_reference" class="block text-sm font-semibold text-[#0d9488] mb-2 mt-4">Reference</label>
                                <input name="bank_reference" id="bank_reference" type="text" autocomplete="off"
                                    value="{{ old('bank_reference', Auth::user()->bank_reference) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Reference (optional)" />
                            </div>
                        </div>
                    </div>
                    <script>
                        function togglePaymentFields() {
                            var method = document.getElementById('payment_method').value;
                            document.getElementById('card-fields').style.display = (method === 'credit_card') ? 'block' : 'none';
                            document.getElementById('bank-fields').style.display = (method === 'bank_transfer') ? 'block' : 'none';
                        }
                        // On page load
                        document.addEventListener('DOMContentLoaded', function() {
                            togglePaymentFields();
                        });
                    </script>
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        @if (session('payment-updated'))
                            <div class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Payment method updated!
                            </div>
                        @endif
                        <button type="submit" class="px-6 py-3 bg-[#0d9488] hover:bg-[#007070] text-white font-semibold rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2 transition-all duration-200">
                            <span class="whitespace-nowrap">Save Payment Method</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div id="tab-content-payment" class="tab-content hidden">
            <!-- Payment Method Details Card (Payment Tab) -->
            <div class="bg-[#f8fafc] border border-[#0d9488] rounded-2xl shadow-lg p-8">
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

                        <div class="md:col-span-2">
                            <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
                            <input name="phone" id="phone" type="text" autocomplete="tel" 
                                   value="{{ old('phone', Auth::user()->phone) }}"
                                   class="w-full px-4 py-3 border {{ $errors->has('phone') ? 'border-red-300 focus:ring-red-500' : 'border-gray-300 focus:ring-teal-500' }} rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200" placeholder="e.g. +673 1234567" />
                            @if ($errors->has('phone'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('phone') }}</p>
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
                        <style>
                        .main-theme-btn {
                            background-color: #0d9488 !important;
                            color: #fff !important;
                            border-radius: 0.75rem !important;
                            font-weight: 600 !important;
                            box-shadow: 0 2px 8px 0 rgba(13,148,136,0.15);
                            padding: 0.75rem 1.5rem;
                            display: inline-flex;
                            align-items: center;
                            gap: 0.5rem;
                            border: none;
                            transition: background 0.2s, box-shadow 0.2s;
                        }
                        .main-theme-btn:hover, .main-theme-btn:focus {
                            background-color: #007070 !important;
                            box-shadow: 0 4px 16px 0 rgba(13,148,136,0.25);
                        }
                        </style>
                    </div>
                </form>
            </div>

        </div>
        <div id="tab-content-password" class="tab-content hidden">
            <!-- Password Update Card (Password Tab) -->
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
        <script>
        // Tab switching logic
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            function showTab(tab) {
                tabContents.forEach(c => c.classList.add('hidden'));
                document.getElementById('tab-content-' + tab).classList.remove('hidden');
                tabBtns.forEach(b => b.classList.remove('border-teal-500', 'text-teal-900'));
                document.querySelector('.tab-btn[data-tab="' + tab + '"]').classList.add('border-teal-500', 'text-teal-900');
            }
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    showTab(this.dataset.tab);
                });
            });
            // Default to profile tab
            showTab('profile');
        });
        </script>
    </div>
</div>

<!-- Success Toast Messages -->
@if (session('profile-updated') || session('status') === 'profile-updated')
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-teal-700 to-emerald-700 bg-clip-text text-transparent">Payment Method Details</h2>
        </div>
        <form method="POST" action="{{ route('profile.payment') }}" class="space-y-6">
            @csrf
            @method('put')
            <div class="space-y-4">
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-teal-50 hover:border-teal-300 transition-all duration-200">
                    <input type="radio" name="payment_method" value="cash" 
                        class="text-teal-600 focus:ring-teal-500 w-5 h-5" 
                        {{ old('payment_method', Auth::user()->payment_method ?? 'cash') === 'cash' ? 'checked' : '' }}>
                    <div class="ml-4">
                        <div class="font-semibold text-gray-800">Cash on Pickup</div>
                        <div class="text-sm text-gray-600">Pay when you collect your order</div>
                    </div>
                </label>
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-teal-50 hover:border-teal-300 transition-all duration-200">
                    <input type="radio" name="payment_method" value="online" 
                        class="text-teal-600 focus:ring-teal-500 w-5 h-5" 
                        {{ old('payment_method', Auth::user()->payment_method) === 'online' ? 'checked' : '' }}>
                    <div class="ml-4">
                        <div class="font-semibold text-gray-800">Credit/Debit Card</div>
                        <div class="text-sm text-gray-600">Pay securely online now</div>
                    </div>
                </label>
            </div>
            <div class="mt-6">
                <label for="payment_details" class="block text-sm font-semibold text-teal-700 mb-2">Payment Details</label>
                <input name="payment_details" id="payment_details" type="text" autocomplete="off"
                    value="{{ old('payment_details', Auth::user()->payment_details) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 hover:border-teal-300 text-teal-700 placeholder-teal-400 bg-white" placeholder="e.g. Card number, bank info, etc." />
            </div>
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                @if (session('payment-updated'))
                    <div class="flex items-center gap-2 text-green-600 font-medium">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Payment method updated!
                    </div>
                @endif
                <button type="submit" class="px-6 py-3 bg-[#0d9488] hover:bg-[#007070] text-white font-semibold rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2 transition-all duration-200">
                    <span class="whitespace-nowrap">Save Payment Method</span>
                </button>
            </div>
        </form>
    </div>
@endif

<script>
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
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268-2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
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
