<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            // defines the structure of the 'images' table
            $table->id();
            $table->string('name')->unique();
            $table->string('mime_type');
            $table->binary('data'); // Use binary() for compatibility
            $table->timestamps();
        });

        // Alter the column to MEDIUMBLOB for larger images
        DB::statement('ALTER TABLE images MODIFY data MEDIUMBLOB');
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
};
