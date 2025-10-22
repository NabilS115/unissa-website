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
                            <div class="absolute inset-0 bg-black/40 rounded-2xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 cursor-pointer select-none"
                                 onclick="openPhotoModal()">
                                <svg class="w-12 h-12 text-white/90" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <path d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <circle cx="12" cy="14" r="3.5" stroke="currentColor" stroke-width="1.5" fill="none" />
                                </svg>
                            </div>
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
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
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
                                </svg>
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
                                    class="w-full px-4 py-3 border border-[#0d9488] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:border-transparent transition-all duration-200 text-[#0d9488] placeholder-[#007070] bg-white" placeholder="Account number" maxlength="16" pattern="^[0-9]{16}$" oninput="validateBankAccount(this)" />
                                <p id="bank-account-validation" class="mt-2 text-sm"></p>
<script>
function validateBankAccount(input) {
    const value = input.value;
    const msg = document.getElementById('bank-account-validation');
    // Only allow digits, no spaces or other characters
    if (!/^[0-9]{16}$/.test(value)) {
        msg.textContent = 'Account number must be exactly 16 digits, no spaces.';
        msg.className = 'mt-2 text-sm text-red-600';
        input.classList.remove('border-[#0d9488]','focus:ring-[#0d9488]');
        input.classList.add('border-red-500','focus:ring-red-500');
    } else {
        msg.textContent = 'Valid account number.';
        msg.className = 'mt-2 text-sm text-green-600';
        input.classList.remove('border-red-500','focus:ring-red-500');
        input.classList.add('border-[#0d9488]','focus:ring-[#0d9488]');
    }
    // Optionally, prevent non-digit input
    input.value = value.replace(/[^0-9]/g, '');
}
</script>

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
                    <script>
function validateCardholderName() {
    const name = document.getElementById('cardholder_name').value.trim();
    const msg = document.getElementById('cardholder-name-validation');
    if (name.length < 2) {
        msg.textContent = 'Name must be at least 2 characters.';
        msg.className = 'mt-2 text-sm text-red-600';
    } else {
        msg.textContent = 'Looks good.';
        msg.className = 'mt-2 text-sm text-green-600';
    }
}
function validateCardNumber() {
    const number = document.getElementById('card_number').value.replace(/\s/g, '');
    const msg = document.getElementById('card-number-validation');
    const re = /^\d{16}$/;
    if (!re.test(number)) {
        msg.textContent = 'Card number must be 16 digits.';
        msg.className = 'mt-2 text-sm text-red-600';
    } else {
        msg.textContent = 'Valid card number.';
        msg.className = 'mt-2 text-sm text-green-600';
    }
}
function validateCardExpiry() {
    const expiry = document.getElementById('card_expiry').value.trim();
    const msg = document.getElementById('card-expiry-validation');
    const re = /^(0[1-9]|1[0-2])\/(\d{2})$/;
    if (!re.test(expiry)) {
        msg.textContent = 'Expiry must be MM/YY.';
        msg.className = 'mt-2 text-sm text-red-600';
        return;
    }
    // Check for expired date
    const [mm, yy] = expiry.split('/');
    const now = new Date();
    // Convert YY to 20YY (assume 2000-2099)
    const yyyy = parseInt(yy) + 2000;
    const expDate = new Date(yyyy, parseInt(mm) - 1, 1);
    if (expDate < new Date(now.getFullYear(), now.getMonth(), 1)) {
        msg.textContent = 'Card is expired.';
        msg.className = 'mt-2 text-sm text-red-600';
    } else {
        msg.textContent = 'Valid expiry date.';
        msg.className = 'mt-2 text-sm text-green-600';
    }
}
function validateCardCCV() {
    const ccv = document.getElementById('card_ccv').value.trim();
    const msg = document.getElementById('card-ccv-validation');
    const re = /^\d{3,4}$/;
    if (!re.test(ccv)) {
        msg.textContent = 'CCV must be 3 or 4 digits.';
        msg.className = 'mt-2 text-sm text-red-600';
    } else {
        msg.textContent = 'Valid CCV.';
        msg.className = 'mt-2 text-sm text-green-600';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    validateCardholderName();
    validateCardNumber();
    validateCardExpiry();
    validateCardCCV();
});
                    </script>
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 w-full">
                        <!-- ...existing code... -->
                    </div>
                    <!-- Move button to its own row at the bottom of the card, checked text removed -->
                    <div class="flex justify-end w-full pt-6">
                        <button type="submit" id="save-payment-btn" class="px-6 py-3 bg-[#0d9488] hover:bg-[#007070] text-white font-semibold rounded-xl shadow-lg focus:outline-none focus:ring-2 focus:ring-[#0d9488] focus:ring-offset-2 transition-all duration-200" autocomplete="off">
                            <span class="whitespace-nowrap">Save Payment Method</span>
                        </button>
                    </div>
                <!-- Toast Notification -->
