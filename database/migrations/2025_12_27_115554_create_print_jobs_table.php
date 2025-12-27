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
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('file_type'); // pdf, doc, image, etc.
            $table->integer('file_size'); // in bytes
            $table->enum('paper_size', ['A4', 'A3', 'Letter', 'Legal'])->default('A4');
            $table->enum('color_option', ['black_white', 'color'])->default('black_white');
            $table->enum('paper_type', ['regular', 'photo', 'cardstock'])->default('regular');
            $table->integer('copies')->default(1);
            $table->enum('orientation', ['portrait', 'landscape'])->default('portrait');
            $table->decimal('price_per_page', 8, 2)->default(0.00);
            $table->decimal('total_price', 8, 2)->default(0.00);
            $table->integer('page_count')->default(1);
            $table->enum('status', ['uploaded', 'processing', 'ready', 'completed', 'cancelled'])->default('uploaded');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
