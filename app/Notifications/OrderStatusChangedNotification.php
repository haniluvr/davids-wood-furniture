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
        return (new MailMessage)
            ->subject('Order Update - #' . $this->order->order_number)
            ->view('emails.orders.status-changed', [
                'order' => $this->order,
                'user' => $notifiable,
                'newStatus' => $this->newStatus,
                'oldStatus' => $this->oldStatus
            ]);
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
