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
            // Drop the existing room_category column if it exists
            if (Schema::hasColumn('products', 'room_category')) {
                $table->dropColumn('room_category');
            }
            
            // Add room_category as JSON column
            $table->json('room_category')->nullable()->after('subcategory_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('room_category');
        });
    }
};