@extends('checkout.layout')

@section('title', 'Order Confirmation')

@php
    $currentStep = 4;
@endphp

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    @if(($payment_status ?? 'pending') === 'failed')
        <!-- Payment Failed -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i data-lucide="x-circle" class="h-8 w-8 text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Failed</h1>
            <p class="text-lg text-gray-600 mb-4">{{ $errorMessage ?? 'Your payment could not be processed. Please try again.' }}</p>
            <p class="text-sm text-gray-500">
                Common reasons for payment failure include:
            </p>
            <ul class="text-sm text-gray-500 mt-2 text-left max-w-md mx-auto">
                <li>• Card declined or insufficient funds</li>
                <li>• Invalid payment method or expired card</li>
                <li>• Payment timeout or network error</li>
                <li>• Invoice expired (payment links expire after the due date)</li>
            </ul>
        </div>

        <!-- Error Details -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Order #{{ $order->order_number }}</h2>
                <p class="text-gray-600">Total Amount: <span class="font-semibold">₱{{ number_format($order->total_amount, 2) }}</span></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('payments.xendit.pay', ['order' => $order->id]) }}" 
                   class="bg-[#8b7355] text-white px-6 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold text-center">
                    Retry Payment
                </a>
                <a href="{{ route('checkout.payment') }}" 
                   class="border border-[#8b7355] text-[#8b7355] px-6 py-3 rounded-lg hover:bg-[#8b7355] hover:text-white transition-colors font-semibold text-center">
                    Change Payment Method
                </a>
                <a href="{{ route('account.orders') }}" 
                   class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center">
                    View My Orders
                </a>
            </div>
        </div>
    @elseif(($payment_status ?? 'pending') === 'paid')
        <!-- Payment Successful -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i data-lucide="check-circle" class="h-8 w-8 text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
            <p class="text-lg text-gray-600">{{ $successMessage ?? 'Your payment has been processed successfully.' }}</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Order #{{ $order->order_number }}</h2>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Total Amount</p>
                    <p class="text-lg font-bold text-[#8b7355]">₱{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-4">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    Your order has been confirmed and is being processed.
                </p>
                <div class="flex justify-end">
                    <a href="{{ route('checkout.summary', ['order' => $order->order_number]) }}" 
                       class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                        Read Summary
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Loading / Redirecting State -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full mb-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#8b7355]"></div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Redirecting to Payment Gateway</h1>
            <p class="text-lg text-gray-600">Please wait while we redirect you to complete your payment...</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Order #{{ $order->order_number }}</h2>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Total Amount</p>
                    <p class="text-lg font-bold text-[#8b7355]">₱{{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-4">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    You will be redirected to Xendit's secure payment page to complete your payment.
                </p>
                <p class="text-sm text-gray-600 mb-4">
                    Click the button below to open the payment gateway in a new window.
                </p>
                <div id="popup-blocked-message" style="display: none;" class="mb-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                    <p class="text-sm text-orange-800 mb-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>
                        <strong>Pop-up blocked:</strong> Please allow pop-ups for this site or click the button below.
                    </p>
                    <p class="text-xs text-orange-700">
                        To allow pop-ups: Click the pop-up icon in your browser's address bar → Enable "Pop-ups and redirects" → Refresh this page.
                    </p>
                </div>
                <div class="flex justify-end">
                    <button 
                       type="button"
                       id="manual-payment-link"
                       onclick="openPaymentGatewayManual('{{ route('payments.xendit.pay', ['order' => $order->id]) }}', 'XenditPayment_{{ $order->order_number }}');"
                       class="bg-[#8b7355] text-white px-8 py-3 rounded-lg hover:bg-[#6b5b47] transition-colors font-semibold">
                        Open Payment Gateway
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Support -->
    <div class="bg-blue-50 rounded-lg p-6 text-center">
        <p class="text-sm text-blue-800">
            <i data-lucide="help-circle" class="w-4 h-4 inline mr-1"></i>
            Need help? <a href="tel:+1234567890" class="font-medium underline">Contact our support team</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // DISABLED: Auto-redirect to Xendit payment gateway
    // User must manually click the "Open Payment Gateway" button
           @if(false && $order->payment_method !== 'Cash on Delivery' && ($payment_status ?? 'pending') === 'pending' && !($isReturnFromPayment ?? false))
               // CRITICAL: Wait for DOM to be fully loaded before setting up auto-open
               // This prevents the page from opening the gateway immediately on load
               (function() {
                   // Wait a bit to ensure page is fully loaded
                   setTimeout(function() {
                       // STRICT: Check if window already exists and is open
                       const windowName = 'XenditPayment_{{ $order->order_number }}';
                       let paymentWindow = null;
                       
                       // Check if a window with this name already exists
                       try {
                           paymentWindow = window.open('', windowName);
                           if (paymentWindow && !paymentWindow.closed && paymentWindow.location.href !== 'about:blank') {
                               console.log('Payment gateway window already exists and is open, skipping auto-open');
                               paymentWindow.focus();
                               const countdownParent = document.getElementById('redirect-countdown');
                               if (countdownParent) {
                                   countdownParent.innerHTML = '<span class="text-green-600 font-medium">Payment gateway is already open. Please complete payment there. This page will automatically update when payment is successful.</span>';
                               }
                               const manualLink = document.getElementById('manual-payment-link');
                               if (manualLink) {
                                   manualLink.style.display = 'none';
                               }
                               return;
                           }
                       } catch (e) {
                           console.log('Could not check for existing window (cross-origin):', e);
                       }
                       
                       // Check sessionStorage as additional safeguard
                       const alreadyOpened = sessionStorage.getItem('paymentGatewayOpened_{{ $order->order_number }}');
                       if (alreadyOpened === 'true') {
                           console.log('Payment gateway already opened for this order (sessionStorage), skipping auto-open');
                           const countdownParent = document.getElementById('redirect-countdown');
                           if (countdownParent) {
                               countdownParent.innerHTML = '<span class="text-green-600 font-medium">Payment gateway is already open. Please complete payment there. This page will automatically update when payment is successful.</span>';
                           }
                           const manualLink = document.getElementById('manual-payment-link');
                           if (manualLink) {
                               manualLink.style.display = 'none';
                           }
                           return;
                       }
                       
                       console.log('Setting up auto-redirect to Xendit payment gateway (will open in 5 seconds as WINDOW)');
                       const paymentUrl = '{{ route('payments.xendit.pay', ['order' => $order->id]) }}';
                       const countdownElement = document.getElementById('countdown');
                       const countdownParent = countdownElement ? countdownElement.parentElement : null;
                       const manualLink = document.getElementById('manual-payment-link');
                       let countdown = 5;
                       let opened = false;
                       
                       console.log('Payment URL:', paymentUrl);
                       console.log('Window name:', windowName);
                       console.log('Will open as WINDOW (popup) after countdown completes');
                       
                       // Store reference for window close detection
                       window.paymentWindowRef = null;
                       window.paymentWindowCheckInterval = null;
            
            // Also listen for window focus/blur events - when user returns to this tab after payment, start checking
            let wasBlurred = false;
            window.addEventListener('blur', function() {
                wasBlurred = true;
                console.log('Window blurred (user likely switched tabs/windows)');
            });
            
            window.addEventListener('focus', function() {
                if (wasBlurred && window.paymentWindowRef && !window.paymentPollingActive) {
                    console.log('Window regained focus, checking if payment window is still open');
                    wasBlurred = false;
                    try {
                        if (window.paymentWindowRef.closed) {
                            console.log('Payment window is closed (detected on focus), starting polling');
                            startPaymentStatusPolling();
                        } else {
                            // Window might still be open, but start polling anyway after a short delay
                            // (user might have completed payment and window auto-closed)
                            setTimeout(function() {
                                if (!window.paymentPollingActive) {
                                    console.log('Starting polling after focus (payment window may have closed)');
                                    startPaymentStatusPolling();
                                }
                            }, 2000);
                        }
                    } catch (e) {
                        // Cross-origin - assume closed and start polling
                        console.log('Payment window likely closed (cross-origin on focus), starting polling');
                        startPaymentStatusPolling();
                    }
                }
            });
            
            function openPaymentGateway() {
                // Double-check: if already opened, return immediately
                if (opened || sessionStorage.getItem('paymentGatewayOpened_{{ $order->order_number }}') === 'true') {
                    console.log('Payment gateway already opened, skipping');
                    return;
                }
                
                // Mark as opened IMMEDIATELY to prevent multiple opens
                opened = true;
                sessionStorage.setItem('paymentGatewayOpened_{{ $order->order_number }}', 'true');
                
                console.log('Opening payment gateway:', paymentUrl);
                
                try {
                    // Force window opening (not tab) by specifying window features
                    // This ensures it opens as a popup window, not a new tab
                    // The key is specifying width, height, and position - this forces a popup window
                    const windowFeatures = [
                        'width=1200',
                        'height=800',
                        'left=' + Math.round((screen.width - 1200) / 2),
                        'top=' + Math.round((screen.height - 800) / 2),
                        'resizable=yes',
                        'scrollbars=yes',
                        'status=yes',
                        'toolbar=no',
                        'menubar=no',
                        'location=yes',
                        'noopener=yes',
                        'noreferrer=yes'
                    ].join(',');
                    
                    console.log('Opening payment gateway as WINDOW (popup) with features:', windowFeatures);
                    // Use named window to ensure only one window opens (will reuse if exists)
                    paymentWindow = window.open(paymentUrl, windowName, windowFeatures);
                    window.paymentWindowRef = paymentWindow;
                    
                    // Check if window opened (not blocked) - use setTimeout to allow window to initialize
                    setTimeout(function() {
                        // More thorough check for popup blocker
                        let isBlocked = false;
                        let isOpened = false;
                        
                        // Check if window exists and is not closed
                        if (paymentWindow) {
                            try {
                                // Check if window is closed
                                if (paymentWindow.closed) {
                                    isBlocked = true;
                                } else {
                                    // Try to access window location to verify it opened
                                    try {
                                        // For same-origin, we can check location
                                        if (paymentWindow.location.href === 'about:blank' || !paymentWindow.location.href) {
                                            // Window opened but hasn't navigated - might still be loading
                                            setTimeout(function() {
                                                // Check again after a delay
                                                try {
                                                    if (paymentWindow.closed) {
                                                        isBlocked = true;
                                                    } else if (paymentWindow.location.href && paymentWindow.location.href !== 'about:blank') {
                                                        isOpened = true;
                                                    }
                                                } catch (e) {
                                                    // Cross-origin - assume opened
                                                    isOpened = true;
                                                }
                                            }, 1000);
                                        } else {
                                            isOpened = true;
                                        }
                                    } catch (e) {
                                        // Cross-origin - can't check location directly, but window exists
                                        // Assume it opened if window is not closed
                                        isOpened = true;
                                    }
                                }
                            } catch (e) {
                                // Error accessing window - might be blocked or closed
                                console.log('Error checking window status:', e);
                                isBlocked = true;
                            }
                        } else {
                            // No window reference - definitely blocked
                            isBlocked = true;
                        }
                        
                        if (isOpened || (!isBlocked && paymentWindow && !paymentWindow.closed)) {
                            console.log('Payment gateway opened successfully');
                            
                            // Update UI
                            if (countdownParent) {
                                countdownParent.innerHTML = '<span class="text-green-600 font-medium">Payment gateway opened. Please complete payment there. This page will automatically update when payment is successful.</span>';
                            }
                            if (manualLink) {
                                manualLink.style.display = 'none';
                            }
                            const popupBlockedMsg = document.getElementById('popup-blocked-message');
                            if (popupBlockedMsg) {
                                popupBlockedMsg.style.display = 'none';
                            }
                            
                            // Start checking if window closes - when it does, start polling immediately
                            window.paymentWindowCheckInterval = setInterval(function() {
                                try {
                                    if (!paymentWindow || paymentWindow.closed) {
                                        console.log('Payment gateway window/tab closed, starting immediate polling');
                                        if (window.paymentWindowCheckInterval) {
                                            clearInterval(window.paymentWindowCheckInterval);
                                            window.paymentWindowCheckInterval = null;
                                        }
                                        window.paymentWindowRef = null;
                                        
                                        // Start polling immediately when window closes
                                        startPaymentStatusPolling();
                                        return;
                                    }
                                    
                                    // Try to access window properties to detect if still valid
                                    try {
                                        const test = paymentWindow.location;
                                    } catch (err) {
                                        // Cross-origin error - window might be closed or navigated
                                        // But also might just be cross-origin, so check with a timeout
                                        setTimeout(function() {
                                            try {
                                                if (!paymentWindow || paymentWindow.closed) {
                                                    console.log('Payment gateway window closed (delayed cross-origin check), starting polling');
                                                    if (window.paymentWindowCheckInterval) {
                                                        clearInterval(window.paymentWindowCheckInterval);
                                                        window.paymentWindowCheckInterval = null;
                                                    }
                                                    window.paymentWindowRef = null;
                                                    startPaymentStatusPolling();
                                                }
                                            } catch (e) {
                                                // Still can't check, but don't assume closed yet
                                            }
                                        }, 2000);
                                    }
                                } catch (e) {
                                    // Any error - be conservative, don't assume closed immediately
                                    console.log('Payment gateway window check error:', e.message);
                                }
                            }, 500); // Check every 500ms for faster detection
                            
                            // Also start polling after 3 seconds as backup (in case window close detection fails)
                            setTimeout(function() {
                                if (!window.paymentPollingActive) {
                                    console.log('Starting backup polling (window may be open but close detection not working)');
                                    startPaymentStatusPolling();
                                }
                            }, 3000);
                            
                            // Focus the new window
                            try {
                                if (paymentWindow && !paymentWindow.closed) {
                                    paymentWindow.focus();
                                }
                            } catch (e) {
                                console.log('Could not focus window:', e);
                            }
                            return;
                        }
                        
                        // If we get here, popup was blocked
                        console.log('Popup appears blocked or failed to open');
                        if (countdownParent) {
                            countdownParent.innerHTML = '<span class="text-orange-600 font-medium">Popup blocked. Please allow pop-ups or click the button below.</span>';
                        }
                        if (manualLink) {
                            manualLink.style.display = 'block';
                        }
                        const popupBlockedMsg = document.getElementById('popup-blocked-message');
                        if (popupBlockedMsg) {
                            popupBlockedMsg.style.display = 'block';
                        }
                        // Remove the sessionStorage flag if blocked so user can try again
                        sessionStorage.removeItem('paymentGatewayOpened_{{ $order->order_number }}');
                        opened = false;
                    }, 800); // Slightly longer delay to ensure window initializes
                } catch (e) {
                    console.error('Error opening payment gateway:', e);
                    if (countdownParent) {
                        countdownParent.innerHTML = '<span class="text-orange-600 font-medium">Error opening gateway. Please click the button below.</span>';
                    }
                    if (manualLink) {
                        manualLink.style.display = 'block';
                    }
                    // Remove the sessionStorage flag on error
                    sessionStorage.removeItem('paymentGatewayOpened_{{ $order->order_number }}');
                    opened = false;
                }
            }
            
            // Function to start payment status polling (can be called immediately when window closes)
            // Make it globally accessible
            window.startPaymentStatusPolling = function() {
                // Don't start if already polling
                if (window.paymentPollingActive) {
                    console.log('Payment polling already active');
                    return;
                }
                window.paymentPollingActive = true;
                
                console.log('Starting payment status polling immediately');
                let pollCount = 0;
                const maxPolls = 120; // Poll for 4 minutes max (120 polls at 2 seconds each)
                
                // Do first check immediately, then continue with interval
                function checkPaymentStatus() {
                    pollCount++;
                    console.log('Polling payment status, attempt:', pollCount);
                    
                    if (pollCount >= maxPolls) {
                        if (window.paymentPollInterval) {
                            clearInterval(window.paymentPollInterval);
                            window.paymentPollInterval = null;
                        }
                        window.paymentPollingActive = false;
                        console.log('Max polls reached, stopping');
                        return;
                    }
                    
                    // Use poll parameter to get JSON status without triggering gateway opening
                    fetch('{{ route('checkout.confirmation', ['order' => $order->order_number]) }}?poll=1', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        method: 'GET',
                        credentials: 'same-origin',
                        cache: 'no-cache'
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        if (!data || !data.payment_status) {
                            if (pollCount <= 3) { // Only log first few attempts to avoid spam
                                console.log('No payment status in response:', data);
                            }
                            return;
                        }
                        
                        console.log('Current payment status:', data.payment_status, '- Poll #' + pollCount);
                        
                        // Handle JSON response
                        if (data.payment_status === 'paid') {
                            console.log('✅✅✅ PAYMENT SUCCESS DETECTED! Reloading page immediately...');
                            if (window.paymentPollInterval) {
                                clearInterval(window.paymentPollInterval);
                                window.paymentPollInterval = null;
                            }
                            window.paymentPollingActive = false;
                            // Clear any window check intervals
                            if (window.paymentWindowCheckInterval) {
                                clearInterval(window.paymentWindowCheckInterval);
                                window.paymentWindowCheckInterval = null;
                            }
                            // Reload immediately
                            window.location.reload();
                        } else if (data.payment_status === 'failed') {
                            console.log('❌ Payment status changed to failed, reloading page');
                            if (window.paymentPollInterval) {
                                clearInterval(window.paymentPollInterval);
                                window.paymentPollInterval = null;
                            }
                            window.paymentPollingActive = false;
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Payment status check failed:', error);
                    });
                }
                
                // Check immediately on first call (no delay)
                checkPaymentStatus();
                
                // Then continue checking every 1 second for faster detection
                window.paymentPollInterval = setInterval(checkPaymentStatus, 1000);
            };
            
            // Update countdown every second
            const countdownInterval = setInterval(function() {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    openPaymentGateway();
                }
            }, 1000);
            
            // DO NOT add a fallback setTimeout - it causes multiple opens
            // The countdown interval already handles the opening
                   }, 100); // Small delay to ensure DOM is ready before starting countdown
               })();
           @endif
    
    // Continuous polling for payment status (always active when payment is pending)
    // This will detect payment success even if user doesn't return to this page
    // Start polling after 15 seconds (give webhook time to process after payment)
    // Note: If payment window closes, polling starts immediately via startPaymentStatusPolling()
    @if($order->payment_method !== 'Cash on Delivery' && ($payment_status ?? 'pending') === 'pending')
        (function() {
            // Only start background polling if not already started by window close detection
            if (window.paymentPollingActive) {
                console.log('Payment polling already active (started by window close detection)');
                return;
            }
            
            console.log('Setting up background payment status polling');
            let pollCount = 0;
            const maxPolls = 60; // Poll for 2 minutes max (60 polls at 2 seconds each)
            let pollInterval = null;
            
            // Start polling after 10 seconds as fallback (if window close detection didn't trigger)
            setTimeout(function() {
                // Don't start if already polling from window close
                if (window.paymentPollingActive) {
                    console.log('Payment polling already active (started by window close), skipping background polling');
                    return;
                }
                
                console.log('Starting background payment status polling (fallback)');
                window.paymentPollingActive = true;
                let pollCount = 0;
                const maxPolls = 90; // Poll for 3 minutes max
                
                function checkPaymentStatus() {
                    pollCount++;
                    console.log('Background polling payment status, attempt:', pollCount);
                    
                    if (pollCount >= maxPolls) {
                        if (pollInterval) {
                            clearInterval(pollInterval);
                        }
                        window.paymentPollingActive = false;
                        console.log('Max polls reached, stopping');
                        return;
                    }
                    
                    // Use poll parameter to get JSON status without triggering gateway opening
                    fetch('{{ route('checkout.confirmation', ['order' => $order->order_number]) }}?poll=1', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        method: 'GET',
                        credentials: 'same-origin',
                        cache: 'no-cache'
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        if (!data || !data.payment_status) return;
                        
                        console.log('Background check - Current payment status:', data.payment_status);
                        
                        // Handle JSON response
                        if (data.payment_status === 'paid') {
                            console.log('✅ Background polling: Payment status changed to paid, reloading page');
                            if (pollInterval) {
                                clearInterval(pollInterval);
                            }
                            window.paymentPollingActive = false;
                            window.location.reload();
                        } else if (data.payment_status === 'failed') {
                            console.log('❌ Background polling: Payment status changed to failed, reloading page');
                            if (pollInterval) {
                                clearInterval(pollInterval);
                            }
                            window.paymentPollingActive = false;
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Payment status check failed:', error);
                    });
                }
                
                // Check immediately
                checkPaymentStatus();
                
                // Then continue every 1 second for faster detection
                pollInterval = setInterval(checkPaymentStatus, 1000);
            }, 5000); // Start polling 5 seconds after page load (faster fallback)
        })();
    @endif
    
    // Function for manual button click - also opens as window, not tab
    window.openPaymentGatewayManual = function(url, windowName) {
        console.log('Manual payment gateway open requested');
        try {
            // Force window opening (not tab) with explicit window features
            const windowFeatures = [
                'width=1200',
                'height=800',
                'left=' + Math.round((screen.width - 1200) / 2),
                'top=' + Math.round((screen.height - 800) / 2),
                'resizable=yes',
                'scrollbars=yes',
                'status=yes',
                'toolbar=no',
                'menubar=no',
                'location=yes',
                'noopener=yes',
                'noreferrer=yes'
            ].join(',');
            
            console.log('Opening payment gateway manually as WINDOW (popup) with features:', windowFeatures);
            const manualWindow = window.open(url, windowName, windowFeatures);
            
            if (manualWindow) {
                window.paymentWindowRef = manualWindow;
                
                // Hide the button
                const manualLink = document.getElementById('manual-payment-link');
                if (manualLink) {
                    manualLink.style.display = 'none';
                }
                
                // Update message
                const countdownParent = document.getElementById('redirect-countdown');
                if (countdownParent) {
                    countdownParent.innerHTML = '<span class="text-green-600 font-medium">Payment gateway opened. Please complete payment there. This page will automatically update when payment is successful.</span>';
                }
                
                const popupBlockedMsg = document.getElementById('popup-blocked-message');
                if (popupBlockedMsg) {
                    popupBlockedMsg.style.display = 'none';
                }
                
                // Start window close detection
                window.paymentWindowCheckInterval = setInterval(function() {
                    try {
                        if (!manualWindow || manualWindow.closed) {
                            console.log('Manual payment gateway window closed, starting polling');
                            if (window.paymentWindowCheckInterval) {
                                clearInterval(window.paymentWindowCheckInterval);
                                window.paymentWindowCheckInterval = null;
                            }
                            window.paymentWindowRef = null;
                            if (window.startPaymentStatusPolling) {
                                window.startPaymentStatusPolling();
                            }
                        }
                    } catch (e) {
                        console.log('Window check error:', e);
                    }
                }, 500);
                
                // Always start backup polling after 3 seconds
                setTimeout(function() {
                    if (!window.paymentPollingActive && window.startPaymentStatusPolling) {
                        console.log('Starting backup polling for manual open');
                        window.startPaymentStatusPolling();
                    }
                }, 3000);
            } else {
                alert('Please allow pop-ups for this site to open the payment gateway.');
            }
        } catch (e) {
            console.error('Error opening payment gateway manually:', e);
            alert('Could not open payment gateway. Please check your browser pop-up settings.');
        }
    };
});
</script>
@endpush
@endsection
