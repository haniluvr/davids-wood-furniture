# Adding Payment Methods to Xendit

## Overview

Xendit supports multiple payment methods that can be enabled for your invoices. This guide explains how to configure and add different payment methods to your checkout flow.

## Available Payment Method Types

Xendit supports the following payment method categories:

### 1. **Cards**
- `CREDIT_CARD` - Credit cards (Visa, Mastercard, American Express, JCB, etc.)
- `DEBIT_CARD` - Debit cards

### 2. **E-Wallets** (Philippines)
- `EWALLET` - Shows all enabled e-wallets (GCash, PayMaya, GrabPay, etc.)
- When using `EWALLET`, Xendit will display all e-wallets that are enabled in your Xendit dashboard

### 3. **Bank Transfers**
- `BANK_TRANSFER` - Online banking transfers (BPI, BDO, Metrobank, etc.)
- Customers can pay via their bank's online banking portal

### 4. **Retail Outlets**
- `RETAIL_OUTLET` - Over-the-counter payments at retail stores
- Includes: 7-Eleven, SM, Robinsons, etc.

### 5. **Direct Debit**
- `DIRECT_DEBIT` - Automated bank debit (BPI, RCBC, etc.)
- Requires customer bank account authorization

### 6. **QR Code Payments**
- `QR_CODE` - QR code payments (GCash QR, PayMaya QR, etc.)
- Customers scan QR code to complete payment

## Configuration Methods

### Method 1: Configure via Admin Panel (Recommended)

1. Go to **Admin → Settings → Integrations → Xendit**
2. Find the **Payment Methods** field (if available)
3. Enter comma-separated payment methods:
   ```
   CREDIT_CARD,DEBIT_CARD,EWALLET,BANK_TRANSFER
   ```
4. Save settings

### Method 2: Update Code Directly

Edit `app/Http/Controllers/Payments/XenditPaymentController.php`:

```php
$payload = [
    // ... other fields ...
    'payment_methods' => [
        'CREDIT_CARD',
        'DEBIT_CARD',
        'EWALLET',
        'BANK_TRANSFER',
        'RETAIL_OUTLET',
        'QR_CODE',
    ],
];
```

### Method 3: Environment Variable (Future Enhancement)

Add to `.env`:
```env
XENDIT_PAYMENT_METHODS=CREDIT_CARD,DEBIT_CARD,EWALLET,BANK_TRANSFER
```

## Specific E-Wallet Configuration

### Enable Specific E-Wallets

When using `EWALLET`, the specific e-wallets shown depend on what's enabled in your Xendit dashboard:

1. **Login to Xendit Dashboard**
2. Go to **Settings → Payment Channels**
3. Enable/disable specific e-wallets:
   - ✅ GCash
   - ✅ PayMaya
   - ✅ GrabPay
   - ✅ ShopeePay
   - And more...

### Note on E-Wallets

