@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-xl shadow-lg">
                    <i data-lucide="user" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-stone-900 dark:text-white">User Details - {{ $all_customer->first_name }} {{ $all_customer->last_name }}</h1>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">View and manage customer information</p>
                </div>
            </div>
            <a href="{{ admin_route('users.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm font-medium text-stone-700 transition-all duration-200 hover:bg-stone-50 hover:border-stone-300 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Users
            </a>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column - User Profile & Stats -->
        <div class="xl:col-span-1 space-y-8">
            <!-- User Profile Card -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                            <i data-lucide="user" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">User Profile</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Customer information and status</p>
</div>
                <div class="p-8">
            <div class="flex flex-col items-center text-center">
                <!-- Avatar -->
                        <div class="relative mb-6">
                            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">
                                    {{ substr($all_customer->first_name, 0, 1) }}{{ substr($all_customer->last_name, 0, 1) }}
                        </span>
                    </div>
                            @if($all_customer->email_verified_at)
                    <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-500 border-2 border-white dark:border-boxdark flex items-center justify-center">
                        <i data-lucide="check" class="w-3 h-3 text-white"></i>
                    </div>
                            @endif
                </div>

                <!-- User Details -->
                        <h3 class="text-xl font-bold text-stone-900 dark:text-white mb-2">
                            {{ $all_customer->first_name }} {{ $all_customer->last_name }}
                </h3>
                        <p class="text-stone-600 dark:text-stone-400 mb-4">{{ $all_customer->email }}</p>

                <!-- Status Badge -->
                <div class="mb-6">
                            @if($all_customer->email_verified_at)
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                            <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                            Unverified
                        </span>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 w-full">
                            <a href="{{ admin_route('users.edit', $all_customer) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-3 text-white hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit
                    </a>
                            <button class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-3 text-stone-700 hover:bg-stone-50 transition-all duration-200 dark:border-strokedark dark:bg-boxdark dark:text-white dark:hover:bg-gray-800">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        Email
                    </button>
                        </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-rose-50 to-pink-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Quick Stats</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Customer activity summary</p>
                </div>
                <div class="p-8">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                            <span class="text-stone-600 dark:text-stone-400">Total Orders</span>
                            <span class="font-semibold text-stone-900 dark:text-white">{{ $all_customer->orders->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                            <span class="text-stone-600 dark:text-stone-400">Total Spent</span>
                            <span class="font-semibold text-stone-900 dark:text-white">₱{{ number_format($all_customer->orders->where('status', '!=', 'cancelled')->sum('total_amount'), 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                            <span class="text-stone-600 dark:text-stone-400">Member Since</span>
                            <span class="font-semibold text-stone-900 dark:text-white">{{ $all_customer->created_at->format('M Y') }}</span>
                        </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Right Column - Main Content with Tabs -->
        <div class="xl:col-span-2">
        <!-- Tabs -->
            <div class="bg-white dark:bg-boxdark rounded-2xl shadow-xl border border-stone-200 dark:border-strokedark overflow-hidden" x-data="{ activeTab: 'overview' }">
                <div class="px-8 py-6 border-b border-stone-200 dark:border-strokedark bg-gradient-to-r from-cyan-50 to-teal-50 dark:from-gray-800 dark:to-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl">
                            <i data-lucide="layers" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-stone-900 dark:text-white">Customer Information</h3>
                    </div>
                    <p class="mt-1 text-sm text-stone-600 dark:text-gray-400">Detailed customer data and history</p>
                </div>
                
                <div class="px-8 py-6">
                    <!-- Tab Navigation -->
                    <div class="border-b border-stone-200 dark:border-strokedark mb-6">
                <nav class="-mb-px flex space-x-8">
                            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-stone-400 dark:hover:text-stone-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Overview
                    </button>
                            <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-stone-400 dark:hover:text-stone-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                                Orders ({{ $all_customer->orders->count() }})
                    </button>
                            <button @click="activeTab = 'addresses'" :class="activeTab === 'addresses' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-stone-500 hover:text-stone-700 hover:border-stone-300 dark:text-stone-400 dark:hover:text-stone-300'" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Addresses
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Personal Information -->
                                <div class="bg-stone-50 dark:bg-stone-800 rounded-xl p-6">
                                    <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-4">Personal Information</h4>
                            <div class="space-y-3">
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Full Name</label>
                                            <p class="text-stone-900 dark:text-white">{{ $all_customer->first_name }} {{ $all_customer->last_name }}</p>
                                </div>
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Email</label>
                                            <p class="text-stone-900 dark:text-white">{{ $all_customer->email }}</p>
                                </div>
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Phone</label>
                                            <p class="text-stone-900 dark:text-white">{{ $all_customer->phone ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Date of Birth</label>
                                            <p class="text-stone-900 dark:text-white">{{ $all_customer->date_of_birth ? $all_customer->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                                <div class="bg-stone-50 dark:bg-stone-800 rounded-xl p-6">
                                    <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-4">Account Information</h4>
                            <div class="space-y-3">
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Member Since</label>
                                            <p class="text-stone-900 dark:text-white">{{ $all_customer->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Last Login</label>
                                            <p class="text-stone-900 dark:text-white">{{ $all_customer->last_login_at ? $all_customer->last_login_at->format('M d, Y g:i A') : 'Never' }}</p>
                                </div>
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Email Verified</label>
                                            <p class="text-stone-900 dark:text-white">{{ $all_customer->email_verified_at ? 'Yes' : 'No' }}</p>
                                </div>
                                <div>
                                            <label class="text-sm font-medium text-stone-600 dark:text-stone-400">Status</label>
                                            <p class="text-stone-900 dark:text-white">{{ ucfirst($all_customer->status ?? 'active') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div x-show="activeTab === 'orders'" x-transition>
                            <div class="bg-stone-50 dark:bg-stone-800 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-stone-900 dark:text-white mb-4">Order History</h4>
                                @if($all_customer->orders->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                                <tr class="border-b border-stone-200 dark:border-strokedark">
                                                    <th class="text-left py-3 px-4 font-medium text-stone-900 dark:text-white">Order #</th>
                                                    <th class="text-left py-3 px-4 font-medium text-stone-900 dark:text-white">Date</th>
                                                    <th class="text-left py-3 px-4 font-medium text-stone-900 dark:text-white">Status</th>
                                                    <th class="text-left py-3 px-4 font-medium text-stone-900 dark:text-white">Total</th>
                                                    <th class="text-left py-3 px-4 font-medium text-stone-900 dark:text-white">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @foreach($all_customer->orders->take(10) as $order)
                                                    <tr class="border-b border-stone-200/50 dark:border-strokedark/50 hover:bg-stone-100 dark:hover:bg-stone-700/50 transition-colors duration-200">
                                                        <td class="py-3 px-4 text-stone-900 dark:text-white">#{{ $order->id }}</td>
                                                        <td class="py-3 px-4 text-stone-600 dark:text-stone-400">{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td class="py-3 px-4">
                                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                            @if($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                            @elseif($order->status === 'shipped') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                            @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                                @else bg-stone-100 text-stone-800 dark:bg-stone-900/30 dark:text-stone-400 @endif">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                        <td class="py-3 px-4 text-stone-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</td>
                                                    <td class="py-3 px-4">
                                                            <a href="{{ admin_route('orders.show', $order) }}" class="text-emerald-600 hover:text-emerald-700 transition-colors duration-200 font-medium">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                    @if($all_customer->orders->count() > 10)
                                    <div class="mt-4 text-center">
                                            <a href="{{ admin_route('orders.index', ['user_id' => $all_customer->id]) }}" class="text-emerald-600 hover:text-emerald-700 transition-colors duration-200 font-medium">
                                                View all {{ $all_customer->orders->count() }} orders
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                        <i data-lucide="shopping-cart" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                                        <p class="text-stone-600 dark:text-stone-400">No orders found</p>
                                </div>
                            @endif
                    </div>
                </div>

                <!-- Addresses Tab -->
                <div x-show="activeTab === 'addresses'" x-transition>
                            <div class="bg-stone-50 dark:bg-stone-800 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-stone-900 dark:text-white">Saved Addresses</h4>
                                    <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-blue-600 px-4 py-2 text-white hover:from-emerald-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add Address
                            </button>
                        </div>
                        <div class="text-center py-8">
                                    <i data-lucide="map-pin" class="w-12 h-12 text-stone-400 mx-auto mb-4"></i>
                                    <p class="text-stone-600 dark:text-stone-400">No saved addresses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    lucide.createIcons();
</script>
@endpush