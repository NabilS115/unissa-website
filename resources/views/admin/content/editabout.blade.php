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
                
                <!-- Page Header -->
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
                                   value="{{ \App\Models\ContentBlock::get('about_title', 'About Tijarah Co', 'text', 'about') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <label for="about_subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                            <input type="text" 
                                   name="content[about_subtitle]" 
                                   id="about_subtitle"
                                   value="{{ \App\Models\ContentBlock::get('about_subtitle', 'Our Story and Mission', 'text', 'about') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Hero Image Settings -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Hero Image</h2>
                        <p class="text-sm text-gray-600">Click on the preview to edit</p>
                    </div>
                    
                    <!-- Hero Preview -->
                    <div class="relative bg-cover bg-center h-64 cursor-pointer" 
                         id="about_hero_preview"
                         style="background-image: url('{{ \App\Models\ContentBlock::get('about_hero_image', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80', 'text', 'about') }}');"
                         onclick="toggleSection('hero')">
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                            <div class="text-center text-white">
                                <h1 class="text-3xl md:text-5xl font-bold mb-4" id="hero_title_display">{{ \App\Models\ContentBlock::get('about_title', 'Our Story & Values', 'text', 'about') }}</h1>
                                <p class="text-lg md:text-xl mb-6" id="hero_subtitle_display">{{ \App\Models\ContentBlock::get('about_subtitle', 'Discover our journey and what drives us forward', 'text', 'about') }}</p>
                                <div class="flex items-center justify-center text-sm text-white/80">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Click to edit hero section
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hero Edit Form -->
                    <div id="hero_edit" class="hidden p-6 bg-gray-50 border-t space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
                            <input type="text" 
                                   name="content[about_title]" 
                                   id="about_title_input"
                                   value="{{ \App\Models\ContentBlock::get('about_title', 'Our Story & Values', 'text', 'about') }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                   onchange="updateHeroDisplay('title', this.value)">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                            <textarea name="content[about_subtitle]" 
                                      id="about_subtitle_input"
                                      rows="2"
                                      placeholder="Enter subtitle (press Enter for new lines)"
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                      onchange="updateHeroDisplay('subtitle', this.value)">{{ str_replace('<br>', "\n", \App\Models\ContentBlock::get('about_subtitle', 'Discover our journey and what drives us forward', 'text', 'about')) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Press Enter to create new lines</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                            <div class="flex items-center space-x-4">
                                <input type="file" id="about_hero_image" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('about_hero_image').click()" 
                                        class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">Choose Image</button>
                                <button type="button" onclick="removeAboutHeroImage()" 
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Remove</button>
                            </div>
                            <input type="hidden" 
                                   name="content[about_hero_image]" 
                                   id="about_hero_image_url"
                                   value="{{ \App\Models\ContentBlock::get('about_hero_image', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80', 'text', 'about') }}">
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="button" onclick="toggleSection('hero')" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Done</button>
                        </div>
                    </div>
                </div>

                <!-- Board of Directors -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Board of Directors</h2>
                        <p class="text-sm text-gray-600">Click on any board member card to edit their information</p>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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

                        <!-- Visual Board Member Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Board Member 1 Card -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-dashed border-gray-200 hover:border-blue-300 transition-colors cursor-pointer" onclick="toggleEditMode(1)">
                                <div class="text-center">
                                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg overflow-hidden" id="member1_avatar">
                                        @php $member1Image = \App\Models\ContentBlock::get('board_member1_image', '', 'text', 'about'); @endphp
                                        @if($member1Image)
                                            <img src="{{ $member1Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span id="member1_initials_display">{{ \App\Models\ContentBlock::get('board_member1_initials', 'DC', 'text', 'about') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-1" id="member1_name_display">{{ \App\Models\ContentBlock::get('board_member1_name', 'Dato\' Chairman', 'text', 'about') }}</h3>
                                    <p class="text-blue-600 font-semibold mb-3" id="member1_title_display">{{ \App\Models\ContentBlock::get('board_member1_title', 'Chairman & Founder', 'text', 'about') }}</p>
                                    <div class="flex items-center justify-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Click to edit
                                    </div>
                                </div>
                                
                                <!-- Hidden Edit Form -->
                                <div id="edit_form_1" class="hidden mt-4 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Name</label>
                                        <input type="text" name="content[board_member1_name]" id="member1_name_input" 
                                               value="{{ \App\Models\ContentBlock::get('board_member1_name', 'Dato\' Chairman', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                               onchange="updateDisplay(1, 'name', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Title</label>
                                        <input type="text" name="content[board_member1_title]" id="member1_title_input"
                                               value="{{ \App\Models\ContentBlock::get('board_member1_title', 'Chairman & Founder', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                               onchange="updateDisplay(1, 'title', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Initials (if no photo)</label>
                                        <input type="text" name="content[board_member1_initials]" id="member1_initials_input"
                                               value="{{ \App\Models\ContentBlock::get('board_member1_initials', 'DC', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                               onchange="updateDisplay(1, 'initials', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Profile Photo</label>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <input type="file" id="board_member1_image" accept="image/*" class="hidden">
                                            <button type="button" onclick="document.getElementById('board_member1_image').click()" 
                                                    class="px-3 py-1 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600">Choose Photo</button>
                                            <button type="button" onclick="removeMemberImage(1)" 
                                                    class="px-3 py-1 bg-gray-500 text-white text-xs rounded-md hover:bg-gray-600">Remove</button>
                                        </div>
                                        <input type="hidden" 
                                               name="content[board_member1_image]" 
                                               id="member1_image_input" 
                                               value="{{ \App\Models\ContentBlock::get('board_member1_image', '', 'text', 'about') }}">
                                    </div>
                                    <div class="flex space-x-2 pt-2">
                                        <button type="button" onclick="toggleEditMode(1)" 
                                                class="flex-1 px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">Done</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Board Member 2 Card -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-dashed border-gray-200 hover:border-teal-300 transition-colors cursor-pointer" onclick="toggleEditMode(2)">
                                <div class="text-center">
                                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-r from-teal-500 to-green-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg overflow-hidden" id="member2_avatar">
                                        @php $member2Image = \App\Models\ContentBlock::get('board_member2_image', '', 'text', 'about'); @endphp
                                        @if($member2Image)
                                            <img src="{{ $member2Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span id="member2_initials_display">{{ \App\Models\ContentBlock::get('board_member2_initials', 'MS', 'text', 'about') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-1" id="member2_name_display">{{ \App\Models\ContentBlock::get('board_member2_name', 'Md. Saiful', 'text', 'about') }}</h3>
                                    <p class="text-teal-600 font-semibold mb-3" id="member2_title_display">{{ \App\Models\ContentBlock::get('board_member2_title', 'Chief Executive Officer', 'text', 'about') }}</p>
                                    <div class="flex items-center justify-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Click to edit
                                    </div>
                                </div>
                                
                                <!-- Hidden Edit Form -->
                                <div id="edit_form_2" class="hidden mt-4 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Name</label>
                                        <input type="text" name="content[board_member2_name]" id="member2_name_input" 
                                               value="{{ \App\Models\ContentBlock::get('board_member2_name', 'Md. Saiful', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                               onchange="updateDisplay(2, 'name', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Title</label>
                                        <input type="text" name="content[board_member2_title]" id="member2_title_input"
                                               value="{{ \App\Models\ContentBlock::get('board_member2_title', 'Chief Executive Officer', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                               onchange="updateDisplay(2, 'title', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Initials (if no photo)</label>
                                        <input type="text" name="content[board_member2_initials]" id="member2_initials_input"
                                               value="{{ \App\Models\ContentBlock::get('board_member2_initials', 'MS', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500"
                                               onchange="updateDisplay(2, 'initials', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Profile Photo</label>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <input type="file" id="board_member2_image" accept="image/*" class="hidden">
                                            <button type="button" onclick="document.getElementById('board_member2_image').click()" 
                                                    class="px-3 py-1 bg-teal-500 text-white text-xs rounded-md hover:bg-teal-600">Choose Photo</button>
                                            <button type="button" onclick="removeMemberImage(2)" 
                                                    class="px-3 py-1 bg-gray-500 text-white text-xs rounded-md hover:bg-gray-600">Remove</button>
                                        </div>
                                        <input type="hidden" 
                                               name="content[board_member2_image]" 
                                               id="member2_image_input" 
                                               value="{{ \App\Models\ContentBlock::get('board_member2_image', '', 'text', 'about') }}">
                                    </div>
                                    <div class="flex space-x-2 pt-2">
                                        <button type="button" onclick="toggleEditMode(2)" 
                                                class="flex-1 px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">Done</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Board Member 3 Card -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-dashed border-gray-200 hover:border-purple-300 transition-colors cursor-pointer" onclick="toggleEditMode(3)">
                                <div class="text-center">
                                    <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg overflow-hidden" id="member3_avatar">
                                        @php $member3Image = \App\Models\ContentBlock::get('board_member3_image', '', 'text', 'about'); @endphp
                                        @if($member3Image)
                                            <img src="{{ $member3Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span id="member3_initials_display">{{ \App\Models\ContentBlock::get('board_member3_initials', 'AF', 'text', 'about') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-1" id="member3_name_display">{{ \App\Models\ContentBlock::get('board_member3_name', 'Ahmad Farid', 'text', 'about') }}</h3>
                                    <p class="text-purple-600 font-semibold mb-3" id="member3_title_display">{{ \App\Models\ContentBlock::get('board_member3_title', 'Chief Financial Officer', 'text', 'about') }}</p>
                                    <div class="flex items-center justify-center text-xs text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Click to edit
                                    </div>
                                </div>
                                
                                <!-- Hidden Edit Form -->
                                <div id="edit_form_3" class="hidden mt-4 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Name</label>
                                        <input type="text" name="content[board_member3_name]" id="member3_name_input" 
                                               value="{{ \App\Models\ContentBlock::get('board_member3_name', 'Ahmad Farid', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                                               onchange="updateDisplay(3, 'name', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Title</label>
                                        <input type="text" name="content[board_member3_title]" id="member3_title_input"
                                               value="{{ \App\Models\ContentBlock::get('board_member3_title', 'Chief Financial Officer', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                                               onchange="updateDisplay(3, 'title', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Initials (if no photo)</label>
                                        <input type="text" name="content[board_member3_initials]" id="member3_initials_input"
                                               value="{{ \App\Models\ContentBlock::get('board_member3_initials', 'AF', 'text', 'about') }}"
                                               class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                                               onchange="updateDisplay(3, 'initials', this.value)">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Profile Photo</label>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <input type="file" id="board_member3_image" accept="image/*" class="hidden">
                                            <button type="button" onclick="document.getElementById('board_member3_image').click()" 
                                                    class="px-3 py-1 bg-purple-500 text-white text-xs rounded-md hover:bg-purple-600">Choose Photo</button>
                                            <button type="button" onclick="removeMemberImage(3)" 
                                                    class="px-3 py-1 bg-gray-500 text-white text-xs rounded-md hover:bg-gray-600">Remove</button>
                                        </div>
                                        <input type="hidden" 
                                               name="content[board_member3_image]" 
                                               id="member3_image_input" 
                                               value="{{ \App\Models\ContentBlock::get('board_member3_image', '', 'text', 'about') }}">
                                    </div>
                                    <div class="flex space-x-2 pt-2">
                                        <button type="button" onclick="toggleEditMode(3)" 
                                                class="flex-1 px-3 py-1 bg-green-500 text-white text-xs rounded-md hover:bg-green-600">Done</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Overview -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Company Overview</h2>
                    </div>
                    <div class="px-6 py-4">
                        <label for="about_overview" class="block text-sm font-medium text-gray-700 mb-2">Overview Content</label>
                        <textarea name="content[about_overview]" 
                                  id="about_overview"
                                  class="ckeditor">{{ \App\Models\ContentBlock::get('about_overview', '<p>Founded with a vision to bridge the gap between traditional business practices and modern innovation, <strong>Tijarah Co</strong> has established itself as a trusted partner for organizations seeking to navigate the complexities of today\'s dynamic marketplace.</p>', 'html', 'about') }}</textarea>
                    </div>
                </div>

                <!-- Mission & Vision -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Mission & Vision</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="about_mission" class="block text-sm font-medium text-gray-700 mb-2">Mission Statement</label>
                            <textarea name="content[about_mission]" 
                                      id="about_mission"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_mission', '<p>To empower businesses through innovative solutions, ethical practices, and sustainable growth strategies that create lasting value for all stakeholders.</p>', 'html', 'about') }}</textarea>
                        </div>
                        <div>
                            <label for="about_vision" class="block text-sm font-medium text-gray-700 mb-2">Vision Statement</label>
                            <textarea name="content[about_vision]" 
                                      id="about_vision"
                                      class="ckeditor">{{ \App\Models\ContentBlock::get('about_vision', '<p>To be the leading catalyst for business transformation in the region, fostering a community where tradition meets innovation.</p>', 'html', 'about') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Company Values -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Company Values</h2>
                    </div>
                    <div class="px-6 py-4">
                        <label for="about_values" class="block text-sm font-medium text-gray-700 mb-2">Our Values</label>
                        <textarea name="content[about_values]" 
                                  id="about_values"
                                  class="ckeditor">{{ \App\Models\ContentBlock::get('about_values', '<ul><li><strong>Integrity:</strong> We operate with transparency and honesty in all our dealings</li><li><strong>Innovation:</strong> We embrace new ideas and technologies to drive progress</li><li><strong>Excellence:</strong> We strive for the highest standards in everything we do</li><li><strong>Collaboration:</strong> We believe in the power of partnership and teamwork</li></ul>', 'html', 'about') }}</textarea>
                    </div>
                </div>

                <!-- Company History -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Company History</h2>
                    </div>
                    <div class="px-6 py-4">
                        <label for="about_history" class="block text-sm font-medium text-gray-700 mb-2">Our Journey</label>
                        <textarea name="content[about_history]" 
                                  id="about_history"
                                  class="ckeditor">{{ \App\Models\ContentBlock::get('about_history', '<p>Our journey began with a simple yet powerful vision: to create meaningful connections between businesses, communities, and opportunities. Over the years, we have grown from a startup with big dreams to a respected player in the business ecosystem.</p>', 'html', 'about') }}</textarea>
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

            // Board member management functions
            window.toggleEditMode = function(memberId) {
                const editForm = document.getElementById('edit_form_' + memberId);
                const isHidden = editForm.classList.contains('hidden');
                
                // Hide all edit forms first
                for (let i = 1; i <= 3; i++) {
                    document.getElementById('edit_form_' + i).classList.add('hidden');
                }
                
                // Show the clicked one if it was hidden
                if (isHidden) {
                    editForm.classList.remove('hidden');
                    editForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            };

            window.updateDisplay = function(memberId, field, value) {
                const displayElement = document.getElementById('member' + memberId + '_' + field + '_display');
                if (displayElement) {
                    displayElement.textContent = value;
                }
            };

            window.toggleSection = function(sectionName) {
                const editForm = document.getElementById(sectionName + '_edit');
                editForm.classList.toggle('hidden');
            };

            window.updateHeroDisplay = function(field, value) {
                const displayElement = document.getElementById('hero_' + field + '_display');
                if (displayElement) {
                    displayElement.textContent = value;
                }
            };

            window.removeAboutHeroImage = function() {
                const urlInput = document.getElementById('about_hero_image_url');
                const preview = document.getElementById('about_hero_preview');
                
                urlInput.value = '';
                urlInput.dispatchEvent(new Event('change'));
                preview.style.backgroundImage = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                alert('Hero background image removed! Click "Save Content" to apply changes.');
            };

            window.removeMemberImage = function(memberId) {
                // Clear the hidden input that stores the image URL
                const imageInput = document.getElementById('member' + memberId + '_image_input');
                imageInput.value = '';
                
                // Trigger change event to ensure form knows the value changed
                imageInput.dispatchEvent(new Event('change'));
                
                // Create a specific empty marker for proper form submission
                const existingEmptyMarker = document.getElementById('member' + memberId + '_empty_marker');
                if (existingEmptyMarker) {
                    existingEmptyMarker.remove();
                }
                const emptyMarker = document.createElement('input');
                emptyMarker.type = 'hidden';
                emptyMarker.name = 'content[board_member' + memberId + '_image_removed]';
                emptyMarker.value = '1';
                emptyMarker.id = 'member' + memberId + '_empty_marker';
                document.getElementById('about-form').appendChild(emptyMarker);
                
                // Get the avatar element and initials
                const avatar = document.getElementById('member' + memberId + '_avatar');
                const initialsInput = document.getElementById('member' + memberId + '_initials_input');
                const initials = initialsInput.value || 'XX';
                
                // Update avatar to show initials instead of image
                avatar.innerHTML = '<span id="member' + memberId + '_initials_display">' + initials + '</span>';
                
                // Also clear the file input if it exists
                const fileInput = document.getElementById('board_member' + memberId + '_image');
                if (fileInput) {
                    fileInput.value = '';
                }
                
                // Mark the form as having unsaved changes
                const form = document.getElementById('about-form');
                if (form) {
                    form.setAttribute('data-unsaved', 'true');
                }
                
                alert('Photo removed successfully! Click "Save Content" to apply changes.');
            };

            // Image upload functions for board members
            ['board_member1_image', 'board_member2_image', 'board_member3_image'].forEach(function(inputId, index) {
                const memberId = index + 1;
                document.getElementById(inputId).addEventListener('change', async function(e) {
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
                            // Update hidden input
                            document.getElementById('member' + memberId + '_image_input').value = result.url;
                            
                            // Update avatar display
                            const avatar = document.getElementById('member' + memberId + '_avatar');
                            avatar.innerHTML = '<img src="' + result.url + '" alt="Board Member" class="w-full h-full object-cover rounded-full">';
                            
                            alert('Image uploaded successfully!');
                        } else {
                            alert('Upload failed: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Upload error:', error);
                        alert('Upload failed. Please try again.');
                    }
                });
            });

            // Image upload functions (keeping old ones for compatibility)
            window.uploadAboutHeroImage = async function() {
                const fileInput = document.getElementById('about_hero_image');
                const file = fileInput.files[0];
                
                if (!file) {
                    alert('Please select an image first');
                    return;
                }

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
                        document.getElementById('about_hero_image_url').value = result.url;
                        document.getElementById('about_hero_image_preview').src = result.url;
                        alert('Image uploaded successfully!');
                    } else {
                        alert('Upload failed: ' + result.message);
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('Upload failed. Please try again.');
                }
            };

            // Form submission handler
            document.getElementById('about-form').addEventListener('submit', async function(e) {
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
                    
                    // Convert line breaks to <br> tags for subtitle field
                    const aboutSubtitle = formData.get('content[about_subtitle]');
                    if (aboutSubtitle) {
                        formData.set('content[about_subtitle]', aboutSubtitle.replace(/\n/g, '<br>'));
                    }
                    
                    // Update form data with CKEditor content
                    for (const [name, editor] of Object.entries(editors)) {
                        const fieldName = name.replace('content[', '').replace(']', '');
                        formData.set(`content[${fieldName}]`, editor.getData());
                    }
                    
                    const response = await fetch('{{ route('content.about.update') }}', {
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
        });
    </script>
</body>
</html>