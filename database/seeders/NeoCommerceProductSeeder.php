<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class NeoCommerceProductSeeder extends Seeder
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

        // Get or create categories
        $electronics = Category::firstOrCreate(['name' => 'Electronics', 'slug' => 'electronics']);
        $furniture = Category::firstOrCreate(['name' => 'Furniture', 'slug' => 'furniture']);
        $shoes = Category::firstOrCreate(['name' => 'Shoes', 'slug' => 'shoes']);

        // Products matching the design image
        $products = [
            [
                'category_id' => $electronics->id,
                'name' => 'Galaxy Smartwatch X2',
                'slug' => 'galaxy-smartwatch-x2',
                'description' => 'Advanced smartwatch with health monitoring, GPS, and long battery life.',
                'short_description' => 'Premium smartwatch with advanced features',
                'price' => 210.50,
                'sku' => 'WATCH-001',
                'stock_quantity' => 400,
                'material' => 'Aluminum',
                'dimensions' => '44mm x 38mm x 10.7mm',
                'featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'Smart Air Purifier',
                'slug' => 'smart-air-purifier',
                'description' => 'HEPA air purifier with smart controls and air quality monitoring.',
                'short_description' => 'Smart HEPA air purifier for clean air',
                'price' => 175.80,
                'sku' => 'AIR-001',
                'stock_quantity' => 320,
                'material' => 'ABS Plastic',
                'dimensions' => '30cm x 30cm x 60cm',
                'featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $furniture->id,
                'name' => 'Ergo Comfort Chair',
                'slug' => 'ergo-comfort-chair',
                'description' => 'Ergonomic office chair with lumbar support and adjustable height.',
                'short_description' => 'Comfortable ergonomic office chair',
                'price' => 145.90,
                'sku' => 'CHAIR-001',
                'stock_quantity' => 275,
                'material' => 'Mesh and Foam',
                'dimensions' => '65cm x 65cm x 120cm',
                'featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'category_id' => $shoes->id,
                'name' => 'Nike Runner Edge',
                'slug' => 'nike-runner-edge',
                'description' => 'Lightweight running shoes with advanced cushioning technology.',
                'short_description' => 'Professional running shoes for athletes',
                'price' => 130.00,
                'sku' => 'SHOE-001',
                'stock_quantity' => 450,
                'material' => 'Synthetic Mesh',
                'dimensions' => 'US 7-12 Available',
                'featured' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'AirPods Neo',
                'slug' => 'airpods-neo',
                'description' => 'Wireless earbuds with noise cancellation and premium sound quality.',
                'short_description' => 'Premium wireless earbuds',
                'price' => 155.40,
                'sku' => 'AUDIO-001',
                'stock_quantity' => 360,
                'material' => 'Plastic and Silicone',
                'dimensions' => '30mm x 21mm x 24mm',
                'featured' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'Pocket Mini Speaker',
                'slug' => 'pocket-mini-speaker',
                'description' => 'Compact Bluetooth speaker with powerful sound and long battery life.',
                'short_description' => 'Portable Bluetooth speaker',
                'price' => 95.20,
                'sku' => 'SPEAKER-001',
                'stock_quantity' => 620,
                'material' => 'Aluminum and Fabric',
                'dimensions' => '8cm x 8cm x 4cm',
                'featured' => true,
                'is_active' => true,
                'sort_order' => 6,
            ],
            // Additional products to make the catalog more robust
            [
                'category_id' => $electronics->id,
                'name' => 'Wireless Gaming Mouse',
                'slug' => 'wireless-gaming-mouse',
                'description' => 'High-precision wireless gaming mouse with RGB lighting.',
                'short_description' => 'Professional gaming mouse',
                'price' => 89.99,
                'sku' => 'MOUSE-001',
                'stock_quantity' => 180,
                'material' => 'ABS Plastic',
                'dimensions' => '12cm x 7cm x 4cm',
                'featured' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'category_id' => $furniture->id,
                'name' => 'Standing Desk Converter',
                'slug' => 'standing-desk-converter',
                'description' => 'Adjustable standing desk converter for healthier work habits.',
                'short_description' => 'Ergonomic standing desk solution',
                'price' => 299.00,
                'sku' => 'DESK-001',
                'stock_quantity' => 85,
                'material' => 'Steel and Wood',
                'dimensions' => '80cm x 50cm x 15cm',
                'featured' => false,
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'category_id' => $shoes->id,
                'name' => 'Classic Leather Boots',
                'slug' => 'classic-leather-boots',
                'description' => 'Premium leather boots with waterproof protection.',
                'short_description' => 'Durable leather boots',
                'price' => 189.50,
                'sku' => 'BOOT-001',
                'stock_quantity' => 125,
                'material' => 'Genuine Leather',
                'dimensions' => 'US 7-12 Available',
                'featured' => false,
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'category_id' => $electronics->id,
                'name' => '4K Webcam Pro',
                'slug' => '4k-webcam-pro',
                'description' => 'Ultra HD webcam with auto-focus and noise reduction microphone.',
                'short_description' => 'Professional 4K webcam',
                'price' => 125.75,
                'sku' => 'CAM-001',
                'stock_quantity' => 95,
                'material' => 'Plastic and Glass',
                'dimensions' => '10cm x 5cm x 8cm',
                'featured' => false,
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('NeoCommerce products seeded successfully!');
        $this->command->info('Created ' . count($products) . ' products across ' . Category::count() . ' categories.');
    }
}