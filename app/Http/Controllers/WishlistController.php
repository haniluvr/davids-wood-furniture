<?php

namespace App\Http\Controllers;

use App\Models\GuestSession;
use App\Models\Product;
use App\Models\WishlistItem;
use App\Services\SessionWishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected $sessionWishlistService;

    public function __construct(SessionWishlistService $sessionWishlistService)
    {
        $this->sessionWishlistService = $sessionWishlistService;
    }

    /**
     * Get or create guest session.
     */
    private function getOrCreateGuestSession(string $sessionId): GuestSession
    {
        $guestSession = GuestSession::findOrCreateSession($sessionId);

        // Ensure session is saved after creating guest session
        session()->save();

        return $guestSession;
    }

    /**
     * Ensure session persistence for guest operations.
     */
    private function ensureSessionPersistence(): void
    {
        // Force session save
        session()->save();

        // Additional persistence check
        if (session()->driver() instanceof \Illuminate\Session\Store) {
            session()->driver()->save();
        }
    }

    /**
     * Preserve guest wishlist data before session loss.
     */
    public function preserveGuestWishlistData($sessionId): array
    {
        $guestItems = WishlistItem::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->with('product')
            ->get();

        \Log::info('Preserving guest wishlist data', [
            'session_id' => $sessionId,
            'items_count' => $guestItems->count(),
            'product_ids' => $guestItems->pluck('product_id')->toArray(),
        ]);

        return $guestItems->toArray();
    }

    /**
     * Restore guest wishlist data from preserved data.
     */
    public function restoreGuestWishlistData($userId, array $preservedData): int
    {
        $restoredCount = 0;

        foreach ($preservedData as $item) {
            $productId = $item['product_id'];

            // Check if user already has this product
            $existingItem = WishlistItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if (! $existingItem) {
                WishlistItem::create([
                    'user_id' => $userId,
                    'session_id' => null,
                    'product_id' => $productId,
                ]);
                $restoredCount++;
            }
        }

        \Log::info('Restored guest wishlist data', [
            'user_id' => $userId,
            'restored_count' => $restoredCount,
            'total_preserved' => count($preservedData),
        ]);

        return $restoredCount;
    }

    /**
     * Get wishlist items for current user or guest.
     */
    public function index(Request $request)
    {
        try {
            $sessionId = session()->getId();
            $userId = \Auth::id();

            \Log::info('Fetching wishlist items from Redis', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'authenticated' => \Auth::check(),
            ]);

            // Use session service to get wishlist items
            $items = $this->sessionWishlistService->getWishlistItems($userId, $sessionId);

            \Log::info('Wishlist items found', [
                'count' => $items->count(),
                'items' => $items->pluck('id')->toArray(),
            ]);

            return response()->json($items);
        } catch (\Exception $e) {
            \Log::error('Error fetching wishlist', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add product to wishlist.
     */
    public function add(Request $request)
    {
        try {
            \Log::info('Wishlist add method called', [
                'request_data' => $request->all(),
                'session_id' => session()->getId(),
                'user_id' => \Auth::id(),
            ]);

            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
            ]);

            $sessionId = session()->getId();
            $userId = \Auth::id();
            $productId = $request->product_id;

            \Log::info('Processing wishlist add with Redis', [
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            // Check if already in wishlist using session service
            $exists = $this->sessionWishlistService->isInWishlist($productId, $userId, $sessionId);

            \Log::info('Wishlist item exists check', [
                'exists' => $exists,
            ]);

            if (! $exists) {
                // Create guest session if needed for guests
                if (! $userId) {
                    \Log::info('Creating guest session for wishlist', [
                        'session_id' => $sessionId,
                    ]);
                    $guestSession = $this->getOrCreateGuestSession($sessionId);
                }

                // Add to session wishlist
                \Log::info('Calling sessionWishlistService->addToWishlist', [
                    'product_id' => $productId,
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                ]);

                $result = $this->sessionWishlistService->addToWishlist($productId, $userId, $sessionId);

                \Log::info('SessionWishlistService->addToWishlist result', [
                    'result' => $result,
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                ]);
            }

            // Ensure session persistence
            $this->ensureSessionPersistence();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Wishlist add error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error adding to wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove product from wishlist.
     */
    public function remove(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
            ]);

            $sessionId = session()->getId();
            $userId = \Auth::id();
            $productId = $request->product_id;

            // Remove from database wishlist
            $this->sessionWishlistService->removeFromWishlist($productId, $userId, $sessionId);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing from wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if product is in wishlist.
     */
    public function check($productId)
    {
        try {
            $sessionId = session()->getId();
            $userId = \Auth::id();

            $inWishlist = $this->sessionWishlistService->isInWishlist($productId, $userId, $sessionId);

            return response()->json(['in_wishlist' => $inWishlist]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle wishlist item (add if not exists, remove if exists).
     */
    public function toggle(Request $request)
    {
        try {
            \Log::info('Wishlist toggle method called', [
                'request_data' => $request->all(),
                'session_id' => session()->getId(),
                'user_id' => \Auth::id(),
            ]);

            $request->validate([
                'product_id' => 'required|integer|exists:products,id',
            ]);

            $sessionId = session()->getId();
            $userId = \Auth::id();
            $productId = $request->product_id;

            \Log::info('Processing wishlist toggle', [
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            // Check if item exists using database service
            $itemExists = $this->sessionWishlistService->isInWishlist($productId, $userId, $sessionId);

            \Log::info('Wishlist item exists check', [
                'item_exists' => $itemExists,
            ]);

            $wasAdded = false;
            if ($itemExists) {
                \Log::info('Removing wishlist item from database', [
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                ]);
                $this->sessionWishlistService->removeFromWishlist($productId, $userId, $sessionId);
            } else {
                \Log::info('Adding wishlist item to database', [
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                ]);

                // Create guest session if needed for guests
                if (! $userId) {
                    \Log::info('Creating guest session for toggle', [
                        'session_id' => $sessionId,
                    ]);
                    $guestSession = $this->getOrCreateGuestSession($sessionId);
                    \Log::info('Guest session created/found', [
                        'session_id' => $guestSession->session_id,
                        'created_at' => $guestSession->created_at,
                        'expires_at' => $guestSession->expires_at,
                    ]);
                }

                try {
                    // Add to database wishlist
                    $this->sessionWishlistService->addToWishlist($productId, $userId, $sessionId);

                    \Log::info('Wishlist item added to database in toggle', [
                        'user_id' => $userId,
                        'session_id' => $sessionId,
                        'product_id' => $productId,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to add wishlist item to database', [
                        'error' => $e->getMessage(),
                        'user_id' => $userId,
                        'session_id' => $sessionId,
                        'product_id' => $productId,
                    ]);

                    throw $e;
                }

                $wasAdded = true;
            }

            // Ensure session persistence
            $this->ensureSessionPersistence();

            return response()->json(['success' => true, 'was_added' => $wasAdded]);
        } catch (\Exception $e) {
            \Log::error('Wishlist toggle error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error toggling wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear all wishlist items for current user or guest.
     */
    public function clear(Request $request)
    {
        try {
            $sessionId = session()->getId();
            $userId = \Auth::id();

            // Clear from database
            $this->sessionWishlistService->clearWishlist($userId, $sessionId);

            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing wishlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Manual migration endpoint for frontend to trigger wishlist migration.
     */
    public function migrate(Request $request)
    {
        try {
            $user = auth()->user();
            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                ], 401);
            }

            $sessionId = session()->getId();

            \Log::info('Manual wishlist migration triggered', [
                'user_id' => $user->id,
                'session_id' => $sessionId,
            ]);

            $this->migrateWishlistToUser($user->id, $sessionId);

            return response()->json([
                'success' => true,
                'message' => 'Wishlist migration completed',
            ]);
        } catch (\Exception $e) {
            \Log::error('Manual wishlist migration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Migration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Migrate guest wishlist items to user account when they log in.
     */
    public function migrateWishlistToUser($userId, $sessionId)
    {
        try {
            \Log::info('Starting database wishlist migration', [
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);

            // Use database service to migrate guest wishlist to user
            $migratedCount = $this->sessionWishlistService->migrateGuestToUser($userId, $sessionId);

            \Log::info('Database wishlist migration completed', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'migrated_count' => $migratedCount,
            ]);
        } catch (\Exception $e) {
            \Log::error('Database wishlist migration failed', [
                'user_id' => $userId,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e; // Re-throw to ensure the error is visible
        }
    }
}
