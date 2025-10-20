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

            // Product and category tables
            'products',
            'categories',

            // Admin and employee tables
            'admins',
            'employees',

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

        foreach ($tables as $table) {
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
    }
}
