@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <div class="flex items-center gap-4 mb-6">
        <div class="relative group" id="profile-photo-group">
            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-teal-600 cursor-pointer" id="profile-photo-trigger">
            <button type="button" id="profile-photo-icon" class="absolute inset-0 flex items-center justify-center w-full h-full bg-transparent rounded-full focus:outline-none" style="z-index:2;">
                <svg xmlns="http://www.w3.org/2000/svg" class="opacity-0 group-hover:opacity-80 transition" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white">
                    <circle cx="12" cy="13" r="3.2" stroke="white" stroke-width="2" fill="none" />
                    <rect x="4" y="7" width="16" height="12" rx="3" stroke="white" stroke-width="2" fill="none" />
                    <rect x="9" y="3" width="6" height="4" rx="2" stroke="white" stroke-width="2" fill="none" />
                </svg>
            </button>
            <div id="profile-photo-menu" class="absolute left-1/2 top-full mt-2 w-28 bg-white border border-teal-600 rounded-lg shadow-lg py-1 opacity-0 pointer-events-none z-50 transform -translate-x-1/2 transition text-sm">
                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <label for="profile_photo" class="block px-4 py-2 text-teal-700 hover:bg-teal-50 hover:text-teal-900 cursor-pointer transition rounded">
                        Upload New Photo
                        <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" onchange="this.form.submit()">
                    </label>
                </form>
                @if(Auth::user()->profile_photo_url)
                <form method="POST" action="{{ route('profile.photo.delete') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 hover:text-red-800 transition rounded">Delete Photo</button>
                </form>
                @endif
            </div>
            <script>
                const trigger = document.getElementById('profile-photo-trigger');
                const icon = document.getElementById('profile-photo-icon');
                const menu = document.getElementById('profile-photo-menu');
                function toggleMenu() {
                    menu.classList.toggle('opacity-0');
                    menu.classList.toggle('pointer-events-none');
                }
                trigger.addEventListener('click', toggleMenu);
                icon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMenu();
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !trigger.contains(e.target) && !icon.contains(e.target)) {
                        menu.classList.add('opacity-0');
                        menu.classList.add('pointer-events-none');
                    }
                });
            </script>
        </div>
        <div>
            <div class="text-2xl font-bold text-teal-700">{{ Auth::user()->name }}</div>
            <div class="text-gray-600">Role: {{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</div>
        </div>
    </div>
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input name="name" id="name" type="text" value="{{ old('name', Auth::user()->name) }}" required autofocus autocomplete="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" />
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input name="email" id="email" type="email" value="{{ old('email', Auth::user()->email) }}" required autocomplete="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" />
            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div class="mt-2">
                    <span class="text-sm text-yellow-600">Your email address is unverified.</span>
                    @if (session('status') === 'verification-link-sent')
                        <span class="block mt-2 font-medium text-green-600">A new verification link has been sent to your email address.</span>
                    @endif
                </div>
            @endif
        </div>
        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition w-full">Save</button>
            @if (session('profile-updated'))
                <span class="me-3 text-green-600 font-medium">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
    <hr class="my-8">
    <form method="POST" action="{{ route('profile.password') }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="text-xl font-bold text-teal-700 mb-2">Change Password</div>
        <div class="relative">
            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input name="current_password" id="current_password" type="password" required autocomplete="current-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 pr-12" />
            <button type="button" class="absolute right-2 top-8 text-gray-500 hover:text-teal-600" onclick="togglePassword('current_password', this)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
        </div>
        <div class="relative">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input name="password" id="password" type="password" required autocomplete="new-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 pr-12" />
            <button type="button" class="absolute right-2 top-8 text-gray-500 hover:text-teal-600" onclick="togglePassword('password', this)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
        </div>
        <div class="relative">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input name="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 pr-12" />
            <button type="button" class="absolute right-2 top-8 text-gray-500 hover:text-teal-600" onclick="togglePassword('password_confirmation', this)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
        </div>
        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition w-full">Change Password</button>
        </div>
    </form>
    @if (session('password-updated'))
        <div id="password-toast" class="fixed top-6 right-6 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            {{ __('Password updated.') }}
        </div>
        <script>
            setTimeout(function() {
                var toast = document.getElementById('password-toast');
                if (toast) toast.style.display = 'none';
            }, 3000);
        </script>
    @endif
    
    <!-- User Reviews Section -->
    <hr class="my-8">
    <div class="mb-4 font-semibold text-lg">My Reviews ({{ Auth::user()->reviews->count() }})</div>
    @if(Auth::user()->reviews->count() > 0)
        <div class="relative flex items-center justify-center max-w-xl mx-auto">
            <button onclick="moveReview(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 bg-transparent border border-transparent text-teal-600 hover:bg-teal-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full z-10 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <div id="reviews-carousel" class="overflow-hidden w-full">
                <div id="reviews-track" class="flex transition-transform duration-500 ease-in-out" style="width: {{ Auth::user()->reviews->count() * 100 }}%">
                    @foreach(Auth::user()->reviews as $review)
                        <div class="min-w-full px-4">
                            <div class="bg-white rounded-xl shadow-lg p-6 border flex flex-col gap-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-yellow-400 text-2xl">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $review->rating ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                </div>
                                
                                <div class="text-gray-700 leading-relaxed mb-3 break-words">
                                    @php
                                        $reviewText = $review->review;
                                        $isLongReview = strlen($reviewText) > 150;
                                        $truncatedText = $isLongReview ? substr($reviewText, 0, 150) : $reviewText;
                                    @endphp
                                    
                                    @if($isLongReview)
                                        <div class="review-text-container">
                                            <span class="review-text-{{ $review->id }} block">"{{ $truncatedText }}..."</span>
                                            <span class="review-full-{{ $review->id }} hidden block whitespace-pre-wrap break-words">"{{ $reviewText }}"</span>
                                            <button class="read-more-btn text-blue-600 hover:text-blue-800 font-medium text-sm mt-2 block" 
                                                    data-review-id="{{ $review->id }}">Read more</button>
                                        </div>
                                    @else
                                        <span class="block whitespace-pre-wrap break-words">"{{ $reviewText }}"</span>
                                    @endif
                                </div>
                                
                                <div class="text-gray-600 text-sm">Product: {{ $review->product->name ?? 'Product not found' }}</div>
                                <div class="text-gray-500 text-xs">{{ $review->created_at->format('M d, Y') }}</div>
                                <div class="flex gap-2 mt-2">
                                    <a href="/review/{{ $review->product_id }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">View Product</a>
                                    @if($review->helpful_count > 0)
                                        <span class="text-gray-500 text-sm">• {{ $review->helpful_count }} found helpful</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button onclick="moveReview(1)" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border border-transparent text-teal-600 hover:bg-teal-600 hover:text-white w-10 h-10 flex items-center justify-center rounded-full z-10 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>
        
        <!-- Review Navigation Dots -->
        @if(Auth::user()->reviews->count() > 1)
            <div class="flex justify-center mt-4 gap-2">
                @foreach(Auth::user()->reviews as $index => $review)
                    <button onclick="goToReview({{ $index }})" 
                            class="review-dot w-2 h-2 rounded-full transition-colors {{ $index === 0 ? 'bg-teal-600' : 'bg-gray-300' }}"
                            data-index="{{ $index }}"></button>
                @endforeach
            </div>
        @endif
        
        <script>
            let currentReview = 0;
            const totalReviews = {{ Auth::user()->reviews->count() }};
            
            function moveReview(dir) {
                if (totalReviews <= 1) return;
                
                const track = document.getElementById('reviews-track');
                currentReview = (currentReview + dir + totalReviews) % totalReviews;
                track.style.transform = `translateX(-${currentReview * 100}%)`;
                updateReviewDots();
            }
            
            function goToReview(index) {
                if (totalReviews <= 1) return;
                
                const track = document.getElementById('reviews-track');
                currentReview = index;
                track.style.transform = `translateX(-${currentReview * 100}%)`;
                updateReviewDots();
            }
            
            function updateReviewDots() {
                document.querySelectorAll('.review-dot').forEach((dot, index) => {
                    if (index === currentReview) {
                        dot.classList.add('bg-teal-600');
                        dot.classList.remove('bg-gray-300');
                    } else {
                        dot.classList.remove('bg-teal-600');
                        dot.classList.add('bg-gray-300');
                    }
                });
            }
            
            // Read more functionality
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.read-more-btn').forEach(btn => {
                    btn.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // Prevent carousel movement
                        
                        const reviewId = this.getAttribute('data-review-id');
                        const truncatedText = document.querySelector(`.review-text-${reviewId}`);
                        const fullText = document.querySelector(`.review-full-${reviewId}`);
                        
                        if (this.textContent === 'Read more') {
                            truncatedText.classList.add('hidden');
                            fullText.classList.remove('hidden');
                            this.textContent = 'Read less';
                        } else {
                            truncatedText.classList.remove('hidden');
                            fullText.classList.add('hidden');
                            this.textContent = 'Read more';
                        }
                    };
                });
            });
        </script>
    @else
        <div class="text-center py-8 bg-gray-50 rounded-lg">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Reviews Yet</h3>
            <p class="text-gray-600 mb-4">You haven't written any reviews yet. Share your experiences with products!</p>
            <a href="/catalog" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v2M7 7h10"/>
                </svg>
                Browse Products
            </a>
        </div>
    @endif

    <script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            btn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.933A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />';
        } else {
            input.type = 'password';
            btn.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
    </script>
</div>
@endsection
