@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-emerald-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- User Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-teal-100 overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500 relative">
                <button type="button" onclick="window.location.href='/edit-profile'" class="absolute top-4 right-4 px-4 py-2 bg-white border border-teal-200 text-teal-700 font-semibold rounded-xl shadow hover:bg-teal-50 hover:text-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-400 transition-all duration-200 z-10" aria-label="Edit Profile">
                    Edit Profile
                </button>
            </div>
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <div class="relative">
                @if(Auth::check())
                    <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                @endif
                        </div>
                        <div class="lg:mb-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                                <div class="flex flex-col gap-0 mb-2">
                                    <div class="flex items-center gap-3">
                                        <h1 class="text-3xl font-bold text-gray-900">@if(Auth::check()){{ Auth::user()->name }}@else Dr. Ahmad bin Ali @endif</h1>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600 mt-2">
                                        <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                        </svg>
                                        <span class="font-medium">@if(Auth::check()){{ Auth::user()->role }}@else Lecturer / Student / Staff @endif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column: Profile Details -->
            <div class="space-y-6">
                <!-- Personal Information Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="border-l-4 border-teal-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Full Name</div>
                            <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->name }}@else Dr. Ahmad bin Ali @endif</div>
                        </div>
                        
                        <div class="border-l-4 border-blue-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Email Address</div>
                            <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->email }}@else name@unissa.edu.bn @endif</div>
                        </div>
                        
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Phone Number</div>
                            <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->phone }}@else +673 xxxx xxxx @endif</div>
                        </div>
                        
                        <div class="border-l-4 border-purple-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Faculty / Department</div>
                            <div class="text-gray-900 font-medium">@if(Auth::check()){{ Auth::user()->department }}@else Faculty of Usuluddin @endif</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Reviews -->
            <div>
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">My Reviews</h2>
                                <p class="text-gray-600">@if(Auth::check()){{ Auth::user()->reviews->count() }} {{ Auth::user()->reviews->count() === 1 ? 'review' : 'reviews' }} shared @else 0 reviews shared @endif</p>
                            </div>
                        </div>
                        
                        @if(Auth::check() && Auth::user()->reviews->count() > 1)
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Use arrows to navigate reviews
                            </div>
                        @endif
                    </div>

                    @if(Auth::check() && Auth::user()->reviews->count() > 0)
                        <!-- Reviews Display Area -->
                        <div class="bg-gray-50 rounded-xl p-6 min-h-[400px]">
                            @if(Auth::check() && Auth::user()->reviews->count() > 1)
                                <!-- Navigation Arrows for Multiple Reviews -->
                                <div class="relative">
                                    <button onclick="moveReview(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white hover:bg-gray-50 rounded-full shadow-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:text-gray-800 transition-all duration-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button onclick="moveReview(1)" class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-12 h-12 bg-white hover:bg-gray-50 rounded-full shadow-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:text-gray-800 transition-all duration-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Reviews Carousel -->
                                    <div id="reviews-carousel" class="overflow-hidden mx-16">
                                        <div id="reviews-track" class="flex transition-transform duration-500 ease-in-out">
                                            @foreach(Auth::user()->reviews as $review)
                                                <div class="w-full flex-shrink-0">
                                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mx-2">
                                                        @include('profile.partials.review-card', ['review' => $review])
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Navigation Dots -->
                                    <div class="flex justify-center mt-6 gap-2">
                                            @if(Auth::check())
                                                @foreach(Auth::user()->reviews as $index => $review)
                                                    <button onclick="goToReview({{ $index }})" 
                                                            class="review-dot w-3 h-3 rounded-full transition-all duration-200 {{ $index === 0 ? 'bg-teal-500 w-8' : 'bg-gray-300 hover:bg-gray-400' }}"
                                                            data-index="{{ $index }}"></button>
                                                @endforeach
                                            @endif
                                    </div>
                                </div>
                            @else
                                <!-- Single Review Display -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    @include('profile.partials.review-card', ['review' => Auth::user()->reviews->first()])
                                </div>
                            @endif
                        </div>
                        
                        <!-- Review carousel & read-more logic moved to external script -->
                        <script>
                            window.__profileReviews = { totalReviews: {{ Auth::user()->reviews->count() }} };
                        </script>
                        <script src="/js/profile-reviews.js"></script>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">No Reviews Yet</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">You haven't written any reviews yet. Share your experiences with products to help other users make informed decisions!</p>
                            <a href="/catalog" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-green-500 text-white font-semibold rounded-xl shadow-lg hover:from-teal-600 hover:to-green-600 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2h14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                                </svg>
                                Browse Products to Review
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
