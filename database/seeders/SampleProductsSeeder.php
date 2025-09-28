<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class SampleProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products (disable foreign key checks temporarily)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get subcategories
        $kingQueenBed = Category::where('slug', 'king-queen-sized-bed')->first();
        $diningTable = Category::where('slug', 'dining-table')->first();

        // Sample Product 1: King Size Bed
        Product::create([
            'category_id' => 1, // Beds
            'subcategory_id' => $kingQueenBed->id,
            'room_category' => ['bedroom'], // JSON array for multiple room categories
            'name' => 'Premium King Size Oak Bed',
            'slug' => 'premium-king-size-oak-bed',
            'description' => 'A beautifully crafted king size bed made from premium oak wood. Features a sturdy frame with elegant design perfect for any bedroom.',
            'short_description' => 'Premium oak king size bed with elegant design',
            'price' => 1299.99,
            'sale_price' => 1099.99,
            'sku' => 'BED001',
            'stock_quantity' => 15,
            'manage_stock' => true,
            'in_stock' => true,
            'weight' => '85 kg',
            'dimensions' => '200cm x 200cm x 45cm',
            'material' => 'Oak Wood',
            'images' => ['bed1.jpg', 'bed2.jpg'],
            'gallery' => ['bed1.jpg', 'bed2.jpg', 'bed3.jpg'],
            'featured' => true,
            'is_active' => true,
            'sort_order' => 1,
            'meta_data' => [
                'warranty' => '2 years',
                'assembly_required' => true,
                'color' => 'Natural Oak'
            ],
        ]);

        // Sample Product 2: Dining Table
        Product::create([
            'category_id' => 4, // Tables
            'subcategory_id' => $diningTable->id,
            'room_category' => ['dining-room', 'living-room'], // Multiple room categories
            'name' => 'Modern Walnut Dining Table',
            'slug' => 'modern-walnut-dining-table',
            'description' => 'A contemporary dining table crafted from solid walnut wood. Seats 6 people comfortably and features a sleek, modern design that complements any dining space.',
            'short_description' => 'Modern walnut dining table seating 6 people',
            'price' => 899.99,
            'sale_price' => null,
            'sku' => 'TAB001',
            'stock_quantity' => 8,
            'manage_stock' => true,
            'in_stock' => true,
            'weight' => '65 kg',
            'dimensions' => '180cm x 90cm x 75cm',
            'material' => 'Walnut Wood',
            'images' => ['table1.jpg', 'table2.jpg'],
            'gallery' => ['table1.jpg', 'table2.jpg', 'table3.jpg'],
            'featured' => false,
            'is_active' => true,
            'sort_order' => 2,
            'meta_data' => [
                'warranty' => '3 years',
                'assembly_required' => true,
                'seating_capacity' => 6,
                'color' => 'Dark Walnut'
            ],
        ]);
    }
}
