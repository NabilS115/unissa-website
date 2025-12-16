<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Privacy Policy - Admin Panel</title>    <link rel="icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon" sizes="32x32">
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
                            <h1 class="text-2xl font-bold text-gray-900">Edit Privacy Policy Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize the content on your privacy policy page</p>
                        </div>
                        <a href="{{ url('/privacy-policy') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Privacy Policy
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
            <form id="privacy-form" class="space-y-8">
                @csrf
                
                <!-- Privacy Policy Page Preview -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Privacy Policy Preview</h2>
                        <p class="text-sm text-gray-600">Click on any section to edit</p>
                    </div>
                    
                    <!-- Header Preview -->
                    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white p-8 cursor-pointer"
                         onclick="toggleSection('header')">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold mb-2" id="header_title_display">{{ \App\Models\ContentBlock::get('privacy_title', 'Privacy Policy', 'text', 'privacy') }}</h1>
                            <p class="text-lg opacity-90" id="header_updated_display">{{ \App\Models\ContentBlock::get('privacy_last_updated', 'Last updated: ' . date('F j, Y'), 'text', 'privacy') }}</p>
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
                                   name="content[privacy_title]" 
                                   id="privacy_title_input"
                                   value="{{ \App\Models\ContentBlock::get('privacy_title', 'Privacy Policy', 'text', 'privacy') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   onchange="updateDisplay('header_title', this.value)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                            <input type="text" 
                                   name="content[privacy_last_updated]" 
                                   id="privacy_last_updated_input"
                                   value="{{ \App\Models\ContentBlock::get('privacy_last_updated', 'Last updated: ' . date('F j, Y'), 'text', 'privacy') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   onchange="updateDisplay('header_updated', this.value)">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleSection('header')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                    
                    <!-- Privacy Content Preview -->
                    <div class="p-8 bg-white cursor-pointer"
                         onclick="toggleSection('content')">
                        <div class="max-w-4xl mx-auto prose prose-lg">
                            <div id="content_preview" class="text-gray-700 leading-relaxed">
                                {!! \App\Models\ContentBlock::get('privacy_content', '<p>Your privacy content will appear here. This section can include information about how you collect, use, and protect user data.</p>', 'html', 'privacy') !!}
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <div class="text-sm text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Click to edit privacy policy content
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content Edit Form -->
                    <div id="content_edit" class="hidden bg-gray-50 p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Privacy Policy Content</label>
                            <textarea name="content[privacy_content]" 
                                      id="privacy_content_input"
                                      class="ckeditor block w-full">{{ \App\Models\ContentBlock::get('privacy_content', '<p>Your privacy content will appear here. This section can include information about how you collect, use, and protect user data.</p>', 'html', 'privacy') }}</textarea>
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
                            <label for="privacy_hero_image" class="block text-sm font-medium text-gray-700">Hero Background Image</label>
                            <div class="mt-1 space-y-2">
                                <div class="flex items-center space-x-4">
                                    <input type="file" 
                                           id="privacy_hero_image" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                    <button type="button" 
                                            onclick="uploadPrivacyHeroImage()"
                                            class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm hover:bg-teal-700">Upload</button>
                                </div>
                                <input type="hidden" 
                                       name="content[privacy_hero_image]" 
                                       id="privacy_hero_image_url"
                                       value="{{ \App\Models\ContentBlock::get('privacy_hero_image', 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1600&q=80', 'text', 'privacy') }}">
                                <div class="mt-2">
                                    <img id="privacy_hero_image_preview" 
                                         src="{{ \App\Models\ContentBlock::get('privacy_hero_image', 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1600&q=80', 'text', 'privacy') }}" 
                                         alt="Hero Background Preview" 
                                         class="w-32 h-20 object-cover rounded border">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Policy Content -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Privacy Policy Content</h2>
                    </div>
                    <div class="px-6 py-4">
                        <label for="privacy_content" class="block text-sm font-medium text-gray-700 mb-2">Policy Content</label>
                        <textarea name="content[privacy_content]" 
                                  id="privacy_content"
                                  class="ckeditor">{{ \App\Models\ContentBlock::get('privacy_content', '
<h3>Information We Collect</h3>
<p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>

<h3>How We Use Your Information</h3>
<p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>

<h3>Information Sharing</h3>
<p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>

<h3>Data Security</h3>
<p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

<h3>Contact Us</h3>
<p>If you have any questions about this Privacy Policy, please contact us at info@tijarahco.com.</p>
                        ', 'html', 'privacy') }}</textarea>
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
            document.getElementById('privacy-form').addEventListener('submit', async function(e) {
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
                    
                    const response = await fetch('{{ route('content.privacy.update') }}', {
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
            window.uploadPrivacyHeroImage = async function() {
                const fileInput = document.getElementById('privacy_hero_image');
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
                        document.getElementById('privacy_hero_image_url').value = result.url;
                        document.getElementById('privacy_hero_image_preview').src = result.url;
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