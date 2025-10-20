<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    /**
     * Get user's payment methods
     */
    public function index()
    {
        $paymentMethods = Auth::user()->paymentMethods()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $paymentMethods,
        ]);
    }

    /**
     * Store a new payment method
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:card,gcash',
            'card_type' => 'required_if:type,card|string|max:255',
            'card_number' => 'required_if:type,card|string|min:13|max:19',
            'card_holder_name' => 'required_if:type,card|string|max:255',
            'card_expiry_month' => 'required_if:type,card|integer|min:1|max:12',
            'card_expiry_year' => 'required_if:type,card|integer|min:'.date('Y'),
            'card_cvv' => 'required_if:type,card|string|min:3|max:4',
            'gcash_number' => 'required_if:type,gcash|string|regex:/^09[0-9]{9}$/',
            'gcash_name' => 'required_if:type,gcash|string|max:255',
            'billing_address' => 'required_if:type,card|array',
            'billing_address.address_line_1' => 'required_if:type,card|string|max:255',
            'billing_address.city' => 'required_if:type,card|string|max:255',
            'billing_address.province' => 'required_if:type,card|string|max:255',
            'billing_address.region' => 'required_if:type,card|string|max:255',
            'billing_address.zip_code' => 'required_if:type,card|string|max:10',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();
        $data = [
            'user_id' => $user->id,
            'type' => $request->type,
        ];

        if ($request->type === 'card') {
            $data = array_merge($data, [
                'card_type' => $request->card_type,
                'card_last_four' => substr($request->card_number, -4),
                'card_holder_name' => $request->card_holder_name,
                'card_expiry_month' => $request->card_expiry_month,
                'card_expiry_year' => $request->card_expiry_year,
                'billing_address' => $request->billing_address,
            ]);
        } elseif ($request->type === 'gcash') {
            $data = array_merge($data, [
                'gcash_number' => $request->gcash_number,
                'gcash_name' => $request->gcash_name,
            ]);
        }

        // If this is set as default, unset other defaults
        if ($request->is_default) {
            $user->paymentMethods()->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            // If this is the first payment method, make it default
            $data['is_default'] = $user->paymentMethods()->count() === 0;
        }

        $paymentMethod = PaymentMethod::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Payment method added successfully',
            'data' => $paymentMethod,
        ]);
    }

    /**
     * Update a payment method
     */
    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'card_holder_name' => 'required_if:type,card|string|max:255',
            'card_expiry_month' => 'required_if:type,card|integer|min:1|max:12',
            'card_expiry_year' => 'required_if:type,card|integer|min:'.date('Y'),
            'gcash_name' => 'required_if:type,gcash|string|max:255',
            'billing_address' => 'required_if:type,card|array',
            'billing_address.address_line_1' => 'required_if:type,card|string|max:255',
            'billing_address.city' => 'required_if:type,card|string|max:255',
            'billing_address.province' => 'required_if:type,card|string|max:255',
            'billing_address.region' => 'required_if:type,card|string|max:255',
            'billing_address.zip_code' => 'required_if:type,card|string|max:10',
        ]);

        $data = [];

        if ($paymentMethod->type === 'card') {
            $data = [
                'card_holder_name' => $request->card_holder_name,
                'card_expiry_month' => $request->card_expiry_month,
                'card_expiry_year' => $request->card_expiry_year,
                'billing_address' => $request->billing_address,
            ];
        } elseif ($paymentMethod->type === 'gcash') {
            $data = [
                'gcash_name' => $request->gcash_name,
            ];
        }

        $paymentMethod->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Payment method updated successfully',
            'data' => $paymentMethod,
        ]);
    }

    /**
     * Remove a payment method
     */
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wasDefault = $paymentMethod->is_default;
        $paymentMethod->delete();

        // If we deleted the default payment method, set another one as default
        if ($wasDefault) {
            $newDefault = Auth::user()->paymentMethods()->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment method removed successfully',
        ]);
    }

    /**
     * Set a payment method as default
     */
    public function setDefault($id)
    {
        $paymentMethod = PaymentMethod::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Unset all other defaults
        Auth::user()->paymentMethods()->update(['is_default' => false]);

        // Set this one as default
        $paymentMethod->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default payment method updated successfully',
            'data' => $paymentMethod,
        ]);
    }
}
