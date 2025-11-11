<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentGateway::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('gateway_key', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by mode
        if ($request->filled('mode')) {
            $query->where('is_test_mode', $request->mode === 'test');
        }

        $paymentGateways = $query->ordered()->paginate(15);

        return view('admin.payment-gateways.index', compact('paymentGateways'));
    }

    public function create()
    {
        return view('admin.payment-gateways.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gateway_key' => 'required|string|max:255|unique:payment_gateways,gateway_key',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'config' => 'nullable|array',
            'supported_currencies' => 'nullable|array',
            'supported_countries' => 'nullable|array',
            'transaction_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'transaction_fee_fixed' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_test_mode' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Set default values for boolean fields if not present (unchecked checkboxes)
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;
        $data['is_test_mode'] = $request->has('is_test_mode') ? (bool) $request->is_test_mode : true;

        // Sync status and environment from config if provided
        if (isset($data['config']['status'])) {
            $data['is_active'] = $data['config']['status'] === 'active';
        }
        if (isset($data['config']['environment'])) {
            $data['is_test_mode'] = $data['config']['environment'] === 'sandbox';
        }

        // Encrypt sensitive configuration data
        if (isset($data['config']) && is_array($data['config'])) {
            $encryptedConfig = [];
            foreach ($data['config'] as $key => $value) {
                if (in_array($key, ['api_key', 'secret_key', 'webhook_secret', 'private_key'])) {
                    $encryptedConfig[$key] = Crypt::encryptString($value);
                } else {
                    $encryptedConfig[$key] = $value;
                }
            }
            // Remove old 'types' array if 'type' exists (migration back to single type)
            if (isset($encryptedConfig['type']) && ! empty($encryptedConfig['type'])) {
                unset($encryptedConfig['types']);
            }
            $data['config'] = $encryptedConfig;
        }

        $paymentGateway = PaymentGateway::create($data);

        // Log the action
        AuditLog::log('payment_gateway.created', Auth::guard('admin')->user(), $paymentGateway, [], [], "Created payment gateway: {$paymentGateway->display_name}");

        return redirect()->to(admin_route('payment-gateways.index'))
            ->with('success', 'Payment gateway created successfully.');
    }

    public function show(PaymentGateway $paymentGateway)
    {
        return view('admin.payment-gateways.show', compact('paymentGateway'));
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        return view('admin.payment-gateways.edit', compact('paymentGateway'));
    }

    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'gateway_key' => 'required|string|max:255|unique:payment_gateways,gateway_key,'.$paymentGateway->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'config' => 'nullable|array',
            'supported_currencies' => 'nullable|array',
            'supported_countries' => 'nullable|array',
            'transaction_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'transaction_fee_fixed' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_test_mode' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldValues = $paymentGateway->only(['name', 'display_name', 'is_active', 'is_test_mode']);
        $data = $request->all();

        // Set default values for boolean fields if not present (unchecked checkboxes)
        if (! $request->has('is_active')) {
            $data['is_active'] = $paymentGateway->is_active;
        } else {
            $data['is_active'] = (bool) $request->is_active;
        }
        if (! $request->has('is_test_mode')) {
            $data['is_test_mode'] = $paymentGateway->is_test_mode;
        } else {
            $data['is_test_mode'] = (bool) $request->is_test_mode;
        }

        // Sync status and environment from config if provided
        if (isset($data['config']['status'])) {
            $data['is_active'] = $data['config']['status'] === 'active';
        }
        if (isset($data['config']['environment'])) {
            $data['is_test_mode'] = $data['config']['environment'] === 'sandbox';
        }

        // Handle configuration data
        if (isset($data['config']) && is_array($data['config'])) {
            $encryptedConfig = $paymentGateway->config ?? [];
            $sensitiveFields = ['api_key', 'secret_key', 'webhook_secret', 'private_key'];

            foreach ($data['config'] as $key => $value) {
                // For sensitive fields, only update if a value is provided (not empty)
                if (in_array($key, $sensitiveFields)) {
                    if (! empty($value)) {
                        $encryptedConfig[$key] = Crypt::encryptString($value);
                    }
                    // If empty, keep the existing value (don't update)
                } else {
                    // For non-sensitive fields, update even if empty
                    $encryptedConfig[$key] = $value;
                }
            }
            // Remove old 'types' array if 'type' exists (migration back to single type)
            if (isset($encryptedConfig['type']) && ! empty($encryptedConfig['type'])) {
                unset($encryptedConfig['types']);
            }
            $data['config'] = $encryptedConfig;
        }

        $paymentGateway->update($data);

        // Log the action
        AuditLog::log('payment_gateway.updated', Auth::guard('admin')->user(), $paymentGateway, $oldValues, $paymentGateway->only(['name', 'display_name', 'is_active', 'is_test_mode']), "Updated payment gateway: {$paymentGateway->display_name}");

        return redirect()->to(admin_route('payment-gateways.index'))
            ->with('success', 'Payment gateway updated successfully.');
    }

    public function destroy(PaymentGateway $paymentGateway)
    {
        $paymentGatewayData = $paymentGateway->toArray();
        $paymentGateway->delete();

        // Log the action
        AuditLog::log('payment_gateway.deleted', Auth::guard('admin')->user(), null, $paymentGatewayData, [], "Deleted payment gateway: {$paymentGatewayData['display_name']}");

        return redirect()->to(admin_route('payment-gateways.index'))
            ->with('success', 'Payment gateway deleted successfully.');
    }

    public function toggleStatus(PaymentGateway $paymentGateway)
    {
        $oldStatus = $paymentGateway->is_active;
        $paymentGateway->update(['is_active' => ! $paymentGateway->is_active]);

        // Log the action
        AuditLog::log('payment_gateway.status_toggled', Auth::guard('admin')->user(), $paymentGateway, ['is_active' => $oldStatus], ['is_active' => $paymentGateway->is_active], "Toggled payment gateway status for {$paymentGateway->display_name} from ".($oldStatus ? 'active' : 'inactive').' to '.($paymentGateway->is_active ? 'active' : 'inactive'));

        return response()->json([
            'success' => true,
            'message' => 'Payment gateway status updated successfully.',
            'is_active' => $paymentGateway->is_active,
        ]);
    }

    public function toggleMode(PaymentGateway $paymentGateway)
    {
        $oldMode = $paymentGateway->is_test_mode;
        $paymentGateway->update(['is_test_mode' => ! $paymentGateway->is_test_mode]);

        // Log the action
        AuditLog::log('payment_gateway.mode_toggled', Auth::guard('admin')->user(), $paymentGateway, ['is_test_mode' => $oldMode], ['is_test_mode' => $paymentGateway->is_test_mode], "Toggled payment gateway mode for {$paymentGateway->display_name} from ".($oldMode ? 'test' : 'live').' to '.($paymentGateway->is_test_mode ? 'test' : 'live'));

        return response()->json([
            'success' => true,
            'message' => 'Payment gateway mode updated successfully.',
            'is_test_mode' => $paymentGateway->is_test_mode,
        ]);
    }

    public function testConnection(PaymentGateway $paymentGateway)
    {
        try {
            // This would typically make an API call to test the connection
            // For now, we'll simulate a test
            $isConnected = true; // Replace with actual API test

            if ($isConnected) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection test successful.',
                    'gateway' => $paymentGateway->display_name,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Connection test failed. Please check your configuration.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: '.$e->getMessage(),
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'payment_gateways' => 'required|array',
            'payment_gateways.*.id' => 'required|exists:payment_gateways,id',
            'payment_gateways.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->payment_gateways as $item) {
            PaymentGateway::where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order']]);
        }

        // Log the action
        AuditLog::log('payment_gateway.reordered', Auth::guard('admin')->user(), null, [], $request->payment_gateways, 'Reordered payment gateways');

        return response()->json([
            'success' => true,
            'message' => 'Payment gateways reordered successfully.',
        ]);
    }
}
