<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SustainabilityController extends Controller
{
    public function index()
    {
        $defaults = [
            'carbon_offset_provider' => '',
            'offset_per_order' => '0',
            'packaging_material' => 'standard',
            'sourcing_policy' => '',
            'show_badge' => '0',
        ];

        $settings = [];
        foreach (array_keys($defaults) as $key) {
            $settings[$key] = Setting::get($key, $defaults[$key]);
        }

        return view('admin.settings.sustainability', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'carbon_offset_provider' => 'nullable|string|max:255',
            'offset_per_order' => 'required|numeric|min:0',
            'packaging_material' => 'required|string|max:255',
            'sourcing_policy' => 'nullable|string',
            'show_badge' => 'nullable|boolean',
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value === null ? '' : $value);
        }

        AuditLog::create([
            'admin_id' => Auth::id(),
            'action' => 'sustainability_settings_updated',
            'model_type' => Setting::class,
            'model_id' => null,
            'old_values' => null,
            'new_values' => $data,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->to(admin_route('settings.sustainability'))
            ->with('success', 'Sustainability settings saved.');
    }
}