- `EWALLET` as a payment method type automatically includes all e-wallets enabled in your account
- You cannot specify individual e-wallets in the API (e.g., you can't do `GCASH`, `PAYMAYA` separately)
- The dropdown shown to customers will include all e-wallets you've enabled in Xendit dashboard

## Excluding Payment Methods

If you want to show most payment methods but exclude specific ones:

```php
$payload = [
    // ... other fields ...
    'payment_methods' => ['CREDIT_CARD', 'DEBIT_CARD', 'EWALLET', 'BANK_TRANSFER'],
    // Optionally exclude specific methods (if supported by Xendit)
    // Note: Check Xendit API docs for latest exclude options
];
```

## Current Implementation

The current code allows you to configure payment methods via the `Setting` model:

```php
$paymentMethods = Setting::get('xendit_payment_methods', 'CREDIT_CARD,DEBIT_CARD,EWALLET');
$paymentMethodsArray = array_map('trim', explode(',', $paymentMethods));
```

**Default**: `CREDIT_CARD,DEBIT_CARD,EWALLET`

## Adding New Payment Methods Step-by-Step

### Example: Adding Bank Transfers

1. **Enable in Xendit Dashboard**:
   - Login to Xendit
   - Go to Settings → Payment Channels
   - Enable "Bank Transfer"

2. **Update Code**:
   ```php
   'payment_methods' => ['CREDIT_CARD', 'DEBIT_CARD', 'EWALLET', 'BANK_TRANSFER'],
   ```

3. **Test**:
   - Create a test order
   - You should see "Bank Transfer" option in the payment gateway

### Example: Adding QR Code Payments

1. **Enable in Xendit Dashboard**:
   - Enable QR Code payments in your Xendit account

2. **Update Code**:
   ```php
   'payment_methods' => ['CREDIT_CARD', 'DEBIT_CARD', 'EWALLET', 'QR_CODE'],
   ```

3. **Test**:
   - Customers will see QR code option when checking out

## Recommended Payment Methods for Philippines

For a Philippine e-commerce site, we recommend:

```php
'payment_methods' => [
    'CREDIT_CARD',
    'DEBIT_CARD',
    'EWALLET',      // GCash, PayMaya, GrabPay
    'BANK_TRANSFER', // Online banking
    'RETAIL_OUTLET', // 7-Eleven, SM, etc.
],
```

This gives customers maximum flexibility while covering the most popular payment methods in the Philippines.

## Testing Payment Methods

### Test Cards (Credit/Debit)
Use Xendit's test card numbers:
- **Success**: `4000000000000002`
- **3D Secure**: `4000008400000009`
- **Declined**: `4000000000000069`

### Test E-Wallets
Use Xendit's test e-wallet numbers (check Xendit documentation)

### Test Bank Transfer
Bank transfers in test mode will show as pending and can be manually marked as paid in Xendit dashboard

## Test Mode vs Live Mode

### ⚠️ Test Mode Limitations

**Important:** Xendit's Test Mode has limitations on payment methods:

- **✅ Credit/Debit Cards**: Usually fully available in test mode
- **⚠️ E-Wallets (GCash, PayMaya, GrabPay)**: May be limited or require specific test accounts
- **⚠️ Bank Transfer**: Limited functionality in test mode
- **⚠️ Retail Outlets (7-Eleven, etc.)**: Limited or not available in test mode
- **⚠️ Direct Debit**: Limited test scenarios available
- **⚠️ QR Code**: May be limited in test mode

**If you only see Credit Cards in test mode:**
1. This is **normal behavior** - many payment methods require Live mode
2. Test with credit cards using test card numbers (4000000000000002, etc.)
3. Switch to **Live** mode after business verification to see all payment methods

### Live Mode

Once you verify your business and switch to Live mode, all activated payment methods should appear correctly.

## Important Notes

1. **Xendit Dashboard Configuration**: Some payment methods must be enabled in your Xendit dashboard first before they'll appear even if you include them in the API call.

2. **Invoice Settings**: Even if payment methods are activated in Payment Channels, you must also configure them in **Invoice Settings → Payment Method → Manage** in the Xendit Dashboard.

3. **Test Mode Limitations**: Many payment methods are limited or unavailable in Test Mode. This is why you might only see Credit Cards even if you've selected multiple methods.

4. **Region Availability**: Not all payment methods are available in all regions. Check Xendit's documentation for Philippines-specific availability.

5. **Fees**: Different payment methods may have different fees. Check your Xendit pricing.

6. **Settlement Times**: Different payment methods have different settlement times (instant, same-day, next-day, etc.).

7. **User Experience**: Too many payment options can be overwhelming. Consider your customer base and focus on the most popular methods.

## References

- [Xendit API Documentation](https://docs.xendit.co/)
- [Xendit Payment Methods Guide](https://docs.xendit.co/payments/)
- [Philippines Payment Methods](https://docs.xendit.co/payments/methods/)

