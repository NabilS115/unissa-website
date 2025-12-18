<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class TestOthersProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:others-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test products with type "others" by duplicating existing food products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get some existing food products to duplicate
        $foodProducts = Product::where('type', 'food')->limit(3)->get();
        
        if ($foodProducts->isEmpty()) {
            $this->error('No food products found to duplicate!');
            return 1;
        }

        // Clear existing "others" products first
        Product::where('type', 'others')->delete();
        $this->info('Cleared existing "others" products');

        // Create new "others" products based on food products
        $othersCategories = ['Electronics', 'Books', 'Home & Garden'];
        $othersNames = ['Wireless Headphones', 'Recipe Book Collection', 'Indoor Plant Set'];
        
        foreach ($foodProducts as $index => $foodProduct) {
            $newProduct = Product::create([
                'name' => $othersNames[$index] ?? "Others Product " . ($index + 1),
                'desc' => "This is a test " . strtolower($othersCategories[$index] ?? 'others') . " product created for testing the others section.",
                'category' => $othersCategories[$index] ?? 'General',
                'img' => $foodProduct->img, // Use same image
                'type' => 'others',
                'price' => $foodProduct->price + 5, // Slightly different price
                'is_active' => true,
                'status' => 'active',
                'track_stock' => true,
                'stock_quantity' => 50,
                'low_stock_threshold' => 10,
            ]);
            
            $this->info("Created others product: {$newProduct->name} (ID: {$newProduct->id})");
        }

        // Clear caches
        Cache::forget('products.browse.others');
        Cache::forget('products.categories');
        $this->info('Cleared product caches');

        $this->info('✅ Successfully created ' . count($foodProducts) . ' test "others" products!');
        return 0;
    }
}
