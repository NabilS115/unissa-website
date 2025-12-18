<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Privacy Policy - Admin Panel</title>
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
                
                <!-- Privacy Policy Header Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Privacy Policy Header</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="privacy_title" class="block text-sm font-medium text-gray-700">Page Title</label>
                            <input type="text" 
                                   name="content[privacy_title]" 
                                   id="privacy_title"
                                   value="{{ \App\Models\ContentBlock::get('privacy_title', 'Privacy Policy', 'text', 'privacy') }}"
                                   class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        
                        <div>
                            <label for="privacy_subtitle" class="block text-sm font-medium text-gray-700">Page Subtitle</label>
                            <input type="text" 
                                   name="content[privacy_subtitle]" 
                                   id="privacy_subtitle"
                                   value="{{ \App\Models\ContentBlock::get('privacy_subtitle', 'How we protect your information', 'text', 'privacy') }}"
                                   class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Privacy Policy Content Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Privacy Policy Content</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="privacy_content" class="block text-sm font-medium text-gray-700">Privacy Policy Full Content</label>
                            <textarea name="content[privacy_content]" 
                                      id="privacy_content"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('privacy_content', '<h2>Information We Collect</h2><p>We collect information you provide directly to us, such as when you create an account, use our services, or contact us.</p><h2>How We Use Information</h2><p>We use the information we collect to provide, maintain, and improve our services.</p><h2>Information Sharing</h2><p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent.</p>', 'html', 'privacy') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Last Updated Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Last Updated</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="privacy_last_updated" class="block text-sm font-medium text-gray-700">Last Updated Date</label>
                            <input type="text" 
                                   name="content[privacy_last_updated]" 
                                   id="privacy_last_updated"
                                   value="{{ \App\Models\ContentBlock::get('privacy_last_updated', date('F j, Y'), 'text', 'privacy') }}"
                                   class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   placeholder="e.g., December 17, 2024">
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
            document.getElementById('privacy-form').addEventListener('submit', async function(e) {
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
                        const match = key.match(/^content\[(.+)\]$/);
                        if (match) {
                            data.content[match[1]] = value;
                        } else {
                            data[key] = value;
                        }
                    }

                    const response = await fetch('{{ route("content.privacy.update") }}', {
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
            
            // Initialize cropper with enhanced options
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
                    // Update circular preview in real-time for board member photos using exact same crop data
                    if (aspectRatio === 1 && cropBoxInfo.includes('Board Member')) {
                        // Store the current crop data for consistency
                        const cropData = cropper.getData();
                        const canvas = cropper.getCroppedCanvas({
                            width: 80,
                            height: 80,
                            imageSmoothingEnabled: true,
                            imageSmoothingQuality: 'high'
                        });
                        previewCircular.src = canvas.toDataURL('image/jpeg', 0.9);
                        
                        // Store crop data for later use in applyCrop
                        cropper.currentCropData = cropData;
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
            const canvas = cropper.getCroppedCanvas({
                maxWidth: 1200,
                maxHeight: 1200,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
            
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
                    
                    // Create final image URL with consistent settings
                    const finalImageUrl = canvas.toDataURL('image/jpeg', 0.9);
                    
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

    // Close modal when clicking outside
    document.getElementById('cropperModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCropperModal();
        }
    });


    </script>
</body>
</html>