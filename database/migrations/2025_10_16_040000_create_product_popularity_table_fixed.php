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
        // Drop table if exists first
        Schema::dropIfExists('product_popularity');

        Schema::create('product_popularity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->comment('Product SKU from products table');
            $table->string('product_name')->comment('Product name from products table');
            $table->integer('wishlist_count')->default(0)->comment('Number of users who added this product to wishlist');
            $table->integer('cart_count')->default(0)->comment('Number of users who added this product to cart');
            $table->integer('total_popularity_score')->default(0)->comment('Combined score for ranking (wishlist_count + cart_count)');
            $table->timestamp('last_updated')->nullable()->comment('When popularity was last calculated');
            $table->timestamps();

            // Indexes for performance
            $table->index('total_popularity_score');
            $table->index('last_updated');
            $table->index('sku');
            $table->unique('product_id'); // One record per product
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_popularity');
    }
};
