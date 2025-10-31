# Cash on Delivery (COD) Flow

## Overview
When a customer selects "Cash on Delivery" as their payment method, the order is processed immediately since no payment gateway interaction is required.

## Complete Flow

### 1. **Shipping Step** (`/checkout`)
- Customer selects/enters shipping address
- Customer selects shipping method
- Information saved to session: `checkout.shipping`

### 2. **Payment Step** (`/checkout/payment`)
- Customer selects "Cash on Delivery"
- System validates COD eligibility (orders must be ₱3,000 or below)
- Payment method saved to session: `checkout.payment`

### 3. **Review Step** (`/checkout/review`)
- Customer reviews order details
- Shows: shipping address, payment method, order items, pricing breakdown
- Customer clicks "Place Order" button

### 4. **Order Processing** (`processOrder()` method)
When "Place Order" is clicked:

1. **Order Creation**:
   - Creates `Order` record with:
     - `payment_method`: "Cash on Delivery"
     - `payment_status`: "pending" (will be updated when payment is collected)
     - `status`: "pending" or "processing" (depending on approval requirements)
   - Creates `OrderItem` records for each product in the order
   - Stores cart item IDs in `admin_notes` for later cart clearing

2. **Email Notification**:
   - Sends order confirmation email using `OrderCreatedMail` (branded template)
   - Email includes: order number, order details, shipping address, order items, pricing breakdown

3. **Cart Clearing**:
   - **Only removes the cart items that were included in THIS order**
   - Does NOT clear other items in the cart
   - Cart item IDs are extracted from the order's `admin_notes` field

4. **Session Cleanup**:
   - Clears `checkout.shipping` and `checkout.payment` from session

5. **Redirect**:
   - Immediately redirects to `/checkout/summary/{order_number}`

### 5. **Summary Page** (`/checkout/summary`)
- Displays order confirmation page with:
  - Order number
  - Order status timeline
  - Order details (items, pricing, shipping address)
  - Estimated delivery date
  - Payment method: "Cash on Delivery"
  - Payment status: "Pending" (until cash is collected)
- Shows success message: "Order placed successfully!"
- Provides buttons:
  - "View Receipt"
  - "Continue Shopping"
  - "My Orders"

## Key Differences from Xendit Flow

| COD | Xendit |
|-----|--------|
| ✅ Cart cleared immediately | ⏳ Cart cleared after payment confirmation |
| ✅ Goes directly to summary page | ⏳ Goes to confirmation page → payment gateway → summary |
| ✅ No payment gateway interaction | ⏳ Requires Xendit payment gateway |
| ✅ Order created with `payment_status: pending` | ⏳ Order created with `payment_status: pending`, updated to `paid` by webhook |

## Payment Status Updates

- **Initial**: `payment_status = 'pending'` (order created)
- **After Delivery**: Admin manually updates `payment_status = 'paid'` when cash is collected
- **Or**: Automated update when order is marked as "delivered" (if configured)

## Cart Clearing Logic

**Important**: Only the cart items that were included in the order are removed. Other cart items remain in the cart.

Example:
- Cart contains: Product A, Product B, Product C
- Customer checks out: Product A only
- After COD order:
  - Product A: ❌ Removed (was in the order)
  - Product B: ✅ Still in cart
  - Product C: ✅ Still in cart

## Email Configuration

If emails are not sending, check:
1. Mail configuration in `.env`:
   ```env
   MAIL_MAILER=log  # For development (emails logged to storage/logs/laravel.log)
   # OR
   MAIL_MAILER=smtp  # For production
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-username
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@davidswood.shop
   MAIL_FROM_NAME="David's Wood Furniture"
   ```

2. Check logs: `storage/logs/laravel.log` for email errors

3. Clear config cache:
   ```bash
   php artisan config:clear
   ```

## Testing

To test the COD flow:
1. Add items to cart
2. Go to checkout
3. Select shipping address and method
4. Select "Cash on Delivery" (order must be ≤ ₱3,000)
5. Review and place order
6. Should immediately see summary page
7. Check email inbox for order confirmation (or check logs if using `MAIL_MAILER=log`)



