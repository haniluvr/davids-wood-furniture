<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cartItemCount = 400;

        // Get all user IDs and product IDs
        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        if (empty($userIds) || empty($productIds)) {
            $this->command->error('No users or products found. Please run UserSeeder and ProductSeeder first.');

            return;
        }

        $createdItems = 0;
        $attempts = 0;
        $maxAttempts = $cartItemCount * 3; // Prevent infinite loop

        while ($createdItems < $cartItemCount && $attempts < $maxAttempts) {
            $attempts++;

            // Get random user and product
            $userId = $userIds[array_rand($userIds)];
            $productId = $productIds[array_rand($productIds)];

            // Check if this combination already exists
            $existingItem = CartItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if (! $existingItem) {
                // Get product details for pricing
                $product = Product::find($productId);

                if (! $product) {
                    continue;
                }

                // Generate random quantity (1-5 items)
                $quantity = rand(1, 5);

                // Use current price (sale price if available, otherwise regular price)
                $unitPrice = $product->sale_price ?? $product->price;
                $totalPrice = $unitPrice * $quantity;

                // Generate product data for cart item
                $productData = [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'slug' => $product->slug,
                    'image' => $product->images[0] ?? null,
                    'material' => $product->material,
                    'dimensions' => $product->dimensions,
                    'weight' => $product->weight,
                ];

                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_data' => $productData,
                ]);

                $createdItems++;
            }
        }

        $this->command->info("Created {$createdItems} cart items successfully!");

        if ($createdItems < $cartItemCount) {
            $this->command->warn("Only created {$createdItems} cart items due to unique constraints. ".
                'This is normal if there are limited users and products.');
        }
    }
}
