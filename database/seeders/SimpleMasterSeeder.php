<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SimpleMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Simple version that works reliably without external API calls
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Simple Master Seeder...');
        $this->command->info('================================================');

        // Phase 0: Ensure all migrations are run
        $this->command->info('🔄 Phase 0: Ensuring all migrations are run...');
        $this->command->call('migrate', ['--force' => true]);

        // Phase 1: Clear and Setup Core System
        $this->command->info('📋 Phase 1: Clear and Setup Core System');
        $this->call([
            TruncateAllTablesSeeder::class,        // Clear all existing data
            CategorySeeder::class,                 // Create product categories
        ]);

        // Phase 2: Products
        $this->command->info('🪑 Phase 2: Products');
        $this->call([
            ProductRepopulationSeeder::class,      // Main product seeder (155+ products)
            UpdateProductWeightAndDimensionsSeeder::class, // Add weight/dimensions
        ]);

        // Phase 3: Employees Only
        $this->command->info('👥 Phase 3: Employees Only');
        $this->call([
            EmployeeSeeder::class,                 // Create employee accounts only
        ]);

        // Phase 4: Users and ALL Related Data
        $this->command->info('🛒 Phase 4: Users and ALL Related Data');
        $this->command->info('   Creating users, orders, cart_items, wishlist_items, reviews...');
        $this->call([
            SimpleRealisticDataSeeder::class,      // Creates 75 users + orders + cart_items + wishlist_items + reviews
        ]);

        // Phase 5: Additional Orders
        $this->command->info('📦 Phase 5: Additional Orders');
        $this->call([
            RepopulateOrdersSeeder::class,         // Specific test orders for user 76
        ]);

        // Phase 6: System Configuration
        $this->command->info('⚙️ Phase 6: System Configuration');
        $this->call([
            PaymentGatewaySeeder::class,           // Payment gateways
            CmsPageSeeder::class,                  // CMS pages
        ]);

        // Phase 7: Final Calculations
        $this->command->info('📊 Phase 7: Final Calculations');
        $this->call([
            ProductPopularitySeeder::class,        // Calculate product popularity
        ]);

        $this->command->info('================================================');
        $this->command->info('✅ Simple Master Seeder completed successfully!');
        $this->command->info('🎉 Your database now has:');
        $this->command->info('   • 75+ users with realistic data');
        $this->command->info('   • 150+ orders with proper statuses');
        $this->command->info('   • Shopping carts and wishlists');
        $this->command->info('   • Product reviews for delivered orders');
        $this->command->info('   • 155+ furniture products');
        $this->command->info('   • Employee accounts');
        $this->command->info('   • Payment gateways and CMS pages');
        $this->command->info('================================================');
    }
}


