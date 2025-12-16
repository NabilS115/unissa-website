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
                        content: {}
                    };
                    
                    for (const [key, value] of formData.entries()) {
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
                        
                        // Scroll to top
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