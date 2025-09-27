<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1, // Chairs
                'name' => 'Handcrafted Oak Dining Chair',
                'slug' => 'handcrafted-oak-dining-chair',
                'description' => 'Beautifully crafted oak dining chair with traditional joinery and natural finish.',
                'short_description' => 'Traditional oak dining chair with handcrafted details.',
                'price' => 299.99,
                'sku' => 'CHAIR-001',
                'stock_quantity' => 10,
                'material' => 'Solid Oak',
                'dimensions' => '18" W × 20" D × 36" H',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 1, // Chairs
                'name' => 'Modern Walnut Office Chair',
                'slug' => 'modern-walnut-office-chair',
                'description' => 'Contemporary office chair made from premium walnut with ergonomic design.',
                'short_description' => 'Modern walnut office chair for comfortable work.',
                'price' => 449.99,
                'sku' => 'CHAIR-002',
                'stock_quantity' => 8,
                'material' => 'Walnut',
                'dimensions' => '22" W × 22" D × 32" H',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 2, // Tables
                'name' => 'Farmhouse Dining Table',
                'slug' => 'farmhouse-dining-table',
                'description' => 'Large farmhouse-style dining table perfect for family gatherings.',
                'short_description' => 'Spacious farmhouse dining table for the whole family.',
                'price' => 899.99,
                'sku' => 'TABLE-001',
                'stock_quantity' => 5,
                'material' => 'Reclaimed Pine',
                'dimensions' => '72" W × 36" D × 30" H',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 2, // Tables
                'name' => 'Coffee Table with Storage',
                'slug' => 'coffee-table-with-storage',
                'description' => 'Functional coffee table with hidden storage compartments.',
                'short_description' => 'Coffee table with built-in storage solutions.',
                'price' => 399.99,
                'sku' => 'TABLE-002',
                'stock_quantity' => 12,
                'material' => 'Cherry Wood',
                'dimensions' => '48" W × 24" D × 18" H',
                'featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => 3, // Cabinets
                'name' => 'Solid Wood Dresser',
                'slug' => 'solid-wood-dresser',
                'description' => 'Six-drawer dresser made from solid hardwood with dovetail joints.',
                'short_description' => 'Traditional six-drawer dresser with dovetail construction.',
                'price' => 1299.99,
                'sku' => 'CABINET-001',
                'stock_quantity' => 3,
                'material' => 'Hardwood',
                'dimensions' => '60" W × 20" D × 32" H',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 4, // Sofas
                'name' => 'Wooden Frame Sofa',
                'slug' => 'wooden-frame-sofa',
                'description' => 'Three-seat sofa with solid wood frame and premium upholstery.',
                'short_description' => 'Comfortable three-seat sofa with wooden frame.',
                'price' => 1899.99,
                'sku' => 'SOFA-001',
                'stock_quantity' => 2,
                'material' => 'Oak Frame',
                'dimensions' => '84" W × 36" D × 34" H',
                'featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 5, // Stools
                'name' => 'Bar Stool Set',
                'slug' => 'bar-stool-set',
                'description' => 'Set of two adjustable bar stools with wooden construction.',
                'short_description' => 'Set of two adjustable wooden bar stools.',
                'price' => 199.99,
                'sku' => 'STOOL-001',
                'stock_quantity' => 15,
                'material' => 'Pine',
                'dimensions' => '16" W × 16" D × 30" H',
                'featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => 6, // Shelves
                'name' => 'Floating Bookshelf',
                'slug' => 'floating-bookshelf',
                'description' => 'Minimalist floating bookshelf with hidden mounting system.',
                'short_description' => 'Sleek floating bookshelf for modern spaces.',
                'price' => 149.99,
                'sku' => 'SHELF-001',
                'stock_quantity' => 20,
                'material' => 'Birch Plywood',
                'dimensions' => '36" W × 8" D × 10" H',
                'featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
