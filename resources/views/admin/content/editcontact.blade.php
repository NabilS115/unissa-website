<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact Page Content - Admin Panel</title>
    <link rel="icon" href="{{ asset('images/tijarah-co-logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/tijarah-co-logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/tijarah-co-logo.png') }}">
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
                
                <!-- Page Header Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Page Header</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="contact_title" class="block text-sm font-medium text-gray-700">Page Title</label>
                            <input type="text" 
                                   name="content[contact_title]" 
                                   id="contact_title"
                                   value="{{ \App\Models\ContentBlock::get('contact_title', 'Contact Us', 'text', 'contact') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        
                        <div>
                            <label for="contact_subtitle" class="block text-sm font-medium text-gray-700">Page Subtitle</label>
                            <input type="text" 
                                   name="content[contact_subtitle]" 
                                   id="contact_subtitle"
                                   value="{{ \App\Models\ContentBlock::get('contact_subtitle', 'Get in touch with us', 'text', 'contact') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Contact Form Description Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Contact Form Description</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="contact_form_description" class="block text-sm font-medium text-gray-700">Form Description</label>
                            <textarea name="content[contact_form_description]" 
                                      id="contact_form_description"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('contact_form_description', '<p>We would love to hear from you! Send us a message and we will respond as soon as possible.</p>', 'html', 'contact') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Contact Information</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" 
                                       name="content[contact_email]" 
                                       id="contact_email"
                                       value="{{ \App\Models\ContentBlock::get('contact_email', 'tijarahco@unissa.edu.bn', 'text', 'contact') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" 
                                       name="content[contact_phone]" 
                                       id="contact_phone"
                                       value="{{ \App\Models\ContentBlock::get('contact_phone', '+673 123 4567', 'text', 'contact') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="contact_address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="content[contact_address]" 
                                          id="contact_address"
                                          rows="4"
                                          placeholder="Enter address lines (press Enter for new lines)"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ str_replace('<br>', "\n", \App\Models\ContentBlock::get('contact_address', 'Universiti Islam Sultan Sharif Ali<br>Simpang 347, Jalan Pasar Gadong<br>Bandar Seri Begawan, Brunei', 'html', 'contact')) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Press Enter to create new lines</p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="contact_business_hours" class="block text-sm font-medium text-gray-700">Business Hours</label>
                                <textarea name="content[contact_business_hours]" 
                                          id="contact_business_hours"
                                          class="ckeditor">{{ \App\Models\ContentBlock::get('contact_business_hours', '<p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM<br><strong>Saturday:</strong> 9:00 AM - 2:00 PM<br><strong>Sunday:</strong> Closed</p>', 'html', 'contact') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Social Media Links</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="contact_facebook_link" class="block text-sm font-medium text-gray-700">Facebook URL (Optional)</label>
                                <input type="text" 
                                       name="content[contact_facebook_link]" 
                                       id="contact_facebook_link"
                                       value="{{ \App\Models\ContentBlock::get('contact_facebook_link', '', 'text', 'contact') }}"
                                       placeholder="https://facebook.com/yourpage (leave empty if not applicable)"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                <p class="text-xs text-gray-500 mt-1">Leave empty if you don't have a Facebook page</p>
                            </div>

                            <div>
                                <label for="contact_instagram_link" class="block text-sm font-medium text-gray-700">Instagram URL (Optional)</label>
                                <input type="text" 
                                       name="content[contact_instagram_link]" 
                                       id="contact_instagram_link"
                                       value="{{ \App\Models\ContentBlock::get('contact_instagram_link', 'https://www.instagram.com/tijarahco.bn/', 'text', 'contact') }}"
                                       placeholder="https://instagram.com/youraccount (leave empty if not applicable)"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                <p class="text-xs text-gray-500 mt-1">Leave empty if you don't have an Instagram account</p>
                            </div>

                            <div>
                                <label for="contact_twitter_link" class="block text-sm font-medium text-gray-700">Twitter URL (Optional)</label>
                                <input type="text" 
                                       name="content[contact_twitter_link]" 
                                       id="contact_twitter_link"
                                       value="{{ \App\Models\ContentBlock::get('contact_twitter_link', '', 'text', 'contact') }}"
                                       placeholder="https://twitter.com/youraccount (leave empty if not applicable)"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                <p class="text-xs text-gray-500 mt-1">Leave empty if you don't have a Twitter account</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            id="save-btn"
                            class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize CKEditor for all textarea elements with ckeditor class
        document.addEventListener('DOMContentLoaded', function() {
            const editorElements = document.querySelectorAll('.ckeditor');
            let editors = {};

            editorElements.forEach((element) => {
                ClassicEditor
                    .create(element, {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
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

            // Form submission
            document.getElementById('contact-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const saveBtn = document.getElementById('save-btn');
                const originalText = saveBtn.textContent;
                saveBtn.textContent = 'Saving...';
                saveBtn.disabled = true;

                try {
                    // Get form data
                    const formData = new FormData(this);
                    
                    // Update form data with CKEditor content
                    for (const [name, editor] of Object.entries(editors)) {
                        if (formData.has(name)) {
                            formData.set(name, editor.getData());
                        }
                    }

                    // Convert FormData to proper nested structure for content fields
                    const data = {
                        content: {}
                    };
                    
                    for (const [key, value] of formData.entries()) {
                        const match = key.match(/^content\[(.+)\]$/);
                        if (match) {
                            let processedValue = value;
                            // Convert line breaks to <br> tags for address field
                            if (match[1] === 'contact_address') {
                                processedValue = value.replace(/\n/g, '<br>');
                            }
                            // Clean up social media URLs - remove # placeholders and validate
                            if (match[1].includes('_link')) {
                                processedValue = value.trim();
                                // Remove placeholder values
                                if (processedValue === '#' || processedValue === '') {
                                    processedValue = '';
                                } else {
                                    // Basic URL validation for non-empty values
                                    if (!processedValue.match(/^https?:\/\/.+/)) {
                                        alert(`Please enter a valid URL for ${match[1].replace('contact_', '').replace('_link', '')} or leave it empty.`);
                                        throw new Error('Invalid URL');
                                    }
                                }
                            }
                            data.content[match[1]] = processedValue;
                        } else {
                            data[key] = value;
                        }
                    }

                    const response = await fetch('{{ route("content.contact.update") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        const successMessage = document.getElementById('success-message');
                        document.getElementById('success-text').textContent = result.message;
                        successMessage.classList.remove('hidden');
                        
                        setTimeout(() => {
                            successMessage.classList.add('hidden');
                        }, 3000);
                        
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        alert('Error: ' + (result.message || 'Unknown error occurred'));
                    }
                } catch (error) {
                    console.error('Error saving content:', error);
                    alert('Error saving content. Please try again.');
                } finally {
                    saveBtn.textContent = originalText;
                    saveBtn.disabled = false;
                }
            });
        });
    </script>
</body>
</html>