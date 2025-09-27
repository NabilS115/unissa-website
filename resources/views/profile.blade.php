@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <!-- Cover Background -->
            <div class="h-32 bg-gradient-to-r from-teal-400 via-teal-500 to-green-500"></div>
            
            <!-- Profile Content -->
            <div class="relative px-8 pb-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between -mt-16">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-6">
                        <!-- Profile Picture -->
                        <div class="relative">
                            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" 
                                 alt="Profile Picture" 
                                 class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-white">
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-sm"></div>
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="lg:mb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name ?? 'Dr. Ahmad bin Ali' }}</h1>
                                <a href="/edit-profile" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-all duration-200" title="Edit Profile">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13z" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex items-center gap-4 text-gray-600 mb-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                    </svg>
                                    <span class="font-medium">{{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                    <span class="font-medium">{{ Auth::user()->reviews->count() }} Reviews</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delete Account Button -->
                    <div class="lg:mb-4">
                        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 border border-red-200 hover:border-red-300 transition-all duration-200 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column: Profile Details -->
            <div class="xl:col-span-1 space-y-6">
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
                            <div class="text-gray-900 font-medium">{{ Auth::user()->name ?? 'Dr. Ahmad bin Ali' }}</div>
                        </div>
                        
                        <div class="border-l-4 border-blue-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Email Address</div>
                            <div class="text-gray-900 font-medium">{{ Auth::user()->email ?? 'name@unissa.edu.bn' }}</div>
                        </div>
                        
                        <div class="border-l-4 border-green-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Phone Number</div>
                            <div class="text-gray-900 font-medium">{{ Auth::user()->phone ?? '+673 xxxx xxxx' }}</div>
                        </div>
                        
                        <div class="border-l-4 border-purple-400 pl-4">
                            <div class="text-sm font-medium text-gray-500 mb-1">Faculty / Department</div>
                            <div class="text-gray-900 font-medium">{{ Auth::user()->department ?? 'Faculty of Usuluddin' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Bio Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Bio & Background</h2>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-600 italic">Academic background and professional interests will be displayed here.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Reviews -->
            <div class="xl:col-span-2">
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
                                <p class="text-gray-600">{{ Auth::user()->reviews->count() }} {{ Auth::user()->reviews->count() === 1 ? 'review' : 'reviews' }} shared</p>
                            </div>
                        </div>
                        
                        @if(Auth::user()->reviews->count() > 1)
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Use arrows to navigate reviews
                            </div>
                        @endif
                    </div>

                    @if(Auth::user()->reviews->count() > 0)
                        <!-- Reviews Display Area -->
                        <div class="bg-gray-50 rounded-xl p-6 min-h-[400px]">
                            @if(Auth::user()->reviews->count() > 1)
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
                                        @foreach(Auth::user()->reviews as $index => $review)
                                            <button onclick="goToReview({{ $index }})" 
                                                    class="review-dot w-3 h-3 rounded-full transition-all duration-200 {{ $index === 0 ? 'bg-teal-500 w-8' : 'bg-gray-300 hover:bg-gray-400' }}"
                                                    data-index="{{ $index }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <!-- Single Review Display -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    @include('profile.partials.review-card', ['review' => Auth::user()->reviews->first()])
                                </div>
                            @endif
                        </div>
                        
                        <script>
                            @if(Auth::user()->reviews->count() > 1)
                            let currentReview = 0;
                            const totalReviews = {{ Auth::user()->reviews->count() }};
                            
                            // Set initial width for carousel track
                            document.addEventListener('DOMContentLoaded', function() {
                                const track = document.getElementById('reviews-track');
                                if (track && totalReviews > 1) {
                                    track.style.width = `${totalReviews * 100}%`;
                                    track.querySelectorAll('.w-full').forEach((slide, index) => {
                                        slide.style.width = `${100 / totalReviews}%`;
                                    });
                                }
                                
                                // Initialize read more functionality
                                initializeReadMoreButtons();
                            });
                            
                            function moveReview(dir) {
                                if (totalReviews <= 1) return;
                                
                                const track = document.getElementById('reviews-track');
                                currentReview = (currentReview + dir + totalReviews) % totalReviews;
                                const translateX = -(currentReview * (100 / totalReviews));
                                track.style.transform = `translateX(${translateX}%)`;
                                updateReviewDots();
                            }
                            
                            function goToReview(index) {
                                if (totalReviews <= 1) return;
                                
                                const track = document.getElementById('reviews-track');
                                currentReview = index;
                                const translateX = -(currentReview * (100 / totalReviews));
                                track.style.transform = `translateX(${translateX}%)`;
                                updateReviewDots();
                            }
                            
                            function updateReviewDots() {
                                document.querySelectorAll('.review-dot').forEach((dot, index) => {
                                    if (index === currentReview) {
                                        dot.className = 'review-dot w-8 h-3 rounded-full bg-teal-500 transition-all duration-200';
                                    } else {
                                        dot.className = 'review-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-200';
                                    }
                                });
                            }
                            @else
                            document.addEventListener('DOMContentLoaded', function() {
                                initializeReadMoreButtons();
                            });
                            @endif

                            function initializeReadMoreButtons() {
                                document.querySelectorAll('.read-more-btn').forEach(btn => {
                                    btn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        
                                        const reviewId = this.getAttribute('data-review-id');
                                        const truncatedText = document.querySelector(`.review-text-${reviewId}`);
                                        const fullText = document.querySelector(`.review-full-${reviewId}`);
                                        const buttonText = this.querySelector('span') || this;
                                        const icon = this.querySelector('svg');
                                        
                                        if (buttonText.textContent.includes('Read more')) {
                                            if (truncatedText) truncatedText.classList.add('hidden');
                                            if (fullText) fullText.classList.remove('hidden');
                                            buttonText.textContent = 'Read less';
                                            if (icon) icon.style.transform = 'rotate(180deg)';
                                        } else {
                                            if (truncatedText) truncatedText.classList.remove('hidden');
                                            if (fullText) fullText.classList.add('hidden');
                                            buttonText.textContent = 'Read more';
                                            if (icon) icon.style.transform = 'rotate(0deg)';
                                        }
                                    });
                                });
                            }
                        </script>
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
