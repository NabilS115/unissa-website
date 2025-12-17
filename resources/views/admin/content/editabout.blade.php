<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Page Content - Admin Panel</title>
    <link rel="icon" href="{{ asset('images/tijarah-co-logo.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/tijarah-co-logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('images/tijarah-co-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .modal {
            z-index: 9999 !important;
        }
        .modal-backdrop {
            z-index: 9998 !important;
        }
        .fixed-buttons {
            z-index: 9997 !important;
        }
        /* Gallery modal button fixes */
        .gallery-modal {
            z-index: 10000 !important;
        }
        .gallery-modal .btn {
            z-index: 10001 !important;
            position: relative !important;
        }
        /* Ensure buttons in modals don't overlap */
        .modal .btn, .modal button {
            z-index: 10001 !important;
            position: relative !important;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Edit About Page Content</h1>
                            <p class="mt-1 text-sm text-gray-600">Customize the content on your about page</p>
                        </div>
                        <a href="{{ url('/company-history') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to About Page
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
            <form id="about-form" class="space-y-8">
                @csrf
                
                <!-- Page Header Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Page Header</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="about_title" class="block text-sm font-medium text-gray-700">Page Title</label>
                            <input type="text" 
                                   name="content[about_title]" 
                                   id="about_title"
                                   value="{{ \App\Models\ContentBlock::get('about_title', 'Our Story & Values', 'text', 'about') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        
                        <div>
                            <label for="about_subtitle" class="block text-sm font-medium text-gray-700">Page Subtitle</label>
                            <input type="text" 
                                   name="content[about_subtitle]" 
                                   id="about_subtitle"
                                   value="{{ \App\Models\ContentBlock::get('about_subtitle', 'Discover our journey and what drives us forward', 'text', 'about') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Board Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Board of Directors Section</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="board_title" class="block text-sm font-medium text-gray-700">Section Title</label>
                            <input type="text" 
                                   name="content[board_title]" 
                                   id="board_title"
                                   value="{{ \App\Models\ContentBlock::get('board_title', 'Board of Directors', 'text', 'about') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        <div>
                            <label for="board_subtitle" class="block text-sm font-medium text-gray-700">Section Subtitle</label>
                            <input type="text" 
                                   name="content[board_subtitle]" 
                                   id="board_subtitle"
                                   value="{{ \App\Models\ContentBlock::get('board_subtitle', 'Meet the visionary leaders driving our company forward', 'text', 'about') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Board Members Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Board Members</h2>
                    </div>
                    <div class="px-6 py-4 space-y-8">
                        <!-- Board Member 1 -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-md font-medium text-gray-800 mb-4">Board Member 1 (Chairman)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="board_member1_name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" 
                                           name="content[board_member1_name]" 
                                           id="board_member1_name"
                                           value="{{ \App\Models\ContentBlock::get('board_member1_name', 'Dato\' Chairman', 'text', 'about') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member1_title" class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" 
                                           name="content[board_member1_title]" 
                                           id="board_member1_title"
                                           value="{{ \App\Models\ContentBlock::get('board_member1_title', 'Chairman & Founder', 'text', 'about') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member1_initials" class="block text-sm font-medium text-gray-700">Initials (shown if no photo)</label>
                                    <input type="text" 
                                           name="content[board_member1_initials]" 
                                           id="board_member1_initials"
                                           value="{{ \App\Models\ContentBlock::get('board_member1_initials', 'DC', 'text', 'about') }}"
                                           maxlength="3"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member1_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                                    <input type="file" 
                                           id="board_member1_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <input type="hidden" name="content[board_member1_image]" id="board_member1_image_url" value="{{ \App\Models\ContentBlock::get('board_member1_image', '', 'text', 'about') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Board Member 2 -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-md font-medium text-gray-800 mb-4">Board Member 2 (CEO)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="board_member2_name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" 
                                           name="content[board_member2_name]" 
                                           id="board_member2_name"
                                           value="{{ \App\Models\ContentBlock::get('board_member2_name', 'Md. Saiful', 'text', 'about') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member2_title" class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" 
                                           name="content[board_member2_title]" 
                                           id="board_member2_title"
                                           value="{{ \App\Models\ContentBlock::get('board_member2_title', 'Chief Executive Officer', 'text', 'about') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member2_initials" class="block text-sm font-medium text-gray-700">Initials (shown if no photo)</label>
                                    <input type="text" 
                                           name="content[board_member2_initials]" 
                                           id="board_member2_initials"
                                           value="{{ \App\Models\ContentBlock::get('board_member2_initials', 'MS', 'text', 'about') }}"
                                           maxlength="3"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member2_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                                    <input type="file" 
                                           id="board_member2_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                    <input type="hidden" name="content[board_member2_image]" id="board_member2_image_url" value="{{ \App\Models\ContentBlock::get('board_member2_image', '', 'text', 'about') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Board Member 3 -->
                        <div>
                            <h3 class="text-md font-medium text-gray-800 mb-4">Board Member 3 (CFO)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="board_member3_name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" 
                                           name="content[board_member3_name]" 
                                           id="board_member3_name"
                                           value="{{ \App\Models\ContentBlock::get('board_member3_name', 'Ahmad Farid', 'text', 'about') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member3_title" class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" 
                                           name="content[board_member3_title]" 
                                           id="board_member3_title"
                                           value="{{ \App\Models\ContentBlock::get('board_member3_title', 'Chief Financial Officer', 'text', 'about') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member3_initials" class="block text-sm font-medium text-gray-700">Initials (shown if no photo)</label>
                                    <input type="text" 
                                           name="content[board_member3_initials]" 
                                           id="board_member3_initials"
                                           value="{{ \App\Models\ContentBlock::get('board_member3_initials', 'AF', 'text', 'about') }}"
                                           maxlength="3"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                <div>
                                    <label for="board_member3_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                                    <input type="file" 
                                           id="board_member3_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    <input type="hidden" name="content[board_member3_image]" id="board_member3_image_url" value="{{ \App\Models\ContentBlock::get('board_member3_image', '', 'text', 'about') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Overview Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Company Overview</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="about_overview" class="block text-sm font-medium text-gray-700">Overview Content</label>
                            <textarea name="content[about_overview]" 
                                      id="about_overview"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_overview', '<p>Founded with a vision to bridge the gap between traditional business practices and modern innovation, <strong>Tijarah Co</strong> has established itself as a trusted partner for organizations seeking to navigate the complexities of today\'s dynamic marketplace.</p>', 'html', 'about') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Mission & Vision Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Mission & Vision</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="about_mission" class="block text-sm font-medium text-gray-700">Mission Statement</label>
                            <textarea name="content[about_mission]" 
                                      id="about_mission"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_mission', '<p>To empower businesses through innovative solutions, ethical practices, and sustainable growth strategies that create lasting value for all stakeholders.</p>', 'html', 'about') }}</textarea>
                        </div>
                        
                        <div>
                            <label for="about_vision" class="block text-sm font-medium text-gray-700">Vision Statement</label>
                            <textarea name="content[about_vision]" 
                                      id="about_vision"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_vision', '<p>To be the leading catalyst for business transformation in the region, fostering a community where tradition meets innovation.</p>', 'html', 'about') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Company Values Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Company Values</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="about_values" class="block text-sm font-medium text-gray-700">Values Content</label>
                            <textarea name="content[about_values]" 
                                      id="about_values"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_values', '<ul><li><strong>Integrity:</strong> We operate with transparency and honesty in all our dealings</li><li><strong>Innovation:</strong> We embrace new ideas and technologies to drive progress</li><li><strong>Excellence:</strong> We strive for the highest standards in everything we do</li><li><strong>Collaboration:</strong> We believe in the power of partnership and teamwork</li></ul>', 'html', 'about') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Company History Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Company History</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="about_history" class="block text-sm font-medium text-gray-700">History Content</label>
                            <textarea name="content[about_history]" 
                                      id="about_history"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_history', '<p>Our journey began with a simple yet powerful vision: to create meaningful connections between businesses, communities, and opportunities. Over the years, we have grown from a startup with big dreams to a respected player in the business ecosystem.</p>', 'html', 'about') }}</textarea>
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
            document.getElementById('about-form').addEventListener('submit', async function(e) {
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

                    const response = await fetch('{{ route("content.about.update") }}', {
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