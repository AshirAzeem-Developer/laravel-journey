<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_categories', function (Blueprint $table) {
            // Add the description column (assuming varchar is fine, maybe text if it's long)
            $table->string('description', 500)->nullable()->after('category_name');

            // Add the category image column (stores the path to the image)
            $table->string('category_image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_categories', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('category_image');
        });
    }
};
