<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewHelpful;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class ReviewController extends Controller
{

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        // Only the review owner can update
        if (auth()->id() !== $review->user_id && (!auth()->user() || auth()->user()->role !== 'admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this review.'
            ], 403);
        }
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();
        
        // Clear review-related caches
        $this->clearReviewCaches($review->product_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully!',
            'review' => $review->load('user')
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Clear review-related caches
        $this->clearReviewCaches($request->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => $review->load('user')
        ]);
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $review = Review::create([
            'product_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Clear review-related caches
        $this->clearReviewCaches($id);

        return back()->with('success', 'Your review has been submitted successfully!');
    }

    public function markHelpful(Request $request, Review $review)
    {
        $userId = Auth::id();
        
        $existingVote = ReviewHelpful::where('review_id', $review->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            $existingVote->delete();
            $review->decrement('helpful_count');
            $action = 'removed';
        } else {
            ReviewHelpful::create([
                'review_id' => $review->id,
                'user_id' => $userId,
            ]);
            $review->increment('helpful_count');
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'helpful_count' => $review->fresh()->helpful_count
        ]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        // Get reviews with user information
        $reviews = Review::where('product_id', $id)
            ->with(['user', 'helpfulVotes'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate rating statistics
        $ratings = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            if ($rating >= 1 && $rating <= 5) {
                $ratings[$rating]++;
            }
        }

        $totalRatings = array_sum($ratings);
        $averageRating = 0;
        
        if ($totalRatings > 0) {
            $weightedSum = 0;
            foreach ($ratings as $star => $count) {
                $weightedSum += $star * $count;
            }
            $averageRating = $weightedSum / $totalRatings;
        }

        return view('product-detail', compact('product', 'reviews', 'ratings', 'averageRating', 'totalRatings'));
    }

    public function helpful(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        return $this->markHelpful($request, $review);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        // Check if the authenticated user is the review owner or an admin
        if (auth()->id() !== $review->user_id && auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this review.'
            ], 403);
        }

        // Delete all helpful votes for this review first
        $review->helpfulVotes()->delete();
        
        // Delete the review
        $review->delete();

        // Clear review-related caches
        $this->clearReviewCaches($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.'
        ]);
    }
    
    /**
     * Clear review-related caches when reviews are modified
     */
    private function clearReviewCaches($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            // Clear product detail cache (includes reviews)
            Cache::forget("product.detail.{$productId}");
            
            // Clear featured products caches (reviews affect rating-based sorting)
            Cache::forget("products.featured.{$product->type}");
            
            // Clear testimonials cache
            Cache::forget('reviews.testimonials');
        }
    }
}
