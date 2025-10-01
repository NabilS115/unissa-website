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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('type');
            $table->enum('status', ['active', 'inactive', 'out_of_stock', 'discontinued'])->default('active')->after('is_active');
            $table->integer('stock_quantity')->nullable()->after('status');
            $table->boolean('track_stock')->default(false)->after('stock_quantity');
            $table->integer('low_stock_threshold')->default(5)->after('track_stock');
            $table->timestamp('last_restocked_at')->nullable()->after('low_stock_threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_active',
                'status',
                'stock_quantity',
                'track_stock',
                'low_stock_threshold',
                'last_restocked_at'
            ]);
        });
    }
};
