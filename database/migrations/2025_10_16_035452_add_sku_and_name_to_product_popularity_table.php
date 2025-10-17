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
        Schema::table('product_popularity', function (Blueprint $table) {
            if (!Schema::hasColumn('product_popularity', 'sku')) {
                $table->string('sku')->after('product_id')->comment('Product SKU from products table');
            }
            if (!Schema::hasColumn('product_popularity', 'product_name')) {
                $table->string('product_name')->after('sku')->comment('Product name from products table');
            }
            if (!Schema::hasIndex('product_popularity', 'product_popularity_sku_index')) {
                $table->index('sku');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_popularity', function (Blueprint $table) {
            $table->dropIndex(['sku']);
            $table->dropColumn(['sku', 'product_name']);
        });
    }
};
