<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateProductPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        
        foreach ($products as $product) {
            // Set different price ranges for food vs merchandise
            if ($product->type === 'food') {
                $price = rand(800, 2500) / 100; // $8.00 to $25.00 for food items
            } else {
                $price = rand(1500, 5000) / 100; // $15.00 to $50.00 for merchandise
            }
            
            $product->update(['price' => $price]);
            
            echo "Updated '{$product->name}' with price $" . number_format($price, 2) . "\n";
        }
        
        echo "\nAll product prices updated successfully!\n";
    }
}
