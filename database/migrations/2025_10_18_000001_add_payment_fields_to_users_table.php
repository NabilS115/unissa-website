<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payment_method')) {
                $table->string('payment_method', 32)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'payment_details')) {
                $table->text('payment_details')->nullable()->after('payment_method');
            }
        });
    }
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('users', 'payment_details')) {
                $table->dropColumn('payment_details');
            }
        });
    }
};
