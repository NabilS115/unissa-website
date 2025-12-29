<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove product-specific columns since these will be in order_items
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'quantity', 'unit_price']);
            
            // Keep total_price at order level as the sum of all order items
            // All other fields remain the same for order-level information
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Restore the removed columns
            $table->foreignId('product_id')->nullable()->constrained();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
        });
    }
};
