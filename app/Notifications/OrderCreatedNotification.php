<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Confirmation - #'.$this->order->order_number)
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Thank you for your order! We have received your order and are processing it.')
            ->line('Order Details:')
            ->line('Order Number: #'.$this->order->order_number)
            ->line('Order Date: '.$this->order->created_at->format('M d, Y'))
            ->line('Total Amount: $'.number_format($this->order->total_amount, 2))
            ->line('Status: '.ucfirst($this->order->status))
            ->action('View Order', url('/account/orders/'.$this->order->id))
            ->line('We will send you another email when your order ships.')
            ->line('If you have any questions, please contact our support team.');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_amount' => $this->order->total_amount,
            'status' => $this->order->status,
        ];
    }
}
