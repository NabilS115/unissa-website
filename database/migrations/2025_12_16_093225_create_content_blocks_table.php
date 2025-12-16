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
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'homepage_hero_title'
            $table->string('type')->default('text'); // text, html, image
            $table->text('content'); // The actual content
            $table->string('page')->default('homepage'); // Which page this belongs to
            $table->string('section')->nullable(); // Optional grouping
            $table->integer('order')->default(0); // For ordering content
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