<!-- Toast Notification (fixed, high z-index, not covering button) -->
<div id="profile-toast" style="position:fixed; bottom:2rem; left:2rem; z-index:9999; display:none; align-items:center; min-width:260px; max-width:90vw; padding:1rem 1.5rem; border-radius:1rem; box-shadow:0 4px 24px rgba(0,0,0,0.12); pointer-events:auto;" class="gap-2 text-white font-semibold">
    <span class="toast-message font-medium text-base"></span>
    <button id="profile-toast-close" class="ml-auto pl-4 focus:outline-none text-white/80 hover:text-white text-2xl leading-none bg-transparent" style="background:none; border:none;">&times;</button>
</div>
<script>
// AJAX Payment Method Save
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-method-form');
    const btn = document.getElementById('save-payment-btn');
    if (form && btn) {
        btn.disabled = false;
        btn.style.pointerEvents = 'auto';
        // Ensure button is always enabled after toast
        document.addEventListener('profile-toast-hide', function() {
            btn.disabled = false;
            btn.style.pointerEvents = 'auto';
        });
        btn.addEventListener('click', function() {
            // Show notification immediately on click
            showProfileToast('Saving payment method...', false);
        });
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Validate required fields before AJAX
            var bank = document.getElementById('bank_name');
            var account = document.getElementById('bank_account');
            if (!bank.value || !account.value) {
                showProfileToast('Please fill in both bank and account number.', true);
                return;
            }
            // Do not disable or block the button
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            console.log('Submitting payment method form via AJAX...');
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value
                },
                body: formData
            })
            .then(async response => {
                console.log('AJAX response status:', response.status);
                let data = null;
                try { data = await response.json(); } catch (err) { console.log('Response not JSON:', err); }
                console.log('AJAX response data:', data);
                if (response.ok) {
                    showProfileToast('Payment method updated!', false);
                } else {
                    let msg = 'Failed to update payment method.';
                    if (data && data.message) msg = data.message;
                    showProfileToast(msg, true);
                }
            })
            .catch((err) => {
                console.log('AJAX error:', err);
                showProfileToast('Failed to update payment method.', true);
            });
        });
    }
    // Toast logic
    window.showProfileToast = function(message, isError = false) {
        let toast = document.getElementById('profile-toast');
        if (!toast) return;
        toast.querySelector('.toast-message').textContent = message;
        toast.classList.remove('bg-green-500','bg-red-500','opacity-0','hidden');
        toast.classList.add(isError ? 'bg-red-500' : 'bg-green-500');
        toast.style.display = 'flex';
        toast.style.opacity = '1';
        toast.style.pointerEvents = 'none';
        console.log('Toast notification shown:', message);
        setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.display = 'none';
                // Custom event to re-enable button after toast hides
                document.dispatchEvent(new Event('profile-toast-hide'));
            }, 300);
        }, 2500);
    };
    const closeBtn = document.getElementById('profile-toast-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            const toast = document.getElementById('profile-toast');
            toast.classList.add('opacity-0');
            setTimeout(() => toast.style.display = 'none', 300);
        });
    }
});
</script>
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

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-6" id="change-password-form">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 gap-6">
                        <div class="relative">
                            <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">Current Password</label>
                            <div class="relative">
                    <input name="current_password" id="current_password" type="password" required autocomplete="current-password"
                                       class="{{ 'w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 pr-12' . ($errors->updatePassword->has('current_password') ? ' border-red-500 focus:ring-red-500' : ' border-gray-300 focus:ring-teal-500') }}" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('current_password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                    </svg>
                                </button>
                            </div>
                            <span id="current_password_error" class="mt-2 text-sm text-red-600 hidden">Current password is required.</span>
                            @if ($errors->updatePassword->has('current_password'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div class="relative">
                            <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">New Password</label>
                            <div class="relative">
                    <input name="password" id="password" type="password" required autocomplete="new-password"
                                       class="{{ 'w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 pr-12' . ($errors->updatePassword->has('password') ? ' border-red-500 focus:ring-red-500' : ' border-gray-300 focus:ring-teal-500') }}" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                    </svg>
                                </button>
                            </div>
                            <span id="password_error" class="mt-2 text-sm text-red-600 hidden">Password must be at least 8 characters.</span>
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
                                       class="{{ 'w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 pr-12' . ($errors->updatePassword->has('password_confirmation') ? ' border-red-500 focus:ring-red-500' : ' border-gray-300 focus:ring-teal-500') }}" />
                                <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none" onclick="togglePassword('password_confirmation', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" />
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />
                                    </svg>
                                </button>
                            </div>
                            <span id="password_confirmation_error" class="mt-2 text-sm text-red-600 hidden">Passwords do not match.</span>
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
                        @endif
                        @if (session('password-updated') || session('status') === 'password-updated')
                            <div class="flex items-center gap-2 text-green-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Password updated successfully!
                            </div>
                        @endif
                        <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105" id="update-password-btn">
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
        <!-- Profile Photo Modal/Overlay (moved out of header to avoid layout shifts) -->
        <div id="photo-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-hidden="true" tabindex="-1" style="background: rgba(0,0,0,0.45); backdrop-filter: blur(4px);">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl md:max-w-2xl lg:max-w-3xl flex flex-col items-stretch border border-gray-100 overflow-hidden relative" role="document" style="max-height:90vh;">
                <!-- Spinner overlay -->
                <div id="photo-modal-spinner" class="absolute inset-0 bg-black/30 items-center justify-center hidden">
                    <div class="bg-white/90 rounded-full p-4 flex items-center gap-3">
                        <svg class="animate-spin h-6 w-6 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span class="spinner-message text-sm font-medium text-gray-700">Uploading...</span>
                    </div>
                </div>
                <!-- Modal Header -->
                <div class="w-full flex items-center justify-between px-6 py-4 bg-gradient-to-r from-teal-50 to-emerald-50 border-b border-teal-100">
                    <span class="font-semibold text-lg text-gray-800">Profile Photo</span>
                    <button onclick="closePhotoModal()" aria-label="Close profile photo dialog" class="text-gray-500 hover:text-gray-800 text-2xl focus:outline-none">&times;</button>
                </div>
                <!-- Modal Content -->
                <div class="flex flex-col items-stretch w-full px-4 py-4">
                    <div class="w-full px-4 py-4" style="overflow:auto; max-height:calc(90vh - 140px);">
                        <div class="flex flex-col items-center mb-4">
                            @if(Auth::check())
                                <img id="modal-photo-preview" src="{{ Auth::user()->profile_photo_url ? Auth::user()->profile_photo_url : asset('images/default-profile.svg') }}" alt="Profile Picture Preview" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow bg-gray-100">
                            @endif
                        </div>
                        <div class="w-full flex flex-col gap-3">
                            {{-- Hidden file input used for both upload and change; onchange opens cropper --}} 
                            <form id="photo-file-form" enctype="multipart/form-data" style="display:none;">
                                <input type="file" name="profile_photo_file" id="photo-file-input" accept="image/*" onchange="openCropperFromFile(event)">
                            </form>
                            <div class="grid grid-cols-1 gap-3">
                                {{-- Render all buttons but control visibility via classes so JS can toggle without a full refresh --}}
                                <button type="button" id="upload-btn" class="w-full px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-semibold shadow transition {{ $hasCustomPhoto ? 'hidden' : '' }}">Upload Image</button>
                                <button type="button" id="change-btn" class="w-full px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-semibold shadow transition {{ $hasCustomPhoto ? '' : 'hidden' }}">Change Image</button>
                                <button type="button" id="delete-btn" class="w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-2xl font-semibold shadow transition {{ $hasCustomPhoto ? '' : 'hidden' }}">Delete Image</button>
                            </div>

                            {{-- Cropper area (hidden until a file is selected) --}}
                            <div id="cropper-area" class="w-full mt-4 hidden">
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                                    <div class="cropper-container p-3" style="height:360px; max-height:60vh; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                        <img id="cropper-image" class="max-w-full max-h-full block mx-auto" src="" alt="Crop preview" style="width:auto; height:100%; object-fit:cover; border-radius:8px;" />
                                    </div>
                                    <div class="flex items-center justify-between gap-3 p-4 border-t border-gray-100">
                                        <div class="flex gap-3">
                                            <button type="button" id="crop-reset-btn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">Reset</button>
                                            <button type="button" id="crop-cancel-btn" class="px-4 py-2 bg-white hover:bg-gray-50 border border-gray-200 rounded-lg text-sm">Cancel</button>
                                        </div>
                                        <button type="button" id="crop-upload-btn" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-semibold">Crop & Save</button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 text-center">Use the handles to crop. Recommended aspect ratio: 1:1.</p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
    <script>
        function openPhotoModal() {
            const modal = document.getElementById('photo-modal');
            if (!modal) return;
            // show modal and set ARIA
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            // lock background scroll
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
            // focus first focusable element inside modal
            setTimeout(() => {
                const closeBtn = modal.querySelector('button[onclick="closePhotoModal()"]');
                if (closeBtn) closeBtn.focus();
            }, 50);
            // Ensure buttons reflect current preview (so delete appears immediately after upload)
            try {
                const preview = document.getElementById('modal-photo-preview');
                const uploadBtn = modal.querySelector('#upload-btn');
                const changeBtn = modal.querySelector('#change-btn');
                const deleteBtn = modal.querySelector('#delete-btn');
                if (preview && preview.src) {
                    const src = preview.src;
                    const isDefault = src.includes('default-profile.svg') || src.startsWith('data:image/svg+xml');
                    if (isDefault) {
                        if (uploadBtn) uploadBtn.classList.remove('hidden');
                        if (changeBtn) changeBtn.classList.add('hidden');
                        if (deleteBtn) deleteBtn.classList.add('hidden');
                    } else {
                        if (uploadBtn) uploadBtn.classList.add('hidden');
                        if (changeBtn) changeBtn.classList.remove('hidden');
                        if (deleteBtn) deleteBtn.classList.remove('hidden');
                    }
                }
            } catch (e) {
                // ignore
            }
            // attach ESC listener and backdrop click
            document.addEventListener('keydown', photoModalKeyHandler);
            modal.addEventListener('click', photoModalBackdropHandler);
        }
        function closePhotoModal() {
            const modal = document.getElementById('photo-modal');
            if (!modal) return;
            // hide modal and reset ARIA
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            // restore scroll
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            // remove listeners
            document.removeEventListener('keydown', photoModalKeyHandler);
            modal.removeEventListener('click', photoModalBackdropHandler);
        }

        function photoModalBackdropHandler(e) {
            // close when clicking on backdrop (the modal wrapper) only
            const modal = document.getElementById('photo-modal');
            if (!modal) return;
            if (e.target === modal) {
                closePhotoModal();
            }
        }

        function photoModalKeyHandler(e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                closePhotoModal();
            }
        }

        function showPhotoModalSpinner(message = null) {
            const s = document.getElementById('photo-modal-spinner');
            if (s) {
                // support optional message; only override if provided
                const msgEl = s.querySelector('.spinner-message');
                if (msgEl) {
                    if (message !== null) msgEl.textContent = message;
                    else if (!msgEl.textContent || msgEl.textContent.trim() === '') msgEl.textContent = 'Uploading...';
                }
                s.classList.remove('hidden'); s.classList.add('flex');
            }
        }

        function hidePhotoModalSpinner() {
            const s = document.getElementById('photo-modal-spinner');
            if (s) { s.classList.add('hidden'); s.classList.remove('flex'); }
        }
        function handlePhotoUpload(event) {
            const fileInput = event.target;
            const file = fileInput.files && fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('modal-photo-preview');
                    if (preview) preview.src = e.target.result;
                    const overlayPhoto = document.getElementById('profile-photo');
                    if (overlayPhoto) overlayPhoto.src = e.target.result;
                    // Update header profile image if present
                    const headerProfileImg = document.querySelector('#profileMenuButton img');
                    if (headerProfileImg) headerProfileImg.src = e.target.result;
                };
                reader.readAsDataURL(file);

                // AJAX upload with spinner
                let formData = new FormData();
                formData.append('profile_photo', file);
                showPhotoModalSpinner();
                fetch("{{ route('profile.photo') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(async response => {
                    hidePhotoModalSpinner();
                    if (response.ok) {
                        closePhotoModal();
                        try {
                            const data = await response.json();
                            const url = data.url || (data.data && data.data.url) || null;
                            if (url) {
                                const overlayPhoto = document.getElementById('profile-photo');
                                const preview = document.getElementById('modal-photo-preview');
                                const headerImg = document.querySelector('#profileMenuButton img');
                                if (overlayPhoto) overlayPhoto.src = url;
                                if (preview) preview.src = url;
                                if (headerImg) headerImg.src = url;
                                // Ensure buttons update: show change/delete, hide upload
                                const uploadBtn = document.getElementById('upload-btn');
                                const changeBtn = document.getElementById('change-btn');
                                const deleteBtn = document.getElementById('delete-btn');
                                if (uploadBtn) uploadBtn.classList.add('hidden');
                                if (changeBtn) changeBtn.classList.remove('hidden');
                                if (deleteBtn) deleteBtn.classList.remove('hidden');
                            }
                        } catch (err) {
                            // ignore JSON parse errors
                        }
                    } else {
                        let msg = 'Failed to upload image.';
                        try {
                            const err = await response.json();
                            if (err && err.message) msg = err.message;
                            else if (err && err.errors) msg = Object.values(err.errors).flat().join(' ');
                        } catch (e) {}
                        alert(msg);
                    }
                })
                .catch(error => {
                    hidePhotoModalSpinner();
                    alert('Failed to upload image. Please check your connection and try again.');
                    console.error('Upload error', error);
                });
            }
        }
        function deleteProfilePhoto() {
            if (!confirm('Are you sure you want to delete your profile photo?')) return;
            // show deleting message on spinner (pass message so it's not overwritten)
            showPhotoModalSpinner('Deleting...');
            fetch("{{ route('profile.photo.delete') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                hidePhotoModalSpinner();
                var defaultPhoto = "{{ Auth::user()->getProfilePhotoUrlAttribute(null) }}";
                var modalPreview = document.getElementById('modal-photo-preview');
                if (modalPreview) modalPreview.src = defaultPhoto;
                var overlayPhoto = document.getElementById('profile-photo');
                if (overlayPhoto) overlayPhoto.src = defaultPhoto;
                const headerProfileImg = document.querySelector('#profileMenuButton img');
                if (headerProfileImg) headerProfileImg.src = defaultPhoto;
                document.querySelectorAll('img[data-profile-photo], img.profile-photo, img[src*="profile-photos/"]').forEach(function(img) {
                    img.src = defaultPhoto;
                });
                closePhotoModal();
            })
            .catch(error => {
                hidePhotoModalSpinner();
                var defaultPhoto = "{{ asset('images/default-profile.svg') }}";
                var modalPreview = document.getElementById('modal-photo-preview');
                if (modalPreview) modalPreview.src = defaultPhoto;
                var overlayPhoto = document.getElementById('profile-photo');
                if (overlayPhoto) overlayPhoto.src = defaultPhoto;
                const headerProfileImg = document.querySelector('#profileMenuButton img');
                if (headerProfileImg) headerProfileImg.src = defaultPhoto;
                document.querySelectorAll('img[data-profile-photo], img.profile-photo, img[src*="profile-photos/"]').forEach(function(img) {
                    img.src = defaultPhoto;
                });
                closePhotoModal();
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            var uploadBtn = document.getElementById('upload-btn');
            if (uploadBtn) {
                uploadBtn.addEventListener('click', function() {
                    document.getElementById('photo-file-input').click();
                });
            }
            var changeBtn = document.getElementById('change-btn');
            if (changeBtn) {
                changeBtn.addEventListener('click', function() {
                    document.getElementById('photo-file-input').click();
                });
            }
            var deleteBtn = document.getElementById('delete-btn');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    deleteProfilePhoto();
                });
            }
            // Cropper bindings
            var cropCancel = document.getElementById('crop-cancel-btn');
            var cropUpload = document.getElementById('crop-upload-btn');
            if (cropCancel) cropCancel.addEventListener('click', function() { hideCropperArea(); });
            if (cropUpload) cropUpload.addEventListener('click', function() { cropAndUpload(); });
            var cropReset = document.getElementById('crop-reset-btn');
            if (cropReset) cropReset.addEventListener('click', function() { resetCropper(); });
        });

        // Cropper logic
        let cropperInstance = null;
        function openCropperFromFile(e) {
            const input = e.target;
            const file = input && input.files && input.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            const img = document.getElementById('cropper-image');
            img.src = url;
            showCropperArea();
            // Destroy previous instance
            if (cropperInstance) {
                try { cropperInstance.destroy(); } catch (err) {}
                cropperInstance = null;
            }
            // Wait a tick for image to load
            img.onload = function() {
                cropperInstance = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 0,
                    dragMode: 'move',
                    autoCropArea: 0.9,
                    restore: false,
                    modal: true,
                    guides: true,
                    center: true,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    responsive: true,
                    checkOrientation: false,
                    zoomable: true,
                    wheelZoomRatio: 0.1,
                    background: true,
                });
            };
        }

        function showCropperArea() {
            const area = document.getElementById('cropper-area');
            if (!area) return;
            area.classList.remove('hidden');
            // ensure modal remains open
            const modal = document.getElementById('photo-modal');
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        }

        function hideCropperArea() {
            const area = document.getElementById('cropper-area');
            if (!area) return;
            area.classList.add('hidden');
            if (cropperInstance) {
                try { cropperInstance.destroy(); } catch (err) {}
                cropperInstance = null;
            }
            // clear file input
            const fileInput = document.getElementById('photo-file-input');
            if (fileInput) fileInput.value = '';
        }

        function resetCropper() {
            if (!cropperInstance) return;
            try { cropperInstance.reset(); } catch (err) { console.warn('Reset failed', err); }
        }

        function cropAndUpload() {
            if (!cropperInstance) return;
            cropperInstance.getCroppedCanvas({ width: 600, height: 600, fillColor: '#fff' }).toBlob(function(blob) {
                if (!blob) return;
                const fd = new FormData();
                fd.append('profile_photo', blob, 'profile.jpg');
                // Append _method if your endpoint expects it (not necessary for POST)
                showPhotoModalSpinner();
                fetch("{{ route('profile.photo') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                })
                .then(async (res) => {
                    hidePhotoModalSpinner();
                    hideCropperArea();
                    if (res.ok) {
                        closePhotoModal();
                        try {
                            const data = await res.json();
                            if (data && data.url) {
                                const overlay = document.getElementById('profile-photo');
                                const preview = document.getElementById('modal-photo-preview');
                                const headerImg = document.querySelector('#profileMenuButton img');
                                if (overlay) overlay.src = data.url;
                                if (preview) preview.src = data.url;
                                if (headerImg) headerImg.src = data.url;
                                // Make delete visible immediately after successful crop upload
                                const uploadBtn = document.getElementById('upload-btn');
                                const changeBtn = document.getElementById('change-btn');
                                const deleteBtn = document.getElementById('delete-btn');
                                if (uploadBtn) uploadBtn.classList.add('hidden');
                                if (changeBtn) changeBtn.classList.remove('hidden');
                                if (deleteBtn) deleteBtn.classList.remove('hidden');
                            } else {
                                const blobUrl = URL.createObjectURL(blob);
                                const overlay = document.getElementById('profile-photo');
                                const preview = document.getElementById('modal-photo-preview');
                                const headerImg = document.querySelector('#profileMenuButton img');
                                if (overlay) overlay.src = blobUrl;
                                if (preview) preview.src = blobUrl;
                                if (headerImg) headerImg.src = blobUrl;
                            }
                        } catch (err) {
                            const blobUrl = URL.createObjectURL(blob);
                            const overlay = document.getElementById('profile-photo');
                            const preview = document.getElementById('modal-photo-preview');
                            const headerImg = document.querySelector('#profileMenuButton img');
                            if (overlay) overlay.src = blobUrl;
                            if (preview) preview.src = blobUrl;
                            if (headerImg) headerImg.src = blobUrl;
                        }
                    } else {
                        let msg = 'Failed to upload cropped image.';
                        try {
                            const err = await res.json();
                            if (err && err.message) msg = err.message;
                            else if (err && err.errors) msg = Object.values(err.errors).flat().join(' ');
                        } catch (e) {}
                        alert(msg);
                    }
                })
                .catch(err => {
                    hidePhotoModalSpinner();
                    hideCropperArea();
                    console.error('Upload error', err);
                    alert('Failed to upload cropped image. Please check your connection and try again.');
                });
            }, 'image/jpeg', 0.9);
        }
        </script>
        @endpush
    </div>
</div>

<!-- Success Toast Messages -->
<!-- Removed payment method details card from bottom of page after profile update -->

<script>
    // Password toggle functionality
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        if (!input || !btn) return;
        const svg = btn.querySelector('svg');
        if (!svg) return;
        if (input.type === 'password') {
            input.type = 'text';
            // Professional Heroicons eye-off (hide)
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.411"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>';
            btn.title = 'Hide password';
        } else {
            input.type = 'password';
            // Professional Heroicons eye (show)
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1.5 12s4.5-7.5 10.5-7.5S22.5 12 22.5 12s-4.5 7.5-10.5 7.5S1.5 12 1.5 12z" /><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none" />';
            btn.title = 'Show password';
        }
    }
