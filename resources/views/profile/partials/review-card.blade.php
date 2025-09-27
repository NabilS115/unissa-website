<!-- Rating Stars -->
<div class="flex items-center justify-center mb-4">
    <div class="flex items-center gap-1 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-full px-4 py-2 shadow-sm border">
        @for($i = 1; $i <= 5; $i++)
            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/>
            </svg>
        @endfor
        <span class="ml-2 text-lg font-semibold text-gray-700">{{ $review->rating }}.0</span>
    </div>
</div>

<!-- Review Text -->
<div class="text-center mb-6">
    <div class="text-gray-700 leading-relaxed text-lg max-w-2xl mx-auto">
        @php
            $reviewText = $review->review;
            $isLongReview = strlen($reviewText) > 200;
            $truncatedText = $isLongReview ? substr($reviewText, 0, 200) : $reviewText;
        @endphp
        
        @if($isLongReview)
            <div class="review-text-container">
                <p class="review-text-{{ $review->id }} mb-0 text-lg">"{{ $truncatedText }}..."</p>
                <p class="review-full-{{ $review->id }} hidden mb-0 text-lg">"{{ $reviewText }}"</p>
                <button class="read-more-btn text-blue-600 hover:text-blue-800 font-medium text-sm mt-4 inline-flex items-center gap-1 transition-colors" 
                        data-review-id="{{ $review->id }}">
                    <span>Read more</span>
                    <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        @else
            <p class="mb-0 text-lg">"{{ $reviewText }}"</p>
        @endif
    </div>
</div>

<!-- Product Info -->
<div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
    <div class="flex items-center justify-between mb-3">
        <div class="flex-1 min-w-0">
            <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Product Reviewed</div>
            <div class="font-semibold text-gray-900 text-lg">{{ $review->product->name ?? 'Product not found' }}</div>
        </div>
        <div class="text-right ml-4">
            <div class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Review Date</div>
            <div class="text-sm text-gray-700 font-medium">{{ $review->created_at->format('M d, Y') }}</div>
        </div>
    </div>
    
    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
        <a href="/review/{{ $review->product_id }}" 
           class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 font-medium text-sm transition-colors hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            View Product Page
        </a>
        
        @if(($review->helpful_count ?? 0) > 0)
            <div class="flex items-center gap-1 text-gray-500 text-sm">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                </svg>
                <span class="font-medium">{{ $review->helpful_count }} people found this helpful</span>
            </div>
        @endif
    </div>
</div>
