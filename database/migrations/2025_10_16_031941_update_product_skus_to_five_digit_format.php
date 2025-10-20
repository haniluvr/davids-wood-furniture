<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing products with the new 5-digit SKU format
        $products = Product::with('category')->get();

        foreach ($products as $product) {
            // Skip if SKU is already in 5-digit format
            if (strlen($product->sku) == 5 && is_numeric($product->sku)) {
                continue;
            }

            // Get category information
            $category = $product->category;
            if (! $category) {
                continue;
            }

            // Determine main category based on category hierarchy
            $mainCategoryId = $this->getMainCategoryId($category);
            $subCategoryId = $this->getSubCategoryId($category);

            // Generate new SKU using the corrected format
            $newSku = sprintf('%d%02d%02d', $mainCategoryId, $subCategoryId, $product->id % 100);

            // Update the product SKU
            $product->update(['sku' => $newSku]);

            echo "Updated product {$product->id} ({$product->name}) SKU from {$product->getOriginal('sku')} to {$newSku}\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible as we don't store the original SKUs
        // You would need to restore from a backup if needed
        echo "This migration cannot be automatically reversed. Please restore from backup if needed.\n";
    }

    /**
     * Get main category ID based on category hierarchy
     */
    private function getMainCategoryId($category)
    {
        // If category has a parent, use parent's ID, otherwise use category's own ID
        if ($category->parent_id) {
            return $category->parent_id;
        }

        return $category->id;
    }

    /**
     * Get subcategory ID based on category hierarchy
     */
    private function getSubCategoryId($category)
    {
        // If category has a parent, use category's own ID, otherwise use 1 as default
        if ($category->parent_id) {
            return $category->id;
        }

        return 1; // Default subcategory for main categories
    }
};
