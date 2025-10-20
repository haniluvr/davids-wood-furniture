<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing tables in correct order (respecting foreign keys)
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('wishlists');

        // Create guest_sessions table
        Schema::create('guest_sessions', function (Blueprint $table) {
            $table->string('session_id', 128)->primary(); // UUID or hashed token
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable(); // Will be set programmatically
        });

        // Create new cart_items table (handles both guest and user)
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // NULL for guests
            $table->string('session_id', 128)->nullable(); // NULL for logged-in users
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->json('product_data')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('session_id')->references('session_id')->on('guest_sessions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id', 'product_id']);
        });

        // Create new wishlist_items table (same pattern as cart)
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id', 128)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->timestamp('created_at')->useCurrent();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('session_id')->references('session_id')->on('guest_sessions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id', 'product_id']);

            // Unique constraints to prevent duplicates
            $table->unique(['user_id', 'product_id'], 'unique_user_product');
            $table->unique(['session_id', 'product_id'], 'unique_session_product');
        });

        // Add database constraints using raw SQL
        DB::statement('
            ALTER TABLE cart_items 
            ADD CONSTRAINT chk_cart_owner 
            CHECK (
                (user_id IS NOT NULL AND session_id IS NULL) OR 
                (user_id IS NULL AND session_id IS NOT NULL)
            )
        ');

        DB::statement('
            ALTER TABLE wishlist_items 
            ADD CONSTRAINT chk_wishlist_owner 
            CHECK (
                (user_id IS NOT NULL AND session_id IS NULL) OR 
                (user_id IS NULL AND session_id IS NOT NULL)
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop constraints first
        DB::statement('ALTER TABLE cart_items DROP CONSTRAINT IF EXISTS chk_cart_owner');
        DB::statement('ALTER TABLE wishlist_items DROP CONSTRAINT IF EXISTS chk_wishlist_owner');

        // Drop tables in correct order
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('guest_sessions');

        // Recreate original tables for rollback
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'session_id']);
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->json('product_data')->nullable();
            $table->timestamps();

            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['cart_id', 'product_id']);
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });
    }
};
