<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\Gallery;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products based on average ratings
        $featuredFood = $this->getFeaturedProductsByRating('food', 3);
        $featuredMerch = $this->getFeaturedProductsByRating('merch', 3);
        
        // Get active gallery images with error handling
        try {
            $galleryImages = Gallery::active()->ordered()->get()->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image_url' => $gallery->getImageUrlAttribute(),
                    'is_active' => $gallery->is_active,
                    'sort_order' => $gallery->sort_order,
                ];
            });
        } catch (\Exception $e) {
            // Fallback to empty collection if Gallery table doesn't exist yet
            $galleryImages = collect([]);
        }

        // Get active vendors with error handling
        try {
            $vendors = Vendor::active()->ordered()->get()->map(function ($vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'type' => $vendor->type,
                    'description' => $vendor->description,
                    'image_url' => $vendor->getImageUrlAttribute(),
                    'is_active' => $vendor->is_active,
                    'sort_order' => $vendor->sort_order,
                ];
            });
        } catch (\Exception $e) {
            // Fallback to empty collection if Vendor table doesn't exist yet
            $vendors = collect([]);
        }

        // Get featured customer reviews (highest rated reviews) with proper error handling
        try {
            $featuredReviews = Review::with(['user', 'product'])
                ->where('rating', '>=', 4) // Only 4-5 star reviews
                ->whereHas('user') // Make sure user exists
                ->whereHas('product') // Make sure product exists
                ->select('reviews.*')
                ->join('users', 'reviews.user_id', '=', 'users.id')
                ->orderBy('rating', 'desc')
                ->orderBy('reviews.created_at', 'desc')
                ->limit(10) // Get more reviews to have variety
                ->get()
                ->groupBy('user_id') // Group by user to ensure different users
                ->map(function($userReviews) {
                    return $userReviews->first(); // Take the best review from each user
                })
                ->take(6); // Take up to 6 different users
        } catch (\Exception $e) {
            // Fallback to empty collection if there's an error
            $featuredReviews = collect([]);
        }

        return view('welcome', compact('featuredFood', 'featuredMerch', 'galleryImages', 'featuredReviews', 'vendors'));
    }

    private function getFeaturedProductsByRating($type, $limit = 3)
    {
        // Get products with their average ratings
        $products = Product::where('type', $type)
            ->select('products.*')
            ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
            ->selectRaw('AVG(reviews.rating) as avg_rating')
            ->selectRaw('COUNT(reviews.id) as review_count')
            ->groupBy('products.id', 'products.name', 'products.desc', 'products.category', 'products.img', 'products.type', 'products.created_at', 'products.updated_at')
            ->havingRaw('COUNT(reviews.id) > 0') // Only products with reviews
            ->orderByDesc('avg_rating')
            ->orderByDesc('review_count') // Secondary sort by review count
            ->limit($limit)
            ->get();

        // Calculate the exact average rating for each product (same logic as catalog)
        foreach ($products as $product) {
            $reviews = Review::where('product_id', $product->id)->get();
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
            
            $product->calculated_rating = number_format($averageRating, 1);
            $product->review_count = $totalRatings;
        }

        return $products;
    }

    public function manageFeatured()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Show current featured products (top rated)
        $featuredFood = $this->getFeaturedProductsByRating('food', 10); // Show more for admin view
        $featuredMerch = $this->getFeaturedProductsByRating('merch', 10);

        // Get all products for reference
        $allFood = Product::where('type', 'food')->get();
        $allMerch = Product::where('type', 'merch')->get();

        return view('admin.featured', compact('featuredFood', 'featuredMerch', 'allFood', 'allMerch'));
    }
}

