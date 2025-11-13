<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // For MySQL
        DB::statement("ALTER TABLE tbl_orders MODIFY COLUMN payment_method ENUM('cash_on_delivery', 'paypal', 'stripe') NULL");
    }

    public function down()
    {
        // Rollback: remove stripe
        DB::statement("ALTER TABLE tbl_orders MODIFY COLUMN payment_method ENUM('cash_on_delivery', 'paypal') NULL");
    }
};
