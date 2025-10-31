<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderFulfillment;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalOrders = 500;
        $totalOrderItems = 600;

        // Get all users and products
        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        if (empty($userIds) || empty($productIds)) {
            $this->command->error('No users or products found. Please run FilipinoUserSeeder and ProductSeeder first.');

            return;
        }

        // Status distribution (500 orders total)
        $statusDistribution = [
            'pending' => (int) round($totalOrders * 0.05),      // 25 orders (5%)
            'processing' => (int) round($totalOrders * 0.10),    // 50 orders (10%)
            'shipped' => (int) round($totalOrders * 0.20),       // 100 orders (20%)
            'delivered' => (int) round($totalOrders * 0.50),     // 250 orders (50%)
            'cancelled' => (int) round($totalOrders * 0.05),     // 25 orders (5%)
        ];

        // Calculate orders with returns (10% of total = 50 orders)
        // These will be orders with return_status != 'none'
        // They should be from the 'delivered' status group
        $ordersWithReturns = (int) round($totalOrders * 0.10); // 50 orders

        // Ensure we have exactly 500 orders
        $actualTotal = array_sum($statusDistribution);
        if ($actualTotal != $totalOrders) {
            // Adjust delivered to match total
            $statusDistribution['delivered'] += ($totalOrders - $actualTotal);
        }

        // Track which delivered orders will have returns
        $deliveredOrdersForReturns = $ordersWithReturns;

        // Get payment gateways from database
        $paymentGateways = \App\Models\PaymentGateway::all();
        $codGateway = $paymentGateways->where('gateway_key', 'cod')->first();
        $xenditGateway = $paymentGateways->where('gateway_key', 'xendit')->first();

        // Payment methods - align with our gateways (COD and Xendit)
        // For COD: use 'cod'
        // For Xendit: use actual payment method names from Xendit
        $codPaymentMethod = 'cod';
        $xenditPaymentMethods = ['credit_card', 'debit_card', 'gcash', 'bank_transfer', 'retail_outlet', 'qr_code', 'direct_debit'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        // Shipping methods
        $shippingMethods = ['standard', 'express', 'free_shipping'];

        // Carriers
        $carriers = ['LBC', 'J&T Express', '2GO', 'Grab Express', 'Lalamove'];

        // Date range: past 3 years
        $threeYearsAgo = strtotime('-3 years');
        $now = time();

        $ordersCreated = 0;
        $orderItemsCreated = 0;
        $ordersWithReturnStatus = [];

        // Track order items distribution
        $remainingOrderItems = $totalOrderItems;
        $deliveredOrdersProcessed = 0;

        foreach ($statusDistribution as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                // Track if this is a delivered order that should have a return
                $shouldHaveReturn = false;
                if ($status === 'delivered' && $deliveredOrdersProcessed < $deliveredOrdersForReturns) {
                    $shouldHaveReturn = true;
                    $deliveredOrdersProcessed++;
                }
                // Get random user
                $userId = $userIds[array_rand($userIds)];
                $user = User::find($userId);

                if (! $user) {
                    continue;
                }

                // Generate order date (spread across 3 years)
                $orderCreatedAt = date('Y-m-d H:i:s', rand($threeYearsAgo, $now));
                $orderYear = date('Y', strtotime($orderCreatedAt));

                // Generate unique order number
                $orderNumber = $this->generateOrderNumber($orderYear);

                // Determine number of items for this order (1-4 items, distribute remaining items)
                $itemsInOrder = 1;
                if ($remainingOrderItems > $count - $i) {
                    // Distribute remaining items across remaining orders
                    $maxItems = min(4, $remainingOrderItems - ($count - $i) + 1);
                    $itemsInOrder = rand(1, max(1, $maxItems));
                }
                $remainingOrderItems -= $itemsInOrder;
                if ($remainingOrderItems < 0) {
                    $remainingOrderItems = 0;
                }

                // Calculate order amounts
                $subtotal = 0;
                $orderItems = [];

                // Create order items
                for ($j = 0; $j < $itemsInOrder; $j++) {
                    $productId = $productIds[array_rand($productIds)];
                    $product = Product::find($productId);

                    if (! $product) {
                        continue;
                    }

                    $quantity = rand(1, 3);
                    $unitPrice = $product->sale_price ?? $product->price;
                    $totalPrice = $unitPrice * $quantity;

                    $subtotal += $totalPrice;

                    $orderItems[] = [
                        'product_id' => $productId,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'product_data' => [
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'slug' => $product->slug,
                            'image' => $product->images[0] ?? null,
                            'material' => $product->material,
                            'dimensions' => $product->dimensions,
                            'weight' => $product->weight,
                        ],
                    ];
                }

                if (empty($orderItems)) {
                    continue;
                }

                // Calculate totals
                $taxRate = 0.12; // 12% VAT
                $taxAmount = $subtotal * $taxRate;
                $shippingCost = rand(150, 500);
                $discountAmount = rand(1, 100) <= 15 ? rand(500, 2000) : 0; // 15% chance of discount
                $totalAmount = $subtotal + $taxAmount + $shippingCost - $discountAmount;

                // Determine payment status based on order status
                $paymentStatus = match ($status) {
                    'cancelled' => rand(1, 2) === 1 ? 'refunded' : 'failed',
                    'delivered', 'shipped' => 'paid',
                    'processing' => rand(1, 10) <= 8 ? 'paid' : 'pending',
                    default => 'pending',
                };

                // Determine return_status
                $returnStatus = 'none';
                if ($shouldHaveReturn) {
                    // Mark this delivered order for return
                    $returnStatuses = ['requested', 'approved', 'received', 'completed'];
                    $returnStatus = $returnStatuses[array_rand($returnStatuses)];
                }

                // Calculate dates based on status progression
                $dates = $this->calculateOrderDates($orderCreatedAt, $status);

                // Create billing and shipping address from user
                $billingAddress = [
                    'name' => $user->first_name.' '.$user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                    'address_line_1' => $user->address_line_1 ?? $user->street ?? '',
                    'address_line_2' => $user->address_line_2 ?? '',
                    'city' => $user->city ?? '',
                    'province' => $user->province ?? '',
                    'region' => $user->region ?? '',
                    'zip_code' => $user->zip_code ?? $user->postal_code ?? '',
                    'country' => $user->country ?? 'Philippines',
                ];

                $shippingAddress = $billingAddress; // Same as billing for simplicity

                // Create order
                $order = Order::create([
                    'user_id' => $userId,
                    'order_number' => $orderNumber,
                    'status' => $status,
                    'fulfillment_status' => $this->getFulfillmentStatus($status),
                    'return_status' => $returnStatus,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'shipping_amount' => $shippingCost,
                    'shipping_cost' => $shippingCost,
                    'shipping_method' => $shippingMethods[array_rand($shippingMethods)],
                    'discount_amount' => $discountAmount,
                    'total_amount' => $totalAmount,
                    'currency' => 'PHP',
                    'billing_address' => $billingAddress,
                    'shipping_address' => $shippingAddress,
                    'payment_method' => $this->selectPaymentMethod($codGateway, $xenditGateway, $codPaymentMethod, $xenditPaymentMethods),
                    'payment_status' => $paymentStatus,
                    'carrier' => in_array($status, ['shipped', 'delivered']) ? $carriers[array_rand($carriers)] : null,
                    'tracking_number' => in_array($status, ['shipped', 'delivered']) ? $this->generateTrackingNumber($user, substr($orderNumber, -4)) : null,
                    'shipped_at' => $dates['shipped_at'],
                    'delivered_at' => $dates['delivered_at'],
                    'created_at' => $dates['created_at'],
                    'updated_at' => $dates['updated_at'],
                ]);

                // Create order items
                foreach ($orderItems as $itemData) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $itemData['product_id'],
                        'product_name' => $itemData['product_name'],
                        'product_sku' => $itemData['product_sku'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total_price' => $itemData['total_price'],
                        'product_data' => $itemData['product_data'],
                        'created_at' => $dates['created_at'],
                        'updated_at' => $dates['updated_at'],
                    ]);
                    $orderItemsCreated++;
                }

                // Create fulfillment record for shipped/delivered/packed orders
                if (in_array($status, ['processing', 'shipped', 'delivered'])) {
                    $this->createFulfillment($order, $status, $dates);
                }

                $ordersCreated++;

                // Track which order has return status for ReturnsRepairsSeeder
                if ($returnStatus !== 'none') {
                    $ordersWithReturnStatus[] = $order->id;
                }
            }
        }

        $this->command->info("Created {$ordersCreated} orders successfully!");
        $this->command->info("Created {$orderItemsCreated} order items successfully!");
        $this->command->info('Orders by status:');
        foreach ($statusDistribution as $status => $count) {
            $actualCount = Order::where('status', $status)->count();
            $this->command->line("  - {$status}: {$actualCount}");
        }
        $this->command->info('Orders with return status: '.count($ordersWithReturnStatus));
        if (count($ordersWithReturnStatus) > 0) {
            $this->command->info('Order IDs with returns: '.implode(', ', array_slice($ordersWithReturnStatus, 0, 10)).(count($ordersWithReturnStatus) > 10 ? '...' : ''));
        }

        // Store order IDs with returns for ReturnsRepairsSeeder
        $this->command->getOutput()->writeln('<info>Note: Run ReturnsRepairsSeeder after this to create return/repair records for orders with return_status.</info>');
    }

    /**
     * Generate unique order number.
     */
    private function generateOrderNumber(string $year): string
    {
        $maxAttempts = 100;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $orderNumber = "ORD-{$year}-{$code}";

            if (! Order::where('order_number', $orderNumber)->exists()) {
                return $orderNumber;
            }
        }

        // Fallback
        $code = strtoupper(substr(base_convert(time(), 10, 36), -4));

        return "ORD-{$year}-{$code}";
    }

    /**
     * Generate tracking number.
     */
    private function generateTrackingNumber(User $user, string $orderCode): string
    {
        $regionCode = $this->getRegionCode($user->region ?? '');
        $provinceCode = $this->getProvinceCode($user->province ?? '', $user->region ?? '');
        $cityCode = $this->getCityCode($user->city ?? '');
        $zipCode = str_pad(substr(preg_replace('/\D/', '', $user->zip_code ?? $user->postal_code ?? '0000'), 0, 4), 4, '0', STR_PAD_LEFT);

        return $regionCode.$provinceCode.$cityCode.'-'.$zipCode.'-'.$orderCode;
    }

    /**
     * Calculate order dates based on status.
     */
    private function calculateOrderDates(string $orderCreatedAt, string $status): array
    {
        $createdTimestamp = strtotime($orderCreatedAt);
        $now = time();

        return match ($status) {
            'pending' => [
                'created_at' => date('Y-m-d H:i:s', $createdTimestamp),
                'updated_at' => date('Y-m-d H:i:s', rand($createdTimestamp, $now)),
                'shipped_at' => null,
                'delivered_at' => null,
            ],
            'processing' => [
                'created_at' => date('Y-m-d H:i:s', $createdTimestamp),
                'updated_at' => date('Y-m-d H:i:s', rand($createdTimestamp, $now)),
                'shipped_at' => null,
                'delivered_at' => null,
            ],
            'shipped' => [
                'created_at' => date('Y-m-d H:i:s', $createdTimestamp),
                'shipped_at' => date('Y-m-d H:i:s', rand($createdTimestamp + 86400, min($createdTimestamp + 604800, $now))), // 1-7 days after order
                'updated_at' => date('Y-m-d H:i:s', rand(strtotime(date('Y-m-d H:i:s', $createdTimestamp + 86400)), $now)),
                'delivered_at' => null,
            ],
            'delivered' => [
                'created_at' => date('Y-m-d H:i:s', $createdTimestamp),
                'shipped_at' => date('Y-m-d H:i:s', rand($createdTimestamp + 86400, min($createdTimestamp + 1209600, $now))), // 1-14 days after order
                'delivered_at' => date('Y-m-d H:i:s', rand(strtotime(date('Y-m-d H:i:s', $createdTimestamp + 86400 * 3)), min($createdTimestamp + 1814400, $now))), // 3-21 days after order
                'updated_at' => date('Y-m-d H:i:s', rand(strtotime(date('Y-m-d H:i:s', $createdTimestamp + 86400 * 3)), $now)),
            ],
            'cancelled' => [
                'created_at' => date('Y-m-d H:i:s', $createdTimestamp),
                'updated_at' => date('Y-m-d H:i:s', rand($createdTimestamp + 3600, min($createdTimestamp + 86400 * 3, $now))), // 1 hour - 3 days
                'shipped_at' => null,
                'delivered_at' => null,
            ],
            default => [
                'created_at' => date('Y-m-d H:i:s', $createdTimestamp),
                'updated_at' => date('Y-m-d H:i:s', $now),
                'shipped_at' => null,
                'delivered_at' => null,
            ],
        };
    }

    /**
     * Get fulfillment status based on order status.
     */
    private function getFulfillmentStatus(string $status): string
    {
        return match ($status) {
            'pending' => 'pending',
            'processing' => 'pending',
            'shipped' => 'shipped',
            'delivered' => 'delivered',
            'cancelled' => 'pending',
            default => 'pending',
        };
    }

    /**
     * Create fulfillment record.
     */
    private function createFulfillment(Order $order, string $status, array $dates): void
    {
        $fulfillment = OrderFulfillment::create([
            'order_id' => $order->id,
            'items_packed' => in_array($status, ['processing', 'shipped', 'delivered']),
            'label_printed' => in_array($status, ['shipped', 'delivered']),
            'shipped' => in_array($status, ['shipped', 'delivered']),
            'carrier' => $order->carrier,
            'tracking_number' => $order->tracking_number,
            'packed_at' => in_array($status, ['processing', 'shipped', 'delivered']) && $dates['shipped_at'] ? date('Y-m-d H:i:s', strtotime($dates['shipped_at']) - 86400) : null, // 1 day before shipping
            'shipped_at' => $dates['shipped_at'],
            'packing_notes' => in_array($status, ['processing', 'shipped', 'delivered']) ? 'Items packed and ready for shipment.' : null,
            'shipping_notes' => in_array($status, ['shipped', 'delivered']) ? 'Package shipped via '.($order->carrier ?? 'standard carrier').'.' : null,
            'created_at' => $dates['created_at'],
            'updated_at' => $dates['updated_at'],
        ]);
    }

    /**
     * Get region code.
     */
    private function getRegionCode(?string $region): string
    {
        $regions = [
            'National Capital Region (NCR)' => 'NC',
            'Cordillera Administrative Region (CAR)' => 'CR',
            'Region I (Ilocos Region)' => 'IL',
            'Region II (Cagayan Valley)' => 'CV',
            'Region III (Central Luzon)' => 'CL',
            'Region IV-A (CALABARZON)' => 'CZ',
            'Region IV-B (MIMAROPA)' => 'MM',
            'Region V (Bicol Region)' => 'BC',
            'Region VI (Western Visayas)' => 'WV',
            'Region VII (Central Visayas)' => 'CV',
            'Region VIII (Eastern Visayas)' => 'EV',
            'Region IX (Zamboanga Peninsula)' => 'ZP',
            'Region X (Northern Mindanao)' => 'NM',
            'Region XI (Davao Region)' => 'DV',
            'Region XII (SOCCSKSARGEN)' => 'SK',
            'Region XIII (Caraga)' => 'CG',
            'Autonomous Region in Muslim Mindanao (ARMM)' => 'AR',
        ];

        return $regions[$region] ?? 'XX';
    }

    /**
     * Get province code.
     */
    private function getProvinceCode(?string $province, ?string $region): string
    {
        if ($region === 'National Capital Region (NCR)' || empty($province)) {
            return '0';
        }

        $hash = crc32(strtolower($province ?? ''));

        return (string) (($hash % 9) + 1);
    }

    /**
     * Get city code.
     */
    private function getCityCode(?string $city): string
    {
        if (empty($city)) {
            return '00';
        }

        $city = strtoupper(preg_replace('/[^A-Z]/', '', $city));
        $first = ord(substr($city, 0, 1)) - 64;
        $second = strlen($city) > 1 ? ord(substr($city, 1, 1)) - 64 : 0;

        $code = ($first % 10).($second % 10);

        return str_pad($code, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Select payment method based on available gateways (COD or Xendit).
     * Aligns with PaymentGatewaySeeder: 'cod' and Xendit payment methods.
     */
    private function selectPaymentMethod($codGateway, $xenditGateway, string $codMethod, array $xenditMethods): string
    {
        // 40% COD, 60% Xendit payment methods
        if (rand(1, 100) <= 40) {
            return $codMethod; // 'cod'
        }

        // Otherwise use Xendit payment method (credit_card, debit_card, gcash, bank_transfer, etc.)
        return $xenditMethods[array_rand($xenditMethods)];
    }
}
