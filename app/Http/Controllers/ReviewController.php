<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewHelpful;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
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

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'review' => $review->load('user')
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully.'
        ]);
    }
}
