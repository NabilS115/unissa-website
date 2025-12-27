<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Printing Services Page - Admin Panel</title>
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
                            <h1 class="text-2xl font-bold text-gray-900">Edit Printing Services Page Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize the content on your printing services page</p>
                        </div>
                        <a href="{{ route('printing.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Printing Page
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
            <form id="printing-form" class="space-y-8">
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
                            <h1 class="text-3xl md:text-4xl font-bold mb-4" id="hero_title_display">{{ \App\Models\ContentBlock::get('printing_hero_title', '🖨️ Printing Services', 'text', 'printing') }}</h1>
                            <p class="text-lg mb-6" id="hero_subtitle_display">{{ strip_tags(\App\Models\ContentBlock::get('printing_hero_subtitle', 'Professional printing services at UNISSA Cafe. Upload your documents, photos, or presentations and get high-quality prints while you enjoy our cafe!', 'text', 'printing')) }}</p>
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
                                   name="content[printing_hero_title]" 
                                   id="hero_title_input"
                                   value="{{ \App\Models\ContentBlock::get('printing_hero_title', '🖨️ Printing Services', 'text', 'printing') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateHeroDisplay('title', this.value)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <textarea name="content[printing_hero_subtitle]" 
                                      id="hero_subtitle_input"
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      onchange="updateHeroDisplay('subtitle', this.value)">{{ \App\Models\ContentBlock::get('printing_hero_subtitle', 'Professional printing services at UNISSA Cafe. Upload your documents, photos, or presentations and get high-quality prints while you enjoy our cafe!', 'text', 'printing') }}</textarea>
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

                <!-- Pricing Section -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Pricing Configuration</h2>
                        <p class="text-sm text-gray-600">Set prices for different printing options</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Black & White Prices -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Black & White Printing</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Printing (B$)</label>
                                    <input type="number" 
                                           step="0.01"
                                           name="content[price_bw_printing]" 
                                           value="{{ \App\Models\ContentBlock::get('price_bw_printing', '0.30', 'text', 'printing') }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Photocopy (B$)</label>
                                    <input type="number" 
                                           step="0.01"
                                           name="content[price_bw_photocopy]" 
                                           value="{{ \App\Models\ContentBlock::get('price_bw_photocopy', '0.20', 'text', 'printing') }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                            </div>
                        </div>

                        <!-- Color Prices -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Color Printing</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Printing (B$)</label>
                                    <input type="number" 
                                           step="0.01"
                                           name="content[price_color_printing]" 
                                           value="{{ \App\Models\ContentBlock::get('price_color_printing', '0.80', 'text', 'printing') }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Photocopy (B$)</label>
                                    <input type="number" 
                                           step="0.01"
                                           name="content[price_color_photocopy]" 
                                           value="{{ \App\Models\ContentBlock::get('price_color_photocopy', '1.00', 'text', 'printing') }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Limits -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Limits</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum File Size (MB)</label>
                                    <input type="number" 
                                           name="content[max_file_size]" 
                                           value="{{ \App\Models\ContentBlock::get('max_file_size', '10', 'text', 'printing') }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Copies per Job</label>
                                    <input type="number" 
                                           name="content[max_copies]" 
                                           value="{{ \App\Models\ContentBlock::get('max_copies', '100', 'text', 'printing') }}"
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions Section -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Instructions & Help Text</h2>
                        <p class="text-sm text-gray-600">Customize instructions and help text for users</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Instructions</label>
                            <textarea name="content[upload_instructions]" 
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      placeholder="Drop your files here or click to browse">{{ \App\Models\ContentBlock::get('upload_instructions', 'Drop your files here or click to browse', 'text', 'printing') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supported Formats Text</label>
                            <textarea name="content[supported_formats]" 
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      placeholder="Supported: PDF, DOC, DOCX, JPG, PNG (max 10MB)">{{ \App\Models\ContentBlock::get('supported_formats', 'Supported: PDF, DOC, DOCX, JPG, PNG (max 10MB)', 'text', 'printing') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Login Required Message</label>
                            <textarea name="content[login_required_message]" 
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      placeholder="Please login to use our printing services.">{{ \App\Models\ContentBlock::get('login_required_message', 'Please login to use our printing services.', 'text', 'printing') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-end">
                        <button type="submit" 
                                id="save-btn"
                                class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="save-text">Save Changes</span>
                            <span id="save-loading" class="hidden">
                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Include Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        // Hero editing functions
        function toggleHeroEdit() {
            const preview = document.getElementById('hero_preview');
            const editForm = document.getElementById('hero_edit_form');
            
            if (editForm.classList.contains('hidden')) {
                editForm.classList.remove('hidden');
                preview.classList.add('opacity-50');
            } else {
                editForm.classList.add('hidden');
                preview.classList.remove('opacity-50');
            }
        }

        function updateHeroDisplay(field, value) {
            if (field === 'title') {
                document.getElementById('hero_title_display').textContent = value;
            } else if (field === 'subtitle') {
                document.getElementById('hero_subtitle_display').textContent = value;
            }
        }

        // Form submission
        document.getElementById('printing-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const saveBtn = document.getElementById('save-btn');
            const saveText = document.getElementById('save-text');
            const saveLoading = document.getElementById('save-loading');
            
            saveBtn.disabled = true;
            saveText.classList.add('hidden');
            saveLoading.classList.remove('hidden');

            try {
                const formData = new FormData(this);
                const response = await fetch('{{ route("content.printing.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('success-message').classList.remove('hidden');
                    // Scroll to top to show success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    setTimeout(() => {
                        document.getElementById('success-message').classList.add('hidden');
                    }, 3000);
                } else {
                    alert('Error saving content: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                alert('Error saving content: ' + error.message);
            } finally {
                saveBtn.disabled = false;
                saveText.classList.remove('hidden');
                saveLoading.classList.add('hidden');
            }
        });
    </script>
</body>
</html>