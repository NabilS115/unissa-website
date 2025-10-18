<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cardholder_name')) {
                $table->string('cardholder_name', 100)->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('users', 'card_number')) {
                $table->string('card_number', 32)->nullable()->after('cardholder_name');
            }
            if (!Schema::hasColumn('users', 'card_expiry')) {
                $table->string('card_expiry', 7)->nullable()->after('card_number'); // MM/YYYY
            }
            if (!Schema::hasColumn('users', 'card_ccv')) {
                $table->string('card_ccv', 8)->nullable()->after('card_expiry');
            }
            if (!Schema::hasColumn('users', 'billing_address')) {
                $table->string('billing_address', 255)->nullable()->after('card_ccv');
            }
        });
    }
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cardholder_name')) {
                $table->dropColumn('cardholder_name');
            }
            if (Schema::hasColumn('users', 'card_number')) {
                $table->dropColumn('card_number');
            }
            if (Schema::hasColumn('users', 'card_expiry')) {
                $table->dropColumn('card_expiry');
            }
            if (Schema::hasColumn('users', 'card_ccv')) {
                $table->dropColumn('card_ccv');
            }
            if (Schema::hasColumn('users', 'billing_address')) {
                $table->dropColumn('billing_address');
            }
        });
    }
};
