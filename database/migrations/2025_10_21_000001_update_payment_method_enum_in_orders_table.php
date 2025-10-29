<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\MigrationHelper;

class UpdatePaymentMethodEnumInOrdersTable extends Migration
{
    public function up()
    {
        MigrationHelper::safeStatement(
            "ALTER TABLE orders MODIFY payment_method ENUM('cash','online','bank_transfer') NOT NULL"
        );
    }

    public function down()
    {
        MigrationHelper::safeStatement(
            "ALTER TABLE orders MODIFY payment_method ENUM('cash','online') NOT NULL"
        );
    }
}
