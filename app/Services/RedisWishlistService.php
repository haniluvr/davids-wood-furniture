<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Models\WishlistItem;
use App\Models\Product;
use App\Models\User;

class RedisWishlistService
{
    private $redis;
    private $prefix = 'wishlist:';
    
    public function __construct()
    {
        $this->redis = Redis::connection();
    }
    
    /**
     * Add product to wishlist (guest or user)
     */
    public function addToWishlist($userId = null, $sessionId = null, $productId)
    {
        try {
            $key = $this->getWishlistKey($userId, $sessionId);
            
            // Check if product exists
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception("Product not found");
            }
            
            // Add to Redis set
            $this->redis->sadd($key, $productId);
            
            // Set expiration for guest sessions (30 days)
            if (!$userId && $sessionId) {
                $this->redis->expire($key, 30 * 24 * 60 * 60); // 30 days
            }
            
            // Also store in database for persistence
            $this->storeInDatabase($userId, $sessionId, $productId);
            
            Log::info('Product added to Redis wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'key' => $key
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to add product to Redis wishlist', [
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
    public function removeFromWishlist($userId = null, $sessionId = null, $productId)
    {
        try {
            $key = $this->getWishlistKey($userId, $sessionId);
            
            // Remove from Redis set
            $this->redis->srem($key, $productId);
            
            // Also remove from database
            $this->removeFromDatabase($userId, $sessionId, $productId);
            
            Log::info('Product removed from Redis wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'key' => $key
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to remove product from Redis wishlist', [
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
            $key = $this->getWishlistKey($userId, $sessionId);
            $productIds = $this->redis->smembers($key);
            
            if (empty($productIds)) {
                // Fallback to database
                return $this->getFromDatabase($userId, $sessionId);
            }
            
            // Get products with the IDs
            $products = Product::whereIn('id', $productIds)->get();
            
            Log::info('Retrieved wishlist from Redis', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'count' => count($productIds),
                'key' => $key
            ]);
            
            return $products;
        } catch (\Exception $e) {
            Log::error('Failed to get wishlist from Redis, falling back to database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            
            // Fallback to database
            return $this->getFromDatabase($userId, $sessionId);
        }
    }
    
    /**
     * Check if product is in wishlist
     */
    public function isInWishlist($userId = null, $sessionId = null, $productId)
    {
        try {
            $key = $this->getWishlistKey($userId, $sessionId);
            return $this->redis->sismember($key, $productId);
        } catch (\Exception $e) {
            Log::error('Failed to check wishlist in Redis, falling back to database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
            
            // Fallback to database
            return $this->isInDatabase($userId, $sessionId, $productId);
        }
    }
    
    /**
     * Migrate guest wishlist to user
     */
    public function migrateGuestToUser($userId, $sessionId)
    {
        try {
            $guestKey = $this->getWishlistKey(null, $sessionId);
            $userKey = $this->getWishlistKey($userId, null);
            
            // Get guest wishlist items
            $guestItems = $this->redis->smembers($guestKey);
            
            if (empty($guestItems)) {
                Log::info('No guest wishlist items to migrate');
                return 0;
            }
            
            $migratedCount = 0;
            
            foreach ($guestItems as $productId) {
                // Add to user's wishlist
                $this->redis->sadd($userKey, $productId);
                
                // Store in database
                $this->storeInDatabase($userId, null, $productId);
                
                $migratedCount++;
            }
            
            // Remove guest wishlist
            $this->redis->del($guestKey);
            
            Log::info('Successfully migrated guest wishlist to user', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'migrated_count' => $migratedCount,
                'guest_items' => $guestItems
            ]);
            
            return $migratedCount;
        } catch (\Exception $e) {
            Log::error('Failed to migrate guest wishlist to user', [
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
            $key = $this->getWishlistKey($userId, $sessionId);
            $this->redis->del($key);
            
            // Also clear from database
            $this->clearFromDatabase($userId, $sessionId);
            
            Log::info('Cleared wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'key' => $key
            ]);
            
            return true;
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
     * Get Redis key for wishlist
     */
    private function getWishlistKey($userId = null, $sessionId = null)
    {
        if ($userId) {
            return $this->prefix . 'user:' . $userId;
        } elseif ($sessionId) {
            return $this->prefix . 'session:' . $sessionId;
        }
        
        throw new \Exception('Either userId or sessionId must be provided');
    }
    
    /**
     * Store in database for persistence
     */
    private function storeInDatabase($userId, $sessionId, $productId)
    {
        try {
            WishlistItem::firstOrCreate([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store wishlist item in database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
        }
    }
    
    /**
     * Remove from database
     */
    private function removeFromDatabase($userId, $sessionId, $productId)
    {
        try {
            WishlistItem::where('user_id', $userId)
                ->where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->delete();
        } catch (\Exception $e) {
            Log::error('Failed to remove wishlist item from database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
        }
    }
    
    /**
     * Get from database (fallback)
     */
    private function getFromDatabase($userId, $sessionId)
    {
        try {
            $query = WishlistItem::with('product');
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            return $query->get()->pluck('product');
        } catch (\Exception $e) {
            Log::error('Failed to get wishlist from database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            return collect();
        }
    }
    
    /**
     * Check if in database (fallback)
     */
    private function isInDatabase($userId, $sessionId, $productId)
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
            Log::error('Failed to check wishlist in database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId
            ]);
            return false;
        }
    }
    
    /**
     * Clear from database
     */
    private function clearFromDatabase($userId, $sessionId)
    {
        try {
            $query = WishlistItem::query();
            
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
            
            $query->delete();
        } catch (\Exception $e) {
            Log::error('Failed to clear wishlist from database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
        }
    }
}
