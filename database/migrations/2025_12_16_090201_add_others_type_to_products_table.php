<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the type enum to include 'others'
        DB::statement("ALTER TABLE products MODIFY COLUMN type ENUM('food', 'merch', 'others')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the type enum to original values
        DB::statement("ALTER TABLE products MODIFY COLUMN type ENUM('food', 'merch')");
    }
};
