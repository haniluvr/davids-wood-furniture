<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\PaymentGateway;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display the settings index page.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $paymentGateways = PaymentGateway::ordered()->get();
        $shippingMethods = ShippingMethod::ordered()->get();
        
        return view('admin.settings.index', compact('settings', 'paymentGateways', 'shippingMethods'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            'currency' => 'required|string|size:3',
            'currency_symbol' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:20',
            'items_per_page' => 'required|integer|min:5|max:100',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string|max:1000',
        ]);

        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            $validated['site_logo'] = $logoPath;
        }

        if ($request->hasFile('site_favicon')) {
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            $validated['site_favicon'] = $faviconPath;
        }

        // Update settings
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear cache
        Cache::forget('settings');

        return back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => 'required|string|max:20',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:20',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'mail_reply_to' => 'nullable|email|max:255',
            'mail_reply_to_name' => 'nullable|string|max:255',
        ]);

        // Update settings
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Email settings updated successfully.');
    }

    /**
     * Update payment gateway settings.
     */
    public function updatePaymentGateway(Request $request, PaymentGateway $paymentGateway)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
            'is_test_mode' => 'boolean',
            'config' => 'nullable|array',
        ]);

        // Handle encrypted config values
        if (isset($validated['config'])) {
            $config = $validated['config'];
            foreach ($config as $key => $value) {
                if (in_array($key, ['api_key', 'secret_key', 'webhook_secret', 'private_key'])) {
                    $paymentGateway->setEncryptedConfigValue($key, $value);
                } else {
                    $paymentGateway->setConfigValue($key, $value);
                }
            }
            unset($validated['config']);
        }

        $paymentGateway->update($validated);

        return back()->with('success', 'Payment gateway settings updated successfully.');
    }

    /**
     * Update shipping method settings.
     */
    public function updateShippingMethod(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:flat_rate,free_shipping,weight_based,price_based',
            'cost' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'required|numeric|min:0',
            'maximum_order_amount' => 'nullable|numeric|min:0',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'zones' => 'nullable|array',
            'weight_rates' => 'nullable|array',
        ]);

        $shippingMethod->update($validated);

        return back()->with('success', 'Shipping method updated successfully.');
    }

    /**
     * Create new shipping method.
     */
    public function createShippingMethod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:flat_rate,free_shipping,weight_based,price_based',
            'cost' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'minimum_order_amount' => 'required|numeric|min:0',
            'maximum_order_amount' => 'nullable|numeric|min:0',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'zones' => 'nullable|array',
            'weight_rates' => 'nullable|array',
        ]);

        ShippingMethod::create($validated);

        return back()->with('success', 'Shipping method created successfully.');
    }

    /**
     * Delete shipping method.
     */
    public function deleteShippingMethod(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();

        return back()->with('success', 'Shipping method deleted successfully.');
    }

    /**
     * Test email configuration.
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Send test email
            \Mail::raw('This is a test email from your admin panel.', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test Email from Admin Panel');
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send test email: ' . $e->getMessage()]);
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');

        return back()->with('success', 'Application cache cleared successfully.');
    }

    /**
     * Get setting value.
     */
    public function getSetting(string $key, $default = null)
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Set setting value.
     */
    public function setSetting(string $key, $value)
    {
        Setting::setValue($key, $value);
        Cache::forget('settings');
    }
}
