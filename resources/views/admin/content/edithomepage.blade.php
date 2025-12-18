<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Homepage Content - Admin Panel</title>
    <link rel="icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="/tijarahco_sdn_bhd_logo.ico?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
    <!-- Add Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
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
                            <h1 class="text-2xl font-bold text-gray-900">Edit Homepage Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize the content on your homepage</p>
                        </div>
                        <a href="{{ url('/') }}" 
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
                        <p class="text-sm font-medium text-green-800" id="success-text">Content updated successfully!</p>
                    </div>
                </div>
            </div>

            <!-- Content Editor Form -->
            <form id="homepage-form" class="space-y-8">
                @csrf
                
                <!-- Hero Section Preview -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Hero Section</h2>
                        <p class="text-sm text-gray-600">Click on the preview to edit</p>
                    </div>
                    
                    <!-- Hero Preview -->
                    <div class="relative bg-cover bg-center h-64 cursor-pointer" 
                         id="hero_preview"
                         style="background-image: url('{{ \App\Models\ContentBlock::get('hero_background_image', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80', 'text', 'homepage') }}');"
                         onclick="toggleHeroEdit()">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <div class="text-center text-white">
                                <h1 class="text-3xl md:text-5xl font-bold mb-4" id="hero_title_display">{{ \App\Models\ContentBlock::get('hero_title', 'Business with Barakah', 'text', 'homepage') }}</h1>
                                <p class="text-lg md:text-xl mb-6" id="hero_subtitle_display">{{ \App\Models\ContentBlock::get('hero_subtitle', 'Promoting halal, ethical, and impactful entrepreneurship through UNISSA\'s Tijarah Co.', 'text', 'homepage') }}</p>
                                <div class="flex items-center justify-center text-sm text-white/80">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Click to edit hero section
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hero Edit Form (Hidden) -->
                    <div id="hero_edit_form" class="hidden p-6 bg-gray-50 border-t space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" 
                                   name="content[hero_title]" 
                                   id="hero_title_input"
                                   value="{{ \App\Models\ContentBlock::get('hero_title', 'Business with Barakah', 'text', 'homepage') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateHeroDisplay('title', this.value)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <textarea name="content[hero_subtitle]" 
                                      id="hero_subtitle_input"
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      onchange="updateHeroDisplay('subtitle', this.value)">{{ \App\Models\ContentBlock::get('hero_subtitle', 'Promoting halal, ethical, and impactful entrepreneurship through UNISSA\'s Tijarah Co.', 'text', 'homepage') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                            <div class="flex items-center space-x-4">
                                <input type="file" id="hero_background_image" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('hero_background_image').click()" 
                                        class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">Choose Image</button>
                                <button type="button" onclick="removeHeroImage()" 
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Remove</button>
                            </div>
                            <input type="hidden" 
                                   name="content[hero_background_image]" 
                                   id="hero_background_image_url"
                                   value="{{ \App\Models\ContentBlock::get('hero_background_image', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80', 'text', 'homepage') }}">
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleHeroEdit()" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">About Section</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="about_content" class="block text-sm font-medium text-gray-700 mb-2">About Content</label>
                            <textarea name="content[about_content]" 
                                      id="about_content"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_content', '<p>Founded with a vision to bridge the gap between traditional business practices and modern innovation, <strong>Tijarah Holdings</strong> has established itself as a trusted partner for organizations seeking to navigate the complexities of today\'s dynamic marketplace.</p><p>Our diverse portfolio of companies and strategic investments reflects our commitment to fostering growth across multiple sectors, from cutting-edge technology solutions to sustainable business practices.</p>', 'html', 'homepage') }}</textarea>
                        </div>

                        <div>
                            <label for="about_logo" class="block text-sm font-medium text-gray-700">About Section Logo/Image</label>
                            <div class="mt-1 space-y-2">
                                <div class="flex items-center space-x-4">
                                    <input type="file" 
                                           id="about_logo" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                    <button type="button" 
                                            onclick="uploadAboutImage()"
                                            class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm hover:bg-teal-700">Upload</button>
                                </div>
                                <input type="hidden" 
                                       name="content[about_logo]" 
                                       id="about_logo_url"
                                       value="{{ \App\Models\ContentBlock::get('about_logo', '/images/tijarahco_sdn_bhd.png', 'text', 'homepage') }}">
                                <div class="mt-2">
                                    <img id="about_image_preview" 
                                         src="{{ asset(\App\Models\ContentBlock::get('about_logo', 'images/tijarahco_sdn_bhd.png', 'text', 'homepage')) }}" 
                                         alt="About Logo Preview" 
                                         class="w-32 h-32 object-contain rounded border bg-gray-50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Services Section</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="services_title" class="block text-sm font-medium text-gray-700">Services Title</label>
                            <input type="text" 
                                   name="content[services_title]" 
                                   id="services_title"
                                   value="{{ \App\Models\ContentBlock::get('services_title', 'Our Services', 'text', 'homepage') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        
                        <div>
                            <label for="services_description" class="block text-sm font-medium text-gray-700">Services Description</label>
                            <textarea name="content[services_description]" 
                                      id="services_description"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('services_description', '<p>We provide comprehensive business solutions designed to accelerate growth and maximize potential.</p>', 'html', 'homepage') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Contact Section</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="contact_title" class="block text-sm font-medium text-gray-700">Contact Section Title</label>
                            <input type="text" 
                                   name="content[contact_title]" 
                                   id="contact_title"
                                   value="{{ \App\Models\ContentBlock::get('contact_title', 'Get In Touch', 'text', 'homepage') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_address" class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="content[contact_address]" 
                                          id="contact_address"
                                          rows="4"
                                          placeholder="Enter address lines (press Enter for new lines)"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ str_replace('<br>', "\n", \App\Models\ContentBlock::get('contact_address', 'Universiti Islam Sultan Sharif Ali<br>Simpang 347, Jalan Pasar Gadong<br>Bandar Seri Begawan, Brunei', 'html', 'homepage')) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Press Enter to create new lines</p>
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" 
                                       name="content[contact_phone]" 
                                       id="contact_phone"
                                       value="{{ \App\Models\ContentBlock::get('contact_phone', '+673 123 4567', 'text', 'homepage') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" 
                                       name="content[contact_email]" 
                                       id="contact_email"
                                       value="{{ \App\Models\ContentBlock::get('contact_email', 'tijarahco@unissa.edu.bn', 'text', 'homepage') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div>
                                <label for="contact_hours" class="block text-sm font-medium text-gray-700">Business Hours</label>
                                <textarea name="content[contact_hours]" 
                                          id="contact_hours"
                                          rows="2"
                                          placeholder="Enter operating hours (press Enter for new lines)"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">{{ str_replace('<br>', "\n", \App\Models\ContentBlock::get('contact_hours', 'Mon-Thu & Sat<br>9:00am - 4:30pm', 'html', 'homepage')) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Press Enter to create new lines</p>
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
        // CSRF token setup
        fetch.defaults = {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };

        // Image upload functions
        // Visual editing functions
        function toggleHeroEdit() {
            const editForm = document.getElementById('hero_edit_form');
            editForm.classList.toggle('hidden');
        }

        function updateHeroDisplay(field, value) {
            const displayElement = document.getElementById('hero_' + field + '_display');
            if (displayElement) {
                displayElement.textContent = value;
            }
        }

        function removeHeroImage() {
            const urlInput = document.getElementById('hero_background_image_url');
            const preview = document.getElementById('hero_preview');
            
            urlInput.value = '';
            preview.style.backgroundImage = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            alert('Background image removed!');
        }

        // Image upload for hero
        document.getElementById('hero_background_image').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

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
                    document.getElementById('hero_background_image_url').value = result.url;
                    document.getElementById('hero_preview').style.backgroundImage = 'url(' + result.url + ')';
                    alert('Image uploaded successfully!');
                } else {
                    alert('Upload failed: ' + result.message);
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Upload failed. Please try again.');
            }
        });

        async function uploadHeroImage() {
            const fileInput = document.getElementById('hero_background_image');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('Please select an image first');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('{{ route("content.upload.image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    document.getElementById('hero_background_image_url').value = result.url;
                    document.getElementById('hero_image_preview').src = result.url;
                    alert('Image uploaded successfully!');
                } else {
                    alert('Upload failed: ' + result.message);
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Upload failed. Please try again.');
            }
        }

        async function uploadAboutImage() {
            const fileInput = document.getElementById('about_logo');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('Please select an image first');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('{{ route("content.upload.image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    document.getElementById('about_logo_url').value = result.url;
                    document.getElementById('about_image_preview').src = result.url;
                    alert('Image uploaded successfully!');
                } else {
                    alert('Upload failed: ' + result.message);
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Upload failed. Please try again.');
            }
        }

        // Initialize CKEditor for all textarea elements with ckeditor class
        let editors = {}; // Move this to global scope
        
        document.addEventListener('DOMContentLoaded', function() {
            const editorElements = document.querySelectorAll('.ckeditor');

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

            // Form submission (moved inside DOMContentLoaded)
            document.getElementById('homepage-form').addEventListener('submit', async function(e) {
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
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        content: {}
                    };
                    
                    for (const [key, value] of formData.entries()) {
                        // Skip the CSRF token as we're adding it manually
                        if (key === '_token') continue;
                        
                        // Extract content field names from content[field_name] format
                        const match = key.match(/^content\[(.+)\]$/);
                        if (match) {
                            let processedValue = value;
                            // Convert line breaks to <br> tags for specific fields that don't use CKEditor
                            if (match[1] === 'contact_address' || match[1] === 'contact_hours') {
                                processedValue = value.replace(/\n/g, '<br>');
                            }
                            data.content[match[1]] = processedValue;
                        } else {
                            data[key] = value;
                        }
                    }

                    console.log('Sending data:', data); // Debug log

                    const response = await fetch('{{ route("content.homepage.update") }}', {
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
                        // Show success message
                        const successMessage = document.getElementById('success-message');
                        document.getElementById('success-text').textContent = result.message;
                        successMessage.classList.remove('hidden');
                        
                        // Hide success message after 3 seconds
                        setTimeout(() => {
                            successMessage.classList.add('hidden');
                        }, 3000);
                        
                        // Scroll to top to show success message
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        alert('Save failed: ' + (result.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Save error:', error);
                    alert('Save failed. Please check console for details.');
                }

                // Reset button
                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
            });
            
            // Initialize image cropper
            initImageCropper();
        });
    </script>

    <!-- Image Cropper Modal -->
    <div id="cropperModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-4xl max-h-[90vh] overflow-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Crop & Edit Image</h3>
                <button id="closeCropper" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <img id="cropperImage" style="max-width: 100%; max-height: 400px;">
            </div>
            <!-- Preview for circular images -->
            <div id="circularPreview" class="mb-4 text-center" style="display: none;">
                <p class="text-sm text-gray-600 mb-2">Preview (how it will look on your website):</p>
                <div class="inline-block">
                    <img id="previewCircular" class="w-20 h-20 rounded-full object-cover border-2 border-gray-300">
                </div>
            </div>
            <!-- Aspect Ratio Controls -->
            <div class="mb-4 text-center">
                <p class="text-sm text-gray-600 mb-2">Aspect Ratio:</p>
                <div class="flex justify-center gap-2">
                    <button id="aspectSquare" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">1:1 Square</button>
                    <button id="aspectWide" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">16:9 Banner</button>
                    <button id="aspectContent" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">4:3 Content</button>
                    <button id="aspectFree" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">Free Crop</button>
                </div>
            </div>
            
            <!-- Image Controls -->
            <div class="mb-4 text-center border-t pt-4">
                <p class="text-sm text-gray-600 mb-2">Image Controls:</p>
                <div class="flex justify-center gap-2 flex-wrap">
                    <button id="resetCrop" class="px-3 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition-colors">↺ Reset</button>
                    <button id="rotateCrop" class="px-3 py-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition-colors">⟲ Rotate</button>
                    <button id="flipCrop" class="px-3 py-2 bg-purple-500 text-white rounded text-sm hover:bg-purple-600 transition-colors">⇄ Flip</button>
                    <button id="zoomIn" class="px-3 py-2 bg-green-500 text-white rounded text-sm hover:bg-green-600 transition-colors">+ Zoom In</button>
                    <button id="zoomOut" class="px-3 py-2 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600 transition-colors">- Zoom Out</button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-center gap-4 border-t pt-4">
                <button id="cancelCrop" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-medium">
                    Cancel
                </button>
                <button id="applyCrop" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium shadow-lg">
                    ✓ Apply Crop
                </button>
            </div>
        </div>
    </div>

    <!-- Add Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
    let cropper = null;
    let currentFileInput = null;
    let currentPreviewElement = null;

    // Image cropping functionality
    function initImageCropper() {
        const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    currentFileInput = input;
                    
                    // Find the preview element for this input
                    const inputId = input.id;
                    currentPreviewElement = document.getElementById(inputId + '_preview_img') || 
                                          document.querySelector(`img[id*="${inputId}"]`) ||
                                          input.parentElement.querySelector('img') ||
                                          input.closest('.mb-4').querySelector('img');
                    
                    openCropperModal(file);
                }
            });
        });
    }

    function openCropperModal(file) {
        const modal = document.getElementById('cropperModal');
        const image = document.getElementById('cropperImage');
        
        // Determine aspect ratio based on input field type
        let aspectRatio = NaN; // Default: free ratio
        let cropBoxInfo = '';
        
        if (currentFileInput) {
            const inputId = currentFileInput.id;
            const inputName = currentFileInput.name;
            
            // Board member images - square for circular display
            if (inputId.includes('board_member') || inputName.includes('board_member')) {
                aspectRatio = 1; // 1:1 square
                cropBoxInfo = 'Board Member Photo (Square - will display as circle)';
            }
            // Hero/Banner images - wide banner ratio
            else if (inputId.includes('hero') || inputId.includes('banner') || inputName.includes('hero') || inputName.includes('banner')) {
                aspectRatio = 16/9; // 16:9 banner
                cropBoxInfo = 'Hero/Banner Image (16:9 ratio)';
            }
            // Logo images - square
            else if (inputId.includes('logo') || inputName.includes('logo')) {
                aspectRatio = 1; // 1:1 square
                cropBoxInfo = 'Logo Image (Square)';
            }
            // About/content images - 4:3 ratio
            else if (inputId.includes('about') || inputName.includes('about')) {
                aspectRatio = 4/3; // 4:3 content ratio
                cropBoxInfo = 'Content Image (4:3 ratio)';
            }
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            image.src = e.target.result;
            modal.classList.remove('hidden');
            
            // Update modal title with crop info
            const modalTitle = modal.querySelector('h3');
            modalTitle.textContent = cropBoxInfo || 'Crop & Edit Image';
            
            // Show/hide circular preview for board member photos
            const circularPreview = document.getElementById('circularPreview');
            const previewCircular = document.getElementById('previewCircular');
            
            if (aspectRatio === 1 && cropBoxInfo.includes('Board Member')) {
                circularPreview.style.display = 'block';
                previewCircular.src = e.target.result;
            } else {
                circularPreview.style.display = 'none';
            }
            
            // Initialize cropper with shape-specific aspect ratio
            cropper = new Cropper(image, {
                aspectRatio: aspectRatio,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: aspectRatio === NaN, // Only allow resize if free ratio
                toggleDragModeOnDblclick: false,
                minCropBoxWidth: 50,
                minCropBoxHeight: 50,
                responsive: true,
                checkOrientation: false,
                crop: function(event) {
                    // Update circular preview in real-time for board member photos
                    if (aspectRatio === 1 && cropBoxInfo.includes('Board Member')) {
                        const canvas = cropper.getCroppedCanvas({
                            width: 200,
                            height: 200,
                            imageSmoothingEnabled: true,
                            imageSmoothingQuality: 'high'
                        });
                        const previewUrl = canvas.toDataURL('image/jpeg', 0.9);
                        previewCircular.src = previewUrl;
                        
                        // Store the exact canvas and URL for final use
                        cropper.finalCanvas = canvas;
                        cropper.finalPreviewUrl = previewUrl;
                    }
                }
            });
        };
        reader.readAsDataURL(file);
    }

    function closeCropperModal() {
        const modal = document.getElementById('cropperModal');
        modal.classList.add('hidden');
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Reset file input if cancelled
        if (currentFileInput) {
            currentFileInput.value = '';
        }
    }

    function applyCrop() {
        if (!cropper || !currentFileInput) {
            closeCropperModal();
            return;
        }
        
        // Close modal immediately for better UX
        const modal = document.getElementById('cropperModal');
        modal.classList.add('hidden');
        
        try {
            // Use the stored canvas from the preview for perfect consistency
            let canvas;
            if (cropper.finalCanvas) {
                // Use the exact same canvas that was shown in the preview
                canvas = cropper.finalCanvas;
            } else {
                // Fallback to generating a new canvas
                canvas = cropper.getCroppedCanvas({
                    width: 200,
                    height: 200,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });
            }
            
            canvas.toBlob(function(blob) {
                if (!blob) {
                    console.error('Failed to create blob from canvas');
                    return;
                }
                
                try {
                    // Create a new File object
                    const fileName = currentFileInput.files[0].name;
                    const croppedFile = new File([blob], fileName, {
                        type: blob.type,
                        lastModified: Date.now()
                    });
                    
                    // Create a new FileList with the cropped file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(croppedFile);
                    currentFileInput.files = dataTransfer.files;
                    
                    // Use the exact same image data that was shown in the preview
                    let finalImageUrl;
                    if (cropper.finalPreviewUrl) {
                        finalImageUrl = cropper.finalPreviewUrl;
                    } else {
                        finalImageUrl = canvas.toDataURL('image/jpeg', 0.9);
                    }
                    
                    // Update preview if exists
                    if (currentPreviewElement) {
                        currentPreviewElement.src = finalImageUrl;
                        currentPreviewElement.style.display = 'block';
                        
                        // Show preview container if hidden
                        const previewContainer = currentPreviewElement.closest('[id*="preview"]');
                        if (previewContainer) {
                            previewContainer.style.display = 'block';
                        }
                        
                        // Hide current image when new one is uploaded
                        const inputId = currentFileInput.id;
                        if (inputId.includes('board_member')) {
                            const currentImageContainer = currentFileInput.parentElement.querySelector('div.mb-2');
                            if (currentImageContainer && currentImageContainer.querySelector('img')) {
                                currentImageContainer.style.display = 'none';
                            }
                        }
                    }
                    
                    // Trigger change event for any existing preview handlers
                    const changeEvent = new Event('change', { bubbles: true });
                    currentFileInput.dispatchEvent(changeEvent);
                    
                } catch (error) {
                    console.error('Error processing cropped image:', error);
                }
                
            }, 'image/jpeg', 0.9);
            
        } catch (error) {
            console.error('Error in applyCrop:', error);
        }
        
        // Clean up cropper
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Reset variables
        currentFileInput = null;
        currentPreviewElement = null;
    }

    // Event listeners
    document.getElementById('closeCropper').addEventListener('click', closeCropperModal);
    document.getElementById('cancelCrop').addEventListener('click', closeCropperModal);
    document.getElementById('applyCrop').addEventListener('click', applyCrop);

    document.getElementById('resetCrop').addEventListener('click', function() {
        if (cropper) {
            cropper.reset();
        }
    });

    document.getElementById('rotateCrop').addEventListener('click', function() {
        if (cropper) {
            cropper.rotate(90);
        }
    });

    document.getElementById('flipCrop').addEventListener('click', function() {
        if (cropper) {
            const imageData = cropper.getImageData();
            cropper.scaleX(-imageData.scaleX);
        }
    });

    document.getElementById('zoomIn').addEventListener('click', function() {
        if (cropper) {
            cropper.zoom(0.1);
        }
    });

    document.getElementById('zoomOut').addEventListener('click', function() {
        if (cropper) {
            cropper.zoom(-0.1);
        }
    });

    // Aspect ratio control buttons
    document.getElementById('aspectSquare').addEventListener('click', function() {
        if (cropper) {
            cropper.setAspectRatio(1);
        }
    });

    document.getElementById('aspectWide').addEventListener('click', function() {
        if (cropper) {
            cropper.setAspectRatio(16/9);
        }
    });

    document.getElementById('aspectContent').addEventListener('click', function() {
        if (cropper) {
            cropper.setAspectRatio(4/3);
        }
    });

    document.getElementById('aspectFree').addEventListener('click', function() {
        if (cropper) {
            cropper.setAspectRatio(NaN);
        }
    });

    // Close modal when clicking outside
    document.getElementById('cropperModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCropperModal();
        }
    });


    </script>
</body>
</html>