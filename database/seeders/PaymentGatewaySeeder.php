<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;
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
                'name' => 'Xendit',
                'gateway_key' => 'xendit',
                'display_name' => 'Xendit',
                'description' => 'Accept multiple payment methods: Credit Cards (Visa, Mastercard, Amex, JCB), Debit Cards, E-Wallets (GCash, PayMaya, GrabPay, ShopeePay), Bank Transfer (BPI, BDO, Metrobank), Retail Outlet (7-Eleven, Cebuana, LBC), QR Code (QRPH), and Direct Debit (BPI, RCBC, Chinabank, UBP)',
                'config' => [
                    'api_key' => Crypt::encryptString('xnd_development_...'),
                    'callback_token' => Crypt::encryptString('your_callback_token_here'),
                    'payment_methods' => [
                        'CREDIT_CARD',
                        'DEBIT_CARD',
                        'EWALLET',
                        'BANK_TRANSFER',
                        'RETAIL_OUTLET',
                        'QR_CODE',
                        'DIRECT_DEBIT',
                    ],
                ],
                'supported_currencies' => ['PHP'],
                'supported_countries' => ['PH'],
                'transaction_fee_percentage' => 3.5,
                'transaction_fee_fixed' => 0.00,
                'is_active' => true,
                'is_test_mode' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Cash on Delivery',
                'gateway_key' => 'cod',
                'display_name' => 'Cash on Delivery',
                'description' => 'Pay with cash when your order is delivered',
                'config' => [
                    'enabled' => true,
                    'max_amount' => 3000.00,
                ],
                'supported_currencies' => ['PHP'],
                'supported_countries' => ['PH'],
                'transaction_fee_percentage' => 0,
                'transaction_fee_fixed' => 0,
                'is_active' => true,
                'is_test_mode' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($paymentGateways as $gateway) {
            PaymentGateway::updateOrCreate(
                ['gateway_key' => $gateway['gateway_key']],
                $gateway
            );
        }

        $this->command->info('Payment gateways seeded successfully.');
    }
}
