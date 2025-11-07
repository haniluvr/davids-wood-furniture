<?php

namespace App\Listeners;

use App\Events\LowStockAlert;
use App\Models\Admin;
use App\Models\Notification;

class CreateLowStockNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(LowStockAlert $event): void
    {
        $product = $event->product;
        $currentStock = $event->currentStock;
        $threshold = $event->threshold;

        // Get all active admins with inventory permissions
        $admins = Admin::active()->get()->filter(function ($admin) {
            return $admin->hasPermission('inventory.view') || $admin->hasPermission('products.view');
        });

        foreach ($admins as $admin) {
            // Check if admin wants to receive low stock notifications
            if (! $admin->wantsNotification('low_stock')) {
                continue;
            }

            // Create notification for this admin
            Notification::createForAdmin(
                $admin,
                'Low Stock Alert',
                "Product '{$product->name}' (SKU: {$product->sku}) is running low on stock. Current quantity: {$currentStock} (threshold: {$threshold})",
                'inventory',
                [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'stock_quantity' => $currentStock,
                    'threshold' => $threshold,
                    'link' => admin_route('inventory.index'),
                ]
            );
        }
    }
}
