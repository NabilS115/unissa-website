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
        Schema::table('print_jobs', function (Blueprint $table) {
            // First, drop the existing enum constraint
            $table->dropColumn('paper_type');
        });

        Schema::table('print_jobs', function (Blueprint $table) {
            // Add the new enum with updated values
            $table->enum('paper_type', ['printing', 'photocopy'])->default('printing')->after('color_option');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn('paper_type');
        });

        Schema::table('print_jobs', function (Blueprint $table) {
            $table->enum('paper_type', ['regular', 'photo', 'cardstock'])->default('regular')->after('color_option');
        });
    }
};
