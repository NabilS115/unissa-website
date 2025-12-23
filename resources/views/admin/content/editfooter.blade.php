<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footer Content - Admin Panel</title>
    <link rel="icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit Footer Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize footer contact information and social media links</p>
                        </div>
                        <a href="/" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Homepage
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
                        <p class="text-sm font-medium text-green-800" id="success-text">Footer content updated successfully!</p>
                    </div>
                </div>
            </div>

            <!-- Footer Editing Form -->
            <form id="footer-form">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- UNISSA Cafe Footer -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 bg-teal-50">
                            <h2 class="text-xl font-semibold text-teal-800">UNISSA Cafe Footer</h2>
                            <p class="text-sm text-teal-600 mt-1">Appears on UNISSA Cafe pages</p>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <!-- Follow Us Section -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Follow Us Title</label>
                                <input type="text" name="content[footer_follow_us_title]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_follow_us_title', 'Follow Us', 'text', 'unissa-footer') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <!-- Social Media -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                                <input type="url" name="content[footer_instagram_url]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_instagram_url', 'https://www.instagram.com/unissacafe/', 'text', 'unissa-footer') }}"
                                       placeholder="https://www.instagram.com/unissacafe/"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL <span class="text-gray-400">(Optional)</span></label>
                                <input type="url" name="content[footer_facebook_url]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_facebook_url', '', 'text', 'unissa-footer') }}"
                                       placeholder="https://www.facebook.com/unissacafe/"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 1</label>
                                <input type="text" name="content[footer_address_line1]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_address_line1', 'Jalan Tungku Link, Gadong BE1410, Universiti Islam Sultan Sharif Ali, Brunei Darussalam', 'text', 'unissa-footer') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                                <input type="text" name="content[footer_address_line2]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_address_line2', 'Jubilee Hall, Gadong Campus', 'text', 'unissa-footer') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="content[footer_phone]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_phone', '+673 860 2877', 'text', 'unissa-footer') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            </div>
                        </div>
                    </div>

                    <!-- Tijarah Co Footer -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                            <h2 class="text-xl font-semibold text-blue-800">Tijarah Co Footer</h2>
                            <p class="text-sm text-blue-600 mt-1">Appears on main company pages</p>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <!-- Social Media -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                                <input type="url" name="content[footer_tijarah_instagram_url]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_tijarah_instagram_url', 'https://www.instagram.com/tijarahco.bn/', 'text', 'tijarah-footer') }}"
                                       placeholder="https://www.instagram.com/tijarahco.bn/"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL <span class="text-gray-400">(Optional)</span></label>
                                <input type="url" name="content[footer_tijarah_facebook_url]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_tijarah_facebook_url', '', 'text', 'tijarah-footer') }}"
                                       placeholder="https://www.facebook.com/tijarahco/"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <input type="text" name="content[footer_tijarah_address_line1]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_tijarah_address_line1', 'Jalan Tungku Link, Gadong BE1410, Universiti Islam Sultan Sharif Ali, Brunei Darussalam', 'text', 'tijarah-footer') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="content[footer_tijarah_email]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_tijarah_email', 'tijarahco.unissa.edu.bn', 'text', 'tijarah-footer') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="content[footer_tijarah_phone]" 
                                       value="{{ \App\Models\ContentBlock::get('footer_tijarah_phone', '+673 245 6789', 'text', 'tijarah-footer') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" id="save-button"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="save-text">Save Footer Content</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Set up CSRF token for all requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById('footer-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const saveButton = document.getElementById('save-button');
            const saveText = document.getElementById('save-text');
            const successMessage = document.getElementById('success-message');
            
            // Disable button and show loading state
            saveButton.disabled = true;
            saveText.textContent = 'Saving...';
            
            // Collect form data
            const formData = new FormData(this);
            const content = {};
            
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('content[') && key.endsWith(']')) {
                    const contentKey = key.slice(8, -1); // Remove 'content[' and ']'
                    content[contentKey] = value;
                }
            }
            
            try {
                const response = await fetch('{{ route("content.footer.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    document.getElementById('success-text').textContent = result.message;
                    successMessage.classList.remove('hidden');
                    
                    // Hide success message after 5 seconds
                    setTimeout(() => {
                        successMessage.classList.add('hidden');
                    }, 5000);
                    
                    // Scroll to top to show success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    alert('Error: ' + (result.message || 'Failed to update content'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update content. Please try again.');
            } finally {
                // Re-enable button
                saveButton.disabled = false;
                saveText.textContent = 'Save Footer Content';
            }
        });
    </script>
</body>
</html>