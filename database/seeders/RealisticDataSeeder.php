<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RealisticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Permissions
        DB::table('admin_permissions')->insert([
            ['role' => 'super_admin', 'permission' => '1', 'granted' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['role' => 'admin', 'permission' => '1', 'granted' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['role' => 'manager', 'permission' => '1', 'granted' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['role' => 'staff', 'permission' => '1', 'granted' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Sample Carts (must be created before cart_items)
        DB::table('carts')->insert([
            [
                'user_id' => null, // Guest cart
                'session_id' => 'session_123',
                'expires_at' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => null, // Guest cart
                'session_id' => 'session_456',
                'expires_at' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Cart Items (from your actual data)
        DB::table('cart_items')->insert([
            [
                'cart_id' => 1,
                'product_id' => 46,
                'quantity' => 2,
                'price' => 22067.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'cart_id' => 1,
                'product_id' => 57,
                'quantity' => 1,
                'price' => 12269.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'cart_id' => 2,
                'product_id' => 79,
                'quantity' => 2,
                'price' => 7198.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Wishlists (must be created before wishlist_items)
        DB::table('wishlists')->insert([
            [
                'user_id' => null, // Guest wishlist
                'session_id' => 'wishlist_session_123',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => null, // Guest wishlist
                'session_id' => 'wishlist_session_456',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Wishlist Items
        DB::table('wishlist_items')->insert([
            [
                'wishlist_id' => 1,
                'product_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'wishlist_id' => 1,
                'product_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'wishlist_id' => 2,
                'product_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Orders (must be created before order_items)
        DB::table('orders')->insert([
            [
                'user_id' => null, // Guest order
                'order_number' => 'DW-2025-001',
                'status' => 'delivered',
                'subtotal' => 60207.00,
                'tax_amount' => 6020.70,
                'shipping_amount' => 500.00,
                'total_amount' => 66727.70,
                'currency' => 'PHP',
                'shipping_address' => json_encode([
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'street' => '123 Main St',
                    'barangay' => 'Barangay 1',
                    'city' => 'Manila',
                    'province' => 'Metro Manila',
                    'region' => 'NCR',
                    'zip_code' => '1000'
                ]),
                'billing_address' => json_encode([
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'street' => '123 Main St',
                    'barangay' => 'Barangay 1',
                    'city' => 'Manila',
                    'province' => 'Metro Manila',
                    'region' => 'NCR',
                    'zip_code' => '1000'
                ]),
                'notes' => 'Please deliver during business hours',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1)
            ],
            [
                'user_id' => null, // Guest order
                'order_number' => 'DW-2025-002',
                'status' => 'processing',
                'subtotal' => 45000.00,
                'tax_amount' => 4500.00,
                'shipping_amount' => 500.00,
                'total_amount' => 50000.00,
                'currency' => 'PHP',
                'shipping_address' => json_encode([
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                    'street' => '456 Oak Ave',
                    'barangay' => 'Barangay 2',
                    'city' => 'Quezon City',
                    'province' => 'Metro Manila',
                    'region' => 'NCR',
                    'zip_code' => '1100'
                ]),
                'billing_address' => json_encode([
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                    'street' => '456 Oak Ave',
                    'barangay' => 'Barangay 2',
                    'city' => 'Quezon City',
                    'province' => 'Metro Manila',
                    'region' => 'NCR',
                    'zip_code' => '1100'
                ]),
                'notes' => 'Gift wrapping requested',
                'created_at' => now()->subDays(2),
                'updated_at' => now()
            ],
        ]);

        // Sample Order Items
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 21072.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 18063.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'order_id' => 2,
                'product_id' => 3,
                'quantity' => 3,
                'price' => 15000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Order Activities
        DB::table('order_activities')->insert([
            [
                'order_id' => 1,
                'action' => 'order_created',
                'description' => 'Order was created by customer',
                'metadata' => json_encode(['source' => 'website']),
                'performed_by' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5)
            ],
            [
                'order_id' => 1,
                'action' => 'payment_received',
                'description' => 'Payment was received and confirmed',
                'metadata' => json_encode(['payment_method' => 'credit_card', 'amount' => 66727.70]),
                'performed_by' => 1,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4)
            ],
            [
                'order_id' => 1,
                'action' => 'order_shipped',
                'description' => 'Order has been shipped',
                'metadata' => json_encode(['tracking_number' => 'DW123456789', 'carrier' => 'LBC']),
                'performed_by' => 1,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ],
            [
                'order_id' => 1,
                'action' => 'order_delivered',
                'description' => 'Order has been delivered to customer',
                'metadata' => json_encode(['delivery_time' => '2025-10-15 14:30:00']),
                'performed_by' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1)
            ],
        ]);

        // Sample Order Fulfillment
        DB::table('order_fulfillment')->insert([
            [
                'order_id' => 1,
                'status' => 'delivered',
                'tracking_number' => 'DW123456789',
                'carrier' => 'LBC',
                'shipped_at' => now()->subDays(3),
                'delivered_at' => now()->subDays(1),
                'notes' => 'Delivered successfully to customer',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1)
            ],
            [
                'order_id' => 2,
                'status' => 'packed',
                'tracking_number' => null,
                'carrier' => null,
                'shipped_at' => null,
                'delivered_at' => null,
                'notes' => 'Order is being prepared for shipment',
                'created_at' => now()->subDays(2),
                'updated_at' => now()
            ],
        ]);

        // Sample Product Reviews (using null user_id for guest reviews)
        DB::table('product_reviews')->insert([
            [
                'product_id' => 1,
                'user_id' => null, // Guest review
                'rating' => 5,
                'review' => 'Excellent quality! The bed is exactly as described and very comfortable.',
                'admin_response' => 'Thank you for your wonderful review!',
                'is_approved' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(5)
            ],
            [
                'product_id' => 2,
                'user_id' => null, // Guest review
                'rating' => 4,
                'review' => 'Good quality furniture, delivery was fast. Would recommend.',
                'admin_response' => null,
                'is_approved' => true,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7)
            ],
            [
                'product_id' => 3,
                'user_id' => null, // Guest review
                'rating' => 3,
                'review' => 'Decent quality but took longer to assemble than expected.',
                'admin_response' => 'Thank you for your feedback. We are working on improving our assembly instructions.',
                'is_approved' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(3)
            ],
        ]);

        // Sample Product Popularity
        DB::table('product_popularity')->insert([
            [
                'product_id' => 1,
                'sku' => '10701',
                'name' => 'Premium Oak Queen/King Sized Bed',
                'view_count' => 150,
                'purchase_count' => 12,
                'total_revenue' => 252864.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 2,
                'sku' => '10702',
                'name' => 'Classic Pine Queen/King Sized Bed',
                'view_count' => 120,
                'purchase_count' => 8,
                'total_revenue' => 144504.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 3,
                'sku' => '10703',
                'name' => 'Modern Walnut Queen/King Sized Bed',
                'view_count' => 200,
                'purchase_count' => 15,
                'total_revenue' => 316080.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Returns/Repairs
        DB::table('returns_repairs')->insert([
            [
                'rma_number' => 'RMA-2025-001',
                'order_id' => 1,
                'user_id' => null, // Guest return
                'type' => 'return',
                'status' => 'completed',
                'reason' => 'Product arrived damaged',
                'description' => 'The bed frame has a crack on the headboard',
                'products' => json_encode([
                    ['product_id' => 1, 'quantity' => 1, 'reason' => 'damaged']
                ]),
                'refund_amount' => 21072.00,
                'refund_method' => 'credit_card',
                'admin_notes' => 'Customer provided photos of damage. Refund processed.',
                'customer_notes' => 'Very disappointed with the condition',
                'photos' => json_encode(['damage_photo_1.jpg', 'damage_photo_2.jpg']),
                'approved_at' => now()->subDays(8),
                'received_at' => now()->subDays(6),
                'completed_at' => now()->subDays(3),
                'processed_by' => 1,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(3)
            ],
        ]);

        // Sample Settings
        DB::table('settings')->insert([
            [
                'key' => 'store_name',
                'value' => "David's Wood Furniture",
                'type' => 'string',
                'description' => 'The name of the store',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'store_email',
                'value' => 'info@dwatelier.co',
                'type' => 'string',
                'description' => 'Main store email address',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'store_phone',
                'value' => '+63 912 345 6789',
                'type' => 'string',
                'description' => 'Main store phone number',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'free_shipping_threshold',
                'value' => '5000',
                'type' => 'integer',
                'description' => 'Minimum order amount for free shipping (PHP)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'tax_rate',
                'value' => '0.10',
                'type' => 'decimal',
                'description' => 'Tax rate as decimal (10% = 0.10)',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Payment Methods
        DB::table('payment_methods')->insert([
            [
                'name' => 'Credit Card',
                'type' => 'card',
                'is_active' => true,
                'config' => json_encode(['processor' => 'stripe', 'test_mode' => false]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bank Transfer',
                'type' => 'bank_transfer',
                'is_active' => true,
                'config' => json_encode(['bank_name' => 'BPI', 'account_number' => '1234567890']),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cash on Delivery',
                'type' => 'cod',
                'is_active' => true,
                'config' => json_encode(['fee' => 100, 'max_amount' => 10000]),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Shipping Methods
        DB::table('shipping_methods')->insert([
            [
                'name' => 'Standard Shipping',
                'code' => 'standard',
                'cost' => 200.00,
                'is_active' => true,
                'config' => json_encode(['delivery_days' => '3-5', 'max_weight' => 50]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Express Shipping',
                'code' => 'express',
                'cost' => 500.00,
                'is_active' => true,
                'config' => json_encode(['delivery_days' => '1-2', 'max_weight' => 30]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Free Shipping',
                'code' => 'free',
                'cost' => 0.00,
                'is_active' => true,
                'config' => json_encode(['min_order' => 5000, 'delivery_days' => '5-7']),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Payment Gateways
        DB::table('payment_gateways')->insert([
            [
                'name' => 'Stripe',
                'code' => 'stripe',
                'is_active' => true,
                'config' => json_encode([
                    'publishable_key' => 'pk_test_...',
                    'secret_key' => 'sk_test_...',
                    'webhook_secret' => 'whsec_...'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'is_active' => false,
                'config' => json_encode([
                    'client_id' => 'client_id_...',
                    'client_secret' => 'client_secret_...',
                    'mode' => 'sandbox'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Sample Inventory Movements
        DB::table('inventory_movements')->insert([
            [
                'product_id' => 1,
                'type' => 'in',
                'quantity' => 50,
                'reason' => 'Initial stock',
                'created_by' => 1,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30)
            ],
            [
                'product_id' => 1,
                'type' => 'out',
                'quantity' => 12,
                'reason' => 'Sales',
                'created_by' => 1,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15)
            ],
            [
                'product_id' => 2,
                'type' => 'in',
                'quantity' => 30,
                'reason' => 'Restock',
                'created_by' => 1,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20)
            ],
        ]);

        // Sample Contact Messages
        DB::table('contact_messages')->insert([
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'subject' => 'Question about delivery',
                'message' => 'Hi, I would like to know if you deliver to Quezon City?',
                'status' => 'read',
                'admin_reply' => 'Yes, we deliver to Quezon City. Standard delivery takes 3-5 business days.',
                'replied_by' => 1,
                'replied_at' => now()->subDays(2),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2)
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'subject' => 'Custom furniture request',
                'message' => 'Do you make custom furniture pieces?',
                'status' => 'new',
                'admin_reply' => null,
                'replied_by' => null,
                'replied_at' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1)
            ],
        ]);

        // Sample CMS Pages
        DB::table('cms_pages')->insert([
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About David\'s Wood Furniture</h1><p>We are a family-owned business specializing in high-quality wooden furniture...</p>',
                'meta_title' => 'About David\'s Wood Furniture - Quality Handcrafted Furniture',
                'meta_description' => 'Learn about our story, craftsmanship, and commitment to quality wooden furniture.',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30)
            ],
            [
                'title' => 'Shipping Information',
                'slug' => 'shipping-information',
                'content' => '<h1>Shipping Information</h1><p>We offer various shipping options to meet your needs...</p>',
                'meta_title' => 'Shipping Information - David\'s Wood Furniture',
                'meta_description' => 'Learn about our shipping options, delivery times, and costs.',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(25)
            ],
            [
                'title' => 'Return Policy',
                'slug' => 'return-policy',
                'content' => '<h1>Return Policy</h1><p>We want you to be completely satisfied with your purchase...</p>',
                'meta_title' => 'Return Policy - David\'s Wood Furniture',
                'meta_description' => 'Our return policy and procedures for returning or exchanging items.',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20)
            ],
        ]);

        // Sample Notifications
        DB::table('notifications')->insert([
            [
                'type' => 'order',
                'recipient_type' => 'admin',
                'recipient_id' => 1,
                'title' => 'New Order Received',
                'message' => 'A new order #DW-2025-002 has been placed by Jane Smith',
                'data' => json_encode(['order_id' => 2, 'order_number' => 'DW-2025-002']),
                'status' => 'read',
                'sent_at' => now()->subDays(2),
                'read_at' => now()->subDays(2),
                'channel' => 'email',
                'error_message' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2)
            ],
            [
                'type' => 'inventory',
                'recipient_type' => 'admin',
                'recipient_id' => 1,
                'title' => 'Low Stock Alert',
                'message' => 'Product "Premium Oak Queen/King Sized Bed" is running low on stock (5 remaining)',
                'data' => json_encode(['product_id' => 1, 'current_stock' => 5]),
                'status' => 'pending',
                'sent_at' => null,
                'read_at' => null,
                'channel' => 'email',
                'error_message' => null,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2)
            ],
        ]);

        $this->command->info('Realistic data seeded successfully!');
        $this->command->info('Created sample data for:');
        $this->command->info('- Admin permissions');
        $this->command->info('- Cart items and carts');
        $this->command->info('- Wishlist items and wishlists');
        $this->command->info('- Order items and orders');
        $this->command->info('- Order activities and fulfillment');
        $this->command->info('- Product reviews and popularity');
        $this->command->info('- Returns and repairs');
        $this->command->info('- Settings and payment methods');
        $this->command->info('- Shipping methods and gateways');
        $this->command->info('- Inventory movements');
        $this->command->info('- Contact messages and CMS pages');
        $this->command->info('- Notifications');
    }
}