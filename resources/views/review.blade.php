@extends('layouts.app')

@section('title', 'Product Review')

@section('content')
<div class="max-w-6xl mx-auto mt-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
    <!-- Left: Product & Ratings -->
    <div>
        <button onclick="goBack()" class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-semibold flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back
        </button>
        <div class="w-full flex justify-center mb-6">
            <img src="{{ $product->img }}" alt="{{ $product->name }}"
                 class="max-w-full max-h-80 rounded-lg object-cover bg-gray-100 border"
                 style="width:100%;height:320px;display:block;" />
        </div>
        <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
        <div class="font-semibold mb-1">Product Description</div>
        <div class="mb-6 text-gray-700">{{ $product->desc }}</div>
        <div class="grid grid-cols-2 gap-6 items-center">
            <div>
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
                @foreach($ratings as $star => $count)
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-gray-600 w-4">{{ $star }}</span>
                    <div class="flex-1 h-2 bg-gray-200 rounded">
                        <div class="h-2 bg-yellow-400 rounded"
                             style="width: {{ $totalRatings ? round($count/$totalRatings*100) : 0 }}%"></div>
                    </div>
                    <span class="text-gray-600 w-8 text-right">{{ $count }}</span>
                </div>
                @endforeach
            </div>
            <div class="flex flex-col items-center justify-center">
                <div class="text-5xl font-bold text-gray-700 mb-2">{{ $totalRatings > 0 ? number_format($averageRating, 2) : '0.00' }}</div>
                <div class="flex items-center mb-1">
                    <span class="text-yellow-400 text-2xl">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))★@elseif($i <= ceil($averageRating))☆@else☆@endif
                        @endfor
                    </span>
                </div>
                <div class="text-gray-500 text-sm text-center">Average rating<br>Based on {{ $totalRatings }} {{ $totalRatings === 1 ? 'rating' : 'ratings' }}</div>
            </div>
        </div>
        <div class="mt-6">
            @auth
                <button id="write-review-btn" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold px-6 py-2 rounded-lg shadow transition">Write a review!</button>
            @else
                <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-600 underline">login</a> to write a review.</p>
            @endauth
        </div>
    </div>
    <!-- Right: Reviews -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        {{-- Debug: Show product info and reviews --}}
        {{-- 
        <div class="mb-4 p-2 bg-gray-100 text-sm">
            <strong>Debug Info:</strong><br>
            Product ID: {{ $product->id }}<br>
            Product Name: {{ $product->name }}<br>
            Reviews Count: {{ isset($reviews) ? $reviews->count() : 'No reviews variable' }}<br>
            @if(isset($reviews))
                @foreach($reviews as $review)
                    Review {{ $review->id }}: User {{ $review->user_id }}, Rating {{ $review->rating }}<br>
                @endforeach
            @endif
        </div>
        --}}
        
        <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
            Reviews ({{ isset($reviews) ? $reviews->count() : 0 }})
        </h2>
        <div class="divide-y divide-gray-200" id="reviews-list">
            @if(isset($reviews) && $reviews->count() > 0)
                @foreach($reviews as $review)
                <div class="bg-white rounded-lg border shadow p-4 flex gap-4 items-start mb-4" data-review-id="{{ $review->id }}">
                    <img src="{{ $review->user->profile_photo_url ?? asset('images/default-profile.svg') }}"
                         alt="{{ $review->user->name ?? 'User' }}"
                         class="w-14 h-14 rounded-full object-cover border-2 border-yellow-400 shadow">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-lg text-gray-800">{{ $review->user->name ?? 'User' }}</span>
                                <span class="text-yellow-400 font-bold">★ {{ number_format($review->rating, 1) }}</span>
                            </div>
                            <span class="text-gray-400 text-xs">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="text-gray-700 mb-2 text-base">
                            @php
                                $reviewText = $review->review;
                                $isLongReview = strlen($reviewText) > 200;
                                $truncatedText = $isLongReview ? substr($reviewText, 0, 200) : $reviewText;
                            @endphp
                            
                            @if($isLongReview)
                                <span class="review-text-{{ $review->id }}">{{ $truncatedText }}...</span>
                                <span class="review-full-{{ $review->id }} hidden">{{ $reviewText }}</span>
                                <button class="read-more-btn text-blue-600 hover:text-blue-800 underline ml-1" 
                                        data-review-id="{{ $review->id }}">Read more</button>
                            @else
                                <span>{{ $reviewText }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-6 text-gray-500 text-sm mt-2">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 9l-5 5-5-5"/>
                                </svg> Helpful
                            </span>
                            <a href="#" class="underline">Report</a>
                            @if(Auth::user() && Auth::user()->role === 'admin')
                                <button class="delete-review-btn text-red-600 underline ml-2" data-id="{{ $review->id }}">Delete</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="py-8 text-center text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-lg font-semibold mb-2">No reviews yet</p>
                    <p>Be the first to share your thoughts about this {{ strtolower($product->category ?? 'product') }}!</p>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Write Review Modal -->
<div id="review-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <button id="close-review-modal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <h3 class="text-xl font-bold mb-4">Write a Review</h3>
        <form id="review-form">
            <div class="mb-4">
                <label class="block font-semibold mb-1">Rating</label>
                <select name="rating" class="w-full border rounded px-3 py-2">
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="1">★☆☆☆☆</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Review</label>
                <textarea name="review" class="w-full border rounded px-3 py-2" rows="4" required></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="cancel-review" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-yellow-400 hover:bg-yellow-500 text-white font-semibold">Submit</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Back button functionality
    function goBack() {
        // Check if we have saved catalog state
        const savedState = sessionStorage.getItem('catalogState');
        
        if (savedState) {
            // Go back to catalog with restored state
            sessionStorage.setItem('restoreCatalogState', savedState);
            window.location.href = '/catalog';
        } else if (document.referrer && document.referrer !== window.location.href) {
            window.history.back();
        } else {
            // Fallback to catalog page
            window.location.href = '/catalog';
        }
    }

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
</script>
@endsection
