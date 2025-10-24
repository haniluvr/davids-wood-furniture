@extends('admin.layouts.app')

@section('title', 'Advanced Settings')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Advanced Settings
    </h2>

    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium" href="{{ admin_route('dashboard') }}">Dashboard /</a>
            </li>
            <li>
                <a class="font-medium" href="{{ admin_route('settings.index') }}">Settings /</a>
            </li>
            <li class="font-medium text-primary">Advanced</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<div class="max-w-4xl mx-auto">
    <form action="{{ admin_route('settings.advanced.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- System Configuration -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">System Configuration</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Debug Mode -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="debug_mode"
                            value="1"
                            {{ old('debug_mode', setting('debug_mode', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Debug Mode</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enable debug mode for development</p>
                </div>

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
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Put the site in maintenance mode</p>
                </div>

                <!-- Cache Enabled -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="cache_enabled"
                            value="1"
                            {{ old('cache_enabled', setting('cache_enabled', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Enable Caching</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enable application caching for better performance</p>
                </div>

                <!-- Queue Processing -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="queue_processing"
                            value="1"
                            {{ old('queue_processing', setting('queue_processing', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Queue Processing</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Process background jobs via queue</p>
                </div>
            </div>
        </div>

        <!-- Performance Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Performance Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Cache TTL -->
                <div>
                    <label for="cache_ttl" class="mb-2.5 block text-black dark:text-white">
                        Cache TTL (minutes)
                    </label>
                    <input
                        type="number"
                        id="cache_ttl"
                        name="cache_ttl"
                        value="{{ old('cache_ttl', setting('cache_ttl', 60)) }}"
                        min="1"
                        max="1440"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('cache_ttl') border-red-500 @enderror"
                    />
                    @error('cache_ttl')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Session Timeout -->
                <div>
                    <label for="session_timeout" class="mb-2.5 block text-black dark:text-white">
                        Session Timeout (minutes)
                    </label>
                    <input
                        type="number"
                        id="session_timeout"
                        name="session_timeout"
                        value="{{ old('session_timeout', setting('session_timeout', 120)) }}"
                        min="5"
                        max="1440"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('session_timeout') border-red-500 @enderror"
                    />
                    @error('session_timeout')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Upload Size -->
                <div>
                    <label for="max_upload_size" class="mb-2.5 block text-black dark:text-white">
                        Max Upload Size (MB)
                    </label>
                    <input
                        type="number"
                        id="max_upload_size"
                        name="max_upload_size"
                        value="{{ old('max_upload_size', setting('max_upload_size', 10)) }}"
                        min="1"
                        max="100"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('max_upload_size') border-red-500 @enderror"
                    />
                    @error('max_upload_size')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- API Rate Limit -->
                <div>
                    <label for="api_rate_limit" class="mb-2.5 block text-black dark:text-white">
                        API Rate Limit (requests/minute)
                    </label>
                    <input
                        type="number"
                        id="api_rate_limit"
                        name="api_rate_limit"
                        value="{{ old('api_rate_limit', setting('api_rate_limit', 60)) }}"
                        min="10"
                        max="1000"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('api_rate_limit') border-red-500 @enderror"
                    />
                    @error('api_rate_limit')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Security Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Two-Factor Authentication -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="two_factor_auth"
                            value="1"
                            {{ old('two_factor_auth', setting('two_factor_auth', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Two-Factor Authentication</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Require 2FA for admin accounts</p>
                </div>

                <!-- IP Whitelist -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="ip_whitelist"
                            value="1"
                            {{ old('ip_whitelist', setting('ip_whitelist', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">IP Whitelist</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Restrict admin access to specific IPs</p>
                </div>

                <!-- Failed Login Attempts -->
                <div>
                    <label for="max_login_attempts" class="mb-2.5 block text-black dark:text-white">
                        Max Login Attempts
                    </label>
                    <input
                        type="number"
                        id="max_login_attempts"
                        name="max_login_attempts"
                        value="{{ old('max_login_attempts', setting('max_login_attempts', 5)) }}"
                        min="3"
                        max="20"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('max_login_attempts') border-red-500 @enderror"
                    />
                    @error('max_login_attempts')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Lockout Duration -->
                <div>
                    <label for="lockout_duration" class="mb-2.5 block text-black dark:text-white">
                        Account Lockout Duration (minutes)
                    </label>
                    <input
                        type="number"
                        id="lockout_duration"
                        name="lockout_duration"
                        value="{{ old('lockout_duration', setting('lockout_duration', 15)) }}"
                        min="5"
                        max="1440"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('lockout_duration') border-red-500 @enderror"
                    />
                    @error('lockout_duration')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Requirements -->
                <div class="md:col-span-2">
                    <label class="mb-2.5 block text-black dark:text-white">Password Requirements</label>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="require_uppercase"
                                    value="1"
                                    {{ old('require_uppercase', setting('require_uppercase', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Require Uppercase Letters</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="require_lowercase"
                                    value="1"
                                    {{ old('require_lowercase', setting('require_lowercase', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Require Lowercase Letters</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="require_numbers"
                                    value="1"
                                    {{ old('require_numbers', setting('require_numbers', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Require Numbers</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="require_symbols"
                                    value="1"
                                    {{ old('require_symbols', setting('require_symbols', true)) ? 'checked' : '' }}
                                    class="mr-2 rounded border-stroke dark:border-strokedark"
                                />
                                <span class="text-black dark:text-white">Require Special Characters</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Minimum Password Length -->
                <div>
                    <label for="min_password_length" class="mb-2.5 block text-black dark:text-white">
                        Minimum Password Length
                    </label>
                    <input
                        type="number"
                        id="min_password_length"
                        name="min_password_length"
                        value="{{ old('min_password_length', setting('min_password_length', 8)) }}"
                        min="6"
                        max="32"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('min_password_length') border-red-500 @enderror"
                    />
                    @error('min_password_length')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Backup Settings -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">Backup Settings</h4>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Auto Backup -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="auto_backup"
                            value="1"
                            {{ old('auto_backup', setting('auto_backup', true)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Automatic Backups</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enable automatic database backups</p>
                </div>

                <!-- Backup Frequency -->
                <div>
                    <label for="backup_frequency" class="mb-2.5 block text-black dark:text-white">
                        Backup Frequency
                    </label>
                    <select
                        id="backup_frequency"
                        name="backup_frequency"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('backup_frequency') border-red-500 @enderror"
                    >
                        <option value="daily" {{ old('backup_frequency', setting('backup_frequency', 'daily')) === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('backup_frequency', setting('backup_frequency', 'daily')) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('backup_frequency', setting('backup_frequency', 'daily')) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('backup_frequency')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Backup Retention -->
                <div>
                    <label for="backup_retention" class="mb-2.5 block text-black dark:text-white">
                        Backup Retention (days)
                    </label>
                    <input
                        type="number"
                        id="backup_retention"
                        name="backup_retention"
                        value="{{ old('backup_retention', setting('backup_retention', 30)) }}"
                        min="7"
                        max="365"
                        class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary @error('backup_retention') border-red-500 @enderror"
                    />
                    @error('backup_retention')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cloud Backup -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="cloud_backup"
                            value="1"
                            {{ old('cloud_backup', setting('cloud_backup', false)) ? 'checked' : '' }}
                            class="mr-2 rounded border-stroke dark:border-strokedark"
                        />
                        <span class="text-black dark:text-white">Cloud Backup</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Store backups in cloud storage</p>
                </div>
            </div>
        </div>

        <!-- System Actions -->
        <div class="rounded-sm border border-stroke bg-white px-7.5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
            <h4 class="text-lg font-semibold text-black dark:text-white mb-6">System Actions</h4>
            
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <button type="button" class="flex items-center justify-center gap-2 rounded-lg border border-blue-500 bg-blue-500 px-6 py-3 text-white hover:bg-blue-600 transition-colors duration-200">
                    <i data-lucide="database" class="w-4 h-4"></i>
                    Clear Cache
                </button>
                <button type="button" class="flex items-center justify-center gap-2 rounded-lg border border-green-500 bg-green-500 px-6 py-3 text-white hover:bg-green-600 transition-colors duration-200">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Create Backup
                </button>
                <button type="button" class="flex items-center justify-center gap-2 rounded-lg border border-orange-500 bg-orange-500 px-6 py-3 text-white hover:bg-orange-600 transition-colors duration-200">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Restart Queue
                </button>
                <button type="button" class="flex items-center justify-center gap-2 rounded-lg border border-red-500 bg-red-500 px-6 py-3 text-white hover:bg-red-600 transition-colors duration-200">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    Clear Logs
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
@endsection
