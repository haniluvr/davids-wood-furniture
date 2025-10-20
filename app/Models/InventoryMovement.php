<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'reason',
        'notes',
        'reference_type',
        'reference_id',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'previous_stock' => 'integer',
        'new_stock' => 'integer',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Helper methods
    public function getFormattedQuantityAttribute()
    {
        $sign = $this->type === 'out' ? '-' : '+';

        return $sign.abs($this->quantity);
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'in' => 'text-green-600',
            'out' => 'text-red-600',
            'adjustment' => 'text-blue-600',
            default => 'text-gray-600',
        };
    }

    public function getTypeBadgeColorAttribute()
    {
        return match ($this->type) {
            'in' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'out' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'adjustment' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    // Static methods for creating movements
    public static function recordStockIn($productId, $quantity, $reason, $notes = null, $referenceType = null, $referenceId = null, $createdBy = null)
    {
        $product = Product::findOrFail($productId);
        $previousStock = $product->stock_quantity;
        $newStock = $previousStock + $quantity;

        // Update product stock
        $product->update(['stock_quantity' => $newStock]);

        // Record movement
        return self::create([
            'product_id' => $productId,
            'type' => 'in',
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reason' => $reason,
            'notes' => $notes,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'created_by' => $createdBy,
        ]);
    }

    public static function recordStockOut($productId, $quantity, $reason, $notes = null, $referenceType = null, $referenceId = null, $createdBy = null)
    {
        $product = Product::findOrFail($productId);
        $previousStock = $product->stock_quantity;
        $newStock = max(0, $previousStock - $quantity); // Don't allow negative stock

        // Update product stock
        $product->update(['stock_quantity' => $newStock]);

        // Record movement
        return self::create([
            'product_id' => $productId,
            'type' => 'out',
            'quantity' => -$quantity, // Store as negative for out movements
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reason' => $reason,
            'notes' => $notes,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'created_by' => $createdBy,
        ]);
    }

    public static function recordStockAdjustment($productId, $newQuantity, $reason, $notes = null, $createdBy = null)
    {
        $product = Product::findOrFail($productId);
        $previousStock = $product->stock_quantity;
        $adjustment = $newQuantity - $previousStock;

        // Update product stock
        $product->update(['stock_quantity' => $newQuantity]);

        // Record movement
        return self::create([
            'product_id' => $productId,
            'type' => 'adjustment',
            'quantity' => $adjustment,
            'previous_stock' => $previousStock,
            'new_stock' => $newQuantity,
            'reason' => $reason,
            'notes' => $notes,
            'created_by' => $createdBy,
        ]);
    }
}