</script>
<script>
function validateName() {
            const name = document.getElementById('name').value.trim();
            const msg = document.getElementById('name-validation');
            if (!/^([A-Za-z\s]{2,})$/.test(name)) {
                msg.textContent = 'Name must be at least 2 letters.';
                msg.className = 'mt-2 text-sm text-red-600';
            } else {
                msg.textContent = 'Valid name!';
                msg.className = 'mt-2 text-sm text-green-600';
            }
}
function validateEmail() {
            const email = document.getElementById('email').value.trim();
            const msg = document.getElementById('email-validation');
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!re.test(email)) {
                msg.textContent = 'Please enter a valid email address.';
                msg.className = 'mt-2 text-sm text-red-600';
            } else {
                msg.textContent = 'Valid email!';
                msg.className = 'mt-2 text-sm text-green-600';
            }
}
function validatePhone() {
            const phone = document.getElementById('phone').value.trim();
            const msg = document.getElementById('phone-validation');
            const re = /^(\+673[- ]?)?\d{7}$/;
            if (phone.length > 0 && !re.test(phone)) {
                msg.textContent = 'Please enter a valid Brunei phone number.';
                msg.className = 'mt-2 text-sm text-red-600';
            } else if (phone.length === 0) {
                msg.textContent = '';
            } else {
                msg.textContent = 'Valid phone number!';
                msg.className = 'mt-2 text-sm text-green-600';
            }
}
        function validateDepartment() {
            const department = document.getElementById('department');
            if (!department) return;
            const msg = document.getElementById('department-validation');
            if (!/^([A-Za-z\s]{2,})$/.test(department.value.trim())) {
                msg.textContent = 'Department must be at least 2 letters.';
                msg.className = 'mt-2 text-sm text-red-600';
            } else {
                msg.textContent = 'Valid department!';
                msg.className = 'mt-2 text-sm text-green-600';
            }
        }
