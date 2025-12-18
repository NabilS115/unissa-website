<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Page Content - Admin Panel</title>
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
                
                <!-- Hero Section Preview -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Hero Section</h2>
                        <p class="text-sm text-gray-600">Click on the preview to edit</p>
                    </div>
                    
                    <!-- Hero Preview -->
                    <div class="relative bg-cover bg-center h-64 cursor-pointer" 
                         id="about_hero_preview"
                         style="background-image: url('{{ \App\Models\ContentBlock::get('about_hero_image', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1600&q=80', 'text', 'about') }}');"
                         onclick="toggleAboutHeroEdit()">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <div class="text-center text-white">
                                <h1 class="text-3xl md:text-5xl font-bold mb-4" id="about_hero_title_display">{{ \App\Models\ContentBlock::get('about_hero_title', 'About Our Company', 'text', 'about') }}</h1>
                                <p class="text-lg md:text-xl mb-6" id="about_hero_subtitle_display">{{ \App\Models\ContentBlock::get('about_hero_subtitle', 'Discover our journey, values, and commitment to excellence', 'text', 'about') }}</p>
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
                    <div id="about_hero_edit_form" class="hidden p-6 bg-gray-50 border-t space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" 
                                   name="content[about_hero_title]" 
                                   id="about_hero_title_input"
                                   value="{{ \App\Models\ContentBlock::get('about_hero_title', 'About Our Company', 'text', 'about') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateAboutHeroDisplay('title', this.value)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <textarea name="content[about_hero_subtitle]" 
                                      id="about_hero_subtitle_input"
                                      rows="3"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      onchange="updateAboutHeroDisplay('subtitle', this.value)">{{ \App\Models\ContentBlock::get('about_hero_subtitle', 'Discover our journey, values, and commitment to excellence', 'text', 'about') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                            <div class="flex items-center space-x-4">
                                <input type="file" id="about_hero_background_image" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('about_hero_background_image').click()" 
                                        class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">Choose Image</button>
                                <button type="button" onclick="removeAboutHeroImage()" 
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Remove</button>
                            </div>
                            <input type="hidden" 
                                   name="content[about_hero_image]" 
                                   id="about_hero_background_image_url"
                                   value="{{ \App\Models\ContentBlock::get('about_hero_image', '', 'text', 'about') }}">
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleAboutHeroEdit()" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                </div>

                <!-- Board Members Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Board Members</h2>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <!-- Board Title and Subtitle -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="board_title" class="block text-sm font-medium text-gray-700">Board Section Title</label>
                                <input type="text" 
                                       name="content[board_title]" 
                                       id="board_title"
                                       value="{{ \App\Models\ContentBlock::get('board_title', 'Board of Directors', 'text', 'about') }}"
                                       class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>
                            <div>
                                <label for="board_subtitle" class="block text-sm font-medium text-gray-700">Board Section Subtitle</label>
                                <input type="text" 
                                       name="content[board_subtitle]" 
                                       id="board_subtitle"
                                       value="{{ \App\Models\ContentBlock::get('board_subtitle', 'Meet the visionary leaders driving our company forward', 'text', 'about') }}"
                                       class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                            </div>
                        </div>
                        
                        <!-- Board Member 1 -->
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h3 class="text-md font-medium text-gray-900 mb-3">Board Member 1</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="board_member1_name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" 
                                           name="content[board_member1_name]" 
                                           id="board_member1_name"
                                           value="{{ \App\Models\ContentBlock::get('board_member1_name', '', 'text', 'about') }}"
                                           class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="board_member1_position" class="block text-sm font-medium text-gray-700">Position</label>
                                    <input type="text" 
                                           name="content[board_member1_position]" 
                                           id="board_member1_position"
                                           value="{{ \App\Models\ContentBlock::get('board_member1_position', '', 'text', 'about') }}"
                                           class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="board_member1_bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                    <textarea name="content[board_member1_bio]" 
                                              id="board_member1_bio"
                                              class="ckeditor">{{ \App\Models\ContentBlock::get('board_member1_bio', '', 'html', 'about') }}</textarea>
                                </div>

                                <div>
                                    <label for="board_member1_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                                    @php $currentImage1 = \App\Models\ContentBlock::get('board_member1_image', '', 'text', 'about'); @endphp
                                    @if($currentImage1)
                                        <div class="mb-2">
                                            <img src="{{ $currentImage1 }}" class="w-20 h-20 object-cover rounded-full border-2 border-gray-300" alt="Current Image">
                                            <div class="flex items-center gap-2 mt-1">
                                                <p class="text-sm text-gray-600">Current image</p>
                                                <button type="button" onclick="removeExistingBoardMemberImage(1)" class="text-xs text-red-600 hover:text-red-700 underline">Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" 
                                           name="board_member1_image"
                                           id="board_member1_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <input type="hidden" name="content[board_member1_image]" id="board_member1_image_url" value="{{ \App\Models\ContentBlock::get('board_member1_image', '', 'text', 'about') }}">
                                    <div id="board_member1_preview" class="mt-2" style="display: none;">
                                        <img id="board_member1_preview_img" class="w-20 h-20 object-cover rounded-full border-2 border-gray-300" alt="Preview">
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-sm text-gray-600">New cropped image</p>
                                            <button type="button" onclick="removeBoardMemberImage(1)" class="text-xs text-red-600 hover:text-red-700 underline">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Board Member 2 -->
                        <div class="border-l-4 border-teal-500 pl-4">
                            <h3 class="text-md font-medium text-gray-900 mb-3">Board Member 2</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="board_member2_name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" 
                                           name="content[board_member2_name]" 
                                           id="board_member2_name"
                                           value="{{ \App\Models\ContentBlock::get('board_member2_name', '', 'text', 'about') }}"
                                           class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                
                                <div>
                                    <label for="board_member2_position" class="block text-sm font-medium text-gray-700">Position</label>
                                    <input type="text" 
                                           name="content[board_member2_position]" 
                                           id="board_member2_position"
                                           value="{{ \App\Models\ContentBlock::get('board_member2_position', '', 'text', 'about') }}"
                                           class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="board_member2_bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                    <textarea name="content[board_member2_bio]" 
                                              id="board_member2_bio"
                                              class="ckeditor">{{ \App\Models\ContentBlock::get('board_member2_bio', '', 'html', 'about') }}</textarea>
                                </div>

                                <div>
                                    <label for="board_member2_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                                    @php $currentImage2 = \App\Models\ContentBlock::get('board_member2_image', '', 'text', 'about'); @endphp
                                    @if($currentImage2)
                                        <div class="mb-2">
                                            <img src="{{ $currentImage2 }}" class="w-20 h-20 object-cover rounded-full border-2 border-gray-300" alt="Current Image">
                                            <div class="flex items-center gap-2 mt-1">
                                                <p class="text-sm text-gray-600">Current image</p>
                                                <button type="button" onclick="removeExistingBoardMemberImage(2)" class="text-xs text-red-600 hover:text-red-700 underline">Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" 
                                           name="board_member2_image"
                                           id="board_member2_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                    <input type="hidden" name="content[board_member2_image]" id="board_member2_image_url" value="{{ \App\Models\ContentBlock::get('board_member2_image', '', 'text', 'about') }}">
                                    <div id="board_member2_preview" class="mt-2" style="display: none;">
                                        <img id="board_member2_preview_img" class="w-20 h-20 object-cover rounded-full border-2 border-gray-300" alt="Preview">
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-sm text-gray-600">New cropped image</p>
                                            <button type="button" onclick="removeBoardMemberImage(2)" class="text-xs text-red-600 hover:text-red-700 underline">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Board Member 3 -->
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h3 class="text-md font-medium text-gray-900 mb-3">Board Member 3</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="board_member3_name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" 
                                           name="content[board_member3_name]" 
                                           id="board_member3_name"
                                           value="{{ \App\Models\ContentBlock::get('board_member3_name', '', 'text', 'about') }}"
                                           class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                
                                <div>
                                    <label for="board_member3_position" class="block text-sm font-medium text-gray-700">Position</label>
                                    <input type="text" 
                                           name="content[board_member3_position]" 
                                           id="board_member3_position"
                                           value="{{ \App\Models\ContentBlock::get('board_member3_position', '', 'text', 'about') }}"
                                           class="mt-1 block w-full border-2 border-gray-400 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="board_member3_bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                    <textarea name="content[board_member3_bio]" 
                                              id="board_member3_bio"
                                              class="ckeditor">{{ \App\Models\ContentBlock::get('board_member3_bio', '', 'html', 'about') }}</textarea>
                                </div>

                                <div>
                                    <label for="board_member3_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                                    @php $currentImage3 = \App\Models\ContentBlock::get('board_member3_image', '', 'text', 'about'); @endphp
                                    @if($currentImage3)
                                        <div class="mb-2">
                                            <img src="{{ $currentImage3 }}" class="w-20 h-20 object-cover rounded-full border-2 border-gray-300" alt="Current Image">
                                            <div class="flex items-center gap-2 mt-1">
                                                <p class="text-sm text-gray-600">Current image</p>
                                                <button type="button" onclick="removeExistingBoardMemberImage(3)" class="text-xs text-red-600 hover:text-red-700 underline">Remove</button>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" 
                                           name="board_member3_image"
                                           id="board_member3_image" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    <input type="hidden" name="content[board_member3_image]" id="board_member3_image_url" value="{{ \App\Models\ContentBlock::get('board_member3_image', '', 'text', 'about') }}">
                                    <div id="board_member3_preview" class="mt-2" style="display: none;">
                                        <img id="board_member3_preview_img" class="w-20 h-20 object-cover rounded-full border-2 border-gray-300" alt="Preview">
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-sm text-gray-600">New cropped image</p>
                                            <button type="button" onclick="removeBoardMemberImage(3)" class="text-xs text-red-600 hover:text-red-700 underline">Remove</button>
                                        </div>
                                    </div>
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
                            <label for="about_overview" class="block text-sm font-medium text-gray-700 mb-2">Company Overview</label>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="about_mission" class="block text-sm font-medium text-gray-700 mb-2">Our Mission</label>
                                <textarea name="content[about_mission]" 
                                          id="about_mission"
                                          class="ckeditor">{{ \App\Models\ContentBlock::get('about_mission', '<p>To empower businesses through innovative solutions, ethical practices, and sustainable growth strategies that create lasting value for all stakeholders.</p>', 'html', 'about') }}</textarea>
                            </div>

                            <div>
                                <label for="about_vision" class="block text-sm font-medium text-gray-700 mb-2">Our Vision</label>
                                <textarea name="content[about_vision]" 
                                          id="about_vision"
                                          class="ckeditor">{{ \App\Models\ContentBlock::get('about_vision', '<p>To be the leading catalyst for business transformation in the region, fostering a community where tradition meets innovation.</p>', 'html', 'about') }}</textarea>
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

            // Image upload handlers for board member images only (hero image handled separately)
            const imageInputs = ['board_member1_image', 'board_member2_image', 'board_member3_image'];
            imageInputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('change', function() {
                        const memberId = inputId.includes('board_member') ? inputId.replace('board_member', '').replace('_image', '') : null;
                        if (memberId) {
                            previewBoardMemberImage(memberId);
                        }
                    });
                }
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
                    
                    // Ensure hero title and subtitle are captured (they're not CKEditor fields)
                    const heroTitle = document.getElementById('about_hero_title_input');
                    const heroSubtitle = document.getElementById('about_hero_subtitle_input');
                    if (heroTitle) {
                        formData.set('content[about_hero_title]', heroTitle.value);
                    }
                    if (heroSubtitle) {
                        formData.set('content[about_hero_subtitle]', heroSubtitle.value);
                    }

                    // Handle image uploads first
                    for (const [key, value] of formData.entries()) {
                        if (value instanceof File && value.size > 0) {
                            const uploadFormData = new FormData();
                            uploadFormData.append('image', value);
                            
                            const uploadResponse = await fetch('{{ route("content.upload.image") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: uploadFormData
                            });
                            
                            const uploadResult = await uploadResponse.json();
                            if (uploadResult.success) {
                                formData.delete(key);
                                formData.set(`content[${key}]`, uploadResult.url);
                            }
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
                        } else if (key !== '_token' && !(value instanceof File)) {
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
            
            // Initialize image cropper
            initImageCropper();
        });

        // About page hero functions (matching homepage style)
        function toggleAboutHeroEdit() {
            const editForm = document.getElementById('about_hero_edit_form');
            editForm.classList.toggle('hidden');
        }

        function updateAboutHeroDisplay(type, value) {
            if (type === 'title') {
                document.getElementById('about_hero_title_display').textContent = value;
            } else if (type === 'subtitle') {
                document.getElementById('about_hero_subtitle_display').textContent = value;
            }
        }

        async function removeAboutHeroImage() {
            if (confirm('Are you sure you want to remove the hero background image?')) {
                document.getElementById('about_hero_background_image_url').value = '';
                document.getElementById('about_hero_preview').style.backgroundImage = 'url("https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1600&q=80")';
            }
        }

        // Handle hero background image file selection
        document.getElementById('about_hero_background_image').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('{{ route("content.upload.image") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('about_hero_background_image_url').value = result.url;
                    document.getElementById('about_hero_preview').style.backgroundImage = `url("${result.url}")`;
                } else {
                    alert('Error uploading image: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                alert('Error uploading image. Please try again.');
            }
        });

        // Preview board member image
        function previewBoardMemberImage(memberId) {
            const fileInput = document.getElementById(`board_member${memberId}_image`);
            const preview = document.getElementById(`board_member${memberId}_preview`);
            const previewImg = document.getElementById(`board_member${memberId}_preview_img`);
            
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Remove existing board member image function
        function removeExistingBoardMemberImage(memberId) {
            if (confirm('Are you sure you want to remove this board member image?')) {
                const hiddenInput = document.getElementById(`board_member${memberId}_image_url`);
                const currentImageDiv = hiddenInput.closest('div').querySelector('.mb-2');
                
                if (hiddenInput) hiddenInput.value = '';
                if (currentImageDiv) currentImageDiv.remove();
            }
        }
        
        // Remove board member image function
        function removeBoardMemberImage(memberId) {
            const fileInput = document.getElementById(`board_member${memberId}_image`);
            const preview = document.getElementById(`board_member${memberId}_preview`);
            const hiddenInput = document.getElementById(`board_member${memberId}_image_url`);
            
            if (fileInput) fileInput.value = '';
            if (preview) preview.style.display = 'none';
            if (hiddenInput) hiddenInput.value = '';
            
            // Show current image again when new image is removed
            const currentImageContainer = fileInput ? fileInput.parentElement.querySelector('div.mb-2') : null;
            if (currentImageContainer) {
                currentImageContainer.style.display = 'block';
            }
        }
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
                    
                    if (currentPreviewElement) {
                        // Use the exact same image as the circular preview
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