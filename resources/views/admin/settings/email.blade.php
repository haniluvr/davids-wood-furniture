@extends('admin.layouts.app')

@section('title', 'Email Settings')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Email Settings
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.settings.index') }}">Settings /</a>
            </li>
            <li class="font-medium text-primary">Email</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.settings.email.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- SMTP Configuration -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">SMTP Configuration</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Mail Driver -->
                <div>
                    <label for="mail_driver" class="mb-2.5 block text-black dark:text-white">
                        Mail Driver
                    </label>
                    <select
                        id="mail_driver"
                        name="mail_driver"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_driver') border-red-500 @enderror"
                    >
                        <option value="smtp" {{ old('mail_driver', setting('mail_driver', 'smtp')) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="mailgun" {{ old('mail_driver', setting('mail_driver', 'smtp')) === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                        <option value="ses" {{ old('mail_driver', setting('mail_driver', 'smtp')) === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                        <option value="postmark" {{ old('mail_driver', setting('mail_driver', 'smtp')) === 'postmark' ? 'selected' : '' }}>Postmark</option>
                    </select>
                    @error('mail_driver')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mail Host -->
                <div>
                    <label for="mail_host" class="mb-2.5 block text-black dark:text-white">
                        SMTP Host
                    </label>
                    <input
                        type="text"
                        id="mail_host"
                        name="mail_host"
                        value="{{ old('mail_host', setting('mail_host', 'smtp.gmail.com')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_host') border-red-500 @enderror"
                        placeholder="smtp.gmail.com"
                    />
                    @error('mail_host')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mail Port -->
                <div>
                    <label for="mail_port" class="mb-2.5 block text-black dark:text-white">
                        SMTP Port
                    </label>
                    <input
                        type="number"
                        id="mail_port"
                        name="mail_port"
                        value="{{ old('mail_port', setting('mail_port', '587')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_port') border-red-500 @enderror"
                        placeholder="587"
                    />
                    @error('mail_port')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mail Encryption -->
                <div>
                    <label for="mail_encryption" class="mb-2.5 block text-black dark:text-white">
                        Encryption
                    </label>
                    <select
                        id="mail_encryption"
                        name="mail_encryption"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_encryption') border-red-500 @enderror"
                    >
                        <option value="tls" {{ old('mail_encryption', setting('mail_encryption', 'tls')) === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ old('mail_encryption', setting('mail_encryption', 'tls')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ old('mail_encryption', setting('mail_encryption', 'tls')) === '' ? 'selected' : '' }}>None</option>
                    </select>
                    @error('mail_encryption')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mail Username -->
                <div>
                    <label for="mail_username" class="mb-2.5 block text-black dark:text-white">
                        SMTP Username
                    </label>
                    <input
                        type="text"
                        id="mail_username"
                        name="mail_username"
                        value="{{ old('mail_username', setting('mail_username', '')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_username') border-red-500 @enderror"
                        placeholder="your-email@gmail.com"
                    />
                    @error('mail_username')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mail Password -->
                <div>
                    <label for="mail_password" class="mb-2.5 block text-black dark:text-white">
                        SMTP Password
                    </label>
                    <input
                        type="password"
                        id="mail_password"
                        name="mail_password"
                        value="{{ old('mail_password', setting('mail_password', '')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_password') border-red-500 @enderror"
                        placeholder="Your email password or app password"
                    />
                    @error('mail_password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Email Templates -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Email Templates</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- From Name -->
                <div>
                    <label for="mail_from_name" class="mb-2.5 block text-black dark:text-white">
                        From Name
                    </label>
                    <input
                        type="text"
                        id="mail_from_name"
                        name="mail_from_name"
                        value="{{ old('mail_from_name', setting('mail_from_name', 'David\'s Wood Furniture')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_from_name') border-red-500 @enderror"
                    />
                    @error('mail_from_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- From Address -->
                <div>
                    <label for="mail_from_address" class="mb-2.5 block text-black dark:text-white">
                        From Address
                    </label>
                    <input
                        type="email"
                        id="mail_from_address"
                        name="mail_from_address"
                        value="{{ old('mail_from_address', setting('mail_from_address', 'noreply@davidswood.com')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_from_address') border-red-500 @enderror"
                    />
                    @error('mail_from_address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reply To Name -->
                <div>
                    <label for="mail_reply_to_name" class="mb-2.5 block text-black dark:text-white">
                        Reply To Name
                    </label>
                    <input
                        type="text"
                        id="mail_reply_to_name"
                        name="mail_reply_to_name"
                        value="{{ old('mail_reply_to_name', setting('mail_reply_to_name', 'Customer Support')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_reply_to_name') border-red-500 @enderror"
                    />
                    @error('mail_reply_to_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reply To Address -->
                <div>
                    <label for="mail_reply_to_address" class="mb-2.5 block text-black dark:text-white">
                        Reply To Address
                    </label>
                    <input
                        type="email"
                        id="mail_reply_to_address"
                        name="mail_reply_to_address"
                        value="{{ old('mail_reply_to_address', setting('mail_reply_to_address', 'support@davidswood.com')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('mail_reply_to_address') border-red-500 @enderror"
                    />
                    @error('mail_reply_to_address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Email Notifications -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Email Notifications</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Order Confirmation -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="email_order_confirmation"
                            value="1"
                            {{ old('email_order_confirmation', setting('email_order_confirmation', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Order Confirmation Emails</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send confirmation emails when orders are placed</p>
                </div>

                <!-- Order Status Updates -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="email_order_status"
                            value="1"
                            {{ old('email_order_status', setting('email_order_status', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Order Status Updates</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send emails when order status changes</p>
                </div>

                <!-- Shipping Notifications -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="email_shipping"
                            value="1"
                            {{ old('email_shipping', setting('email_shipping', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Shipping Notifications</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send shipping and tracking information</p>
                </div>

                <!-- Low Stock Alerts -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="email_low_stock"
                            value="1"
                            {{ old('email_low_stock', setting('email_low_stock', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Low Stock Alerts</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send alerts when inventory is low</p>
                </div>

                <!-- New Customer Welcome -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="email_welcome"
                            value="1"
                            {{ old('email_welcome', setting('email_welcome', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Welcome Emails</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send welcome emails to new customers</p>
                </div>

                <!-- Password Reset -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="email_password_reset"
                            value="1"
                            {{ old('email_password_reset', setting('email_password_reset', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Password Reset Emails</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send password reset links via email</p>
                </div>
            </div>
        </div>

        <!-- Test Email -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Test Email Configuration</h4>
            
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="test_email" class="mb-2.5 block text-black dark:text-white">
                        Test Email Address
                    </label>
                    <input
                        type="email"
                        id="test_email"
                        name="test_email"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                        placeholder="test@example.com"
                    />
                </div>
                <button type="button" id="test-email-btn" class="flex items-center gap-2 rounded-lg border border-blue-500 bg-blue-500 px-6 py-3 text-white hover:bg-blue-600 transition-colors duration-200">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Send Test Email
                </button>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <button type="button" class="flex items-center gap-2 rounded-lg border border-gray-500 bg-gray-500 px-6 py-3 text-white hover:bg-gray-600 transition-colors duration-200">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset to Defaults
            </button>
            <button type="submit" class="flex items-center gap-2 rounded-lg border border-primary bg-primary px-6 py-3 text-white hover:bg-primary/90 transition-colors duration-200">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const testEmailBtn = document.getElementById('test-email-btn');
    const testEmailInput = document.getElementById('test_email');

    testEmailBtn.addEventListener('click', function() {
        const email = testEmailInput.value;
        
        if (!email) {
            alert('Please enter a test email address');
            return;
        }

        // Disable button and show loading
        testEmailBtn.disabled = true;
        testEmailBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Sending...';

        // Send test email request
        fetch('{{ route("admin.settings.email.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Test email sent successfully!');
            } else {
                alert('Failed to send test email: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error sending test email: ' + error.message);
        })
        .finally(() => {
            // Re-enable button
            testEmailBtn.disabled = false;
            testEmailBtn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> Send Test Email';
        });
    });
});
</script>
@endsection