// Initial validation on page load
document.addEventListener('DOMContentLoaded', function() {
    validateName();
    validateEmail();
    validatePhone();
            const name = document.getElementById('cardholder_name').value.trim();
            let msg; // single reusable message element to avoid redeclaration
            msg = document.getElementById('cardholder-name-validation');
            if (!/^([A-Za-z\s]{2,})$/.test(name)) {
                msg.textContent = 'Cardholder name must be at least 2 letters.';
                msg.className = 'mt-2 text-sm text-red-600';
            } else {
                msg.textContent = 'Valid cardholder name.';
                msg.className = 'mt-2 text-sm text-green-600';
            }
    let valid = true;

    // Current password
    if (!current.value.trim()) {
        current.classList.add('border-red-500');
        document.getElementById('current_password_error').classList.remove('hidden');
        valid = false;
    } else {
        current.classList.remove('border-red-500');
        document.getElementById('current_password_error').classList.add('hidden');
    }

    // New password
    if (newPass.value.length < 8) {
        newPass.classList.add('border-red-500');
        document.getElementById('password_error').classList.remove('hidden');
        valid = false;
    } else {
        newPass.classList.remove('border-red-500');
        document.getElementById('password_error').classList.add('hidden');
    }

    // Confirm password
    if (confirmPass.value !== newPass.value) {
        confirmPass.classList.add('border-red-500');
        document.getElementById('password_confirmation_error').classList.remove('hidden');
        valid = false;
    } else {
        confirmPass.classList.remove('border-red-500');
        document.getElementById('password_confirmation_error').classList.add('hidden');
    }

    // Card number
    const number = document.getElementById('card_number').value.replace(/\s/g, '');
    msg = document.getElementById('card-number-validation');
    if (!/^\d{13,19}$/.test(number)) {
        msg.textContent = 'Card number must be 13–19 digits.';
        msg.className = 'mt-2 text-sm text-red-600';
    } else if (!luhnCheck(number)) {
        msg.textContent = 'Card number is not valid.';
        msg.className = 'mt-2 text-sm text-red-600';
    } else {
        msg.textContent = 'Valid card number!';
        msg.className = 'mt-2 text-sm text-green-600';
    }
        valid = false;
    } else {
            const expiry = document.getElementById('card_expiry').value.trim();
            msg = document.getElementById('card-expiry-validation');
            const re = /^(0[1-9]|1[0-2])\/(\d{4})$/;
            if (!re.test(expiry)) {
                msg.textContent = 'Expiry must be MM/YYYY.';
                msg.className = 'mt-2 text-sm text-red-600';
                return;
            }
            // Check for expired date
            const [mm, yyyy] = expiry.split('/');
            const now = new Date();
            const expDate = new Date(parseInt(yyyy), parseInt(mm) - 1, 1);
            if (expDate < new Date(now.getFullYear(), now.getMonth(), 1)) {
                msg.textContent = 'Card is expired.';
                msg.className = 'mt-2 text-sm text-red-600';
            } else {
                msg.textContent = 'Valid expiry date!';
                msg.className = 'mt-2 text-sm text-green-600';
            }
    const form = document.getElementById('change-password-form');
    if (current && newPass && confirmPass) {
            const ccv = document.getElementById('card_ccv').value.trim();
            msg = document.getElementById('card-ccv-validation');
            if (!/^\d{3,4}$/.test(ccv)) {
                msg.textContent = 'CCV must be 3 or 4 digits.';
                msg.className = 'mt-2 text-sm text-red-600';
            } else {
                msg.textContent = 'Valid CCV!';
                msg.className = 'mt-2 text-sm text-green-600';
            }
    }
});
            validateName();
            validateEmail();
            validatePhone();
            validateDepartment();
            validateCardholderName();
            validateCardNumber();
            validateCardExpiry();
            validateCardCCV();
            validateBillingAddress();
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
