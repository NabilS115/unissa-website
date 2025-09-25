<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('galleries', 'image_url')) {
                $table->string('image_url')->after('id');
            }
            if (!Schema::hasColumn('galleries', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('image_url');
            }
            if (!Schema::hasColumn('galleries', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });
    }

    public function down()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'is_active', 'sort_order']);
        });
    }
};
