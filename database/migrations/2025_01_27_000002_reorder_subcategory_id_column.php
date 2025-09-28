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
        Schema::table('products', function (Blueprint $table) {
            // Drop the existing subcategory_id column
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn('subcategory_id');
        });

        Schema::table('products', function (Blueprint $table) {
            // Add subcategory_id column right after category_id
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->onDelete('set null')->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the reordered subcategory_id column
            $table->dropForeign(['subcategory_id']);
            $table->dropColumn('subcategory_id');
        });

        Schema::table('products', function (Blueprint $table) {
            // Add subcategory_id column back to its original position (after room_category)
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->onDelete('set null')->after('room_category');
        });
    }
};
