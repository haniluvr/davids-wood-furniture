<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ReturnRepair;
use Illuminate\Database\Seeder;

class ReturnsRepairsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all orders with return_status != 'none'
        $ordersWithReturns = Order::where('return_status', '!=', 'none')
            ->with(['user', 'orderItems.product'])
            ->get();

        if ($ordersWithReturns->isEmpty()) {
            $this->command->warn('No orders with return_status found. Please run OrderSeeder first.');

            return;
        }

        $returnTypes = ['return', 'repair', 'exchange'];
        $returnReasons = [
            'Product arrived damaged',
            'Wrong item received',
            'Item not as described',
            'Defective product',
            'Size/color mismatch',
            'Changed my mind',
            'Quality issues',
            'Missing parts',
            'Item does not fit',
            'Customer dissatisfaction',
        ];

        $returnDescriptions = [
            'The item arrived with visible damage to the packaging and the product itself.',
            'I received a different item than what I ordered.',
            'The product does not match the description on the website.',
            'The item has manufacturing defects.',
            'The size/color is different from what was ordered.',
            'I decided I no longer need this item.',
            'The quality is not as expected.',
            'Some parts or accessories are missing.',
            'The item does not fit in my space as expected.',
            'Overall, I am not satisfied with the product.',
        ];

        $statusFlow = [
            'requested' => ['requested'],
            'approved' => ['approved'],
            'received' => ['received'],
            'processing' => ['processing'],
            'repaired' => ['repaired'], // Only for repair type
            'refunded' => ['refunded'], // Only for return type
            'completed' => ['completed'],
            'rejected' => ['rejected'],
        ];

        $createdReturns = 0;

        foreach ($ordersWithReturns as $order) {
            // Determine return type
            $type = $returnTypes[array_rand($returnTypes)];
            $reason = $returnReasons[array_rand($returnReasons)];
            $description = $returnDescriptions[array_rand($returnDescriptions)];

            // Get products from order items
            $products = [];
            foreach ($order->orderItems as $orderItem) {
                // Not all items may be returned
                if (rand(1, 100) <= 70) { // 70% chance each item is in the return
                    $products[] = [
                        'product_id' => $orderItem->product_id,
                        'product_name' => $orderItem->product_name,
                        'sku' => $orderItem->product_sku,
                        'quantity' => rand(1, $orderItem->quantity),
                        'reason' => rand(1, 3) === 1 ? 'damaged' : (rand(1, 2) === 1 ? 'defective' : 'wrong_item'),
                    ];
                }
            }

            if (empty($products)) {
                // If no products selected, at least return the first item
                $firstItem = $order->orderItems->first();
                if ($firstItem) {
                    $products[] = [
                        'product_id' => $firstItem->product_id,
                        'product_name' => $firstItem->product_name,
                        'sku' => $firstItem->product_sku,
                        'quantity' => 1,
                        'reason' => 'damaged',
                    ];
                }
            }

            // Use order's return_status as base, or determine based on order age
            $orderAge = time() - strtotime($order->created_at);
            $daysOld = $orderAge / 86400;

            // Start with order's return_status if set, otherwise calculate
            $status = $order->return_status !== 'none' ? $order->return_status : 'requested';

            // If order's return_status is generic, refine based on age and type
            if (in_array($status, ['requested', 'approved'])) {
                if ($daysOld > 60) {
                    // Orders older than 60 days - likely completed
                    $statusOptions = $type === 'repair' ? ['completed', 'repaired'] : ($type === 'return' ? ['completed', 'refunded'] : ['completed']);
                    $status = $statusOptions[array_rand($statusOptions)];
                } elseif ($daysOld > 30) {
                    // Orders 30-60 days old - in progress
                    $statusOptions = $type === 'repair' ? ['received', 'processing', 'repaired'] : ($type === 'return' ? ['received', 'processing', 'refunded'] : ['received', 'processing', 'completed']);
                    $status = $statusOptions[array_rand($statusOptions)];
                } elseif ($daysOld > 14) {
                    // Orders 14-30 days old - approved or received
                    $statusOptions = ['approved', 'received'];
                    $status = $statusOptions[array_rand($statusOptions)];
                }
            }

            // Ensure status matches type
            if ($type === 'repair' && $status === 'refunded') {
                $status = 'repaired';
            }
            if ($type === 'return' && in_array($status, ['repaired'])) {
                $status = 'refunded';
            }

            // Calculate return request date (after order was delivered)
            $deliveredAt = $order->delivered_at ? strtotime($order->delivered_at) : strtotime($order->created_at);
            $returnRequestAt = $deliveredAt + rand(86400, 2592000); // 1-30 days after delivery
            $now = time();

            $approvedAt = null;
            $receivedAt = null;
            $completedAt = null;

            if (in_array($status, ['approved', 'received', 'processing', 'repaired', 'refunded', 'completed'])) {
                $approvedAt = date('Y-m-d H:i:s', rand($returnRequestAt + 86400, min($returnRequestAt + 604800, $now))); // 1-7 days after return request
            }

            if (in_array($status, ['received', 'processing', 'repaired', 'refunded', 'completed'])) {
                $receivedAt = $approvedAt ? date('Y-m-d H:i:s', rand(strtotime($approvedAt) + 86400, min(strtotime($approvedAt) + 2592000, $now))) : null; // 1-30 days after approval
            }

            if (in_array($status, ['completed', 'repaired', 'refunded'])) {
                $completedAt = $receivedAt ? date('Y-m-d H:i:s', rand(strtotime($receivedAt) + 86400, min(strtotime($receivedAt) + 2592000, $now))) : null; // 1-30 days after received
            }

            // Calculate refund amount (if applicable)
            $refundAmount = null;
            $refundMethod = null;
            if (in_array($status, ['refunded', 'completed']) && $type === 'return') {
                // Calculate partial refund based on returned items
                $refundAmount = 0;
                foreach ($products as $product) {
                    $orderItem = $order->orderItems->where('product_id', $product['product_id'])->first();
                    if ($orderItem) {
                        $refundAmount += ($orderItem->unit_price * $product['quantity']);
                    }
                }
                // May deduct restocking fee (10-20%)
                if (rand(1, 2) === 1) {
                    $refundAmount = $refundAmount * (rand(80, 90) / 100);
                }
                $refundAmount = round($refundAmount, 2);
                $refundMethod = ['original_payment', 'bank_transfer', 'gcash', 'store_credit'][array_rand(['original_payment', 'bank_transfer', 'gcash', 'store_credit'])];
            }

            // Generate RMA number
            $rmaNumber = $this->generateRmaNumber(date('Y', $orderCreatedAt));

            // Admin notes based on status
            $adminNotes = null;
            if (in_array($status, ['approved', 'received', 'processing', 'repaired', 'refunded', 'completed'])) {
                $notes = [
                    'Customer provided photos. Issue verified.',
                    'Product received and inspected.',
                    'Refund processed successfully.',
                    'Repair completed and item returned to customer.',
                    'Replacement item shipped.',
                    'Customer satisfied with resolution.',
                ];
                $adminNotes = $notes[array_rand($notes)];
            }

            // Customer notes
            $customerNotes = [
                'Please process my return as soon as possible.',
                'I need this resolved urgently.',
                'Thank you for handling this return.',
                'I would like a refund instead of exchange.',
                'Can you send me a replacement?',
            ];

            // Create return/repair record
            $returnRepair = ReturnRepair::create([
                'rma_number' => $rmaNumber,
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'type' => $type,
                'status' => $status,
                'reason' => $reason,
                'description' => $description,
                'products' => $products,
                'refund_amount' => $refundAmount,
                'refund_method' => $refundMethod,
                'admin_notes' => $adminNotes,
                'customer_notes' => $customerNotes[array_rand($customerNotes)],
                'approved_at' => $approvedAt,
                'received_at' => $receivedAt,
                'completed_at' => $completedAt,
                'created_at' => date('Y-m-d H:i:s', min($returnRequestAt, $now)),
                'updated_at' => $completedAt ?? $receivedAt ?? $approvedAt ?? date('Y-m-d H:i:s', min($returnRequestAt + 86400, $now)),
            ]);

            // Update order with RMA number if not already set
            if (! $order->rma_number) {
                $order->update(['rma_number' => $rmaNumber]);
            }

            $createdReturns++;
        }

        $this->command->info("Created {$createdReturns} return/repair records successfully!");
        $this->command->info('Returns/Repairs by status:');
        $statuses = ['requested', 'approved', 'received', 'processing', 'repaired', 'refunded', 'completed', 'rejected'];
        foreach ($statuses as $status) {
            $count = ReturnRepair::where('status', $status)->count();
            if ($count > 0) {
                $this->command->line("  - {$status}: {$count}");
            }
        }
    }

    /**
     * Generate unique RMA number.
     */
    private function generateRmaNumber(string $year): string
    {
        $maxAttempts = 100;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $rmaNumber = "RMA-{$year}-{$code}";

            if (! ReturnRepair::where('rma_number', $rmaNumber)->exists()) {
                return $rmaNumber;
            }
        }

        // Fallback
        $code = strtoupper(substr(base_convert(time(), 10, 36), -4));

        return "RMA-{$year}-{$code}";
    }
}
