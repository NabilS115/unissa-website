<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name', 64)->nullable()->after('billing_address');
            }
            if (!Schema::hasColumn('users', 'bank_account')) {
                $table->string('bank_account', 64)->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('users', 'bank_reference')) {
                $table->string('bank_reference', 128)->nullable()->after('bank_account');
            }
        });
    }
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bank_name')) {
                $table->dropColumn('bank_name');
            }
            if (Schema::hasColumn('users', 'bank_account')) {
                $table->dropColumn('bank_account');
            }
            if (Schema::hasColumn('users', 'bank_reference')) {
                $table->dropColumn('bank_reference');
            }
        });
    }
};
