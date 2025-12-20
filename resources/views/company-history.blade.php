@extends('layouts.app')

@section('title', 'Tijarah Co - About')

@section('content')
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Edit Button -->
        <div class="fixed top-20 right-4 z-50">
            <a href="{{ route('content.about') }}" 
               class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
               style="background-color: #0d9488 !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Page
            </a>
        </div>
    @endif
<div class="min-h-screen bg-gray-50">
    <!-- Hero Banner Section -->
    <section class="w-full h-80 flex flex-col items-center justify-center mb-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <img src="{!! \App\Models\ContentBlock::get('about_hero_image', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1600&q=80', 'text', 'about') !!}" alt="Company Banner" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white drop-shadow-lg mb-4">{!! \App\Models\ContentBlock::get('about_hero_title', 'About Our Company', 'text', 'about') !!}</h1>
            <p class="text-lg md:text-xl text-white drop-shadow-md">{!! \App\Models\ContentBlock::get('about_hero_subtitle', 'Discover our journey, values, and commitment to excellence', 'text', 'about') !!}</p>
        </div>
    </section>

    <!-- Board of Directors Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Board Header -->
            <div class="bg-teal-600 px-8 py-12" style="background-color:#0d9488;">
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="mb-6">
                                <h2 class="text-3xl md:text-4xl font-extrabold text-white">{!! \App\Models\ContentBlock::get('board_title', 'Board of Directors', 'text', 'about') !!}</h2>
                            </div>
                            <div class="text-white/90 text-sm">
                                {!! \App\Models\ContentBlock::get('board_subtitle', 'Meet the visionary leaders driving our company forward', 'text', 'about') !!}
                            </div>
                        </div>
                        @auth
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('content.about') }}" 
                                   class="inline-flex items-center px-3 py-2 border border-white/30 rounded-md text-sm font-medium text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Board
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Board Content -->
            <div class="p-8 md:p-12">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Chairman -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 group">
                            <div class="text-center">
                                @php
                                    $member1Image = \App\Models\ContentBlock::get('board_member1_image', '', 'text', 'about');
                                    $member1Name = \App\Models\ContentBlock::get('board_member1_name', 'Dato\' Chairman', 'text', 'about');
                                    $member1Initials = collect(explode(' ', strip_tags($member1Name)))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                                @endphp
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-lg group-hover:shadow-xl transition-shadow overflow-hidden" style="background: linear-gradient(to right, #166534, #14532d) !important;">
                                    @if($member1Image)
                                        <img src="{{ $member1Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-white font-black text-2xl drop-shadow-lg">{{ $member1Initials }}</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{!! \App\Models\ContentBlock::get('board_member1_name', 'Dato\' Chairman', 'text', 'about') !!}</h3>
                                <p class="text-blue-600 font-semibold mb-3">{!! \App\Models\ContentBlock::get('board_member1_position', 'Chairman & Founder', 'text', 'about') !!}</p>
                            </div>
                        </div>

                        <!-- CEO -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 group">
                            <div class="text-center">
                                @php
                                    $member2Image = \App\Models\ContentBlock::get('board_member2_image', '', 'text', 'about');
                                    $member2Name = \App\Models\ContentBlock::get('board_member2_name', 'Md. Saiful', 'text', 'about');
                                    $member2Initials = collect(explode(' ', strip_tags($member2Name)))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                                @endphp
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-lg group-hover:shadow-xl transition-shadow overflow-hidden" style="background: linear-gradient(to right, #dc2626, #ea580c) !important;">
                                    @if($member2Image)
                                        <img src="{{ $member2Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-white font-black text-2xl drop-shadow-lg">{{ $member2Initials }}</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{!! \App\Models\ContentBlock::get('board_member2_name', 'Md. Saiful', 'text', 'about') !!}</h3>
                                <p class="text-teal-600 font-semibold mb-3">{!! \App\Models\ContentBlock::get('board_member2_position', 'Chief Executive Officer', 'text', 'about') !!}</p>
                            </div>
                        </div>

                        <!-- CFO -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 group">
                            <div class="text-center">
                                @php
                                    $member3Image = \App\Models\ContentBlock::get('board_member3_image', '', 'text', 'about');
                                    $member3Name = \App\Models\ContentBlock::get('board_member3_name', 'Ahmad Farid', 'text', 'about');
                                    $member3Initials = collect(explode(' ', strip_tags($member3Name)))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                                @endphp
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-lg group-hover:shadow-xl transition-shadow overflow-hidden" style="background: linear-gradient(to right, #1e40af, #1e3a8a) !important;">
                                    @if($member3Image)
                                        <img src="{{ $member3Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-white font-black text-2xl drop-shadow-lg">{{ $member3Initials }}</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{!! \App\Models\ContentBlock::get('board_member3_name', 'Ahmad Farid', 'text', 'about') !!}</h3>
                                <p class="text-purple-600 font-semibold mb-3">{!! \App\Models\ContentBlock::get('board_member3_position', 'Chief Financial Officer', 'text', 'about') !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Company Overview Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Overview Header -->
            <div class="bg-teal-600 px-8 py-12" style="background-color:#0d9488;">
                <div class="max-w-4xl mx-auto">
                    <div class=\"flex items-center gap-4 mb-6\">

                        <h2 class="text-3xl md:text-4xl font-extrabold text-white">Our Company</h2>
                    </div>
                    <div class="text-white/90 text-sm">
                        Building excellence through innovation and integrity
                    </div>
                </div>
            </div>
            
            <!-- Overview Content -->
            <div class="p-8 md:p-12">
                <div class="max-w-4xl mx-auto">
                    <div class="text-gray-700 text-lg leading-relaxed">
                        {!! \App\Models\ContentBlock::get('about_overview', '<p>Founded with a vision to bridge the gap between traditional business practices and modern innovation, <strong>Tijarah Co</strong> has established itself as a trusted partner for organizations seeking to navigate the complexities of today\'s dynamic marketplace.</p>', 'html', 'about') !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-teal-700 mb-4">Mission & Vision</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Our guiding principles that drive us forward</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Mission -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-500/10 to-emerald-500/10"></div>
                    <div class="relative p-8 md:p-12">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold text-teal-700">Our Mission</h3>
                        </div>
                        <div class="text-gray-700 leading-relaxed text-lg">
                            {!! \App\Models\ContentBlock::get('about_mission', '<p>To empower businesses through innovative solutions, ethical practices, and sustainable growth strategies that create lasting value for all stakeholders.</p>', 'html', 'about') !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vision -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-cyan-500/10"></div>
                    <div class="relative p-8 md:p-12">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold text-emerald-700">Our Vision</h3>
                        </div>
                        <div class="text-gray-700 leading-relaxed text-lg">
                            {!! \App\Models\ContentBlock::get('about_vision', '<p>To be the leading catalyst for business transformation in the region, fostering a community where tradition meets innovation.</p>', 'html', 'about') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Call to Action Section -->
    <section class="w-full bg-teal-600 py-16" style="background-color:#0d9488;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Experience Our Story?</h2>
            <p class="text-xl text-teal-100 mb-8 max-w-2xl mx-auto">Join us on our culinary journey and taste the passion, quality, and tradition in every bite.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/catalog" class="inline-flex items-center px-8 py-4 bg-white text-teal-600 font-semibold rounded-xl shadow-lg hover:bg-gray-50 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Explore Our Catalog
                </a>
                <a href="/contact" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-teal-600 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Get In Touch
                </a>
            </div>
        </div>
    </section>
</div>
@endsection