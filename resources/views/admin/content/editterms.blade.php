<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Terms of Service - Admin Panel</title>
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
                
                <!-- Terms of Service Header Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Terms of Service Header</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="terms_title" class="block text-sm font-medium text-gray-700">Page Title</label>
                            <input type="text" 
                                   name="content[terms_title]" 
                                   id="terms_title"
                                   value="{{ \App\Models\ContentBlock::get('terms_title', 'Terms of Service', 'text', 'terms') }}"
                                   class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        
                        <div>
                            <label for="terms_subtitle" class="block text-sm font-medium text-gray-700">Page Subtitle</label>
                            <input type="text" 
                                   name="content[terms_subtitle]" 
                                   id="terms_subtitle"
                                   value="{{ \App\Models\ContentBlock::get('terms_subtitle', 'Terms and conditions of use', 'text', 'terms') }}"
                                   class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Terms of Service Content Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Terms of Service Content</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="terms_content" class="block text-sm font-medium text-gray-700">Terms of Service Full Content</label>
                            <textarea name="content[terms_content]" 
                                      id="terms_content"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('terms_content', '<h2>Acceptance of Terms</h2><p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p><h2>Use License</h2><p>Permission is granted to temporarily download one copy of the materials on our website for personal, non-commercial transitory viewing only.</p><h2>Disclaimer</h2><p>The materials on our website are provided on an "as is" basis. We make no warranties, expressed or implied.</p>', 'html', 'terms') }}</textarea>
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
                            <label for="terms_last_updated" class="block text-sm font-medium text-gray-700">Last Updated Date</label>
                            <input type="text" 
                                   name="content[terms_last_updated]" 
                                   id="terms_last_updated"
                                   value="{{ \App\Models\ContentBlock::get('terms_last_updated', date('F j, Y'), 'text', 'terms') }}"
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
            document.getElementById('terms-form').addEventListener('submit', async function(e) {
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
                            data.content[match[1]] = value;
                        } else {
                            data[key] = value;
                        }
                    }

                    const response = await fetch('{{ route("content.terms.update") }}', {
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