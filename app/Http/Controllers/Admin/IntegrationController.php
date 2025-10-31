<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class IntegrationController extends Controller
{
    public function index()
    {
        // A simple registry using settings namespace keys
        $integrations = [
            [
                'key' => 'xendit',
                'name' => 'Xendit',
                'description' => 'Online payments for SEA. Configure API keys and webhooks.',
                'enabled' => (bool) Setting::get('integration_xendit_enabled', false),
            ],
        ];

        return view('admin.integrations.index', compact('integrations'));
    }

    public function edit(string $integration)
    {
        abort_unless(in_array($integration, ['xendit']), 404);

        $config = [
            'public_key' => Setting::get('xendit_public_key', config('services.xendit.public_key')),
            'secret_key' => Setting::get('xendit_secret_key', config('services.xendit.secret_key')),
            'callback_token' => Setting::get('xendit_callback_token', config('services.xendit.callback_token')),
            'environment' => Setting::get('xendit_environment', config('services.xendit.environment', 'test')),
            'payment_methods' => Setting::get('xendit_payment_methods', 'CREDIT_CARD,DEBIT_CARD,EWALLET'),
            'enabled' => (bool) Setting::get('integration_xendit_enabled', false),
        ];

        return view('admin.integrations.edit', [
            'integration' => $integration,
            'config' => $config,
        ]);
    }

    public function update(Request $request, string $integration)
    {
        abort_unless(in_array($integration, ['xendit']), 404);

        $data = $request->validate([
            'public_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'callback_token' => 'nullable|string|max:255',
            'environment' => 'required|in:test,live',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'in:CREDIT_CARD,DEBIT_CARD,EWALLET,BANK_TRANSFER,RETAIL_OUTLET,QR_CODE,DIRECT_DEBIT',
            'enabled' => 'nullable|boolean',
        ]);

        // Convert payment_methods array to comma-separated string
        if (isset($data['payment_methods']) && is_array($data['payment_methods']) && ! empty($data['payment_methods'])) {
            // Filter out any empty values and ensure they're uppercase
            $filteredMethods = array_filter(array_map('trim', $data['payment_methods']));
            $data['payment_methods'] = implode(',', $filteredMethods);

            \Log::info('Payment methods saved', [
                'raw_input' => $request->input('payment_methods'),
                'filtered_array' => $filteredMethods,
                'saved_string' => $data['payment_methods'],
                'count' => count($filteredMethods),
            ]);
        } else {
            $data['payment_methods'] = 'CREDIT_CARD,DEBIT_CARD,EWALLET'; // Default
            \Log::info('Payment methods not provided, using default', ['default' => $data['payment_methods']]);
        }

        // Persist to settings
        foreach ($data as $key => $value) {
            Setting::set('xendit_'.$key, $value ?? '');
        }

        Setting::set('integration_xendit_enabled', (bool) ($data['enabled'] ?? false));

        AuditLog::create([
            'user_type' => 'admin',
            'user_id' => Auth::guard('admin')->id(),
            'action' => 'integration_updated',
            'model' => Setting::class,
            'model_id' => null,
            'old_values' => null,
            'new_values' => ['integration' => $integration, 'data' => $data],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'description' => "Updated {$integration} integration settings",
        ]);

        return redirect()->to(admin_route('integrations.edit', ['integration' => $integration]))
            ->with('success', 'Integration settings saved.');
    }

    public function testConnection(Request $request, string $integration)
    {
        abort_unless($integration === 'xendit', 404);

        $secret = $request->input('secret_key') ?: Setting::get('xendit_secret_key', config('services.xendit.secret_key'));
        $env = $request->input('environment') ?: Setting::get('xendit_environment', config('services.xendit.environment', 'test'));

        if (! $secret) {
            return response()->json([
                'success' => false,
                'message' => 'Secret key is missing.',
            ], 422);
        }

        try {
            // Simple ping using Balance endpoint which requires auth
            $response = Http::withBasicAuth($secret, '')
                ->timeout(10)
                ->get('https://api.xendit.co/balance', [
                    'account_type' => 'CASH',
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Xendit connection successful.',
                    'data' => $response->json(),
                ]);
            }

            // Get error details
            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? $errorBody['error'] ?? 'Unknown error';

            // Check for common 401 issues
            if ($response->status() === 401) {
                $errorMessage = 'Authentication failed. Please verify your Secret Key is correct. Make sure you\'re using the Secret Key (not Public Key or Callback Token).';
            }

            return response()->json([
                'success' => false,
                'message' => 'Xendit responded with status '.$response->status().': '.$errorMessage,
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
