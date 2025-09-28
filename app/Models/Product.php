<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
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
        'room_category' => 'array',
        'images' => 'array',
        'gallery' => 'array',
        'meta_data' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
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
     * Get the room category text based on the array values
     */
    public function getRoomCategoryTextAttribute()
    {
        $roomCategories = [
            'bedroom' => 'Bedroom',
            'living-room' => 'Living Room',
            'dining-room' => 'Dining Room',
            'bathroom' => 'Bathroom',
            'office-and-study' => 'Office and Study',
            'garden-and-balcony' => 'Garden and Balcony'
        ];

        if (is_array($this->room_category)) {
            return array_map(function($room) use ($roomCategories) {
                return $roomCategories[$room] ?? ucfirst(str_replace('-', ' ', $room));
            }, $this->room_category);
        }

        return $roomCategories[$this->room_category] ?? 'Unknown';
    }

    /**
     * Validate that subcategory_id is valid for the given category_id
     */
    public function validateSubcategory()
    {
        if (!$this->subcategory_id || !$this->category_id) {
            return true; // Allow null subcategory
        }

        $subcategory = Category::find($this->subcategory_id);
        return $subcategory && $subcategory->parent_id == $this->category_id;
    }

    /**
     * Get valid subcategories for the current category
     */
    public function getValidSubcategories()
    {
        if (!$this->category_id) {
            return collect();
        }

        return Category::where('parent_id', $this->category_id)->get();
    }
}
