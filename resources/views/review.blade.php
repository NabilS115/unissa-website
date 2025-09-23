@extends('layouts.app')

@section('title', 'Product Review')

@section('content')
<div class="max-w-6xl mx-auto mt-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
    <!-- Left: Product & Ratings -->
    <div>
        <button onclick="window.history.back()" class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-semibold flex items-center gap-2">
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
                    $ratings = [1 => 4, 2 => 7, 3 => 12, 4 => 34, 5 => 50];
                    $totalRatings = array_sum($ratings);
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
                <div class="text-5xl font-bold text-gray-700 mb-2">{{ number_format($product->rating ?? 4.11, 2) }}</div>
                <div class="flex items-center mb-1">
                    <span class="text-yellow-400 text-2xl">★★★★☆</span>
                </div>
                <div class="text-gray-500 text-sm text-center">Average rating<br>Based on {{ $totalRatings }} rating</div>
            </div>
        </div>
        <div class="mt-6">
            <button id="write-review-btn" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold px-6 py-2 rounded-lg shadow transition">Write a review!</button>
        </div>
    </div>
    <!-- Right: Reviews -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        {{-- Debug: Show reviews array --}}
        {{-- @dump($reviews) --}}
        <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
            Reviews
        </h2>
        <div class="divide-y divide-gray-200" id="reviews-list">
            @isset($reviews)
                @forelse($reviews as $review)
                <div class="py-6 flex gap-6 items-start">
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
                        <div class="text-gray-700 mb-2 text-base">{{ $review->review }}</div>
                        <div class="flex items-center gap-6 text-gray-500 text-sm mt-2">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 9l-5 5-5-5"/>
                                </svg> Helpful
                            </span>
                            <a href="#" class="underline">Report</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-400">No reviews yet. Be the first to write one!</div>
                @endforelse
            @endisset
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
    // Modal logic
    const modal = document.getElementById('review-modal');
    document.getElementById('write-review-btn').onclick = () => { modal.classList.remove('hidden'); };
    document.getElementById('close-review-modal').onclick = () => { modal.classList.add('hidden'); };
    document.getElementById('cancel-review').onclick = () => { modal.classList.add('hidden'); };

    document.getElementById('review-form').onsubmit = async function(e) {
        e.preventDefault();
        const rating = this.rating.value;
        const reviewText = this.review.value;
        // Send review to backend
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
                    review: reviewText
                })
            });
            if (res.ok) {
                // Optionally, fetch and re-render all reviews from backend here
                // For now, just add to frontend
                const data = await res.json();
                const reviewsList = document.getElementById('reviews-list');
                const newReview = document.createElement('div');
                newReview.className = "bg-white rounded-lg border shadow p-4 flex gap-4 items-start";
                const profilePhoto = "{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}";
                newReview.innerHTML = `
                    <img src="${profilePhoto}" alt="You" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <span class="font-semibold">You</span>
                                <span class="text-yellow-400 ml-2 text-sm">★ ${rating}</span>
                            </div>
                            <span class="text-gray-400 text-xs">${new Date().toLocaleDateString()}</span>
                        </div>
                        <div class="text-gray-700 mb-2">${reviewText}</div>
                        <div class="flex items-center gap-4 text-gray-500 text-sm">
                            <span>👍 0</span>
                            <a href="#" class="underline">Report</a>
                        </div>
                    </div>
                `;
                reviewsList.prepend(newReview);
                modal.classList.add('hidden');
                this.reset();
            } else {
                alert("Failed to submit review.");
            }
        } catch {
            alert("Network error.");
        }
    };
</script>
@endsection
