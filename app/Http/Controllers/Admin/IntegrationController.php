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
            'enabled' => 'nullable|boolean',
        ]);

        // Persist to settings
        foreach ($data as $key => $value) {
            Setting::set('xendit_'.$key, $value ?? '');
        }

        Setting::set('integration_xendit_enabled', (bool) ($data['enabled'] ?? false));

        AuditLog::create([
            'admin_id' => Auth::id(),
            'action' => 'integration_updated',
            'model_type' => Setting::class,
            'model_id' => null,
            'old_values' => null,
            'new_values' => ['integration' => $integration, 'data' => $data],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->to(admin_route('integrations.edit', $integration))
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

            return response()->json([
                'success' => false,
                'message' => 'Xendit responded with status '.$response->status().': '.($response->json('error') ?? 'Unknown error'),
            ], $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
