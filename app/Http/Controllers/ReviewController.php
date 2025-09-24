<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
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
        return view('review', compact('product', 'reviews'));
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
}
