<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentMethodEnumInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // If using ENUM, change to include 'bank_transfer'
            \DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cash','online','bank_transfer') NOT NULL");
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to original ENUM
            \DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cash','online') NOT NULL");
        });
    }
}
