<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('mime_type');
            $table->binary('data'); // binary() works fine in both MySQL and SQLite
            $table->timestamps();
        });

        // Run this only if the DB driver supports MODIFY (i.e., MySQL)
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE images MODIFY data MEDIUMBLOB');
        }
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
};

