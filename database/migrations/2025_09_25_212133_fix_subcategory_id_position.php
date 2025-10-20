<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to properly reorder the column
        DB::statement('ALTER TABLE products MODIFY COLUMN subcategory_id bigint(20) unsigned NULL AFTER category_id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move subcategory_id back to the end
        DB::statement('ALTER TABLE products MODIFY COLUMN subcategory_id bigint(20) unsigned NULL');
    }
};
