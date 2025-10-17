<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Crypt;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentGateways = [
            [
                'name' => 'Stripe',
                'gateway_key' => 'stripe',
                'display_name' => 'Stripe',
                'description' => 'Accept credit cards, debit cards, and digital wallets',
                'config' => [
                    'publishable_key' => 'pk_test_...',
                    'api_key' => Crypt::encryptString('sk_test_...'),
                    'webhook_secret' => Crypt::encryptString('whsec_...'),
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'supported_countries' => ['US', 'CA', 'GB', 'AU', 'DE', 'FR'],
                'transaction_fee_percentage' => 2.9,
                'transaction_fee_fixed' => 0.30,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'PayPal',
                'gateway_key' => 'paypal',
                'display_name' => 'PayPal',
                'description' => 'Accept PayPal payments and credit cards',
                'config' => [
                    'client_id' => 'test_client_id',
                    'client_secret' => Crypt::encryptString('test_client_secret'),
                    'webhook_id' => 'test_webhook_id',
                ],
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'supported_countries' => ['US', 'CA', 'GB', 'AU', 'DE', 'FR'],
                'transaction_fee_percentage' => 3.4,
                'transaction_fee_fixed' => 0.30,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Square',
                'gateway_key' => 'square',
                'display_name' => 'Square',
                'description' => 'Accept payments online and in-person',
                'config' => [
                    'application_id' => 'test_app_id',
                    'access_token' => Crypt::encryptString('test_access_token'),
                    'location_id' => 'test_location_id',
                ],
                'supported_currencies' => ['USD', 'CAD', 'GBP', 'AUD'],
                'supported_countries' => ['US', 'CA', 'GB', 'AU'],
                'transaction_fee_percentage' => 2.9,
                'transaction_fee_fixed' => 0.30,
                'is_active' => false,
                'is_test_mode' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Authorize.Net',
                'gateway_key' => 'authorize_net',
                'display_name' => 'Authorize.Net',
                'description' => 'Reliable payment processing for businesses',
                'config' => [
                    'login_id' => 'test_login_id',
                    'transaction_key' => Crypt::encryptString('test_transaction_key'),
                    'signature_key' => Crypt::encryptString('test_signature_key'),
                ],
                'supported_currencies' => ['USD', 'CAD'],
                'supported_countries' => ['US', 'CA'],
                'transaction_fee_percentage' => 2.9,
                'transaction_fee_fixed' => 0.30,
                'is_active' => false,
                'is_test_mode' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Cash on Delivery',
                'gateway_key' => 'cod',
                'display_name' => 'Cash on Delivery',
                'description' => 'Pay with cash when your order is delivered',
                'config' => [
                    'enabled' => true,
                    'max_amount' => 500.00,
                ],
                'supported_currencies' => ['USD'],
                'supported_countries' => ['US'],
                'transaction_fee_percentage' => 0,
                'transaction_fee_fixed' => 0,
                'is_active' => true,
                'is_test_mode' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($paymentGateways as $gateway) {
            PaymentGateway::create($gateway);
        }

        $this->command->info('Payment gateways seeded successfully.');
    }
}
