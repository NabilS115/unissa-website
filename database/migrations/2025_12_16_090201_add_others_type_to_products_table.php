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
        // Check database driver and use appropriate syntax
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support ENUM or MODIFY COLUMN
            // Use string type with constraint check instead
            Schema::table('products', function (Blueprint $table) {
                $table->string('type_new')->default('food');
            });
            
            // Copy data from old column to new column
            DB::statement("UPDATE products SET type_new = type");
            
            // Drop old column and rename new column
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('type');
            });
            
            Schema::table('products', function (Blueprint $table) {
                $table->renameColumn('type_new', 'type');
            });
        } else {
            // MySQL/PostgreSQL - Update the type enum to include 'others'
            DB::statement("ALTER TABLE products MODIFY COLUMN type ENUM('food', 'merch', 'others')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check database driver and use appropriate syntax
        if (DB::getDriverName() === 'sqlite') {
            // SQLite approach - recreate column without 'others'
            Schema::table('products', function (Blueprint $table) {
                $table->string('type_new')->default('food');
            });
            
            // Copy data, excluding 'others' type
            DB::statement("UPDATE products SET type_new = type WHERE type IN ('food', 'merch')");
            DB::statement("UPDATE products SET type_new = 'food' WHERE type = 'others'");
            
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('type');
            });
            
            Schema::table('products', function (Blueprint $table) {
                $table->renameColumn('type_new', 'type');
            });
        } else {
            // MySQL/PostgreSQL - Revert the type enum to original values
            DB::statement("ALTER TABLE products MODIFY COLUMN type ENUM('food', 'merch')");
        }
    }
};
