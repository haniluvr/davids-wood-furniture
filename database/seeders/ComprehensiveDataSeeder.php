<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder runs all the new comprehensive data seeders in the correct order.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive data seeding...');

        // Run seeders in order
        $this->call([
            ProductSeeder::class,
            FilipinoUserSeeder::class,
            WishlistItemSeeder::class,
            CartItemSeeder::class,
        ]);

        $this->command->info('Comprehensive data seeding completed successfully!');
        $this->command->info('Created:');
        $this->command->info('- 200 products with IKEA-inspired data');
        $this->command->info('- 150 Filipino users with complete addresses');
        $this->command->info('- 300 wishlist items');
        $this->command->info('- 400 cart items');
    }
}
