<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tbl_orders', function (Blueprint $table) {
            // Add transaction_id if it doesn't exist
            if (!Schema::hasColumn('tbl_orders', 'transaction_id')) {
                $table->string('transaction_id', 255)->nullable()->after('payment_method');
            }
        });

        // Update payment_method enum to include stripe
        DB::statement("ALTER TABLE tbl_orders MODIFY COLUMN payment_method ENUM('cash_on_delivery', 'paypal', 'stripe') NULL");
    }

    public function down()
    {
        // Remove stripe from enum
        DB::statement("ALTER TABLE tbl_orders MODIFY COLUMN payment_method ENUM('cash_on_delivery', 'paypal') NULL");

        // Remove transaction_id column
        Schema::table('tbl_orders', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
};
