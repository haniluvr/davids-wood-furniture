<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use App\Models\Order;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderStatusChangedNotification;
use App\Notifications\LowStockNotification;
use App\Notifications\NewReviewNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = NotificationModel::with(['user', 'notifiable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => NotificationModel::count(),
            'unread' => NotificationModel::whereNull('read_at')->count(),
            'today' => NotificationModel::whereDate('created_at', today())->count(),
            'this_week' => NotificationModel::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    public function templates()
    {
        $templates = [
            'order_created' => [
                'name' => 'Order Created',
                'subject' => 'Order Confirmation - #{{order_number}}',
                'description' => 'Sent when a new order is placed'
            ],
            'order_status_changed' => [
                'name' => 'Order Status Changed',
                'subject' => 'Order Update - #{{order_number}}',
                'description' => 'Sent when order status changes'
            ],
            'low_stock' => [
                'name' => 'Low Stock Alert',
                'subject' => 'Low Stock Alert - {{product_name}}',
                'description' => 'Sent when product stock is low'
            ],
            'new_review' => [
                'name' => 'New Review',
                'subject' => 'New Product Review - {{product_name}}',
                'description' => 'Sent when a new review is submitted'
            ],
            'welcome' => [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to {{site_name}}!',
                'description' => 'Sent to new users after registration'
            ],
            'password_reset' => [
                'name' => 'Password Reset',
                'subject' => 'Reset Your Password',
                'description' => 'Sent when user requests password reset'
            ]
        ];

        return view('admin.notifications.templates', compact('templates'));
    }

    public function updateTemplate(Request $request, $template)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'is_active' => 'boolean'
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
            'body' => 'required|string'
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
            return redirect()->back()->with('error', 'Failed to send notifications: ' . $e->getMessage());
        }
    }

    public function test(Request $request)
    {
        $request->validate([
            'type' => 'required|in:order_created,order_status_changed,low_stock,new_review,welcome,password_reset',
            'email' => 'required|email'
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
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
            return redirect()->back()->with('error', 'Failed to send test notification: ' . $e->getMessage());
        }
    }
}
