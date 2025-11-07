@extends('admin.layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="settings" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Account Settings</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Manage your account security and preferences</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Password Change Section -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl">
                        <i data-lucide="lock" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Change Password</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update your account password</p>
            </div>
            <div class="p-8">
                <form action="{{ admin_route('profile.settings.update') }}" method="POST" id="password-form">
                    @csrf
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="current_password" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                placeholder="Enter your current password"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('current_password') border-red-300 @enderror"
                            />
                            @error('current_password')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="new_password" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    New Password <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    id="new_password"
                                    name="new_password"
                                    placeholder="Enter new password (min 8 characters)"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('new_password') border-red-300 @enderror"
                                />
                                @error('new_password')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="new_password_confirmation" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                    Confirm New Password <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    id="new_password_confirmation"
                                    name="new_password_confirmation"
                                    placeholder="Confirm new password"
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('new_password_confirmation') border-red-300 @enderror"
                                />
                                @error('new_password_confirmation')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Email Change Section -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-green-50 to-blue-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl">
                        <i data-lucide="mail" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Change Login Email</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Update your login email address</p>
            </div>
            <div class="p-8">
                <form action="{{ admin_route('profile.settings.update') }}" method="POST" id="email-form">
                    @csrf
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="current_email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Current Email
                            </label>
                            <input
                                type="email"
                                id="current_email"
                                value="{{ $admin->email }}"
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600 dark:border-strokedark dark:bg-gray-800 dark:text-gray-400 cursor-not-allowed"
                                disabled
                                readonly
                            />
                        </div>

                        <div class="space-y-2">
                            <label for="new_email" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                New Email Address <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                id="new_email"
                                name="new_email"
                                value="{{ old('new_email') }}"
                                placeholder="Enter new email address"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('new_email') border-red-300 @enderror"
                            />
                            @error('new_email')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email_current_password" class="block text-sm font-medium text-stone-700 dark:text-stone-300">
                                Current Password <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="password"
                                id="email_current_password"
                                name="email_current_password"
                                placeholder="Enter your current password to confirm"
                                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-500 focus:border-primary focus:outline-none dark:border-strokedark dark:bg-boxdark dark:text-white dark:placeholder-stone-400 @error('email_current_password') border-red-300 @enderror"
                            />
                            @error('email_current_password')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-stone-500 dark:text-gray-400">Enter your current password to verify this change</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-green-700 hover:to-blue-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Update Email
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Two-Factor Authentication Section -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl">
                        <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Two-Factor Authentication</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Security information about your account</p>
            </div>
            <div class="p-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5"></i>
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                Two-Factor Authentication is Required
                            </p>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Two-factor authentication (2FA) is mandatory for all admin accounts and cannot be disabled. This adds an extra layer of security to protect your account. When you log in, you will receive a verification code at your personal email address.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield-check" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                            <div>
                                <p class="text-sm font-medium text-stone-900 dark:text-white">2FA Status</p>
                                <p class="text-xs text-stone-600 dark:text-gray-400">Two-factor authentication is {{ $admin->two_factor_enabled ? 'enabled' : 'required' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            {{ $admin->two_factor_enabled ? 'Enabled' : 'Required' }}
                        </span>
                    </div>

                    @if($admin->personal_email)
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="mail" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">Verification Email</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">{{ $admin->personal_email }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 dark:text-yellow-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-yellow-900 dark:text-yellow-100">No Personal Email Configured</p>
                                    <p class="text-xs text-yellow-700 dark:text-yellow-300">Please set your personal email in your profile to receive 2FA codes</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notification Preferences Section -->
        <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
            <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl">
                        <i data-lucide="bell" class="w-5 h-5 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Notification Preferences</h3>
                </div>
                <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Choose which notifications you want to receive</p>
            </div>
            <div class="p-8" x-data="{
                preferences: @js($admin->notification_preferences ?? \App\Models\Admin::getDefaultNotificationPreferences()),
                toggle(key) {
                    this.preferences[key] = !this.preferences[key];
                }
            }">
                <form action="{{ admin_route('profile.settings.update-notifications') }}" method="POST" id="notification-preferences-form">
                    @csrf
                    <div class="space-y-4">
                        <!-- New Orders -->
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-green-600 dark:text-green-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">New Orders</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">Get notified when new orders are placed</p>
                                </div>
                            </div>
                            <button type="button" @click="toggle('new_orders')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :class="preferences.new_orders ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="preferences.new_orders ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                            <input type="hidden" name="new_orders" :value="preferences.new_orders ? '1' : '0'">
                        </div>

                        <!-- Order Status Updates -->
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="truck" class="w-5 h-5 text-blue-600 dark:text-blue-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">Order Status Updates</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">Get notified when order status changes</p>
                                </div>
                            </div>
                            <button type="button" @click="toggle('order_status_updates')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :class="preferences.order_status_updates ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="preferences.order_status_updates ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                            <input type="hidden" name="order_status_updates" :value="preferences.order_status_updates ? '1' : '0'">
                        </div>

                        <!-- Customer Messages -->
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="message-circle" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">Customer Messages</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">Get notified when customers send messages</p>
                                </div>
                            </div>
                            <button type="button" @click="toggle('customer_messages')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :class="preferences.customer_messages ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="preferences.customer_messages ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                            <input type="hidden" name="customer_messages" :value="preferences.customer_messages ? '1' : '0'">
                        </div>

                        <!-- Low Stock Alerts -->
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 dark:text-yellow-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">Low Stock Alerts</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">Get notified when products are running low on stock</p>
                                </div>
                            </div>
                            <button type="button" @click="toggle('low_stock')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :class="preferences.low_stock ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="preferences.low_stock ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                            <input type="hidden" name="low_stock" :value="preferences.low_stock ? '1' : '0'">
                        </div>

                        <!-- New Customers -->
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="user-plus" class="w-5 h-5 text-pink-600 dark:text-pink-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">New Customers</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">Get notified when new customers register</p>
                                </div>
                            </div>
                            <button type="button" @click="toggle('new_customers')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :class="preferences.new_customers ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="preferences.new_customers ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                            <input type="hidden" name="new_customers" :value="preferences.new_customers ? '1' : '0'">
                        </div>

                        <!-- Product Reviews -->
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="star" class="w-5 h-5 text-orange-600 dark:text-orange-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">Product Reviews</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">Get notified when customers leave product reviews</p>
                                </div>
                            </div>
                            <button type="button" @click="toggle('product_reviews')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :class="preferences.product_reviews ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="preferences.product_reviews ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                            <input type="hidden" name="product_reviews" :value="preferences.product_reviews ? '1' : '0'">
                        </div>

                        <!-- Refund Requests -->
                        <div class="flex items-center justify-between p-4 bg-stone-50 dark:bg-gray-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <i data-lucide="refresh-ccw" class="w-5 h-5 text-red-600 dark:text-red-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-stone-900 dark:text-white">Refund Requests</p>
                                    <p class="text-xs text-stone-600 dark:text-gray-400">Get notified when customers request refunds</p>
                                </div>
                            </div>
                            <button type="button" @click="toggle('refund_requests')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2" :class="preferences.refund_requests ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200" :class="preferences.refund_requests ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                            <input type="hidden" name="refund_requests" :value="preferences.refund_requests ? '1' : '0'">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-sm font-medium text-white rounded-xl shadow-lg transition-all duration-200 hover:from-purple-700 hover:to-pink-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

