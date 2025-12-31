<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cash Pickup Settings - Admin Panel</title>
    <link rel="icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Cash Pickup Settings</h1>
                            <p class="mt-1 text-sm text-gray-600">Manage pickup location, hours, and contact details for cash on pickup orders</p>
                        </div>
                        <a href="{{ route('admin.profile') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Admin
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div id="success-message" class="hidden bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800" id="success-text">Settings updated successfully!</p>
                    </div>
                </div>
            </div>

            <!-- Cash Pickup Settings Form -->
            <form id="cash-pickup-form" class="space-y-8">
                @csrf
                
                <!-- Current Settings Preview -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Current Pickup Information</h2>
                        <p class="text-sm text-gray-600">Preview of how customers will see your pickup details</p>
                    </div>
                    
                    <!-- Pickup Details Preview -->
                    <div class="p-6 bg-gradient-to-r from-amber-50 to-orange-50">
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-amber-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Cash on Pickup</h3>
                                    <p class="text-sm text-gray-600">Collection and payment details</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Location:</span>
                                    <span class="text-amber-700 font-medium" id="preview-location-name">
                                        {{ \App\Models\ContentBlock::get('pickup_location_name', 'UNISSA Café', 'text', 'cash-pickup') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Address:</span>
                                    <span class="text-gray-700 font-medium" id="preview-location-address">
                                        {{ \App\Models\ContentBlock::get('pickup_location_address', '123 Main Street, City Center', 'text', 'cash-pickup') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Hours:</span>
                                    <span class="text-gray-700 font-medium" id="preview-business-hours">
                                        {{ \App\Models\ContentBlock::get('pickup_business_hours', 'Monday-Sunday, 8:00 AM - 8:00 PM', 'text', 'cash-pickup') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Contact:</span>
                                    <span class="text-gray-700 font-medium" id="preview-contact-phone">
                                        {{ \App\Models\ContentBlock::get('pickup_contact_phone', '+673 8123456', 'text', 'cash-pickup') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Pickup Details -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Edit Pickup Details</h2>
                        <p class="text-sm text-gray-600">Update the pickup location and contact information for customers</p>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <div>
                            <label for="pickup_location_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Location Name *
                            </label>
                            <input type="text" 
                                   name="content[pickup_location_name]" 
                                   id="pickup_location_name"
                                   value="{{ \App\Models\ContentBlock::get('pickup_location_name', 'UNISSA Café', 'text', 'cash-pickup') }}"
                                   placeholder="e.g., UNISSA Café"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-lg"
                                   onchange="updatePreview('name', this.value)"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">The name of your business/pickup location</p>
                        </div>
                        
                        <div>
                            <label for="pickup_location_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Pickup Address *
                            </label>
                            <textarea name="content[pickup_location_address]" 
                                      id="pickup_location_address"
                                      rows="3"
                                      placeholder="e.g., 123 Main Street&#10;City Center, State 12345"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-lg"
                                      onchange="updatePreview('address', this.value)"
                                      required>{{ \App\Models\ContentBlock::get('pickup_location_address', '123 Main Street, City Center', 'text', 'cash-pickup') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">The full address where customers can pick up their orders</p>
                        </div>

                        <div>
                            <label for="pickup_business_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Business Hours *
                            </label>
                            <textarea name="content[pickup_business_hours]" 
                                      id="pickup_business_hours"
                                      rows="3"
                                      placeholder="e.g., Monday-Friday: 9:00 AM - 6:00 PM&#10;Saturday: 9:00 AM - 2:00 PM&#10;Sunday: Closed"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-lg"
                                      onchange="updatePreview('hours', this.value)"
                                      required>{{ \App\Models\ContentBlock::get('pickup_business_hours', 'Monday-Sunday, 8:00 AM - 8:00 PM', 'text', 'cash-pickup') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">When customers can collect their orders</p>
                        </div>

                        <div>
                            <label for="pickup_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Phone *
                            </label>
                            <input type="text" 
                                   name="content[pickup_contact_phone]" 
                                   id="pickup_contact_phone"
                                   value="{{ \App\Models\ContentBlock::get('pickup_contact_phone', '+673 8123456', 'text', 'cash-pickup') }}"
                                   placeholder="e.g., +673 8123456"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500 text-lg"
                                   onchange="updatePreview('phone', this.value)"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Phone number for customers to call about their orders</p>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Important Information</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p class="mb-2">Please ensure all information is accurate:</p>
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Only enter your actual business location and contact details</li>
                                            <li>Double-check the address and phone number for accuracy</li>
                                            <li>These details will be visible to all customers during checkout</li>
                                            <li>Changes will apply immediately to all new orders</li>
                                            <li>Make sure business hours are current and accurate</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            id="save-btn"
                            class="bg-amber-600 hover:bg-amber-700 text-white font-medium py-3 px-6 rounded-md transition-colors">
                        Save Cash Pickup Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission
            document.getElementById('cash-pickup-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const saveBtn = document.getElementById('save-btn');
                const originalText = saveBtn.textContent;
                saveBtn.textContent = 'Saving...';
                saveBtn.disabled = true;

                try {
                    // Get form data
                    const formData = new FormData(this);
                    
                    // Convert FormData to proper nested structure for content fields
                    const data = {
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        content: {}
                    };
                    
                    for (const [key, value] of formData.entries()) {
                        // Skip the CSRF token as we're adding it manually
                        if (key === '_token') continue;
                        
                        // Extract the content field name from content[field_name] format
                        if (key.startsWith('content[') && key.endsWith(']')) {
                            const fieldName = key.slice(8, -1); // Remove 'content[' and ']'
                            data.content[fieldName] = value;
                        }
                    }

                    const response = await fetch('{{ route("content.cash-pickup.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': data._token,
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        showSuccessMessage(result.message);
                    } else {
                        alert('Error: ' + (result.message || 'Failed to save settings'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while saving settings.');
                }

                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
            });
        });

        function showSuccessMessage(message) {
            const successDiv = document.getElementById('success-message');
            const successText = document.getElementById('success-text');
            successText.textContent = message;
            successDiv.classList.remove('hidden');
            
            setTimeout(() => {
                successDiv.classList.add('hidden');
            }, 5000);
        }

        function updatePreview(type, value) {
            if (type === 'name') {
                document.getElementById('preview-location-name').textContent = value || 'UNISSA Café';
            } else if (type === 'address') {
                document.getElementById('preview-location-address').textContent = value || '123 Main Street, City Center';
            } else if (type === 'hours') {
                document.getElementById('preview-business-hours').textContent = value || 'Monday-Sunday, 8:00 AM - 8:00 PM';
            } else if (type === 'phone') {
                document.getElementById('preview-contact-phone').textContent = value || '+673 8123456';
            }
        }
    </script>
</body>
</html>