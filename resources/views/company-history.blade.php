@extends('layouts.app')

@section('title', 'Tijarah Co - About')

@section('content')
    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Admin Edit Button -->
        <div class="fixed top-20 right-4 z-50">
            <a href="{{ route('content.about') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600 hover:from-teal-700 hover:via-emerald-700 hover:to-cyan-700 text-white rounded-2xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
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
            <div class="bg-gradient-to-r from-teal-500 to-green-500 px-8 py-12">
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                    </svg>
                                </div>
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
                                    $member1Initials = \App\Models\ContentBlock::get('board_member1_initials', 'DC', 'text', 'about');
                                @endphp
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-lg group-hover:shadow-xl transition-shadow overflow-hidden" style="background: linear-gradient(to right, #166534, #14532d) !important;">
                                    @if($member1Image)
                                        <img src="{{ $member1Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-white font-black text-2xl drop-shadow-lg">{{ $member1Initials }}</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{!! \App\Models\ContentBlock::get('board_member1_name', 'Dato\' Chairman', 'text', 'about') !!}</h3>
                                <p class="text-blue-600 font-semibold mb-3">{!! \App\Models\ContentBlock::get('board_member1_title', 'Chairman & Founder', 'text', 'about') !!}</p>
                            </div>
                        </div>

                        <!-- CEO -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 group">
                            <div class="text-center">
                                @php
                                    $member2Image = \App\Models\ContentBlock::get('board_member2_image', '', 'text', 'about');
                                    $member2Initials = \App\Models\ContentBlock::get('board_member2_initials', 'MS', 'text', 'about');
                                @endphp
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-lg group-hover:shadow-xl transition-shadow overflow-hidden" style="background: linear-gradient(to right, #dc2626, #ea580c) !important;">
                                    @if($member2Image)
                                        <img src="{{ $member2Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-white font-black text-2xl drop-shadow-lg">{{ $member2Initials }}</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{!! \App\Models\ContentBlock::get('board_member2_name', 'Md. Saiful', 'text', 'about') !!}</h3>
                                <p class="text-teal-600 font-semibold mb-3">{!! \App\Models\ContentBlock::get('board_member2_title', 'Chief Executive Officer', 'text', 'about') !!}</p>
                            </div>
                        </div>

                        <!-- CFO -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 group">
                            <div class="text-center">
                                @php
                                    $member3Image = \App\Models\ContentBlock::get('board_member3_image', '', 'text', 'about');
                                    $member3Initials = \App\Models\ContentBlock::get('board_member3_initials', 'AF', 'text', 'about');
                                @endphp
                                <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-lg group-hover:shadow-xl transition-shadow overflow-hidden" style="background: linear-gradient(to right, #1e40af, #1e3a8a) !important;">
                                    @if($member3Image)
                                        <img src="{{ $member3Image }}" alt="Board Member" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <span class="text-white font-black text-2xl drop-shadow-lg">{{ $member3Initials }}</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{!! \App\Models\ContentBlock::get('board_member3_name', 'Ahmad Farid', 'text', 'about') !!}</h3>
                                <p class="text-purple-600 font-semibold mb-3">{!! \App\Models\ContentBlock::get('board_member3_title', 'Chief Financial Officer', 'text', 'about') !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Company Overview Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12">
            <div class="text-gray-700 text-lg leading-relaxed mb-8">
                {!! \App\Models\ContentBlock::get('about_overview', '<p>Founded with a vision to bridge the gap between traditional business practices and modern innovation, <strong>Tijarah Co</strong> has established itself as a trusted partner for organizations seeking to navigate the complexities of today\'s dynamic marketplace.</p>', 'html', 'about') !!}
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-teal-700 mb-4">Our Mission</h3>
                <div class="text-gray-700 leading-relaxed">
                    {!! \App\Models\ContentBlock::get('about_mission', '<p>To empower businesses through innovative solutions, ethical practices, and sustainable growth strategies that create lasting value for all stakeholders.</p>', 'html', 'about') !!}
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-teal-700 mb-4">Our Vision</h3>
                <div class="text-gray-700 leading-relaxed">
                    {!! \App\Models\ContentBlock::get('about_vision', '<p>To be the leading catalyst for business transformation in the region, fostering a community where tradition meets innovation.</p>', 'html', 'about') !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Company History Section -->
    <section class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12">
            <h3 class="text-3xl font-bold text-teal-700 mb-6">Our Journey</h3>
            <div class="text-gray-700 leading-relaxed">
                {!! \App\Models\ContentBlock::get('about_history', '<p>Our journey began with a simple yet powerful vision: to create meaningful connections between businesses, communities, and opportunities. Over the years, we have grown from a startup with big dreams to a respected player in the business ecosystem.</p>', 'html', 'about') !!}
            </div>
        </div>
    </section>

    <!-- Company History Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- History Header -->
            <div class="bg-gradient-to-r from-teal-500 to-green-500 px-8 py-12">
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white">Our History</h2>
                    </div>
                    <div class="text-white/90 text-sm">
                        From humble beginnings to culinary excellence
                    </div>
                </div>
            </div>

            <!-- History Content -->
            <div class="p-8 md:p-12">
                <div class="max-w-4xl mx-auto space-y-8">
                    <!-- Timeline Item 1 -->
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.941 2.524 1 1 0 01-.809 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-xl font-bold text-teal-700 mb-3">1985 - The Beginning</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    Founded in 1985 in a humble kitchen on the outskirts of Bandar Seri Begawan, our company began as a family-run eatery serving traditional recipes passed down through generations. The aroma of freshly cooked nasi lemak and the warmth of our hospitality quickly made us a local favorite.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Item 2 -->
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-xl font-bold text-green-700 mb-3">1990s - Growth & Innovation</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    As word spread, so did our ambitions. By the early 1990s, we expanded to new locations, introducing innovative dishes inspired by both local heritage and global flavors. Our commitment to quality and authenticity set us apart in the growing culinary landscape.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Item 3 -->
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-xl font-bold text-teal-700 mb-3">2000s - Digital Era</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    The turn of the millennium marked a new era: we embraced technology, launched our first website, and began sourcing sustainable ingredients from trusted farmers. Our commitment to community was unwavering—we sponsored food drives, supported local schools, and hosted annual cultural festivals celebrating Bruneian cuisine.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Item 4 -->
                    <div class="flex gap-6 group">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <h3 class="text-xl font-bold text-teal-700 mb-3">Today - Excellence & Innovation</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    Today, our company stands as a beacon of culinary excellence in Brunei and beyond. We continue to innovate, blending tradition with modernity, and remain dedicated to our founding values. Every meal we serve is a tribute to our journey—a story of resilience, creativity, and the joy of bringing people together around the table.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-teal-700 mb-4">Our Core Values</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">The principles that guide everything we do</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Integrity -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 h-64 md:h-auto relative overflow-hidden">
                        <img src="/images/integrity.jpeg" alt="Integrity" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/20 to-transparent"></div>
                    </div>
                    <div class="md:w-1/2 p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-teal-700">Integrity</h3>
                        </div>
                        <p class="text-gray-700 leading-relaxed">We uphold the highest standards of honesty and transparency in all our operations, building trust with every interaction.</p>
                    </div>
                </div>
            </div>

            <!-- Quality -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-teal-700">Quality</h3>
                        </div>
                        <p class="text-gray-700 leading-relaxed">Our commitment to excellence is reflected in every dish we serve, ensuring exceptional experiences for our customers.</p>
                    </div>
                    <div class="md:w-1/2 h-64 md:h-auto relative overflow-hidden">
                        <img src="/images/quality.png" alt="Quality" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-gradient-to-l from-teal-600/20 to-transparent"></div>
                    </div>
                </div>
            </div>

            <!-- Innovation -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 h-64 md:h-auto relative overflow-hidden">
                        <img src="/images/innovation.png" alt="Innovation" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-transparent"></div>
                    </div>
                    <div class="md:w-1/2 p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-teal-700">Innovation</h3>
                        </div>
                        <p class="text-gray-700 leading-relaxed">We embrace creativity and forward-thinking to continually improve our offerings and stay ahead of culinary trends.</p>
                    </div>
                </div>
            </div>

            <!-- Community -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-green-700">Community</h3>
                        </div>
                        <p class="text-gray-700 leading-relaxed">We believe in giving back and fostering strong relationships with our customers, partners, and local community.</p>
                    </div>
                    <div class="md:w-1/2 h-64 md:h-auto relative overflow-hidden">
                        <img src="/images/community.jpg" alt="Community" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-gradient-to-l from-green-600/20 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sustainability - Full Width -->
        <div class="mt-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/2 h-64 lg:h-80 relative overflow-hidden">
                        <img src="/images/sustainability.png" alt="Sustainability" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-transparent"></div>
                    </div>
                    <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-emerald-700">Sustainability</h3>
                        </div>
                        <p class="text-gray-700 leading-relaxed text-lg mb-6">We strive to minimize our environmental impact and promote responsible practices throughout our operations, from sourcing to service.</p>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-gray-600">Eco-friendly packaging</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-gray-600">Local sourcing</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-gray-600">Waste reduction</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-gray-600">Energy efficiency</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="w-full bg-gradient-to-r from-teal-600 to-green-600 py-16">
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