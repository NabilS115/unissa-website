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
                    <div class="relative group flex-shrink-0 z-10" id="profile-photo-group" x-data="{ modalOpen: false, modalType: '' }">
                        <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" 
                             alt="Profile Picture" 
                             class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-xl bg-white cursor-pointer" 
                             id="profile-photo-trigger">

                        <button type="button" id="profile-photo-icon" @click="modalOpen = true" class="absolute inset-0 flex items-center justify-center w-full h-full rounded-2xl focus:outline-none transition-all duration-200 group-hover:bg-opacity-30" style="z-index:2;"></button>

                        <!-- Professional Modal Overlay for Upload & Delete (together) -->
                        <template x-if="modalOpen">
                            <div class="fixed inset-0 z-[9999] flex items-center justify-center">
                                <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="modalOpen = false"></div>
                                <div class="relative bg-white rounded-2xl shadow-2xl px-6 py-8 w-full max-w-xs flex flex-col items-center z-10">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Profile Photo</h2>
                                    <p class="text-gray-500 mb-6 text-center">Update or remove your profile picture below.</p>
                                    <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" class="w-full flex flex-col items-center gap-4 mb-4">
                                        @csrf
                                        <label for="profile_photo" class="flex flex-col items-center gap-2 cursor-pointer group">
                                            <span class="flex items-center justify-center w-16 h-16 rounded-full bg-teal-50 group-hover:bg-teal-100 transition">
                                                <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                            </span>
                                            <span class="font-medium text-teal-700 group-hover:text-teal-900">Upload New Photo</span>
                                            <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                                        </label>
                                    </form>
                                    @if(Auth::user()->profile_photo_url)
                                    <div class="w-full flex items-center gap-2 my-2">
                                        <div class="flex-1 h-px bg-gray-200"></div>
                                        <span class="text-xs text-gray-400 font-medium">or</span>
                                        <div class="flex-1 h-px bg-gray-200"></div>
                                    </div>
                                    <form method="POST" action="{{ route('profile.photo.delete') }}" class="w-full flex flex-col items-center gap-4 mt-2">
                                        @csrf
                                        <span class="flex items-center justify-center w-16 h-16 rounded-full bg-red-50">
                                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </span>
                                        <span class="font-medium text-red-600">Delete Profile Photo</span>
                                        <button type="submit" class="w-full py-2 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition">Delete</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </template>
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
            <!-- Payment Method Details Card -->
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
                            <span class="block text-sm font-semibold text-[#0d9488] mb-2">Payment Method</span>
                            <div class="flex flex-col gap-3">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="credit_card" {{ old('payment_method', Auth::user()->payment_method) == 'credit_card' ? 'checked' : '' }} class="form-radio text-[#0d9488] focus:ring-[#0d9488]" onclick="togglePaymentFields()">
                                    <span class="ml-2 text-[#0d9488] font-semibold">Credit/Debit Card</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method', Auth::user()->payment_method) == 'bank_transfer' ? 'checked' : '' }} class="form-radio text-[#0d9488] focus:ring-[#0d9488]" onclick="togglePaymentFields()">
                                    <span class="ml-2 text-[#0d9488] font-semibold">Bank Transfer</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="other" {{ old('payment_method', Auth::user()->payment_method) == 'other' ? 'checked' : '' }} class="form-radio text-[#0d9488] focus:ring-[#0d9488]" onclick="togglePaymentFields()">
                                    <span class="ml-2 text-[#0d9488] font-semibold">Other</span>
                                </label>
                            </div>
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
                            <div id="other-fields" style="display:none;">
                                <label for="payment_details" class="block text-sm font-semibold text-[#0d9488] mb-2">Other Payment Details</label>
                                <input name="payment_details" id="payment_details" type="text" autocomplete="off"
                                    value="{{ old('payment_details', Auth::user()->payment_details) }}"
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="e.g. Account number, reference, etc." />
                            </div>
                        </div>
                    </div>
                    <script>
                        function togglePaymentFields() {
                            var method = document.querySelector('input[name="payment_method"]:checked').value;
                            document.getElementById('card-fields').style.display = (method === 'credit_card') ? 'block' : 'none';
                            document.getElementById('bank-fields').style.display = (method === 'bank_transfer') ? 'block' : 'none';
                            document.getElementById('other-fields').style.display = (method === 'other') ? 'block' : 'none';
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
