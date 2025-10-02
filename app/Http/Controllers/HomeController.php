<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
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

        // Only pass gallery images to homepage (featured products and reviews removed from display)
        return view('welcome', compact('galleryImages'));
    }

    private function getFeaturedProductsByRating($type, $limit = 3)
    {
        // Get products with their average ratings (keep this for admin features)
        $products = Product::where('type', $type)
            ->select('products.*')
            ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
            ->selectRaw('AVG(reviews.rating) as avg_rating')
            ->selectRaw('COUNT(reviews.id) as review_count')
            ->groupBy(
                'products.id', 
                'products.name', 
                'products.desc', 
                'products.category', 
                'products.img', 
                'products.type', 
                'products.price',
                'products.is_active',
                'products.status', 
                'products.stock_quantity', 
                'products.track_stock', 
                'products.low_stock_threshold', 
                'products.last_restocked_at',
                'products.created_at', 
                'products.updated_at'
            )
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

