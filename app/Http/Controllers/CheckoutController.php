<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Get selected cart items from session storage.
     */
    private function getSelectedCartItems()
    {
        // Check if selected items are stored in session
        $selectedItems = Session::get('selectedCartItems', []);

        if (! empty($selectedItems)) {
            return $selectedItems;
        }

        // Fallback: if no selected items in session, get all cart items
        $user = Auth::user();
        $allCartItems = CartItem::forUser($user->id)->pluck('product_id')->toArray();

        return $allCartItems;
    }

    /**
     * Show shipping information step.
     */
    public function index()
    {
        $user = Auth::user();

        // Get selected cart items from session storage
        $selectedProductIds = $this->getSelectedCartItems();

        if (empty($selectedProductIds)) {
            return redirect()->route('products')->with('error', 'No items selected for checkout.');
        }

        $cartItems = CartItem::forUser($user->id)
            ->whereIn('product_id', $selectedProductIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum('total_price');

        // Calculate total weight of cart items
        $totalWeight = $cartItems->sum(function ($item) {
            return ($item->product->weight ?? 0) * $item->quantity;
        });

        // Get available shipping methods (only flat_rate for customers, plus auto-detected free/weight-based)
        $availableShippingMethods = ShippingMethod::active()
            ->ordered()
            ->get()
            ->filter(function ($method) use ($subtotal, $totalWeight) {
                // Customers can only select flat_rate manually
                // But we auto-detect free_shipping and weight_based
                if ($method->type === 'flat_rate') {
                    return $method->isAvailableFor($subtotal, $totalWeight);
                }

                return false;
            })
            ->map(function ($method) use ($subtotal, $totalWeight) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'description' => $method->description,
                    'type' => $method->type,
                    'cost' => $method->calculateCost($subtotal, $totalWeight),
                    'estimated_days' => $method->getEstimatedDeliveryDays(),
                ];
            });

        // Auto-detect free shipping if applicable
        $freeShippingMethod = ShippingMethod::active()
            ->where('type', 'free_shipping')
            ->where(function ($query) use ($subtotal) {
                $query->whereNull('free_shipping_threshold')
                    ->orWhere('free_shipping_threshold', '<=', $subtotal);
            })
            ->first();

        // Auto-detect weight-based shipping if applicable
        $weightBasedMethod = ShippingMethod::active()
            ->where('type', 'weight_based')
            ->where(function ($query) use ($subtotal) {
                $query->where(function ($q) use ($subtotal) {
                    $q->whereNull('minimum_order_amount')
                        ->orWhere('minimum_order_amount', '<=', $subtotal);
                });
            })
            ->first();

        // Determine default shipping method and cost
        // Priority: 1. Free shipping, 2. Weight-based, 3. First flat_rate
        $defaultShippingMethod = null;
        $defaultShippingCost = 0;

        if ($freeShippingMethod) {
            $defaultShippingMethod = $freeShippingMethod;
            $defaultShippingCost = 0;
        } elseif ($weightBasedMethod && $totalWeight > 0) {
            $defaultShippingMethod = $weightBasedMethod;
            $defaultShippingCost = $weightBasedMethod->calculateCost($subtotal, $totalWeight);
        } elseif ($availableShippingMethods->isNotEmpty()) {
            $firstFlatRate = ShippingMethod::find($availableShippingMethods->first()['id']);
            $defaultShippingMethod = $firstFlatRate;
            $defaultShippingCost = $availableShippingMethods->first()['cost'];
        }

        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $defaultShippingCost + $taxAmount;

        // Check if default address is complete
        $isDefaultAddressComplete = $this->isDefaultAddressComplete($user);

        return view('checkout.shipping', compact(
            'user',
            'cartItems',
            'subtotal',
            'defaultShippingCost',
            'taxAmount',
            'total',
            'isDefaultAddressComplete',
            'availableShippingMethods',
            'freeShippingMethod',
            'weightBasedMethod',
            'defaultShippingMethod',
            'totalWeight'
        ));
    }

    /**
     * Validate and save shipping information.
     */
    public function validateShipping(Request $request)
    {
        $user = auth()->user();
        $addressOption = $request->input('address_option', 'default');
        $saveAsDefault = $request->boolean('save_as_default', false);

        // Determine shipping method (auto-detected or selected)
        $selectedProductIds = $this->getSelectedCartItems();
        $cartItems = CartItem::forUser($user->id)
            ->whereIn('product_id', $selectedProductIds)
            ->with('product')
            ->get();

        $subtotal = $cartItems->sum('total_price');
        $totalWeight = $cartItems->sum(function ($item) {
            return ($item->product->weight ?? 0) * $item->quantity;
        });

        // Auto-detect shipping method
        $shippingMethod = null;

        // Priority: 1. Free shipping, 2. Weight-based, 3. Selected flat_rate
        $freeShippingMethod = ShippingMethod::active()
            ->where('type', 'free_shipping')
            ->where(function ($query) use ($subtotal) {
                $query->whereNull('free_shipping_threshold')
                    ->orWhere('free_shipping_threshold', '<=', $subtotal);
            })
            ->first();

        if ($freeShippingMethod) {
            $shippingMethod = $freeShippingMethod;
        } else {
            $weightBasedMethod = ShippingMethod::active()
                ->where('type', 'weight_based')
                ->where(function ($query) use ($subtotal) {
                    $query->whereNull('minimum_order_amount')
                        ->orWhere('minimum_order_amount', '<=', $subtotal);
                })
                ->first();

            if ($weightBasedMethod && $totalWeight > 0) {
                $shippingMethod = $weightBasedMethod;
            } else {
                // Use selected flat_rate method
                $request->validate([
                    'shipping_method_id' => 'required|exists:shipping_methods,id',
                ]);
                $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);

                // Verify it's a flat_rate method
                if ($shippingMethod->type !== 'flat_rate') {
                    return redirect()->back()->with('error', 'Invalid shipping method selected.');
                }
            }
        }

        if ($addressOption === 'default') {
            // Use user's default address
            // Check if default address is complete
            if (! $this->isDefaultAddressComplete($user)) {
                return redirect()->back()->with('error', 'Your default address is incomplete. Please provide a complete address or use a different address for shipping.');
            }

            $shippingData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address_line_1' => $user->street, // Philippines address: street, barangay, city, province, region, zip_code
                'address_line_2' => null, // Not used in Philippines address structure
                'city' => $user->city,
                'province' => $user->province,
                'region' => $user->region,
                'barangay' => $user->barangay,
                'zip_code' => $user->zip_code,
                'address_option' => 'default',
                'shipping_method_id' => $shippingMethod->id,
                'shipping_method_name' => $shippingMethod->name,
            ];
        } else {
            // Validate custom address
            $request->validate([
                'address_line_1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'nullable|string|max:255',
                'region' => 'required|string|max:255',
                'barangay' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
            ]);

            // Philippines address structure: street (address_line_1), barangay, city, province, region, zip_code
            $shippingData = $request->only([
                'address_line_1', 'city', 'province',
                'region', 'barangay', 'zip_code',
            ]);
            // Get user info from authenticated user for shipping data
            $shippingData['first_name'] = $user->first_name;
            $shippingData['last_name'] = $user->last_name;
            $shippingData['email'] = $user->email;
            $shippingData['phone'] = $user->phone;
            $shippingData['address_line_2'] = null; // Not used in Philippines address structure
            $shippingData['address_option'] = 'custom';
            $shippingData['shipping_method_id'] = $shippingMethod->id;
            $shippingData['shipping_method_name'] = $shippingMethod->name;

            // Save as default address if requested
            // Philippines address structure: street, barangay, city, province (n/a if NCR), region, zip_code
            if ($saveAsDefault) {
                $user->update([
                    'street' => $shippingData['address_line_1'],
                    'city' => $shippingData['city'],
                    'province' => $shippingData['province'] ?? null, // null for NCR region
                    'region' => $shippingData['region'],
                    'barangay' => $shippingData['barangay'],
                    'zip_code' => $shippingData['zip_code'],
                ]);
            }
        }

        // Calculate shipping cost (already calculated above)
        $shippingCost = $shippingMethod->calculateCost($subtotal, $totalWeight);
        $shippingData['shipping_cost'] = $shippingCost;
        $shippingData['estimated_delivery_days'] = $shippingMethod->getEstimatedDeliveryDays();
        $shippingData['shipping_method_id'] = $shippingMethod->id;

        // Store shipping info in session
        Session::put('checkout.shipping', $shippingData);

        return redirect()->route('checkout.payment');
    }

    /**
     * Show payment method selection step.
     */
    public function showPayment()
    {
        if (! Session::has('checkout.shipping')) {
            return redirect()->route('checkout.index');
        }

        $user = Auth::user();
        $paymentMethods = $user->paymentMethods()->orderBy('is_default', 'desc')->get();

        // Get selected cart items
        $selectedProductIds = $this->getSelectedCartItems();
        $cartItems = CartItem::forUser($user->id)
            ->whereIn('product_id', $selectedProductIds)
            ->with('product')
            ->get();

        $subtotal = $cartItems->sum('total_price');
        $shippingInfo = Session::get('checkout.shipping');
        // Use shipping cost from session (already calculated in shipping step)
        $shippingCost = $shippingInfo['shipping_cost'] ?? 0;
        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $taxAmount;

        $codEligible = $total <= 3000;

        return view('checkout.payment', compact('paymentMethods', 'cartItems', 'subtotal', 'shippingCost', 'taxAmount', 'total', 'codEligible'));
    }

    /**
     * Validate payment method selection.
     */
    public function validatePayment(Request $request)
    {
        try {
            // For COD, ensure payment_method_id is null/empty (not validated)
            if ($request->payment_method === 'cod') {
                $request->merge(['payment_method_id' => null]);
            }

            $validated = $request->validate([
                'payment_method' => 'required|string|in:cod,existing,xendit',
                'payment_method_id' => 'required_if:payment_method,existing|nullable|exists:payment_methods,id',
            ]);

            \Log::info('Payment validation passed', [
                'payment_method' => $request->payment_method,
                'has_shipping' => Session::has('checkout.shipping'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Payment validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);

            throw $e;
        }

        // Store payment info in session
        $paymentData = $request->only(['payment_method', 'payment_method_id']);

        Session::put('checkout.payment', $paymentData);
        Session::save(); // Ensure session is saved immediately

        return redirect()->route('checkout.review')->with('success', 'Payment method selected successfully.');
    }

    /**
     * Show order review step.
     */
    public function showReview()
    {
        if (! Session::has('checkout.shipping') || ! Session::has('checkout.payment')) {
            return redirect()->route('checkout.index');
        }

        $user = Auth::user();

        // Get selected cart items
        $selectedProductIds = $this->getSelectedCartItems();
        $cartItems = CartItem::forUser($user->id)
            ->whereIn('product_id', $selectedProductIds)
            ->with('product')
            ->get();

        $shippingInfo = Session::get('checkout.shipping');
        $paymentInfo = Session::get('checkout.payment');

        $subtotal = $cartItems->sum('total_price');
        // Use shipping cost from session (already calculated in shipping step)
        $shippingCost = $shippingInfo['shipping_cost'] ?? 0;
        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $taxAmount;

        // Get payment method details
        $paymentMethod = null;
        if ($paymentInfo['payment_method'] === 'existing') {
            $paymentMethod = PaymentMethod::where('id', $paymentInfo['payment_method_id'])
                ->where('user_id', $user->id)
                ->first();
        }

        return view('checkout.review', compact('cartItems', 'shippingInfo', 'paymentInfo', 'paymentMethod', 'subtotal', 'shippingCost', 'taxAmount', 'total'));
    }

    /**
     * Process the final order.
     */
    public function processOrder(Request $request)
    {
        if (! Session::has('checkout.shipping')) {
            return redirect()->route('checkout.index');
        }

        // Get payment info from request or session
        if ($request->has('payment_method')) {
            // Validate payment method
            $request->validate([
                'payment_method' => 'required|string|in:cod,existing,xendit',
                'payment_method_id' => 'required_if:payment_method,existing|nullable|exists:payment_methods,id',
            ]);

            // Save payment info from request to session
            $paymentData = $request->only(['payment_method', 'payment_method_id']);

            // For COD and xendit, ensure payment_method_id is null
            if (in_array($paymentData['payment_method'], ['cod', 'xendit'])) {
                $paymentData['payment_method_id'] = null;
            }

            Session::put('checkout.payment', $paymentData);
            Session::save();
            $paymentInfo = $paymentData;
        } else {
            // Get from session
            if (! Session::has('checkout.payment')) {
                return redirect()->route('checkout.payment')->with('error', 'Please select a payment method.');
            }
            $paymentInfo = Session::get('checkout.payment');
        }

        $user = Auth::user();

        // Get selected cart items
        $selectedProductIds = $this->getSelectedCartItems();
        $cartItems = CartItem::forUser($user->id)
            ->whereIn('product_id', $selectedProductIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        $shippingInfo = Session::get('checkout.shipping');

        $subtotal = $cartItems->sum('total_price');
        // Use shipping cost from session (already calculated in shipping step)
        $shippingCost = $shippingInfo['shipping_cost'] ?? 0;
        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $taxAmount;

        // Validate COD eligibility
        if ($paymentInfo['payment_method'] === 'cod' && $total > 3000) {
            return redirect()->back()->with('error', 'Cash on Delivery is only available for orders ₱3,000 and below.');
        }

        try {
            DB::beginTransaction();

            // Check if order requires approval (high value orders or special conditions)
            $requiresApproval = $this->shouldRequireApproval($total, $user);
            $approvalReason = $requiresApproval ? $this->getApprovalReason($total, $user) : null;

            // Create order with payment method details
            $paymentMethodName = $this->getPaymentMethodName($paymentInfo);
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => $requiresApproval ? 'pending' : 'processing',
                'fulfillment_status' => 'pending',
                'requires_approval' => $requiresApproval,
                'approval_reason' => $approvalReason,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingCost,
                'shipping_method' => $shippingInfo['shipping_method_name'] ?? 'standard',
                'shipping_cost' => $shippingCost,
                'discount_amount' => 0,
                'total_amount' => $total,
                'currency' => 'PHP',
                'billing_address' => $shippingInfo, // Using shipping as billing for now
                'shipping_address' => $shippingInfo,
                'payment_method' => $paymentMethodName,
                'payment_status' => 'pending',
                'notes' => $request->notes ?? null,
            ]);

            // Store cart item IDs that are being ordered (for cart clearing later)
            // Store as JSON in admin_notes so we can retrieve them later when payment is confirmed
            $orderedCartItemIds = [];

            // Create order items and decrement stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'product_name' => $cartItem->product_name,
                    'product_sku' => $cartItem->product_sku,
                ]);
                // Track which cart items were included in this order
                $orderedCartItemIds[] = $cartItem->id;

                // Decrement stock immediately for COD orders (payment is confirmed)
                // For Xendit orders, stock will be decremented when payment is confirmed via webhook
                if (($paymentInfo['payment_method'] ?? 'cod') === 'cod') {
                    try {
                        // Record inventory movement (this also decrements stock)
                        \App\Models\InventoryMovement::recordStockOut(
                            $cartItem->product_id,
                            $cartItem->quantity,
                            'order',
                            "Order #{$order->order_number} - COD payment confirmed",
                            'App\Models\Order',
                            $order->id,
                            $user->id
                        );
                    } catch (\Exception $e) {
                        \Log::warning('Failed to decrement stock for COD order', [
                            'order_id' => $order->id,
                            'product_id' => $cartItem->product_id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the order if stock update fails
                    }
                }
            }

            // Store ordered cart item IDs in order for later cart clearing
            $order->admin_notes = trim(($order->admin_notes ? $order->admin_notes."\n" : '').'OrderedCartItemIds: '.json_encode($orderedCartItemIds));

            // Note: For Xendit payments, payment method is selected on Xendit's hosted page

            // Store estimated delivery days in shipping address
            $shippingInfo['estimated_delivery_days'] = $shippingInfo['estimated_delivery_days'] ?? '5-7 days';
            $order->shipping_address = $shippingInfo;
            $order->save();

            // Send order confirmation email
            try {
                // Send email directly using Mailable (not Notification)
                \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderCreatedMail($order));
            } catch (\Exception $e) {
                // Log error but don't fail the order
                \Log::error('Failed to send order confirmation email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Clear checkout session
            Session::forget(['checkout.shipping', 'checkout.payment']);

            DB::commit();

            // If payment is COD, clear only the ordered cart items immediately and go to summary
            if (($paymentInfo['payment_method'] ?? 'cod') === 'cod') {
                // Clear only the cart items that were included in this order
                CartItem::whereIn('id', $orderedCartItemIds)->delete();

                return redirect()->route('checkout.summary', ['order' => $order->order_number])
                    ->with('success', 'Order placed successfully!');
            }

            // For Xendit payments, DO NOT clear cart yet - wait for payment confirmation
            // Cart will be cleared when payment_status becomes 'paid' via webhook or returnSuccess
            return redirect()->route('checkout.confirmation', ['order' => $order->order_number]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    /**
     * Show order confirmation (intermediate page before payment gateway or summary).
     */
    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('orderItems.product')
            ->firstOrFail();

        // If COD, go directly to summary
        if ($order->payment_method === 'Cash on Delivery') {
            return redirect()->route('checkout.summary', ['order' => $order->order_number]);
        }

        // If this is a polling request, return JSON status only (don't render full page)
        if (request()->has('poll') || request()->wantsJson() || request()->ajax()) {
            $order->refresh();

            return response()->json([
                'payment_status' => $order->payment_status,
                'order_number' => $order->order_number,
            ]);
        }

        // Prepare data for layout (order summary sidebar)
        // Convert orderItems to cartItems-like format for the sidebar
        $cartItems = collect($order->orderItems)->map(function ($item) {
            return (object) [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'product_sku' => $item->product_sku,
                'product' => $item->product,
            ];
        });

        $subtotal = $order->subtotal;
        $shippingCost = $order->shipping_cost;
        $taxAmount = $order->tax_amount;
        $total = $order->total_amount;

        // Check if this is a return from Xendit (via query parameter or session) - MUST be before payment status check
        $isReturnFromPayment = request()->has('payment_return') || session()->has('payment_return');

        // Reload order to get latest status (webhook may have updated it)
        $previousPaymentStatus = $order->payment_status;
        $order->refresh();
        $paymentStatus = $order->payment_status;
        $errorMessage = null;
        $successMessage = null;

        // Only clear cart when payment is confirmed as paid (not on initial confirmation page load)
        // Cart will be cleared by webhook when payment_status becomes 'paid'
        // OR by returnSuccess handler when user returns from successful payment

        if ($paymentStatus === 'failed') {
            // Get error message from session or default
            $errorMessage = session('error', 'Your payment could not be processed. Please try again.');
            // Do NOT clear cart if payment fails - keep items for retry
        } elseif ($paymentStatus === 'paid') {
            // Payment successful - show success message
            $successMessage = session('success', 'Payment successful!');
            // Clear cart only when payment is confirmed as paid AND we're returning from payment
            // This ensures we don't clear cart on every page load, only when payment is successful
            if ($isReturnFromPayment || $previousPaymentStatus !== 'paid') {
                // Clear only the cart items that were included in this order
                $this->clearOrderedCartItems($order);
            }
        }

        return view('checkout.confirmation', [
            'order' => $order,
            'payment_status' => $paymentStatus,
            'currentStep' => 4,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'taxAmount' => $taxAmount,
            'total' => $total,
            'errorMessage' => $errorMessage,
            'successMessage' => $successMessage ?? null,
            'isReturnFromPayment' => $isReturnFromPayment,
        ]);
    }

    /**
     * Show order summary (final confirmation page after successful payment).
     */
    public function summary($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('orderItems.product')
            ->firstOrFail();

        // Calculate estimated delivery date
        $estimatedDeliveryDays = 0;
        if ($order->shipping_address && isset($order->shipping_address['estimated_delivery_days'])) {
            $estimatedDeliveryDays = (int) str_replace(['-', ' days', ' day'], '', $order->shipping_address['estimated_delivery_days']);
        }
        $estimatedDeliveryDate = $estimatedDeliveryDays > 0
            ? now()->addDays($estimatedDeliveryDays)->format('F d, Y')
            : now()->addDays(7)->format('F d, Y'); // Default 7 days

        // Prepare data for layout (order summary sidebar)
        // Convert orderItems to cartItems-like format for the sidebar
        $cartItems = collect($order->orderItems)->map(function ($item) {
            return (object) [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
                'product_sku' => $item->product_sku,
                'product' => $item->product,
            ];
        });

        $subtotal = $order->subtotal;
        $shippingCost = $order->shipping_cost;
        $taxAmount = $order->tax_amount;
        $total = $order->total_amount;

        return view('checkout.summary', [
            'order' => $order,
            'estimatedDeliveryDate' => $estimatedDeliveryDate,
            'currentStep' => 5,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'taxAmount' => $taxAmount,
            'total' => $total,
        ]);
    }

    /**
     * Calculate shipping cost.
     */
    private function calculateShipping($region, $subtotal)
    {
        // Free shipping over ₱5,000
        if ($subtotal >= 5000) {
            return 0;
        }

        $shippingRates = [
            'National Capital Region (NCR)' => 50,
            'Metro Manila' => 50,
        ];

        return $shippingRates[$region] ?? 100; // Default provincial rate
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

    /**
     * Calculate tax (12% VAT).
     */
    private function calculateTax($subtotal)
    {
        return $subtotal * 0.12;
    }

    /**
     * Get payment method display name.
     */
    private function getPaymentMethodName($paymentInfo)
    {
        switch ($paymentInfo['payment_method']) {
            case 'cod':
                return 'Cash on Delivery';
            case 'existing':
                $paymentMethod = PaymentMethod::find($paymentInfo['payment_method_id']);

                return $paymentMethod ? $paymentMethod->getDisplayName() : 'Unknown Payment Method';
            case 'xendit':
                return 'Online Payment (via Xendit)';
            default:
                return 'Unknown Payment Method';
        }
    }

    /**
     * Save new payment method.
     */
    private function saveNewPaymentMethod($user, $paymentInfo)
    {
        $data = [
            'user_id' => $user->id,
            'type' => $paymentInfo['new_payment_type'],
            'is_default' => $user->paymentMethods()->count() === 0, // First payment method is default
        ];

        if ($paymentInfo['new_payment_type'] === 'card') {
            $data = array_merge($data, [
                'card_type' => $this->detectCardType($paymentInfo['card_number']),
                'card_last_four' => substr($paymentInfo['card_number'], -4),
                'card_holder_name' => $paymentInfo['card_holder_name'],
                'card_expiry_month' => $paymentInfo['card_expiry_month'],
                'card_expiry_year' => $paymentInfo['card_expiry_year'],
                'billing_address' => $paymentInfo['billing_address'],
            ]);
        } elseif ($paymentInfo['new_payment_type'] === 'gcash') {
            $data = array_merge($data, [
                'gcash_number' => $paymentInfo['gcash_number'],
                'gcash_name' => $paymentInfo['gcash_name'],
            ]);
        }

        PaymentMethod::create($data);
    }

    /**
     * Detect card type from number.
     */
    private function detectCardType($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        if (preg_match('/^4/', $cardNumber)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $cardNumber)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47]/', $cardNumber)) {
            return 'American Express';
        } elseif (preg_match('/^6/', $cardNumber)) {
            return 'Discover';
        }

        return 'Unknown';
    }

    /**
     * Check if user's default address is complete.
     */
    private function isDefaultAddressComplete($user)
    {
        // Check essential address fields - street, barangay, city, region, zipcode
        // Province can be null as per user requirement
        $requiredFields = [
            'street',
            'barangay',
            'city',
            'region',
            'zip_code',
        ];

        foreach ($requiredFields as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if order requires approval.
     */
    private function shouldRequireApproval($total, $user)
    {
        // Orders over ₱10,000 require approval
        if ($total > 10000) {
            return true;
        }

        // New users (less than 30 days old) with orders over ₱5,000 require approval
        if ($user->created_at->diffInDays(now()) < 30 && $total > 5000) {
            return true;
        }

        // Users with previous cancelled orders require approval for orders over ₱3,000
        $hasCancelledOrders = Order::where('user_id', $user->id)
            ->where('status', 'cancelled')
            ->exists();

        if ($hasCancelledOrders && $total > 3000) {
            return true;
        }

        return false;
    }

    /**
     * Get approval reason for order.
     */
    private function getApprovalReason($total, $user)
    {
        if ($total > 10000) {
            return 'High value order (₱'.number_format($total, 2).') requires manager approval';
        }

        if ($user->created_at->diffInDays(now()) < 30 && $total > 5000) {
            return 'New customer with order over ₱5,000 requires approval';
        }

        $hasCancelledOrders = Order::where('user_id', $user->id)
            ->where('status', 'cancelled')
            ->exists();

        if ($hasCancelledOrders && $total > 3000) {
            return 'Customer with previous cancelled orders requires approval for orders over ₱3,000';
        }

        return 'Order requires approval';
    }
}
