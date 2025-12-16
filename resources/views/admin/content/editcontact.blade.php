<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact Page Content - Admin Panel</title>
    <link rel="icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
                            <h1 class="text-2xl font-bold text-gray-900">Edit Contact Page Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize the content on your contact page</p>
                        </div>
                        <a href="{{ url('/contact') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Contact Page
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
            <form id="contact-form" class="space-y-8">
                @csrf
                
                <!-- Contact Page Preview -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Contact Page Preview</h2>
                        <p class="text-sm text-gray-600">Click on any section to edit</p>
                    </div>
                    
                    <!-- Header Preview -->
                    <div class="relative bg-gradient-to-r from-teal-500 to-blue-600 text-white p-8 cursor-pointer"
                         onclick="toggleSection('header')">
                        <div class="text-center">
                            <h1 class="text-3xl font-bold mb-2" id="header_title_display">{{ \App\Models\ContentBlock::get('contact_title', 'Contact Us', 'text', 'contact') }}</h1>
                            <p class="text-lg" id="header_subtitle_display">{{ \App\Models\ContentBlock::get('contact_subtitle', 'Get in touch with us', 'text', 'contact') }}</p>
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
                                   name="content[contact_title]" 
                                   id="contact_title_input"
                                   value="{{ \App\Models\ContentBlock::get('contact_title', 'Contact Us', 'text', 'contact') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateDisplay('header_title', this.value)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <input type="text" 
                                   name="content[contact_subtitle]" 
                                   id="contact_subtitle_input"
                                   value="{{ \App\Models\ContentBlock::get('contact_subtitle', 'Get in touch with us', 'text', 'contact') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateDisplay('header_subtitle', this.value)">
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleSection('header')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                    
                    <!-- Contact Info Preview -->
                    <div class="p-8 bg-white cursor-pointer border-b border-gray-100"
                         onclick="toggleSection('info')">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold mb-1">Email</h3>
                                <p class="text-gray-600" id="contact_email_display">{{ \App\Models\ContentBlock::get('contact_email', 'info@tijarahco.com', 'text', 'contact') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold mb-1">Phone</h3>
                                <p class="text-gray-600" id="contact_phone_display">{{ \App\Models\ContentBlock::get('contact_phone', '+673 123 4567', 'text', 'contact') }}</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold mb-1">Address</h3>
                                <p class="text-gray-600" id="contact_address_display">{!! \App\Models\ContentBlock::get('contact_address', 'Bandar Seri Begawan, Brunei', 'html', 'contact') !!}</p>
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <div class="text-sm text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Click to edit contact information
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Info Edit Form -->
                    <div id="info_edit" class="hidden bg-gray-50 p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" 
                                       name="content[contact_email]" 
                                       id="contact_email_input"
                                       value="{{ \App\Models\ContentBlock::get('contact_email', 'info@tijarahco.com', 'text', 'contact') }}"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                       onchange="updateDisplay('contact_email', this.value)">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" 
                                       name="content[contact_phone]" 
                                       id="contact_phone_input"
                                       value="{{ \App\Models\ContentBlock::get('contact_phone', '+673 123 4567', 'text', 'contact') }}"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                       onchange="updateDisplay('contact_phone', this.value)">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <input type="text" 
                                       name="content[contact_address]" 
                                       id="contact_address_input"
                                       value="{{ \App\Models\ContentBlock::get('contact_address', 'Bandar Seri Begawan, Brunei', 'text', 'contact') }}"
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                       onchange="updateDisplay('contact_address', this.value)">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleSection('info')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                </div>



                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Contact Information</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" 
                                       name="content[contact_phone]" 
                                       id="contact_phone"
                                       value="{{ \App\Models\ContentBlock::get('contact_phone', '+60 12-345 6789', 'text', 'contact') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" 
                                       name="content[contact_email]" 
                                       id="contact_email"
                                       value="{{ \App\Models\ContentBlock::get('contact_email', 'info@tijarahco.com', 'text', 'contact') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>
                        </div>
                        <div>
                            <label for="contact_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="content[contact_address]" 
                                      id="contact_address"
                                      rows="3"
                                      placeholder="Enter address lines (press Enter for new lines)"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ str_replace('<br>', "\n", \App\Models\ContentBlock::get('contact_address', 'Kuala Lumpur, Malaysia', 'text', 'contact')) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Press Enter to create new lines</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Description -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Contact Form Description</h2>
                    </div>
                    <div class="px-6 py-4">
                        <label for="contact_form_description" class="block text-sm font-medium text-gray-700 mb-2">Description Text</label>
                        <textarea name="content[contact_form_description]" 
                                  id="contact_form_description"
                                  class="ckeditor">{{ \App\Models\ContentBlock::get('contact_form_description', '<p>We would love to hear from you! Send us a message and we will respond as soon as possible.</p>', 'html', 'contact') }}</textarea>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Business Hours</h2>
                    </div>
                    <div class="px-6 py-4">
                        <label for="contact_business_hours" class="block text-sm font-medium text-gray-700 mb-2">Business Hours</label>
                        <textarea name="content[contact_business_hours]" 
                                  id="contact_business_hours"
                                  class="ckeditor">{{ \App\Models\ContentBlock::get('contact_business_hours', '<p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM<br><strong>Saturday:</strong> 9:00 AM - 2:00 PM<br><strong>Sunday:</strong> Closed</p>', 'html', 'contact') }}</textarea>
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
            document.getElementById('contact-form').addEventListener('submit', async function(e) {
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
                    
                    // Convert line breaks to <br> tags for contact address field
                    const contactAddress = formData.get('content[contact_address]');
                    if (contactAddress) {
                        formData.set('content[contact_address]', contactAddress.replace(/\n/g, '<br>'));
                    }
                    
                    // Update form data with CKEditor content
                    for (const [name, editor] of Object.entries(editors)) {
                        const fieldName = name.replace('content[', '').replace(']', '');
                        formData.set(`content[${fieldName}]`, editor.getData());
                    }
                    
                    const response = await fetch('{{ route('content.contact.update') }}', {
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

            // Legacy function for backward compatibility
            window.uploadContactHeroImage = async function() {
                alert('Hero section has been removed from the contact page.');
            };
        });
    </script>
</body>
</html>