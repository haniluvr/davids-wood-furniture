<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Low Stock Alert - ' . $this->product->name)
            ->greeting('Hello!')
            ->line('This is a low stock alert for one of your products.')
            ->line('Product Details:')
            ->line('Product Name: ' . $this->product->name)
            ->line('SKU: ' . $this->product->sku)
            ->line('Current Stock: ' . $this->product->stock_quantity)
            ->line('Low Stock Threshold: ' . ($this->product->low_stock_threshold ?? 'Not set'))
            ->line('Category: ' . ($this->product->category ? $this->product->category->name : 'N/A'))
            ->line('Price: $' . number_format($this->product->price, 2))
            ->action('View Product', url('/admin/products/' . $this->product->id))
            ->line('Please consider restocking this product to avoid stockouts.')
            ->line('You can update the stock quantity or adjust the low stock threshold in the admin panel.');
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_sku' => $this->product->sku,
            'current_stock' => $this->product->stock_quantity,
            'low_stock_threshold' => $this->product->low_stock_threshold,
        ];
    }
}
