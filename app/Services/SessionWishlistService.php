<?php

namespace App\Services;

use App\Models\GuestSession;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SessionWishlistService
{
    private $sessionKey = 'wishlist_items';

    /**
     * Add product to wishlist (guest or user)
     */
    public function addToWishlist($productId, $userId = null, $sessionId = null)
    {
        try {
            \Log::info('SessionWishlistService->addToWishlist called', [
                'product_id' => $productId,
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);

            // Check if product exists
            $product = Product::find($productId);
            if (! $product) {
                \Log::error('Product not found in SessionWishlistService', ['product_id' => $productId]);
                throw new \Exception('Product not found');
            }

            // Check if already in wishlist
            $exists = $this->isInWishlist($productId, $userId, $sessionId);
            if ($exists) {
                Log::info('Product already in wishlist', [
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                ]);

                return true;
            }

            // Create guest session if needed for guests
            if (! $userId && $sessionId) {
                $this->ensureGuestSession($sessionId);
            }

            // Store in database
            \Log::info('Creating WishlistItem in database', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
            ]);

            $wishlistItem = WishlistItem::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId, // For authenticated users, session_id should be null
                'product_id' => $productId,
            ]);

            \Log::info('WishlistItem created successfully', [
                'wishlist_item_id' => $wishlistItem->id,
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
            ]);

            // Also store in session for immediate access
            if (! $userId) {
                $this->addToSessionWishlist($productId);
            }

            Log::info('Product added to wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to add product to wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
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
                $query->where('user_id', $userId)->whereNull('session_id');
            } else {
                $query->where('session_id', $sessionId)->whereNull('user_id');
            }

            $deleted = $query->delete();

            // Also remove from session
            if (! $userId) {
                $this->removeFromSessionWishlist($productId);
            }

            Log::info('Product removed from wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'deleted' => $deleted,
            ]);

            return $deleted > 0;
        } catch (\Exception $e) {
            Log::error('Failed to remove product from wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
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
                $query->where('user_id', $userId)->whereNull('session_id');
            } else {
                $query->where('session_id', $sessionId)->whereNull('user_id');
            }

            $items = $query->get();

            Log::info('Retrieved wishlist items', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'count' => $items->count(),
            ]);

            return $items;
        } catch (\Exception $e) {
            Log::error('Failed to get wishlist items', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
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
                $query->where('user_id', $userId)->whereNull('session_id');
            } else {
                $query->where('session_id', $sessionId)->whereNull('user_id');
            }

            return $query->exists();
        } catch (\Exception $e) {
            Log::error('Failed to check wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
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
            \Log::info('SessionWishlistService: migrateGuestToUser called', [
                'user_id' => $userId,
                'guest_session_id_received' => $sessionId,
            ]);

            // Get all guest wishlist items
            $guestItems = WishlistItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->get();

            \Log::info('SessionWishlistService: Found guest wishlist items for migration', [
                'guest_session_id' => $sessionId,
                'count' => $guestItems->count(),
                'item_ids' => $guestItems->pluck('id')->toArray(),
            ]);

            if ($guestItems->isEmpty()) {
                \Log::info('SessionWishlistService: No guest wishlist items to migrate');

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
                            'product_id' => $guestItem->product_id,
                        ]);
                        $skippedCount++;
                    } else {
                        // Create user wishlist item
                        WishlistItem::create([
                            'user_id' => $userId,
                            'session_id' => null,
                            'product_id' => $guestItem->product_id,
                        ]);

                        Log::info('Migrated wishlist item to user', [
                            'user_id' => $userId,
                            'product_id' => $guestItem->product_id,
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
                        'deleted_count' => $deletedCount,
                    ]);

                    // Clean up guest session
                    \Log::info('SessionWishlistService: Guest session record deletion attempt', [
                        'guest_session_id' => $sessionId,
                    ]);
                    GuestSession::where('session_id', $sessionId)->delete();
                    \Log::info('SessionWishlistService: Guest session record deleted after migration', [
                        'guest_session_id' => $sessionId,
                    ]);
                }
            });

            Log::info('Session wishlist migration completed', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'migrated_count' => $migratedCount,
                'skipped_count' => $skippedCount,
                'total_guest_items' => $guestItems->count(),
            ]);

            return $migratedCount;
        } catch (\Exception $e) {
            Log::error('Session wishlist migration failed', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
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
                $query->where('user_id', $userId)->whereNull('session_id');
            } else {
                $query->where('session_id', $sessionId)->whereNull('user_id');
            }

            $deletedCount = $query->delete();

            // Also clear session
            if (! $userId) {
                Session::forget($this->sessionKey);
            }

            Log::info('Cleared wishlist', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'deleted_count' => $deletedCount,
            ]);

            return $deletedCount;
        } catch (\Exception $e) {
            Log::error('Failed to clear wishlist', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'session_id' => $sessionId,
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

            if (! $guestSession) {
                $guestSession = GuestSession::create([
                    'session_id' => $sessionId,
                    'expires_at' => now()->addDays(30),
                ]);

                Log::info('Created guest session', [
                    'session_id' => $sessionId,
                ]);
            }

            return $guestSession;
        } catch (\Exception $e) {
            Log::error('Failed to ensure guest session', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
            ]);
            throw $e;
        }
    }

    /**
     * Add to session wishlist
     */
    private function addToSessionWishlist($productId)
    {
        $wishlist = Session::get($this->sessionKey, []);
        if (! in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            Session::put($this->sessionKey, $wishlist);
        }
    }

    /**
     * Remove from session wishlist
     */
    private function removeFromSessionWishlist($productId)
    {
        $wishlist = Session::get($this->sessionKey, []);
        $wishlist = array_filter($wishlist, function ($id) use ($productId) {
            return $id != $productId;
        });
        Session::put($this->sessionKey, array_values($wishlist));
    }

    /**
     * Get session wishlist
     */
    public function getSessionWishlist()
    {
        return Session::get($this->sessionKey, []);
    }

    /**
     * Sync session with database
     */
    public function syncSessionWithDatabase($sessionId)
    {
        try {
            $sessionItems = Session::get($this->sessionKey, []);
            $dbItems = WishlistItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->pluck('product_id')
                ->toArray();

            // Add session items to database
            foreach ($sessionItems as $productId) {
                if (! in_array($productId, $dbItems)) {
                    WishlistItem::create([
                        'session_id' => $sessionId,
                        'product_id' => $productId,
                    ]);
                }
            }

            Log::info('Synced session with database', [
                'session_id' => $sessionId,
                'session_items' => count($sessionItems),
                'db_items' => count($dbItems),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to sync session with database', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
            ]);
        }
    }
}
