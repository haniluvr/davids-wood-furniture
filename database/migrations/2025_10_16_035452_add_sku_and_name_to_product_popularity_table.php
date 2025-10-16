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
            $table->string('sku')->after('product_id')->comment('Product SKU from products table');
            $table->string('product_name')->after('sku')->comment('Product name from products table');
            $table->index('sku');
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
