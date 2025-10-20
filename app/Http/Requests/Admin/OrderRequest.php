<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,partially_paid,refunded,partially_refunded',
            'shipping_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'billing_address' => 'required|array',
            'billing_address.first_name' => 'required|string|max:255',
            'billing_address.last_name' => 'required|string|max:255',
            'billing_address.company' => 'nullable|string|max:255',
            'billing_address.address_line_1' => 'required|string|max:255',
            'billing_address.address_line_2' => 'nullable|string|max:255',
            'billing_address.city' => 'required|string|max:255',
            'billing_address.state' => 'required|string|max:255',
            'billing_address.postal_code' => 'required|string|max:20',
            'billing_address.country' => 'required|string|max:255',
            'billing_address.phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|array',
            'shipping_address.first_name' => 'required|string|max:255',
            'shipping_address.last_name' => 'required|string|max:255',
            'shipping_address.company' => 'nullable|string|max:255',
            'shipping_address.address_line_1' => 'required|string|max:255',
            'shipping_address.address_line_2' => 'nullable|string|max:255',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.state' => 'required|string|max:255',
            'shipping_address.postal_code' => 'required|string|max:20',
            'shipping_address.country' => 'required|string|max:255',
            'shipping_address.phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'nullable|string|max:255',
            'shipping_method' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'Selected user does not exist.',
            'status.required' => 'Order status is required.',
            'status.in' => 'Invalid order status.',
            'payment_status.required' => 'Payment status is required.',
            'payment_status.in' => 'Invalid payment status.',
            'shipping_status.required' => 'Shipping status is required.',
            'shipping_status.in' => 'Invalid shipping status.',
            'billing_address.required' => 'Billing address is required.',
            'billing_address.array' => 'Billing address must be an array.',
            'billing_address.first_name.required' => 'Billing first name is required.',
            'billing_address.last_name.required' => 'Billing last name is required.',
            'billing_address.address_line_1.required' => 'Billing address line 1 is required.',
            'billing_address.city.required' => 'Billing city is required.',
            'billing_address.state.required' => 'Billing state is required.',
            'billing_address.postal_code.required' => 'Billing postal code is required.',
            'billing_address.country.required' => 'Billing country is required.',
            'shipping_address.required' => 'Shipping address is required.',
            'shipping_address.array' => 'Shipping address must be an array.',
            'shipping_address.first_name.required' => 'Shipping first name is required.',
            'shipping_address.last_name.required' => 'Shipping last name is required.',
            'shipping_address.address_line_1.required' => 'Shipping address line 1 is required.',
            'shipping_address.city.required' => 'Shipping city is required.',
            'shipping_address.state.required' => 'Shipping state is required.',
            'shipping_address.postal_code.required' => 'Shipping postal code is required.',
            'shipping_address.country.required' => 'Shipping country is required.',
            'items.required' => 'Order items are required.',
            'items.array' => 'Order items must be an array.',
            'items.min' => 'At least one item is required.',
            'items.*.product_id.required' => 'Product ID is required for each item.',
            'items.*.product_id.exists' => 'Selected product does not exist.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.integer' => 'Quantity must be a whole number.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.price.required' => 'Price is required for each item.',
            'items.*.price.numeric' => 'Price must be a valid number.',
            'items.*.price.min' => 'Price cannot be negative.',
            'subtotal.required' => 'Subtotal is required.',
            'subtotal.numeric' => 'Subtotal must be a valid number.',
            'subtotal.min' => 'Subtotal cannot be negative.',
            'tax_amount.numeric' => 'Tax amount must be a valid number.',
            'tax_amount.min' => 'Tax amount cannot be negative.',
            'shipping_amount.numeric' => 'Shipping amount must be a valid number.',
            'shipping_amount.min' => 'Shipping amount cannot be negative.',
            'discount_amount.numeric' => 'Discount amount must be a valid number.',
            'discount_amount.min' => 'Discount amount cannot be negative.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.numeric' => 'Total amount must be a valid number.',
            'total_amount.min' => 'Total amount cannot be negative.',
            'currency.required' => 'Currency is required.',
            'currency.max' => 'Currency code cannot exceed 3 characters.',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'customer',
            'status' => 'order status',
            'payment_status' => 'payment status',
            'shipping_status' => 'shipping status',
            'billing_address' => 'billing address',
            'shipping_address' => 'shipping address',
            'items' => 'order items',
            'subtotal' => 'subtotal',
            'tax_amount' => 'tax amount',
            'shipping_amount' => 'shipping amount',
            'discount_amount' => 'discount amount',
            'total_amount' => 'total amount',
            'currency' => 'currency',
            'payment_method' => 'payment method',
            'shipping_method' => 'shipping method',
            'tracking_number' => 'tracking number',
            'notes' => 'customer notes',
            'internal_notes' => 'internal notes',
        ];
    }
}
