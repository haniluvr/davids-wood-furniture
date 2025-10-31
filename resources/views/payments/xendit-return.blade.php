<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment {{ $status === 'success' ? 'Successful' : ($status === 'processing' ? 'Processing' : 'Error') }} - David's Wood Furniture</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full text-center">
        @if($status === 'success')
            <!-- Payment Successful -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <i data-lucide="check-circle" class="h-12 w-12 text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
            <p class="text-lg text-gray-600 mb-6">
                Your payment has been processed successfully.
            </p>
            @if($order)
                <p class="text-sm text-gray-500 mb-6">
                    Order #{{ $order->order_number }}
                </p>
            @endif
            <p class="text-sm text-gray-500">
                You can close this window. Your order confirmation page will update automatically.
            </p>
            <script>
                lucide.createIcons();
                // Auto-close after 5 seconds
                setTimeout(function() {
                    window.close();
                }, 5000);
            </script>
        @elseif($status === 'processing')
            <!-- Payment Processing -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 mb-6">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Processing</h1>
            <p class="text-lg text-gray-600 mb-6">
                We're confirming your payment. Please wait...
            </p>
            @if($order)
                <p class="text-sm text-gray-500 mb-6">
                    Order #{{ $order->order_number }}
                </p>
            @endif
            <p class="text-sm text-gray-500">
                You can close this window. Your order confirmation page will update automatically once payment is confirmed.
            </p>
            <script>
                // Refresh page after 3 seconds to check status again
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            </script>
        @else
            <!-- Error -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
                <i data-lucide="x-circle" class="h-12 w-12 text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Error</h1>
            <p class="text-lg text-gray-600 mb-6">
                We couldn't process your payment. Please try again.
            </p>
            <p class="text-sm text-gray-500">
                You can close this window and check your order status.
            </p>
            <script>
                lucide.createIcons();
            </script>
        @endif
    </div>
</body>
</html>



