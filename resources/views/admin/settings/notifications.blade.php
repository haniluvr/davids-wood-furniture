@extends('admin.layouts.app')

@section('title', 'Notification Settings')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Notification Settings
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.settings.index') }}">Settings /</a>
            </li>
            <li class="font-medium text-primary">Notifications</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Email Notifications -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Email Notifications</h4>
            
            <div class="space-y-6">
                <!-- Order Notifications -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <h5 class="font-medium text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-primary"></i>
                        Order Notifications
                    </h5>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_new_order"
                                    value="1"
                                    {{ old('email_new_order', setting('email_new_order', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">New Order Received</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when a new order is placed</p>
                        </div>
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
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when order status changes</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_payment_received"
                                    value="1"
                                    {{ old('email_payment_received', setting('email_payment_received', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Payment Received</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when payment is confirmed</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_order_cancelled"
                                    value="1"
                                    {{ old('email_order_cancelled', setting('email_order_cancelled', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Order Cancelled</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when an order is cancelled</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Notifications -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <h5 class="font-medium text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                        Customer Notifications
                    </h5>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_new_customer"
                                    value="1"
                                    {{ old('email_new_customer', setting('email_new_customer', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">New Customer Registration</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when a new customer registers</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_customer_review"
                                    value="1"
                                    {{ old('email_customer_review', setting('email_customer_review', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">New Product Review</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when a customer leaves a review</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_contact_message"
                                    value="1"
                                    {{ old('email_contact_message', setting('email_contact_message', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Contact Form Message</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when contact form is submitted</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_wishlist_item"
                                    value="1"
                                    {{ old('email_wishlist_item', setting('email_wishlist_item', false)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Wishlist Activity</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when items are added to wishlist</p>
                        </div>
                    </div>
                </div>

                <!-- Inventory Notifications -->
                <div class="p-4 border border-stroke dark:border-strokedark rounded-lg">
                    <h5 class="font-medium text-black dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="warehouse" class="w-5 h-5 text-orange-600"></i>
                        Inventory Notifications
                    </h5>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_low_stock"
                                    value="1"
                                    {{ old('email_low_stock', setting('email_low_stock', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Low Stock Alert</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when inventory is running low</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_out_of_stock"
                                    value="1"
                                    {{ old('email_out_of_stock', setting('email_out_of_stock', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Out of Stock Alert</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when products are out of stock</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_stock_restocked"
                                    value="1"
                                    {{ old('email_stock_restocked', setting('email_stock_restocked', false)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Stock Restocked</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify when inventory is restocked</p>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="email_inventory_movement"
                                    value="1"
                                    {{ old('email_inventory_movement', setting('email_inventory_movement', false)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Inventory Movements</span>
                            </label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Notify of significant inventory changes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Notifications -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">SMS Notifications</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- SMS Provider -->
                <div>
                    <label for="sms_provider" class="mb-2.5 block text-black dark:text-white">
                        SMS Provider
                    </label>
                    <select
                        id="sms_provider"
                        name="sms_provider"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('sms_provider') border-red-500 @enderror"
                    >
                        <option value="twilio" {{ old('sms_provider', setting('sms_provider', 'twilio')) === 'twilio' ? 'selected' : '' }}>Twilio</option>
                        <option value="nexmo" {{ old('sms_provider', setting('sms_provider', 'twilio')) === 'nexmo' ? 'selected' : '' }}>Vonage (Nexmo)</option>
                        <option value="aws_sns" {{ old('sms_provider', setting('sms_provider', 'twilio')) === 'aws_sns' ? 'selected' : '' }}>AWS SNS</option>
                        <option value="none" {{ old('sms_provider', setting('sms_provider', 'twilio')) === 'none' ? 'selected' : '' }}>Disabled</option>
                    </select>
                    @error('sms_provider')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SMS Enabled -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="sms_enabled"
                            value="1"
                            {{ old('sms_enabled', setting('sms_enabled', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Enable SMS Notifications</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send SMS notifications for critical events</p>
                </div>

                <!-- SMS Events -->
                <div class="md:col-span-2">
                    <label class="mb-2.5 block text-black dark:text-white">SMS Notification Events</label>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="sms_urgent_orders"
                                    value="1"
                                    {{ old('sms_urgent_orders', setting('sms_urgent_orders', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Urgent Orders</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="sms_payment_failed"
                                    value="1"
                                    {{ old('sms_payment_failed', setting('sms_payment_failed', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Payment Failures</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="sms_system_alerts"
                                    value="1"
                                    {{ old('sms_system_alerts', setting('sms_system_alerts', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">System Alerts</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="sms_security_alerts"
                                    value="1"
                                    {{ old('sms_security_alerts', setting('sms_security_alerts', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Security Alerts</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Push Notifications -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Push Notifications</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Push Notifications Enabled -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="push_notifications_enabled"
                            value="1"
                            {{ old('push_notifications_enabled', setting('push_notifications_enabled', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Enable Push Notifications</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Send browser push notifications</p>
                </div>

                <!-- Notification Frequency -->
                <div>
                    <label for="notification_frequency" class="mb-2.5 block text-black dark:text-white">
                        Notification Frequency
                    </label>
                    <select
                        id="notification_frequency"
                        name="notification_frequency"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('notification_frequency') border-red-500 @enderror"
                    >
                        <option value="immediate" {{ old('notification_frequency', setting('notification_frequency', 'immediate')) === 'immediate' ? 'selected' : '' }}>Immediate</option>
                        <option value="hourly" {{ old('notification_frequency', setting('notification_frequency', 'immediate')) === 'hourly' ? 'selected' : '' }}>Hourly Digest</option>
                        <option value="daily" {{ old('notification_frequency', setting('notification_frequency', 'immediate')) === 'daily' ? 'selected' : '' }}>Daily Digest</option>
                        <option value="weekly" {{ old('notification_frequency', setting('notification_frequency', 'immediate')) === 'weekly' ? 'selected' : '' }}>Weekly Digest</option>
                    </select>
                    @error('notification_frequency')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quiet Hours -->
                <div>
                    <label for="quiet_hours_start" class="mb-2.5 block text-black dark:text-white">
                        Quiet Hours Start
                    </label>
                    <input
                        type="time"
                        id="quiet_hours_start"
                        name="quiet_hours_start"
                        value="{{ old('quiet_hours_start', setting('quiet_hours_start', '22:00')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('quiet_hours_start') border-red-500 @enderror"
                    />
                    @error('quiet_hours_start')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quiet_hours_end" class="mb-2.5 block text-black dark:text-white">
                        Quiet Hours End
                    </label>
                    <input
                        type="time"
                        id="quiet_hours_end"
                        name="quiet_hours_end"
                        value="{{ old('quiet_hours_end', setting('quiet_hours_end', '08:00')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('quiet_hours_end') border-red-500 @enderror"
                    />
                    @error('quiet_hours_end')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Notification Recipients -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Notification Recipients</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Admin Email -->
                <div>
                    <label for="admin_notification_email" class="mb-2.5 block text-black dark:text-white">
                        Admin Notification Email
                    </label>
                    <input
                        type="email"
                        id="admin_notification_email"
                        name="admin_notification_email"
                        value="{{ old('admin_notification_email', setting('admin_notification_email', 'admin@davidswood.com')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('admin_notification_email') border-red-500 @enderror"
                    />
                    @error('admin_notification_email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admin Phone -->
                <div>
                    <label for="admin_notification_phone" class="mb-2.5 block text-black dark:text-white">
                        Admin Notification Phone
                    </label>
                    <input
                        type="tel"
                        id="admin_notification_phone"
                        name="admin_notification_phone"
                        value="{{ old('admin_notification_phone', setting('admin_notification_phone', '+1 (555) 123-4567')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('admin_notification_phone') border-red-500 @enderror"
                    />
                    @error('admin_notification_phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Manager Email -->
                <div>
                    <label for="manager_notification_email" class="mb-2.5 block text-black dark:text-white">
                        Manager Notification Email
                    </label>
                    <input
                        type="email"
                        id="manager_notification_email"
                        name="manager_notification_email"
                        value="{{ old('manager_notification_email', setting('manager_notification_email', 'manager@davidswood.com')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('manager_notification_email') border-red-500 @enderror"
                    />
                    @error('manager_notification_email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Support Email -->
                <div>
                    <label for="support_notification_email" class="mb-2.5 block text-black dark:text-white">
                        Support Notification Email
                    </label>
                    <input
                        type="email"
                        id="support_notification_email"
                        name="support_notification_email"
                        value="{{ old('support_notification_email', setting('support_notification_email', 'support@davidswood.com')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('support_notification_email') border-red-500 @enderror"
                    />
                    @error('support_notification_email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
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
@endsection
