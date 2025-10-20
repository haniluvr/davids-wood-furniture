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
        Schema::table('wishlist_items', function (Blueprint $table) {
            // Drop the foreign key constraint that's causing issues
            $table->dropForeign(['session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlist_items', function (Blueprint $table) {
            // Re-add the foreign key constraint if needed
            $table->foreign('session_id')->references('session_id')->on('guest_sessions')->onDelete('cascade');
        });
    }
};
