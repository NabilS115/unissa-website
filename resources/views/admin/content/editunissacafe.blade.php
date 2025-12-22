<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit UNISSA Cafe Homepage - Admin Panel</title>
    <link rel="icon" href="/unissa-favicon.ico?v={{ time() }}" type="image/x-icon" sizes="32x32">
    <link rel="shortcut icon" href="/unissa-favicon.ico?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="/unissa-favicon.ico?v={{ time() }}">
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
                            <h1 class="text-2xl font-bold text-gray-900">Edit UNISSA Cafe Homepage Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize the content on your UNISSA Cafe homepage</p>
                        </div>
                        <a href="{{ route('unissa-cafe.homepage') }}" 
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
            <form id="unissacafe-form" class="space-y-8">
                @csrf
                
                <!-- Hero Section -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Hero Section</h2>
                        <p class="text-sm text-gray-600">Click on the preview to edit</p>
                    </div>
                    
                    <!-- Hero Preview -->
                    <div class="relative bg-gradient-to-br from-teal-600 via-emerald-600 to-cyan-600 h-64 cursor-pointer flex items-center justify-center" 
                         id="hero_preview"
                         onclick="toggleHeroEdit()">
                        <div class="text-center text-white px-4">
                            <h1 class="text-3xl md:text-4xl font-bold mb-4" id="hero_title_display">{{ \App\Models\ContentBlock::get('hero_title', 'Welcome to UNISSA Cafe', 'text', 'unissa-cafe') }}</h1>
                            <p class="text-lg mb-6" id="hero_subtitle_display">{{ strip_tags(\App\Models\ContentBlock::get('hero_subtitle', 'Experience the perfect blend of delicious cuisine and premium quality. Where every bite tells a story and every product reflects excellence.', 'text', 'unissa-cafe')) }}</p>
                            <div class="flex items-center justify-center text-sm text-white/80">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Click to edit hero section
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
                                   value="{{ \App\Models\ContentBlock::get('hero_title', 'Welcome to UNISSA Cafe', 'text', 'unissa-cafe') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateHeroDisplay('title', this.value)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <textarea name="content[hero_subtitle]" 
                                      id="hero_subtitle_input"
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      onchange="updateHeroDisplay('subtitle', this.value)">{{ \App\Models\ContentBlock::get('hero_subtitle', 'Experience the perfect blend of delicious cuisine and premium quality. Where every bite tells a story and every product reflects excellence.', 'text', 'unissa-cafe') }}</textarea>
                        </div>
                        
                        <div class="flex justify-between">
                            <button type="button" onclick="toggleHeroEdit()" class="text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel Edit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">About Section</h2>
                        <p class="text-sm text-gray-600">Click on the preview to edit</p>
                    </div>
                    
                    <!-- About Preview -->
                    <div class="p-6 cursor-pointer hover:bg-gray-50 transition-colors" 
                         id="about_preview"
                         onclick="toggleAboutEdit()">
                        <div class="mb-4">
                            <h2 class="text-3xl font-bold text-teal-700" id="about_title_display">{{ \App\Models\ContentBlock::get('about_title', 'About UNISSA Cafe', 'text', 'unissa-cafe') }}</h2>
                        </div>
                        <div class="text-gray-700 text-lg leading-relaxed mb-4" id="about_description_display">
                            {!! \App\Models\ContentBlock::get('about_description', '<p>Discover our carefully curated selection of mouth-watering food and high-quality merchandise. From artisan pizzas and fresh salads to exclusive branded items, UNISSA Cafe offers an unforgettable experience that combines great taste with premium quality.</p>', 'html', 'unissa-cafe') !!}
                        </div>
                        <div class="flex items-center justify-center text-sm text-gray-500 mt-4">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Click to edit about section
                        </div>
                    </div>
                    
                    <!-- About Edit Form (Hidden) -->
                    <div id="about_edit_form" class="hidden p-6 bg-gray-50 border-t space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">About Title</label>
                            <input type="text" 
                                   name="content[about_title]" 
                                   id="about_title_input"
                                   value="{{ \App\Models\ContentBlock::get('about_title', 'About UNISSA Cafe', 'text', 'unissa-cafe') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateAboutDisplay('title', this.value)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">About Description</label>
                            <textarea name="content[about_description]" 
                                      id="about_description_input"
                                      class="ckeditor-about">{{ \App\Models\ContentBlock::get('about_description', '<p>Discover our carefully curated selection of mouth-watering food and high-quality merchandise. From artisan pizzas and fresh salads to exclusive branded items, UNISSA Cafe offers an unforgettable experience that combines great taste with premium quality.</p>', 'html', 'unissa-cafe') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">About Section Image</label>
                            <div class="flex items-center space-x-4">
                                <input type="file" 
                                       id="about_image_file" 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                <button type="button" 
                                        onclick="uploadImage('about_image')"
                                        class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm hover:bg-teal-700">Upload</button>
                            </div>
                            <input type="hidden" 
                                   name="content[about_image]" 
                                   id="about_image_url"
                                   value="{{ \App\Models\ContentBlock::get('about_image', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80', 'text', 'unissa-cafe') }}">
                            <div class="mt-2">
                                <img id="about_image_preview" 
                                     src="{{ \App\Models\ContentBlock::get('about_image', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80', 'text', 'unissa-cafe') }}" 
                                     alt="About Image Preview" 
                                     class="w-48 h-32 object-cover rounded border bg-gray-50">
                            </div>
                        </div>
                        
                        <div class="flex justify-between">
                            <button type="button" onclick="toggleAboutEdit()" class="text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel Edit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Button Labels Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Navigation Button Labels</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="food_button_text" class="block text-sm font-medium text-gray-700">Food & Beverages Button Text</label>
                            <input type="text" 
                                   name="content[food_button_text]" 
                                   id="food_button_text"
                                   value="{{ \App\Models\ContentBlock::get('food_button_text', 'Browse Food & Beverages', 'text', 'unissa-cafe') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        
                        <div>
                            <label for="merchandise_button_text" class="block text-sm font-medium text-gray-700">Merchandise Button Text</label>
                            <input type="text" 
                                   name="content[merchandise_button_text]" 
                                   id="merchandise_button_text"
                                   value="{{ \App\Models\ContentBlock::get('merchandise_button_text', 'Shop Merchandise', 'text', 'unissa-cafe') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        
                        <div>
                            <label for="others_button_text" class="block text-sm font-medium text-gray-700">Others Button Text</label>
                            <input type="text" 
                                   name="content[others_button_text]" 
                                   id="others_button_text"
                                   value="{{ \App\Models\ContentBlock::get('others_button_text', 'Explore Others', 'text', 'unissa-cafe') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
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

    <!-- Cropper Modal -->
    <div id="cropperModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Crop Image</h3>
                <button id="closeCropper" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 p-4 overflow-hidden">
                <div class="max-h-96 flex items-center justify-center">
                    <img id="cropImage" class="max-w-full max-h-full" style="max-height: 400px;">
                </div>
            </div>
            
            <div class="p-4 border-t bg-gray-50">
                <div class="mb-4 text-center">
                    <p class="text-sm text-gray-600 mb-3">Choose aspect ratio:</p>
                    <div class="flex justify-center gap-2">
                        <button id="aspectSquare" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">1:1 Square</button>
                        <button id="aspectWide" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">16:9 Banner</button>
                        <button id="aspectContent" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">4:3 Content</button>
                        <button id="aspectFree" class="px-3 py-2 bg-indigo-500 text-white rounded text-sm hover:bg-indigo-600 transition-colors">Free Crop</button>
                    </div>
                </div>
                
                <div class="mb-4 text-center border-t pt-4">
                    <p class="text-sm text-gray-600 mb-2">Image Controls:</p>
                    <div class="flex justify-center gap-2">
                        <button id="rotateCrop" class="px-3 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition-colors">↻ Rotate</button>
                        <button id="flipCrop" class="px-3 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition-colors">↔ Flip</button>
                        <button id="resetCrop" class="px-3 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition-colors">Reset</button>
                        <button id="zoomIn" class="px-3 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition-colors">+ Zoom</button>
                        <button id="zoomOut" class="px-3 py-2 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition-colors">- Zoom</button>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button id="cancelCrop" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                    <button id="applyCrop" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition-colors">Apply Crop</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        let editors = {};
        let cropper = null;
        let currentImageField = null;
        let currentFileInput = null;
        let currentPreviewElement = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor
            document.querySelectorAll('.ckeditor-about').forEach(function(textarea) {
                ClassicEditor
                    .create(textarea, {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo']
                    })
                    .then(editor => {
                        editors['content[about_description]'] = editor;
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor:', error);
                    });
            });

            // Form submission
            document.getElementById('unissacafe-form').addEventListener('submit', async function(e) {
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
                        
                        // Extract the content field name from content[field_name] format
                        if (key.startsWith('content[') && key.endsWith(']')) {
                            const fieldName = key.slice(8, -1); // Remove 'content[' and ']'
                            data.content[fieldName] = value;
                        }
                    }

                    const response = await fetch('{{ route("content.unissa-cafe.update") }}', {
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
                        // Update displays with new content
                        updateDisplays();
                        // Scroll to top to show success message
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        alert('Error: ' + (result.message || 'Failed to save changes'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while saving changes.');
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

        function toggleHeroEdit() {
            const editForm = document.getElementById('hero_edit_form');
            editForm.classList.toggle('hidden');
        }

        function toggleAboutEdit() {
            const editForm = document.getElementById('about_edit_form');
            editForm.classList.toggle('hidden');
        }

        function updateHeroDisplay(type, value) {
            if (type === 'title') {
                const display = document.getElementById('hero_title_display');
                display.textContent = value;
            } else if (type === 'subtitle') {
                document.getElementById('hero_subtitle_display').textContent = value;
            }
        }

        function updateAboutDisplay(type, value) {
            if (type === 'title') {
                document.getElementById('about_title_display').textContent = value;
            }
        }

        function updateDisplays() {
            // Update hero displays
            const heroTitle = document.getElementById('hero_title_input').value;
            const heroSubtitle = document.getElementById('hero_subtitle_input').value;
            updateHeroDisplay('title', heroTitle);
            updateHeroDisplay('subtitle', heroSubtitle);

            // Update about displays
            const aboutTitle = document.getElementById('about_title_input').value;
            updateAboutDisplay('title', aboutTitle);
            
            if (editors['content[about_description]']) {
                document.getElementById('about_description_display').innerHTML = editors['content[about_description]'].getData();
            }
        }

        function uploadImage(fieldName) {
            const fileInput = document.getElementById(fieldName + '_file');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('Please select an image first.');
                return;
            }
            
            currentImageField = fieldName;
            currentFileInput = fileInput;
            currentPreviewElement = document.getElementById(fieldName + '_preview');
            
            // Show crop modal
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cropImage').src = e.target.result;
                document.getElementById('cropperModal').classList.remove('hidden');
                initCropper();
            };
            reader.readAsDataURL(file);
        }

        function initCropper() {
            const image = document.getElementById('cropImage');
            
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(image, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                background: false,
            });
        }

        function closeCropperModal() {
            document.getElementById('cropperModal').classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            currentImageField = null;
            currentFileInput = null;
            currentPreviewElement = null;
        }

        function applyCrop() {
            if (!cropper) return;
            
            const canvas = cropper.getCroppedCanvas({
                maxWidth: 800,
                maxHeight: 600,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            
            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('image', blob, 'cropped-image.jpg');
                
                fetch('{{ route("content.upload.image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentPreviewElement.src = data.url;
                        document.getElementById(currentImageField + '_url').value = data.url;
                        closeCropperModal();
                        showSuccessMessage('Image uploaded successfully!');
                    } else {
                        alert('Failed to upload image.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while uploading the image.');
                });
            }, 'image/jpeg', 0.9);
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