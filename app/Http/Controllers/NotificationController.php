<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user.
     */
    public function getUserNotifications(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $perPage = $request->get('per_page', 15);

        $notifications = Notification::where(function ($query) use ($user) {
            $query->where('recipient_type', 'user')
                ->where('recipient_id', $user->id);
        })
            ->orWhere('recipient_type', 'all')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'has_more' => $notifications->hasMorePages(),
            ],
        ]);
    }

    /**
     * Get unread notification count for the authenticated user.
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['count' => 0]);
        }

        $count = Notification::where(function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->where('recipient_type', 'user')
                    ->where('recipient_id', $user->id);
            })
                ->orWhere('recipient_type', 'all');
        })
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('recipient_type', 'user')
                    ->where('recipient_id', $user->id);
            })
            ->orWhere('recipient_type', 'all')
            ->first();

        if (! $notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $updated = Notification::where(function ($query) use ($user) {
            $query->where('recipient_type', 'user')
                ->where('recipient_id', $user->id);
        })
            ->orWhere('recipient_type', 'all')
            ->where('status', 'sent')
            ->update([
                'status' => 'read',
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
            'updated_count' => $updated,
        ]);
    }

    /**
     * Delete a specific notification.
     */
    public function deleteNotification(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('recipient_type', 'user')
                    ->where('recipient_id', $user->id);
            })
            ->orWhere('recipient_type', 'all')
            ->first();

        if (! $notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }

    /**
     * Clear all notifications for the authenticated user.
     */
    public function clearAll(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $deleted = Notification::where(function ($query) use ($user) {
            $query->where('recipient_type', 'user')
                ->where('recipient_id', $user->id);
        })
            ->orWhere('recipient_type', 'all')
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared',
            'deleted_count' => $deleted,
        ]);
    }
}
