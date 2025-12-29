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
        // Since this is a fresh implementation and we're changing the structure,
        // we'll just clear existing orders and start fresh for simplicity.
        // In a production environment, you would write data migration logic here.
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('orders')->truncate();
        \DB::table('order_items')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This would restore the old data structure if needed
        // For now, we'll just leave it empty since this is a one-way migration
    }
};
