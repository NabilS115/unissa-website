@extends('layouts.app')

@section('title', 'Product Review')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb and Back Button -->
        <div class="mb-8">
            <button onclick="goBack()" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 border border-gray-200 transition-all duration-200 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                <span id="back-button-text">Back to Catalog</span>
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left: Product Information -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-8">
                    <!-- Product Image -->
                    <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 p-8">
                        <img src="{{ $product->img }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover rounded-xl shadow-md" />
                    </div>
                    
                    <!-- Product Details -->
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-teal-500 to-green-500 rounded-full">
                                {{ ucfirst($product->type ?? 'Product') }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                        <p class="text-gray-600 leading-relaxed mb-6">{{ $product->desc }}</p>
                        
                        <!-- Category Badge -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                            </svg>
                            {{ $product->category }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Ratings and Reviews -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Rating Summary -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    @php
                        // Calculate actual ratings distribution from reviews
                        $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                        if (isset($reviews) && $reviews->count() > 0) {
                            foreach ($reviews as $review) {
                                $rating = (int) $review->rating;
                                if ($rating >= 1 && $rating <= 5) {
                                    $ratings[$rating]++;
                                }
                            }
                        }
                        $totalRatings = array_sum($ratings);
                        
                        // Calculate average rating from actual reviews
                        $averageRating = 0;
                        if ($totalRatings > 0) {
                            $weightedSum = 0;
                            foreach ($ratings as $star => $count) {
                                $weightedSum += $star * $count;
                            }
                            $averageRating = $weightedSum / $totalRatings;
                        }
                    @endphp

                    <div class="flex items-start justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Customer Reviews</h2>
                            <p class="text-gray-600">Based on {{ $totalRatings }} {{ $totalRatings === 1 ? 'review' : 'reviews' }}</p>
                        </div>
                        @auth
                            <button id="write-review-btn" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold rounded-xl shadow-lg hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                                Write a Review
                            </button>
                        @else
                            <div class="text-center">
                                <p class="text-gray-600 mb-3">Want to share your experience?</p>
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Login to Review
                                </a>
                            </div>
                        @endauth
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Overall Rating -->
                        <div class="text-center">
                            <div class="text-6xl font-bold text-gray-900 mb-2">
                                {{ $totalRatings > 0 ? number_format($averageRating, 1) : '0.0' }}
                            </div>
                            <div class="flex justify-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-gray-600">out of 5 stars</p>
                        </div>

                        <!-- Rating Breakdown -->
                        <div class="space-y-3">
                            @for($star = 5; $star >= 1; $star--)
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700 w-6">{{ $star }}</span>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                    </svg>
                                    <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full transition-all duration-700"
                                             style="width: {{ $totalRatings ? round($ratings[$star]/$totalRatings*100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-8 text-right">{{ $ratings[$star] }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"/>
                        </svg>
                        All Reviews ({{ isset($reviews) ? $reviews->count() : 0 }})
                    </h3>

                    <div class="space-y-6" id="reviews-list">
                        @if(isset($reviews) && $reviews->count() > 0)
                            @foreach($reviews as $review)
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow duration-200" data-review-id="{{ $review->id }}">
                                    <div class="flex gap-4">
                                        <img src="{{ $review->user->profile_photo_url ?? asset('images/default-profile.svg') }}"
                                             alt="{{ $review->user->name ?? 'User' }}"
                                             class="w-12 h-12 rounded-full object-cover border-2 border-yellow-400 shadow-sm flex-shrink-0">
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-3">
                                                    <h4 class="font-semibold text-gray-900">{{ $review->user->name ?? 'User' }}</h4>
                                                    <div class="flex items-center gap-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                            
                                            <div class="text-gray-700 leading-relaxed mb-4 break-words">
                                                @php
                                                    $reviewText = $review->review;
                                                    $isLongReview = strlen($reviewText) > 300;
                                                    $truncatedText = $isLongReview ? substr($reviewText, 0, 300) : $reviewText;
                                                @endphp
                                                
                                                @if($isLongReview)
                                                    <span class="review-text-{{ $review->id }} block">{{ $truncatedText }}...</span>
                                                    <span class="review-full-{{ $review->id }} hidden block">{{ $reviewText }}</span>
                                                    <button class="read-more-btn text-blue-600 hover:text-blue-800 font-medium ml-1 mt-2" 
                                                            data-review-id="{{ $review->id }}">Read more</button>
                                                @else
                                                    <span class="block">{{ $reviewText }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                @if(!Auth::user() || Auth::user()->id !== $review->user_id)
                                                    <button class="helpful-btn flex items-center gap-1 hover:text-gray-700 transition-colors {{ Auth::user() && $review->isHelpfulBy(Auth::user()) ? 'text-blue-600' : 'text-gray-500' }}" data-review-id="{{ $review->id }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                                        </svg>
                                                        Helpful <span class="helpful-count">({{ $review->helpful_count ?? 0 }})</span>
                                                    </button>
                                                @else
                                                    <span class="flex items-center gap-1 text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.20-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                                        </svg>
                                                        Helpful <span class="helpful-count">({{ $review->helpful_count ?? 0 }})</span>
                                                    </span>
                                                @endif
                                                @if(Auth::user() && Auth::user()->role === 'admin')
                                                    <button class="delete-review-btn text-red-600 hover:text-red-800 font-medium transition-colors" data-id="{{ $review->id }}">
                                                        Delete Review
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-12">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No reviews yet</h3>
                                <p class="text-gray-600 mb-6">Be the first to share your thoughts about this {{ strtolower($product->category ?? 'product') }}!</p>
                                @auth
                                    <button onclick="document.getElementById('write-review-btn').click()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold rounded-xl shadow-lg hover:from-yellow-500 hover:to-yellow-600 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Write the First Review
                                    </button>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Write Review Modal -->
<div id="review-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <button id="close-review-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Write a Review</h3>
                <p class="text-gray-600">Share your experience with {{ $product->name }}</p>
            </div>

            <form id="review-form" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">How would you rate this product?</label>
                    <div class="flex gap-1 justify-center mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn w-10 h-10 text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="5">
                    <p class="text-center text-sm text-gray-500" id="rating-text">Excellent</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Tell us about your experience</label>
                    <textarea name="review" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-yellow-400 focus:border-transparent resize-none" rows="4" placeholder="What did you like or dislike? What did you use this product for?" required></textarea>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" id="cancel-review" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-xl hover:from-yellow-500 hover:to-yellow-600 font-semibold transition-all duration-200 shadow-lg">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Back button functionality
    function goBack() {
        // Check if we have saved catalog state
        const savedState = sessionStorage.getItem('catalogState');
        
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                
                // If coming from homepage, go back to homepage
                if (state.source === 'homepage') {
                    sessionStorage.removeItem('catalogState'); // Clean up
                    window.location.href = '/';
                    return;
                }
                
                // Otherwise, go back to catalog with restored state
                sessionStorage.setItem('restoreCatalogState', savedState);
                window.location.href = '/catalog';
            } catch (e) {
                // If parsing fails, fallback to catalog
                window.location.href = '/catalog';
            }
        } else if (document.referrer && document.referrer !== window.location.href) {
            window.history.back();
        } else {
            // Fallback to catalog page
            window.location.href = '/catalog';
        }
    }

    // Update back button text based on source
    document.addEventListener('DOMContentLoaded', function() {
        const savedState = sessionStorage.getItem('catalogState');
        const backButtonText = document.getElementById('back-button-text');
        
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                if (state.source === 'homepage') {
                    backButtonText.textContent = 'Back to Homepage';
                }
            } catch (e) {
                // Keep default text if parsing fails
            }
        }
    });

    // Modal logic
    const modal = document.getElementById('review-modal');
    const writeReviewBtn = document.getElementById('write-review-btn');
    
    if (writeReviewBtn) {
        writeReviewBtn.onclick = () => { modal.classList.remove('hidden'); };
    }
    
    document.getElementById('close-review-modal').onclick = () => { modal.classList.add('hidden'); };
    document.getElementById('cancel-review').onclick = () => { modal.classList.add('hidden'); };

    document.getElementById('review-form').onsubmit = async function(e) {
        e.preventDefault();
        const rating = this.rating.value;
        const reviewText = this.review.value;
        
        // Add loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        
        try {
            const res = await fetch("{{ route('review.add', $product->id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    rating: rating,
                    review: reviewText,
                    product_id: {{ $product->id }}
                })
            });
            
            const data = await res.json();
            
            if (res.ok) {
                window.location.reload();
            } else {
                console.error('Server response:', data);
                alert(data.message || "Failed to submit review. Please try again.");
            }
        } catch (error) {
            console.error('Network error:', error);
            alert("Network error. Please check your connection and try again.");
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    };

    document.querySelectorAll('.delete-review-btn').forEach(btn => {
        btn.onclick = async function(e) {
            e.preventDefault();
            if (!confirm('Delete this review?')) return;
            const reviewId = this.getAttribute('data-id');
            try {
                const res = await fetch(`{{ url('/reviews') }}/${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                if (res.ok) {
                    document.querySelector(`[data-review-id="${reviewId}"]`).remove();
                    // Reload page to update ratings statistics
                    window.location.reload();
                } else {
                    alert('Failed to delete review.');
                }
            } catch {
                alert('Network error.');
            }
        };
    });

    // Read more functionality
    document.querySelectorAll('.read-more-btn').forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
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
    
    // Star rating functionality
    const starBtns = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');
    const ratingTexts = ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
    
    starBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            const rating = index + 1;
            ratingInput.value = rating;
            ratingText.textContent = ratingTexts[index];
            
            starBtns.forEach((star, i) => {
                if (i < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        });
        
        btn.addEventListener('mouseenter', () => {
            const rating = index + 1;
            starBtns.forEach((star, i) => {
                if (i < rating) {
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                }
            });
        });
    });
    
    // Set initial 5-star rating
    starBtns.forEach((star, i) => {
        if (i < 5) {
            star.classList.add('text-yellow-400');
        }
    });
    
    // Helpful button functionality
    document.querySelectorAll('.helpful-btn').forEach(btn => {
        btn.onclick = async function(e) {
            e.preventDefault();
            
            @auth
                const reviewId = this.getAttribute('data-review-id');
                const countSpan = this.querySelector('.helpful-count');
                
                try {
                    const res = await fetch(`{{ url('/reviews') }}/${reviewId}/helpful`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await res.json();
                    
                    if (res.ok) {
                        countSpan.textContent = `(${data.helpful_count})`;
                        
                        if (data.action === 'added') {
                            this.classList.add('text-blue-600');
                            this.classList.remove('text-gray-500');
                        } else {
                            this.classList.remove('text-blue-600');
                            this.classList.add('text-gray-500');
                        }
                    } else {
                        alert(data.message || 'Failed to mark as helpful');
                    }
                } catch (error) {
                    console.error('Network error:', error);
                    alert('Network error. Please try again.');
                }
            @else
                alert('Please login to mark reviews as helpful');
            @endauth
        };
    });
</script>
@endsection
