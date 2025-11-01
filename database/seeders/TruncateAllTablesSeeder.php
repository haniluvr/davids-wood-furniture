<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateAllTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🗑️ Truncating all database tables...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Define tables to exclude from truncation
        $excludedTables = [
            'employees',    // Exclude employees table - contains essential data
            'categories',   // Exclude categories table - contains essential data
        ];

        // Define tables to truncate in correct order (child tables first)
        $tables = [
            // User-related tables (child tables first)
            'product_reviews',
            'order_items',
            'orders',
            'cart_items',
            'carts',
            'wishlist_items',
            'wishlists',
            'users',

            // Product tables (categories excluded)
            'products',

            // Admin tables (employees excluded)
            'admins',

            // Other system tables
            'audit_logs',
            'notifications',
            'contact_messages',
            'cms_pages',
            'settings',
            'payment_methods',
            'inventory_movements',
            'guest_sessions',
            'archived_users',
        ];

        $truncatedCount = 0;
        $skippedCount = 0;

        foreach ($tables as $table) {
            // Skip if table is in excluded list
            if (in_array($table, $excludedTables)) {
                $this->command->info("⏭️ Skipped table (excluded): {$table}");
                $skippedCount++;

                continue;
            }

            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->command->info("✅ Truncated table: {$table}");
                $truncatedCount++;
            } else {
                $this->command->warn("⚠️ Table does not exist: {$table}");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("🎉 Successfully truncated {$truncatedCount} tables");
        if ($skippedCount > 0) {
            $this->command->info("⏭️ Skipped {$skippedCount} excluded table(s): ".implode(', ', $excludedTables));
        }
    }
}
