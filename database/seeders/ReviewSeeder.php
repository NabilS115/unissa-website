<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample review data with different users
        $sampleReviews = [
            [
                'name' => 'Danish Naufal',
                'email' => 'danish.naufal@example.com',
                'reviews' => [
                    [
                        'rating' => 5,
                        'review' => 'The best pancake that I ever eaten for the past 10 years! The texture is perfectly fluffy and the taste is absolutely divine. Worth every penny!',
                        'product_name' => 'Homemade Pancakes'
                    ]
                ]
            ],
            [
                'name' => 'Aisyah Rahman',
                'email' => 'aisyah.rahman@example.com',
                'reviews' => [
                    [
                        'rating' => 5,
                        'review' => 'Absolutely delicious! The pancakes are fluffy and taste just like home. My family loved them so much that we ordered again the next day.',
                        'product_name' => 'Homemade Pancakes'
                    ]
                ]
            ],
            [
                'name' => 'Hitstonecold Ayeeee',
                'email' => 'hitstonecold@example.com',
                'reviews' => [
                    [
                        'rating' => 4,
                        'review' => 'It is worth the price. Not much to say, but the quality is consistent and delivery was fast.',
                        'product_name' => 'Homemade Pancakes'
                    ]
                ]
            ],
            [
                'name' => 'Sarah Mitchell',
                'email' => 'sarah.mitchell@example.com',
                'reviews' => [
                    [
                        'rating' => 5,
                        'review' => 'Outstanding quality and exceptional taste! This has become my go-to order whenever I want something special. Highly recommended!',
                        'product_name' => 'Gourmet Burger'
                    ]
                ]
            ],
            [
                'name' => 'Ahmed Hassan',
                'email' => 'ahmed.hassan@example.com',
                'reviews' => [
                    [
                        'rating' => 4,
                        'review' => 'Great product with authentic flavors. The portion size is generous and the packaging was excellent. Will definitely order again.',
                        'product_name' => 'Chicken Biryani'
                    ]
                ]
            ],
            [
                'name' => 'Maria Gonzalez',
                'email' => 'maria.gonzalez@example.com',
                'reviews' => [
                    [
                        'rating' => 5,
                        'review' => 'Fantastic experience! The food arrived hot and fresh, and the flavors were incredible. This exceeded all my expectations.',
                        'product_name' => 'Italian Pasta'
                    ]
                ]
            ]
        ];

        foreach ($sampleReviews as $userData) {
            // Create or find user
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'), // Default password
                    'role' => 'user'
                ]
            );

            foreach ($userData['reviews'] as $reviewData) {
                // Find product by name (or create if doesn't exist)
                $product = Product::where('name', 'like', '%' . $reviewData['product_name'] . '%')->first();
                
                if ($product) {
                    // Check if review already exists to avoid duplicates
                    $existingReview = Review::where('user_id', $user->id)
                                          ->where('product_id', $product->id)
                                          ->first();
                                          
                    if (!$existingReview) {
                        Review::create([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'rating' => $reviewData['rating'],
                            'review' => $reviewData['review'],
                            'helpful_count' => rand(0, 15)
                        ]);
                    }
                }
            }
        }
    }
}
