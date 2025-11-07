<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification as NotificationModel;
use App\Models\Order;
use App\Models\User;
use App\Notifications\LowStockNotification;
use App\Notifications\NewReviewNotification;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        $query = NotificationModel::where('recipient_type', 'admin')
            ->where('recipient_id', $admin->id);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'unread') {
                $query->whereIn('status', ['pending', 'sent']);
            } elseif ($request->status === 'read') {
                $query->where('status', 'read');
            }
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        $stats = [
            'total' => NotificationModel::where('recipient_type', 'admin')
                ->where('recipient_id', $admin->id)
                ->count(),
            'unread' => NotificationModel::where('recipient_type', 'admin')
                ->where('recipient_id', $admin->id)
                ->whereIn('status', ['pending', 'sent'])
                ->count(),
            'today' => NotificationModel::where('recipient_type', 'admin')
                ->where('recipient_id', $admin->id)
                ->whereDate('created_at', today())
                ->count(),
            'this_week' => NotificationModel::where('recipient_type', 'admin')
                ->where('recipient_id', $admin->id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Get notifications for the current admin (API endpoint).
     */
    public function getNotifications(Request $request)
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not authenticated',
            ], 401);
        }

        $limit = $request->get('limit', 10);
        $unreadOnly = $request->get('unread_only', false);

        $query = NotificationModel::where('recipient_type', 'admin')
            ->where('recipient_id', $admin->id)
            ->orderBy('created_at', 'desc');

        if ($unreadOnly) {
            $query->whereIn('status', ['pending', 'sent']);
        }

        $notifications = $query->limit($limit)->get();

        $unreadCount = NotificationModel::where('recipient_type', 'admin')
            ->where('recipient_id', $admin->id)
            ->whereIn('status', ['pending', 'sent'])
            ->count();

        // Log for debugging
        \Log::info('Notifications API called', [
            'admin_id' => $admin->id,
            'notifications_count' => $notifications->count(),
            'unread_count' => $unreadCount,
            'message_notifications' => $notifications->where('type', 'message')->count(),
            'order_notifications' => $notifications->where('type', 'order')->count(),
            'refund_notifications' => $notifications->where('type', 'refund')->count(),
            'notification_types' => $notifications->pluck('type')->toArray(),
            'notification_statuses' => $notifications->pluck('status')->toArray(),
        ]);

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                $data = is_array($notification->data)
                    ? $notification->data
                    : (is_string($notification->data)
                        ? json_decode($notification->data, true)
                        : []);

                // Regenerate link with current environment context for all notification types
                $link = null;

                // Check for refund/return repair notifications first (before order_id check)
                if (isset($data['return_repair_id'])) {
                    $link = admin_route('orders.returns-repairs.show', $data['return_repair_id']);
                }
                // Check for message notifications
                elseif (isset($data['message_id'])) {
                    $link = admin_route('messages.show', $data['message_id']);
                }
                // Check for order notifications
                elseif (isset($data['order_id'])) {
                    $link = admin_route('orders.show', $data['order_id']);
                }
                // Check for product/review notifications
                elseif (isset($data['product_id'])) {
                    $link = admin_route('products.show', $data['product_id']);
                }
                // Check for customer notifications
                elseif (isset($data['user_id'])) {
                    $link = admin_route('users.show', $data['user_id']);
                }
                // Check for review notifications
                elseif (isset($data['review_id'])) {
                    $link = admin_route('reviews.index'); // Reviews list page
                }
                // Check for inventory notifications
                elseif (isset($data['product_id']) && $notification->type === 'inventory') {
                    $link = admin_route('inventory.index'); // Inventory page
                }
                // If link exists in data but we couldn't regenerate, try to fix the domain using helper
                elseif (! empty($data['link'])) {
                    $storedLink = $data['link'];
                    // Use AdminRouteHelper to rebuild URL with correct domain
                    $link = \App\Helpers\AdminRouteHelper::rebuildUrl($storedLink);
                }

                // Update data with regenerated link
                if ($link) {
                    $data['link'] = $link;
                }

                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $this->mapNotificationType($notification->type),
                    'read' => $notification->status === 'read',
                    'timestamp' => $notification->created_at->toISOString(),
                    'data' => $data ?: [],
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Request $request, $id)
    {
        $admin = auth('admin')->user();

        $notification = NotificationModel::where('recipient_type', 'admin')
            ->where('recipient_id', $admin->id)
            ->where('id', $id)
            ->first();

        if (! $notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $admin = auth('admin')->user();

        NotificationModel::where('recipient_type', 'admin')
            ->where('recipient_id', $admin->id)
            ->whereIn('status', ['pending', 'sent'])
            ->update([
                'status' => 'read',
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Map database notification type to frontend type.
     */
    private function mapNotificationType($type): string
    {
        $mapping = [
            'order' => 'order',
            'order_status' => 'order_status',
            'inventory' => 'inventory',
            'message' => 'message',
            'customer' => 'customer',
            'review' => 'review',
            'refund' => 'refund',
            'system' => 'info',
        ];

        return $mapping[$type] ?? 'info';
    }

    public function templates()
    {
        $templates = [
            'order_created' => [
                'name' => 'Order Created',
                'subject' => 'Order Confirmation - #{{order_number}}',
                'description' => 'Sent when a new order is placed',
            ],
            'order_status_changed' => [
                'name' => 'Order Status Changed',
                'subject' => 'Order Update - #{{order_number}}',
                'description' => 'Sent when order status changes',
            ],
            'low_stock' => [
                'name' => 'Low Stock Alert',
                'subject' => 'Low Stock Alert - {{product_name}}',
                'description' => 'Sent when product stock is low',
            ],
            'new_review' => [
                'name' => 'New Review',
                'subject' => 'New Product Review - {{product_name}}',
                'description' => 'Sent when a new review is submitted',
            ],
            'welcome' => [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to {{site_name}}!',
                'description' => 'Sent to new users after registration',
            ],
            'password_reset' => [
                'name' => 'Password Reset',
                'subject' => 'Reset Your Password',
                'description' => 'Sent when user requests password reset',
            ],
        ];

        return view('admin.notifications.templates', compact('templates'));
    }

    public function updateTemplate(Request $request, $template)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // Update template in settings or database
        $setting = \App\Models\Setting::firstOrCreate(
            ['key' => "notification_template_{$template}"],
            ['value' => json_encode([])]
        );

        $templateData = json_decode($setting->value, true);
        $templateData['subject'] = $request->subject;
        $templateData['body'] = $request->body;
        $templateData['is_active'] = $request->boolean('is_active', true);
        $templateData['updated_at'] = now();

        $setting->update(['value' => json_encode($templateData)]);

        return redirect()->back()->with('success', 'Template updated successfully.');
    }

    public function send(Request $request)
    {
        $request->validate([
            'type' => 'required|in:order_created,order_status_changed,low_stock,new_review,welcome,password_reset',
            'recipients' => 'required|array',
            'recipients.*' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $recipients = $request->recipients;
        $subject = $request->subject;
        $body = $request->body;

        try {
            foreach ($recipients as $email) {
                $user = User::where('email', $email)->first();

                if ($user) {
                    // Send notification to user
                    $user->notify(new \App\Notifications\CustomNotification($subject, $body));
                } else {
                    // Send email directly
                    Mail::raw($body, function ($message) use ($email, $subject) {
                        $message->to($email)->subject($subject);
                    });
                }
            }

            return redirect()->back()->with('success', 'Notifications sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send notifications: '.$e->getMessage());
        }
    }

    public function test(Request $request)
    {
        $request->validate([
            'type' => 'required|in:order_created,order_status_changed,low_stock,new_review,welcome,password_reset',
            'email' => 'required|email',
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (! $user) {
                return redirect()->back()->with('error', 'User not found with that email address.');
            }

            // Send test notification based on type
            switch ($request->type) {
                case 'order_created':
                    $order = Order::latest()->first();
                    if ($order) {
                        $user->notify(new OrderCreatedNotification($order));
                    }

                    break;
                case 'order_status_changed':
                    $order = Order::latest()->first();
                    if ($order) {
                        $user->notify(new OrderStatusChangedNotification($order, 'Processing'));
                    }

                    break;
                case 'low_stock':
                    $product = \App\Models\Product::where('stock_quantity', '<', 10)->first();
                    if ($product) {
                        $user->notify(new LowStockNotification($product));
                    }

                    break;
                case 'new_review':
                    $review = \App\Models\ProductReview::latest()->first();
                    if ($review) {
                        $user->notify(new NewReviewNotification($review));
                    }

                    break;
            }

            return redirect()->back()->with('success', 'Test notification sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send test notification: '.$e->getMessage());
        }
    }
}
