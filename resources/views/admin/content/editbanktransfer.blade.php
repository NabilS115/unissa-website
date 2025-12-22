<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bank Transfer Settings - Admin Panel</title>
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
                            <h1 class="text-2xl font-bold text-gray-900">Bank Transfer Settings</h1>
                            <p class="mt-1 text-sm text-gray-600">Manage BIBD bank account details for customer transfers</p>
                        </div>
                        <a href="{{ route('cart.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Cart
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

            <!-- Bank Transfer Settings Form -->
            <form id="bank-transfer-form" class="space-y-8">
                @csrf
                
                <!-- Current Settings Preview -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Current Bank Transfer Information</h2>
                        <p class="text-sm text-gray-600">Preview of how customers will see your bank details</p>
                    </div>
                    
                    <!-- Bank Details Preview -->
                    <div class="p-6 bg-gradient-to-r from-teal-50 to-emerald-50">
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-teal-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-teal-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">BIBD Bank Transfer</h3>
                                    <p class="text-sm text-gray-600">Transfer details for payment</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Account Name:</span>
                                    <span class="text-teal-700 font-medium" id="preview-account-name">
                                        {{ \App\Models\ContentBlock::get('bank_account_name', 'UNISSA Café', 'text', 'bank-transfer') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Account Number:</span>
                                    <span class="font-mono bg-teal-100 px-3 py-1 rounded text-teal-800 font-bold" id="preview-account-number">
                                        {{ \App\Models\ContentBlock::get('bank_account_number', '[Your Account Number]', 'text', 'bank-transfer') }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Bank:</span>
                                    <span class="text-gray-700 font-medium">BIBD (Bank Islam Brunei Darussalam)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Bank Details -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Edit Bank Account Details</h2>
                        <p class="text-sm text-gray-600">Update the bank account information for customer transfers</p>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <div>
                            <label for="bank_account_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Account Name *
                            </label>
                            <input type="text" 
                                   name="content[bank_account_name]" 
                                   id="bank_account_name"
                                   value="{{ \App\Models\ContentBlock::get('bank_account_name', 'UNISSA Café', 'text', 'bank-transfer') }}"
                                   placeholder="e.g., UNISSA Café"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-lg"
                                   onchange="updatePreview('name', this.value)"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">The exact name on the bank account</p>
                        </div>
                        
                        <div>
                            <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Account Number *
                            </label>
                            <input type="text" 
                                   name="content[bank_account_number]" 
                                   id="bank_account_number"
                                   value="{{ \App\Models\ContentBlock::get('bank_account_number', '', 'text', 'bank-transfer') }}"
                                   placeholder="e.g., 1234567890123456"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 text-lg font-mono"
                                   onchange="updatePreview('number', this.value)"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">The BIBD account number where customers will transfer money</p>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Important Security Notice</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Only enter your actual business BIBD account details</li>
                                            <li>Double-check the account number for accuracy</li>
                                            <li>These details will be visible to all customers during checkout</li>
                                            <li>Changes will apply immediately to all new orders</li>
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
                            class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-3 px-6 rounded-md transition-colors">
                        Save Bank Transfer Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission
            document.getElementById('bank-transfer-form').addEventListener('submit', async function(e) {
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

                    const response = await fetch('{{ route("content.bank-transfer.update") }}', {
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
                document.getElementById('preview-account-name').textContent = value || 'UNISSA Café';
            } else if (type === 'number') {
                document.getElementById('preview-account-number').textContent = value || '[Your Account Number]';
            }
        }
    </script>
</body>
</html>