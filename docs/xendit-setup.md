# Xendit Setup Guide

This project is wired for Xendit configuration via the Admin UI and config/services.php. No direct API calls are implemented yet, so you can safely store credentials and run the app.

## 1) Create a Xendit account
- Sign up and create a business on Xendit.
- Choose an environment: Test (recommended) or Live when ready.

## 2) Get your API credentials
- From the Xendit Dashboard, copy:
  - Public Key
  - Secret Key
  - Callback/Verification Token (for webhooks)

## 3) Configure environment variables (optional)
You can store defaults in your environment so new environments come prefilled in the admin form.

```
XENDIT_PUBLIC_KEY=your_public_key
XENDIT_SECRET_KEY=your_secret_key
XENDIT_CALLBACK_TOKEN=your_callback_token
XENDIT_ENV=test
```

These are read by config/services.php under the xendit key and shown in the admin form as defaults.

## 4) Configure via Admin → Settings → Integrations → Xendit
- Go to Settings (cog in sidebar) → Integrations → Configure Xendit.
- Enter the keys and select Environment (test/live).
- Toggle Enabled if you want the store to regard Xendit as active.
- Save. Values are persisted in the settings table with xendit_* keys.

## 5) Webhooks (when enabling API logic)
- Expose a public URL for webhooks, e.g. https://admin.yourdomain.com/webhooks/xendit
- In Xendit Dashboard, add the webhook URL and use your Callback Token for verification.
- Recommended events: invoice.status, payment.succeeded, payment.failed, refund.succeeded.

## 6) Using in code (future)
- Read config from the settings first, then fall back to config('services.xendit.*').
- Example pattern:

```php
$public = setting('xendit_public_key', config('services.xendit.public_key'));
$secret = setting('xendit_secret_key', config('services.xendit.secret_key'));
$env = setting('xendit_environment', config('services.xendit.environment', 'test'));
```

- For API calls, use Xendit SDK or plain HTTP with the secret key as Basic Auth.

## 7) Test checklist
- Save keys via the admin form and confirm success alert.
- Switch environment Test/Live and ensure the value persists.
- Toggle Enabled and verify the Integrations list shows the badge.

## 8) Security notes
- Do not commit real keys to version control.
- Secret keys must never be exposed client-side.
- Limit who has manage-settings permission in Admin.

## 9) Troubleshooting
- If forms don’t save, check database connectivity and that the settings table exists.
- If values don’t appear, ensure config cache isn’t stale (php artisan config:clear).
- For webhook issues, verify server can receive public requests and tokens match.


