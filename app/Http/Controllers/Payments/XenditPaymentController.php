<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
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

        // Build invoice payload
        $payload = [
            'external_id' => $order->order_number,
            'amount' => (float) $order->total_amount,
            'currency' => 'PHP',
            'payer_email' => optional($order->user)->email,
            'description' => 'Payment for order '.$order->order_number,
            'success_redirect_url' => $successUrl,
            'failure_redirect_url' => $failedUrl,
        ];

        // Create invoice via Xendit API
        $response = Http::withBasicAuth($secret, '')
            ->asJson()
            ->post('https://api.xendit.co/v2/invoices', $payload);

        if (! $response->successful()) {
            Log::error('Xendit invoice creation failed', [
                'status' => $response->status(),
                'body' => $response->json(),
                'order' => $order->id,
            ]);

            return redirect()->route('checkout.review')->with('error', 'Unable to create payment. Please try again.');
        }

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
        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order && $order->user_id === Auth::id()) {
                return redirect()->route('checkout.confirmation', ['order' => $order->order_number])
                    ->with('success', 'Payment processing. We will update your order shortly.');
            }
        }

        return redirect()->route('account.orders')->with('success', 'Payment processing.');
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
                return redirect()->route('checkout.review')->with('error', 'Payment was cancelled or failed.');
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
            Log::warning('Xendit webhook unauthorized');

            return response()->json(['message' => 'unauthorized'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit webhook received', ['payload' => $payload]);

        // Handle invoice status updates
        $event = $payload['event'] ?? null; // e.g., invoice.paid, invoice.expired
        $data = $payload['data'] ?? [];
        $externalId = $data['external_id'] ?? null;
        $status = $data['status'] ?? null; // PAID, EXPIRED, PENDING

        if ($externalId) {
            $order = Order::where('order_number', $externalId)->first();
            if ($order) {
                if ($status === 'PAID') {
                    $order->payment_status = 'paid';
                    $order->status = $order->status === 'pending' ? 'processing' : $order->status;
                } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
                    $order->payment_status = 'failed';
                }

                // append note
                $note = 'Xendit Update: '.($data['id'] ?? 'N/A').' status '.$status;
                $order->admin_notes = trim(($order->admin_notes ? $order->admin_notes."\n" : '').$note);
                $order->save();
            }
        }

        return response()->json(['received' => true]);
    }
}
