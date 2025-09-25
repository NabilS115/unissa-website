<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the table if it exists and recreate it with correct structure
        Schema::dropIfExists('galleries');
        
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('image_url');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galleries');
    }
};
