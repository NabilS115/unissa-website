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
            // Update the enum to include all statuses used in the app
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'ready_for_pickup',
                'picked_up',
                'cancelled'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to the original enum values
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'completed',
                'cancelled'
            ])->default('pending')->change();
        });
    }
};
