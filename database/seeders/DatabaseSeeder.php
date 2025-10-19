<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed core data
        $this->call([
            TruncateAllTablesSeeder::class,  // NEW: Truncate all tables first
            CategorySeeder::class,
            ProductRepopulationSeeder::class, // Use the IKEA-based product seeder with more products
            EmployeeSeeder::class,
            RealisticDataSeeder::class, // Generate realistic customer data (includes users)
            ProductPopularitySeeder::class, // Calculate product popularity from wishlist and cart data
        ]);
    }
}
