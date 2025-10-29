<?php

namespace Database\Seeders;

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
            ProductSeeder::class, // NEW: 200 products using IKEA data as reference
            FilipinoUserSeeder::class, // NEW: 150 Filipino users with complete data
            WishlistItemSeeder::class, // NEW: 300 wishlist items
            CartItemSeeder::class, // NEW: 400 cart items
            EmployeeSeeder::class,
            ProductPopularitySeeder::class, // Calculate product popularity from wishlist and cart data
        ]);
    }
}
