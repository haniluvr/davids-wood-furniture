<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'room_category',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'weight',
        'dimensions',
        'material',
        'images',
        'gallery',
        'featured',
        'is_active',
        'sort_order',
        'meta_data',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'featured' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
        'gallery' => 'array',
        'meta_data' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function wishlists(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->sale_price < $this->price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    /**
     * Generate a custom product ID based on category and product sequence
     * Format: AABB where A=main_category, B=sub_category, CC=product_number
     */
    public function generateCustomProductId($mainCategoryId, $subCategoryId, $productNumber)
    {
        return sprintf('%d%d%02d', $mainCategoryId, $subCategoryId, $productNumber);
    }

    /**
     * Parse custom product ID to extract components
     */
    public function parseCustomProductId($customId)
    {
        if (strlen($customId) != 4) {
            return null;
        }
        
        return [
            'main_category' => (int) substr($customId, 0, 1),
            'sub_category' => (int) substr($customId, 1, 1),
            'product_number' => (int) substr($customId, 2, 2)
        ];
    }

    /**
     * Get the room category text based on the numeric value
     */
    public function getRoomCategoryTextAttribute()
    {
        $roomCategories = [
            1 => 'Bedroom',
            2 => 'Living Room',
            3 => 'Dining Room',
            4 => 'Bathroom',
            5 => 'Office and Study',
            6 => 'Garden and Balcony'
        ];

        return $roomCategories[$this->room_category] ?? 'Unknown';
    }
}
