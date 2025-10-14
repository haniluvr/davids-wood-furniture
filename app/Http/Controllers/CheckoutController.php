<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;

class CheckoutController extends Controller
{

    /**
     * Show shipping information step
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = CartItem::forUser($user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum('total_price');
        $shippingCost = $this->calculateShipping($user->region, $subtotal);
        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $taxAmount;

        return view('checkout.shipping', compact('user', 'cartItems', 'subtotal', 'shippingCost', 'taxAmount', 'total'));
    }

    /**
     * Validate and save shipping information
     */
    public function validateShipping(Request $request)
    {
        $addressOption = $request->input('address_option', 'default');
        
        if ($addressOption === 'default') {
            // Use user's default address
            $user = auth()->user();
            $shippingData = [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address_line_1' => $user->address_line_1,
                'address_line_2' => $user->address_line_2,
                'city' => $user->city,
                'province' => $user->province,
                'region' => $user->region,
                'barangay' => $user->barangay,
                'zip_code' => $user->zip_code,
                'address_option' => 'default'
            ];
        } else {
            // Validate custom address
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'barangay' => 'nullable|string|max:255',
                'zip_code' => 'required|string|max:10',
            ]);
            
            $shippingData = $request->all();
        }

        // Store shipping info in session
        Session::put('checkout.shipping', $shippingData);

        return redirect()->route('checkout.payment');
    }

    /**
     * Show payment method selection step
     */
    public function showPayment()
    {
        if (!Session::has('checkout.shipping')) {
            return redirect()->route('checkout.index');
        }

        $user = Auth::user();
        $paymentMethods = $user->paymentMethods()->orderBy('is_default', 'desc')->get();
        
        $cartItems = CartItem::forUser($user->id)
            ->with('product')
            ->get();

        $subtotal = $cartItems->sum('total_price');
        $shippingInfo = Session::get('checkout.shipping');
        $shippingCost = $this->calculateShipping($shippingInfo['region'], $subtotal);
        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $taxAmount;

        $codEligible = $total <= 3000;

        return view('checkout.payment', compact('paymentMethods', 'cartItems', 'subtotal', 'shippingCost', 'taxAmount', 'total', 'codEligible'));
    }

    /**
     * Validate payment method selection
     */
    public function validatePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:cod,existing,new',
            'payment_method_id' => 'required_if:payment_method,existing|exists:payment_methods,id',
            'new_payment_type' => 'required_if:payment_method,new|in:card,gcash',
        ]);

        // Additional validation for new payment methods
        if ($request->payment_method === 'new') {
            if ($request->new_payment_type === 'card') {
                $request->validate([
                    'card_number' => 'required|string|min:13|max:19',
                    'card_holder_name' => 'required|string|max:255',
                    'card_expiry_month' => 'required|integer|min:1|max:12',
                    'card_expiry_year' => 'required|integer|min:' . date('Y'),
                    'card_cvv' => 'required|string|min:3|max:4',
                    'billing_address' => 'required|array',
                    'billing_address.address_line_1' => 'required|string|max:255',
                    'billing_address.city' => 'required|string|max:255',
                    'billing_address.province' => 'required|string|max:255',
                    'billing_address.region' => 'required|string|max:255',
                    'billing_address.zip_code' => 'required|string|max:10',
                ]);
            } elseif ($request->new_payment_type === 'gcash') {
                $request->validate([
                    'gcash_number' => 'required|string|regex:/^09[0-9]{9}$/',
                    'gcash_name' => 'required|string|max:255',
                ]);
            }
        }

        // Store payment info in session
        Session::put('checkout.payment', $request->all());

        return redirect()->route('checkout.review');
    }

    /**
     * Show order review step
     */
    public function showReview()
    {
        if (!Session::has('checkout.shipping') || !Session::has('checkout.payment')) {
            return redirect()->route('checkout.index');
        }

        $user = Auth::user();
        $cartItems = CartItem::forUser($user->id)
            ->with('product')
            ->get();

        $shippingInfo = Session::get('checkout.shipping');
        $paymentInfo = Session::get('checkout.payment');
        
        $subtotal = $cartItems->sum('total_price');
        $shippingCost = $this->calculateShipping($shippingInfo['region'], $subtotal);
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
     * Process the final order
     */
    public function processOrder(Request $request)
    {
        if (!Session::has('checkout.shipping') || !Session::has('checkout.payment')) {
            return redirect()->route('checkout.index');
        }

        $user = Auth::user();
        $cartItems = CartItem::forUser($user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products')->with('error', 'Your cart is empty.');
        }

        $shippingInfo = Session::get('checkout.shipping');
        $paymentInfo = Session::get('checkout.payment');

        $subtotal = $cartItems->sum('total_price');
        $shippingCost = $this->calculateShipping($shippingInfo['region'], $subtotal);
        $taxAmount = $this->calculateTax($subtotal);
        $total = $subtotal + $shippingCost + $taxAmount;

        // Validate COD eligibility
        if ($paymentInfo['payment_method'] === 'cod' && $total > 3000) {
            return redirect()->back()->with('error', 'Cash on Delivery is only available for orders ₱3,000 and below.');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingCost,
                'shipping_method' => 'standard',
                'shipping_cost' => $shippingCost,
                'discount_amount' => 0,
                'total_amount' => $total,
                'currency' => 'PHP',
                'billing_address' => $shippingInfo, // Using shipping as billing for now
                'shipping_address' => $shippingInfo,
                'payment_method' => $this->getPaymentMethodName($paymentInfo),
                'payment_status' => 'pending',
                'notes' => $request->notes ?? null,
            ]);

            // Create order items
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
            }

            // Save new payment method if applicable
            if ($paymentInfo['payment_method'] === 'new') {
                $this->saveNewPaymentMethod($user, $paymentInfo);
            }

            // Clear cart
            CartItem::forUser($user->id)->delete();

            // Clear checkout session
            Session::forget(['checkout.shipping', 'checkout.payment']);

            DB::commit();

            return redirect()->route('checkout.confirmation', ['order' => $order->order_number])
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('orderItems.product')
            ->firstOrFail();

        return view('checkout.confirmation', compact('order'));
    }

    /**
     * Calculate shipping cost
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
     * Calculate tax (12% VAT)
     */
    private function calculateTax($subtotal)
    {
        return $subtotal * 0.12;
    }

    /**
     * Get payment method display name
     */
    private function getPaymentMethodName($paymentInfo)
    {
        switch ($paymentInfo['payment_method']) {
            case 'cod':
                return 'Cash on Delivery';
            case 'existing':
                $paymentMethod = PaymentMethod::find($paymentInfo['payment_method_id']);
                return $paymentMethod ? $paymentMethod->getDisplayName() : 'Unknown Payment Method';
            case 'new':
                return $paymentInfo['new_payment_type'] === 'card' ? 'Credit/Debit Card' : 'GCash';
            default:
                return 'Unknown Payment Method';
        }
    }

    /**
     * Save new payment method
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
     * Detect card type from number
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
}
