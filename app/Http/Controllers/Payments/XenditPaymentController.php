<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class XenditPaymentController extends Controller
{
    /**
     * Create an invoice and redirect customer to Xendit hosted page.
     */
    public function pay(Order $order)
    {
        // Only allow customer to pay own order and only when pending
        abort_unless(Auth::id() && $order->user_id === Auth::id(), 403);
        abort_unless($order->payment_status === 'pending', 400);

        $secret = Setting::get('xendit_secret_key', config('services.xendit.secret_key'));
        $env = Setting::get('xendit_environment', config('services.xendit.environment', 'test'));

        abort_unless(! empty($secret), 500, 'Xendit not configured');

        $successUrl = URL::route('payments.xendit.return.success', ['order' => $order->order_number]);
        $failedUrl = URL::route('payments.xendit.return.failed', ['order' => $order->order_number]);

        // Build invoice payload - configure available payment methods
        // Available payment_methods options:
        // - 'CREDIT_CARD': Credit cards (Visa, Mastercard, Amex, etc.)
        // - 'DEBIT_CARD': Debit cards
        // - 'EWALLET': E-wallets (GCash, PayMaya, GrabPay, etc. - shows all enabled in Xendit)
        // - 'BANK_TRANSFER': Bank transfers (BPI, BDO, etc.)
        // - 'RETAIL_OUTLET': Retail outlets (7-Eleven, etc.)
        // - 'DIRECT_DEBIT': Direct debit (BPI, RCBC, etc.)
        // - 'QR_CODE': QR code payments (GCash QR, PayMaya QR, etc.)
        // You can also use 'should_exclude' to exclude specific methods if needed
        $paymentMethods = Setting::get('xendit_payment_methods', 'CREDIT_CARD,DEBIT_CARD,EWALLET');
        $paymentMethodsArray = array_values(array_filter(array_map('trim', explode(',', $paymentMethods)))); // Filter out empty values and reset keys

        // If no payment methods found, use default
        if (empty($paymentMethodsArray)) {
            $paymentMethodsArray = ['CREDIT_CARD', 'DEBIT_CARD', 'EWALLET'];
            Log::warning('No payment methods found in settings, using default', ['default' => $paymentMethodsArray]);
        }

        // Ensure all values are uppercase (Xendit expects uppercase)
        $paymentMethodsArray = array_map('strtoupper', $paymentMethodsArray);

        // In test mode, some payment methods may not be available
        // Filter based on environment if needed (Xendit will handle this server-side, but we log it)
        if ($env === 'test') {
            Log::info('Xendit test mode detected - some payment methods may have limitations', [
                'requested_methods' => $paymentMethodsArray,
            ]);
        }

        Log::info('Xendit payment methods configured', [
            'environment' => $env,
            'setting_value' => $paymentMethods,
            'methods_array' => $paymentMethodsArray,
            'count' => count($paymentMethodsArray),
            'array_type' => gettype($paymentMethodsArray),
        ]);

        // Build payload
        // NOTE: Xendit Invoice API may ignore payment_methods parameter
        // and only show channels activated/configured at account level
        $payload = [
            'external_id' => $order->order_number,
            'amount' => (float) $order->total_amount,
            'currency' => 'PHP',
            'payer_email' => optional($order->user)->email,
            'description' => 'Payment for order '.$order->order_number,
            'success_redirect_url' => $successUrl,
            'failure_redirect_url' => $failedUrl,
            'should_send_email' => true,
        ];

        // Only include payment_methods if we have specific methods to include
        // If omitted, Xendit should show all activated channels for the account
        // However, based on testing, it seems Xendit may still require account-level configuration
        if (! empty($paymentMethodsArray) && count($paymentMethodsArray) > 0) {
            $payload['payment_methods'] = $paymentMethodsArray;
            Log::info('Including payment_methods in payload', ['methods' => $paymentMethodsArray]);
        } else {
            Log::info('Omitting payment_methods - Xendit will use account defaults', [
                'note' => 'All activated channels in Payment Channels should appear',
            ]);
        }

        // Log the payload being sent to Xendit (excluding sensitive data)
        Log::info('Xendit invoice creation payload', [
            'external_id' => $payload['external_id'],
            'amount' => $payload['amount'],
            'payment_methods' => $payload['payment_methods'] ?? 'not included',
            'payment_methods_count' => isset($payload['payment_methods']) ? count($payload['payment_methods']) : 0,
        ]);

        // Create invoice via Xendit API
        $response = Http::withBasicAuth($secret, '')
            ->asJson()
            ->post('https://api.xendit.co/v2/invoices', $payload);

        if (! $response->successful()) {
            Log::error('Xendit invoice creation failed', [
                'status' => $response->status(),
                'body' => $response->json(),
                'order' => $order->id,
                'payment_methods_sent' => $payload['payment_methods'] ?? 'not set',
            ]);

            return redirect()->route('checkout.review')->with('error', 'Unable to create payment. Please try again.');
        }

        // Log successful invoice creation with payment methods
        $invoiceResponse = $response->json();
        Log::info('Xendit invoice created successfully', [
            'invoice_id' => $invoiceResponse['id'] ?? 'N/A',
            'invoice_url' => $invoiceResponse['invoice_url'] ?? 'N/A',
            'payment_methods_sent' => $payload['payment_methods'] ?? [],
            'payment_methods_in_response' => $invoiceResponse['available_payment_methods'] ?? $invoiceResponse['payment_methods'] ?? 'not in response',
            'invoice_full_response' => $invoiceResponse, // Full response to debug what Xendit returns
            'order_number' => $order->order_number,
        ]);

        $invoice = $response->json();
        $invoiceUrl = $invoice['invoice_url'] ?? null;

        if (! $invoiceUrl) {
            return redirect()->route('checkout.review')->with('error', 'Payment link not available.');
        }

        // Optionally note invoice id for admins (no schema change)
        try {
            $order->admin_notes = trim(($order->admin_notes ? $order->admin_notes."\n" : '').'Xendit Invoice: '.($invoice['id'] ?? 'N/A').', External: '.$order->order_number);
            $order->save();
        } catch (\Throwable $e) {
            // Non-fatal
        }

        return redirect()->away($invoiceUrl);
    }

    /**
     * Customer success return. We rely on webhook for the final status.
     */
    public function returnSuccess(Request $request)
    {
        $orderNumber = $request->query('order');
        Log::info('Xendit returnSuccess called', ['order_number' => $orderNumber]);

        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order && $order->user_id === Auth::id()) {
                // Reload order to get latest payment status from webhook (webhook may have already updated it)
                $order->refresh();

                Log::info('Xendit returnSuccess: Order found', [
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status,
                ]);

                // Check Xendit API directly for the latest invoice status as fallback
                try {
                    $secret = Setting::get('xendit_secret_key', config('services.xendit.secret_key'));
                    if ($secret) {
                        $xenditInvoiceId = null;
                        // Extract invoice ID from admin_notes if available
                        if ($order->admin_notes && preg_match('/Xendit Invoice: ([a-f0-9]+)/', $order->admin_notes, $matches)) {
                            $xenditInvoiceId = $matches[1];

                            // Fetch invoice status from Xendit API
                            $response = Http::withBasicAuth($secret, '')
                                ->get('https://api.xendit.co/v2/invoices/'.$xenditInvoiceId);

                            if ($response->successful()) {
                                $invoice = $response->json();
                                if (isset($invoice['status']) && $invoice['status'] === 'PAID') {
                                    // Update order status directly
                                    $order->payment_status = 'paid';
                                    $order->status = $order->status === 'pending' ? 'processing' : $order->status;
                                    $order->save();

                                    // Clear only the cart items that were included in this order
                                    $this->clearOrderedCartItems($order);

                                    Log::info('Payment status updated via direct API check in returnSuccess', [
                                        'order_number' => $order->order_number,
                                        'invoice_id' => $xenditInvoiceId,
                                    ]);

                                    // Create notification for successful payment
                                    try {
                                        $notification = Notification::createForUser(
                                            $order->user,
                                            'Payment Successful',
                                            "Your payment for order #{$order->order_number} has been confirmed successfully. Your order is now being processed.",
                                            'system',
                                            ['order_id' => $order->id, 'order_number' => $order->order_number, 'type' => 'payment_success']
                                        );
                                        // Mark as sent immediately so it shows in the badge
                                        $notification->markAsSent();
                                    } catch (\Exception $e) {
                                        Log::warning('Failed to create payment success notification in returnSuccess', [
                                            'order_id' => $order->id,
                                            'error' => $e->getMessage(),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to check Xendit invoice status directly', [
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Reload order one more time to get the latest status
                $order->refresh();

                // Instead of redirecting, show a simple success page in the new tab
                // The original confirmation page will automatically detect the payment success via polling
                return view('payments.xendit-return', [
                    'order' => $order,
                    'status' => $order->payment_status === 'paid' ? 'success' : 'processing',
                ]);
            } else {
                Log::warning('Xendit returnSuccess: Order not found or unauthorized', [
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        // Fallback view if order not found
        return view('payments.xendit-return', [
            'order' => null,
            'status' => 'error',
        ]);
    }

    /**
     * Customer failed return.
     */
    public function returnFailed(Request $request)
    {
        $orderNumber = $request->query('order');
        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order && $order->user_id === Auth::id()) {
                // Update order payment status to failed
                $order->payment_status = 'failed';
                $order->save();

                // Redirect to confirmation page with payment_return flag
                return redirect()->route('checkout.confirmation', [
                    'order' => $order->order_number,
                    'payment_return' => '1',
                ])->with([
                    'error' => 'Payment was cancelled or failed. Please try again.',
                    'payment_return' => true,
                ]);
            }
        }

        return redirect()->route('account.orders')->with('error', 'Payment failed.');
    }

    /**
     * Webhook to receive invoice updates from Xendit.
     */
    public function webhook(Request $request)
    {
        $tokenHeader = $request->header('x-callback-token') ?: $request->header('X-CALLBACK-TOKEN');
        $expected = Setting::get('xendit_callback_token', config('services.xendit.callback_token'));

        if (empty($expected) || $tokenHeader !== $expected) {
            Log::warning('Xendit webhook unauthorized', [
                'received_token' => $tokenHeader ? 'present' : 'missing',
                'expected_set' => ! empty($expected),
            ]);

            return response()->json(['message' => 'unauthorized'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit webhook received', ['payload' => $payload]);

        // Handle invoice status updates
        // Xendit can send payloads in two formats:
        // 1. Wrapped: { "event": "invoice.paid", "data": { "external_id": "...", "status": "PAID" } }
        // 2. Direct: { "external_id": "...", "status": "PAID", ... }
        $event = $payload['event'] ?? null; // e.g., invoice.paid, invoice.expired
        $data = $payload['data'] ?? [];

        // Check if payload is wrapped (has 'data' key) or direct
        if (! empty($data) && isset($data['external_id'])) {
            // Wrapped format
            $externalId = $data['external_id'] ?? null;
            $status = $data['status'] ?? null;
        } else {
            // Direct format - payload itself contains the invoice data
            $externalId = $payload['external_id'] ?? null;
            $status = $payload['status'] ?? null;
        }

        Log::info('Xendit webhook processing', [
            'event' => $event,
            'external_id' => $externalId,
            'status' => $status,
            'payload_format' => ! empty($data) && isset($data['external_id']) ? 'wrapped' : 'direct',
        ]);

        if ($externalId) {
            $order = Order::where('order_number', $externalId)->first();
            if ($order) {
                $previousStatus = $order->payment_status;

                if ($status === 'PAID') {
                    $order->payment_status = 'paid';
                    $order->status = $order->status === 'pending' ? 'processing' : $order->status;

                    Log::info('Xendit payment confirmed via webhook', [
                        'order_number' => $order->order_number,
                        'previous_status' => $previousStatus,
                        'new_status' => 'paid',
                    ]);

                    // Clear only the cart items that were included in this order
                    $this->clearOrderedCartItems($order);
                    Log::info('Cart items cleared for paid order', ['order_id' => $order->id]);

                    // Create notification for successful payment
                    try {
                        $notification = Notification::createForUser(
                            $order->user,
                            'Payment Successful',
                            "Your payment for order #{$order->order_number} has been confirmed successfully. Your order is now being processed.",
                            'system',
                            ['order_id' => $order->id, 'order_number' => $order->order_number, 'type' => 'payment_success']
                        );
                        // Mark as sent immediately so it shows in the badge
                        $notification->markAsSent();
                    } catch (\Exception $e) {
                        Log::warning('Failed to create payment success notification', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } elseif (in_array($status, ['EXPIRED', 'FAILED', 'VOIDED'])) {
                    $order->payment_status = 'failed';
                    Log::info('Xendit payment failed/expired via webhook', [
                        'order_number' => $order->order_number,
                        'status' => $status,
                    ]);
                    // Do NOT clear cart if payment fails - keep items for retry
                } else {
                    Log::info('Xendit webhook received with status', [
                        'order_number' => $order->order_number,
                        'status' => $status,
                    ]);
                }

                // append note - get ID from data or payload
                $invoiceId = $data['id'] ?? $payload['id'] ?? 'N/A';
                $note = 'Xendit Update: '.$invoiceId.' status '.$status.' at '.now()->toDateTimeString();
                $order->admin_notes = trim(($order->admin_notes ? $order->admin_notes."\n" : '').$note);
                $order->save();

                Log::info('Order updated via webhook', [
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status,
                ]);
            } else {
                Log::warning('Xendit webhook: Order not found', ['external_id' => $externalId]);
            }
        } else {
            Log::warning('Xendit webhook: No external_id in payload', ['payload' => $payload]);
        }

        return response()->json(['received' => true]);
    }

    /**
     * Clear only the cart items that were included in a specific order.
     */
    private function clearOrderedCartItems(Order $order)
    {
        // Extract cart item IDs from admin_notes
        $orderedCartItemIds = [];
        if ($order->admin_notes && preg_match('/OrderedCartItemIds:\s*(\[[\d,\s]+\])/', $order->admin_notes, $matches)) {
            $jsonArray = $matches[1];
            $orderedCartItemIds = json_decode($jsonArray, true) ?? [];
            $orderedCartItemIds = array_filter(array_map('intval', $orderedCartItemIds));
        }

        if (! empty($orderedCartItemIds)) {
            // Clear only the specific cart items that were in this order
            CartItem::whereIn('id', $orderedCartItemIds)->delete();
        } else {
            // Fallback: if we can't find the IDs, match by product_id from order items
            $productIds = $order->orderItems()->pluck('product_id')->toArray();
            if (! empty($productIds)) {
                CartItem::forUser($order->user_id)
                    ->whereIn('product_id', $productIds)
                    ->delete();
            }
        }
    }
}
