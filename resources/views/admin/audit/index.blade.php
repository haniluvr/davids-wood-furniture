@extends('admin.layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="min-h-screen bg-white dark:bg-boxdark">
    <!-- Header -->
    <div class="bg-white dark:bg-boxdark shadow-sm border-b border-stroke dark:border-strokedark">
        <div class="mx-auto max-w-screen-2xl">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-black dark:text-white">Audit Log</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Complete record of system activities and user actions</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ admin_route('audit.export') }}?{{ http_build_query(request()->query()) }}" 
                       class="inline-flex items-center px-4 py-2 border border-stroke dark:border-strokedark rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-boxdark hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-screen-2xl py-6">
        <!-- Statistics Cards -->
        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Total Logs -->
            <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Logs</p>
                        <p class="text-2xl font-bold text-black dark:text-white">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                        <i data-lucide="file-text" class="h-6 w-6 text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <!-- Today -->
            <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Today</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['today']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                        <i data-lucide="calendar" class="h-6 w-6 text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- This Week -->
            <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Week</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($stats['this_week']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                        <i data-lucide="activity" class="h-6 w-6 text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">This Month</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($stats['this_month']) }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
                        <i data-lucide="trending-up" class="h-6 w-6 text-orange-600 dark:text-orange-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="mb-6 rounded-xl border border-stroke bg-white p-4 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Quick Filters:</span>
                <a href="{{ admin_route('audit.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ !request('category') ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    All
                </a>
                <a href="{{ admin_route('audit.index', ['category' => 'security']) }}" 
                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('category') === 'security' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <i data-lucide="shield" class="w-4 h-4 mr-1.5"></i>
                    Security
                </a>
                <a href="{{ admin_route('audit.index', ['category' => 'orders']) }}" 
                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('category') === 'orders' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <i data-lucide="shopping-cart" class="w-4 h-4 mr-1.5"></i>
                    Orders
                </a>
                <a href="{{ admin_route('audit.index', ['category' => 'inventory']) }}" 
                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('category') === 'inventory' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <i data-lucide="package" class="w-4 h-4 mr-1.5"></i>
                    Inventory
                </a>
                <a href="{{ admin_route('audit.index', ['category' => 'users']) }}" 
                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('category') === 'users' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    <i data-lucide="users" class="w-4 h-4 mr-1.5"></i>
                    Users
                </a>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="mb-6 rounded-xl border border-stroke bg-white p-6 shadow-sm dark:border-strokedark dark:bg-boxdark">
            <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Filters</h3>
            <form method="GET" action="{{ admin_route('audit.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Search -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search actions, users, IP..." 
                               class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- User Search -->
                    <div class="relative">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">User</label>
                        <div class="relative">
                            <input type="text" 
                                   id="user-search" 
                                   placeholder="Search by name or email..."
                                   value="{{ request('user_id') ? ($adminUsers->where('id', request('user_id'))->first() ? $adminUsers->where('id', request('user_id'))->first()->full_name . ' (' . $adminUsers->where('id', request('user_id'))->first()->email . ')' : '') : '' }}"
                                   class="w-full rounded-lg border border-stroke px-3 py-2 pl-10 text-sm dark:border-strokedark dark:bg-form-input focus:ring-2 focus:ring-primary">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <button type="button" id="clear-user-search" class="absolute right-2 top-1/2 transform -translate-y-1/2 hidden text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <input type="hidden" name="user_id" id="selected-user-id" value="{{ request('user_id') }}">
                        <div id="user-results" class="hidden absolute left-0 right-0 z-50 w-full mt-1 border border-stroke rounded-lg max-h-60 overflow-y-auto bg-white shadow-lg dark:border-strokedark dark:bg-boxdark"></div>
                    </div>

                    <!-- Action -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Action</label>
                        <select name="action" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input focus:ring-2 focus:ring-primary">
                            <option value="">All Actions</option>
                            <optgroup label="Orders">
                                <option value="order.created" {{ request('action') === 'order.created' ? 'selected' : '' }}>Order Created</option>
                                <option value="order.updated" {{ request('action') === 'order.updated' ? 'selected' : '' }}>Order Updated</option>
                                <option value="order.deleted" {{ request('action') === 'order.deleted' ? 'selected' : '' }}>Order Deleted</option>
                                <option value="order.status_updated" {{ request('action') === 'order.status_updated' ? 'selected' : '' }}>Order Status Updated</option>
                                <option value="order.approved" {{ request('action') === 'order.approved' ? 'selected' : '' }}>Order Approved</option>
                                <option value="order.rejected" {{ request('action') === 'order.rejected' ? 'selected' : '' }}>Order Rejected</option>
                                <option value="order.refund_issued" {{ request('action') === 'order.refund_issued' ? 'selected' : '' }}>Order Refund Issued</option>
                                <option value="order.fulfillment_status_updated" {{ request('action') === 'order.fulfillment_status_updated' ? 'selected' : '' }}>Fulfillment Status Updated</option>
                            </optgroup>
                            <optgroup label="Customers">
                                <option value="customer.created" {{ request('action') === 'customer.created' ? 'selected' : '' }}>Customer Created</option>
                                <option value="customer.updated" {{ request('action') === 'customer.updated' ? 'selected' : '' }}>Customer Updated</option>
                                <option value="customer.deleted" {{ request('action') === 'customer.deleted' ? 'selected' : '' }}>Customer Deleted</option>
                                <option value="customer.suspended" {{ request('action') === 'customer.suspended' ? 'selected' : '' }}>Customer Suspended</option>
                                <option value="customer.unsuspended" {{ request('action') === 'customer.unsuspended' ? 'selected' : '' }}>Customer Unsuspended</option>
                                <option value="customer.email_verified" {{ request('action') === 'customer.email_verified' ? 'selected' : '' }}>Email Verified</option>
                                <option value="customer.email_unverified" {{ request('action') === 'customer.email_unverified' ? 'selected' : '' }}>Email Unverified</option>
                                <option value="customer.password_reset" {{ request('action') === 'customer.password_reset' ? 'selected' : '' }}>Password Reset</option>
                            </optgroup>
                            <optgroup label="Admin Users">
                                <option value="admin_user.created" {{ request('action') === 'admin_user.created' ? 'selected' : '' }}>Admin User Created</option>
                                <option value="admin_user.deleted" {{ request('action') === 'admin_user.deleted' ? 'selected' : '' }}>Admin User Deleted</option>
                                <option value="admin_user.permissions_updated" {{ request('action') === 'admin_user.permissions_updated' ? 'selected' : '' }}>Permissions Updated</option>
                                <option value="admin_user.permissions_reset" {{ request('action') === 'admin_user.permissions_reset' ? 'selected' : '' }}>Permissions Reset</option>
                            </optgroup>
                            <optgroup label="Inventory">
                                <option value="inventory.adjusted" {{ request('action') === 'inventory.adjusted' ? 'selected' : '' }}>Stock Adjusted</option>
                                <option value="inventory.added" {{ request('action') === 'inventory.added' ? 'selected' : '' }}>Stock Added</option>
                                <option value="inventory.removed" {{ request('action') === 'inventory.removed' ? 'selected' : '' }}>Stock Removed</option>
                            </optgroup>
                            <optgroup label="Shipping">
                                <option value="shipping_method.created" {{ request('action') === 'shipping_method.created' ? 'selected' : '' }}>Shipping Method Created</option>
                                <option value="shipping_method.updated" {{ request('action') === 'shipping_method.updated' ? 'selected' : '' }}>Shipping Method Updated</option>
                                <option value="shipping_method.deleted" {{ request('action') === 'shipping_method.deleted' ? 'selected' : '' }}>Shipping Method Deleted</option>
                                <option value="shipping_method.status_toggled" {{ request('action') === 'shipping_method.status_toggled' ? 'selected' : '' }}>Shipping Status Toggled</option>
                                <option value="shipping_method.reordered" {{ request('action') === 'shipping_method.reordered' ? 'selected' : '' }}>Shipping Methods Reordered</option>
                            </optgroup>
                            <optgroup label="Payment">
                                <option value="payment_gateway.created" {{ request('action') === 'payment_gateway.created' ? 'selected' : '' }}>Payment Gateway Created</option>
                                <option value="payment_gateway.updated" {{ request('action') === 'payment_gateway.updated' ? 'selected' : '' }}>Payment Gateway Updated</option>
                                <option value="payment_gateway.deleted" {{ request('action') === 'payment_gateway.deleted' ? 'selected' : '' }}>Payment Gateway Deleted</option>
                                <option value="payment_gateway.status_toggled" {{ request('action') === 'payment_gateway.status_toggled' ? 'selected' : '' }}>Payment Status Toggled</option>
                                <option value="payment_gateway.mode_toggled" {{ request('action') === 'payment_gateway.mode_toggled' ? 'selected' : '' }}>Payment Mode Toggled</option>
                                <option value="payment_gateway.reordered" {{ request('action') === 'payment_gateway.reordered' ? 'selected' : '' }}>Payment Gateways Reordered</option>
                            </optgroup>
                            <optgroup label="Settings">
                                <option value="settings.general_updated" {{ request('action') === 'settings.general_updated' ? 'selected' : '' }}>General Settings Updated</option>
                                <option value="settings.email_updated" {{ request('action') === 'settings.email_updated' ? 'selected' : '' }}>Email Settings Updated</option>
                            </optgroup>
                            <optgroup label="Returns & Repairs">
                                <option value="return_repair.created" {{ request('action') === 'return_repair.created' ? 'selected' : '' }}>Return/Repair Created</option>
                                <option value="return_repair.approved" {{ request('action') === 'return_repair.approved' ? 'selected' : '' }}>Return/Repair Approved</option>
                                <option value="return_repair.rejected" {{ request('action') === 'return_repair.rejected' ? 'selected' : '' }}>Return/Repair Rejected</option>
                                <option value="return_repair.received" {{ request('action') === 'return_repair.received' ? 'selected' : '' }}>Return/Repair Received</option>
                                <option value="return_repair.refund_processed" {{ request('action') === 'return_repair.refund_processed' ? 'selected' : '' }}>Refund Processed</option>
                                <option value="return_repair.completed" {{ request('action') === 'return_repair.completed' ? 'selected' : '' }}>Return/Repair Completed</option>
                            </optgroup>
                            @foreach($actions as $action)
                                @if(!str_contains($action, 'order.') && !str_contains($action, 'customer.') && !str_contains($action, 'admin_user.') && !str_contains($action, 'inventory.') && !str_contains($action, 'shipping_method.') && !str_contains($action, 'payment_gateway.') && !str_contains($action, 'settings.') && !str_contains($action, 'return_repair.'))
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace(['_', '.'], ' ', $action)) }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Model -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Resource Type</label>
                        <select name="model" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input focus:ring-2 focus:ring-primary">
                            <option value="">All Resources</option>
                            <option value="App\Models\Order" {{ request('model') === 'App\Models\Order' ? 'selected' : '' }}>Order</option>
                            <option value="App\Models\User" {{ request('model') === 'App\Models\User' ? 'selected' : '' }}>User</option>
                            <option value="App\Models\Admin" {{ request('model') === 'App\Models\Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="App\Models\Product" {{ request('model') === 'App\Models\Product' ? 'selected' : '' }}>Product</option>
                            <option value="App\Models\ShippingMethod" {{ request('model') === 'App\Models\ShippingMethod' ? 'selected' : '' }}>Shipping Method</option>
                            <option value="App\Models\PaymentGateway" {{ request('model') === 'App\Models\PaymentGateway' ? 'selected' : '' }}>Payment Gateway</option>
                            <option value="App\Models\ReturnRepair" {{ request('model') === 'App\Models\ReturnRepair' ? 'selected' : '' }}>Return/Repair</option>
                            <option value="App\Models\Category" {{ request('model') === 'App\Models\Category' ? 'selected' : '' }}>Category</option>
                            <option value="App\Models\InventoryMovement" {{ request('model') === 'App\Models\InventoryMovement' ? 'selected' : '' }}>Inventory Movement</option>
                            @foreach($models as $model)
                                @if(!in_array($model, ['App\Models\Order', 'App\Models\User', 'App\Models\Admin', 'App\Models\Product', 'App\Models\ShippingMethod', 'App\Models\PaymentGateway', 'App\Models\ReturnRepair', 'App\Models\Category', 'App\Models\InventoryMovement']))
                                <option value="{{ $model }}" {{ request('model') === $model ? 'selected' : '' }}>
                                    {{ class_basename($model) }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                               class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Date To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                               class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- User Type -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">User Type</label>
                        <select name="user_type" class="w-full rounded-lg border border-stroke px-3 py-2 text-sm dark:border-strokedark dark:bg-form-input focus:ring-2 focus:ring-primary">
                            <option value="">All Types</option>
                            <option value="admin" {{ request('user_type') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('user_type') === 'user' ? 'selected' : '' }}>Customer</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-stroke dark:border-strokedark">
                    <button type="submit" class="rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white hover:bg-opacity-90">
                        Apply Filters
                    </button>
                    <a href="{{ admin_route('audit.index') }}" class="inline-flex items-center justify-center rounded-lg border border-stroke px-4 py-2.5 text-sm hover:bg-gray-50 dark:border-strokedark dark:hover:bg-gray-800">
                        <i data-lucide="x" class="h-4 w-4 mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Audit Log Table -->
        <div class="rounded-xl border border-stroke bg-white shadow-sm dark:border-strokedark dark:bg-boxdark">
            <div class="flex items-center justify-between border-b border-stroke p-6 dark:border-strokedark">
                <h3 class="text-lg font-semibold text-black dark:text-white">Activity Log</h3>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ $auditLogs->firstItem() ?? 0 }} - {{ $auditLogs->lastItem() ?? 0 }} of {{ $auditLogs->total() }} entries
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-stroke bg-gray-50 dark:border-strokedark dark:bg-gray-800">
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Timestamp</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">User</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Action</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Description</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Resource</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Changes</th>
                            <th class="py-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stroke dark:divide-strokedark">
                        @forelse($auditLogs as $log)
                        @php
                            $criticality = $log->criticality ?? 'low';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors {{ $criticality === 'high' ? 'bg-red-50/50 dark:bg-red-900/10 border-l-4 border-l-red-500' : ($criticality === 'medium' ? 'bg-yellow-50/50 dark:bg-yellow-900/10 border-l-4 border-l-yellow-500' : '') }}">
                            <td class="py-4 px-4">
                                @if($log->created_at)
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $log->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $log->created_at->format('H:i:s') }} UTC
                                </div>
                                @else
                                <div class="text-sm font-medium text-gray-400">N/A</div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $log->user_display ?? 'System' }}
                                </div>
                                @if($log->user_email)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $log->user_email }}
                                </div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->action_badge_color ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action ?? 'N/A')) }}
                                </span>
                                @if(($log->criticality ?? 'low') === 'high')
                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200" title="High Risk Action">
                                    🔴
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-900 dark:text-white max-w-xs">
                                    {{ $log->description ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($log->model)
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ class_basename($log->model) }}
                                </div>
                                @if($log->model_id)
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    ID: {{ $log->model_id }}
                                </div>
                                @endif
                                @else
                                <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($log->formatted_changes)
                                <div class="text-xs text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $log->formatted_changes }}">
                                    {{ Str::limit($log->formatted_changes, 50) }}
                                </div>
                                @else
                                <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $log->ip_address ?? 'N/A' }}
                                </div>
                                @if($log->user_agent)
                                <div class="text-xs text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 30) }}
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="file-x" class="h-12 w-12 text-gray-400 mb-4"></i>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">No audit logs found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Try adjusting your filters</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($auditLogs->hasPages())
            <div class="border-t border-stroke px-6 py-4 dark:border-strokedark">
                {{ $auditLogs->links('admin.partials.pagination') }}
            </div>
            @endif
        </div>

        <!-- Retention Policy Notice -->
        <div class="mt-6 rounded-xl border border-stroke bg-blue-50 p-4 dark:border-strokedark dark:bg-blue-900/20">
            <div class="flex items-start">
                <i data-lucide="info" class="h-5 w-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0"></i>
                <div>
                    <p class="text-sm font-medium text-blue-900 dark:text-blue-200">Log Retention Policy</p>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">Audit logs are retained for 365 days. Critical actions (deletions, role changes, refunds) are highlighted for quick identification.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const userSearch = document.getElementById('user-search');
        const selectedUserId = document.getElementById('selected-user-id');
        const userResults = document.getElementById('user-results');
        const clearUserBtn = document.getElementById('clear-user-search');
        let searchTimeout;

        // Show/hide clear button
        function toggleClearButton() {
            if (userSearch.value.trim() || selectedUserId.value) {
                clearUserBtn.classList.remove('hidden');
            } else {
                clearUserBtn.classList.add('hidden');
            }
        }

        toggleClearButton();

        // Clear user selection
        clearUserBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userSearch.value = '';
            selectedUserId.value = '';
            userResults.classList.add('hidden');
            toggleClearButton();
        });

        // Close results when clicking outside
        document.addEventListener('click', function(e) {
            if (!userSearch.closest('.relative').contains(e.target)) {
                userResults.classList.add('hidden');
            }
        });

        // User search functionality
        userSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            toggleClearButton();

            if (query.length < 2) {
                userResults.classList.add('hidden');
                if (!query) {
                    selectedUserId.value = '';
                }
                return;
            }

            searchTimeout = setTimeout(() => {
                searchUsers(query);
            }, 300);
        });

        userSearch.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                searchUsers(this.value.trim());
            }
        });

        function searchUsers(query) {
            const url = '{{ admin_route("audit.search-users") }}?q=' + encodeURIComponent(query);
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                displayUserResults(data);
            })
            .catch(error => {
                console.error('Error searching users:', error);
                userResults.innerHTML = '<div class="p-4 text-center text-red-500">Error searching users</div>';
                userResults.classList.remove('hidden');
            });
        }

        function displayUserResults(users) {
            if (users.length === 0) {
                userResults.innerHTML = '<div class="p-4 text-center text-gray-500 dark:text-gray-400">No users found</div>';
            } else {
                userResults.innerHTML = users.map(user => `
                    <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer border-b border-stroke last:border-b-0 dark:border-strokedark" 
                         onclick="selectUser(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}')">
                        <div class="font-medium text-black dark:text-white">${user.name}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">${user.email}</div>
                        ${user.role ? `<div class="text-xs text-gray-400 dark:text-gray-500">${user.role}</div>` : ''}
                    </div>
                `).join('');
            }
            userResults.classList.remove('hidden');
            
            // Re-initialize Lucide icons for dynamically added content
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        window.selectUser = function(id, name, email) {
            selectedUserId.value = id;
            userSearch.value = name + ' (' + email + ')';
            userResults.classList.add('hidden');
            toggleClearButton();
        };
    });
</script>
@endpush
@endsection
