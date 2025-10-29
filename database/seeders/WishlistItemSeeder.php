<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Database\Seeder;

class WishlistItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wishlistItemCount = 300;

        // Get all user IDs and product IDs
        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        if (empty($userIds) || empty($productIds)) {
            $this->command->error('No users or products found. Please run UserSeeder and ProductSeeder first.');

            return;
        }

        $createdItems = 0;
        $attempts = 0;
        $maxAttempts = $wishlistItemCount * 3; // Prevent infinite loop

        while ($createdItems < $wishlistItemCount && $attempts < $maxAttempts) {
            $attempts++;

            // Get random user and product
            $userId = fake()->randomElement($userIds);
            $productId = fake()->randomElement($productIds);

            // Check if this combination already exists
            $existingItem = WishlistItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if (! $existingItem) {
                WishlistItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                ]);

                $createdItems++;
            }
        }

        $this->command->info("Created {$createdItems} wishlist items successfully!");

        if ($createdItems < $wishlistItemCount) {
            $this->command->warn("Only created {$createdItems} wishlist items due to unique constraints. ".
                'This is normal if there are limited users and products.');
        }
    }
}
