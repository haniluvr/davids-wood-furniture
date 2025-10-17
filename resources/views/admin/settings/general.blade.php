@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        General Settings
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ route('admin.dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ route('admin.settings.index') }}">Settings /</a>
            </li>
            <li class="font-medium text-primary">General</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.settings.general.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Site Information -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Site Information</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Site Name -->
                <div>
                    <label for="site_name" class="mb-2.5 block text-black dark:text-white">
                        Site Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="site_name"
                        name="site_name"
                        value="{{ old('site_name', setting('site_name', 'David\'s Wood Furniture')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('site_name') border-red-500 @enderror"
                        required
                    />
                    @error('site_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site Tagline -->
                <div>
                    <label for="site_tagline" class="mb-2.5 block text-black dark:text-white">
                        Site Tagline
                    </label>
                    <input
                        type="text"
                        id="site_tagline"
                        name="site_tagline"
                        value="{{ old('site_tagline', setting('site_tagline', 'Handcrafted Wood Furniture')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('site_tagline') border-red-500 @enderror"
                    />
                    @error('site_tagline')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site URL -->
                <div>
                    <label for="site_url" class="mb-2.5 block text-black dark:text-white">
                        Site URL <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="url"
                        id="site_url"
                        name="site_url"
                        value="{{ old('site_url', setting('site_url', url('/'))) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('site_url') border-red-500 @enderror"
                        required
                    />
                    @error('site_url')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admin Email -->
                <div>
                    <label for="admin_email" class="mb-2.5 block text-black dark:text-white">
                        Admin Email <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="admin_email"
                        name="admin_email"
                        value="{{ old('admin_email', setting('admin_email', 'admin@davidswood.com')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('admin_email') border-red-500 @enderror"
                        required
                    />
                    @error('admin_email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Contact Information</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Business Name -->
                <div>
                    <label for="business_name" class="mb-2.5 block text-black dark:text-white">
                        Business Name
                    </label>
                    <input
                        type="text"
                        id="business_name"
                        name="business_name"
                        value="{{ old('business_name', setting('business_name', 'David\'s Wood Furniture')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('business_name') border-red-500 @enderror"
                    />
                    @error('business_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="mb-2.5 block text-black dark:text-white">
                        Phone Number
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', setting('phone', '+1 (555) 123-4567')) }}"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('phone') border-red-500 @enderror"
                    />
                    @error('phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="mb-2.5 block text-black dark:text-white">
                        Business Address
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('address') border-red-500 @enderror"
                    >{{ old('address', setting('address', '123 Wood Street\nCraftville, CV 12345\nUnited States')) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Currency & Localization -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Currency & Localization</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Currency -->
                <div>
                    <label for="currency" class="mb-2.5 block text-black dark:text-white">
                        Default Currency
                    </label>
                    <select
                        id="currency"
                        name="currency"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('currency') border-red-500 @enderror"
                    >
                        <option value="USD" {{ old('currency', setting('currency', 'USD')) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency', setting('currency', 'USD')) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ old('currency', setting('currency', 'USD')) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        <option value="CAD" {{ old('currency', setting('currency', 'USD')) === 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timezone -->
                <div>
                    <label for="timezone" class="mb-2.5 block text-black dark:text-white">
                        Timezone
                    </label>
                    <select
                        id="timezone"
                        name="timezone"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('timezone') border-red-500 @enderror"
                    >
                        <option value="America/New_York" {{ old('timezone', setting('timezone', 'America/New_York')) === 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                        <option value="America/Chicago" {{ old('timezone', setting('timezone', 'America/New_York')) === 'America/Chicago' ? 'selected' : '' }}>Central Time (CT)</option>
                        <option value="America/Denver" {{ old('timezone', setting('timezone', 'America/New_York')) === 'America/Denver' ? 'selected' : '' }}>Mountain Time (MT)</option>
                        <option value="America/Los_Angeles" {{ old('timezone', setting('timezone', 'America/New_York')) === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (PT)</option>
                    </select>
                    @error('timezone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Format -->
                <div>
                    <label for="date_format" class="mb-2.5 block text-black dark:text-white">
                        Date Format
                    </label>
                    <select
                        id="date_format"
                        name="date_format"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('date_format') border-red-500 @enderror"
                    >
                        <option value="Y-m-d" {{ old('date_format', setting('date_format', 'Y-m-d')) === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                        <option value="m/d/Y" {{ old('date_format', setting('date_format', 'Y-m-d')) === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                        <option value="d/m/Y" {{ old('date_format', setting('date_format', 'Y-m-d')) === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        <option value="M d, Y" {{ old('date_format', setting('date_format', 'Y-m-d')) === 'M d, Y' ? 'selected' : '' }}>Jan 1, 2024</option>
                    </select>
                    @error('date_format')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="mb-2.5 block text-black dark:text-white">
                        Default Language
                    </label>
                    <select
                        id="language"
                        name="language"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('language') border-red-500 @enderror"
                    >
                        <option value="en" {{ old('language', setting('language', 'en')) === 'en' ? 'selected' : '' }}>English</option>
                        <option value="es" {{ old('language', setting('language', 'en')) === 'es' ? 'selected' : '' }}>Spanish</option>
                        <option value="fr" {{ old('language', setting('language', 'en')) === 'fr' ? 'selected' : '' }}>French</option>
                        <option value="de" {{ old('language', setting('language', 'en')) === 'de' ? 'selected' : '' }}>German</option>
                    </select>
                    @error('language')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Site Features -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Site Features</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Maintenance Mode -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="maintenance_mode"
                            value="1"
                            {{ old('maintenance_mode', setting('maintenance_mode', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Maintenance Mode</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enable maintenance mode to temporarily disable the site</p>
                </div>

                <!-- User Registration -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="user_registration"
                            value="1"
                            {{ old('user_registration', setting('user_registration', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Allow User Registration</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Allow new users to register accounts</p>
                </div>

                <!-- Email Verification -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="email_verification"
                            value="1"
                            {{ old('email_verification', setting('email_verification', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Require Email Verification</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Require users to verify their email address</p>
                </div>

                <!-- Guest Checkout -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="guest_checkout"
                            value="1"
                            {{ old('guest_checkout', setting('guest_checkout', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Allow Guest Checkout</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Allow customers to checkout without creating an account</p>
                </div>

                <!-- Product Reviews -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="product_reviews"
                            value="1"
                            {{ old('product_reviews', setting('product_reviews', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Enable Product Reviews</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Allow customers to review products</p>
                </div>

                <!-- Wishlist -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="wishlist"
                            value="1"
                            {{ old('wishlist', setting('wishlist', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Enable Wishlist</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Allow customers to save products to wishlist</p>
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
