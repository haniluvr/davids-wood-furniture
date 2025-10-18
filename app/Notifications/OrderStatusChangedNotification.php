<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $newStatus;
    protected $oldStatus;

    public function __construct(Order $order, $newStatus, $oldStatus = null)
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Order Update - #' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your order status has been updated.');

        if ($this->oldStatus) {
            $message->line('Previous Status: ' . ucfirst($this->oldStatus));
        }

        $message->line('New Status: ' . ucfirst($this->newStatus));

        // Add status-specific information
        switch ($this->newStatus) {
            case 'processing':
                $message->line('We are now preparing your order for shipment.');
                break;
            case 'shipped':
                $message->line('Your order has been shipped!');
                if ($this->order->tracking_number) {
                    $message->line('Tracking Number: ' . $this->order->tracking_number);
                }
                break;
            case 'delivered':
                $message->line('Your order has been delivered!');
                $message->line('We hope you enjoy your purchase. If you have any questions or concerns, please don\'t hesitate to contact us.');
                break;
            case 'cancelled':
                $message->line('Your order has been cancelled.');
                $message->line('If you have any questions about this cancellation, please contact our support team.');
                break;
        }

        $message->line('Order Details:')
            ->line('Order Number: #' . $this->order->order_number)
            ->line('Total Amount: $' . number_format($this->order->total_amount, 2))
            ->action('View Order', url('/account/orders/' . $this->order->id))
            ->line('Thank you for choosing us!');

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'total_amount' => $this->order->total_amount,
        ];
    }
}
