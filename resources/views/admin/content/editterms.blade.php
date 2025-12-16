<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Terms of Service - Admin Panel</title>    <link rel="icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}">    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
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
                            <h1 class="text-2xl font-bold text-gray-900">Edit Terms of Service Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize the content on your terms of service page</p>
                        </div>
                        <a href="{{ url('/terms-of-service') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Terms of Service
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
                        <p class="text-sm font-medium text-green-800" id="success-text">Content updated successfully!</p>
                    </div>
                </div>
            </div>

            <!-- Content Editor Form -->
            <form id="terms-form" class="space-y-8">
                @csrf
                
                <!-- Terms of Service Page Preview -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Terms of Service Preview</h2>
                        <p class="text-sm text-gray-600">Click on any section to edit</p>
                    </div>
                    
                    <!-- Header Preview -->
                    <div class="relative bg-gradient-to-r from-green-600 to-teal-600 text-white p-8 cursor-pointer"
                         onclick="toggleSection('header')">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold mb-2" id="header_title_display">{{ \App\Models\ContentBlock::get('terms_title', 'Terms of Service', 'text', 'terms') }}</h1>
                            <p class="text-lg opacity-90" id="header_updated_display">{{ \App\Models\ContentBlock::get('terms_last_updated', 'Last updated: ' . date('F j, Y'), 'text', 'terms') }}</p>
                        </div>
                        <div class="absolute top-4 right-4 text-sm opacity-75">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Click to edit
                        </div>
                    </div>
                    
                    <!-- Header Edit Form -->
                    <div id="header_edit" class="hidden bg-gray-50 p-6 border-b space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
                            <input type="text" 
                                   name="content[terms_title]" 
                                   id="terms_title_input"
                                   value="{{ \App\Models\ContentBlock::get('terms_title', 'Terms of Service', 'text', 'terms') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                                   onchange="updateDisplay('header_title', this.value)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                            <input type="text" 
                                   name="content[terms_last_updated]" 
                                   id="terms_last_updated_input"
                                   value="{{ \App\Models\ContentBlock::get('terms_last_updated', 'Last updated: ' . date('F j, Y'), 'text', 'terms') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                                   onchange="updateDisplay('header_updated', this.value)">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleSection('header')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                    
                    <!-- Terms Content Preview -->
                    <div class="p-8 bg-white cursor-pointer"
                         onclick="toggleSection('content')">
                        <div class="max-w-4xl mx-auto prose prose-lg">
                            <div id="content_preview" class="text-gray-700 leading-relaxed">
                                {!! \App\Models\ContentBlock::get('terms_content', '<p>Your terms of service content will appear here. This section should include the terms and conditions for using your services.</p>', 'html', 'terms') !!}
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <div class="text-sm text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Click to edit terms of service content
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content Edit Form -->
                    <div id="content_edit" class="hidden bg-gray-50 p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Terms of Service Content</label>
                            <textarea name="content[terms_content]" 
                                      id="terms_content_input"
                                      class="ckeditor block w-full">{{ \App\Models\ContentBlock::get('terms_content', '<p>Your terms of service content will appear here. This section should include the terms and conditions for using your services.</p>', 'html', 'terms') }}</textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleSection('content')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                </div>

                <!-- Hero Image Settings -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Hero Image</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="terms_hero_image" class="block text-sm font-medium text-gray-700">Hero Background Image</label>
                            <div class="mt-1 space-y-2">
                                <div class="flex items-center space-x-4">
                                    <input type="file" 
                                           id="terms_hero_image" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                    <button type="button" 
                                            onclick="uploadTermsHeroImage()"
                                            class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm hover:bg-teal-700">Upload</button>
                                </div>
                                <input type="hidden" 
                                       name="content[terms_hero_image]" 
                                       id="terms_hero_image_url"
                                       value="{{ \App\Models\ContentBlock::get('terms_hero_image', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=1600&q=80', 'text', 'terms') }}">
                                <div class="mt-2">
                                    <img id="terms_hero_image_preview" 
                                         src="{{ \App\Models\ContentBlock::get('terms_hero_image', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=1600&q=80', 'text', 'terms') }}" 
                                         alt="Hero Background Preview" 
                                         class="w-32 h-20 object-cover rounded border">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms of Service Content -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Terms of Service Content</h2>
                    </div>
                    <div class="px-6 py-4">
                        <label for="terms_content" class="block text-sm font-medium text-gray-700 mb-2">Terms Content</label>
                        <textarea name="content[terms_content]" 
                                  id="terms_content"
                                  class="ckeditor">{{ \App\Models\ContentBlock::get('terms_content', '
<h3>1. Acceptance of Terms</h3>
<p>By using our services, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>

<h3>2. Use of Services</h3>
<p>You may use our services only for lawful purposes and in accordance with these Terms. You agree not to use the services in any way that could damage or overburden our systems.</p>

<h3>3. User Accounts</h3>
<p>You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.</p>

<h3>4. Prohibited Uses</h3>
<p>You may not use our services for any illegal or unauthorized purpose, to transmit harmful content, or to infringe on the rights of others.</p>

<h3>5. Intellectual Property</h3>
<p>All content and materials on our platform are protected by intellectual property rights and remain our property or the property of our licensors.</p>

<h3>6. Limitation of Liability</h3>
<p>We shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of our services.</p>

<h3>7. Contact Information</h3>
<p>If you have any questions about these Terms of Service, please contact us at info@tijarahco.com.</p>
                        ', 'html', 'terms') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" id="submit-btn" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition duration-200">
                        <span class="submit-text">Save Changes</span>
                        <span class="loading-text hidden">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Visual editing functions
        function toggleSection(sectionName) {
            const editForm = document.getElementById(sectionName + '_edit');
            editForm.classList.toggle('hidden');
        }

        function updateDisplay(elementId, value) {
            const displayElement = document.getElementById(elementId + '_display');
            if (displayElement) {
                displayElement.textContent = value;
            }
        }

        // Initialize CKEditor for all textarea elements with ckeditor class
        document.addEventListener('DOMContentLoaded', function() {
            const editorElements = document.querySelectorAll('.ckeditor');
            let editors = {};

            editorElements.forEach((element) => {
                ClassicEditor
                    .create(element, {
                        toolbar: {
                            items: [
                                'heading', '|', 'bold', 'italic', 'link', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|', 'blockQuote', 'insertTable', '|',
                                'undo', 'redo'
                            ]
                        }
                    })
                    .then(editor => {
                        editors[element.name] = editor;
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor:', error);
                    });
            });

            // Form submission handler
            document.getElementById('terms-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submit-btn');
                const submitText = submitBtn.querySelector('.submit-text');
                const loadingText = submitBtn.querySelector('.loading-text');
                
                // Show loading state
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingText.classList.remove('hidden');
                
                try {
                    const formData = new FormData(this);
                    
                    // Update form data with CKEditor content
                    for (const [name, editor] of Object.entries(editors)) {
                        const fieldName = name.replace('content[', '').replace(']', '');
                        formData.set(`content[${fieldName}]`, editor.getData());
                    }
                    
                    const response = await fetch('{{ route('content.terms.update') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        // Show success message
                        const successMessage = document.getElementById('success-message');
                        const successText = document.getElementById('success-text');
                        successText.textContent = result.message;
                        successMessage.classList.remove('hidden');
                        
                        // Hide success message after 5 seconds
                        setTimeout(() => {
                            successMessage.classList.add('hidden');
                        }, 5000);
                        
                        // Scroll to top to show success message
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        alert('Error: ' + (result.message || 'Unknown error occurred'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while saving. Please try again.');
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                }
            });

            // Image upload function
            window.uploadTermsHeroImage = async function() {
                const fileInput = document.getElementById('terms_hero_image');
                const file = fileInput.files[0];
                
                if (!file) {
                    alert('Please select an image first');
                    return;
                }

                const formData = new FormData();
                formData.append('image', file);

                try {
                    const response = await fetch('{{ route('content.upload.image') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (result.success) {
                        document.getElementById('terms_hero_image_url').value = result.url;
                        document.getElementById('terms_hero_image_preview').src = result.url;
                        alert('Image uploaded successfully!');
                    } else {
                        alert('Upload failed: ' + result.message);
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('Upload failed. Please try again.');
                }
            };
        });
    </script>
</body>
</html>