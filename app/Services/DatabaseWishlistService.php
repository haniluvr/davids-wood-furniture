<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\WishlistItem;
use App\Models\Product;
use App\Models\GuestSession;

class DatabaseWishlistService
{
    /**
     * Add product to wishlist (guest or user)
     */
    public function addToWishlist($productId, $userId = null, $sessionId = null)
    {
        try {
            // Check if product exists
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception("Product not found");
            }
            
            // Check if already in wishlist
            $exists = $this->isInWishlist($userId, $sessionId, $productId);
            if ($exists) {
                Log::info('Product already in wishlist', [
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $productId
                ]);
                return true;
            }
            
            // Create guest session if needed for guests
            if (!$userId && $sessionId) {
                $this->ensureGuestSession($sessionId);
            }
            
            // Create wishlist item
            WishlistItem::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
            
            Log::info('Product added to wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to add product to wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
            throw $e;
        }
    }
    
    /**
     * Remove product from wishlist
     */
    public function removeFromWishlist($productId, $userId = null, $sessionId = null)
    {
        try {
            $query = WishlistItem::where('product_id', $productId);
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            $deleted = $query->delete();
            
            Log::info('Product removed from wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'deleted' => $deleted
            ]);
            
            return $deleted > 0;
        } catch (\Exception $e) {
            Log::error('Failed to remove product from wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
            throw $e;
        }
    }
    
    /**
     * Get wishlist items
     */
    public function getWishlistItems($userId = null, $sessionId = null)
    {
        try {
            $query = WishlistItem::with('product');
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            $items = $query->get();
            
            Log::info('Retrieved wishlist items', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'count' => $items->count()
            ]);
            
            return $items;
        } catch (\Exception $e) {
            Log::error('Failed to get wishlist items', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            return collect();
        }
    }
    
    /**
     * Check if product is in wishlist
     */
    public function isInWishlist($productId, $userId = null, $sessionId = null)
    {
        try {
            $query = WishlistItem::where('product_id', $productId);
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            return $query->exists();
        } catch (\Exception $e) {
            Log::error('Failed to check wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
            return false;
        }
    }
    
    /**
     * Migrate guest wishlist to user
     */
    public function migrateGuestToUser($userId, $sessionId)
    {
        try {
            Log::info('Starting database wishlist migration', [
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            
            // Get all guest wishlist items
            $guestItems = WishlistItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();
            
            if ($guestItems->isEmpty()) {
                Log::info('No guest wishlist items to migrate');
                return 0;
            }
            
            $migratedCount = 0;
            $skippedCount = 0;
            
            DB::transaction(function () use ($userId, $sessionId, $guestItems, &$migratedCount, &$skippedCount) {
                foreach ($guestItems as $guestItem) {
                    // Check if user already has this product
                    $existingItem = WishlistItem::where('user_id', $userId)
                        ->where('product_id', $guestItem->product_id)
                        ->first();
                    
                    if ($existingItem) {
                        Log::info('User already has product, skipping', [
                            'user_id' => $userId,
                            'product_id' => $guestItem->product_id
                        ]);
                        $skippedCount++;
                    } else {
                        // Create user wishlist item
                        WishlistItem::create([
                            'user_id' => $userId,
                            'session_id' => null,
                            'product_id' => $guestItem->product_id
                        ]);
                        
                        Log::info('Migrated wishlist item to user', [
                            'user_id' => $userId,
                            'product_id' => $guestItem->product_id
                        ]);
                        $migratedCount++;
                    }
                }
                
                // Delete guest items only if migration was successful
                if ($migratedCount > 0 || $skippedCount > 0) {
                    $deletedCount = WishlistItem::where('session_id', $sessionId)
                        ->whereNull('user_id')
                        ->delete();
                    
                    Log::info('Deleted guest wishlist items', [
                        'deleted_count' => $deletedCount
                    ]);
                    
                    // Clean up guest session
                    GuestSession::where('session_id', $sessionId)->delete();
                }
            });
            
            Log::info('Database wishlist migration completed', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'migrated_count' => $migratedCount,
                'skipped_count' => $skippedCount,
                'total_guest_items' => $guestItems->count()
            ]);
            
            return $migratedCount;
        } catch (\Exception $e) {
            Log::error('Database wishlist migration failed', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            throw $e;
        }
    }
    
    /**
     * Clear wishlist
     */
    public function clearWishlist($userId = null, $sessionId = null)
    {
        try {
            $query = WishlistItem::query();
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            $deletedCount = $query->delete();
            
            Log::info('Cleared wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'deleted_count' => $deletedCount
            ]);
            
            return $deletedCount;
        } catch (\Exception $e) {
            Log::error('Failed to clear wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            throw $e;
        }
    }
    
    /**
     * Ensure guest session exists
     */
    private function ensureGuestSession($sessionId)
    {
        try {
            $guestSession = GuestSession::find($sessionId);
            
            if (!$guestSession) {
                $guestSession = GuestSession::create([
                    'session_id' => $sessionId,
                    'expires_at' => now()->addDays(30)
                ]);
                
                Log::info('Created guest session', [
                    'session_id' => $sessionId
                ]);
            }
            
            return $guestSession;
        } catch (\Exception $e) {
            Log::error('Failed to ensure guest session', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId
            ]);
            throw $e;
        }
    }
    
    /**
     * Preserve guest wishlist data before session loss
     */
    public function preserveGuestWishlistData($sessionId)
    {
        try {
            $guestItems = WishlistItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->with('product')
                ->get();
            
            Log::info('Preserved guest wishlist data', [
                'session_id' => $sessionId,
                'items_count' => $guestItems->count(),
                'product_ids' => $guestItems->pluck('product_id')->toArray()
            ]);
            
            return $guestItems->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to preserve guest wishlist data', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId
            ]);
            return [];
        }
    }
    
    /**
     * Restore guest wishlist data from preserved data
     */
    public function restoreGuestWishlistData($userId, $preservedData)
    {
        try {
            $restoredCount = 0;
            
            foreach ($preservedData as $item) {
                $productId = $item['product_id'];
                
                // Check if user already has this product
                $existingItem = WishlistItem::where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->first();
                
                if (!$existingItem) {
                    WishlistItem::create([
                        'user_id' => $userId,
                        'session_id' => null,
                        'product_id' => $productId
                    ]);
                    $restoredCount++;
                }
            }
            
            Log::info('Restored guest wishlist data', [
                'user_id' => $userId,
                'restored_count' => $restoredCount,
                'total_preserved' => count($preservedData)
            ]);
            
            return $restoredCount;
        } catch (\Exception $e) {
            Log::error('Failed to restore guest wishlist data', [
                'error' => $e->getMessage(),
                'user_id' => $userId
            ]);
            return 0;
        }
    }
}
