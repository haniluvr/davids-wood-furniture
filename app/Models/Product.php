<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

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

    protected $appends = [
        'average_rating',
        'reviews_count',
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
     * Format: A-BB-CC where A=main_category, BB=sub_category, CC=product_number
     */
    public function generateCustomProductId($mainCategoryId, $subCategoryId, $productNumber)
    {
        return sprintf('%d%02d%02d', $mainCategoryId, $subCategoryId, $productNumber);
    }

    /**
     * Parse custom product ID to extract components
     */
    public function parseCustomProductId($customId)
    {
        if (strlen($customId) != 5) {
            return null;
        }

        return [
            'main_category' => (int) substr($customId, 0, 1),
            'sub_category' => (int) substr($customId, 1, 2),
            'product_number' => (int) substr($customId, 3, 2),
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
            'garden-and-balcony' => 'Garden and Balcony',
        ];

        if (is_array($this->room_category)) {
            return array_map(function ($room) use ($roomCategories) {
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
        if (! $this->subcategory_id || ! $this->category_id) {
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
        if (! $this->category_id) {
            return collect();
        }

        return Category::where('parent_id', $this->category_id)->get();
    }

    // Inventory relationships
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Inventory helper methods
    public function isLowStock($threshold = 10): bool
    {
        return $this->stock_quantity <= $threshold && $this->stock_quantity > 0;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'text-red-600',
            'low_stock' => 'text-yellow-600',
            'in_stock' => 'text-green-600',
            default => 'text-gray-600',
        };
    }

    public function getStockStatusBadgeColorAttribute(): string
    {
        return match ($this->stock_status) {
            'out_of_stock' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'low_stock' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'in_stock' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    // Scopes for inventory management
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock_quantity', '<=', $threshold)->where('stock_quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Reviews relationship
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    // Get average rating
    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    // Get total reviews count
    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }
}
