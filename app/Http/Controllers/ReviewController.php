<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewHelpful;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $reviews = Review::where('product_id', $id)
            ->with('user') // eager load user for each review
            ->latest()
            ->get();
        return view('product-detail', compact('product', 'reviews'));
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['success' => true]);
    }

    public function helpful(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Please login to mark reviews as helpful'], 401);
        }

        $review = Review::findOrFail($id);
        $user = auth()->user();

        // Check if user already marked this review as helpful
        $existingHelpful = ReviewHelpful::where('user_id', $user->id)
                                      ->where('review_id', $review->id)
                                      ->first();

        if ($existingHelpful) {
            // Remove helpful mark
            $existingHelpful->delete();
            $review->decrement('helpful_count');
            $action = 'removed';
        } else {
            // Add helpful mark
            ReviewHelpful::create([
                'user_id' => $user->id,
                'review_id' => $review->id,
            ]);
            $review->increment('helpful_count');
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'action' => $action,
            'helpful_count' => $review->fresh()->helpful_count,
        ]);
    }
}
