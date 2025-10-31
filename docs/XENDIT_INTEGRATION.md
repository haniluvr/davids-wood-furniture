# Xendit Payment Integration Guide

## Overview

Xendit is now fully integrated into the checkout flow. When customers select Credit/Debit Card or GCash as their payment method, they will be redirected to Xendit's hosted payment page to complete the transaction.

## How It Works

### Payment Flow

1. **Customer selects payment method** on `/checkout/payment`:
   - **Cash on Delivery (COD)**: Order is created immediately, customer pays upon delivery
   - **Credit/Debit Card or GCash**: Customer is redirected to Xendit payment page

2. **Order Creation**: When a non-COD payment is selected:
   - Order is created with `payment_status = 'pending'`
   - Payment method type (card/gcash) is stored in order notes
   - Customer is redirected to `/payments/xendit/pay/{order_id}`

3. **Xendit Invoice Creation**: 
   - Creates a Xendit invoice via API
   - Includes payment channels (CREDIT_CARD, DEBIT_CARD, EWALLET for GCash)
   - Prioritizes the selected payment method (card or GCash)
   - Redirects customer to Xendit's hosted payment page

4. **Payment Completion**:
   - Customer completes payment on Xendit
   - Xendit sends webhook to `/webhooks/xendit`
   - Order status is updated based on payment status
   - Customer is redirected to confirmation page

## Configuration

### 1. Xendit Account Setup

1. Sign up at [Xendit](https://www.xendit.co/)
2. Get your API credentials:
   - **Secret Key** (for server-side API calls)
   - **Public Key** (optional, for client-side)
   - **Callback Token** (for webhook verification)

### 2. Configure in Admin Panel

Go to **Admin → Settings → Integrations → Xendit**:
- Enter your **Secret Key**
- Enter your **Callback Token**
- Select **Environment** (Test or Live)
- Toggle **Enabled** to activate Xendit

### 3. Environment Variables (Optional)

You can also set defaults in your `.env` file:

```env
XENDIT_SECRET_KEY=your_secret_key_here
XENDIT_CALLBACK_TOKEN=your_callback_token_here
XENDIT_ENV=test
```

### 4. Webhook Configuration

1. In Xendit Dashboard, go to **Settings → Webhooks**
2. Add webhook URL: `https://yourdomain.com/webhooks/xendit`
3. Use your **Callback Token** for verification
4. Subscribe to events:
   - `invoice.paid`
   - `invoice.expired`
   - `invoice.failed`

## Payment Methods Supported

### Credit/Debit Cards
- Visa
- Mastercard
- American Express
- Discover

### E-Wallets
- GCash (via Xendit's EWALLET channel)

### Cash on Delivery
- Direct order creation (no payment gateway)

## Payment Method Priority

The system prioritizes the selected payment method:

- **Card selected**: Shows cards first, then e-wallet options
- **GCash selected**: Shows e-wallet first, then card options
- **Default**: All payment methods available

## Code Structure

### Controllers

- `CheckoutController`: Handles order creation and payment method selection
- `XenditPaymentController`: Manages Xendit API integration
  - `pay()`: Creates Xendit invoice and redirects
  - `returnSuccess()`: Handles successful payment return
  - `returnFailed()`: Handles failed payment return
  - `webhook()`: Processes Xendit webhook events

### Routes

```php
// Payment routes
Route::get('/payments/xendit/pay/{order}', ...);
Route::get('/payments/xendit/return/success', ...);
Route::get('/payments/xendit/return/failed', ...);

// Webhook (public, no auth required)
Route::post('/webhooks/xendit', ...);
```

## Testing

### Test Mode

1. Use Xendit test credentials
2. Use test card numbers from Xendit documentation:
   - **Success**: 4000000000000002
   - **3D Secure**: 4000008400000009
   - **Declined**: 4000000000000069

### Test GCash

Use test GCash numbers provided by Xendit in their documentation.

### Webhook Testing

Use Xendit's webhook simulator or ngrok to test webhook locally:
```bash
ngrok http 8080
# Use the ngrok URL in Xendit webhook settings
```

## Error Handling

- **Xendit not configured**: Shows error message, redirects back to review
- **Invoice creation failed**: Logs error, shows user-friendly message
- **Payment failed**: Customer redirected to review page with error
- **Webhook unauthorized**: Logs warning, returns 401

## Security Notes

- Secret keys are never exposed client-side
- Webhook verification using callback token
- Only order owner can access payment URLs
- All Xendit API calls are server-side only

## Troubleshooting

### Payment Not Redirecting to Xendit
- Check Xendit secret key is configured
- Verify Xendit is enabled in admin settings
- Check Laravel logs for API errors

### Webhook Not Receiving Updates
- Verify webhook URL is accessible publicly
- Check callback token matches Xendit dashboard
- Review webhook logs in Xendit dashboard

### Payment Status Not Updating
- Check webhook is properly configured
- Verify webhook handler is receiving requests
- Review Laravel logs for webhook processing errors

## Support

For Xendit-specific issues:
- [Xendit Documentation](https://docs.xendit.co/)
- [Xendit Support](https://www.xendit.co/en/support/)

For integration issues:
- Check Laravel logs: `storage/logs/laravel.log`
- Review Xendit dashboard for invoice status
- Verify order records in database

